<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            // Check if Google credentials are configured
            if (!config('services.google.client_id') || !config('services.google.client_secret')) {
                return redirect('/')->with('error', 'Google OAuth is not configured properly.');
            }

            return Socialite::driver('google')
                ->scopes(['email', 'profile'])
                ->redirect();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to initialize Google login: ' . $e->getMessage());
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['google_login' => 'Google login failed. Please try again.']);
        }

        $existingUser = User::where('google_id', $user->id)->first();

        if ($existingUser) {
            Auth::login($existingUser, true);
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => bcrypt(uniqid()), // Or generate a random password
            ]);
            Auth::login($newUser, true);
        }

        return redirect()->intended('/'); // Redirect to home page after login
    }
}