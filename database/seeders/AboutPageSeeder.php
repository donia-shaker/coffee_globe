<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'name' => [
                    'ar' => 'نبذة عن الشركة',
                    'en' => 'About the Company',
                ],
                'text' => [
                    'ar' => "تأسست شركة البن اليمني للتجارة لتكون علامة تجارية رائدة في عالم البن والتوابل في المملكة العربية السعودية. انطلاقًا من شغفنا بالجودة، تخصصنا في إنتاج واستيراد أجود محاصيل القهوة، وأفخر أنواع الهيل، ومجموعة متنوعة من البهارات الطبيعية من أفضل المزارع حول العالم. ندرك في شركة البن اليمني أن القهوة والبهارات هما قلب الضيافة والمطبخ السعودي؛ لذا نحرص على تطبيق معايير دقيقة في الفرز والتنقية والتغليف، لنضمن لعملائنا نكهات طازجة، وروائح زكية، ومنتجات خالية من الشوائب تلبي ميولهم ومجالسهم.",
                    'en' => "Al-Yemeni Coffee Company was established to become a leading brand in the world of coffee and spices in the Kingdom of Saudi Arabia. Driven by our passion for quality, we specialize in producing and importing the finest coffee crops, premium cardamom, and a diverse range of natural spices sourced from the best farms around the world. At Al-Yemeni Coffee Company, we understand that coffee and spices are at the heart of Saudi hospitality and cuisine; therefore, we adhere to precise standards of sorting, purification, and packaging to provide our customers with fresh flavors, rich aromas, and products free of impurities — perfectly suited for their gatherings and taste preferences.",
                ],
            ],
            [
                'name' => [
                    'ar' => ' الرؤية',
                    'en' => 'Vision',
                ],
                'text' => [
                    'ar' =>" أن نكون الوجهة الأولى والمرجع الأكثر موثوقية في المملكة العربية السعودية لعشاق القهوة الأصلية والهيل والبهارات الفاخرة، وأن نرتقي بمفهوم الضيافة العربية من خلال تقديم منتجات تجمع بين عراقة الماضي وأعلى معايير الجودة العالمية.",
                    'en' => "To be the first and most trusted destination in the Kingdom of Saudi Arabia for lovers of authentic coffee, premium cardamom, and luxury spices. We aim to elevate the concept of Arab hospitality by offering products that blend rich heritage with the highest global quality standards.",
                ],
            ],
            [
                'name' => [
                    'ar' => ' الرسالة',
                    'en' => 'Mission',
                ],
                'text' => [
                    'ar' => "نلتزم بإثراء المائدة السعودية عبر استيراد وبيع أجود محاصيل البن والهيل وأندر التوابل المنتقاة بعناية من المزارع الطبيعية. نسعى لتقديم تجربة تذوق استثنائية لعملائنا من خلال ضمان نقاء النّتاجات، والحفاظ على نكهاتها الغنية، والتميّز في الخدمة، بما يعكس قيم الكرم والأصالة، ويحقق رضا كل من يبحث عن المذاق الرفيع والجودة التي لا تُضاهى.",
                    'en' => "We are committed to enriching the Saudi table by importing and offering the finest coffee, cardamom, and rare spices carefully selected from natural farms. We strive to deliver an exceptional tasting experience through guaranteed purity, preserved rich flavors, and distinguished service — reflecting values of generosity and authenticity, and meeting the expectations of those who seek refined taste and unmatched quality.",
                ],
            ]
        ];

        foreach ($pages as $page) {
            $exists = AboutPage::where('name->en', $page['name']['en'])->exists();
            if (!$exists) {
                AboutPage::create($page);
            }
        }
    }
}
