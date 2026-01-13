<?php

namespace Database\Seeders;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        if (Blog::count() < 4) {
            $count = 4 - Blog::count();
            for ($i = 0; $i < $count; $i++) {
                Blog::create([
                    'name' => [
                        'ar' => 'دليل المبتدئين لتذوق القهوة المختصة',
                        'en' => 'Beginner\'s guide to tasting specialty coffee'
                    ],
                    'text' => [
                        'ar' => 'القهوة المختصة هي أكثر من مجرد مشروب، إنها تجربة حسية تجمع بين الجودة العالية، الخبرة، والابتكار. تتميز هذه القهوة بأنها تحصل على تقييم 80+ نقطة من قبل خبراء معتمدين، مما يضمن أن كل كوب تقدمه يحتوي على النكهة المثالية والخصائص الفريدة لمصدره.

تبدأ رحلة القهوة المختصة مع اختيار أفضل الحبوب الخضراء من المزارعين مباشرة حول العالم، مع الالتزام بأعلى المعايير الأخلاقية والجودة. بعد ذلك، يتم تحميص الحبوب بدقة باستخدام أحدث تقنيات التحميص والمنحنيات المثالية لكل صنف، لضمان إبراز النكهات الطبيعية لكل حبة.

في الكوفي شوب المختص، يتم تقديم القهوة بإشراف باريستا محترفين، مع التركيز على فن التحضير والضيافة، لتكون تجربة تذوق لا تُنسى. سواء كنت مبتدئاً أو خبيراً، تقدم القهوة المختصة تجربة فريدة تعكس شغف الجميع بالمذاق والجودة.',
                        'en' => 'Specialty coffee is more than just a beverage; it is a sensory experience that combines high quality, expertise, and innovation. This coffee is scored 80+ points by certified experts, ensuring that every cup you enjoy delivers the perfect flavor and unique characteristics of its origin.

The journey begins with selecting the finest green beans directly from farmers around the world, adhering to the highest ethical and quality standards. Then, the beans are carefully roasted using the latest roasting techniques and optimal profiles for each variety, highlighting the natural flavors of every bean.

At a specialty coffee shop, the coffee is served under the supervision of professional baristas, focusing on the art of preparation and hospitality, making every tasting experience unforgettable. Whether you are a beginner or an expert, specialty coffee offers a unique journey that reflects everyone\'s passion for taste and quality.'
                    ],
                    'date' => Carbon::now(),
                ]);
            }
        }
    }
}
