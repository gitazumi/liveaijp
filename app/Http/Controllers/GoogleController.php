<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(Request $request)
    {
        try {
            Log::info('Google Callback Request:', $request->all());

            $user = Socialite::driver('google')->stateless()->user();
            Log::info('Google User Data:', (array)$user);

            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect()->intended('welcome');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => Hash::make($user->name . '12345'),
                ]);
                $newUser->assignRole('user');
                Auth::login($newUser);
                return redirect()->intended('welcome');
            }
        } catch (\Throwable $th) {
            Log::error('Error in Google Callback:', [
                'message' => $th->getMessage()
            ]);
            return response()->json(['error' => 'Google login failed.'], 500);
        }
    }
}
