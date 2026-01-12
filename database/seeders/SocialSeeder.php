<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!SocialMedia::where('icon', 'fab fa-whatsapp')->exists()) {
            SocialMedia::create([
                'name' =>  ' واتساب',
                'url' => '#',
                'icon' => 'fab fa-whatsapp'
            ]);
        }

        if (!SocialMedia::where('icon', 'fab fa-youtube')->exists()) {
            SocialMedia::create([
                'name' => ' youtube',
                'url' => '#',
                'icon' => 'fab fa-youtube'
            ]);
        }

        if (!SocialMedia::where('icon', 'fab fa-facebook-f')->exists()) {
            SocialMedia::create([
                'name' => ' facebook',
                'url' => '#',
                'icon' => 'fab fa-facebook-f'
            ]);
        }

        if (!SocialMedia::where('icon', 'fab fa-instagram')->exists()) {
            SocialMedia::create([
                'name' =>  'instagram',
                'url' => '#',
                'icon' => 'fab fa-instagram'
            ]);
        }
    }
}
