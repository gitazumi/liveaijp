<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Chat;
use App\Models\Faq;
use App\Models\Information;

class CallChatGptApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    protected $messages;
    protected $userId;
    protected $conversationId;
    protected $openaiApiKey;

    public function __construct($messages, $userId, $conversationId, $openaiApiKey)
    {
        $this->messages = $messages;
        $this->userId = $userId;
        $this->conversationId = $conversationId;
        $this->openaiApiKey = $openaiApiKey;
    }

    public function handle()
    {
        // メッセージが空の場合、ジョブを終了
        if (empty($this->messages)) {
            Log::error('Messages are empty. Cannot send to ChatGPT API.');
            $this->fail('Messages are empty.');
            return;
        }

        Log::info('CallChatGptApi job started.', [
            'messages' => $this->messages,
            'userId' => $this->userId,
            'conversationId' => $this->conversationId,
            'openaiApiKey' => $this->openaiApiKey
        ]);

        $url = "https://api.openai.com/v1/chat/completions";

        // ユーザーの店舗情報とFAQを取得
        $storeInformation = Information::where('user_id', $this->userId)->first();
        if (!$storeInformation) {
            Log::error('Store information not found for user ' . $this->userId);
            $this->fail('Store information not found');
            return;
        }
        $storeInformation = json_encode($storeInformation);

        $faqs = Faq::where('user_id', $this->userId)->get();
        if ($faqs->isEmpty()) {
            Log::error('No FAQs found for user ' . $this->userId);
            $this->fail('No FAQs found');
            return;
        }
        $faqContent = $faqs->map(function ($faq) {
            return "- {$faq->question}: {$faq->answer}";
        })->join("\n");

        // ChatGPTに送信するデータ
        $data = [
            "model" => 'gpt-4',
            "messages" => array_merge(
                [
                    [
                        'role' => 'system',
                        'content' => "You are a chatbot trained to answer based on the following FAQs:\n\nFAQs:\n$faqContent\n\n. Answer questions as if you are their assistant. your company information: $storeInformation"
                    ]
                ],
                $this->messages
            ),
            "max_tokens" => 1000,
            "temperature" => 0.7,
        ];

        Log::info('Sending request to ChatGPT API.', ['data' => substr(json_encode($data), 0, 500)]);

        // cURLでChatGPT APIにリクエスト
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->openaiApiKey}",
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => 300, 
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            Log::error('ChatGPT API Error: ' . $error);
            $this->fail($error);
            return;
        }

        if ($httpCode !== 200) {
            Log::error('ChatGPT API HTTP Error', ['http_code' => $httpCode, 'response' => $response]);
            $this->fail("HTTP error: $httpCode");
            return;
        }

        $responseData = json_decode($response, true);
        Log::info('Received response from ChatGPT API.', ['response' => $responseData]);

        if (isset($responseData['error'])) {
            Log::error('ChatGPT API Error: ' . $responseData['error']['message']);
            $this->fail($responseData['error']['message']);
            return;
        }

        // APIの応答からメッセージを取得
        $aiResponse = $responseData['choices'][0]['message']['content'];
        $messageId = $responseData['id'];

        // AIの応答をデータベースに保存
        Chat::create([
            'conversation_id' => $this->conversationId,
            'message' => $aiResponse,
            'openai_message_id' => $messageId,
            'send_by' => 0
        ]);

        Log::info('ChatGPT API Response saved to database.', ['response' => $aiResponse]);

        // ジョブが完了したので削除
        $this->delete();
    }
}
