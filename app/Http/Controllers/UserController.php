<?php

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
        Auth::loginUsingId($id);
        return redirect('welcome');
    }
}
