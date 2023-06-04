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
        Schema::create('product_developer_details', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('developer_id')->constrained('developers')->cascadeOnDelete();
            $table->integer('total_unit')->default(1);
            $table->string('certificate')->nullable();
            $table->integer('area')->nullable();
            $table->string('price_list_image')->nullable();
            $table->string('side_plan_image')->nullable();
            $table->boolean('status')->default(true);
            $table->primary(['product_id', 'developer_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_developer_details');
    }
};
