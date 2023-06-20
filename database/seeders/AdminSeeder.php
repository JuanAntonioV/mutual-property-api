<?php

namespace Database\Seeders;

use App\Entities\FolderEntities;
use App\Models\Staffs\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Staff::find(1)) return;

        $userId = DB::table('staffs')->insertGetId([
            'username' => 'admin',
            'email' => 'admin@email.com',
            'photo' => FolderEntities::DEFAULT_PROFILE_PICTURE,
            'password' => Hash::make('admin123'),
            'status' => true,
            'is_super' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('staff_details')->insert([
            'staff_id' => $userId,
            'full_name' => 'Admin',
            'position' => 'admin',
            'phone_number' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
