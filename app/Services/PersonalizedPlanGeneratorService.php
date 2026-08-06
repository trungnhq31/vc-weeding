<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use App\Modules\Budget\Models\BudgetItem;
use App\Modules\Workspace\Models\Workspace;

class PersonalizedPlanGeneratorService
{
    public function generateForWorkspace(Workspace $workspace): void
    {
        // Clear old default milestones & tasks if any
        WeddingMilestone::where('workspace_id', $workspace->id)->delete();
        BudgetItem::where('workspace_id', $workspace->id)->delete();

        $budgetCap = (float) $workspace->budget_cap;
        $ceremonyType = $workspace->ceremony_type ?? 'traditional_south';

        // -------------------------------------------------------------
        // 1. GENERATE PERSONALIZED MILESTONES & TASKS BASED ON CEREMONY
        // -------------------------------------------------------------
        $milestoneTemplates = $this->getMilestoneTemplates($ceremonyType);

        foreach ($milestoneTemplates as $mIndex => $mTemp) {
            $milestoneAllocated = $budgetCap * $mTemp['budget_pct'];

            $milestone = WeddingMilestone::create([
                'workspace_id' => $workspace->id,
                'timeframe' => $mTemp['timeframe'],
                'title' => $mTemp['title'],
                'slug' => $mTemp['slug'],
                'icon' => $mTemp['icon'],
                'order' => $mTemp['order'],
                'summary' => $mTemp['summary'],
                'budget_allocated' => $milestoneAllocated,
                'budget_spent' => 0,
                'progress_percentage' => 0,
            ]);

            foreach ($mTemp['tasks'] as $tTemp) {
                $taskEstCost = $budgetCap * $tTemp['cost_pct'];

                WeddingTask::create([
                    'milestone_id' => $milestone->id,
                    'title' => $tTemp['title'],
                    'priority' => $tTemp['priority'],
                    'estimated_cost' => $taskEstCost,
                    'actual_cost' => 0,
                    'vendor_info' => $tTemp['vendor'],
                    'due_date' => now()->addDays(($mIndex + 1) * 30),
                    'is_completed' => false,
                ]);
            }
        }

        // -------------------------------------------------------------
        // 2. GENERATE PERSONALIZED 50/15/10/10/15 BUDGET ITEMS
        // -------------------------------------------------------------
        $budgetCategories = [
            ['name' => 'Sảnh Tiệc & Thực Đơn Cỗ Cưới', 'category' => 'venue', 'pct' => 0.50, 'status' => 'deposit_paid'],
            ['name' => 'Phim Ảnh Phóng Sự & Trang Điểm Makeup', 'category' => 'photo_video', 'pct' => 0.15, 'status' => 'partially_paid'],
            ['name' => 'Trang Trí Hoa Tươi Gia Tiên & Sảnh', 'category' => 'decor', 'pct' => 0.10, 'status' => 'unpaid'],
            ['name' => 'Trang Phục Váy Cưới & Nhẫn Cưới Gold', 'category' => 'attire', 'pct' => 0.10, 'status' => 'fully_paid'],
            ['name' => 'Dự Phòng Rủi Ro & Khoản Khác', 'category' => 'reserve', 'pct' => 0.15, 'status' => 'unpaid'],
        ];

        foreach ($budgetCategories as $bCat) {
            $estAmount = $budgetCap * $bCat['pct'];
            $depositPaid = match ($bCat['status']) {
                'fully_paid' => $estAmount,
                'deposit_paid', 'partially_paid' => $estAmount * 0.30,
                default => 0,
            };

            BudgetItem::create([
                'workspace_id' => $workspace->id,
                'category_name' => $bCat['name'],
                'item_name' => $bCat['name'],
                'estimated_amount' => $estAmount,
                'actual_amount' => $estAmount,
                'deposit_paid' => $depositPaid,
                'payment_status' => $bCat['status'],
                'due_payment_date' => now()->addDays(15),
            ]);
        }
    }

    private function getMilestoneTemplates(string $ceremonyType): array
    {
        if ($ceremonyType === 'catholic_church') {
            return [
                [
                    'timeframe' => 'Tháng 1 - Tháng 2',
                    'title' => 'Giai đoạn 1: Chuẩn Bị Giáo Lý & Thủ Tục Nhà Thờ',
                    'slug' => 'giai-doan-1',
                    'icon' => '⛪',
                    'order' => 1,
                    'summary' => 'Hoàn tất học khóa giáo lý hôn nhân & xin giấy giới thiệu cha xứ',
                    'budget_pct' => 0.15,
                    'tasks' => [
                        ['title' => 'Đăng ký lớp học Giáo Lý Hôn Nhân tại Giáo xứ', 'priority' => 'urgent', 'cost_pct' => 0.02, 'vendor' => 'Giáo Xứ Chính Tòa'],
                        ['title' => 'Xin giấy phao phối & Trình diện Cha Xứ', 'priority' => 'high', 'cost_pct' => 0.01, 'vendor' => 'Văn Phòng Giáo Xứ'],
                        ['title' => 'Khảo sát & chốt Sảnh tiệc cưới tiễn khách', 'priority' => 'high', 'cost_pct' => 0.12, 'vendor' => 'Grand Palace / White Palace'],
                    ],
                ],
                [
                    'timeframe' => 'Tháng 3 - Tháng 4',
                    'title' => 'Giai đoạn 2: Thánh Đường & Phóng Sự Cưới',
                    'slug' => 'giai-doan-2',
                    'icon' => '📷',
                    'order' => 2,
                    'summary' => 'Trang trí hoa tươi Thánh đường & chốt đội quay phim phóng sự',
                    'budget_pct' => 0.35,
                    'tasks' => [
                        ['title' => 'Đặt cọc Đội Quay Phóng Sự Cưới Lễ Nhà Thờ', 'priority' => 'urgent', 'cost_pct' => 0.15, 'vendor' => 'TuArt Wedding & Cinematic'],
                        ['title' => 'Đặt hoa tươi trang trí lối đi & bàn thờ Thánh Đường', 'priority' => 'high', 'cost_pct' => 0.10, 'vendor' => 'Bliss Floral Decor'],
                        ['title' => 'May/Thuê Váy Cưới Công Chúa Lễ Thánh Đường', 'priority' => 'medium', 'cost_pct' => 0.10, 'vendor' => 'Chung Thanh Phong Bridal'],
                    ],
                ],
                [
                    'timeframe' => 'Tháng 5 - Tháng 6',
                    'title' => 'Giai đoạn 3: Tiệc Cưới & Lễ Báo Hỷ',
                    'slug' => 'giai-doan-3',
                    'icon' => '🥂',
                    'order' => 3,
                    'summary' => 'Tổng duyệt Ca Đoàn & Điều phối tiệc cưới nhà hàng',
                    'budget_pct' => 0.50,
                    'tasks' => [
                        ['title' => 'Luyện hát Ca Đoàn & Duyệt nhạc nghi thức Nhà Thờ', 'priority' => 'medium', 'cost_pct' => 0.05, 'vendor' => 'Ca Đoàn Giáo Xứ'],
                        ['title' => 'Gửi Thiệp Cưới Digital & Chốt Danh Sách Khách Mời', 'priority' => 'urgent', 'cost_pct' => 0.05, 'vendor' => 'Eloria Wedding OS Digital'],
                        ['title' => 'Khai Tiệc Cỗ Cưới & Rót Rượu Champagne', 'priority' => 'high', 'cost_pct' => 0.40, 'vendor' => 'Trung Tâm Tiệc Cưới'],
                    ],
                ],
            ];
        }

        if ($ceremonyType === 'destination_outdoor') {
            return [
                [
                    'timeframe' => 'Tháng 1 - Tháng 2',
                    'title' => 'Giai đoạn 1: Khảo Sát Resort & Đặt Chỗ Khách Mời',
                    'slug' => 'giai-doan-1',
                    'icon' => '🌊',
                    'order' => 1,
                    'summary' => 'Khảo sát địa điểm tiệc bãi biển Outdoor & xe bus đưa đón',
                    'budget_pct' => 0.25,
                    'tasks' => [
                        ['title' => 'Khảo sát & chốt hợp đồng Resort Bãi Biển / Villa', 'priority' => 'urgent', 'cost_pct' => 0.15, 'vendor' => 'Chloe Gallery / Mia Resort'],
                        ['title' => 'Thuê xe bus đưa đón đoàn khách xa', 'priority' => 'high', 'cost_pct' => 0.10, 'vendor' => 'Đội Xe Du Lịch'],
                    ],
                ],
                [
                    'timeframe' => 'Tháng 3 - Tháng 4',
                    'title' => 'Giai đoạn 2: Decor Outdoor & Kế Hoạch Dự Phòng Mưa',
                    'slug' => 'giai-doan-2',
                    'icon' => '⛺',
                    'order' => 2,
                    'summary' => 'Thiết kế cổng hoa tiệc bãi biển & đặt bạt kéo dự phòng mưa',
                    'budget_pct' => 0.35,
                    'tasks' => [
                        ['title' => 'Thiết kế concept hoa tươi Cổng Chào Bãi Biển', 'priority' => 'high', 'cost_pct' => 0.20, 'vendor' => 'Meraki Wedding Planner'],
                        ['title' => 'Đặt sẵn Bạt Kéo Mưa & Phương Án B Sảnh Trong Nhà', 'priority' => 'urgent', 'cost_pct' => 0.15, 'vendor' => 'Đội Kỹ Thuật Resort'],
                    ],
                ],
                [
                    'timeframe' => 'Tháng 5 - Tháng 6',
                    'title' => 'Giai đoạn 3: Lễ Tiệc Ngoài Trời & Đêm Nhạc',
                    'slug' => 'giai-doan-3',
                    'icon' => '🔥',
                    'order' => 3,
                    'summary' => 'Nghi thức trao nhẫn bãi biển & đêm nhạc giao lưu',
                    'budget_pct' => 0.40,
                    'tasks' => [
                        ['title' => 'Nghi thức trao nhẫn Sunset Ceremony', 'priority' => 'urgent', 'cost_pct' => 0.15, 'vendor' => 'Wedding Planner Coordinator'],
                        ['title' => 'Đêm nhạc giao lưu bạn bè & âm thanh sự kiện', 'priority' => 'high', 'cost_pct' => 0.25, 'vendor' => 'Đội Âm Thanh Light & Sound'],
                    ],
                ],
            ];
        }

        // Default: traditional_south (Lễ Gia Tiên Miền Nam)
        return [
            [
                'timeframe' => 'Tháng 1 - Tháng 2',
                'title' => 'Giai đoạn 1: Khởi Động & Đặt Cọc Sảnh Tiệc TP.HCM',
                'slug' => 'giai-doan-1',
                'icon' => '💍',
                'order' => 1,
                'summary' => 'Chốt sảnh tiệc nhà hàng & đặt cọc đợt 1 giữ ngày đẹp',
                'budget_pct' => 0.20,
                'tasks' => [
                    ['title' => 'Đặt cọc Trung tâm Hội nghị Tiệc cưới Asiana Plaza / White Palace', 'priority' => 'urgent', 'cost_pct' => 0.12, 'vendor' => 'White Palace Event Center'],
                    ['title' => 'Đặt lịch chụp ảnh cưới Pre-Wedding Phim trường', 'priority' => 'high', 'cost_pct' => 0.08, 'vendor' => 'Tee Le Studio & Bridal'],
                ],
            ],
            [
                'timeframe' => 'Tháng 3 - Tháng 4',
                'title' => 'Giai đoạn 2: Lễ Gia Tiên & Mâm Quả',
                'slug' => 'giai-doan-2',
                'icon' => '🏮',
                'order' => 2,
                'summary' => 'Đặt 6 mâm quả dâng lễ gia tiên & thuê áo dài bê tráp',
                'budget_pct' => 0.30,
                'tasks' => [
                    ['title' => 'Đặt 6 mâm quả dâng lễ gia tiên', 'priority' => 'urgent', 'cost_pct' => 0.12, 'vendor' => 'Cưới Hỏi Tráp Vàng'],
                    ['title' => 'Thuê áo dài cưới dâu rể & đội nam nữ bê tráp (8 cặp)', 'priority' => 'high', 'cost_pct' => 0.08, 'vendor' => 'Áo Dài Cưới Sài Gòn'],
                    ['title' => 'Trang trí bàn thờ gia tiên hoa tươi tại tư gia', 'priority' => 'high', 'cost_pct' => 0.10, 'vendor' => 'Như Cưới Wedding Decor'],
                ],
            ],
            [
                'timeframe' => 'Tháng 5 - Tháng 6',
                'title' => 'Giai đoạn 3: Tiệc Cưới & Tiếp Đón Khách',
                'slug' => 'giai-doan-3',
                'icon' => '🎉',
                'order' => 3,
                'summary' => 'Đón khách check-in Photo Booth & Khai tiệc cỗ cưới 10 món',
                'budget_pct' => 0.50,
                'tasks' => [
                    ['title' => 'Gửi Thiệp Cưới Digital & Nhận RSVP Khách Mời qua Zalo', 'priority' => 'high', 'cost_pct' => 0.05, 'vendor' => 'Eloria Wedding OS'],
                    ['title' => 'Đón khách & Check-in Photo Booth hoa tươi sảnh tiệc', 'priority' => 'urgent', 'cost_pct' => 0.10, 'vendor' => 'Ban Khánh Tiết Nhà Hàng'],
                    ['title' => 'Thanh toán đợt cuối & Khai tiệc cỗ cưới', 'priority' => 'high', 'cost_pct' => 0.35, 'vendor' => 'White Palace / Gem Center'],
                ],
            ],
        ];
    }
}
