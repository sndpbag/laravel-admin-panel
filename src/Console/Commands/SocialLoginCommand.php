<?php

namespace Sndpbag\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SocialLoginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-panel:social-login {--google : Enable Google Login} {--facebook : Enable Facebook Login} {--remove : Disable all social logins}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable/Disable Google and Facebook Login';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $google = $this->option('google');
        $facebook = $this->option('facebook');
        $remove = $this->option('remove');

        $configPath = config_path('admin-panel.php');

        if (!File::exists($configPath)) {
            $this->error('Config file not found. Please publish the package configuration first.');
            return;
        }

        $configContent = File::get($configPath);

        if ($remove) {
            $configContent = preg_replace("/'google' => true/", "'google' => false", $configContent);
            $configContent = preg_replace("/'facebook' => true/", "'facebook' => false", $configContent);
            $this->info('Social login disabled.');
        } else {
            if ($google) {
                $configContent = preg_replace("/'google' => false/", "'google' => true", $configContent);
                $this->updateEnv(['GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET', 'GOOGLE_REDIRECT_URL']);
                $this->info('Google login enabled.');
            }
            if ($facebook) {
                $configContent = preg_replace("/'facebook' => false/", "'facebook' => true", $configContent);
                $this->updateEnv(['FACEBOOK_CLIENT_ID', 'FACEBOOK_CLIENT_SECRET', 'FACEBOOK_REDIRECT_URL']);
                $this->info('Facebook login enabled.');
            }

            if (!$google && !$facebook) {
                $this->info('Please specify a flag: --google, --facebook, or --remove');
                return;
            }
        }

        File::put($configPath, $configContent);

        $this->info('Configuration updated successfully.');
    }

    protected function updateEnv($keys)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return;
        }

        $envContent = File::get($envPath);
        $appUrl = env('APP_URL', 'http://localhost');

        foreach ($keys as $key) {
            if (strpos($envContent, $key) === false) {
                $value = '';
                if (str_contains($key, 'REDIRECT_URL')) {
                    $provider = strtolower(explode('_', $key)[0]);
                    $value = "{$appUrl}/login/{$provider}/callback";
                }

                $envContent .= "\n{$key}={$value}";
                $this->info("Added {$key} to .env");
            }
        }

        File::put($envPath, $envContent);
    }
}
