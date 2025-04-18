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
    \App\Models\User::where('email', 'admin@example.com')->delete();

    \App\Models\User::create([
        'name' => env('ADMIN_NAME', 'Admin User'),
        'email' => env('ADMIN_EMAIL', 'admin@example.com'),
        'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')),
        'is_admin' => true,
    ]);
}

}
