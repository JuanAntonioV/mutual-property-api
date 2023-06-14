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
        Schema::create('project_details', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->integer('total_unit')->default(1);
            $table->string('certificate')->nullable();
            $table->integer('area')->nullable();
            $table->string('price_list_image')->nullable();
            $table->string('side_plan_image')->nullable();
            $table->string('brochure_file')->nullable();
            $table->string('facilities')->default('-');
            $table->boolean('status')->default(true);
            $table->primary(['project_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
