<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Sndpbag\AdminPanel\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLogController extends Controller
{
     public function index()
    {
        // Fetch logs with user data, latest first, and paginate
        // $logs = UserLog::with('user')->latest('login_at')->paginate(15);
         // বর্তমান লগইন করা ইউজারের ID
    $userId = Auth::id();

    // শুধুমাত্র ওই ইউজারের লগ গুলো আনো, সর্বশেষ লগ প্রথমে
    $logs = UserLog::with('user')
        ->where('user_id', $userId)
        ->latest('login_at')
        ->paginate(8);
        
        return view('admin-panel::dashboard.user-logs.index', compact('logs'));
    }
}
