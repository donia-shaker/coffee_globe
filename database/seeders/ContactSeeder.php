<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        if (!ContactUs::where('icon', 'fas fa-phone')->exists()) {
            ContactUs::create([
                'name' => [
                    'ar' => ' الهاتف',
                    'en' => 'Phone'
                ],
                'value' => [
                    'ar' => '009665202266',
                    'en' => '009665202266'
                ],
                'icon' => 'fas fa-phone'
            ]);
        }

        if (!ContactUs::where('icon', 'fas fa-envelope')->exists()) {
            ContactUs::create([
                'name' => [
                    'ar' => 'البريد الإلكتروني',
                    'en' => 'Email'
                ],
                'value' => [
                    'ar' => 'info@coffeeglobe',
                    'en' => 'info@coffeeglobe'
                ],
                'icon' => 'fas fa-envelope'
            ]);
        }

        if (!ContactUs::where('icon', 'fas fa-location-dot')->exists()) {
            ContactUs::create([
                'name' => [
                    'ar' => ' الموقع',
                    'en' => 'Location'
                ],
                'value' => [
                    'ar' => 'الرياض، المملكة العربية السعودية',
                    'en' => 'Riyadh, Saudi Arabia'
                ],
                'icon' => 'fas fa-location-dot'
            ]);
        }
    }
}
