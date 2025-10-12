<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
 
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
  public function index()
    {
     $user = auth()->user();

        // Database থেকে theme settings লোড করা, না থাকলে ডিফল্ট মান ব্যবহার করা
        $settings = session('theme_settings', [
            'primary_color' => '#1A685B',
            'secondary_color' => '#FF5528',
            'accent_color' => '#FFAC00',
            'font_family' => "'Poppins', sans-serif",
            'font_size' => 'md',
        ]);


        
        // ১. একটি ডিফল্ট অ্যারে তৈরি করা হচ্ছে, যা নিশ্চিত করবে সব key সবসময় থাকবে
        $defaultNotifications = [
            'email' => false,
            'push' => false,
            'sms' => false,
            'weekly' => false,
        ];

        // ২. ডাটাবেস থেকে raw ডেটা লোড করা হচ্ছে
        $userNotificationsData = $user->notification_settings;
        
        // ৩. একটি খালি অ্যারে তৈরি করা হচ্ছে ডেটা রাখার জন্য
        $userSettingsArray = [];

        // ৪. ডেটাটি অ্যারে নাকি স্ট্রিং, তা চেক করা হচ্ছে
        if (is_array($userNotificationsData)) {
            // যদি মডেলের casting কাজ করে এবং এটি একটি অ্যারে হয়, তাহলে সরাসরি ব্যবহার করা হচ্ছে
            $userSettingsArray = $userNotificationsData;
        } elseif (is_string($userNotificationsData) && !empty($userNotificationsData)) {
            // যদি এটি একটি স্ট্রিং হয়, তাহলে সেটিকে অ্যারে-তে রূপান্তরিত করা হচ্ছে
            $userSettingsArray = json_decode($userNotificationsData, true) ?? []; // json_decode ব্যর্থ হলে খালি অ্যারে ব্যবহার হবে
        }

        // ৫. সবশেষে, ডিফল্ট অ্যারের সাথে ইউজারের সেটিংস (যা এখন একটি নিশ্চিত অ্যারে) মার্জ করা হচ্ছে
        $notifications = array_merge($defaultNotifications, $userSettingsArray);


        return view('admin-panel::dashboard.settings.index', compact('user', 'settings', 'notifications'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:10',
        ]);

        // Update profile logic here
        auth()->user()->update($validated);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password logic here
        auth()->user()->update(['password' => Hash::make($validated['new_password'])]);
        
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
            'font_family' => 'required|string',
            'font_size' => 'required|in:sm,md,lg',
        ]);

        // Save theme settings to database or session
        session(['theme' => $validated]);
        
        return response()->json(['success' => true, 'message' => 'Theme applied successfully!']);
    }

    public function updateNotifications(Request $request)
    {
       $user = auth()->user();

    // পুরনো সেটিংস নাও
    $oldSettings = $user->notification_settings ?? [];

    // নতুন মানগুলো request থেকে নাও
    $newSettings = [
        'email' => $request->has('email'),
        'push' => $request->has('push'),
        'sms' => $request->has('sms'),
        'weekly' => $request->has('weekly'),
    ];

    // পুরনো সেটিংসের সাথে নতুনগুলো merge করো
    $updatedSettings = array_merge($oldSettings, $newSettings);

    // আপডেট করো
    $user->update([
        'notification_settings' => $updatedSettings
    ]);

    return response()->json(['success' => true]);
    }

    

     public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user = auth()->user();
            $image = $request->file('profile_image');

            // 1. পুরানো ছবির পাথ (path) নিয়ে নেওয়া
            $oldImagePath = $user->profile_image;

            // 2. নতুন ছবির জন্য একটি ইউনিক নাম তৈরি করা
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // 3. ছবিটি 'storage/app/public/profile_images' ফোল্ডারে সেভ করা
            // putFileAs ব্যবহার করাটা storeAs-এর থেকে বেশি নির্ভরযোগ্য
            Storage::disk('public')->putFileAs('profile_images', $image, $filename);

            // 4. যদি পুরানো ছবি থাকে, তাহলে সেটি ডিলিট করা
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            // 5. ডাটাবেসে নতুন ছবির পাথ আপডেট করা
            $user->update(['profile_image' => 'profile_images/' . $filename]);

            // 6. সফলভাবে আপলোড হওয়ার পর রেসপন্স পাঠানো
            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully!',
                'image_url' => Storage::url('profile_images/' . $filename) // সঠিক URL জেনারেট করা
            ]);

        } catch (\Exception $e) {
            // যদি কোনো সমস্যা হয়, তাহলে error রেসপন্স পাঠানো
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
