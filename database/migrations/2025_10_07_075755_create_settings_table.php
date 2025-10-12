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
        Schema::create('settings', function (Blueprint $table) {
              $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('primary_color')->default('#1A685B');
            $table->string('secondary_color')->default('#FF5528');
            $table->string('accent_color')->default('#FFAC00');
            $table->string('font_family')->default("'Poppins', sans-serif");
            $table->enum('font_size', ['sm', 'md', 'lg'])->default('md');
            $table->boolean('dark_mode')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
