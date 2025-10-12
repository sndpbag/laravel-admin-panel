<?php

namespace Sndpbag\AdminPanel\Providers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AdminPanelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // এখানে আমরা আমাদের প্যাকেজের routes, views, migrations লোড করার কোড লিখব।

         // ১. প্যাকেজের রাউট (Routes) লোড করার জন্য
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        // ২. প্যাকেজের মাইগ্রেশন (Migrations) লোড করার জন্য
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // ৩. প্যাকেজের ভিউ (Views) লোড করার জন্য
        // 'admin-panel' নামটি জরুরি, এটি ভিউগুলোকে চেনার জন্য ব্যবহৃত হবে।
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin-panel');

        // ৪. প্যাকেজের অ্যাসেট (CSS/JS) এবং অন্যান্য ফাইল পাবলিশ করার জন্য
        // ব্যবহারকারী `php artisan vendor:publish` কমান্ড চালালে এই ফাইলগুলো কপি হবে।
        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/admin-panel'),
        ], 'admin-panel-assets');


          $this->publishes([
            __DIR__.'/../../config/admin-panel.php' => config_path('admin-panel.php'),
        ], 'admin-panel-config'); // একটি ট্যাগ দেওয়া হলো

         // Eta config/auth.php file ke dynamically override korbe.
        Config::set('auth.providers.users.model', \Sndpbag\AdminPanel\Models\User::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          $this->mergeConfigFrom(
            __DIR__.'/../../config/admin-panel.php', 'admin-panel'
        );
    }
}