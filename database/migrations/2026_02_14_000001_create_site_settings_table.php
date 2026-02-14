<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default maintenance mode settings
        DB::table('site_settings')->insert([
            ['key' => 'maintenance_mode', 'value' => 'false', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_message', 'value' => 'We are currently performing scheduled maintenance. We will be back shortly!', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_bypass_token', 'value' => \Illuminate\Support\Str::random(32), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_allowed_ips', 'value' => '[]', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
