<?php

namespace  Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
use Illuminate\Http\RedirectResponse;
 
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
     /**
     * Display the password reset link request view.
     * 'Forgot Password' ফর্মটি দেখানোর জন্য।
     */
    public function create(): View
    {
        // Laravel Breeze-এর জন্য এই view ফাইলটি auth.forgot-password নামে থাকে।
        // যদি আপনার ফাইলের নাম ভিন্ন হয়, তবে পরিবর্তন করে নেবেন।
        return view('admin-panel::auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     * ইমেল অ্যাড্রেস সাবমিট করার পর পাসওয়ার্ড রিসেট লিঙ্ক পাঠানোর জন্য।
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user.
        // পাসওয়ার্ড রিসেট লিঙ্ক পাঠানোর চেষ্টা করা হচ্ছে।
        $status = Password::sendResetLink($request->only('email'));

        // যদি লিঙ্ক সফলভাবে পাঠানো হয়, তাহলে একটি success message দেখানো হবে।
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // যদি কোনো কারণে ব্যর্থ হয় (যেমন ইমেল খুঁজে না পাওয়া গেলে), তাহলে error দেখানো হবে।
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
