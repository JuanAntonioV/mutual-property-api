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
        Schema::create('product_details', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('bedroom');
            $table->integer('bathroom');
            $table->integer('floor');
            $table->integer('garage');
            $table->string('certificate');
            $table->string('soil_area');
            $table->integer('land_area');
            $table->integer('building_area');
            $table->string('building_size');
            $table->string('building_condition');
            $table->string('building_direction');
            $table->integer('electricity_capacity');
            $table->string('water_source');
            $table->string('floor_plan_image')->nullable();
            $table->primary('product_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
