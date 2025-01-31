<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
        ], [
            'name' => env('ADMIN_NAME', 'Admin User'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')),
            'is_admin' => true,
        ]);
    }
}
