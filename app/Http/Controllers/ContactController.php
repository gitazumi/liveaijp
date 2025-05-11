<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    /**
     * Display the contact form
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Send the contact form email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required|recaptcha',
        ], [
            'g-recaptcha-response.required' => 'ロボットではないことを確認してください。',
            'g-recaptcha-response.recaptcha' => 'reCAPTCHA認証に失敗しました。もう一度お試しください。',
        ]);

        Mail::to('code.sawa@gmail.com')->send(new ContactMail([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ]));

        return redirect()->route('contact.index')->with('success', '問い合わせ完了');
    }
    
    /**
     * Handle feedback form submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function feedback(Request $request)
    {
        $validated = $request->validate([
            'feedback' => 'required|string',
            'recipient' => 'required|email',
        ]);

        Mail::to($validated['recipient'])->send(new ContactMail([
            'name' => auth()->user()->name ?? 'ユーザー',
            'email' => auth()->user()->email ?? 'フィードバックフォーム',
            'message' => $validated['feedback'],
        ]));

        return redirect()->back()->with('success', 'フィードバックをお送りいただきありがとうございます。');
    }
}
