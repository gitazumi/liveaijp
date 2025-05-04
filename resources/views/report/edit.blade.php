<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-6">詐欺通報の追記</h1>
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <strong class="font-bold">成功：</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <strong class="font-bold">入力内容に問題があります：</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- 元の通報内容 -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold mb-4">通報内容</h2>
                        
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-1">詐欺の種類：</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($report->scam_types as $scamType)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-indigo-100 text-indigo-800">
                                        @if (strpos($scamType, 'other:') === 0)
                                            その他: {{ substr($scamType, 6) }}
                                        @else
                                            @php
                                                $scamTypeLabels = [
                                                    'dating_app' => '出会い系アプリ・マッチングアプリ',
                                                    'phishing' => 'フィッシング詐欺',
                                                    'email' => 'メール詐欺',
                                                    'family' => 'オレオレ詐欺・親族装い型',
                                                    'investment' => '投資詐欺・副業詐欺',
                                                    'sns' => 'SNS詐欺',
                                                    'fake_shop' => '偽通販サイト',
                                                    'sms' => '宅配業者を装ったSMS',
                                                    'job' => '求人・アルバイト詐欺',
                                                    'crypto' => '仮想通貨・暗号資産詐欺',
                                                    'qr' => 'QRコード詐欺・偽アプリ誘導',
                                                    'virus' => 'ウイルス感染を装うポップアップ',
                                                ];
                                            @endphp
                                            {{ $scamTypeLabels[$scamType] ?? $scamType }}
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-1">詐欺の内容：</h3>
                            <div class="bg-white p-3 rounded border border-gray-200">
                                {!! nl2br(e($report->description)) !!}
                            </div>
                        </div>
                        
                        @if ($report->evidence_files)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-1">証拠ファイル：</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($report->evidence_files as $file)
                                        <div class="bg-white p-3 rounded border border-gray-200">
                                            <div class="flex items-center">
                                                @if (in_array(pathinfo($file['original_name'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                @endif
                                                <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-sm text-blue-600 hover:underline truncate">
                                                    {{ $file['original_name'] }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4 text-sm text-gray-500">
                            通報日時: {{ $report->created_at->format('Y年m月d日 H:i') }}
                        </div>
                    </div>
                    
                    <!-- 追記コメント一覧 -->
                    @if ($comments->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold mb-4">追記履歴</h2>
                            
                            <div class="space-y-4">
                                @foreach ($comments as $comment)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="mb-2">
                                            {!! nl2br(e($comment->comment_text)) !!}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $comment->created_at->format('Y年m月d日 H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- 追記フォーム -->
                    <div>
                        <h2 class="text-lg font-semibold mb-4">新しい追記を追加</h2>
                        
                        <form action="{{ route('report.update', ['token' => $report->edit_token]) }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div>
                                <label for="comment_text" class="block font-medium text-gray-700 mb-2">
                                    追記内容<span class="text-red-600">*</span>
                                </label>
                                <textarea name="comment_text" id="comment_text" rows="4" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="新しい情報や詳細を追記してください（10文字以上）">{{ old('comment_text') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">※10文字以上で入力してください</p>
                            </div>
                            
                            <div class="flex items-center justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    追記を送信する
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
