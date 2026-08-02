<script setup lang="ts">
import { ref, computed } from 'vue';
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
  ShieldCheck,
  Smartphone,
  ChevronLeft,
  ChevronRight,
  X
} from 'lucide-vue-next';
import IphoneTemplatePreviewFrame from '@/Components/Wedding/IphoneTemplatePreviewFrame.vue';

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

const activeCategory = ref('all');
const selectedPreviewTemplate = ref<typeof templates[0] | null>(null);
const currentPreviewIndex = ref(0);

const categories = [
  { id: 'all', label: 'Tất Cả (10)' },
  { id: 'pastel', label: 'Mở Sáp Nến' },
  { id: 'royal', label: 'Hoàng Gia' },
  { id: 'editorial', label: 'Tạp Chí & Press' },
  { id: 'botanical', label: 'Vòm Hoa Lá' },
  { id: 'traditional', label: 'Song Hỷ' },
  { id: 'ticket', label: 'Vé Máy Bay' },
  { id: 'emerald', label: 'Thẻ Kính' },
];

const templates = [
  {
    id: 'romantic-pastel',
    name: 'White Rose (Hồng Trắng)',
    tagline: 'Ngọt Ngào, Tinh Tế & Thơ Mộng',
    description: 'Phong cách Hồng Phấn & Kem ấm mở dấu sáp nến Wax Seal lãng mạn kèm phong bì dán tem cổ điển.',
    primaryColor: '#EC4899',
    bgColor: 'bg-rose-50/70',
    badgeText: '+ PREMIUM',
    isNew: true,
    coverImage: '/images/hero/luxury_wedding_hero.png',
    demoUrl: '/invitations/romantic-pastel',
    tags: ['Hoa hồng', 'Mở sáp nến', 'Ngọt ngào'],
    features: ['Mở dấu sáp nến Wax Seal 3D', 'Lịch đếm ngược thời gian đám cưới', 'Khung ảnh thiệp mờ lãng mạn', 'Nhạc nền Piano du dương']
  },
  {
    id: 'royal-gold',
    name: 'Royal Gold (Hoàng Gia)',
    tagline: 'Sang Trọng, Quý Phái & Hoàng Gia',
    description: 'Khung viền vàng dát lá & Biểu tượng Monogram Crest T&V hoàng gia quý phái với font Serif uy nghi.',
    primaryColor: '#D97706',
    bgColor: 'bg-amber-50/70',
    badgeText: '+ PREMIUM',
    isNew: true,
    coverImage: '/images/home/luxury_planning_banner.png',
    demoUrl: '/invitations/royal-gold',
    tags: ['Dát vàng', 'Hoàng gia', 'Monogram'],
    features: ['Huy hiệu Monogram T&V dát vàng', 'Khung viền đôi lá vàng hoàng gia', 'QR Code Check-in VIP tại cổng', 'Hộp mừng cưới dát vàng độc bản']
  },
  {
    id: 'modern-slate',
    name: 'Maison Editorial (Cao cấp)',
    tagline: 'Hiện Đại, Tạp Chí & Bất Đối Xứng',
    description: 'Phong cách tạp chí thời trang Notion/Linear 2 cột bất đối xứng với thanh điều hướng sticky bên trái.',
    primaryColor: '#475569',
    bgColor: 'bg-slate-100/70',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/home/luxury_invitation_banner.png',
    demoUrl: '/invitations/modern-slate',
    tags: ['Hiện đại', 'Tạp chí', 'Minimalist'],
    features: ['Layout tạp chí 2 cột Linear/Notion', 'Thanh điều hướng Sticky bên trái', 'Phím tắt Cmd + K tra cứu nhanh', 'Form RSVP tối giản sắc nét']
  },
  {
    id: 'botanical-sage',
    name: 'Olive Editorial (Tinh tế)',
    tagline: 'Tự Nhiên, Tươi Mát & Vòm Hoa Lá',
    description: 'Tone xanh lá xô thơm tươi mát, kết hợp khung ảnh vòm cong mềm mại và họa tiết cành lá thiên nhiên.',
    primaryColor: '#10B981',
    bgColor: 'bg-emerald-50/70',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/home/luxury_ballroom_banner.png',
    demoUrl: '/invitations/botanical-sage',
    tags: ['Xanh lá', 'Thiên nhiên', 'Vòm hoa'],
    features: ['Khung ảnh vòm cong mềm mại', 'Dòng chảy lịch trình hoa lá', 'Trang cá nhân Dâu Rể tự nhiên', 'Lời mời đích danh từng khách']
  },
  {
    id: 'indochine-traditional',
    name: 'Indochine Red (Song Hỷ)',
    tagline: 'Truyền Thống, Son Thắm & Đỏ Nhung',
    description: 'Họa tiết lưới gỗ Đông Dương kết hợp biểu tượng Song Hỷ (囍) dát vàng nổi bật trên nền đỏ nhung kiêu sa.',
    primaryColor: '#991B1B',
    bgColor: 'bg-red-50/70',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/home/luxury_vendor_banner.png',
    demoUrl: '/invitations/indochine-traditional',
    tags: ['Truyền thống', 'Song hỷ', 'Đỏ nhung'],
    features: ['Biểu tượng Song Hỷ dát vàng nổi', 'Khung viền lưới gỗ Đông Dương', 'Nghi lễ Ăn Hỏi & Thành Hôn', 'Phong bì lì xì mừng cưới đỏ nhung']
  },
  {
    id: 'celestial-blue',
    name: 'Ocean Breeze (Vé Máy Bay)',
    tagline: 'Đột Phá, Vé Máy Bay & Biển Xanh',
    description: 'Thiết kế Vé máy bay chuyến bay tình yêu xé góc độc đáo, tích hợp mã QR Code check-in sân bay.',
    primaryColor: '#0284C7',
    bgColor: 'bg-sky-50/70',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/home/luxury_invitation_banner.png',
    demoUrl: '/invitations/celestial-blue',
    tags: ['Boarding pass', 'Biển xanh', 'Đột phá'],
    features: ['Cuống vé máy bay xé góc độc đáo', 'Mã QR Check-in cổng Asiana VIP', 'Thông tin chuyến bay WED-2026', 'Thư xác nhận RSVP Boarding Pass']
  },
  {
    id: 'emerald-luxe',
    name: 'Imperial Emerald (Thẻ Kính)',
    tagline: 'Quyền Quý, Xanh Ngọc Bảo & Thẻ Kính',
    description: 'Tone xanh ngọc bảo hoàng gia kết hợp thẻ kính mờ (backdrop blur) và vòng đếm ngược kim tuyến lấp lánh.',
    primaryColor: '#059669',
    bgColor: 'bg-emerald-950/20',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/home/luxury_planning_banner.png',
    demoUrl: '/invitations/emerald-luxe',
    tags: ['Ngọc bảo', 'Kính mờ', 'Quý phái'],
    features: ['Thẻ kính mờ Frosted Glassmorphism', 'Vòng đếm ngược kim tuyến lấp lánh', 'Huy hiệu hoàng gia ngọc bảo VIP', 'Két quà điện tử ngọc bảo']
  },
  {
    id: 'sunset-coral',
    name: 'Tropical Sunset (San Hô)',
    tagline: 'Bình Minh, Cam San Hô & Split View',
    description: 'Bố cục 50/50 chia đôi màn hình độc đáo (bên trái ảnh chú rể cô dâu, bên phải nội dung thư mời cam san hô).',
    primaryColor: '#EA580C',
    bgColor: 'bg-orange-50/70',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/hero/luxury_wedding_hero.png',
    demoUrl: '/invitations/sunset-coral',
    tags: ['San hô', 'Split view', 'Bình minh'],
    features: ['Bố cục 50/50 chia đôi màn hình', 'Sticky Banner ảnh đôi trải dài', 'Tone màu cam san hô ấm áp', 'Tương thích tối ưu điện thoại']
  },
  {
    id: 'crimson-velvet',
    name: 'Wedding Gazette (Báo In)',
    tagline: 'Báo In, Tin Tức Cổ Điển & Retro Press',
    description: 'Trình bày dạng tờ báo in tin tức tiệc cưới cổ điển 3 cột "THE WEDDING GAZETTE - SPECIAL EDITION".',
    primaryColor: '#1C1917',
    bgColor: 'bg-stone-100/80',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/home/luxury_ballroom_banner.png',
    demoUrl: '/invitations/crimson-velvet',
    tags: ['Báo in', 'Retro press', 'Vintage'],
    features: ['Layout tờ báo in Retro 3 cột', 'Tiêu đề tin tức đám cưới giật gân', 'Con dấu Press Stamp đăng ký RSVP', 'Giấy in kraft hoài niệm cổ điển']
  },
  {
    id: 'vintage-sepia',
    name: 'Fairytale Journal (Trang Sách)',
    tagline: 'Trang Sách, Phân Chương & Thần Tiên',
    description: 'Cuốn sách câu chuyện tình yêu phân chương tab (Chương I - IV) cho phép lật mở từng trang ký niệm.',
    primaryColor: '#92400E',
    bgColor: 'bg-amber-100/60',
    badgeText: '+ PREMIUM',
    isNew: false,
    coverImage: '/images/home/luxury_vendor_banner.png',
    demoUrl: '/invitations/vintage-sepia',
    tags: ['Trang sách', 'Thần tiên', 'Cổ điển'],
    features: ['Thẻ Bookmark chuyển chương I - IV', 'Nền bìa sách thần tiên hoài niệm', 'Trang lưu bút viết tiếp câu chuyện', 'Tiếng lật trang sách kèm nhạc cổ điển']
  }
];

const filteredTemplates = computed(() => {
  if (activeCategory.value === 'all') return templates;
  return templates.filter(t => {
    if (activeCategory.value === 'pastel') return t.id === 'romantic-pastel';
    if (activeCategory.value === 'royal') return t.id === 'royal-gold';
    if (activeCategory.value === 'editorial') return t.id === 'modern-slate' || t.id === 'crimson-velvet';
    if (activeCategory.value === 'botanical') return t.id === 'botanical-sage';
    if (activeCategory.value === 'traditional') return t.id === 'indochine-traditional';
    if (activeCategory.value === 'ticket') return t.id === 'celestial-blue';
    if (activeCategory.value === 'emerald') return t.id === 'emerald-luxe';
    return true;
  });
});

const openPreviewModal = (template: typeof templates[0]) => {
  selectedPreviewTemplate.value = template;
  currentPreviewIndex.value = filteredTemplates.value.findIndex(t => t.id === template.id);
};

const closePreviewModal = () => {
  selectedPreviewTemplate.value = null;
};

const prevPreview = () => {
  if (currentPreviewIndex.value > 0) {
    currentPreviewIndex.value--;
  } else {
    currentPreviewIndex.value = filteredTemplates.value.length - 1;
  }
  selectedPreviewTemplate.value = filteredTemplates.value[currentPreviewIndex.value];
};

const nextPreview = () => {
  if (currentPreviewIndex.value < filteredTemplates.value.length - 1) {
    currentPreviewIndex.value++;
  } else {
    currentPreviewIndex.value = 0;
  }
  selectedPreviewTemplate.value = filteredTemplates.value[currentPreviewIndex.value];
};
</script>

<template>
    <Head title="Kho Mẫu Thiệp Cưới Online & Catalog — Eloria OS" />

    <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans selection:bg-rose-100 selection:text-rose-900">
        <!-- Navigation Bar -->
        <header class="sticky top-0 z-40 bg-[#FAF8F5]/90 backdrop-blur-md border-b border-rose-100/80 shadow-2xs">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <!-- Brand Logo: Only Logo Image (Larger) + Eloria Text -->
                <Link href="/" class="flex items-center gap-3 group">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-12 md:h-14 w-auto rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
                    <span class="font-serif text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
                </Link>

                <!-- Navigation Links -->
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

        <!-- Hero Header -->
        <section class="py-12 md:py-16 px-6 bg-gradient-to-b from-[#FFFDF9] via-[#FAF8F5] to-rose-50/40 text-center">
            <div class="max-w-4xl mx-auto space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100 border border-rose-200 text-rose-900 text-xs font-bold uppercase tracking-wider">
                    <Sparkles class="w-4 h-4 text-rose-600" />
                    KHO MẪU THIỆP CƯỚI ĐIỆN TỬ 2026
                </div>
                <h1 class="text-3xl md:text-5xl font-serif font-extrabold text-slate-900 tracking-tight leading-tight">
                    Chọn Mẫu Thiệp Đám Cưới Mơ Ước
                </h1>
                <p class="text-sm md:text-base text-slate-600 max-w-xl mx-auto leading-relaxed">
                    Click vào bất kỳ mẫu thiệp nào để **Xem trước giao diện iPhone 3D** mượt mà cùng toàn bộ tính năng tự động xác nhận RSVP & Nhạc nền.
                </p>
            </div>
        </section>

        <!-- Category Filter Tabs -->
        <section class="max-w-7xl mx-auto px-6 pb-6">
            <div class="flex items-center justify-center flex-wrap gap-2 md:gap-3">
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="activeCategory = cat.id"
                    class="px-4 py-2 rounded-xl text-xs md:text-sm font-bold transition-all duration-300 cursor-pointer flex items-center gap-1.5"
                    :class="activeCategory === cat.id 
                        ? 'bg-slate-900 text-white shadow-md scale-105' 
                        : 'bg-white text-slate-700 hover:bg-rose-50 border border-slate-200/80 hover:border-rose-300'"
                >
                    {{ cat.label }}
                </button>
            </div>
        </section>

        <!-- 4-Column Template Catalog Grid (Clean Photo Cards) -->
        <section class="max-w-7xl mx-auto px-6 pb-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                <div 
                    v-for="item in filteredTemplates" 
                    :key="item.id" 
                    @click="openPreviewModal(item)"
                    class="bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-2xl transition-all duration-500 overflow-hidden cursor-pointer group flex flex-col justify-between"
                >
                    <!-- 1. Dedicated Header Bar Above Preview Box (Zero Badge Overlap!) -->
                    <div class="px-4 py-3 bg-white border-b border-slate-100 flex items-center justify-between z-20">
                        <div class="flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 text-white font-extrabold text-[10px] tracking-wider uppercase shadow-2xs">
                                {{ item.badgeText }}
                            </span>
                            <span v-if="item.isNew" class="px-2.5 py-1 rounded-lg bg-gradient-to-r from-rose-500 to-pink-600 text-white font-extrabold text-[10px] tracking-wider uppercase shadow-2xs">
                                NEW
                            </span>
                        </div>
                        <button 
                            @click.stop="openPreviewModal(item)"
                            class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-extrabold text-[11px] flex items-center gap-1 transition-all cursor-pointer border border-rose-200/60"
                        >
                            <Eye class="w-3.5 h-3.5 text-rose-600" /> Xem 3D
                        </button>
                    </div>

                    <!-- 2. Visual Phone Frame Backdrop Box -->
                    <div 
                        class="relative h-[340px] w-full overflow-hidden transition-colors duration-500 flex items-center justify-center p-4"
                        :class="item.bgColor"
                    >
                        <!-- iPhone Mockup Frame Centered Proudly -->
                        <div class="relative z-10 drop-shadow-xl transform group-hover:scale-[1.03] transition-transform duration-500">
                            <IphoneTemplatePreviewFrame 
                                :demoUrl="item.demoUrl" 
                                :templateName="item.name" 
                                :themeColor="item.primaryColor"
                            />
                        </div>
                    </div>

                    <!-- Card Title, Style Tag & Feature Bullet Points Footer -->
                    <div class="p-4 bg-white space-y-3 border-t border-slate-100 flex-grow flex flex-col justify-between">
                        <div>
                            <h4 class="font-serif font-bold text-slate-900 text-base group-hover:text-rose-700 transition-colors leading-snug">
                                {{ item.name }}
                            </h4>
                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                <span 
                                    v-for="(tag, tIdx) in item.tags" 
                                    :key="tIdx" 
                                    class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-medium"
                                >
                                    {{ tag }}
                                </span>
                            </div>
                        </div>

                        <!-- Feature Bullet Points -->
                        <div class="space-y-1.5 pt-2.5 border-t border-slate-100 text-xs text-slate-600 font-medium">
                            <div v-for="(feat, fIdx) in item.features" :key="fIdx" class="flex items-center gap-1.5">
                                <CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
                                <span class="truncate">{{ feat }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Interactive iPhone Preview Modal -->
        <Teleport to="body">
            <div 
                v-if="selectedPreviewTemplate" 
                class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4 md:p-8 overflow-y-auto animate-fade-in"
            >
                <!-- Close Button Top Right -->
                <button 
                    @click="closePreviewModal" 
                    class="fixed top-6 right-6 z-50 w-11 h-11 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all cursor-pointer border border-white/20 shadow-lg"
                >
                    <X class="w-6 h-6" />
                </button>

                <!-- Navigation Prev Arrow Button -->
                <button 
                    @click="prevPreview" 
                    class="hidden md:flex fixed left-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 rounded-full bg-white/10 text-white items-center justify-center hover:bg-white/20 transition-all cursor-pointer border border-white/20 shadow-lg"
                >
                    <ChevronLeft class="w-6 h-6" />
                </button>

                <!-- Navigation Next Arrow Button -->
                <button 
                    @click="nextPreview" 
                    class="hidden md:flex fixed right-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 rounded-full bg-white/10 text-white items-center justify-center hover:bg-white/20 transition-all cursor-pointer border border-white/20 shadow-lg"
                >
                    <ChevronRight class="w-6 h-6" />
                </button>

                <!-- Modal Inner Grid Layout -->
                <div class="max-w-5xl w-full grid md:grid-cols-12 gap-8 md:gap-12 items-center relative z-40 my-auto">
                    
                    <!-- Left Side: Template Meta Information & CTAs -->
                    <div class="md:col-span-6 space-y-6 text-white text-left">
                        
                        <!-- Tag Pill -->
                        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-extrabold uppercase tracking-widest">
                            ✦ XEM TRƯỚC MẪU
                        </div>

                        <!-- Title & Subtitle -->
                        <div>
                            <h2 class="text-3xl md:text-5xl font-serif font-extrabold text-white tracking-tight leading-tight">
                                {{ selectedPreviewTemplate.name }}
                            </h2>
                            <p class="text-rose-300 text-sm font-semibold mt-2">{{ selectedPreviewTemplate.tagline }}</p>
                        </div>

                        <!-- Style Badges Row -->
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-lg bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase">
                                {{ selectedPreviewTemplate.badgeText }}
                            </span>
                            <span 
                                v-for="(tag, idx) in selectedPreviewTemplate.tags" 
                                :key="idx" 
                                class="px-3 py-1 rounded-lg bg-slate-800 border border-slate-700 text-slate-300 text-xs font-medium"
                            >
                                {{ tag }}
                            </span>
                        </div>

                        <!-- 4 Feature Cards Grid -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div 
                                v-for="(feat, fIdx) in selectedPreviewTemplate.features" 
                                :key="fIdx" 
                                class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 text-xs text-slate-200 font-medium flex items-center gap-2 shadow-inner"
                            >
                                <span>{{ feat }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 flex flex-wrap items-center gap-4">
                            <Link 
                                href="/onboarding" 
                                class="px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-slate-950 font-extrabold text-sm shadow-xl shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all uppercase tracking-wider flex items-center gap-2 cursor-pointer"
                            >
                                DÙNG MẪU NÀY NGAY
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                            <button 
                                @click="closePreviewModal" 
                                class="px-6 py-4 rounded-2xl bg-slate-900 border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-800 font-bold text-sm transition-all uppercase tracking-wider cursor-pointer"
                            >
                                ĐÓNG XEM TRƯỚC
                            </button>
                        </div>
                    </div>

                    <!-- Right Side: iPhone 16 Pro Mockup Frame with Live Iframe -->
                    <div class="md:col-span-6 flex justify-center items-center relative">
                        <div class="drop-shadow-[0_25px_60px_rgba(0,0,0,0.6)]">
                            <IphoneTemplatePreviewFrame 
                                :demoUrl="selectedPreviewTemplate.demoUrl" 
                                :templateName="selectedPreviewTemplate.name" 
                                :themeColor="selectedPreviewTemplate.primaryColor"
                            />
                        </div>
                    </div>

                </div>
            </div>
        </Teleport>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 py-12 px-6 border-t border-slate-800">
            <div class="max-w-7xl mx-auto text-center text-xs space-y-2">
                <p class="font-serif font-bold text-base text-white">Eloria Wedding OS</p>
                <p>© 2026 Eloria. Hệ điều hành lập kế hoạch cưới chuyên nghiệp.</p>
            </div>
        </footer>
    </div>
</template>
