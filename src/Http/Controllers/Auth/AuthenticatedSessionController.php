<?php

namespace  Sndpbag\AdminPanel\Http\Controllers\Auth;

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
    public function store(LoginRequest $request): RedirectResponse
    {


          $request->authenticate();


    $user = \Illuminate\Support\Facades\Auth::user();

      // User "Remember Me" checkbox-e tick diyeche kina, sheta session-e rakha hocche
    $request->session()->put('login_remember', $request->boolean('remember'));

    $plainOtp = random_int(100000, 999999);

    $user->update([
        'otp' => Hash::make($plainOtp), // OTP HASH KORA HOCHCHE
        'otp_expires_at' => now()->addMinutes(5),
    ]);

    try {
        Notification::send($user, new LoginOtpNotification($plainOtp));
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('OTP Email sending failed: ' . $e->getMessage());
    }

    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    $request->session()->put('otp_email', $user->email);

    return redirect()->route('otp.show');
    
        // // This authenticates the user based on the validated data from LoginRequest
        // $request->authenticate();

        // // Regenerate the session ID to prevent session fixation attacks
        // $request->session()->regenerate();

        // // ============== START: LOGIC FOR SUCCESSFUL LOGIN ==============
        // try {
        //     // Use the Agent package to parse the user-agent string
        //     $agent = new Agent();
            
        //     // Use the Location package to get geographic info from the IP address
        //     // The 'false' parameter can be used for testing with a local IP like 127.0.0.1
        //     $location = Location::get($request->ip());

        //     // Create the log entry with all the detailed information
        //     UserLog::create([
        //         'user_id'    => auth()->id(),
        //         'email'      => auth()->user()->email,
        //         'ip_address' => $request->ip(),
        //         'user_agent' => $request->userAgent(),
        //         'session_id' => $request->session()->getId(), // Store session ID to track logout
                
        //         // Details from Agent
        //         'browser'    => $agent->browser(),
        //         'platform'   => $agent->platform(),
        //         'device'     => $agent->isDesktop() ? 'Desktop' : ($agent->isTablet() ? 'Tablet' : 'Mobile'),

        //         // Details from Location
        //         'country'    => $location ? $location->countryName : 'Unknown',
        //         'city'       => $location ? $location->cityName : 'Unknown',
                
        //         // Status and Timestamps
        //         'success'    => true,
        //         'login_at'   => now(),
        //     ]);
        // } catch (\Exception $e) {
        //     // If logging fails for any reason, don't prevent the user from logging in.
        //     // You can log this error to a file or another service if needed.
        //     \Log::error('Failed to create user log: ' . $e->getMessage());
        // }
        // // =============== END: LOGIC FOR SUCCESSFUL LOGIN ===============

        // // return redirect()->intended(RouteServiceProvider::HOME);
        // return redirect()->intended(route('dashboard'));
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
