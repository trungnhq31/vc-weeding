<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Enums\RsvpStatus;
use App\Models\Category;
use App\Models\Guest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
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
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -1. Seed Demo Login Accounts
        User::updateOrCreate(
            ['email' => 'groom@eloria.vn'],
            ['name' => 'Chú Rể Quốc Trung', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'bride@eloria.vn'],
            ['name' => 'Cô Dâu Hồng Vân', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'admin@eloria.vn'],
            ['name' => 'Eloria Admin', 'password' => Hash::make('password')]
        );

        // 0. Seed Invitation Templates Catalog (10 Curated Premium Templates)
        $templates = [
            [
                'id' => 'romantic-pastel',
                'name' => '1. Romantic Pastel & Wax Seal',
                'description' => 'Bố cục Hồng Phấn & Kem ấm áp mở dấu sáp nến Wax Seal lãng mạn.',
                'thumbnail_url' => '/images/templates/pastel-thumb.png',
                'vue_component' => 'Templates/PastelTemplate.vue',
                'is_premium' => false,
            ],
            [
                'id' => 'royal-gold',
                'name' => '2. Royal Gold & Monogram Crest',
                'description' => 'Khung viền vàng dát lá & Biểu tượng Monogram Crest T&V hoàng gia quý phái.',
                'thumbnail_url' => '/images/templates/royal-thumb.png',
                'vue_component' => 'Templates/RoyalGoldTemplate.vue',
                'is_premium' => true,
            ],
            [
                'id' => 'modern-slate',
                'name' => '3. Modern Editorial Magazine',
                'description' => 'Bố cục tạp chí thời trang asymmetric 2 cột thanh lịch có thanh điều hướng sticky.',
                'thumbnail_url' => '/images/templates/slate-thumb.png',
                'vue_component' => 'Templates/ModernSlateTemplate.vue',
                'is_premium' => false,
            ],
            [
                'id' => 'botanical-sage',
                'name' => '4. Botanical Garden & Arch Cards',
                'description' => 'Khung ảnh vòm mềm mại & họa tiết dây lá tươi mát cho tiệc cưới ngoài trời.',
                'thumbnail_url' => '/images/templates/botanical-thumb.png',
                'vue_component' => 'Templates/BotanicalSageTemplate.vue',
                'is_premium' => true,
            ],
            [
                'id' => 'indochine-traditional',
                'name' => '5. Indochine Red Velvet & Song Hỷ',
                'description' => 'Họa tiết lưới gỗ Đông Dương & Biểu tượng Song Hỷ (囍) dát vàng trên nền đỏ nhung.',
                'thumbnail_url' => '/images/templates/indochine-thumb.png',
                'vue_component' => 'Templates/IndochineTemplate.vue',
                'is_premium' => true,
            ],
            [
                'id' => 'celestial-blue',
                'name' => '6. Ocean Breeze Boarding Pass Ticket',
                'description' => 'Vé máy bay chuyến bay tình yêu xé góc kèm mã QR check-in sân bay.',
                'thumbnail_url' => '/images/templates/blue-thumb.png',
                'vue_component' => 'Templates/BoardingPassTemplate.vue',
                'is_premium' => false,
            ],
            [
                'id' => 'emerald-luxe',
                'name' => '7. Imperial Emerald Glass Ring',
                'description' => 'Nền xanh ngọc bảo hoàng gia kết hợp thẻ kính mờ & vòng đếm ngược kim tuyến.',
                'thumbnail_url' => '/images/templates/emerald-thumb.png',
                'vue_component' => 'Templates/EmeraldLuxeTemplate.vue',
                'is_premium' => true,
            ],
            [
                'id' => 'sunset-coral',
                'name' => '8. Tropical Sunset 50/50 Split View',
                'description' => 'Bố cục chia đôi màn hình 50/50 màu hồng cam san hô tràn đầy năng lượng.',
                'thumbnail_url' => '/images/templates/coral-thumb.png',
                'vue_component' => 'Templates/SunsetCoralTemplate.vue',
                'is_premium' => false,
            ],
            [
                'id' => 'crimson-velvet',
                'name' => '9. The Wedding Gazette Newspaper',
                'description' => 'Báo in tin tức tiệc cưới cổ điển 3 cột "EXTRA EXTRA! JUST MARRIED".',
                'thumbnail_url' => '/images/templates/crimson-thumb.png',
                'vue_component' => 'Templates/GazetteNewspaperTemplate.vue',
                'is_premium' => true,
            ],
            [
                'id' => 'vintage-sepia',
                'name' => '10. Storybook Fairytale Journal',
                'description' => 'Cuốn sách câu chuyện tình yêu với các thẻ phân chương (Chương I - IV) hoài niệm.',
                'thumbnail_url' => '/images/templates/sepia-thumb.png',
                'vue_component' => 'Templates/StorybookJournalTemplate.vue',
                'is_premium' => true,
            ],
        ];

        foreach ($templates as $tpl) {
            InvitationTemplate::updateOrCreate(['id' => $tpl['id']], $tpl);
        }

        // 0.1 Seed Default Wedding Workspace
        $workspace = Workspace::firstOrCreate(
            ['slug' => 'quoc-trung-hong-van'],
            [
                'name' => 'Đám Cưới Nguyễn Hoàng Quốc Trung & Lê Thị Hồng Vân',
                'groom_name' => 'Nguyễn Hoàng Quốc Trung',
                'bride_name' => 'Lê Thị Hồng Vân',
                'wedding_date' => '2026-10-24',
                'wedding_location' => 'TP. Hồ Chí Minh',
                'venue_name' => 'Trung Tâm Hội Nghị Asiana Plaza',
                'estimated_guests' => 200,
                'wedding_hashtag' => '#TrungVanWedding2026',
                'couple_story' => 'Hành trình 6 năm tình yêu từ mái trường đại học đến ngày chung đôi hạnh phúc.',
                'budget_cap' => 350000000.00,
                'currency' => 'VND',
            ]
        );

        WorkspaceMember::firstOrCreate(
            ['workspace_id' => $workspace->id, 'member_name' => 'Quốc Trung'],
            ['role' => 'groom']
        );

        WorkspaceMember::firstOrCreate(
            ['workspace_id' => $workspace->id, 'member_name' => 'Hồng Vân'],
            ['role' => 'bride']
        );

        WorkspaceInvitation::firstOrCreate(
            ['workspace_id' => $workspace->id],
            [
                'template_id' => 'romantic-pastel',
                'custom_title' => 'Lễ Thành Hôn Quốc Trung & Hồng Vân',
                'primary_color' => '#EC4899',
                'font_family' => 'Playfair Display',
                'groom_parents' => 'Ông N.V. Nam & Bà T.T. Mai',
                'bride_parents' => 'Ông L.V. Hùng & Bà P.T. Cúc',
                'event_time' => '11:30 Sáng',
                'google_maps_url' => 'https://maps.google.com/?q=Asiana+Plaza',
                'bank_name' => 'Vietcombank',
                'bank_account_number' => '1029384756',
                'bank_account_holder' => 'NGUYEN HOANG QUOC TRUNG',
                'enable_wax_seal' => true,
                'enable_qr_checkin' => true,
                'enable_gift_box' => true,
            ]
        );

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
        $guest = Guest::firstOrCreate(
            ['guest_slug' => 'anh-tuan-va-chi-lan'],
            [
                'workspace_id' => $workspace->id,
                'table_id' => $tableVip->id,
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
            ]
        );

        Guest::firstOrCreate(
            ['guest_slug' => 'gia-dinh-chu-sau'],
            [
                'workspace_id' => $workspace->id,
                'table_id' => $tableFamily->id,
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
            ]
        );

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
