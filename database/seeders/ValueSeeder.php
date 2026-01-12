<?php

namespace Database\Seeders;

use App\Models\Value;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name' => [
                    'ar' => 'الجودة',
                    'en' => 'Quality'
                ],
                'text' => [
                    'ar' => 'التزام صارم بأعلى معايير الجودة في كل مرحلة',
                    'en' => 'Strict commitment to the highest quality standards at every stage'
                ]
            ],
            [
                'name' => [
                    'ar' => 'الاستدامة',
                    'en' => 'Sustainability'
                ],
                'text' => [
                    'ar' => 'دعم الممارسات المستدامة والتجارة العادلة',
                    'en' => 'Supporting sustainable practices and fair trade'
                ]
            ],
            [
                'name' => [
                    'ar' => 'الخبرة',
                    'en' => 'Expertise'
                ],
                'text' => [
                    'ar' => 'فريق من المحترفين المعتمدين والشغوفين',
                    'en' => 'A team of certified and passionate professionals'
                ]
            ],
            [
                'name' => [
                    'ar' => 'الابتكار',
                    'en' => 'Innovation'
                ],
                'text' => [
                    'ar' => 'استخدام أحدث التقنيات والطرق في التحميص',
                    'en' => 'Using the latest technologies and methods in roasting'
                ]
            ]
        ];

        foreach ($values as $value) {
            $exists = Value::where('name->en', $value['name']['en'])->exists();
            if (!$exists) {
                Value::create($value);
            }
        }
    }
}
