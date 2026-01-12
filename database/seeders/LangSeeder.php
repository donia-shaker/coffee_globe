<?php

namespace Database\Seeders;

use App\Models\Lang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LangSeeder extends Seeder
{
    public function run(): void
    {
        if (!Lang::where('code', 'en')->exists()) {
            Lang::create([
                'name' => [
                    'en' => 'English',
                    'ar' => 'الإنجليزية'
                ],
                'code' => 'en'
            ]);
        }
        
        if (!Lang::where('code', 'ar')->exists()) {
            Lang::create([
                'name' => [
                    'en' => 'Arabic',
                    'ar' => 'العربية'
                ],
                'code' => 'ar'
            ]);
        }
    }
}
