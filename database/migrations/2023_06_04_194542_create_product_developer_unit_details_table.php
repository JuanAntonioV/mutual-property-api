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
        Schema::create('product_developer_unit_details', function (Blueprint $table) {
            $table->foreignId('product_developer_unit_id')->constrained('product_developer_units')
                ->cascadeOnDelete();
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
            $table->primary('product_developer_unit_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_developer_unit_details');
    }
};
