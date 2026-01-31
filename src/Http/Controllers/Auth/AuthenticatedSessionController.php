<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Http\Requests\Auth\LoginRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// Import these classes
use Sndpbag\AdminPanel\Models\UserLog;
use Sndpbag\AdminPanel\Notifications\LoginOtpNotification;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent; // For User Agent Parsing
use Stevebauman\Location\Facades\Location; // For GeoIP

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin-panel::auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {


    //     $request->authenticate();


    //     $user = \Illuminate\Support\Facades\Auth::user();

    //     // ২. ডিভাইস লিমিট চেক (সর্বোচ্চ ২ টি সেশন)
    //     // এটি সেশন ড্রাইভার 'database' হলে কাজ করবে
    //     $activeSessions = \DB::table('sessions')->where('user_id', $user->id)->count();

    //     if ($activeSessions >= 2) {
    //         // যদি ২টির বেশি হয়, তবে সবথেকে পুরনো সেশনটি ডিলিট করে দেওয়া (অথবা নতুন লগইন ব্লক করা)
    //         \DB::table('sessions')
    //             ->where('user_id', $user->id)
    //             ->orderBy('last_activity', 'asc')
    //             ->limit(1)
    //             ->delete();
    //     }


    //     // ৩. নতুন ডিভাইস বা আইপি অ্যালার্ট লজিক
    //     // ইউজার আগে কখনো এই আইপি থেকে সফলভাবে লগইন করেছে কি না তা চেক করা
    //     $knownIp = \Sndpbag\AdminPanel\Models\UserLog::where('user_id', $user->id)
    //         ->where('ip_address', $request->ip())
    //         ->where('success', true)
    //         ->exists();

    //     if (!$knownIp) {
    //         // নতুন আইপি হলে নোটিফিকেশন পাঠানো
    //         try {
    //             $user->notify(new \Sndpbag\AdminPanel\Notifications\NewDeviceLoginNotification($request->ip()));
    //         } catch (\Exception $e) {
    //             \Illuminate\Support\Facades\Log::error('New Device Alert failed: ' . $e->getMessage());
    //         }
    //     }


    //     // User "Remember Me" checkbox-e tick diyeche kina, sheta session-e rakha hocche
    //     $request->session()->put('login_remember', $request->boolean('remember'));

    //     $plainOtp = random_int(100000, 999999);

    //     $user->update([
    //         'otp' => Hash::make($plainOtp), // OTP HASH KORA HOCHCHE
    //         'otp_expires_at' => now()->addMinutes(5),
    //     ]);

    //     try {
    //         Notification::send($user, new LoginOtpNotification($plainOtp));
    //     } catch (\Exception $e) {
    //         \Illuminate\Support\Facades\Log::error('OTP Email sending failed: ' . $e->getMessage());
    //     }

    //     \Illuminate\Support\Facades\Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     $request->session()->put('otp_email', $user->email);

    //     return redirect()->route('otp.show');

    //     // // This authenticates the user based on the validated data from LoginRequest
    //     // $request->authenticate();

    //     // // Regenerate the session ID to prevent session fixation attacks
    //     // $request->session()->regenerate();

    //     // // ============== START: LOGIC FOR SUCCESSFUL LOGIN ==============
    //     // try {
    //     //     // Use the Agent package to parse the user-agent string
    //     //     $agent = new Agent();

    //     //     // Use the Location package to get geographic info from the IP address
    //     //     // The 'false' parameter can be used for testing with a local IP like 127.0.0.1
    //     //     $location = Location::get($request->ip());

    //     //     // Create the log entry with all the detailed information
    //     //     UserLog::create([
    //     //         'user_id'    => auth()->id(),
    //     //         'email'      => auth()->user()->email,
    //     //         'ip_address' => $request->ip(),
    //     //         'user_agent' => $request->userAgent(),
    //     //         'session_id' => $request->session()->getId(), // Store session ID to track logout

    //     //         // Details from Agent
    //     //         'browser'    => $agent->browser(),
    //     //         'platform'   => $agent->platform(),
    //     //         'device'     => $agent->isDesktop() ? 'Desktop' : ($agent->isTablet() ? 'Tablet' : 'Mobile'),

    //     //         // Details from Location
    //     //         'country'    => $location ? $location->countryName : 'Unknown',
    //     //         'city'       => $location ? $location->cityName : 'Unknown',

    //     //         // Status and Timestamps
    //     //         'success'    => true,
    //     //         'login_at'   => now(),
    //     //     ]);
    //     // } catch (\Exception $e) {
    //     //     // If logging fails for any reason, don't prevent the user from logging in.
    //     //     // You can log this error to a file or another service if needed.
    //     //     \Log::error('Failed to create user log: ' . $e->getMessage());
    //     // }
    //     // // =============== END: LOGIC FOR SUCCESSFUL LOGIN ===============

    //     // // return redirect()->intended(RouteServiceProvider::HOME);
    //     // return redirect()->intended(route('dashboard'));
    // }


    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // ১. ক্রেডেনশিয়াল ও ক্যাপচা ভ্যালিডেশন এবং পাসওয়ার্ড চেক
        $request->authenticate();

        // ইউজার অবজেক্ট নেওয়া
        $user = \Illuminate\Support\Facades\Auth::user();

        // ২. ইমেইল ভেরিফিকেশন চেক
        if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail()) {
            \Illuminate\Support\Facades\Auth::logout();

            $message = 'Your email address is not verified. Please verify it first.';
            if ($request->wantsJson()) {
                return response()->json(['errors' => ['email' => [$message]]], 422);
            }

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        // ৩. ডিভাইস লিমিট চেক
        try {
            $activeSessions = \DB::table('sessions')->where('user_id', $user->id)->count();

            if ($activeSessions >= 2) {
                \DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->orderBy('last_activity', 'asc')
                    ->limit(1)
                    ->delete();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Session limit check failed: ' . $e->getMessage());
        }

        // ৪. নতুন ডিভাইস বা আইপি অ্যালার্ট লজিক
        $knownIp = \Sndpbag\AdminPanel\Models\UserLog::where('user_id', $user->id)
            ->where('ip_address', $request->ip())
            ->where('success', true)
            ->exists();

        if (!$knownIp) {
            try {
                $user->notify(new \Sndpbag\AdminPanel\Notifications\NewDeviceLoginNotification($request->ip()));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('New Device Alert Email failed: ' . $e->getMessage());
            }
        }

        // ৫. OTP জেনারেশন এবং সিকিউরিটি সেটিংস
        $request->session()->put('login_remember', $request->boolean('remember'));

        $plainOtp = random_int(100000, 999999);

        $user->update([
            'otp' => \Illuminate\Support\Facades\Hash::make($plainOtp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // OTP ইমেইল পাঠানো
        try {
            $user->notify(new \Sndpbag\AdminPanel\Notifications\LoginOtpNotification($plainOtp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP Email sending failed: ' . $e->getMessage());
        }

        // ৬. ইউজারকে লগআউট করা (OTP ভেরিফাই না হওয়া পর্যন্ত)
        $emailForOtp = $user->email;
        \Illuminate\Support\Facades\Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->put('otp_email', $emailForOtp);

        if ($request->wantsJson()) {
            return response()->json(['redirect_url' => route('otp.show')]);
        }

        return redirect()->route('otp.show');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ============== START: LOGIC FOR LOGOUT ==============
        try {
            // Logout korar thik age, bortoman session-er ID-ti neoya hocche

            // Find the log entry for the current session using the session ID
            $log = UserLog::where('session_id', $request->session()->getId())
                ->whereNotNull('login_at') // Ensure it's a login record
                ->whereNull('logout_at')   // Ensure it hasn't been logged out yet
                ->first();

            // If a matching log is found, update the logout_at timestamp
            if ($log) {
                $log->update(['logout_at' => now()]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to update user log on logout: ' . $e->getMessage());
        }
        // =============== END: LOGIC FOR LOGOUT ===============

        $user = Auth::user();

        // Proceed with the standard logout process
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // 1. Database theke remember_token muche dewa hocche
        if ($user) {
            $user->remember_token = null;
            $user->save();
        }

        return redirect('/login')->withCookie(Cookie::forget(Auth::guard()->getRecallerName()));
    }
}
