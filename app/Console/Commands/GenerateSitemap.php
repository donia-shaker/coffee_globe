<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.xml for Coffee Globe website with multilingual support';

    public function handle()
    {
        $this->info('Generating multilingual sitemap...');

        $sitemap = Sitemap::create();
        $baseUrl = config('app.url', 'https://coffeeglobe.sa');

        // ========================================
        // Static Pages - Arabic (default)
        // ========================================
        $staticPages = [
            ['path' => '/', 'priority' => 1.0, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['path' => '/about', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['path' => '/solution', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['path' => '/blogs', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['path' => '/fqs', 'priority' => 0.6, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['path' => '/contact', 'priority' => 0.7, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['path' => '/privacy-policy', 'priority' => 0.3, 'freq' => Url::CHANGE_FREQUENCY_YEARLY],
            ['path' => '/terms-of-service', 'priority' => 0.3, 'freq' => Url::CHANGE_FREQUENCY_YEARLY],
        ];

        foreach ($staticPages as $page) {
            $arUrl = $baseUrl . $page['path'];
            $enUrl = $baseUrl . '/en' . ($page['path'] === '/' ? '' : $page['path']);

            // Add Arabic version
            $sitemap->add(
                Url::create($arUrl)
                    ->setLastModificationDate(Carbon::today())
                    ->setChangeFrequency($page['freq'])
                    ->setPriority($page['priority'])
            );

            // Add English version
            $sitemap->add(
                Url::create($enUrl)
                    ->setLastModificationDate(Carbon::today())
                    ->setChangeFrequency($page['freq'])
                    ->setPriority($page['priority'])
            );
        }

        // ========================================
        // Blog Pages - Dynamic
        // ========================================
        Blog::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->chunk(100, function ($blogs) use ($sitemap, $baseUrl) {
                foreach ($blogs as $blog) {
                    $lastMod = $blog->updated_at ?? $blog->created_at;

                    // Arabic blog URL
                    $arBlogUrl = "{$baseUrl}/blog/{$blog->id}";
                    $sitemap->add(
                        Url::create($arBlogUrl)
                            ->setLastModificationDate($lastMod)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.7)
                    );

                    // English blog URL
                    $enBlogUrl = "{$baseUrl}/en/blog/{$blog->id}";
                    $sitemap->add(
                        Url::create($enBlogUrl)
                            ->setLastModificationDate($lastMod)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.7)
                    );
                }
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Multilingual sitemap generated successfully at public/sitemap.xml');
        $this->info('Total URLs: ' . ($staticPages ? count($staticPages) * 2 : 0) . ' static + ' . (Blog::where('is_active', 1)->count() * 2) . ' blog URLs');
    }
}
