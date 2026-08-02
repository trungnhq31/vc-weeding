<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Workspace\Models\Workspace;

class VendorMatchmakerService
{
    /**
     * Calculate Match Score % between workspace preferences & vendor profile.
     */
    public function calculateMatchScore(?Workspace $workspace, array $vendorData, string $selectedVibe = 'pastel', string $selectedLocation = 'TP. Hồ Chí Minh'): int
    {
        $score = 75;

        // Vibe match (+12%)
        $vibe = strtolower($vendorData['vibe_category'] ?? 'pastel');
        if ($vibe === strtolower($selectedVibe)) {
            $score += 12;
        }

        // Location match (+8%)
        $city = strtolower($vendorData['city'] ?? '');
        if (str_contains($city, 'hồ chí minh') || str_contains(strtolower($selectedLocation), $city)) {
            $score += 8;
        }

        // Rating boost (+4%)
        if (($vendorData['rating'] ?? 4.5) >= 4.8) {
            $score += 4;
        }

        return min(99, max(82, $score));
    }

    /**
     * Get Curated Matchmaker Catalog categorized with dynamic % Match Score.
     */
    public function getRecommendations(?Workspace $workspace, string $selectedVibe = 'pastel', string $selectedLocation = 'TP. Hồ Chí Minh'): array
    {
        $curatedCatalog = [
            // 1. Sảnh tiệc & Nhà hàng
            [
                'id' => 'rec-venue-1',
                'name' => 'White Palace Event Center',
                'category' => 'venue',
                'category_name' => 'Sảnh Tiệc & Nhà Hàng',
                'vibe_category' => 'pastel',
                'vibe_label' => 'Pastel Romantic & Luxury',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Phú Nhuận & Thủ Đức',
                'latitude' => 10.7981,
                'longitude' => 106.6749,
                'price_tier' => 'premium',
                'price_label' => '150 - 350 Triệu / Tiệc',
                'rating' => 4.9,
                'capacity_text' => '150 - 800 Khách',
                'contact_name' => 'Bộ Phận Đặt Tiệc White Palace',
                'phone' => '1900 636 622',
                'email' => 'booking@whitepalace.com.vn',
                'portfolio_images' => [
                    '/images/home/luxury_ballroom_banner.png',
                    'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['Sảnh không cột sang trọng', '100% Hoa tươi trang trí', 'Menu Á-Âu 5 sao'],
                'halls' => [
                    [
                        'name' => 'Sảnh Grand Ballroom (Tầng 1)',
                        'capacity_tables' => '40 - 80 Bàn (400 - 800 Khách)',
                        'min_capacity' => 400,
                        'max_capacity' => 800,
                        'image' => '/images/home/luxury_ballroom_banner.png',
                        'description' => 'Sảnh thiết kế vòm trắng tinh khôi không cột, màn hình LED 4K 800 inch, hệ thống đèn pha lê nhập khẩu.',
                        'price_per_table' => '8.5 - 14.5 Triệu / Bàn',
                    ],
                    [
                        'name' => 'Sảnh Crystal Hall (Tầng 2)',
                        'capacity_tables' => '20 - 45 Bàn (200 - 450 Khách)',
                        'min_capacity' => 200,
                        'max_capacity' => 450,
                        'image' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80',
                        'description' => 'Không gian sang trọng kết hợp hoa tươi ngập tràn, thảm lụa ấm cúng.',
                        'price_per_table' => '7.8 - 12.0 Triệu / Bàn',
                    ],
                ],
            ],
            [
                'id' => 'rec-venue-2',
                'name' => 'Gem Center Ballroom',
                'category' => 'venue',
                'category_name' => 'Sảnh Tiệc & Nhà Hàng',
                'vibe_category' => 'royal',
                'vibe_label' => 'Royal Gold & Hoàng Gia',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 1',
                'latitude' => 10.7858,
                'longitude' => 106.7008,
                'price_tier' => 'luxury',
                'price_label' => '300 - 600 Triệu / Tiệc',
                'rating' => 4.9,
                'capacity_text' => '200 - 1000 Khách',
                'contact_name' => 'Gem Center Event Team',
                'phone' => '028 3825 6000',
                'email' => 'event@gemcenter.com.vn',
                'portfolio_images' => [
                    'https://images.unsplash.com/photo-1544078751-58fee2d8a03b?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['Trần cao 9m hiện đại', 'Hệ thống ánh sáng nghệ thuật', 'Vị trí trung tâm Quận 1'],
                'halls' => [
                    [
                        'name' => 'Sảnh Pollux Ballroom',
                        'capacity_tables' => '50 - 100 Bàn (500 - 1000 Khách)',
                        'min_capacity' => 500,
                        'max_capacity' => 1000,
                        'image' => 'https://images.unsplash.com/photo-1544078751-58fee2d8a03b?auto=format&fit=crop&w=800&q=80',
                        'description' => 'Đỉnh cao thiết kế gỗ & ánh sáng dát vàng, trần chịu lực treo dàn dựng sân khấu hoành tráng.',
                        'price_per_table' => '12.5 - 22.0 Triệu / Bàn',
                    ],
                    [
                        'name' => 'Sảnh Castor Hall',
                        'capacity_tables' => '30 - 60 Bàn (300 - 600 Khách)',
                        'min_capacity' => 300,
                        'max_capacity' => 600,
                        'image' => '/images/home/luxury_ballroom_banner.png',
                        'description' => 'Sảnh tiệc ấm cúng phong cách hoàng gia thượng hạng.',
                        'price_per_table' => '10.5 - 18.0 Triệu / Bàn',
                    ],
                ],
            ],
            [
                'id' => 'rec-venue-3',
                'name' => 'Chloe Gallery Living Dining',
                'category' => 'venue',
                'category_name' => 'Sảnh Tiệc & Nhà Hàng',
                'vibe_category' => 'garden',
                'vibe_label' => 'Botanical Garden & Outdoor',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 7',
                'latitude' => 10.7291,
                'longitude' => 106.7214,
                'price_tier' => 'premium',
                'price_label' => '200 - 450 Triệu / Tiệc',
                'rating' => 4.8,
                'capacity_text' => '80 - 300 Khách',
                'contact_name' => 'Chloe Gallery Weddings',
                'phone' => '0903 305 999',
                'email' => 'chloe@chloegallery.vn',
                'portfolio_images' => [
                    'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['Sân vườn ven hồ thơ mộng', 'Phong cách Indochine & Hiện đại', 'Tiệc nướng BBQ & Fine Dining'],
                'halls' => [
                    [
                        'name' => 'Sảnh Sân Vườn Sunset Garden',
                        'capacity_tables' => '15 - 30 Bàn (150 - 300 Khách)',
                        'min_capacity' => 150,
                        'max_capacity' => 300,
                        'image' => 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=800&q=80',
                        'description' => 'Không gian ngoài trời ngợp cỏ xanh ven Cầu Ánh Sao, lý tưởng cho lễ thề nguyện Hôn Phối.',
                        'price_per_table' => '9.0 - 16.0 Triệu / Bàn',
                    ],
                ],
            ],
            [
                'id' => 'rec-venue-4',
                'name' => 'Asiana Plaza Bình Thạnh',
                'category' => 'venue',
                'category_name' => 'Sảnh Tiệc & Nhà Hàng',
                'vibe_category' => 'royal',
                'vibe_label' => 'Royal Gold & Tân Cổ Điển',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Bình Thạnh',
                'latitude' => 10.8035,
                'longitude' => 106.6994,
                'price_tier' => 'standard',
                'price_label' => '120 - 280 Triệu / Tiệc',
                'rating' => 4.8,
                'capacity_text' => '100 - 650 Khách',
                'contact_name' => 'Asiana Plaza Booking',
                'phone' => '028 3510 9999',
                'email' => 'booking@asiana-plaza.vn',
                'portfolio_images' => [
                    '/images/home/luxury_ballroom_banner.png',
                ],
                'highlights' => ['Kiến trúc Hoàng gia Châu Âu', '9 Sảnh tiệc chủ đề đa dạng', 'Menu tiệc phong phú'],
                'halls' => [
                    [
                        'name' => 'Sảnh Violet (Tầng 3)',
                        'capacity_tables' => '25 - 50 Bàn (250 - 500 Khách)',
                        'min_capacity' => 250,
                        'max_capacity' => 500,
                        'image' => '/images/home/luxury_ballroom_banner.png',
                        'description' => 'Tone hoa tử đằng lãng mạn sang trọng với lối đi phủ nến lung linh.',
                        'price_per_table' => '6.5 - 11.5 Triệu / Bàn',
                    ],
                    [
                        'name' => 'Sảnh Rosa (Tầng 4)',
                        'capacity_tables' => '15 - 35 Bàn (150 - 350 Khách)',
                        'min_capacity' => 150,
                        'max_capacity' => 350,
                        'image' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80',
                        'description' => 'Tone hồng ngọt ngào phong cách Pastel Romantic.',
                        'price_per_table' => '6.0 - 10.5 Triệu / Bàn',
                    ],
                ],
            ],

            // 2. Makeup & Hair Studio
            [
                'id' => 'rec-makeup-1',
                'name' => 'Tee Le Studio & Bridal Makeup',
                'category' => 'makeup',
                'category_name' => 'Trang Điểm & Làm Tóc',
                'vibe_category' => 'pastel',
                'vibe_label' => 'Trong Trẻo & Tự Nhiên Hàn Quốc',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 1',
                'latitude' => 10.7769,
                'longitude' => 106.6953,
                'price_tier' => 'premium',
                'price_label' => '6 - 15 Triệu / Lần',
                'rating' => 4.9,
                'capacity_text' => 'Make-up Cô Dâu & Mẹ',
                'contact_name' => 'Tee Le Studio',
                'phone' => '0938 990 922',
                'email' => 'teele.studio@gmail.com',
                'portfolio_images' => [
                    'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['Tone makeup mỏng nhẹ căng bóng', 'Dặm phấn trực tiếp tại tiệc', 'Tạo kiểu tóc hoa tươi'],
            ],
            [
                'id' => 'rec-makeup-2',
                'name' => 'Hiwon Makeup & Bridal',
                'category' => 'makeup',
                'category_name' => 'Trang Điểm & Làm Tóc',
                'vibe_category' => 'pastel',
                'vibe_label' => 'Soft Pastel & Kiêu Sa',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Phú Nhuận',
                'latitude' => 10.7952,
                'longitude' => 106.6805,
                'price_tier' => 'standard',
                'price_label' => '4 - 10 Triệu / Lần',
                'rating' => 4.8,
                'capacity_text' => 'Make-up Cô Dâu & Chú Rể',
                'contact_name' => 'Hiwon Team',
                'phone' => '0902 456 789',
                'email' => 'hiwon.makeup@gmail.com',
                'portfolio_images' => [
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['Mỹ phẩm High-end chính hãng', 'Tư vấn dáng mày & kiểu tóc phù hợp khuôn mặt'],
            ],

            // 3. Decor & Floral Styling
            [
                'id' => 'rec-decor-1',
                'name' => 'Bliss Weddings & Events Decor',
                'category' => 'florist',
                'category_name' => 'Trang Trí & Decor Gia Tiên/Sảnh',
                'vibe_category' => 'pastel',
                'vibe_label' => 'Luxury Floral & Pastel Concept',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 3',
                'latitude' => 10.7735,
                'longitude' => 106.6922,
                'price_tier' => 'luxury',
                'price_label' => '50 - 200 Triệu / Gói',
                'rating' => 4.9,
                'capacity_text' => 'Trang trí trọn gói',
                'contact_name' => 'Bliss Decor Specialist',
                'phone' => '0901 332 552',
                'email' => 'info@blissvn.com',
                'portfolio_images' => [
                    'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['100% Hoa tươi nhập khẩu', 'Cổng hoa & Backdrop 3D', 'Bàn Gallery nến thơm xa xỉ'],
            ],

            // 4. Photo & Video Pre-wedding
            [
                'id' => 'rec-photo-1',
                'name' => 'TuArt Wedding Studio & Cinematic',
                'category' => 'studio',
                'category_name' => 'Phim Ảnh Pre-wedding & Ngày Cưới',
                'vibe_category' => 'pastel',
                'vibe_label' => 'Cinematic & Romantic Film',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 1',
                'latitude' => 10.7712,
                'longitude' => 106.6980,
                'price_tier' => 'standard',
                'price_label' => '15 - 40 Triệu / Bộ ảnh',
                'rating' => 4.8,
                'capacity_text' => 'Chụp Studio & Ngoại cảnh',
                'contact_name' => 'TuArt Booking Centre',
                'phone' => '0888 088 668',
                'email' => 'tuart.wedding@gmail.com',
                'portfolio_images' => [
                    'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['Màu phim lãng mạn độc quyền', 'Quay phim Flycam 4K ngày cưới', 'Áo cưới đi kèm bộ ảnh'],
            ],

            // 5. Trang phục Cưới (Bridal Gown & Suit)
            [
                'id' => 'rec-attire-1',
                'name' => 'Chung Thanh Phong Bridal',
                'category' => 'attire',
                'category_name' => 'Trang Phục Váy Cưới & Vest',
                'vibe_category' => 'pastel',
                'vibe_label' => 'Haute Couture & Fairytale',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 1',
                'latitude' => 10.7745,
                'longitude' => 106.6998,
                'price_tier' => 'luxury',
                'price_label' => '25 - 90 Triệu / Váy',
                'rating' => 4.9,
                'capacity_text' => 'Thiết kế theo số đo & May thuê',
                'contact_name' => 'Chung Thanh Phong Boutique',
                'phone' => '0938 888 888',
                'email' => 'bridal@chungthanhphong.com',
                'portfolio_images' => [
                    'https://images.unsplash.com/photo-1594552072238-b8a33785b261?auto=format&fit=crop&w=800&q=80',
                ],
                'highlights' => ['Đính kết đá Swarovski thủ công', 'Form váy chuẩn siết eo', 'Thử váy phòng VIP khép kín'],
            ],
        ];

        // Attach dynamic match scores based on couple's preferences
        return array_map(function ($vendor) use ($workspace, $selectedVibe, $selectedLocation) {
            $vendor['match_score'] = $this->calculateMatchScore($workspace, $vendor, $selectedVibe, $selectedLocation);

            return $vendor;
        }, $curatedCatalog);
    }
}
