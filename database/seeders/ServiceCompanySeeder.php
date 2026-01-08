<?php

namespace Database\Seeders;

use App\Models\ServiceCompany;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceCompany::create([
            'name' => [
                'ar' => 'توريد البن الأخضر بكميات',
                'en' => 'Supplying green coffee in bulk'
            ],
            'text' => [
                'ar' => 'مرونة في الطلبات الكبيرة وضمان ثبات الجودة',
                'en' => 'Flexibility in large orders and ensuring consistent quality'
            ]
        ]);

        ServiceCompany::create([
            'name' => [
                'ar' => 'الاستشارات الفنية للتحميص',
                'en' => 'Technical roasting consultations'
            ],
            'text' => [
                'ar' => 'خبراء التحميص لدينا يشاركونك خبرتهم لاختيار آلية التحميص الأنسب لكل صنف',
                'en' => 'Our roasting experts share their knowledge to choose the most suitable roasting method for each variety'
            ]
        ]);

        ServiceCompany::create([
            'name' => [
                'ar' => 'التخصيص',
                'en' => 'Customization'
            ],
            'text' =>  [
                'ar' => 'تصميم خلطات حبوب خاصة بعلامة العميل التجارية مع سرية تامة',
                'en' => 'Designing custom coffee bean blends for the client’s brand with complete confidentiality'
            ]
        ]);

        ServiceCompany::create([
            'name' =>  [
                'ar' => 'الاستدامة في وفرة القهوة',
                'en' => 'Sustainability in coffee supply'
            ],
            'text' =>  [
                'ar' => 'مرونة في الطلبات الكبيرة وضمان ثبات الجودة',
                'en' => 'Flexibility in large orders and ensuring consistent quality'
            ]
        ]);

        ServiceCompany::create([
            'name' => [
                'ar' => 'الاستدامة في النكهة والطعم',
                'en' => 'Sustainability in flavor and taste'
            ],
            'text' =>  [
                'ar' => 'خبراء التحميص لدينا يشاركونك خبرتهم لاختيار آلية التحميص الأنسب لكل صنف',
                'en' => 'Our roasting experts share their knowledge to choose the most suitable roasting method for each variety'
            ]
        ]);
    }
}
