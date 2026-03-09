<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login', ['mode' => 'login']);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'captcha'],
        ]);
        $credentials = Arr::only($validated, ['email', 'password']);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'user') {
                return redirect()->intended(route('index'));
            } else {
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()
            ->withErrors(['email' => 'The provided credentials are incorrect.'])
            ->withInput($request->only('email', 'auth_mode', 'remember'));
    }

    public function showRegister()
    {
        return view('login', ['showRegister' => true]);
    }

    // Registration form submit handle করবে
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // password + password_confirmation
            'g-recaptcha-response' => ['required', 'captcha'],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        // Auto login করতে চাইলে:
        Auth::login($user);

        return redirect()
            ->route('index') // যেখানে নিয়ে যেতে চাই
            ->with('success', 'Account created successfully.');
    }

    public function forgotPage()
    {
        return view('forgot');
    }

    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $data['email'])->first();
        $newPassword = Str::random(10);

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'User';
        $body = "Hello {$fullName},\n\n" .
            "Your password has been reset. Use the credentials below to log in:\n" .
            "Email: {$user->email}\n" .
            "Password: {$newPassword}\n\n" .
            "For security, please log in and change this password right away.\n\n" .
            "Thank you.";

        Mail::raw($body, function ($message) use ($user, $fullName) {
            $message->to($user->email, $fullName)
                ->subject('Your new baraBD password');
        });

        return back()->with('success', 'A new password has been emailed to you.');
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed', 'different:current_password'],
        ], [
            'password.different' => 'New password must be different from current password.',
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
