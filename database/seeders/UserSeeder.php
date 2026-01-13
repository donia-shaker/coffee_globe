<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'super_admin@gmail.com')->exists()) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'super_admin@gmail.com',
                'phone' => '7777777777',
                'password' => Hash::make('123123'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('super_admin');
        }
    }
}
