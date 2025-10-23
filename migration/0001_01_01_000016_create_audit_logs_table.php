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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('role_abbreviation', 1)->nullable(); // A, L, S
            $table->string('action_type', 100); // e.g., 'Module Created', 'Stock Added'
            $table->text('details')->nullable(); // Detailed change log/message
            
            // For report filtering
            $table->string('related_entity_type', 100)->nullable(); // e.g., 'Module', 'Ingredient', 'Class'
            $table->unsignedBigInteger('related_entity_id')->nullable(); 
            
            $table->timestamp('action_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
