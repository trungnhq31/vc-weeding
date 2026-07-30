<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { 
  Heart, 
  Sparkles, 
  Palette, 
  CheckCircle2, 
  ExternalLink,
  ArrowRight,
  Eye,
  Sliders,
  Music,
  QrCode,
  ShieldCheck
} from 'lucide-vue-next';

interface Post {
    id: string;
    title: string;
    slug: string;
    excerpt: string;
    reading_time_minutes: number;
    published_at: string;
}

defineProps<{
    latestPosts?: Post[];
}>();

const page = usePage();
const authUser = computed(() => (page.props as any).auth?.user);

const templates = [
  {
    id: 'romantic-pastel',
    name: 'Romantic Pastel',
    tagline: 'Ngọt ngào, Tinh tế & Thơ mộng',
    description: 'Tone màu hồng phấn kết hợp kem ấm pastel, hoa văn chìm cùng hiệu ứng phong bì dán tem sáp cổ điển.',
    primaryColor: '#EC4899',
    bgColor: 'bg-rose-50/70',
    borderColor: 'border-rose-200',
    badgeText: 'Phổ biến nhất',
    demoUrl: '/wedding?template=romantic-pastel',
    features: ['Tem sáp niêm phong animation', 'Lịch trình đám cưới đếm ngược', 'Hộp thư chúc phúc & RSVP', 'Nhạc nền lãng mạn']
  },
  {
    id: 'royal-gold',
    name: 'Royal Gold',
    tagline: 'Sang trọng, Quý phái & Hoàng gia',
    description: 'Tone màu vàng sâm panh thượng hạng `#D97706` kết hợp chi tiết hoa văn dát vàng nổi bật.',
    primaryColor: '#D97706',
    bgColor: 'bg-amber-50/70',
    borderColor: 'border-amber-200',
    badgeText: 'Sang trọng',
    demoUrl: '/wedding?template=royal-gold',
    features: ['Dát vàng 24K viền thiệp', 'QR Code Check-in độc bản', 'Bản đồ Google Maps chỉ đường', 'Album kỷ niệm HD']
  },
  {
    id: 'modern-slate',
    name: 'Modern Slate',
    tagline: 'Hiện đại, Tối giản & Thanh lịch',
    description: 'Phong cách Notion/Linear minimalist với tone ghi sáng thanh lịch, font chữ Sans-Serif sắc nét.',
    primaryColor: '#475569',
    bgColor: 'bg-slate-100/70',
    borderColor: 'border-slate-300',
    badgeText: 'Minimalist',
    demoUrl: '/wedding?template=modern-slate',
    features: ['Bố cục thẻ thông tin tối giản', 'Phím tắt Cmd+K trải nghiệm', 'Xác nhận tham dự 1-click', 'Tương thích 100% Mobile']
  },
  {
    id: 'botanical-sage',
    name: 'Botanical Sage',
    tagline: 'Tự nhiên, Tươi mát & Thảo mộc',
    description: 'Tone xanh lá xô thơm `#10B981` tươi mát, kết hợp họa tiết cành ô liu và lá thảo mộc trang nhã.',
    primaryColor: '#10B981',
    bgColor: 'bg-emerald-50/70',
    borderColor: 'border-emerald-200',
    badgeText: 'Thiên nhiên',
    demoUrl: '/wedding?template=botanical-sage',
    features: ['Họa tiết cành lá vẽ tay', 'Tích hợp tài khoản mừng cưới', 'Lời mời đích danh từng khách', 'Thiết lập mật khẩu nhóm']
  }
];
</script>

<template>
    <Head title="Kho Mẫu Thiệp Cưới Online & Catalog — Eloria OS" />

    <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans selection:bg-rose-100 selection:text-rose-900">
        <!-- Navigation Bar -->
        <header class="sticky top-0 z-50 bg-[#FAF8F5]/90 backdrop-blur-md border-b border-rose-100/80">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <!-- Brand Logo: Only Logo Image (Larger) + Eloria Text -->
                <Link href="/" class="flex items-center gap-3 group">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-12 md:h-14 w-auto rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
                    <span class="font-serif text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
                </Link>
                <!-- Navigation Links: 100% Short Vietnamese -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-700">
                    <Link href="/" class="hover:text-rose-700 transition-colors">Trang chủ</Link>
                    <Link href="/portfolio" class="text-rose-800 font-semibold border-b-2 border-rose-500 pb-0.5">Mẫu thiệp</Link>
                    <Link href="/wedding/timeline" class="hover:text-rose-700 transition-colors">Lập kế hoạch</Link>
                    <Link href="/wedding/vendors" class="hover:text-rose-700 transition-colors">Đối tác</Link>
                    <Link href="/blog" class="hover:text-rose-700 transition-colors">Cẩm nang</Link>
                </nav>
                <!-- Header Actions -->
                <div class="flex items-center gap-2 md:gap-3">
                    <template v-if="authUser">
                        <Link href="/wedding/timeline" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md flex items-center gap-1.5 hover:scale-105 active:scale-95">
                            Vào Workspace
                            <ArrowRight class="w-4 h-4 text-rose-400" />
                        </Link>
                    </template>
                    <template v-else>
                        <Link href="/login" class="px-3.5 py-2 rounded-xl text-slate-700 hover:text-rose-700 hover:bg-rose-50 font-semibold text-xs md:text-sm transition-all">
                            Đăng nhập
                        </Link>
                        <Link href="/register" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md shadow-slate-900/10 flex items-center gap-1.5 hover:scale-105 active:scale-95">
                            Đăng ký
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Portfolio Hero Header -->
        <section class="py-16 md:py-24 px-6 bg-gradient-to-b from-[#FFFDF9] via-[#FAF8F5] to-rose-50/40 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-100 border border-rose-200 text-rose-900 text-xs font-semibold uppercase tracking-wider mb-6">
                    <Sparkles class="w-4 h-4 text-rose-600" />
                    Bộ Collection Thiệp Cưới Điện Tử Độc Bản
                </div>
                <h1 class="text-4xl md:text-6xl font-serif font-extrabold text-slate-900 tracking-tight leading-tight">
                    Kho Mẫu Thiệp Mời Cưới <br />
                    <span class="bg-gradient-to-r from-rose-700 via-rose-600 to-amber-600 bg-clip-text text-transparent">Tươi Sáng & Pastel Tinh Tế</span>
                </h1>
                <p class="mt-4 text-base md:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Khám phá 4 phong cách thiệp cưới sang trọng, tùy biến màu sắc, âm nhạc và lời chúc dành riêng cho ngày trọng đại của Dâu Rể.
                </p>
            </div>
        </section>

        <!-- Template Catalog Grid Section -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid md:grid-cols-2 gap-8">
                <div v-for="item in templates" :key="item.id" class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider" :class="item.bgColor" :style="{ color: item.primaryColor }">
                                {{ item.badgeText }}
                            </span>
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                <Palette class="w-3.5 h-3.5" /> Tone Pastel
                            </div>
                        </div>

                        <!-- Card Header -->
                        <h3 class="text-2xl font-serif font-bold text-slate-900 mb-1 group-hover:text-rose-700 transition-colors">
                            {{ item.name }}
                        </h3>
                        <p class="text-xs font-semibold text-rose-800 mb-3">{{ item.tagline }}</p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ item.description }}</p>

                        <!-- Features Checklist -->
                        <div class="space-y-2 mb-8 pt-4 border-t border-slate-100">
                            <div v-for="(feat, idx) in item.features" :key="idx" class="flex items-center gap-2 text-xs text-slate-700">
                                <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0" />
                                <span>{{ feat }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a :href="item.demoUrl" class="px-5 py-2.5 rounded-xl bg-rose-50 text-rose-900 border border-rose-200 hover:bg-rose-100 font-semibold text-xs transition-all flex items-center gap-1.5">
                            <Eye class="w-4 h-4 text-rose-600" /> Xem Thiệp Demo Live
                        </a>
                        <Link href="/onboarding" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs transition-all flex items-center gap-1.5">
                            Áp dụng Mẫu Này
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Highlight Footer Banner -->
        <section class="max-w-7xl mx-auto px-6 pb-20">
            <div class="p-10 rounded-3xl bg-gradient-to-r from-rose-100/80 via-amber-50 to-rose-50 border border-rose-200 text-center">
                <h3 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">Bạn Muốn Thiết Kế Thiệp Độc Bản Theo Yêu Cầu?</h3>
                <p class="mt-2 text-sm text-slate-600 max-w-xl mx-auto">
                    Hệ điều hành Eloria hỗ trợ tùy chỉnh font chữ, phối màu Pastel theo tone trang trí tiệc cưới và xuất bản tức thì qua subdomain riêng.
                </p>
                <div class="mt-6 flex justify-center gap-4">
                    <Link href="/onboarding" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-semibold text-sm shadow-md hover:bg-slate-800 transition">
                        Bắt Đầu Ngay Dùng Thử
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
