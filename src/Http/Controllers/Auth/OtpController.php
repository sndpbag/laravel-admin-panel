<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\User;
use Sndpbag\AdminPanel\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- Hash check korar jonno
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class OtpController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('login');
        }
        return view('admin-panel::auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|numeric|digits:6']);
        $email = $request->session()->get('otp_email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Your session has expired. Please try again.']);
        }
        $user = User::where('email', $email)->first();

        // --- HASHED OTP CHECK KORA HOCHCHE ---
        if (!$user || !$user->otp || !Hash::check($request->otp, $user->otp) || now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'The OTP you entered is invalid or has expired.']);
        }
        // --- HASH CHECK SHESH ---


        // 1. Session theke "Remember Me" totthyo-ti neoya hocche
        $remember = $request->session()->get('login_remember', false);

        // 2. User-ke "Remember Me" totthyo-shaho login korano hocche
        Auth::login($user, $remember);

        // Auth::login($user);
        $request->session()->regenerate();
        $user->update(['otp' => null, 'otp_expires_at' => null]);
        $request->session()->forget('otp_email');

        try {
            //  Use the Agent package to parse the user-agent string
            $agent = new Agent();

            // Use the Location package to get geographic info from the IP address
            // The 'false' parameter can be used for testing with a local IP like 127.0.0.1
            $location = Location::get($request->ip());

            // Create the log entry with all the detailed information
            UserLog::create([
                'user_id' => auth()->id(),
                'email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(), // Store session ID to track logout

                // Details from Agent
                'browser' => $agent->browser(),
                'platform' => $agent->platform(),
                'device' => $agent->isDesktop() ? 'Desktop' : ($agent->isTablet() ? 'Tablet' : 'Mobile'),

                // Details from Location
                'country' => $location ? $location->countryName : 'Unknown',
                'city' => $location ? $location->cityName : 'Unknown',

                // Status and Timestamps
                'success' => true,
                'login_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create user log after OTP: ' . $e->getMessage());
        }

        return redirect()->intended(route('dashboard'));
    }
    public function resend(Request $request)
    {
        $email = $request->session()->get('otp_email');
        if (!$email) {
            return response()->json(['message' => 'Session expired. Please login again.'], 419);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Generate new OTP
        $plainOtp = random_int(100000, 999999);

        // Update User
        $user->update([
            'otp' => \Illuminate\Support\Facades\Hash::make($plainOtp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send Email
        try {
            $user->notify(new \Sndpbag\AdminPanel\Notifications\LoginOtpNotification($plainOtp));
        } catch (\Exception $e) {
            // Log error but generally return success to user to avoid panic, or return error if critical
        }

        return response()->json(['message' => 'New OTP sent successfully!']);
    }
}
