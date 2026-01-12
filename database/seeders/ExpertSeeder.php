<?php

namespace Database\Seeders;

use App\Models\Expert;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        $experts = [
            [
                'name' => [
                    'ar' => 'خبرة في البن الأخضر المختص',
                    'en' => 'Expertise in specialty green coffee'
                ],
                'text' => [
                    'ar' => 'نعتمد على علاقات مباشرة وموثوقة مع المزارعين حول العالم لضمان الحصول على بن أخضر يلتزم بأعلى المعايير الأخلاقية والجودة، نتبع بروتوكولات صارمة لتقييم البن الأخضر بما في ذلك تحليل الرطوبة والكثافة، وتقييم الكوب ((Cupping من قبل مقيمينا المعتمدين.',
                    'en' => 'We rely on direct and trusted relationships with farmers worldwide to ensure green coffee that meets the highest ethical and quality standards. We follow strict protocols to evaluate green coffee, including moisture and density analysis, and cupping assessment by our certified evaluators.'
                ]
            ],
            [
                'name' => [
                    'ar' => 'التحميص (المحمصة)',
                    'en' => 'Roasting (Roastery)'
                ],
                'text' => [
                    'ar' => 'التحميص لدينا ليس مجرد عملية تسخين، بل هو علم دقيق نستخدم فيه أحدث التقنيات لإبراز الإيحاءات المميزة لكل مصدر بن، مع حرصنا الكامل لوصول البن إليك في ذروة النكهة',
                    'en' => 'Our roasting is not just a heating process; it is a precise science where we use the latest techniques to highlight the distinctive notes of each coffee origin, ensuring it reaches you at peak flavor.'
                ]
            ],
            [
                'name' => [
                    'ar' => 'ضمان استدامة الجودة والطعم',
                    'en' => 'Ensuring consistent quality and taste'
                ],
                'text' => [
                    'ar' => 'نستخدم أدوات تحليل دقيقة ومنحنيات تحميص (Roast Profiles) ونظام اختبار دوري (Cupping) في كل مرة، وذلك لضمان أن النكهة والجودة التي تحبها اليوم هي نفسها التي ستجدها كل يوم، هذا الالتزام هو سر ثبات مذاق Coffee Globe.',
                    'en' => 'We use precise analysis tools, roast profiles, and a regular cupping system each time to ensure that the flavor and quality you love today are the same you will find every day. This commitment is the secret behind Coffee Globe's consistent taste.'
                ]
            ],
            [
                'name' => [
                    'ar' => 'التزامنا بالقهوة السعودية',
                    'en' => 'Our commitment to Saudi coffee'
                ],
                'text' => [
                    'ar' => 'نولي اهتماماً خاصاً لتوفير أجود أنواع البن للقهوة السعودية، ونسعى للمساهمة في تطوير معايير الجودة وتقديم القهوة السعودية بعدة خلطات سرية تناسب الذوق السعودي باستخدام أجود أنواع البن ووفق أعلى المعايير الدولية',
                    'en' => 'We pay special attention to providing the finest coffee for Saudi coffee, striving to develop quality standards and offer Saudi coffee in several secret blends that suit the Saudi taste, using the best coffee beans and following the highest international standards.'
                ]
            ],
            [
                'name' => [
                    'ar' => 'فن التحضير والضيافة',
                    'en' => 'The art of preparation and hospitality'
                ],
                'text' => [
                    'ar' => 'فريقنا من الباريستا المحترفين يتمتع بمهارات متقدمة في جميع طرق التحضير (الإسبريسو، التقطير، وغيرهما). مقهانا هو مساحة للتجربة والتدريب، وليس مجرد نقطة بيع',
                    'en' => 'Our team of professional baristas possesses advanced skills in all preparation methods (espresso, pour-over, and more). Our café is a space for experimentation and training, not just a point of sale.'
                ]
            ],
            [
                'name' => [
                    'ar' => 'خبراء على استعداد لخدمتك',
                    'en' => 'Experts ready to serve you'
                ],
                'text' => [
                    'ar' => 'يتألف فريق Coffee Globe من مجموعة من المحترفين المتخصصين يحملون شهادات دولية في تقييم البن والتحميص، نحن نؤمن بالتعلم المستمر وتبادل المعرفة، كل فرد في فريقنا هو جزء من مهمة توفير أفضل وأدق كوب قهوة يمكنك تذوقه',
                    'en' => 'The Coffee Globe team consists of a group of specialized professionals holding international certifications in coffee evaluation and roasting. We believe in continuous learning and knowledge sharing, and every member of our team is part of the mission to deliver the best and most precise cup of coffee you can taste.'
                ]
            ]
        ];

        foreach ($experts as $expert) {
            $exists = Expert::where('name->en', $expert['name']['en'])->exists();
            if (!$exists) {
                Expert::create($expert);
            }
        }
    }
}
