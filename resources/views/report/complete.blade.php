<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        
                        <h1 class="text-2xl font-semibold mb-4">通報ありがとうございました</h1>
                        
                        <p class="mb-6">詐欺の通報を受け付けました。情報提供にご協力いただき、ありがとうございます。</p>
                        
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>重要：</strong> 以下の編集用リンクを必ず保存してください。このリンクがあれば、後から情報を追記できます。
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-100 p-4 rounded-md mb-6">
                            <p class="text-sm text-gray-700 mb-2">追記や修正はこちらのリンクから可能です：</p>
                            <div class="flex items-center justify-center">
                                <input type="text" value="{{ url('/report/edit/' . $editToken) }}" id="edit_link" readonly
                                    class="block w-full md:w-2/3 rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <button onclick="copyEditLink()" class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    コピー
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <a href="{{ route('report.create') }}" class="text-indigo-600 hover:text-indigo-800">
                                ← 通報フォームに戻る
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyEditLink() {
            const editLink = document.getElementById('edit_link');
            editLink.select();
            document.execCommand('copy');
            
            const button = event.currentTarget;
            const originalText = button.textContent;
            button.textContent = 'コピー完了！';
            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            }, 2000);
        }
    </script>
    @endpush
</x-app-layout>
