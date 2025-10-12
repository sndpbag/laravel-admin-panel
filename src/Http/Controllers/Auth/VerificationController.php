<?php

namespace  Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Sndpbag\AdminPanel\Models\User;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /**
     * মেথড ১: "Verify your email" নোটিশ পেজটি দেখানোর জন্য।
     * এটি EmailVerificationPromptController-এর কাজ করে।
     */
    public function notice(Request $request)
    {
        // যদি ইউজারের ইমেল আগে থেকেই ভেরিফাই করা থাকে, তাহলে তাকে ড্যাশবোর্ডে পাঠিয়ে দেওয়া হবে।
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard'))
            : view('admin-panel::auth.verify-email');
    }

    /**
     * মেথড ২: ইমেল থেকে আসা ভেরিফিকেশন লিঙ্কটি হ্যান্ডেল করার জন্য।
     * এটি VerifyEmailController-এর কাজ করে।
     * EmailVerificationRequest নিশ্চিত করে যে লিঙ্কটি সঠিক এবং সুরক্ষিত।
     */
    // public function verify(EmailVerificationRequest $request)
    // {
       


    //     // ধাপ ১: URL থেকে পাওয়া 'id' দিয়ে ইউজারকে খোঁজা হচ্ছে
    //     $user = User::find($request->route('id'));

    //     // যদি ইউজারকে খুঁজে না পাওয়া যায়
    //     if (! $user) {
    //         // যদি কোনো কারণে ইউজারকে খুঁজে না পাওয়া যায়, তাহলে একটি সুন্দর বার্তা সহ লগইন পেজে পাঠিয়ে দেওয়া হবে
    //         return redirect()->route('login')->withErrors(['email' => 'Verification link is invalid or has expired. Please try registering again.']);
    //     }

    //     // ধাপ ২: লিঙ্কটি সঠিক কিনা তা ম্যানুয়ালি চেক করা হচ্ছে
    //     if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
    //         abort(403, 'Invalid verification link.');
    //     }

    //     // ধাপ ৩: যদি ইউজারের ইমেল আগে থেকেই ভেরিফাই করা থাকে
    //     if ($user->hasVerifiedEmail()) {
    //         return redirect()->route('login')->with('status', 'Your email is already verified. You can log in now.');
    //     }

    //     // ধাপ ৪: ইউজারের ইমেলকে ভেরিফাই হিসেবে চিহ্নত করা হচ্ছে
    //     if ($user->markEmailAsVerified()) {
    //         event(new Verified($user));
    //     }

    //     // ধাপ ৫: সফলভাবে ভেরিফাই হওয়ার পর ইউজারকে লগইন পেজে একটি বার্তা সহ পাঠানো হচ্ছে
    //     return redirect()->route('login')->with('status', 'Your email has been successfully verified! Please log in.');
    // }



      public function verify(Request $request) // <-- পরিবর্তন: EmailVerificationRequest-এর বদলে শুধু Request
    {
        // URL থেকে পাওয়া 'id' দিয়ে ইউজারকে খোঁজা হচ্ছে
        $user = User::find($request->route('id'));

        // যদি ইউজারকে খুঁজে না পাওয়া যায়
        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'Verification link is invalid or has expired. Please try registering again.']);
        }

        // লিঙ্কটি সঠিক কিনা তা ম্যানুয়ালি চেক করা হচ্ছে (signed middleware এটি নিজে করে, তবে ডাবল চেক ভালো)
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link hash.');
        }

        // যদি ইউজারের ইমেল আগে থেকেই ভেরিফাই করা থাকে
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Your email is already verified. You can log in now.');
        }

        // ইউজারের ইমেলকে ভেরিফাই হিসেবে চিহ্নত করা হচ্ছে
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // সফলভাবে ভেরিফাই হওয়ার পর ইউজারকে লগইন পেজে একটি বার্তা সহ পাঠানো হচ্ছে
        return redirect()->route('login')->with('status', 'Your email has been successfully verified! Please log in.');
    }


 

    /**
     * মেথড ৩: ভেরিফিকেশন ইমেল আবার পাঠানোর জন্য।
     * এটি EmailVerificationNotificationController-এর কাজ করে।
     */
    public function send(Request $request)
    {
        // যদি ইউজারের ইমেল আগে থেকেই ভেরিফাই করা থাকে, তাহলে তাকে ড্যাশবোর্ডে পাঠিয়ে দেওয়া হবে।
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // ইউজারকে আবার ভেরিফিকেশন ইমেল পাঠানো হচ্ছে।
        $request->user()->sendEmailVerificationNotification();

        // ইউজারকে আগের পেজে একটি সফল বার্তা সহ ফেরত পাঠানো হচ্ছে।
        return back()->with('status', 'verification-link-sent');
    }
}
