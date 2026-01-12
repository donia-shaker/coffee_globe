<?php

namespace Database\Seeders;

use App\Models\ClientReview;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (ClientReview::count() < 6) {
            $count = 6 - ClientReview::count();
            for ($i = 0; $i < $count; $i++) {
                ClientReview::create([
                    'name' => [
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
}
