<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'email' => 'admin@admin.com',
            'name' => 'admin',
            'password' => Hash::make('admin@123'),
            'type' => User::ADMIN_TYPE
        ]);
    }
}
