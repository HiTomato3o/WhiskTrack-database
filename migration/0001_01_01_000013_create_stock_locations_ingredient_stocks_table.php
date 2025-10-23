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
        Schema::create('stock_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // Central Store, Class Storage
        });
        
        // This table stores the stock level for Central Store AND all Class Storages
        Schema::create('ingredient_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('stock_locations')->onDelete('restrict');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade'); // NULL for Central Store
            
            $table->bigInteger('stock_total_base_unit')->default(0); // ALWAYS stored in base unit (g, ml, pcs)
            $table->decimal('stock_packs', 10, 2)->default(0.00); // Derived/Calculated
            
            // Only applicable/used for Central Store stock (min_level, alerts)
            $table->bigInteger('minimum_level_base_unit')->default(0); 
            $table->boolean('is_alert_on')->default(true);
            
            $table->timestamp('last_updated_at')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->unique(['ingredient_id', 'location_id', 'class_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_stock');
        Schema::dropIfExists('stock_locations');
    }
};
