<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Auth;
use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Sndpbag\AdminPanel\Models\User;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;




class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * রেজিস্ট্রেশন ফর্মটি দেখানোর জন্য এই মেথড কাজ করে।
     */
    public function create(): View
    {
        return view('admin-panel::auth.register');
    }

    /**
     * Handle an incoming registration request.
     * রেজিস্ট্রেশন ফর্ম সাবমিট করার পর ডেটা এখানে আসে।
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // ফর্ম থেকে আসা ডেটা validate করা হচ্ছে।
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'captcha' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // সেশন থেকে ক্যাপচা নিয়ে ছোট হাতের অক্ষরে রূপান্তর করে চেক করা
                    $sessionCaptcha = session()->get('login_captcha');
                    if (!$sessionCaptcha || strtolower($value) !== strtolower($sessionCaptcha)) {
                        $fail('The captcha code is incorrect.');
                    }
                }
            ],
        ]);

        // নতুন ইউজার তৈরি করা হচ্ছে।
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // একটি ইভেন্ট dispatch করা হয়, যেমন ভেরিফিকেশন ইমেল পাঠানোর জন্য।
        event(new Registered($user));

        // ক্যাপচা সেশন মুছে ফেলা
        session()->forget('login_captcha');

        // JSON রেসপন্স (AJAX এর জন্য)
        if ($request->wantsJson()) {
            return response()->json([
                'redirect_url' => route('login'),
                'message' => 'Registration successful! Please check your email to verify your account.'
            ]);
        }

        // Login page-e ekta success message-shaho ferot pathano hocche
        return redirect()->route('login')->with('status', 'Registration successful! Please check your email to verify your account.');
    }
}
