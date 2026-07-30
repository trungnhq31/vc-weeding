<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Enums\RsvpStatus;
use App\Models\Category;
use App\Models\Guest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\WeddingMemory;
use App\Models\Wish;
use App\Modules\Budget\Models\BudgetItem;
use App\Modules\Guest\Models\Table;
use App\Modules\Invitation\Models\InvitationTemplate;
use App\Modules\Invitation\Models\WorkspaceInvitation;
use App\Modules\Task\Models\Task;
use App\Modules\Workspace\Models\Workspace;
use App\Modules\Workspace\Models\WorkspaceMember;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Seed Invitation Templates Catalog
        $templates = [
            [
                'id' => 'romantic-pastel',
                'name' => 'Romantic Pastel',
                'description' => 'Phong cách Hồng & Kem lãng mạn nhẹ nhàng với hiệu ứng mở sáp Wax Seal.',
                'thumbnail_url' => '/images/templates/pastel-thumb.png',
                'vue_component' => 'Templates/Pastel.vue',
                'is_premium' => false,
            ],
            [
                'id' => 'royal-gold',
                'name' => 'Royal Champagne & Gold',
                'description' => 'Phong cách Vàng Champagne & Trắng ngà sang trọng với font Playfair Display.',
                'thumbnail_url' => '/images/templates/royal-thumb.png',
                'vue_component' => 'Templates/RoyalGold.vue',
                'is_premium' => true,
            ],
            [
                'id' => 'modern-slate',
                'name' => 'Modern Minimalist Slate',
                'description' => 'Phong cách Tối giản hiện đại màu Slate/Indigo dành cho cặp đôi trẻ.',
                'thumbnail_url' => '/images/templates/slate-thumb.png',
                'vue_component' => 'Templates/ModernSlate.vue',
                'is_premium' => false,
            ],
            [
                'id' => 'botanical-sage',
                'name' => 'Vintage Botanical Garden',
                'description' => 'Phong cách hoa lá thiên nhiên màu Xanh Sage tươi mát cho tiệc cưới ngoài trời.',
                'thumbnail_url' => '/images/templates/botanical-thumb.png',
                'vue_component' => 'Templates/BotanicalSage.vue',
                'is_premium' => true,
            ],
        ];

        foreach ($templates as $tpl) {
            InvitationTemplate::updateOrCreate(['id' => $tpl['id']], $tpl);
        }

        // 0.1 Seed Default Wedding Workspace
        $workspace = Workspace::create([
            'name' => 'Đám Cưới Nguyễn Hoàng Quốc Trung & Lê Thị Hồng Vân',
            'slug' => 'quoc-trung-hong-van',
            'groom_name' => 'Nguyễn Hoàng Quốc Trung',
            'bride_name' => 'Lê Thị Hồng Vân',
            'wedding_date' => '2026-10-24',
            'wedding_location' => 'TP. Hồ Chí Minh',
            'venue_name' => null,
            'estimated_guests' => 200,
            'wedding_hashtag' => '#TrungVanWedding2026',
            'couple_story' => 'Hành trình 6 năm tình yêu từ mái trường đại học đến ngày chung đôi hạnh phúc.',
            'budget_cap' => 350000000.00,
            'currency' => 'VND',
        ]);

        WorkspaceMember::create([
            'workspace_id' => $workspace->id,
            'member_name' => 'Quốc Trung',
            'role' => 'groom',
        ]);

        WorkspaceMember::create([
            'workspace_id' => $workspace->id,
            'member_name' => 'Hồng Vân',
            'role' => 'bride',
        ]);

        WorkspaceInvitation::create([
            'workspace_id' => $workspace->id,
            'template_id' => 'romantic-pastel',
            'custom_title' => 'Lễ Thành Hôn Quốc Trung & Hồng Vân',
            'primary_color' => '#EC4899',
            'enable_wax_seal' => true,
            'enable_qr_checkin' => true,
        ]);

        // 0.15 Seed Sample Tables
        $tableVip = Table::create([
            'workspace_id' => $workspace->id,
            'table_name' => 'Bàn VIP 02 (Đồng Nghiệp)',
            'capacity' => 10,
            'zone_name' => 'Sảnh Chính - Khu VIP',
            'shape' => 'round',
        ]);

        $tableFamily = Table::create([
            'workspace_id' => $workspace->id,
            'table_name' => 'Bàn 05 (Họ Hàng Nhà Chú Rể)',
            'capacity' => 10,
            'zone_name' => 'Sảnh Trái - Họ Hàng',
            'shape' => 'round',
        ]);

        // 0.2 Seed Sample Tasks
        Task::create([
            'workspace_id' => $workspace->id,
            'category' => 'venue',
            'title' => 'Đặt cọc Trung tâm Hội nghị Tiệc cưới Asiana Plaza',
            'description' => 'Hoàn tất thanh toán đợt 1 giữ sảnh tiệc Grand Ballroom.',
            'status' => 'done',
            'priority' => 'urgent',
            'due_date' => now()->subDays(5),
            'estimated_cost' => 150000000.00,
            'actual_cost' => 150000000.00,
        ]);

        Task::create([
            'workspace_id' => $workspace->id,
            'category' => 'attire',
            'title' => 'May đo Áo dài Ăn hỏi & Váy cưới Nhập khẩu',
            'description' => 'Hẹn thử áo dài tư gia và chốt số đo váy cưới chính tiệc.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(3),
            'estimated_cost' => 45000000.00,
            'actual_cost' => 48000000.00,
        ]);

        Task::create([
            'workspace_id' => $workspace->id,
            'category' => 'media',
            'title' => 'Chốt Hợp đồng Ekip Phim ảnh & Pre-wedding Đà Lạt',
            'description' => 'Xác nhận danh sách thợ chụp chính, thợ quay highlight và xe di chuyển.',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => now()->addDays(7),
            'estimated_cost' => 35000000.00,
            'actual_cost' => 35000000.00,
        ]);

        // 0.3 Seed Sample Budget Items
        BudgetItem::create([
            'workspace_id' => $workspace->id,
            'category_name' => 'Địa điểm & Tiệc cưới',
            'item_name' => 'Tiệc cưới Asiana Plaza (30 Bàn VIP)',
            'estimated_amount' => 180000000.00,
            'actual_amount' => 185000000.00,
            'deposit_paid' => 50000000.00,
            'payment_status' => 'deposit_paid',
            'due_payment_date' => now()->addDays(4),
            'notes' => 'Hạn thanh toán đợt 2 trước ngày 05 tháng sau.',
        ]);

        BudgetItem::create([
            'workspace_id' => $workspace->id,
            'category_name' => 'Phim ảnh & Quay chụp',
            'item_name' => 'Gói Chụp Pre-wedding & Phóng sự cưới',
            'estimated_amount' => 35000000.00,
            'actual_amount' => 35000000.00,
            'deposit_paid' => 15000000.00,
            'payment_status' => 'deposit_paid',
            'due_payment_date' => now()->addDays(12),
            'notes' => 'Cọc đợt 1 đã giữ lịch.',
        ]);

        BudgetItem::create([
            'workspace_id' => $workspace->id,
            'category_name' => 'Trang phục & Trang điểm',
            'item_name' => 'Áo dài Cưới & Váy Cưới Công chúa',
            'estimated_amount' => 45000000.00,
            'actual_amount' => 48000000.00,
            'deposit_paid' => 48000000.00,
            'payment_status' => 'fully_paid',
            'due_payment_date' => null,
            'notes' => 'Đã thanh toán trọn gói.',
        ]);

        // 1. Seed Sample Guests with workspace_id and table_id
        $guest = Guest::create([
            'workspace_id' => $workspace->id,
            'table_id' => $tableVip->id,
            'guest_slug' => 'anh-tuan-va-chi-lan',
            'name' => 'Anh Tuấn & Chị Lan',
            'salutation' => 'Trân trọng kính mời Anh Tuấn & Chị Lan',
            'group' => 'Đồng nghiệp',
            'estimated_count' => 2,
            'confirmed_count' => 2,
            'dietary_preference' => 'Món mặn chuẩn set menu',
            'shuttle_bus' => 'yes',
            'qr_code_token' => 'QR-TUANLAN-2026',
            'is_checked_in' => false,
            'table_name' => 'Bàn VIP 02 (Đồng Nghiệp)',
            'rsvp_status' => RsvpStatus::Attending,
            'notes' => 'Sẽ đến đúng giờ dự tiệc',
        ]);

        Guest::create([
            'workspace_id' => $workspace->id,
            'table_id' => $tableFamily->id,
            'guest_slug' => 'gia-dinh-chu-sau',
            'name' => 'Gia đình Chú Sáu',
            'salutation' => 'Kính mời Chú Sáu và Gia đình',
            'group' => 'Họ hàng',
            'estimated_count' => 4,
            'confirmed_count' => 0,
            'dietary_preference' => null,
            'shuttle_bus' => 'no',
            'qr_code_token' => 'QR-CHUSAU-2026',
            'is_checked_in' => false,
            'table_name' => 'Bàn 05 (Họ Hàng Nhà Chú Rể)',
            'rsvp_status' => RsvpStatus::Pending,
            'notes' => null,
        ]);

        // 2. Seed Sample Wishes
        Wish::create([
            'workspace_id' => $workspace->id,
            'guest_id' => $guest->id,
            'sender_name' => 'Anh Tuấn & Chị Lan',
            'message' => 'Chúc hai bạn trăm năm hạnh phúc, sớm đón quý tử nhé!',
            'is_approved' => true,
            'is_pinned' => true,
        ]);

        Wish::create([
            'workspace_id' => $workspace->id,
            'guest_id' => null,
            'sender_name' => 'Bạn thân cấp 3',
            'message' => 'Mừng ngày vui của hai bạn! Chúc một đời an yên, cùng nhau vượt qua mọi sóng gió.',
            'is_approved' => true,
            'is_pinned' => false,
        ]);

        // 3. Seed Sample Wedding Memories
        WeddingMemory::create([
            'workspace_id' => $workspace->id,
            'guest_id' => null,
            'uploader_name' => 'Chú Rể Quốc Trung',
            'category' => 'pre_wedding',
            'title' => 'Chụp Ảnh Cổng Ngoại Cảnh Đà Lạt',
            'description' => 'Khoảnh khắc bình minh tuyệt đẹp tại đồi thông Đà Lạt.',
            'image_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80',
            'is_approved' => true,
            'is_pinned' => true,
        ]);

        WeddingMemory::create([
            'workspace_id' => $workspace->id,
            'guest_id' => null,
            'uploader_name' => 'Cô Dâu Hồng Vân',
            'category' => 'engagement',
            'title' => 'Lễ Đính Hôn Ấm Cúng',
            'description' => 'Nghi lễ ăn hỏi truyền thống tại tư gia hai họ.',
            'image_url' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1200&q=80',
            'is_approved' => true,
            'is_pinned' => true,
        ]);

        WeddingMemory::create([
            'workspace_id' => $workspace->id,
            'guest_id' => $guest->id,
            'uploader_name' => 'Anh Tuấn & Chị Lan',
            'category' => 'guest_upload',
            'title' => 'Kỷ niệm cùng Dâu Rể tại buổi tập dượt tiệc',
            'description' => 'Chúc dâu rể luôn rạng rỡ và hạnh phúc như hôm nay!',
            'image_url' => 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=1200&q=80',
            'is_approved' => true,
            'is_pinned' => false,
        ]);

        // 3. Seed Blog Categories & Tags
        $catSystem = Category::create(['name' => 'System Design', 'slug' => 'system-design']);
        $catLaravel = Category::create(['name' => 'Laravel Framework', 'slug' => 'laravel-framework']);

        $tagVue = Tag::create(['name' => 'Vue 3', 'slug' => 'vue-3']);
        $tagInertia = Tag::create(['name' => 'Inertia v2', 'slug' => 'inertia-v2']);
        $tagReverb = Tag::create(['name' => 'WebSockets', 'slug' => 'websockets']);

        // 4. Seed Blog Posts
        $post = Post::create([
            'category_id' => $catLaravel->id,
            'title' => 'Xây dựng Online Wedding Invitation với Laravel 13, Inertia v2 & Reverb',
            'slug' => 'xay-dung-online-wedding-invitation-voi-laravel-13',
            'excerpt' => 'Hướng dẫn chi tiết cách thiết kế hệ thống thiệp cưới cá nhân hóa tích hợp Reverb WebSockets và Subdomain architecture.',
            'content_markdown' => "# Xây dựng Online Wedding Invitation\n\nHệ thống thiệp cưới online cho phép mỗi khách mời nhận được một URL riêng biệt với lời xưng hô thân mật.\n\n```php\n// Action submit RSVP\n\$action->execute(\$rsvpData);\n```",
            'content_html' => '<h1>Xây dựng Online Wedding Invitation</h1><p>Hệ thống thiệp cưới online cho phép mỗi khách mời nhận được một URL riêng biệt.</p>',
            'reading_time_minutes' => 5,
            'status' => PostStatus::Published,
            'published_at' => now(),
            'seo_title' => 'Hướng dẫn xây dựng Thiệp Cưới Online Laravel 13',
            'seo_description' => 'Khám phá kiến trúc xây dựng trang thiệp cưới tương tác realtime với Laravel Reverb và Inertia.js v2.',
        ]);

        $post->tags()->attach([$tagVue->id, $tagInertia->id, $tagReverb->id]);

        // 5. Seed Wedding Preparation Milestones & Tasks
        $this->call(WeddingMilestoneSeeder::class);
    }
}
