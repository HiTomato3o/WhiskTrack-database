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
        // FIX: Renamed from 'sessions' to 'academic_sessions' to prevent conflict
        // with Laravel's built-in session table, which caused the error.
        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_name', 100)->unique(); // e.g., Session I:2025/2026
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            // FIX: Use 'academic_session_id' to reference the new table name
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->string('semester_name', 10); // e.g., SEMESTER 1
            $table->date('start_date');
            $table->date('end_date');
            $table->unique(['academic_session_id', 'semester_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('academic_sessions');
    }
};
