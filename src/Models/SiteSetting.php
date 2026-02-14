<?php

namespace Sndpbag\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return self::get('maintenance_mode', 'false') === 'true';
    }

    /**
     * Get maintenance bypass token
     */
    public static function getBypassToken()
    {
        return self::get('maintenance_bypass_token');
    }

    /**
     * Get allowed IPs
     */
    public static function getAllowedIps()
    {
        $ips = self::get('maintenance_allowed_ips', '[]');
        return json_decode($ips, true) ?? [];
    }
}
