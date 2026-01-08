<?php

namespace Database\Seeders;

use App\Models\FQ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FQ::create([
            'name' =>   [
                'ar' => 'ما هي القهوة المختصة؟',
                'en' => 'What is specialty coffee?'
            ],
            'text' =>  [
                'ar' => 'القهوة المختصة هي قهوة عالية الجودة تحصل على تقييم 80+ نقطة من قبل خبراء معتمدين...',
                'en' => 'Specialty coffee is high-quality coffee that scores 80+ points by certified experts...'
            ]
        ]);

        FQ::create([
            'name' =>  [
                'ar' => 'كيف أختار طريقة التحضير المناسبة؟',
                'en' => 'How do I choose the right brewing method?'
            ],
            'text' =>  [
                'ar' => 'القهوة المختصة هي قهوة عالية الجودة تحصل على تقييم 80+ نقطة من قبل خبراء معتمدين...',
                'en' => 'Specialty coffee is high-quality coffee that scores 80+ points by certified experts...'
            ]
        ]);
    }
}
