<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        if (Slider::count() === 0) {
            Slider::create([
                'title' => [
                    'ar' => 'مصدرك الأول لأجود أنواع البن المختص في المملكة العربية السعودية',
                    'en' => 'Yemen Café Trading',
                ],
                'text' => [
                    'ar' => 'Coffee Globe علامة متخصصة في القهوة المختصة، تجمع بين خبرة عميقة، وتحكم صارم بالجودة، وتقنيات حديثة تمتد من المصدر إلى الفنجان.',
                    'en' => 'Authenticity of Yemeni coffee and quality roasting',
                ],
            ]);
        }
    }
}
