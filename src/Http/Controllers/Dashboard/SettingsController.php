<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Sndpbag\AdminPanel\Models\SiteSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Database থেকে theme settings লোড করা, না থাকলে ডিফল্ট মান ব্যবহার করা
        $defaultSettings = [
            'primary_color' => '#1A685B',
            'secondary_color' => '#FF5528',
            'accent_color' => '#FFAC00',
            'font_family' => "'Poppins', sans-serif",
            'font_size' => 'md',
            'dark_mode' => false,
        ];

        $settings = array_merge($defaultSettings, $user->theme_settings ?? []);



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

        // Maintenance Mode Settings
        $maintenanceEnabled = SiteSetting::get('maintenance_mode', 'false') === 'true';
        $maintenanceMessage = SiteSetting::get('maintenance_message', 'We are currently performing scheduled maintenance.');
        $bypassToken = SiteSetting::getBypassToken();
        $allowedIps = implode(', ', SiteSetting::getAllowedIps());

        return view('admin-panel::dashboard.settings.index', compact(
            'user',
            'settings',
            'notifications',
            'maintenanceEnabled',
            'maintenanceMessage',
            'bypassToken',
            'allowedIps'
        ));
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
            'primary_color' => 'sometimes|string',
            'secondary_color' => 'sometimes|string',
            'accent_color' => 'sometimes|string',
            'font_family' => 'sometimes|string',
            'font_size' => 'sometimes|in:sm,md,lg',
            'dark_mode' => 'nullable', // Allow boolean or 'system' string
        ]);

        // Save theme settings to database
        $user = auth()->user();
        $currentSettings = $user->theme_settings ?? [];
        $newSettings = array_merge($currentSettings, $validated);

        $user->update(['theme_settings' => $newSettings]);

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

    /**
     * Backup database and download SQL file
     */
    public function backupDatabase()
    {
        try {
            $dbName = config('database.connections.mysql.database');

            // Generate filename with timestamp
            $filename = 'backup_' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/' . $filename);

            // Get all tables
            $tables = \DB::select('SHOW TABLES');
            $tablesKey = 'Tables_in_' . $dbName;

            // Start SQL dump content
            $sql = "-- Database Backup\n";
            $sql .= "-- Database: {$dbName}\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tablesKey;

                // Drop table statement
                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "-- Table structure for table `{$tableName}`\n";
                $sql .= "-- --------------------------------------------------------\n\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";

                // Create table statement
                $createTable = \DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

                // Table data
                $rows = \DB::table($tableName)->get();

                if ($rows->count() > 0) {
                    $sql .= "-- Dumping data for table `{$tableName}`\n\n";

                    foreach ($rows as $row) {
                        $row = (array) $row;
                        $columns = array_keys($row);
                        $values = array_values($row);

                        // Escape values
                        $escapedValues = array_map(function ($value) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            return "'" . addslashes($value) . "'";
                        }, $values);

                        $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
                    }

                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // Write to file
            file_put_contents($backupPath, $sql);

            // Check if backup was successful
            if (!file_exists($backupPath)) {
                throw new \Exception('Database backup failed. Could not create backup file.');
            }

            // Download the file
            return response()->download($backupPath, $filename, [
                'Content-Type' => 'application/sql',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['backup' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle maintenance mode on/off
     */
    public function toggleMaintenanceMode(Request $request)
    {
        try {
            $isEnabled = $request->input('enabled') === 'true';

            SiteSetting::set('maintenance_mode', $isEnabled ? 'true' : 'false');

            if ($isEnabled) {
                // Generate new bypass token when enabling
                $newToken = \Illuminate\Support\Str::random(32);
                SiteSetting::set('maintenance_bypass_token', $newToken);
                SiteSetting::set('maintenance_started_at', now());
            }

            return response()->json([
                'success' => true,
                'message' => $isEnabled ? 'Maintenance mode enabled' : 'Maintenance mode disabled',
                'bypass_url' => $isEnabled ? route('maintenance.bypass', ['token' => SiteSetting::getBypassToken()]) : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle maintenance mode: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update maintenance mode settings
     */
    public function updateMaintenanceSettings(Request $request)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'estimated_time' => 'nullable|date',
            'allowed_ips' => 'nullable|string',
        ]);

        try {
            if (isset($validated['message'])) {
                SiteSetting::set('maintenance_message', $validated['message']);
            }

            if (isset($validated['estimated_time'])) {
                SiteSetting::set('maintenance_estimated_time', $validated['estimated_time']);
            }

            if (isset($validated['allowed_ips'])) {
                // Convert comma-separated IPs to JSON array
                $ips = array_map('trim', explode(',', $validated['allowed_ips']));
                $ips = array_filter($ips); // Remove empty values
                SiteSetting::set('maintenance_allowed_ips', json_encode($ips));
            }

            return response()->json([
                'success' => true,
                'message' => 'Maintenance settings updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bypass maintenance mode with secret token
     */
    public function bypassMaintenance($token)
    {
        $validToken = SiteSetting::getBypassToken();

        if ($token === $validToken) {
            session(['maintenance_bypass' => true]);
            return redirect()->route('dashboard')->with('success', 'Maintenance mode bypassed! You can now access the site.');
        }

        return redirect('/')->withErrors(['bypass' => 'Invalid bypass token.']);
    }
}
