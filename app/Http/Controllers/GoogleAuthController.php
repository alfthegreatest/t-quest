<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect('/')->with('error', 'Google authentication failed.');
        }

        // Check if the user already exists in the database
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            // Otherwise, create a new user and log them in
            $newUser = User::updateOrCreate([
                'email' => $user->email,
            ], [
                'name' => $user->name,
                'password' => bcrypt(Str::random(16)), // Set a random password
                'email_verified_at' => now(),
            ]);
            Auth::login($newUser);
        }

        return redirect('/dashboard');
    }
}
