oうs<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::role('user')->paginate();
        return view('users.index', compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required',
        ]);
        $validatedData['password'] = Hash::make($request->password);

        $validatedData['faq_limit'] = 20; // FAQ登録数のデフォルト値
        $validatedData['api_request_limit'] = 100; // APIリクエスト数のデフォルト値

        $user = User::create($validatedData);
        $user->assignRole('user');
        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }
    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('users.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
            'status' => 'required',
        ]);
        if ($request->password) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::defaults()],
                'password_confirmation' => 'required',
            ]);
            $validatedData['password'] = Hash::make($request->password);
        }
        User::findOrFail($id)->update($validatedData);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function autoLogin($id) {
        session(['admin_user_id' => Auth::id()]);

        Auth::loginUsingId($id);
        return redirect('welcome');
    }

    public function returnToAdmin() {
        if (session()->has('admin_user_id')) {
            $adminId = session('admin_user_id');

            session()->forget('admin_user_id');

            Auth::loginUsingId($adminId);

            return redirect()->route('users.index')->with('success', '管理者アカウントに戻りました。');
        }

        return redirect()->route('welcome')->with('error', '管理者アカウント情報が見つかりませんでした。');
    }

    public function manage($id)
    {
        $user = User::findOrFail($id);
        return view('users.manage', compact('user'));
    }

    public function updateManage(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
            'status' => 'required',
            'faq_limit' => 'nullable|integer|min:0',
            'api_request_limit' => 'nullable|integer|min:0',
        ]);

        if ($request->password) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::defaults()],
                'password_confirmation' => 'required',
            ]);
            $validatedData['password'] = Hash::make($request->password);
        }

        if ($request->has('faq_limit_type') && $request->faq_limit_type === 'unlimited') {
            $validatedData['faq_limit'] = null;
        }
        
        if ($request->has('api_request_limit_type') && $request->api_request_limit_type === 'unlimited') {
            $validatedData['api_request_limit'] = null;
        }

        $user->update($validatedData);

        return redirect()->route('users.manage', ['id' => $id])->with('success', 'ユーザー情報が更新されました。');
    }
}
