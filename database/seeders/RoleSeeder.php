<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'super_admin',
            'display_name' => [
                'ar' => 'مدير النظام',
                'en' => 'Super Admin'
            ]
        ]);

        Role::create([
            'name' => 'admin',
            'display_name' => [
                'ar' => 'مدير',
                'en' => 'admin'
            ],
        ]);

        Role::create([
            'name' => 'customer',
            'display_name' => [
                'ar' => 'عميل',
                'en' => 'customer'
            ]
        ]);
        Role::create([
            'name' => 'supplier',
            'display_name' => [
                'ar' => 'مورد',
                'en' => 'supplier'
            ]
        ]);
    }
}
