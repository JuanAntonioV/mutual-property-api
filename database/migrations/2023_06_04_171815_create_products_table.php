<?php

use App\Entities\ProductEntities;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs');
            $table->foreignId('categories_id')->constrained('categories');
            $table->foreignId('sub_categories_id')->constrained('sub_categories');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('cover_image');
            $table->string('address');
            $table->string('status', 45)->default(ProductEntities::STATUS_DRAFT);
            $table->boolean('is_sold')->default(ProductEntities::IS_SOLD);
            $table->bigInteger('price');
            $table->string('map_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
