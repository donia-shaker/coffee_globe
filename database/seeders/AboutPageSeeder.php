<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'name' => [
                    'ar' => 'قصتنا',
                    'en' => 'Our Story',
                ],
                'text' => [
                    "ar" => "بدأت Coffee Globe من شغف عميق بالقهوة المختصة ورؤية واضحة: توفير أجود أنواع البن للمملكة العربية السعودية. فريقنا يضم خبراء Q-Graders معتمدين ومحمصين محترفين يعملون معاً لتقديم تجربة قهوة استثنائية. من خلال علاقاتنا المباشرة مع مزارع البن حول العالم، نضمن الحصول على أفضل الحبوب الخضراء التي نحمصها بعناية فائقة.",
                    "en" => "Coffee Globe started from a deep passion for specialty coffee and a clear vision: providing the finest coffee beans to Saudi Arabia. Our team includes certified Q-Graders and professional roasters working together to deliver an exceptional coffee experience. Through our direct relationships with coffee farms worldwide, we ensure we get the best green beans, which we roast with utmost care."

                ]
            ]
        ];

        foreach ($pages as $page) {
            $exists = AboutPage::where('name->en', $page['name']['en'])->exists();
            if (!$exists) {
                AboutPage::create($page);
            }
        }
    }
}
