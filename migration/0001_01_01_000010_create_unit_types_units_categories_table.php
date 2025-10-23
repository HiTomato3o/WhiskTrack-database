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
        Schema::create('unit_types', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Weight', 'Volume', 'Count', 'Pack'])->unique();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name', 10)->unique(); // KG, G, L, ML, PCS, PKT
            $table->enum('base_unit', ['G', 'ML', 'PCS']); // G, ML, or PCS
            $table->decimal('conversion_factor', 10, 6); // Factor to convert to base unit
            $table->foreignId('unit_type_id')->constrained('unit_types');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // Flours & Starches
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('units');
        Schema::dropIfExists('unit_types');
    }
};
