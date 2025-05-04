<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-6">詐欺通報フォーム</h1>
                    
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

                    <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- 詐欺の種類（複数選択可） -->
                        <div>
                            <label class="block font-medium text-gray-700 mb-2">
                                詐欺の種類（複数選択可）<span class="text-red-600">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($scamTypes as $value => $label)
                                    <div class="flex items-start">
                                        <input type="checkbox" name="scam_types[]" id="scam_type_{{ $value }}" value="{{ $value }}" 
                                            class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            {{ in_array($value, old('scam_types', [])) ? 'checked' : '' }}
                                            @if ($value === 'other') onclick="toggleOtherScamType()" @endif>
                                        <label for="scam_type_{{ $value }}" class="ml-2 block text-sm text-gray-700">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- その他の詐欺タイプの詳細入力欄 -->
                            <div id="other_scam_type_container" class="mt-4 {{ in_array('other', old('scam_types', [])) ? '' : 'hidden' }}">
                                <label for="other_scam_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    その他の詐欺タイプの詳細<span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="other_scam_type" id="other_scam_type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    value="{{ old('other_scam_type') }}" placeholder="詐欺の種類を具体的に記入してください">
                            </div>
                        </div>
                        
                        <!-- 詐欺の内容（自由記述） -->
                        <div>
                            <label for="description" class="block font-medium text-gray-700 mb-2">
                                詐欺の内容<span class="text-red-600">*</span>
                                <span id="char_count" class="text-sm text-gray-500 ml-2">0/1000文字</span>
                            </label>
                            <textarea name="description" id="description" rows="6" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="詐欺の内容を具体的に記入してください。どのような手口で、どのように被害に遭った（または遭いそうになった）かなど、詳しく教えてください。（10文字以上、1000文字以内）">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">※10文字以上で入力してください</p>
                        </div>
                        
                        <!-- 証拠ファイルアップロード（任意） -->
                        <div>
                            <label class="block font-medium text-gray-700 mb-2">
                                証拠ファイル（任意、最大3ファイル）
                            </label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="file" name="evidence_files[]" id="evidence_file_1" 
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="flex items-center">
                                    <input type="file" name="evidence_files[]" id="evidence_file_2" 
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="flex items-center">
                                    <input type="file" name="evidence_files[]" id="evidence_file_3" 
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">※jpg, png, pdf形式、各5MB以内</p>
                        </div>
                        
                        <!-- reCAPTCHA -->
                        <div class="mt-4">
                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        </div>
                        
                        <!-- 送信ボタン -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                通報を送信する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const descriptionField = document.getElementById('description');
            const charCount = document.getElementById('char_count');
            
            function updateCharCount() {
                const count = descriptionField.value.length;
                charCount.textContent = count + '/1000文字';
                
                if (count > 1000) {
                    charCount.classList.add('text-red-600');
                    charCount.classList.remove('text-gray-500');
                } else {
                    charCount.classList.remove('text-red-600');
                    charCount.classList.add('text-gray-500');
                }
            }
            
            descriptionField.addEventListener('input', updateCharCount);
            updateCharCount(); // 初期表示時にも実行
        });
        
        function toggleOtherScamType() {
            const otherCheckbox = document.getElementById('scam_type_other');
            const otherContainer = document.getElementById('other_scam_type_container');
            
            if (otherCheckbox.checked) {
                otherContainer.classList.remove('hidden');
            } else {
                otherContainer.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
