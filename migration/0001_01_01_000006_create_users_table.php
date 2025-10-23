<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 128);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('restrict');
            $table->string('student_lecturer_admin_id', 24)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->string('phone_number', 20)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
        
        // REMOVED: Duplicate password_reset_tokens and sessions tables
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        // REMOVED: Duplicate table drops
    }
};