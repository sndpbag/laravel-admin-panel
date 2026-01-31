<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function generate()
    {
        if (!extension_loaded('gd')) {
            return response()->json(['error' => 'GD Library is not installed on this server.'], 500);
        }

        $code = strtolower(substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 6));
        session(['login_captcha' => $code]);
        session()->save(); // Explicitly save session to ensure it persists before response generation

        return response()->stream(function () use ($code) {
            $image = imagecreatetruecolor(120, 45);
            $background = imagecolorallocate($image, 25, 104, 91); // Primary color
            $text_color = imagecolorallocate($image, 255, 255, 255);

            imagefilledrectangle($image, 0, 0, 120, 45, $background);

            // Add noise
            for ($i = 0; $i < 5; $i++) {
                $line_color = imagecolorallocate($image, 64, 64, 64);
                imageline($image, 0, rand(0, 45), 120, rand(0, 45), $line_color);
            }

            imagestring($image, 5, 30, 15, $code, $text_color);

            imagepng($image);
            imagedestroy($image);
        }, 200, ['Content-Type' => 'image/png']);
    }
}