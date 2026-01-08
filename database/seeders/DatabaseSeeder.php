<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(LangSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(SliderSeeder::class);
        $this->call(AboutPageSeeder::class);
        $this->call(SocialSeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(ValueSeeder::class);
        $this->call(ExpertSeeder::class);
        $this->call(ServiceCompanySeeder::class);
        $this->call(WhyUsSeeder::class);
        $this->call(ClientReviewSeeder::class);
        $this->call(BlogSeeder::class);

    }
}
