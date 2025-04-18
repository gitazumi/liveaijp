<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    function index() {
        $faqs = Faq::where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc') // 追加: 作成日時の降順
                   ->paginate(30);
        return view('admin.faq.index', compact('faqs'));
    }
    function faqView() {
        $faqs = Faq::all(); // Fetch FAQs from database
        return view('index', compact('faqs'));
    }
    function store(Request $request) {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $user = Auth::user();
        $validatedData['user_id'] = $user->id;
        
        if (!$user->isExistingAccount()) {
            $faqCount = Faq::where('user_id', $user->id)->count();
            if ($faqCount >= 20) {
                return redirect()->back()->with('error', 'FAQ登録数の上限（20件）に達しています。');
            }
        }

        Faq::create($validatedData);
        return redirect()->back()->with('success', 'FAQ added successfully.');
        
    }
    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'FAQ deleted successfully.');
    }
    function update(Request $request, $id) {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $validatedData['user_id'] = Auth::id();

        Faq::find($id)->update($validatedData);
        return redirect()->back()->with('success', 'FAQ updated successfully.');
        
    }
}
