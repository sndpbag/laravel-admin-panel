<?php

namespace  Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     * নতুন পাসওয়ার্ড সেট করার ফর্মটি দেখানোর জন্য।
     */
    public function create(Request $request): View
    {
        // Laravel Breeze-এর জন্য এই view ফাইলটি auth.reset-password নামে থাকে।
        return view('admin-panel::auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     * নতুন পাসওয়ার্ড সাবমিট করার পর ডেটাবেসে আপডেট করার জন্য।
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password.
        // পাসওয়ার্ড রিসেট করার চেষ্টা করা হচ্ছে।
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // যদি পাসওয়ার্ড সফলভাবে রিসেট হয়, তাহলে login পেজে redirect করা হবে।
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        // যদি কোনো কারণে ব্যর্থ হয় (যেমন টোকেন ভুল থাকলে), তাহলে error দেখানো হবে।
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
