<?php

namespace Database\Seeders;

use App\Models\Lang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lang::create([
            'name' => [
                'en' => 'English',
                'ar' => 'الإنجليزية'
            ],
            'code' => 'en'
        ]);
        Lang::create([
            'name' => [
                'en' => 'Arabic',
                'ar' => 'العربية'
            ],
            'code' => 'ar'
        ]);
        
    }
}
