<?php

use App\Entities\StaffEntitites;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('username', 45)->unique();
            $table->string('password');
            $table->string('email', 45)->unique();
            $table->boolean('status')->default(StaffEntitites::STATUS_ACTIVE);
            $table->boolean('is_super')->default(StaffEntitites::IS_NOT_SUPER_ADMIN);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
