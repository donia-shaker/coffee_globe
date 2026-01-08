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
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super_admin@gmail.com',
            'phone' => '7777777777',
            'password' => Hash::make('123123'),
        ]);
        $user->assignRole('super_admin');

    }
}
