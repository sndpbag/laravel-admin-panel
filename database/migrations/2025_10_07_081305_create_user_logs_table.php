<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_logs', function (Blueprint $table) {
           $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('email')->nullable(); // To store email for failed attempts
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->string('session_id')->nullable()->index(); // Index for faster lookups
        
        // Detailed Information
        $table->string('browser')->nullable();
        $table->string('platform')->nullable(); // e.g., Windows, macOS, Linux
        $table->string('device')->nullable(); // e.g., Desktop, Mobile, Tablet
        
        // Location Information
        $table->string('country')->nullable();
        $table->string('city')->nullable();
        
        // Status and Timestamps
        $table->string('login_type')->default('normal'); // e.g., normal, socialite
        $table->boolean('success')->default(true);
        $table->timestamp('login_at')->useCurrent();
        $table->timestamp('logout_at')->nullable();
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
