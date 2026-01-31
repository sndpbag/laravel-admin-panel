<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function generate()
    {
        $code = strtolower(substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 6));
        session(['login_captcha' => $code]);

        $image = imagecreatetruecolor(120, 45);
        $background = imagecolorallocate($image, 25, 104, 91); // আপনার প্রাইমারি কালার
        $text_color = imagecolorallocate($image, 255, 255, 255);
        
        imagefilledrectangle($image, 0, 0, 120, 45, $background);
        
        // কিছু নয়েজ যোগ করা
        for($i=0; $i<5; $i++) {
            $line_color = imagecolorallocate($image, 64, 64, 64);
            imageline($image, 0, rand(0,45), 120, rand(0,45), $line_color);
        }

        imagestring($image, 5, 30, 15, $code, $text_color);
        
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit;
    }
}