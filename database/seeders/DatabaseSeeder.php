<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Enums\RsvpStatus;
use App\Models\Category;
use App\Models\Guest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Wish;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Sample Guests
        $guest = Guest::create([
            'guest_slug' => 'anh-tuan-va-chi-lan',
            'name' => 'Anh Tuấn & Chị Lan',
            'salutation' => 'Trân trọng kính mời Anh Tuấn & Chị Lan',
            'group' => 'Đồng nghiệp',
            'estimated_count' => 2,
            'confirmed_count' => 2,
            'dietary_preference' => 'Món mặn chuẩn set menu',
            'rsvp_status' => RsvpStatus::Attending,
            'notes' => 'Sẽ đến đúng giờ dự tiệc',
        ]);

        Guest::create([
            'guest_slug' => 'gia-dinh-chu-sau',
            'name' => 'Gia đình Chú Sáu',
            'salutation' => 'Kính mời Chú Sáu và Gia đình',
            'group' => 'Họ hàng',
            'estimated_count' => 4,
            'confirmed_count' => 0,
            'dietary_preference' => null,
            'rsvp_status' => RsvpStatus::Pending,
            'notes' => null,
        ]);

        // 2. Seed Sample Wishes
        Wish::create([
            'guest_id' => $guest->id,
            'sender_name' => 'Anh Tuấn & Chị Lan',
            'message' => 'Chúc hai bạn trăm năm hạnh phúc, sớm đón quý tử nhé!',
            'is_approved' => true,
            'is_pinned' => true,
        ]);

        Wish::create([
            'guest_id' => null,
            'sender_name' => 'Bạn thân cấp 3',
            'message' => 'Mừng ngày vui của hai bạn! Chúc một đời an yên, cùng nhau vượt qua mọi sóng gió.',
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
