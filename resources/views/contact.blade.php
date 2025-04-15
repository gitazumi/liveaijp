@extends('company_description.description_layout')
@section('title', 'お問い合わせ | LiveAI')
@section('content')
    <h1 class="wf txt-42 txt-s f-weight-800">お問い合わせ</h1>

    <div class="wf mt-50 flex-col max-w-3xl mx-auto">
        <div class="mb-8 text-center">
            <p class="text-lg mb-4">
                LiveAIにご興味をお持ちいただきありがとうございます。
            </p>
            <p class="text-lg mb-4">
                ご質問・ご相談・機能に関するお問い合わせなど、以下のフォームよりお気軽にご連絡ください。
            </p>
            <p class="text-lg">
                担当者より1〜2営業日以内にご返信させていただきます。
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 text-center" role="alert">
                <span class="block text-xl font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">お名前 <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">お問い合わせ内容 <span class="text-red-500">*</span></label>
                <textarea name="message" id="message" rows="6" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="text-center">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-black bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    送信する
                </button>
            </div>
        </form>
    </div>
@endsection
