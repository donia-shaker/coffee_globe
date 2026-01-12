<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => [
                    'ar' => 'المحمصة',
                    'en' => 'Roastery'
                ],
                'text' => [
                    'ar' => 'تحميص احترافـــــــــــي يبرز الخصائص الحسية لكل نوع بن',
                    'en' => 'Professional roasting that highlights the sensory characteristics of each coffee type'
                ],
            ],
            [
                'name' => [
                    'ar' => 'الكوفي شوب',
                    'en' => 'Coffee Shop'
                ],
                'text' => [
                    'ar' => 'مساحة تجربة وتذوق بإشراف باريستا محترفين',
                    'en' => 'A tasting and experience space supervised by professional baristas'
                ],
            ],
            [
                'name' => [
                    'ar' => 'البن الأخضر',
                    'en' => 'Green Coffee'
                ],
                'text' => [
                    'ar' => 'توريد بن أخضر مختص للمحامص والتجار',
                    'en' => 'Supplying specialty green coffee to roasters and traders'
                ],
            ]
        ];

        foreach ($services as $service) {
            $exists = Service::where('name->en', $service['name']['en'])->exists();
            if (!$exists) {
                Service::create($service);
            }
        }
    }
}
