<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Api;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Sndpbag\AdminPanel\Models\User;
use Sndpbag\AdminPanel\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Sndpbag\AdminPanel\Notifications\LoginOtpNotification;
use Sndpbag\AdminPanel\Notifications\NewDeviceLoginNotification;

class ApiAuthController extends Controller
{
    /**
     * Handle API Registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign default 'user' role
        $role = Role::where('slug', 'user')->first();
        if (!$role) {
            $role = Role::create([
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Default user role',
            ]);
        }
        if (method_exists($user, 'roles')) {
            $user->roles()->attach($role->id);
        }

        event(new Registered($user));

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful! Please check your email to verify your account.',
            'user' => $user
        ], 201);
    }

    /**
     * Handle API Login and return Sanctum Token (or trigger OTP).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided credentials are incorrect.'
            ], 422);
        }

        // Check if user is active
        if (isset($user->status) && $user->status !== 'active') {
             return response()->json([
                'status' => 'error',
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        // --- OTP Logic ---
        // For simplicity, we trigger OTP if it's generally required or per user
        // Here we'll follow the package's OTP logic: always send OTP if we want to match web logic
        
        $plainOtp = random_int(100000, 999999);
        $user->update([
            'otp' => Hash::make($plainOtp),
            'otp_expires_at' => now()->addMinutes(10), // Give more time for API/Mobile
        ]);

        try {
            $user->notify(new LoginOtpNotification($plainOtp));
        } catch (\Exception $e) {
            \Log::error('API OTP Email sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'otp_required',
            'message' => 'An OTP has been sent to your email. Please verify to complete login.',
            'email' => $user->email
        ]);
    }

    /**
     * Verify OTP and return Sanctum Token.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->otp || !Hash::check($request->otp, $user->otp) || now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The OTP you entered is invalid or has expired.'
            ], 422);
        }

        // Clear OTP
        $user->update(['otp' => null, 'otp_expires_at' => null]);

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Authentication successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Resend OTP to the user's email.
     */
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $plainOtp = random_int(100000, 999999);
        $user->update([
            'otp' => Hash::make($plainOtp),
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            $user->notify(new LoginOtpNotification($plainOtp));
        } catch (\Exception $e) {
            \Log::error('API Resend OTP Email sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'A new OTP has been sent to your email.'
        ]);
    }

    /**
     * Resend Email Verification link.
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'info',
                'message' => 'Email is already verified.'
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'success',
            'message' => 'Verification link sent to your email.'
        ]);
    }

    /**
     * Handle Forgot Password (Send Reset Link).
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'success',
                'message' => __($status)
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __($status)
        ], 422);
    }

    /**
     * Handle Reset Password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

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

        if ($status == Password::PASSWORD_RESET) {
            return response()->json([
                'status' => 'success',
                'message' => __($status)
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __($status)
        ], 422);
    }

    /**
     * Get authenticated user details.
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ]);
    }

    /**
     * Handle API Logout (Revoke Token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out and token revoked'
        ]);
    }
}
