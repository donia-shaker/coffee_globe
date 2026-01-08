<?php

namespace Database\Seeders;

use App\Models\WhyUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhyUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhyUs::create([
            'name' =>  [
                'ar' => 'مرونة الدفع',
                'en' => 'Flexible payment'
            ],
            'text' => [
                'ar' => 'تقديم خيارات دفع متعددة تناسب الشركات الكبيرة والصغيرة',
                'en' => 'Providing multiple payment options suitable for both large and small businesses'
            ]
        ]);

        WhyUs::create([
            'name' =>   [
                'ar' => 'الدعم الفني المتواصل',
                'en' => 'Continuous technical support'
            ],
            'text' => [
                'ar' => 'فريقنا على استعداد دائم للإجابة على استفساراتك الفنية',
                'en' => 'Our team is always ready to answer your technical inquiries'
            ]
        ]);
    }
}
