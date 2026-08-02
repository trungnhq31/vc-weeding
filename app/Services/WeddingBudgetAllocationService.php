<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Support\Str;

class WeddingBudgetAllocationService
{
    /**
     * Calculate 5-pillar meticulous budget allocation breakdown.
     */
    public function calculateStandardBreakdown(float $totalBudget, int $estimatedGuests): array
    {
        $tables = (int) max(1, ceil($estimatedGuests / 10));
        $venueAllocation = $totalBudget * 0.50;
        $perTableCap = $venueAllocation / $tables;

        return [
            'total_budget' => $totalBudget,
            'estimated_guests' => $estimatedGuests,
            'total_tables' => $tables,
            'per_table_cap' => (float) $perTableCap,
            'pillars' => [
                [
                    'key' => 'venue',
                    'title' => 'Sảnh Tiệc & Thực Đơn',
                    'percentage' => 50,
                    'allocated' => $venueAllocation,
                    'icon' => 'Building2',
                    'color' => 'bg-[#881337] text-white',
                    'note' => "Tối đa " . number_format((int) $perTableCap) . " đ / bàn {$tables} bàn",
                ],
                [
                    'key' => 'media',
                    'title' => 'Pre-wedding & Phim Ảnh',
                    'percentage' => 15,
                    'allocated' => $totalBudget * 0.15,
                    'icon' => 'Camera',
                    'color' => 'bg-amber-600 text-white',
                    'note' => 'Chụp studio / ngoại cảnh + Quay phim ngày cưới',
                ],
                [
                    'key' => 'decor',
                    'title' => 'Trang Trí & Gia Tiên',
                    'percentage' => 15,
                    'allocated' => $totalBudget * 0.15,
                    'icon' => 'Sparkles',
                    'color' => 'bg-rose-600 text-white',
                    'note' => 'Backdrop đón khách + Trang trí bàn thờ gia tiên',
                ],
                [
                    'key' => 'attire',
                    'title' => 'Trang Phục & Trang Điểm',
                    'percentage' => 10,
                    'allocated' => $totalBudget * 0.10,
                    'icon' => 'Crown',
                    'color' => 'bg-sky-600 text-white',
                    'note' => 'Váy cưới dâu, Suit chú rể & Makeup ngày cưới',
                ],
                [
                    'key' => 'contingency',
                    'title' => 'Nhẫn Cưới & Quà Đáp Lễ / Dự Phòng',
                    'percentage' => 10,
                    'allocated' => $totalBudget * 0.10,
                    'icon' => 'DollarSign',
                    'color' => 'bg-emerald-600 text-white',
                    'note' => 'Cặp nhẫn cưới, quà cảm ơn & Quỹ dự phòng vỡ trận',
                ],
            ],
        ];
    }

    /**
     * Get AI Recommended Banquet Halls matching per-table budget cap and location.
     */
    public function getRecommendedVenues(float $totalBudget, int $estimatedGuests, string $location = 'TP. Hồ Chí Minh'): array
    {
        $breakdown = $this->calculateStandardBreakdown($totalBudget, $estimatedGuests);
        $perTableCap = $breakdown['per_table_cap'];

        $catalog = [
            [
                'id' => 'v-1',
                'name' => 'Trung Tâm Tiệc Cưới Luxury Palace',
                'district' => 'Quận Gò Vấp, TP. HCM',
                'price_per_table' => 5500000,
                'price_label' => '5.500.000 đ - 7.500.000 đ / bàn',
                'capacity_text' => '200 - 1,500 Khách (Khung vòm sang trọng)',
                'highlights' => ['Sảnh tiệc Hoàng Gia 24K', 'Ưu đãi tặng 100% bia', 'Menu 6 món cao cấp'],
                'rating' => 4.9,
                'match_score' => 98,
                'tier' => 'Optimal Choice (Khuyên dùng nhất)',
            ],
            [
                'id' => 'v-2',
                'name' => 'White Palace Event Center',
                'district' => 'Quận Phú Nhuận / TP. Thủ Đức',
                'price_per_table' => 8000000,
                'price_label' => '8.000.000 đ - 12.000.000 đ / bàn',
                'capacity_text' => '300 - 2,000 Khách (Kiến trúc vòm trắng Minimalist)',
                'highlights' => ['Không gian nghệ thuật mở', 'Hệ thống âm thanh ánh sáng chuẩn Concert'],
                'rating' => 4.95,
                'match_score' => 95,
                'tier' => 'Luxury Premium Choice',
            ],
            [
                'id' => 'v-3',
                'name' => 'Riverside Palace International Center',
                'district' => 'Quận 4, TP. HCM (Bờ sông)',
                'price_per_table' => 4800000,
                'price_label' => '4.800.000 đ - 6.500.000 đ / bàn',
                'capacity_text' => '150 - 1,000 Khách (View sông thoáng đãng)',
                'highlights' => ['Gần trung tâm Q1', 'Thực đơn Á - Âu phong phú', 'Tối ưu ngân sách'],
                'rating' => 4.8,
                'match_score' => 92,
                'tier' => 'Best Value Choice',
            ],
            [
                'id' => 'v-4',
                'name' => 'GEM Center Convention Hall',
                'district' => 'Quận 1, TP. HCM',
                'price_per_table' => 15000000,
                'price_label' => '15.000.000 đ - 25.000.000 đ / bàn',
                'capacity_text' => '200 - 1,200 Khách (Trung tâm Q1 đẳng cấp thượng lưu)',
                'highlights' => ['Đỉnh cao tiệc cưới VIP', 'Decor hoa tươi độc bản 100%'],
                'rating' => 5.0,
                'match_score' => 88,
                'tier' => 'Ultra High-End Choice',
            ],
        ];

        // Sort catalog by match score closeness to perTableCap
        return array_map(function ($venue) use ($perTableCap) {
            $diff = abs($venue['price_per_table'] - $perTableCap);
            if ($diff < 1500000) {
                $venue['match_score'] = 98;
            } elseif ($diff < 3500000) {
                $venue['match_score'] = 92;
            }
            return $venue;
        }, $catalog);
    }

    /**
     * Lock selected venue into workspace & update budget spent.
     */
    public function selectVenue(string $venueName, float $depositAmount): array
    {
        $workspace = Workspace::latest()->first();
        if (! $workspace) {
            $workspace = Workspace::create([
                'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
                'slug' => 'quoc-trung-hong-van-'.Str::random(5),
                'groom_name' => 'Nguyễn Hoàng Quốc Trung',
                'bride_name' => 'Lê Thị Hồng Vân',
                'budget_cap' => 250000000.00,
                'estimated_guests' => 200,
                'wedding_date' => '2026-10-24',
                'wedding_location' => 'TP. Hồ Chí Minh',
            ]);
        }

        $workspace->update([
            'venue_name' => $venueName,
        ]);

        Vendor::firstOrCreate(
            [
                'workspace_id' => $workspace->id,
                'name' => $venueName,
            ],
            [
                'category' => 'venue',
                'city' => $workspace->wedding_location ?? 'TP. Hồ Chí Minh',
                'district' => 'Trung Tâm',
                'price_range' => 'premium',
                'contact_phone' => '0988776655',
                'rating' => 4.9,
                'match_score' => 98,
            ]
        );

        // Update venue task on timeline if exists
        $task = WeddingTask::where('title', 'like', '%sảnh tiệc%')->orWhere('title', 'like', '%nhà hàng%')->first();
        if ($task) {
            $task->update([
                'is_completed' => true,
                'vendor_info' => $venueName,
                'actual_cost' => $depositAmount > 0 ? $depositAmount : 35000000.00,
            ]);

            if ($task->milestone) {
                $task->milestone->update(['budget_spent' => $task->milestone->tasks()->sum('actual_cost')]);
            }
        }

        return [
            'success' => true,
            'message' => "Đã chốt thành công sảnh tiệc [{$venueName}] vào hệ thống!",
            'workspace' => $workspace->fresh(),
        ];
    }
}
