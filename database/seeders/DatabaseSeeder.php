<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Account
        User::firstOrCreate(
            ['email' => 'admin@ecommerce.test'],
            [
                'name' => 'Admin Store',
                'mobile' => '081234567890',
                'password' => Hash::make('password'),
                'utype' => 'ADM',
            ]
        );

        // Customer Account
        User::firstOrCreate(
            ['email' => 'user@ecommerce.test'],
            [
                'name' => 'Customer User',
                'mobile' => '081234567891',
                'password' => Hash::make('password'),
                'utype' => 'USR',
            ]
        );
    }
}
