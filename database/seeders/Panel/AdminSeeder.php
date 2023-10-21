<?php

namespace Database\Seeders\Panel;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'mobile' => '09123456789',
            'is_admin' => true,
        ], [
            'name' => 'مدیریت',
            'family' => 'فروشگاه',
            'mobile' => '09123456789',
            'is_admin' => true,
            'password' => Hash::make(123456)
        ]);
    }
}
