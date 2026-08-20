<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use App\Models\YouTubeVideo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Sonakshi Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'phone' => '+91 98765 00000',
            ]
        );

        // 2. Customer Users
        $customer1 = User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Priya Sharma',
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'phone' => '+91 98765 43210',
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'meera.sharma@example.com'],
            [
                'name' => 'Meera Sharma',
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'phone' => '+91 98765 11223',
            ]
        );

        $customer3 = User::updateOrCreate(
            ['email' => 'riya.desai@example.com'],
            [
                'name' => 'Riya Desai',
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'phone' => '+91 98765 99887',
            ]
        );

        // 3. Tags
        $bridalTag = Tag::firstOrCreate(['slug' => 'bridal'], ['name' => 'Bridal']);
        $festiveTag = Tag::firstOrCreate(['slug' => 'festive'], ['name' => 'Festive']);
        $trendingTag = Tag::firstOrCreate(['slug' => 'trending'], ['name' => 'Trending on YouTube']);
        $weddingTag = Tag::firstOrCreate(['slug' => 'wedding-edit'], ['name' => 'The Wedding Edit']);

        // 4. Categories
        $lacha = Category::updateOrCreate(
            ['slug' => 'lacha'],
            [
                'name' => 'Lacha',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBxl_zCQSFBOaL1VH2hbGU38yRRF4hCWLcouaPZjwt3LCBA5X4WnOdHb3Gy-pmrlSRSWOgstv1RjGqdiUww6e3qY4iSdtsL9xu3s1CbMVw-vTuQ9BTOgEAQjhCPKqQdw3vigFPyfO7_YyEvnbRr0jP62LuyU0sD93Oopf95rSPUYmFphXtmRyJ5bincBfly_Jf_y4cWwRDw6zOym8EVlYxMCZai8XQIeWoH7dVg6iPGTqH2H1AMw5xq',
                'description' => 'Royal handcrafted Banarasi and Silk Lacha sets with opulent zari borders.',
                'display_order' => 1,
                'is_active' => true,
            ]
        );

        $lehenga = Category::updateOrCreate(
            ['slug' => 'lehenga'],
            [
                'name' => 'Lehenga',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDQdZmmZ0xIUWh6bR5_D0yN1XalEFa3ufiPA311tu-O3FW2WumPOWeiUItzOLmvSs6E9hqMeWNixjSPy158fFASd1RdlRVyHBwlsGAyYHpT7A6M1D1r4phTe-1rEzEhUpf4cLNuJZxyxebXhfir_1wno4XzmXQ2fBCtl3GuHPr0QM4pZNCiRPp15yrWyUr30hdPjRckHk9m2QRNXxxyW4SEZ8Oyo6Yeh2yEkgPwhpDkxjlMP2htMs3X',
                'description' => 'Exquisite bridal & festive lehengas with hand-embroidered zardosi details.',
                'display_order' => 2,
                'is_active' => true,
            ]
        );

        $suits = Category::updateOrCreate(
            ['slug' => 'suits'],
            [
                'name' => 'Suits & Sets',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBQfznMSXpwnKSX7NUy_eKrkEsZtbeO22gB5SuD-DD_spUktCCBw3pae7p09cPg6UygkvgUvZtEoBY8av1vKr-YLKAXYcpY7w-IYisveGZmNKyFNdXV6L3AwvnbhXZwQ6KkztKZk9jxF4kVUZCZ0vdRG_NOdOQl7z_VNjBltIttY6u-td9pOTG049tThLWn341jS5Qt3YyUgjP5ZSLZeCQtvw1MAgt9WrQSA39M4bq4jm4U1WovmO4X',
                'description' => 'Elegant Chanderi, Georgette, and Anarkali suits crafted for timeless celebrations.',
                'display_order' => 3,
                'is_active' => true,
            ]
        );

        $dupattas = Category::updateOrCreate(
            ['slug' => 'dupattas'],
            [
                'name' => 'Dupattas',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD6OUFNoPez3K8ahLWCH3MX-X7qprNXffphzya2LXli4wa76urLZf4N1UB_lS1kcaZqye-KNugTVOCoUU_skzM8GDE1zx6B3hZYooBjjbsJRm1LZsGd_c6PGuaJIAk_NRGH35PV2G6B9qkZq8nz5mbPd-R_dQlDMvZylXabBSC7VrXREaxjD-cCXEvi6fEH7_FTZfHAr4LFKVchpdIGMqF80eZANbhOkYVWaSkhVA4yDmjIhHZjWEcr',
                'description' => 'Handwoven Banarasi, Mukaish, and Bandhani statement dupattas.',
                'display_order' => 4,
                'is_active' => true,
            ]
        );

        $festive = Category::updateOrCreate(
            ['slug' => 'festive-wear'],
            [
                'name' => 'Festive Wear',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDXgk8Wk6XQl7Aka_DQbiytwM1LwXXfbar63XF0zGvhkRmRcI_p0VvMZLy4kvDgRzQoj-aNgdypAIxaraj2XKINxprNBm-MiN1fjY586yNmIeQCDg4ipUjuulGediAV1L_vqpm1DpLDEZ4rnGywwK3KmQGhxe40eoby3Wn6T1XFPLZHG2FbFWNeIByqmuAfypD5lbImosHdxszROZl1cTP42J0KHrmsUH5KIkRuDjVvbZ6X4mvRXGa9',
                'description' => 'Radiant celebratory wear for weddings, sangeet, and festive milestones.',
                'display_order' => 5,
                'is_active' => true,
            ]
        );

        // 5. Products
        $p1 = Product::updateOrCreate(
            ['slug' => 'royal-banarasi-lacha'],
            [
                'category_id' => $lacha->id,
                'name' => 'Royal Banarasi Lacha',
                'sku' => 'SS-BAN-01',
                'price' => 12499.00,
                'compare_price' => 15000.00,
                'stock' => 18,
                'low_stock_threshold' => 5,
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBHo2AI-nuhCDtY7OqAzC7IuIQinAr6fQtpX8m0hWogHweqDrvQyU1BS5Y83-MMJOBkKtlKnvaqO5iBdPxGPekyDKAKl4k711cKT2TqACShhWws87SUjzduLOk9ZJAPsri35wMSmkCCZX3rl8ZmGf24drcMIPch3PLxunrxx0DwjrWT8JBPX4KuyCgkXUXxkMckXHk8Q7BDImoU-UCR05O4GD6O45E4mLyPPypNlRM9jK3Dc6vshirC',
                'gallery_images' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuCRwGBPQiyFCmYnd-ER240zXtxF9fDMLMKpTS9G6j71l1ecOk5JZ4AgPa5avhMZXz_aY5D4RbIC8FRVss6pFbKcfYd7TTktCuduqqteMeru_fcQapbuIL6ntp5ueZude4lt5Qkb78_HgyLqJ_Q7zSkixlYSIX38I6PQSm9aEUh1qPmZZYtAQeeKqS3kxef9EZbOIQ1oqTXDR3I_k0eVIncuE5PDVMVQevwLwr7jbUFd9bkEmu3GpZz',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuBvw51YQIT-HvVpf-uAofJsIy9gKO5QgQKSNownX3b76g_HshGPXOK41xifdqqgSapgoHhBDbkZzYfs_pypFYM9urXur_N7uzJXp6UzaTFinHoxMpTRk7EvW7S6PfSstTlpYTTNbBg8TJ1HMUJ_gGzC0hRjbI9BUxFTdGLvlRyn6KCr5b6-2O9m3crOrNSYi2EmKxDDzhV-RaMQj6tjBQ80U9NdZjds6z-LJYGkN5SqoqUtdiVkjean',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuCgT9OitLF4PE_zLxBs8mNiVB7haKsAyBJSF49QaaRJvl3uX-scTYzUmfF8qcsi5LA5nfHYrSJzJF5sYoewlbAV8RTtfObZ_LL7Djrt40vbfw5PWxbv-7zbiBgVXMHzFh8GZ6swN6YTQpGJf2ZNzB-15e78iQnTxTULEUxE9ZDB0F28kZaH67KZzlAXDPFPISB8bsuWov9x3UqrVUAL4cSE-LbExxB6wjIzLcmtmPkCwB7mUMelVTPt',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuCgtsZ8T0nJa79E4N4SQicn0_K_6rIMZIBnB-9UD7L5UvJETZRo_GzFrDa6-9YDliriHNBgTzF_D5dUsnCrHMdBAj5v6T9DHB9Qt7htCaBn8Gj_k34c4YgDNPXKP0VGZ3gRBBq4j_W77RIsDRiw-IGvdDAObC29YWDNZNFwQqD5ltaNK6gxP6EuwSFfltA5-4COHpkw4Ucpohu-1EilnB5UI9jAR9GTCFhqZHMYl1y2t-UEx0hLKndv',
                ],
                'sizes' => ['S', 'M', 'L', 'XL', 'CUSTOM FIT'],
                'colors' => ['Crimson Red', 'Maroon Gold'],
                'description' => 'Embrace the grandeur of heritage with our Royal Banarasi Lacha. Handwoven with intricate zari motifs that tell tales of traditional craftsmanship, this ensemble is designed for the modern woman who roots for her culture. Includes skirt flare, matching blouse piece, and rich dupatta.',
                'fabric_care' => 'Pure Katan Silk & Zari Weave. Dry Clean Only. Store wrapped in muslin cloth away from direct sunlight to preserve gold luster.',
                'shipping_info' => 'Free insured shipping across India. Dispatches in 24-48 hours. Express delivery available within 3-5 business days.',
                'rating' => 4.85,
                'reviews_count' => 42,
                'is_featured' => true,
                'is_active' => true,
            ]
        );
        $p1->tags()->sync([$bridalTag->id, $trendingTag->id, $weddingTag->id]);

        $p2 = Product::updateOrCreate(
            ['slug' => 'rose-organza-lacha'],
            [
                'category_id' => $lacha->id,
                'name' => 'Rose Organza Lacha',
                'sku' => 'SS-ORG-02',
                'price' => 18500.00,
                'compare_price' => 22000.00,
                'stock' => 8,
                'low_stock_threshold' => 5,
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCwdN1ftjB8zIXfzcYEPCxytlDSZOlKotGjnWachLP1_hxmlncFs-v7kZj4yn2PgiiFJE-QYWtp99kbhVrV-oW7fn6D56biJ_ER1IUHOOjZbiJ4aZbIOQMcX6xFzpr-h5RWe5MlEpoP3Hw-kCbNn1f3VoOrpTUFuZroan6DsQ6djlBCEw-k7RP2LgF3b82WHZXUwGnKCGypbXntqg3dYtblF8VIqiuy3fzUBgWJy_G5_R-7PEUQv0Jy',
                'gallery_images' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuCwdN1ftjB8zIXfzcYEPCxytlDSZOlKotGjnWachLP1_hxmlncFs-v7kZj4yn2PgiiFJE-QYWtp99kbhVrV-oW7fn6D56biJ_ER1IUHOOjZbiJ4aZbIOQMcX6xFzpr-h5RWe5MlEpoP3Hw-kCbNn1f3VoOrpTUFuZroan6DsQ6djlBCEw-k7RP2LgF3b82WHZXUwGnKCGypbXntqg3dYtblF8VIqiuy3fzUBgWJy_G5_R-7PEUQv0Jy',
                ],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Dusty Rose', 'Pastel Pink'],
                'description' => 'Lightweight ethereal organza with delicate floral hand-embroidery and scalloped borders.',
                'fabric_care' => 'Pure Organza Silk with Pearl Handwork. Dry clean only.',
                'shipping_info' => 'Ships within 2-3 days. Standard free delivery across India.',
                'rating' => 4.90,
                'reviews_count' => 28,
                'is_featured' => true,
                'is_active' => true,
            ]
        );
        $p2->tags()->sync([$festiveTag->id, $weddingTag->id]);

        $p3 = Product::updateOrCreate(
            ['slug' => 'midnight-velvet-lehenga'],
            [
                'category_id' => $lehenga->id,
                'name' => 'Midnight Velvet Lehenga',
                'sku' => 'SS-VEL-03',
                'price' => 45000.00,
                'compare_price' => 52000.00,
                'stock' => 3,
                'low_stock_threshold' => 5,
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAeKcdH3O6KmP4to6RVVfVDcxytUMZJAJyoTRlHrqZrBsx5-tbJiaapQbGwtQ_rjFfi3c28zXjSuUfQRhYyFEwlVywl-KSp2bEQGaDdceZ2E4-cVYxHmLdGZGQv6S3mLv1kdZQ5AsaYrMlbpQuvFQzz3kUICDHnvrgDIfempAh6GV5f5trqLcO4LIyWxxLN0CAnBhmeDHrod9KksRnGSS8DoU6fd2Jt9e8KUhB3WhpO_HagwgSKG0XP',
                'gallery_images' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuAeKcdH3O6KmP4to6RVVfVDcxytUMZJAJyoTRlHrqZrBsx5-tbJiaapQbGwtQ_rjFfi3c28zXjSuUfQRhYyFEwlVywl-KSp2bEQGaDdceZ2E4-cVYxHmLdGZGQv6S3mLv1kdZQ5AsaYrMlbpQuvFQzz3kUICDHnvrgDIfempAh6GV5f5trqLcO4LIyWxxLN0CAnBhmeDHrod9KksRnGSS8DoU6fd2Jt9e8KUhB3WhpO_HagwgSKG0XP',
                ],
                'sizes' => ['S', 'M', 'L', 'CUSTOM FIT'],
                'colors' => ['Midnight Navy', 'Royal Emerald'],
                'description' => 'Heavy micro-velvet bridal lehenga adorned with antique copper zardosi, resham threadwork, and dual dupattas.',
                'fabric_care' => 'Micro Velvet with Pure Silk Lining. Professional dry clean only.',
                'shipping_info' => 'Ships within 7-10 business days due to custom tailoring and finish.',
                'rating' => 5.00,
                'reviews_count' => 19,
                'is_featured' => true,
                'is_active' => true,
            ]
        );
        $p3->tags()->sync([$bridalTag->id, $weddingTag->id]);

        $p4 = Product::updateOrCreate(
            ['slug' => 'ivory-chanderi-suit'],
            [
                'category_id' => $suits->id,
                'name' => 'Ivory Chanderi Suit',
                'sku' => 'SS-CHN-04',
                'price' => 9200.00,
                'compare_price' => 11000.00,
                'stock' => 25,
                'low_stock_threshold' => 5,
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBIDH9jrnfx-Y87AR62nOCxAgCBYlNZY8ZNe2mTA-HuJlwpKwC7p-WxYKNqALvq09oy8zuIzlM7ngqJMLUm8gE_nTbT_3nbAaG0wot8oR-h67-QAck_FOrTpareakstfpQycSQat0iH10fRZJ6Sle1mHsWIUCU914n7IdOsFDEAIQVDAVQDc1urku1bWqmCbRndffJvtyQF0bmFNt1Bj6OnfvrgR2qh2UZ4Wzjtaf2mTzQNFWno2tH5',
                'gallery_images' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuBIDH9jrnfx-Y87AR62nOCxAgCBYlNZY8ZNe2mTA-HuJlwpKwC7p-WxYKNqALvq09oy8zuIzlM7ngqJMLUm8gE_nTbT_3nbAaG0wot8oR-h67-QAck_FOrTpareakstfpQycSQat0iH10fRZJ6Sle1mHsWIUCU914n7IdOsFDEAIQVDAVQDc1urku1bWqmCbRndffJvtyQF0bmFNt1Bj6OnfvrgR2qh2UZ4Wzjtaf2mTzQNFWno2tH5',
                ],
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Ivory Gold', 'Cream'],
                'description' => 'A timeless festive staple made of pure Chanderi silk with gota patti detailing and soft mulmul lining.',
                'fabric_care' => 'Pure Chanderi Silk. Hand wash gently or dry clean.',
                'shipping_info' => 'Ready to ship in 24 hours. Delivery in 2-4 business days.',
                'rating' => 4.70,
                'reviews_count' => 34,
                'is_featured' => false,
                'is_active' => true,
            ]
        );
        $p4->tags()->sync([$festiveTag->id]);

        $p5 = Product::updateOrCreate(
            ['slug' => 'emerald-mukaish-dupatta'],
            [
                'category_id' => $dupattas->id,
                'name' => 'Emerald Mukaish Dupatta',
                'sku' => 'SS-DUP-05',
                'price' => 4500.00,
                'compare_price' => 5500.00,
                'stock' => 12,
                'low_stock_threshold' => 5,
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDVhYBdFI1vfJsgAs_gFO8zI1knqzoSjGKuVL3DcPt-x_2lsRuGqPBBNXiJq5F8c7MjhuhXOKTBupxRfVf7QhV8D-hPb1ms1NOLKciVoQbSdhk9riwAeftjvwxxEPODk3A3wmPn2ZcetXKbB_hulwvTS8u6Fr2UfbwL60MtdKKLa0ZQHnUoIDGO0Qu0xS3z6ICJ87lkWiGdhArEPLamFgcIizGgX6d45ygOmqq41pF9BOWjXTJg2OCs',
                'gallery_images' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuDVhYBdFI1vfJsgAs_gFO8zI1knqzoSjGKuVL3DcPt-x_2lsRuGqPBBNXiJq5F8c7MjhuhXOKTBupxRfVf7QhV8D-hPb1ms1NOLKciVoQbSdhk9riwAeftjvwxxEPODk3A3wmPn2ZcetXKbB_hulwvTS8u6Fr2UfbwL60MtdKKLa0ZQHnUoIDGO0Qu0xS3z6ICJ87lkWiGdhArEPLamFgcIizGgX6d45ygOmqq41pF9BOWjXTJg2OCs',
                ],
                'sizes' => ['FREE SIZE (2.5M)'],
                'colors' => ['Emerald Green', 'Forest Green'],
                'description' => 'Handcrafted pure georgette dupatta with badla mukaish dots and gold kiran border.',
                'fabric_care' => 'Pure Georgette with Metal Mukaish. Dry clean only.',
                'shipping_info' => 'Ready to ship in 24 hours.',
                'rating' => 4.95,
                'reviews_count' => 15,
                'is_featured' => false,
                'is_active' => true,
            ]
        );
        $p5->tags()->sync([$festiveTag->id]);

        $p6 = Product::updateOrCreate(
            ['slug' => 'traditional-lugadi'],
            [
                'category_id' => $festive->id,
                'name' => 'Traditional Lugadi',
                'sku' => 'SS-LUG-06',
                'price' => 5800.00,
                'compare_price' => 6900.00,
                'stock' => 14,
                'low_stock_threshold' => 5,
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCmX3_EvRyDL3WpgXirkY56i4ohd-PD48ZmWVZ_17RWWxRznRi4aI74bDhR8emqdGsOsDC1S5Crj_PheEvYwzfa3cIoDHzjlsIS_sT1GmqJw56rhOfLELoqfPPGASRjcVAuoHFt3JckUbqgs-dzp4bmBOszNGGiHh7fS7e61Ll7_2YZqmGMn4awBcf8yJqyvYh6tgQTmDNakvH1S0KLVeokWrl8nFb9eusJ2DnIDrPKTYjRHaZXHxX_',
                'gallery_images' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuCmX3_EvRyDL3WpgXirkY56i4ohd-PD48ZmWVZ_17RWWxRznRi4aI74bDhR8emqdGsOsDC1S5Crj_PheEvYwzfa3cIoDHzjlsIS_sT1GmqJw56rhOfLELoqfPPGASRjcVAuoHFt3JckUbqgs-dzp4bmBOszNGGiHh7fS7e61Ll7_2YZqmGMn4awBcf8yJqyvYh6tgQTmDNakvH1S0KLVeokWrl8nFb9eusJ2DnIDrPKTYjRHaZXHxX_',
                ],
                'sizes' => ['FREE SIZE'],
                'colors' => ['Mustard Gold', 'Marigold Yellow'],
                'description' => 'Traditional festive regional drape featuring authentic hand-block prints and auspicious gold border.',
                'fabric_care' => 'Silk Cotton Blend. Dry clean recommended.',
                'shipping_info' => 'Dispatches within 24 hours.',
                'rating' => 4.80,
                'reviews_count' => 22,
                'is_featured' => true,
                'is_active' => true,
            ]
        );
        $p6->tags()->sync([$festiveTag->id]);

        // 6. YouTube Lookbook Videos
        $vHero = YouTubeVideo::updateOrCreate(
            ['slug' => 'royal-heritage-banarasi-masterclass'],
            [
                'title' => 'The Royal Heritage Banarasi Collection 2026 Masterclass',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzQlvbBZ_7sBoCi083plH6_cPAtuOm2s7NPLKZvmSANG7K09ZNrbIPDaRZCkHMnYdK27C5TsIL0IOnTFP5X3MKW2S1Tt2ghmuhABQx-O9imTL5wVx3mYlYDB7UVKvnN9d7TXVBTsAdUJiaERBH9U4EeaGmi_uhOjXssQaxKzlTvliMn39rAXx8AP2RLguYT9l-4m0lZvtDCulkHmZgjl9q6W1CFaItU1CirxuXldi197muluEMndo5',
                'duration' => '14:20',
                'views_text' => '48K views',
                'description' => 'Deep dive into handwoven Katan silk, intricate zardosi craftsmanship, and draping masterclasses by our lead stylists.',
                'is_hero' => true,
                'is_trending' => true,
                'is_lookbook' => false,
                'display_order' => 1,
                'is_active' => true,
            ]
        );
        $vHero->products()->sync([$p1->id, $p2->id]);

        $v1 = YouTubeVideo::updateOrCreate(
            ['slug' => 'bridal-lehenga-styling-masterclass'],
            [
                'title' => 'Bridal Lehenga Styling & Draping Masterclass',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzQlvbBZ_7sBoCi083plH6_cPAtuOm2s7NPLKZvmSANG7K09ZNrbIPDaRZCkHMnYdK27C5TsIL0IOnTFP5X3MKW2S1Tt2ghmuhABQx-O9imTL5wVx3mYlYDB7UVKvnN9d7TXVBTsAdUJiaERBH9U4EeaGmi_uhOjXssQaxKzlTvliMn39rAXx8AP2RLguYT9l-4m0lZvtDCulkHmZgjl9q6W1CFaItU1CirxuXldi197muluEMndo5',
                'duration' => '12:45',
                'views_text' => '45K views',
                'description' => 'Learn how to drape double dupattas and style heavy bridal velvet lehengas for grand wedding receptions.',
                'is_hero' => false,
                'is_trending' => false,
                'is_lookbook' => true,
                'display_order' => 2,
                'is_active' => true,
            ]
        );
        $v1->products()->sync([$p3->id, $p1->id]);

        $v2 = YouTubeVideo::updateOrCreate(
            ['slug' => 'pure-silk-lacha-draping-guide'],
            [
                'title' => 'Pure Silk Lacha Draping & Heritage Saree Guide',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDd6alUz92TMFGrRS_F-AyB4F6wfWr5T1s7_8Z6joB_f_8KqIBQ9nWxH8CZbkjWMVWGt4AVpfm1-ZmKqb7ns46wDiRhMgUQstOyHDl-2XYoAPLYLZtvV9C5EdEn-3sg2nTTLyf-aGOUu6PtFAVAcMwtkKM8h49ozekkwdv5dRoQCnbKZtGa2ZA77v3s2ZzjHPHy92F4EoedXSfYA6T4rz96loWptqbICZJ_pk_xAk4FiiyyPFZdGDUL',
                'duration' => '08:30',
                'views_text' => '28K views',
                'description' => 'Effortless traditional pleating techniques for high flare Banarasi Lachas.',
                'is_hero' => false,
                'is_trending' => false,
                'is_lookbook' => true,
                'display_order' => 3,
                'is_active' => true,
            ]
        );
        $v2->products()->sync([$p1->id, $p6->id]);

        // 7. Seed Orders
        // Seed order ORD-8829
        $ord8829 = Order::updateOrCreate(
            ['order_number' => 'ORD-8829'],
            [
                'user_id' => $customer1->id,
                'customer_name' => 'Ananya Sharma',
                'customer_email' => 'customer@gmail.com',
                'customer_phone' => '+91 98765 43210',
                'shipping_address' => '402, Royal Residency, MG Road',
                'city' => 'Varanasi',
                'state' => 'Uttar Pradesh',
                'postal_code' => '221001',
                'country' => 'India',
                'payment_method' => 'UPI',
                'payment_status' => 'paid',
                'transaction_id' => 'TXN98472918',
                'subtotal' => 21050.00,
                'discount' => 0.00,
                'shipping_fee' => 0.00,
                'total_amount' => 21050.00,
                'status' => 'processing',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $ord8829->id, 'product_name' => 'Royal Banarasi Lacha'],
            [
                'product_id' => $p1->id,
                'product_sku' => 'SS-BAN-01',
                'price' => 12499.00,
                'quantity' => 1,
                'size' => 'M',
                'color' => 'Crimson Red',
                'product_image' => $p1->main_image,
                'total' => 12499.00,
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $ord8829->id, 'product_name' => 'Ivory Chanderi Suit'],
            [
                'product_id' => $p4->id,
                'product_sku' => 'SS-CHN-04',
                'price' => 8551.00,
                'quantity' => 1,
                'size' => 'L',
                'color' => 'Ivory Gold',
                'product_image' => $p4->main_image,
                'total' => 8551.00,
            ]
        );

        // Seed order ORD-9023
        $ord9023 = Order::updateOrCreate(
            ['order_number' => 'ORD-9023'],
            [
                'user_id' => $customer2->id,
                'customer_name' => 'Meera Sharma',
                'customer_email' => 'meera.sharma@example.com',
                'customer_phone' => '+91 98765 11223',
                'shipping_address' => 'Villa 12, Gulmohar Enclave, South Ex',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'postal_code' => '110049',
                'country' => 'India',
                'payment_method' => 'Card',
                'payment_status' => 'paid',
                'transaction_id' => 'TXN77102948',
                'subtotal' => 45000.00,
                'discount' => 4500.00,
                'shipping_fee' => 0.00,
                'total_amount' => 40500.00,
                'status' => 'shipped',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $ord9023->id, 'product_name' => 'Midnight Velvet Lehenga'],
            [
                'product_id' => $p3->id,
                'product_sku' => 'SS-VEL-03',
                'price' => 45000.00,
                'quantity' => 1,
                'size' => 'M',
                'color' => 'Midnight Navy',
                'product_image' => $p3->main_image,
                'total' => 45000.00,
            ]
        );

        // Seed order ORD-9022
        $ord9022 = Order::updateOrCreate(
            ['order_number' => 'ORD-9022'],
            [
                'user_id' => $customer3->id,
                'customer_name' => 'Riya Desai',
                'customer_email' => 'riya.desai@example.com',
                'customer_phone' => '+91 98765 99887',
                'shipping_address' => '701, Silver Sands, Juhu Tara Road',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'postal_code' => '400049',
                'country' => 'India',
                'payment_method' => 'UPI',
                'payment_status' => 'paid',
                'transaction_id' => 'TXN33819201',
                'subtotal' => 8950.00,
                'discount' => 0.00,
                'shipping_fee' => 0.00,
                'total_amount' => 8950.00,
                'status' => 'delivered',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $ord9022->id, 'product_name' => 'Emerald Mukaish Dupatta'],
            [
                'product_id' => $p5->id,
                'product_sku' => 'SS-DUP-05',
                'price' => 8950.00,
                'quantity' => 1,
                'size' => 'FREE SIZE (2.5M)',
                'color' => 'Emerald Green',
                'product_image' => $p5->main_image,
                'total' => 8950.00,
            ]
        );
    }
}
