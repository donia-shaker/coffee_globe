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
        SocialMedia::create([
            'name' =>  ' واتساب',
            'url' => '#',
            'icon' => 'fab fa-whatsapp'
        ]);

        SocialMedia::create([
            'name' => ' youtube',
            'url' => '#',
            'icon' => 'fab fa-youtube'
        ]);

        SocialMedia::create([
            'name' => ' facebook',
            'url' => '#',
            'icon' => 'fab fa-facebook-f'
        ]);

         SocialMedia::create([
            'name' =>  'instagram',
            'url' => '#',
            'icon' => 'fab fa-instagram'
        ]);
    }
}
