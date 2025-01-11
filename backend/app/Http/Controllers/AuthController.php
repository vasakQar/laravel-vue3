<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Please check your email for verification.']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Please verify your email before logging in.'], 403);
            }

            $token = $user->createToken('YourAppName')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail() || $hash !== sha1($user->email)) {
            return response()->json(['message' => 'Invalid or already verified email.']);
        }

        $user->markEmailAsVerified();

        return redirect()->to(config('app.front_url') . '/login');
    }

    public function logout(Request $request)
    {
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
