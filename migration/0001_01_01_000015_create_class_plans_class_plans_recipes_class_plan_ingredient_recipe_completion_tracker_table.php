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
        Schema::create('class_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->integer('week_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('module_id')->constrained('modules')->onDelete('restrict');
            $table->enum('student_type', ['Individual', 'Groups']);
            $table->integer('quantity_portion'); // Total students or total groups
            $table->enum('status', ['Pending', 'Verified', 'Ongoing', 'Completed', 'Overdue'])->default('Pending');
            
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->unique(['class_id', 'week_number']); // One plan per week per class
        });
        
        Schema::create('class_plan_recipes', function (Blueprint $table) {
            $table->foreignId('class_plan_id')->constrained('class_plans')->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->primary(['class_plan_id', 'recipe_id']);
        });

        Schema::create('class_plan_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_plan_id')->constrained('class_plans')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('restrict');
            $table->string('brand', 100)->nullable();
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict'); // Stock Unit
            $table->decimal('quantity_packs', 12, 4)->nullable(); 
            $table->bigInteger('quantity_total_base_unit'); // Total required quantity (converted)
            $table->enum('source', ['Recipe', 'Purchased Order']);
            $table->timestamps();
        });
        
        Schema::create('recipe_completion_tracker', function (Blueprint $table) {
            $table->foreignId('class_plan_id')->constrained('class_plans')->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->enum('status', ['Not Started', 'Completed'])->default('Not Started');
            $table->primary(['class_plan_id', 'recipe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_completion_tracker');
        Schema::dropIfExists('class_plan_ingredients');
        Schema::dropIfExists('class_plan_recipes');
        Schema::dropIfExists('class_plans');
    }
};
