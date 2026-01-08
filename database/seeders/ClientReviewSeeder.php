<?php

namespace Database\Seeders;

use App\Models\ClientReview;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 6; $i++) {
            ClientReview::create([
                'name' =>  [
                    'ar' => 'محمد العتيبي',
                    'en' => 'Mohammed Alotaibi'
                ],
                'text' => [
                    'ar' => 'أفضل قهوة مختصة جربتها في السعودية. الجودة والخدمة استثنائية.',
                    'en' => 'The best specialty coffee I have tried in Saudi Arabia. Exceptional quality and service.'
                ],
                'rate' => 5
            ]);
        }
    }
}
