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
        if (!Role::where('name', 'super_admin')->exists()) {
            Role::create([
                'name' => 'super_admin',
                'display_name' => [
                    'ar' => 'مدير النظام',
                    'en' => 'Super Admin'
                ]
            ]);
        }

        if (!Role::where('name', 'admin')->exists()) {
            Role::create([
                'name' => 'admin',
                'display_name' => [
                    'ar' => 'مدير',
                    'en' => 'admin'
                ],
            ]);
        }

        if (!Role::where('name', 'customer')->exists()) {
            Role::create([
                'name' => 'customer',
                'display_name' => [
                    'ar' => 'عميل',
                    'en' => 'customer'
                ]
            ]);
        }
        
        if (!Role::where('name', 'supplier')->exists()) {
            Role::create([
                'name' => 'supplier',
                'display_name' => [
                    'ar' => 'مورد',
                    'en' => 'supplier'
                ]
            ]);
        }
    }
}
