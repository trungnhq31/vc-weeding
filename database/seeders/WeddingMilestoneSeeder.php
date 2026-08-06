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
                    [
                        'title' => 'Thống nhất ngân sách trần & danh sách khách mời dự kiến',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => 'Chốt số lượng bàn tiệc chính thức + bàn dự phòng.',
                        'vendor_info' => 'Gia đình 2 bên',
                        'subtasks' => [
                            ['id' => 'sub-1', 'title' => 'Lập file Excel tính toán ngân sách nhà trai & nhà gái', 'is_completed' => false],
                            ['id' => 'sub-2', 'title' => 'Chốt số lượng khách chính thức và bàn dự phòng (10-15%)', 'is_completed' => false],
                            ['id' => 'sub-3', 'title' => 'Thống nhất tỷ lệ đóng góp ngân sách gia đình', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Khảo sát thực đơn & Đặt cọc sảnh tiệc cưới',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 100000000,
                        'actual_cost' => 0,
                        'notes' => 'Đặt cọc giữ sảnh tiệc cưới.',
                        'vendor_info' => 'Trung tâm tiệc cưới',
                        'subtasks' => [
                            ['id' => 'sub-4', 'title' => 'Lên danh sách 3-5 nhà hàng tiệc cưới tiềm năng', 'is_completed' => false],
                            ['id' => 'sub-5', 'title' => 'Khảo sát không gian sảnh tiệc & bãi đỗ xe', 'is_completed' => false],
                            ['id' => 'sub-6', 'title' => 'Thử món thực đơn 6 món tiệc cưới', 'is_completed' => false],
                            ['id' => 'sub-7', 'title' => 'Ký hợp đồng & thanh toán cọc đợt 1 (30%)', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Chốt phong cách trang trí & tone màu chủ đạo cho tiệc cưới',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => 'Lựa chọn tone màu hoa tươi trang trí tiệc.',
                        'vendor_info' => 'Wedding Planner / Decor',
                        'subtasks' => [
                            ['id' => 'sub-8', 'title' => 'Chọn Moodboard tone màu (Rose Gold / Sage Green / Champagne)', 'is_completed' => false],
                            ['id' => 'sub-9', 'title' => 'Thống nhất thiết kế Backdrop đón khách & Sân khấu làm lễ', 'is_completed' => false],
                        ],
                    ],
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
                    [
                        'title' => 'Chốt ekip chụp ảnh Pre-wedding & quay phim cưới',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 35000000,
                        'actual_cost' => 0,
                        'notes' => 'Lựa chọn gói chụp studio hoặc ngoại cảnh.',
                        'vendor_info' => 'Studio chụp ảnh cưới',
                        'subtasks' => [
                            ['id' => 'sub-10', 'title' => 'Xem portfolio 3 Studio chuyên nghiệp', 'is_completed' => false],
                            ['id' => 'sub-11', 'title' => 'Chốt địa điểm chụp (Studio indoor / Ngoại cảnh)', 'is_completed' => false],
                            ['id' => 'sub-12', 'title' => 'Thử đồ studio & chốt kịch bản chụp ảnh', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Chọn mẫu nhẫn cưới & khắc tên hai đứa',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 15000000,
                        'actual_cost' => 0,
                        'notes' => 'Thử size và khắc tên nhẫn cưới.',
                        'vendor_info' => 'Cửa hàng trang sức',
                        'subtasks' => [
                            ['id' => 'sub-13', 'title' => 'Đến showroom đo size tay cô dâu & chú rể', 'is_completed' => false],
                            ['id' => 'sub-14', 'title' => 'Chốt chất liệu vàng 18K / Vàng trắng', 'is_completed' => false],
                            ['id' => 'sub-15', 'title' => 'Khắc ngày cưới & tên gọi thân mật inside nhẫn', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Thử và chốt Váy cưới chính cô dâu + Vest chú rể',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 20000000,
                        'actual_cost' => 0,
                        'notes' => 'Thử dáng váy tiệc chính & vest làm lễ.',
                        'vendor_info' => 'Thương hiệu áo cưới',
                        'subtasks' => [
                            ['id' => 'sub-16', 'title' => 'Thử 3 dáng váy cưới (Váy công chúa, Đuôi cá, A-line)', 'is_completed' => false],
                            ['id' => 'sub-17', 'title' => 'May đo / chỉnh sửa vừa vặn thân hình', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Thiết lập Trang Thiệp Cưới Điện Tử Online trên Eloria OS',
                        'priority' => 'medium',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => 'Tự tùy biến mẫu thiệp và lời mời cá nhân hóa.',
                        'vendor_info' => 'Eloria OS',
                        'subtasks' => [
                            ['id' => 'sub-18', 'title' => 'Chọn mẫu thiệp điện tử 3D phong cách lãng mạn', 'is_completed' => false],
                            ['id' => 'sub-19', 'title' => 'Cấu hình Google Maps chỉ đường & Form RSVP', 'is_completed' => false],
                        ],
                    ],
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
                    [
                        'title' => 'Rà soát danh sách khách mời (Họ hàng, Bạn bè, Đồng nghiệp)',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => 'Phân bổ số lượng bàn tiệc theo nhóm khách.',
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-20', 'title' => 'Phân nhóm khách mời theo mối quan hệ', 'is_completed' => false],
                            ['id' => 'sub-21', 'title' => 'Ghi chú món ăn kiêng / ăn chay của từng khách', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'In ấn thiệp cưới giấy & Tạo thiệp mở sáp online',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 5000000,
                        'actual_cost' => 0,
                        'notes' => 'Thiết kế thiệp cưới điện tử kèm mã QR check-in.',
                        'vendor_info' => 'Eloria OS',
                        'subtasks' => [
                            ['id' => 'sub-22', 'title' => 'In thiệp giấy dành tặng người lớn tuổi', 'is_completed' => false],
                            ['id' => 'sub-23', 'title' => 'Tạo link thiệp Online cá nhân hóa kèm mã QR', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Gửi thiệp cưới Online cá nhân hóa cho từng khách mời',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => 'Gửi qua Zalo / Messenger kèm mã QR cá nhân.',
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-24', 'title' => 'Gửi link thiệp qua Zalo/Messenger kèm lời nhắn', 'is_completed' => false],
                            ['id' => 'sub-25', 'title' => 'Nhắc khách điền thông tin tham dự trên Eloria OS', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Theo dõi RSVP chốt số lượng người tham dự & sơ đồ bàn tiệc',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => 'Cập nhật món ăn đặc biệt cho khách.',
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-26', 'title' => 'Báo cáo RSVP thời gian thực trên Dashboard', 'is_completed' => false],
                            ['id' => 'sub-27', 'title' => 'Chốt danh sách xếp bàn tiệc 10 người/bàn', 'is_completed' => false],
                        ],
                    ],
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
                    [
                        'title' => 'Đặt dịch vụ trang trí hoa tươi Lễ Gia Tiên nhà trai & nhà gái',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 25000000,
                        'actual_cost' => 0,
                        'notes' => 'Trang trí gia tiên phong cách truyền thống ấm cúng.',
                        'vendor_info' => 'Dịch vụ Gia Tiên Decor',
                        'subtasks' => [
                            ['id' => 'sub-28', 'title' => 'Chốt mẫu bàn thờ gia tiên & mâm quả', 'is_completed' => false],
                            ['id' => 'sub-29', 'title' => 'Chốt danh sách 6-8 bạn trẻ bê tráp hai nhà', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Nếm thử món & chốt thực đơn tiệc cưới chính thức',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 5000000,
                        'actual_cost' => 0,
                        'notes' => 'Duyệt danh sách món ăn tiệc cưới.',
                        'vendor_info' => 'Đội ngũ bếp nhà hàng',
                        'subtasks' => [
                            ['id' => 'sub-30', 'title' => 'Duyệt thực đơn 6 món chính + đồ uống', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Gặp MC chốt kịch bản chương trình tiệc & chọn nhạc làm lễ',
                        'priority' => 'medium',
                        'is_completed' => false,
                        'estimated_cost' => 5000000,
                        'actual_cost' => 0,
                        'notes' => 'Chốt danh sách bài hát làm lễ và First Dance.',
                        'vendor_info' => 'MC Tiệc Cưới',
                        'subtasks' => [
                            ['id' => 'sub-31', 'title' => 'Truyền đạt câu chuyện tình yêu cho MC', 'is_completed' => false],
                            ['id' => 'sub-32', 'title' => 'Chốt playlist nhạc làm lễ & First Dance', 'is_completed' => false],
                        ],
                    ],
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
                    [
                        'title' => 'Chốt số lượng bàn tiệc chính thức với nhà hàng',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => null,
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-33', 'title' => 'Báo số lượng bàn chính & bàn dự phòng', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Chuẩn bị quà đáp lễ dành cho khách mời',
                        'priority' => 'medium',
                        'is_completed' => false,
                        'estimated_cost' => 10000000,
                        'actual_cost' => 0,
                        'notes' => 'Hộp quà cảm ơn khách tham dự.',
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-34', 'title' => 'Đóng gói 200 hộp quà cảm ơn', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Họp ban khánh tiết & Phân công nhiệm vụ lễ tiệc',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 5000000,
                        'actual_cost' => 0,
                        'notes' => null,
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-35', 'title' => 'Phân công người quản lý thùng tiền mừng & xe đưa đón', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Thử lại váy cưới & vest lần cuối trước ngày cưới',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => null,
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-36', 'title' => 'Thử đồ lần cuối & lấy trang phục về nhà', 'is_completed' => false],
                        ],
                    ],
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
                    [
                        'title' => 'Trang điểm cô dâu & Chú rể chuẩn bị xe hoa làm lễ',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 5000000,
                        'actual_cost' => 0,
                        'notes' => null,
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-37', 'title' => 'Makeup cô dâu từ 4h00 sáng', 'is_completed' => false],
                            ['id' => 'sub-38', 'title' => 'Kiểm tra xe hoa chú rể', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Lễ Gia Tiên hai nhà & Nghi thức cưới truyền thống',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 10000000,
                        'actual_cost' => 0,
                        'notes' => null,
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-39', 'title' => 'Trao mâm quả & xin dâu', 'is_completed' => false],
                            ['id' => 'sub-40', 'title' => 'Nghi thức thắp hương gia tiên & trao vàng', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Đón khách & Khai tiệc cưới hoành tráng tại nhà hàng',
                        'priority' => 'urgent',
                        'is_completed' => false,
                        'estimated_cost' => 5000000,
                        'actual_cost' => 0,
                        'notes' => null,
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-41', 'title' => 'Đón khách & chụp ảnh tại Backdrop', 'is_completed' => false],
                            ['id' => 'sub-42', 'title' => 'Bắt đầu lễ cưới, cắt bánh & rót rượu Champagne', 'is_completed' => false],
                        ],
                    ],
                    [
                        'title' => 'Trải nghiệm khoảnh khắc hạnh phúc trọn vẹn nhất đời!',
                        'priority' => 'high',
                        'is_completed' => false,
                        'estimated_cost' => 0,
                        'actual_cost' => 0,
                        'notes' => null,
                        'vendor_info' => null,
                        'subtasks' => [
                            ['id' => 'sub-43', 'title' => 'Nâng ly chúc mừng hạnh phúc lứa đôi!', 'is_completed' => false],
                        ],
                    ],
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
