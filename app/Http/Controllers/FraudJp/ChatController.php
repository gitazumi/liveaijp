<?php

namespace App\Http\Controllers\FraudJp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    private $openai_api_key;
    private $model = 'gpt-3.5-turbo';
    private $max_tokens = 1000;
    private $temperature = 0.7;

    public function __construct()
    {
        $this->openai_api_key = env('FRAUDJP_OPENAI_API_KEY', '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');
    }

    /**
     * Display the chat interface
     */
    public function index()
    {
        return view('fraudjp.chat');
    }

    /**
     * Process a chat message
     */
    public function message(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $sessionId = $this->getSessionId($request);
            
            $this->storeMessage($sessionId, $userMessage, true);
            
            $extractedData = $this->extractFraudIndicators($userMessage);
            
            $matchingReports = $this->findMatchingReports($extractedData);
            
            $aiResponse = $this->getAiResponse($userMessage, $extractedData, $matchingReports, $sessionId);
            
            $this->storeMessage($sessionId, $aiResponse, false);
            
            return response()->json([
                'message' => $aiResponse,
                'session_id' => $sessionId,
                'db_referenced' => !empty($matchingReports)
            ]);
        } catch (\Exception $e) {
            Log::error('FraudJp Chat Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => '申し訳ありませんが、エラーが発生しました。しばらくしてからもう一度お試しください。'
            ], 500);
        }
    }

    /**
     * Get chat history for a session
     */
    public function getHistory(Request $request)
    {
        $sessionId = $this->getSessionId($request);
        
        $messages = DB::table('fraudjp_messages')
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json([
            'messages' => $messages
        ]);
    }

    /**
     * Get or generate a session ID
     */
    private function getSessionId(Request $request)
    {
        if ($request->has('session_id')) {
            return $request->input('session_id');
        }
        
        return Str::uuid()->toString();
    }

    /**
     * Store a message in the database
     */
    private function storeMessage($sessionId, $message, $isUser)
    {
        return DB::table('fraudjp_messages')->insert([
            'session_id' => $sessionId,
            'message' => $message,
            'is_user' => $isUser,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Extract potential fraud indicators from a message
     */
    private function extractFraudIndicators($message)
    {
        $data = [
            'phone_numbers' => [],
            'urls' => [],
            'keywords' => []
        ];
        
        preg_match_all('/\b(?:\d{2,4}-\d{2,4}-\d{4}|\d{10,11})\b/', $message, $phoneMatches);
        if (!empty($phoneMatches[0])) {
            $data['phone_numbers'] = $phoneMatches[0];
        }
        
        preg_match_all('/\bhttps?:\/\/[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/))/i', $message, $urlMatches);
        if (!empty($urlMatches[0])) {
            $data['urls'] = $urlMatches[0];
        }
        
        $domains = [];
        foreach ($data['urls'] as $url) {
            $parsedUrl = parse_url($url);
            if (isset($parsedUrl['host'])) {
                $domains[] = $parsedUrl['host'];
            }
        }
        $data['domains'] = $domains;
        
        $fraudKeywords = ['詐欺', '不審', '偽', '未納', '請求', 'カード入力', '被害', '不正', '不審なメール', '不審なSMS', '不審な電話'];
        foreach ($fraudKeywords as $keyword) {
            if (mb_strpos($message, $keyword) !== false) {
                $data['keywords'][] = $keyword;
            }
        }
        
        return $data;
    }

    /**
     * Find matching fraud reports in the database
     */
    private function findMatchingReports($extractedData)
    {
        $matchingReports = [];
        
        if (!empty($extractedData['phone_numbers'])) {
            foreach ($extractedData['phone_numbers'] as $phoneNumber) {
                $reports = DB::table('reports')
                    ->where('status', 'on')
                    ->where(function($query) use ($phoneNumber) {
                        $query->where('content', 'like', '%' . $phoneNumber . '%')
                              ->orWhere('phone_number', 'like', '%' . $phoneNumber . '%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                if ($reports->count() > 0) {
                    $matchingReports = array_merge($matchingReports, $reports->toArray());
                }
            }
        }
        
        if (!empty($extractedData['urls']) || !empty($extractedData['domains'])) {
            $searchTerms = array_merge($extractedData['urls'], $extractedData['domains'] ?? []);
            
            foreach ($searchTerms as $term) {
                $reports = DB::table('reports')
                    ->where('status', 'on')
                    ->where(function($query) use ($term) {
                        $query->where('content', 'like', '%' . $term . '%')
                              ->orWhere('url', 'like', '%' . $term . '%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                if ($reports->count() > 0) {
                    $matchingReports = array_merge($matchingReports, $reports->toArray());
                }
            }
        }
        
        $uniqueReports = [];
        $reportIds = [];
        
        foreach ($matchingReports as $report) {
            if (!in_array($report->id, $reportIds)) {
                $reportIds[] = $report->id;
                $uniqueReports[] = $report;
                
                if (count($uniqueReports) >= 5) {
                    break;
                }
            }
        }
        
        return $uniqueReports;
    }

    /**
     * Generate an AI response using OpenAI API
     */
    private function getAiResponse($userMessage, $extractedData, $matchingReports, $sessionId)
    {
        $history = DB::table('fraudjp_messages')
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse();
        
        $messages = [];
        foreach ($history as $message) {
            $messages[] = [
                'role' => $message->is_user ? 'user' : 'assistant',
                'content' => $message->message
            ];
        }
        
        if (empty($messages)) {
            $messages[] = [
                'role' => 'user',
                'content' => $userMessage
            ];
        }
        
        $systemPrompt = $this->prepareSystemPrompt($extractedData, $matchingReports);
        
        $url = "https://api.openai.com/v1/chat/completions";
        $data = [
            "model" => $this->model,
            "messages" => array_merge(
                [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ]
                ],
                $messages
            ),
            "max_tokens" => $this->max_tokens,
            "temperature" => $this->temperature,
        ];
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->openai_api_key
        ])->post($url, $data);
        
        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData['choices'][0]['message']['content'];
        } else {
            Log::error('OpenAI API Error: ' . $response->body());
            throw new \Exception('OpenAI API Error: ' . $response->status());
        }
    }

    /**
     * Prepare the system prompt for OpenAI
     */
    private function prepareSystemPrompt($extractedData, $matchingReports)
    {
        $systemPrompt = <<<EOT
役割と応答方針
あなたの役割は、ユーザーからの自然な文章（電話番号、SMSの内容、URL、被害状況など）に対して、詐欺の可能性や過去の通報事例をもとに、冷静かつ丁寧な説明と助言を行うことです。

データベースには、ユーザーから通報された大量の詐欺事例が保存されています（MySQL）。

プログラムによって照合・抽出された情報があなたに渡されることがあります。

それらの情報は信頼性の高い一次情報として扱い、可能性判断に重きを置いてください。

あなたは警察や法律専門家ではないため、最終的な断定や判断は行わないでください。

回答する際には、「〜の可能性があります」「〜と疑われます」など、慎重かつ中立的な表現を使ってください。

ユーザーが不安や混乱を感じている場合は、落ち着いた言葉で寄り添い、以下のような対応を提案してください：

対応例（Markdown表現可能）
消費生活センターや警察への相談

クレジットカード会社・金融機関への連絡

詐欺メッセージやURLへのアクセス停止

通報の協力（フォーム案内）
EOT;

        if (!empty($extractedData['phone_numbers']) || !empty($extractedData['urls']) || !empty($extractedData['keywords'])) {
            $systemPrompt .= "\n\n【抽出された情報】\n";
            
            if (!empty($extractedData['phone_numbers'])) {
                $systemPrompt .= "電話番号: " . implode(", ", $extractedData['phone_numbers']) . "\n";
            }
            
            if (!empty($extractedData['urls'])) {
                $systemPrompt .= "URL: " . implode(", ", $extractedData['urls']) . "\n";
            }
            
            if (!empty($extractedData['domains'])) {
                $systemPrompt .= "ドメイン: " . implode(", ", $extractedData['domains']) . "\n";
            }
            
            if (!empty($extractedData['keywords'])) {
                $systemPrompt .= "キーワード: " . implode(", ", $extractedData['keywords']) . "\n";
            }
        }
        
        if (!empty($matchingReports)) {
            $systemPrompt .= "\n\n【データベース照合結果】\n";
            $systemPrompt .= "以下の通報事例が見つかりました：\n";
            
            foreach ($matchingReports as $index => $report) {
                $systemPrompt .= "\n事例" . ($index + 1) . ":\n";
                $systemPrompt .= "通報日時: " . $report->created_at . "\n";
                $systemPrompt .= "内容: " . $report->content . "\n";
                
                if (!empty($report->phone_number)) {
                    $systemPrompt .= "電話番号: " . $report->phone_number . "\n";
                }
                
                if (!empty($report->url)) {
                    $systemPrompt .= "URL: " . $report->url . "\n";
                }
            }
        } else {
            $systemPrompt .= "\n\n【データベース照合結果】\n";
            $systemPrompt .= "該当する通報情報は見つかりませんでした。\n";
            $systemPrompt .= "該当情報は確認できませんでしたが、もし不審な内容であれば、ぜひ通報をご協力ください。\n";
            $systemPrompt .= "通報フォームはこちらです：https://fraud.jp/report\n";
        }
        
        return $systemPrompt;
    }
}
