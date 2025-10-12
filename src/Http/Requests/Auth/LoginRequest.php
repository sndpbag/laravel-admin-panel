<?php

namespace Sndpbag\AdminPanel\Http\Requests\Auth;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Sndpbag\AdminPanel\Models\User;

 

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }


     public function authenticate(): void
    {
        // প্রথমে নিশ্চিত করুন যে, user অনেকবার ভুল চেষ্টা করে заблоки হননি।
        $this->ensureIsNotRateLimited();

        // Auth::attempt() মেথডটি email ও password দিয়ে লগইন করার চেষ্টা করে।
        // যদি সফল না হয়, তাহলে এটি false রিটার্ন করে।
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // যদি লগইন ব্যর্থ হয়, তাহলে rate limiter-এর count বাড়িয়ে দেওয়া হয়।
            RateLimiter::hit($this->throttleKey());

            // একটি validation error দেখানো হয়।
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

         // --- আমাদের কাস্টম নিরাপত্তা চেক ---
        $user = Auth::user();

        // ধাপ ১: ইউজারের ইমেল ভেরিফাই করা আছে কি না, তা চেক করা হচ্ছে
        if (! $user->hasVerifiedEmail()) {
            Auth::logout(); // লগইন করতে না দিয়ে সেশনটি নষ্ট করে দেওয়া হচ্ছে
            throw ValidationException::withMessages([
                'email' => 'Your email address is not verified. Please check your inbox for the verification link.',
            ]);
        }

        // ধাপ ২: ইউজারের স্ট্যাটাস 'Active' কি না, তা চেক করা হচ্ছে
        if ($user->status !== 'Active') {
            Auth::logout(); // লগইন করতে না দিয়ে সেশনটি নষ্ট করে দেওয়া হচ্ছে
            throw ValidationException::withMessages([
                'email' => 'Your account is currently inactive. Please contact support for assistance.',
            ]);
        }
        
        // যদি লগইন সফল হয়, তাহলে rate limiter রিসেট করে দেওয়া হয়।
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     * এই মেথডটি নিশ্চিত করে যে, user brute-force attack (বারবার ভুল পাসওয়ার্ড দিয়ে চেষ্টা) করছেন না।
     * নির্দিষ্ট সময়ের মধ্যে অনেকবার ভুল চেষ্টা করলে তাকে 잠시 ব্লক করে দেওয়া হয়।
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     * Rate limiter-এর জন্য একটি unique key তৈরি করে।
     * এটি user-এর email এবং IP address-এর উপর ভিত্তি করে তৈরি হয়।
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
