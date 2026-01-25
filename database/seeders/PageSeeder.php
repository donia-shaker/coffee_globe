<?php

namespace Database\Seeders;

use App\Models\PageImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PageImage::create([
            'name' => [
                'ar' => 'من نحن',
                'en' => 'About Us ',
            ]
        ]);

        PageImage::create([
            'name' => [
                'ar' => ' حلولنا',
                'en' => 'Our Solutions ',
            ]
        ]);

        PageImage::create([
            'name' => [
                'ar' => ' المدونة',
                'en' => ' Blogs ',
            ]
        ]);

        PageImage::create([
            'name' => [
                'ar' => ' الأسئلة الشائعة',
                'en' => 'Frequently Questions',
            ]
        ]);
    }
}
