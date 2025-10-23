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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('brand', 100)->nullable();
            
            $table->enum('measurement_type', ['Unit based', 'Pack-based']);

            // Pack-based fields
            $table->foreignId('pack_type_unit_id')->nullable()->constrained('units'); 
            $table->foreignId('inner_unit_id')->nullable()->constrained('units'); 
            $table->integer('quantity_per_pack')->nullable(); 
            $table->decimal('weight_per_inner_unit', 8, 2)->nullable();
            $table->foreignId('weight_unit_inner_id')->nullable()->constrained('units'); 
            
            // Weight Type fields
            $table->enum('weight_type', ['Fixed', 'Ranged']);
            $table->decimal('weight_per_pack', 8, 2)->nullable(); 
            $table->decimal('min_weight_per_pack', 8, 2)->nullable(); 
            $table->decimal('max_weight_per_pack', 8, 2)->nullable(); 
            
            // Unit-based field
            $table->foreignId('unit_id')->nullable()->constrained('units'); 
            
            $table->enum('base_unit', ['G', 'ML', 'PCS']); // G, ML, or PCS (Auto-derived)
            
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
