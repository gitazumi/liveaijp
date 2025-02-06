<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private $openai_api_key;
    private $model = 'gpt-4';
    private $max_tokens = 3500;
    private $temperature = 0.7;

    public function __construct()
    {
        $this->openai_api_key = env('OPENAI_API_KEY', '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');
    }
    function history()
    {
        return view('admin.chat.history');
    }
    function chat()
    {
        return view('admin.chat.chat');
    }
    function chatBot()
    {
        return view('admin.chat.bot');
    }

    function message(Request $request)
    {
        $this->storeMessage($request->message);
        // $detailedPrompt = ;
        $prompt = $request->message;
        $openai_api_key = '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';
        $url = "https://api.openai.com/v1/chat/completions";
        $data = [
            "model" => "gpt-4",
            "messages" => [
                // [
                //     "role" => "system",
                //     "content" => $detailedPrompt
                // ],
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ],
            "max_tokens" => 3500,
            "temperature" => 0.7,
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $openai_api_key",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return response()->json(['error' => curl_error($ch)], 500);
        }

        curl_close($ch);

        $responseData = json_decode($response, true);
        if (isset($responseData['error'])) {
            return response()->json(['error' => $responseData['error']['message']], 500);
        }

        $this->storeMessage($responseData['choices'][0]['message']['content'], $responseData['id'], true);

        return response()->json(['error' => false, 'response' => $responseData['choices'][0]['message']['content']], 200);
    }

    public function storeMessage($message, $chat_id = null, $AI = false)
    {
        if ($AI) {
            Chat::create([
                'chat_id' => $chat_id,
                'message' => $message,
                'send_by' => 0
            ]);
        } else {
            Chat::create([
                'chat_id' => $chat_id,
                'message' => $message,
            ]);
        }
    }
}
