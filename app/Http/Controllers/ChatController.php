<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Chat;
use App\Models\Conversation;
use App\Models\Faq;
use App\Models\Information;
use App\Models\User;
use Google\Client;
use Google\Service\Calendar;

class ChatController extends Controller
{
    private $openai_api_key;
    private $model = 'gpt-3.5-turbo';
    private $max_tokens = 1000;
    private $temperature = 0.7;

    public function __construct()
    {
        $this->openai_api_key = "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";
    }

    private function getSessionId(Request $request)
    {
        if (!$request->has('chat_session_id')) {
            return Str::uuid()->toString();
        }
        return $request->input('chat_session_id');
    }

    public function getChatHistory(Request $request)
    {
     	$sessionId = $this->getSessionId($request);
        $conversation = Conversation::where('session_id', $sessionId)->first();

        if (!$conversation) {
            return response()->json([
                'messages' => []
            ]);
        }

        $messages = Chat::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'message' => $chat->message,
                    'send_by' => $chat->send_by ? 'user' : 'ai',
                    'created_at' => $chat->created_at->format('Y-m-d H:i:s')
                ];
            });

        return response()->json([
            'messages' => $messages
        ]);
    }

    public function message(Request $request)
    {
        try {
            if (Auth::id()) {
                $userId = Auth::id();
            } else {
                $user = User::where('chatbot_token', $request->header('X-Chatbot-Token'))->first();
                if ($user) {
                    $userId = $user->id;
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Invalid chatbot token.'
                    ], 500);
                }
                if (!$userId) {
                    return response()->json([
                        'error' => true,
                        'response' => $request->header('X-Chatbot-Token')
                    ]);
                }
            }
            if ($request->conversation_id) {
                $sessionId = $request->conversation_id;
            } else {
                $sessionId = $this->getSessionId($request);
            }
            $conversation = $this->getOrCreateConversation($sessionId, $request->message, $userId);
            // is_test フラグがある場合は履歴を保存しない
            if (!$request->has('is_test') || !$request->is_test) {
                $this->storeMessage([
                    'conversation_id' => $conversation->id,
                    'message' => $request->message,
                    'send_by' => 1
                ]);
            }

            $contextMessages = $this->getConversationContext($conversation->id);
            $start = microtime(true);
            $aiResponse = $this->getAiResponse($contextMessages, $userId);
            $end = microtime(true);
            $executionTime = $end - $start;
            Log::info('ChatGPT API Execution Time: ' . $executionTime . ' seconds');

            if (isset($aiResponse['error'])) {
                throw new \Exception($aiResponse['error']);
            }

            // is_test フラグがある場合は履歴を保存しない
            if (!$request->has('is_test') || !$request->is_test) {
                $this->storeMessage([
                    'conversation_id' => $conversation->id,
                    'message' => $aiResponse['message'],
                    'openai_message_id' => $aiResponse['message_id'],
                    'send_by' => 0
                ]);
            }

            $processedResponse = $this->renderHyperlinks($aiResponse['message']);

            return response()->json([
                'error' => false,
                'response' => $processedResponse
            ]);
        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'An error occurred while processing your message.',
                'issue' => $e->getMessage()
            ], 500);
        }
    }

    private function renderHyperlinks($text)
    {
        // URLを抽出する正規表現
        $urlRegex = '/\bhttps?:\/\/[a-zA-Z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+/u';

        return preg_replace_callback($urlRegex, function ($matches) {
            $url = $matches[0];

            // URLの末尾に `)` や `。` や `,` がある場合、それらを削除
            while (preg_match('/[),。,\s]$/u', $url)) {
                $url = substr($url, 0, -1);
            }

            return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '</a>';
        }, $text);
    }


    private function getOrCreateConversation($sessionId, $title, $userId)
    {
        return Conversation::firstOrCreate(
            [
                'session_id' => $sessionId,
            ],
            ['title' => $title, 'user_id' => $userId]
        );
    }

    private function storeMessage($data)
    {
        return Chat::create($data);
    }

    private function getConversationContext($conversationId, $limit = 10)
    {
        $recentMessages = Chat::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();

        $messages = [];
        foreach ($recentMessages as $message) {
            $messages[] = [
                'role' => $message->send_by ? 'user' : 'assistant',
                'content' => $message->message
            ];
        }

        return $messages;
    }

    private function getAiResponse($messages, $userId)
    {
        $url = "https://api.openai.com/v1/chat/completions";

        $storeInformation = Information::where('user_id', $userId)->first();
        $storeInformation = json_encode($storeInformation);
        $faqs = Faq::where('user_id', $userId)->get();
        $events = $this->getCalendarEvents($userId);
        $faqContent = $faqs->map(function ($faq) {
            return "- {$faq->question}: {$faq->answer}";
        })->join("\n");

        $events = json_encode($events);
        $data = [
            "model" => $this->model,
            "messages" => array_merge(
                [
                    [
                        'role' => 'system',
                        'content' => "You are a chatbot trained to answer based on the following FAQs and Calendar Events:\n\nFAQs:\n$faqContent\n\nCalendar Events:\n$events\n\n. Answer questions as if you are their assistant. your company information: $storeInformation"
                    ]
                ],
                $messages
            ),
            "max_tokens" => $this->max_tokens,
            "temperature" => $this->temperature,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->openai_api_key}",
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => 3000,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => $error];
        }

        $responseData = json_decode($response, true);
        if (isset($responseData['error'])) {
            return ['error' => $responseData['error']['message']];
        }

        return [
            'message' => $responseData['choices'][0]['message']['content'],
            'message_id' => $responseData['id']
        ];
    }

    public function getCalendarEvents($userId)
    {
        try {
            $user = User::find($userId);

            $client = new \Google_Client();
            $client->setAccessToken($user->google_access_token);

            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                $user->update(['google_access_token' => $client->getAccessToken()['access_token']]);
            }

            $service = new Calendar($client);
            $calendarId = 'primary';
            $events = $service->events->listEvents($calendarId);
            return $events->getItems();
        } catch (\Exception $e) {
            return [];
        }
    }

    function history()
    {
        // メッセージが1件以上ある会話のみ取得する
        $conversations = Conversation::where('user_id', Auth::id())
            ->whereHas('messages', function ($query) {
                $query->where('conversation_id', '!=', null); // メッセージがある会話のみを対象
            })
            ->latest()
            ->paginate(30);

        return view('admin.chat.history', compact('conversations'));
    }


    function chat($sessionId)
    {
        $chats = Conversation::with('messages')->where('session_id', $sessionId)->firstOrFail();
        return view('admin.chat.chat', compact('chats'));
    }

    function chatBot()
    {
        return view('admin.chat.bot');
    }

    function generateSnippet()
    {
        $user = User::find(Auth::id());
        if (!$user->chatbot_token) {
            $user->chatbot_token = Str::uuid()->toString();
            $user->save();
        }

        return view('admin.chat.snippet', compact('user'));
    }
}
