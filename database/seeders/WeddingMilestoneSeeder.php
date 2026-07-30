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
                'timeframe' => 'Tháng 1 - Tháng 2',
                'icon' => 'Building2',
                'order' => 1,
                'status' => MilestoneStatus::InProgress,
                'summary' => 'Thống nhất ngân sách trần, khảo sát địa điểm và đặt cọc sảnh tiệc cưới.',
                'notes' => 'Thống nhất hợp đồng sảnh tiệc và hoàn tất thanh toán đợt cọc đầu tiên.',
                'budget_allocated' => 100000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Thống nhất ngân sách trần & danh sách khách mời dự kiến', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Chốt số lượng bàn tiệc chính thức + bàn dự phòng.', 'vendor_info' => 'Gia đình 2 bên'],
                    ['title' => 'Khảo sát thực đơn & Đặt cọc sảnh tiệc cưới', 'is_completed' => false, 'estimated_cost' => 100000000, 'actual_cost' => 0, 'notes' => 'Đặt cọc giữ sảnh tiệc cưới.', 'vendor_info' => 'Trung tâm tiệc cưới'],
                    ['title' => 'Chốt phong cách trang trí & tone màu chủ đạo cho tiệc cưới', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Lựa chọn tone màu hoa tươi trang trí tiệc.', 'vendor_info' => 'Wedding Planner / Decor'],
                ],
            ],
            [
                'title' => 'Giai đoạn 2: Pre-wedding, Trang phục & Nhẫn cưới',
                'slug' => 'giai-doan-2-prewedding-trang-phuc',
                'timeframe' => 'Tháng 2 - Tháng 3',
                'icon' => 'Camera',
                'order' => 2,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Chụp bộ ảnh Pre-wedding studio/ngoại cảnh, chọn nhẫn cưới và may/thuê váy cô dâu + suit chú rể.',
                'notes' => 'Lên kế hoạch bộ ảnh Pre-wedding và thử trang phục tiệc cưới.',
                'budget_allocated' => 70000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Chốt ekip chụp ảnh Pre-wedding & quay phim cưới', 'is_completed' => false, 'estimated_cost' => 35000000, 'actual_cost' => 0, 'notes' => 'Lựa chọn gói chụp studio hoặc ngoại cảnh.', 'vendor_info' => 'Studio chụp ảnh cưới'],
                    ['title' => 'Chọn mẫu nhẫn cưới & khắc tên hai đứa', 'is_completed' => false, 'estimated_cost' => 15000000, 'actual_cost' => 0, 'notes' => 'Thử size và khắc tên nhẫn cưới.', 'vendor_info' => 'Cửa hàng trang sức'],
                    ['title' => 'Thử và chốt Váy cưới chính cô dâu + Vest chú rể', 'is_completed' => false, 'estimated_cost' => 20000000, 'actual_cost' => 0, 'notes' => 'Thử dáng váy tiệc chính & vest làm lễ.', 'vendor_info' => 'Thương hiệu áo cưới'],
                    ['title' => 'Thiết lập Trang Thiệp Cưới Điện Tử Online trên Eloria OS', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Tự tùy biến mẫu thiệp và lời mời cá nhân hóa.', 'vendor_info' => 'Eloria OS'],
                ],
            ],
            [
                'title' => 'Giai đoạn 3: Khách mời & Phát thiệp cưới',
                'slug' => 'giai-doan-3-khach-moi-phat-thiep',
                'timeframe' => 'Tháng 3 - Tháng 4',
                'icon' => 'MailCheck',
                'order' => 3,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Rà soát danh sách khách mời, gửi link thiệp cưới điện tử online và theo dõi phản hồi RSVP.',
                'notes' => 'Phân nhóm khách mời Họ Hàng, Bạn Bè, Đồng Nghiệp.',
                'budget_allocated' => 10000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Rà soát danh sách khách mời (Họ hàng, Bạn bè, Đồng nghiệp)', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Phân bổ số lượng bàn tiệc theo nhóm khách.', 'vendor_info' => null],
                    ['title' => 'In ấn thiệp cưới giấy & Tạo thiệp mở sáp online', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => 'Thiết kế thiệp cưới điện tử kèm mã QR check-in.', 'vendor_info' => 'Eloria OS'],
                    ['title' => 'Gửi thiệp cưới Online cá nhân hóa cho từng khách mời', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Gửi qua Zalo / Messenger kèm mã QR cá nhân.', 'vendor_info' => null],
                    ['title' => 'Theo dõi RSVP chốt số lượng người tham dự & sơ đồ bàn tiệc', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => 'Cập nhật món ăn đặc biệt cho khách.', 'vendor_info' => null],
                ],
            ],
            [
                'title' => 'Giai đoạn 4: Trang trí Gia tiên, Thực đơn & Kịch bản',
                'slug' => 'giai-doan-4-trang-tri-thuc-don',
                'timeframe' => 'Tháng 4 - Tháng 5',
                'icon' => 'Sparkles',
                'order' => 4,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Chốt mẫu trang trí hoa tươi Lễ Gia Tiên hai nhà, nếm thử thực đơn tiệc cưới và chốt kịch bản MC.',
                'notes' => null,
                'budget_allocated' => 35000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Đặt dịch vụ trang trí hoa tươi Lễ Gia Tiên nhà trai & nhà gái', 'is_completed' => false, 'estimated_cost' => 25000000, 'actual_cost' => 0, 'notes' => 'Trang trí gia tiên phong cách truyền thống ấm cúng.', 'vendor_info' => 'Dịch vụ Gia Tiên Decor'],
                    ['title' => 'Nếm thử món & chốt thực đơn tiệc cưới chính thức', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => 'Duyệt danh sách món ăn tiệc cưới.', 'vendor_info' => 'Đội ngũ bếp nhà hàng'],
                    ['title' => 'Gặp MC chốt kịch bản chương trình tiệc & chọn nhạc làm lễ', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => 'Chốt danh sách bài hát làm lễ và First Dance.', 'vendor_info' => 'MC Tiệc Cưới'],
                ],
            ],
            [
                'title' => 'Giai đoạn 5: Rà soát tổng duyệt & Chuẩn bị lễ cưới',
                'slug' => 'giai-doan-5-ra-soat-tong-duyet',
                'timeframe' => 'Tháng 5 - Tuần 3',
                'icon' => 'ListChecks',
                'order' => 5,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Rà soát 100% công việc, thử lại trang phục, chốt số lượng bàn tiệc và chuẩn bị quà đáp lễ.',
                'notes' => null,
                'budget_allocated' => 15000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Chốt số lượng bàn tiệc chính thức với nhà hàng', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Chuẩn bị quà đáp lễ dành cho khách mời', 'is_completed' => false, 'estimated_cost' => 10000000, 'actual_cost' => 0, 'notes' => 'Hộp quà cảm ơn khách tham dự.', 'vendor_info' => null],
                    ['title' => 'Họp ban khánh tiết & Phân công nhiệm vụ lễ tiệc', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Thử lại váy cưới & vest lần cuối trước ngày cưới', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                ],
            ],
            [
                'title' => 'Giai đoạn 6: NGÀY CƯỚI THĂNG HOA!',
                'slug' => 'giai-doan-6-ngay-cuoi-thang-hoa',
                'timeframe' => 'Ngày Cưới',
                'icon' => 'PartyPopper',
                'order' => 6,
                'status' => MilestoneStatus::Pending,
                'summary' => 'Lễ Gia Tiên trang trọng buổi sáng & Tiệc Cưới bùng nổ buổi tối!',
                'notes' => null,
                'budget_allocated' => 20000000.00,
                'budget_spent' => 0.00,
                'tasks' => [
                    ['title' => 'Trang điểm cô dâu & Chú rể chuẩn bị xe hoa làm lễ', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Lễ Gia Tiên hai nhà & Nghi thức cưới truyền thống', 'is_completed' => false, 'estimated_cost' => 10000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Đón khách & Khai tiệc cưới hoành tráng tại nhà hàng', 'is_completed' => false, 'estimated_cost' => 5000000, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                    ['title' => 'Trải nghiệm khoảnh khắc hạnh phúc trọn vẹn nhất đời!', 'is_completed' => false, 'estimated_cost' => 0, 'actual_cost' => 0, 'notes' => null, 'vendor_info' => null],
                ],
            ],
        ];

        // Clear previous sample milestones to replace with clean generic ones
        WeddingMilestone::query()->delete();

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
