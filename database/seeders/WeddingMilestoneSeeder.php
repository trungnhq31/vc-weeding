<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MilestoneStatus;
use App\Models\WeddingMilestone;
use Illuminate\Database\Seeder;

class WeddingMilestoneSeeder extends Seeder
{
    public function run(): void
    {
        $milestones = [
            [
                'title' => 'Giai đoạn 1: Khởi động & Đặt cọc địa điểm',
                'slug' => 'giai-doan-1-khoi-dong-dat-coc',
                'timeframe' => '15/07 - 31/07/2026',
                'icon' => 'Building2',
                'order' => 1,
                'status' => MilestoneStatus::Completed,
                'summary' => 'Chốt ngân sách 250M, khảo sát và đặt cọc sảnh tiệc 25 bàn tại Asiana Plaza.',
                'notes' => 'Đã chốt hợp đồng giữ sảnh Peak Season với Asiana Plaza. Đã gửi tiền đặt cọc đợt 1.',
                'budget_allocated' => 100000000.00,
                'budget_spent' => 95000000.00,
                'tasks' => [
                    ['title' => 'Thống nhất ngân sách trần 250,000,000 VNĐ & danh sách 250 khách', 'is_completed' => true, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Chốt 25 bàn tiệc chính thức + 2 bàn dự phòng.', 'vendor_info' => 'Gia đình 2 bên'],
                    ['title' => 'Khảo sát thực đơn & Đặt cọc sảnh tiệc Asiana Plaza (Peak Season 12/2026)', 'is_completed' => true, 'estimated_cost' => 100000000, 'actual_cost' => 95000000, 'notes' => 'Hợp đồng số #AP-2026-1219. Đặt cọc đợt 1 thành công.', 'vendor_info' => 'Asiana Plaza - Hotine: 028 38 123 456'],
                    ['title' => 'Chốt concept chủ đạo: Cream, Gold & Rosewood elegance', 'is_completed' => true, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Tone màu sang trọng phù hợp sảnh tiệc Asiana Plaza.', 'vendor_info' => 'Wedding Planner'],
                ],
            ],
            [
                'title' => 'Giai đoạn 2: Pre-wedding, Trang phục & Nhẫn cưới',
                'slug' => 'giai-doan-2-prewedding-trang-phuc',
                'timeframe' => '01/08 - 15/09/2026',
                'icon' => 'Camera',
                'order' => 2,
                'status' => MilestoneStatus::InProgress,
                'summary' => 'Chụp bộ ảnh Pre-wedding studio/ngoại cảnh, mua nhẫn cưới và may/thuê váy cô dâu + suit chú rể.',
                'notes' => 'Đã chọn studio chụp Pre-wedding tại Đà Lạt. Dự kiến chụp vào giữa tháng 8.',
                'budget_allocated' => 70000000.00,
                'budget_spent' => 68000000.00,
                'tasks' => [
                    ['title' => 'Chốt ekip chụp ảnh Pre-wedding & quay phim cưới', 'is_completed' => true, 'estimated_cost' => 35000000, 'actual_cost' => 34000000, 'notes' => 'Gói chụp 2 ngày tại Đà Lạt + Studio HCM.', 'vendor_info' => 'Artisan Wedding Studio'],
                    ['title' => 'Chọn mẫu nhẫn cưới & khắc tên hai đứa', 'is_completed' => true, 'estimated_cost' => 15000000, 'actual_cost' => 14500000, 'notes' => 'Nhẫn cưới Vàng Tây 18K khắc tên "Vân & Cẩm 19.12.2026".', 'vendor_info' => 'PNJ Gold Studio'],
                    ['title' => 'Thử và chốt Váy cưới chính cô dâu + Vest chú rể', 'is_completed' => true, 'estimated_cost' => 20000000, 'actual_cost' => 19500000, 'notes' => 'Váy xòe cúp ngực đính đá cho cô dâu + Vest đen Lịch lãm.', 'vendor_info' => 'Bridal Boutique'],
                    ['title' => 'Lập trang Web Thiệp Cưới Online (VCDevHub Subdomain & Reverb Realtime)', 'is_completed' => true, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => 'Hệ thống Laravel 13 + Vue 3 Inertia v2 + Reverb realtime.', 'vendor_info' => 'Chú rể tự code'],
                ],
            ],
            [
                'title' => 'Giai đoạn 3: Khách mời & Phát thiệp cưới',
                'slug' => 'giai-doan-3-khach-moi-phat-thiep',
                'timeframe' => '16/09 - 31/10/2026',
                'icon' => 'MailCheck',
                'order' => 3,
                'status' => MilestoneStatus::InProgress,
                'summary' => 'In ấn thiệp cưới giấy, gửi link thiệp online cá nhân hóa cho từng khách và theo dõi phản hồi RSVP.',
                'notes' => 'Lập file Excel 250 khách mời phân nhóm Họ Hàng, Bạn Bè, Đồng Nghiệp.',
                'budget_allocated' => 10000000.00,
                'budget_spent' => 3000000.00,
                'tasks' => [
                    ['title' => 'Rà soát danh sách 250 khách mời (Họ hàng, Bạn bè, Đồng nghiệp)', 'is_completed' => true, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Phân bổ 12 bàn Họ hàng, 8 bàn Bạn bè, 5 bàn Đồng nghiệp.', 'vendor_info' => null],
                    ['title' => 'Thiết kế & in ấn thiệp cưới giấy cao cấp', 'is_completed' => true, 'estimated_cost' => 5000000, 'actual_cost' => 3000000, 'notes' => 'In 200 thiệp giấy ép kim ép nổ.', 'vendor_info' => 'Xưởng In Thiệp Đẹp'],
                    ['title' => 'Gửi thiệp cưới Online cá nhân hóa (wedding.vcwedding.test/{guest_slug})', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Gửi link Zalo / Messenger kèm mã QR cá nhân.', 'vendor_info' => null],
                    ['title' => 'Theo dõi RSVP chốt số lượng người đi cùng & thực đơn đặc biệt', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Cập nhật món chay cho khách có nhu cầu.', 'vendor_info' => null],
                ],
            ],
            [
                'title' => 'Giai đoạn 4: Trang trí Gia tiên, Thực đơn & Kịch bản',
                'slug' => 'giai-doan-4-trang-tri-thuc-don',
                'timeframe' => '01/11 - 30/11/2026',
                'icon' => 'Sparkles',
                'order' => 4,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Chốt mẫu trang trí hoa tươi Lễ Gia Tiên hai nhà, nếm thử thực đơn tiệc cưới Asiana Plaza và chốt kịch bản MC.',
                'notes' => null,
                'budget_allocated' => 35000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Đặt dịch vụ trang trí hoa tươi Lễ Gia Tiên nhà trai & nhà gái', 'is_completed' => false, 'estimated_cost' => 25000000, 'actual_cost' => 0, 'notes' => 'Hoa hồng kem + hoa Cát Tường phong cách truyền thống.', 'vendor_info' => 'Gia Tiên Decor'],
                    ['title' => 'Nếm thử món & chốt thực đơn 6 món tiệc cưới Asiana Plaza', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => 'Thực đơn 6 món cao cấp bao gồm Súp hải sản & Bò sốt tiêu đen.', 'vendor_info' => 'Asiana Plaza Chef'],
                    ['title' => 'Gặp MC chốt kịch bản chương trình tiệc & chọn bài hát First Dance', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => 'Bài hát chọn: "Until I Found You".', 'vendor_info' => 'MC Tiệc Cưới'],
                ],
            ],
            [
                'title' => 'Giai đoạn 5: Rà soát tổng duyệt & Chuẩn bị lễ cưới',
                'slug' => 'giai-doan-5-ra-soat-tong-duyet',
                'timeframe' => '01/12 - 15/12/2026',
                'icon' => 'ListChecks',
                'order' => 5,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Rà soát 100% công việc, thử lại trang phục, chốt số lượng bàn tiệc với nhà hàng và mua quà đáp lễ khách.',
                'notes' => null,
                'budget_allocated' => 15000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Chốt số lượng bàn tiệc chính thức (25 bàn) với Asiana Plaza', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Chuẩn bị 250 phần quà đáp lễ khách mời', 'is_completed' => false, 'estimated_cost' => 10000000, 'actual_cost' => 0, 'notes' => 'Hộp trà hoa cúc & bánh quy bơ handmade.', 'vendor_info' => 'Sweet Gift Shop'],
                    ['title' => 'Họp với Ban Khánh Tiết (Phụ trách nhẫn, bao lì xì bêtáp, xe hoa)', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Thử lại váy cưới & vest lần cuối', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                ],
            ],
            [
                'title' => 'Giai đoạn 6: NGÀY CƯỚI THĂNG HOA! (19/12/2026)',
                'slug' => 'giai-doan-6-ngay-cuoi-thang-hoa',
                'timeframe' => '16/12 - 19/12/2026',
                'icon' => 'PartyPopper',
                'order' => 6,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Lễ Gia Tiên buổi sáng & Tiệc Cưới Bùng Nổ buổi tối tại Asiana Plaza!',
                'notes' => null,
                'budget_allocated' => 20000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Trang điểm cô dâu & Chú rể chuẩn bị xe hoa (05:00)', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Lễ Gia Tiên hai nhà & Làm lễ gia tiên trang trọng (07:30 - 10:30)', 'is_completed' => false, 'estimated_cost' => 10000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Đón khách & Khai tiệc cưới hoành tráng tại Asiana Plaza (17:30 - 21:30)', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Trải nghiệm khoảnh khắc hạnh phúc trọn vẹn nhất đời!', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                ],
            ],
        ];

        foreach ($milestones as $data) {
            $tasks = $data['tasks'];
            unset($data['tasks']);

            $milestone = WeddingMilestone::create($data);

            foreach ($tasks as $taskData) {
                $milestone->tasks()->create($taskData);
            }
        }
    }
}
