<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    /**
     * Display the fraud report form
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $scamTypes = [
            'dating_app' => '出会い系アプリ・マッチングアプリ',
            'phishing' => 'フィッシング詐欺（偽ログイン画面など）',
            'email' => 'メール詐欺（迷惑メールやなりすまし）',
            'family' => 'オレオレ詐欺・親族装い型',
            'investment' => '投資詐欺・副業詐欺',
            'sns' => 'SNS詐欺（Instagram・X・LINEなど）',
            'fake_shop' => '偽通販サイト（詐欺ショッピングサイト）',
            'sms' => '宅配業者を装ったSMS（偽通知リンク）',
            'job' => '求人・アルバイト詐欺（高収入・短時間）',
            'crypto' => '仮想通貨・暗号資産詐欺',
            'qr' => 'QRコード詐欺・偽アプリ誘導',
            'virus' => 'ウイルス感染を装うポップアップ・警告画面',
            'other' => 'その他'
        ];
        
        return view('report.create', compact('scamTypes'));
    }

    /**
     * Store a new fraud report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'scam_types' => 'required|array|min:1',
                'scam_types.*' => 'required|string',
                'other_scam_type' => 'required_if:scam_types,other|nullable|string|max:255',
                'description' => 'required|string|min:10|max:1000',
                'evidence_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
            ], [
                'scam_types.required' => '詐欺の種類を選択してください。',
                'scam_types.min' => '少なくとも1つの詐欺の種類を選択してください。',
                'description.required' => '詐欺の内容を入力してください。',
                'description.min' => '詐欺の内容は10文字以上で入力してください。',
                'description.max' => '詐欺の内容は1000文字以内で入力してください。',
                'evidence_files.*.mimes' => '証拠ファイルはjpg、png、pdfのみ対応しています。',
                'evidence_files.*.max' => '証拠ファイルは5MB以下にしてください。',
                'other_scam_type.required_if' => 'その他を選択した場合は詳細を入力してください。',
            ]);

            $scamTypesData = $validated['scam_types'];
            if (in_array('other', $scamTypesData) && isset($validated['other_scam_type'])) {
                $scamTypesData = array_filter($scamTypesData, function($type) {
                    return $type !== 'other';
                });
                $scamTypesData[] = 'other:' . $validated['other_scam_type'];
            }

            $evidenceFiles = [];
            if ($request->hasFile('evidence_files')) {
                $files = $request->file('evidence_files');
                
                $files = array_slice($files, 0, 3);
                
                foreach ($files as $file) {
                    $path = $file->store('evidence', 'public');
                    $evidenceFiles[] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ];
                }
            }

            $editToken = Str::uuid()->toString();

            $report = new Report();
            $report->scam_types = $scamTypesData;
            $report->description = $validated['description'];
            $report->evidence_files = !empty($evidenceFiles) ? $evidenceFiles : null;
            $report->edit_token = $editToken;
            $report->ip_address = $request->ip();
            $report->user_agent = $request->userAgent();
            $report->save();

            return view('report.complete', ['editToken' => $editToken]);

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', '通報の送信中にエラーが発生しました。もう一度お試しください。')->withInput();
        }
    }

    /**
     * Display the edit form for a report
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function edit($token)
    {
        $report = Report::where('edit_token', $token)->firstOrFail();
        $comments = ReportComment::where('report_id', $report->id)
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        return view('report.edit', compact('report', 'comments'));
    }

    /**
     * Update a report with a new comment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $token)
    {
        try {
            $validated = $request->validate([
                'comment_text' => 'required|string|min:10',
            ], [
                'comment_text.required' => '追記内容を入力してください。',
                'comment_text.min' => '追記内容は10文字以上で入力してください。',
            ]);

            $report = Report::where('edit_token', $token)->firstOrFail();
            
            $comment = new ReportComment();
            $comment->report_id = $report->id;
            $comment->comment_text = $validated['comment_text'];
            $comment->save();

            return redirect()->route('report.edit', ['token' => $token])
                            ->with('success', '追記が完了しました。');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', '追記の送信中にエラーが発生しました。もう一度お試しください。')->withInput();
        }
    }
}
