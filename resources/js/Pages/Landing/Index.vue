<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { 
  Heart, 
  Sparkles, 
  CheckCircle2, 
  Calendar, 
  DollarSign, 
  Users, 
  Building2, 
  Bot, 
  ArrowRight, 
  Layers, 
  Palette, 
  ShieldCheck, 
  FileText,
  Clock,
  Star,
  Zap,
  Play,
  ChevronRight,
  Eye,
  Sliders
} from 'lucide-vue-next';
import ThreeWeddingAnimationCanvas from '@/Components/Wedding/ThreeWeddingAnimationCanvas.vue';

interface Template {
  id: string;
  name: string;
  slug: string;
  theme_color: string;
  description: string;
  thumbnail_url?: string;
}

interface Post {
  id: string;
  title: string;
  slug: string;
  excerpt: string;
  published_at: string;
}

defineProps<{
  templates?: Template[];
  latestPosts?: Post[];
}>();

const page = usePage();
const authUser = computed(() => (page.props as any).auth?.user);

const scrollProgress = ref(0);
const activeTab = ref('all');

const updateScrollProgress = () => {
  const scrollTop = window.scrollY;
  const docHeight = document.documentElement.scrollHeight - window.innerHeight;
  if (docHeight > 0) {
    scrollProgress.value = Math.min(1, Math.max(0, scrollTop / docHeight));
  }
};

onMounted(() => {
  window.addEventListener('scroll', updateScrollProgress);
});

onUnmounted(() => {
  window.removeEventListener('scroll', updateScrollProgress);
});

const defaultTemplates = [
  { id: 'romantic-pastel', name: 'Romantic Pastel', theme_color: '#EC4899', description: 'Hồng phấn kem ấm ngọt ngào, tem sáp niêm phong lãng mạn.', badge: 'Phổ biến nhất' },
  { id: 'royal-gold', name: 'Royal Gold', theme_color: '#D97706', description: 'Vàng sâm panh hoàng gia sang trọng, viền dát vàng 24K.', badge: 'Sang trọng' },
  { id: 'modern-slate', name: 'Modern Slate', theme_color: '#475569', description: 'Ghi sáng Notion/Linear minimalist thanh lịch sắc nét.', badge: 'Minimalist' },
  { id: 'botanical-sage', name: 'Botanical Sage', theme_color: '#10B981', description: 'Xanh lá thảo mộc tươi mát, họa tiết cành ô liu tinh tế.', badge: 'Thiên nhiên' },
];
</script>

<template>
  <Head title="Eloria Wedding OS — Hệ Điều Hành Lập Kế Hoạch Đám Cưới Thế Hệ Mới" />

  <div class="relative min-h-screen bg-[#FAF8F5] text-slate-900 font-sans selection:bg-rose-100 selection:text-rose-900 overflow-x-hidden">
    <!-- 3D Three.js WebGL Particle Background Canvas -->
    <ThreeWeddingAnimationCanvas :progress="scrollProgress" :canvasOpacity="0.4" />

    <!-- Floating Background Decorative Elements -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
      <div class="absolute -top-40 -left-40 w-96 h-96 bg-rose-200/40 rounded-full blur-3xl animate-pulse-slow"></div>
      <div class="absolute top-1/3 -right-40 w-96 h-96 bg-amber-200/40 rounded-full blur-3xl animate-pulse-slow delay-1000"></div>
      <div class="absolute bottom-10 left-1/4 w-80 h-80 bg-rose-300/30 rounded-full blur-3xl animate-pulse-slow delay-2000"></div>
    </div>

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-50 bg-[#FAF8F5]/90 backdrop-blur-md border-b border-rose-100/80 transition-all duration-300 shadow-2xs">
      <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Brand Logo: Only Logo Image (Larger) + Eloria Text -->
        <Link href="/" class="flex items-center gap-3 group">
          <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-12 md:h-14 w-auto rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
          <span class="font-serif text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
        </Link>

        <!-- Navigation Links: 100% Short Vietnamese -->
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-700">
          <a href="#features" class="hover:text-rose-700 transition-colors py-1">Tính năng</a>
          <a href="#templates" class="hover:text-rose-700 transition-colors py-1">Mẫu thiệp</a>
          <Link href="/wedding/timeline" class="hover:text-rose-700 transition-colors py-1">Lập kế hoạch</Link>
          <Link href="/wedding/vendors" class="hover:text-rose-700 transition-colors py-1">Đối tác</Link>
          <Link href="/blog" class="hover:text-rose-700 transition-colors py-1">Cẩm nang</Link>
        </nav>

        <!-- Header Actions: Conditional Login & Register vs Into Workspace -->
        <div class="flex items-center gap-2 md:gap-3">
          <template v-if="authUser">
            <Link
              href="/wedding/timeline"
              class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md shadow-slate-900/10 flex items-center gap-1.5 hover:scale-105 active:scale-95"
            >
              Vào Workspace
              <ArrowRight class="w-4 h-4 text-rose-400" />
            </Link>
          </template>
          <template v-else>
            <Link
              href="/login"
              class="px-4 py-2 rounded-xl text-slate-700 hover:text-rose-700 hover:bg-rose-50/80 font-semibold text-xs md:text-sm transition-all"
            >
              Đăng nhập
            </Link>
            <Link
              href="/register"
              class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md shadow-slate-900/10 flex items-center gap-1.5 hover:scale-105 active:scale-95"
            >
              Đăng ký
            </Link>
          </template>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-16 pb-24 md:pt-24 md:pb-32 px-6 bg-gradient-to-b from-[#FFFDF9] via-[#FAF8F5] to-rose-50/40 z-10">
      <div class="max-w-6xl mx-auto text-center relative z-20">
        <!-- Floating Animated Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-100/90 border border-rose-200 text-rose-900 text-xs font-semibold uppercase tracking-wider mb-8 shadow-xs animate-float">
          <Sparkles class="w-4 h-4 text-rose-600 animate-spin-slow" />
          Nền Tảng Operating System Cho Đám Cưới Thế Hệ Mới 2026
        </div>

        <!-- Headline with Gradient Animation -->
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-extrabold text-slate-900 tracking-tight leading-[1.15] max-w-5xl mx-auto">
          Nền Tảng Điều Phối & Lập Kế Hoạch Cưới <br class="hidden md:block" />
          <span class="bg-gradient-to-r from-rose-700 via-amber-600 to-rose-600 bg-clip-text text-transparent animate-gradient">
            Kết Nối Tất Cả Thành Viên Đám Cưới
          </span>
        </h1>

        <!-- Subtitle: Strategic Position -->
        <p class="mt-6 text-base md:text-xl text-slate-600 max-w-3.5xl mx-auto leading-relaxed font-sans">
          Eloria không chỉ lập kế hoạch đám cưới — Eloria giúp <strong class="text-slate-900 font-semibold">Cô Dâu, Chú Rể, Wedding Planner & Nhà Cung Cấp</strong> cùng phối hợp real-time: Quản lý công việc Kanban, hợp đồng & dòng tiền, sơ đồ bàn tiệc & thiệp mở sáp độc bản.
        </p>

        <!-- CTA Action Buttons -->
        <div class="mt-10 flex flex-wrap justify-center gap-4">
          <Link
            href="/onboarding"
            class="px-8 py-4 rounded-2xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-sm md:text-base shadow-xl shadow-slate-900/15 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2"
          >
            <Calendar class="w-5 h-5 text-rose-400" />
            Thiết Lập Đám Cưới Của Bạn
          </Link>
          <Link
            href="/wedding"
            class="px-8 py-4 rounded-2xl bg-white text-slate-800 border border-rose-200 hover:bg-rose-50/70 font-semibold text-sm md:text-base shadow-xs hover:shadow-md hover:scale-105 active:scale-95 transition-all flex items-center gap-2"
          >
            <Heart class="w-5 h-5 text-rose-500 fill-rose-500/20" />
            Xem Mẫu Thiệp Mời Online
          </Link>
        </div>

        <!-- Floating Interactive Mockup Card -->
        <div class="mt-16 max-w-4xl mx-auto p-4 md:p-6 rounded-3xl bg-white/80 border border-rose-200/90 shadow-2xl backdrop-blur-xl hover:shadow-rose-200/50 hover:-translate-y-1 transition-all duration-500 relative group">
          <div class="aspect-16/9 rounded-2xl bg-gradient-to-br from-[#FAF8F5] via-white to-rose-50 border border-rose-100 p-6 flex flex-col justify-between overflow-hidden relative">
            
            <!-- Mockup Header -->
            <div class="flex items-center justify-between border-b border-rose-100/80 pb-4">
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                <span class="text-xs font-mono font-medium text-slate-400 ml-2">wedding.eloria.app/van-anh-bich-tran</span>
              </div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-rose-800 bg-rose-100 px-3 py-1 rounded-full border border-rose-200">
                LIVE DEMO WORKSPACE
              </span>
            </div>

            <!-- Mockup Content Grid -->
            <div class="grid md:grid-cols-3 gap-4 my-auto text-left">
              <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                <span class="text-[10px] text-slate-400 font-semibold uppercase">Đồng Hồ Đếm Ngược</span>
                <div class="text-lg font-bold text-slate-900 mt-1 flex items-center gap-1">
                  <Clock class="w-4 h-4 text-rose-500 animate-spin-slow" /> 86 Ngày Nữa
                </div>
              </div>
              <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                <span class="text-[10px] text-slate-400 font-semibold uppercase">Đã Xác Nhận (RSVP)</span>
                <div class="text-lg font-bold text-emerald-600 mt-1 flex items-center gap-1">
                  <CheckCircle2 class="w-4 h-4 text-emerald-600" /> 182 / 200 Khách
                </div>
              </div>
              <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                <span class="text-[10px] text-slate-400 font-semibold uppercase">Ngân Sách Đã Chi</span>
                <div class="text-lg font-bold text-slate-900 mt-1 flex items-center gap-1">
                  <DollarSign class="w-4 h-4 text-amber-600" /> 165M / 250M
                </div>
              </div>
            </div>

            <!-- Mockup Footer -->
            <div class="pt-3 border-t border-rose-100 flex items-center justify-between text-xs text-slate-500">
              <span class="flex items-center gap-1"><Sparkles class="w-3.5 h-3.5 text-amber-500" /> Tự động cảnh báo nợ & vỡ ngân sách</span>
              <span class="text-rose-700 font-semibold group-hover:translate-x-1 transition-transform cursor-pointer">Khám phá Workspace →</span>
            </div>
          </div>
        </div>

        <!-- Metric Counter Bar -->
        <div class="mt-16 pt-8 border-t border-rose-200/60 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
          <div class="p-4 rounded-2xl bg-white/60 border border-rose-100 shadow-2xs hover:bg-white transition">
            <div class="text-3xl font-serif font-bold text-rose-900">5,000+</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Đám Cưới Được Lập Kế Hoạch</div>
          </div>
          <div class="p-4 rounded-2xl bg-white/60 border border-rose-100 shadow-2xs hover:bg-white transition">
            <div class="text-3xl font-serif font-bold text-amber-700">99.8%</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Khách Hàng Hài Lòng</div>
          </div>
          <div class="p-4 rounded-2xl bg-white/60 border border-rose-100 shadow-2xs hover:bg-white transition">
            <div class="text-3xl font-serif font-bold text-emerald-700">0%</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Rủi Ro Vỡ Ngân Sách</div>
          </div>
          <div class="p-4 rounded-2xl bg-white/60 border border-rose-100 shadow-2xs hover:bg-white transition">
            <div class="text-3xl font-serif font-bold text-rose-900">4.9 / 5</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Đánh Giá Từ Wedding Planner</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Core Features Matrix Section -->
    <section id="features" class="py-24 px-6 max-w-7xl mx-auto relative z-10">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-bold text-rose-600 uppercase tracking-widest bg-rose-100 px-3 py-1 rounded-full">WORKFLOW ENGINE SỐ 1</span>
        <h2 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mt-4">
          Giải Pháp Toàn Diện Cho Đám Cưới Hoàn Hảo
        </h2>
        <p class="text-slate-600 text-sm md:text-base mt-3">
          Tối ưu hóa thời gian, loại bỏ rủi ro vỡ ngân sách và lưu giữ kỷ niệm đẹp nhất cho ngày trọng đại.
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Feature 1: Thiệp Mời Online -->
        <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-xl hover:-translate-y-2 hover:border-rose-300 transition-all duration-300 flex flex-col justify-between group">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
              <Palette class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-serif font-bold text-slate-900 mb-2 group-hover:text-rose-700 transition-colors">Kho Thiệp Mời Online Live</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              4 Mẫu thiệp độc bản (*Romantic Pastel, Royal Gold, Modern Slate, Botanical Sage*). Live Preview linh hoạt, tùy biến nhạc nền, nhạc phong bì sáp & QR Check-in.
            </p>
          </div>
          <Link href="/portfolio" class="mt-6 text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
            Trải nghiệm Mẫu Thiệp →
          </Link>
        </div>

        <!-- Feature 2: Core Task & Timeline -->
        <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-xl hover:-translate-y-2 hover:border-rose-300 transition-all duration-300 flex flex-col justify-between group">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-6 transition-transform">
              <Calendar class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-serif font-bold text-slate-900 mb-2 group-hover:text-rose-700 transition-colors">Lộ Trình & Task Management</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              Quản lý tiến độ 5 mảng công việc cưới (*Venue, Attire, Media, Ceremony, Reception*). Theo dõi deadline, độ ưu tiên & lưu trữ ảnh kỷ niệm chứng từ.
            </p>
          </div>
          <Link href="/wedding/timeline" class="mt-6 text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
            Mở Lộ Trình Cưới →
          </Link>
        </div>

        <!-- Feature 3: Financial Budgeting -->
        <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-xl hover:-translate-y-2 hover:border-rose-300 transition-all duration-300 flex flex-col justify-between group">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
              <DollarSign class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-serif font-bold text-slate-900 mb-2 group-hover:text-rose-700 transition-colors">Quản Lý Ngân Sách Dòng Tiền</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              Tính toán tự động trần ngân sách vs chi phí thực tế. Quản lý tiền cọc, tự động cảnh báo vỡ ngân sách và danh sách nợ đến hạn trong 7 ngày.
            </p>
          </div>
          <Link href="/wedding/budget" class="mt-6 text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
            Xem Quản Lý Ngân Sách →
          </Link>
        </div>

        <!-- Feature 4: Guest & Seating Canvas -->
        <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-xl hover:-translate-y-2 hover:border-rose-300 transition-all duration-300 flex flex-col justify-between group">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-800 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-6 transition-transform">
              <Users class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-serif font-bold text-slate-900 mb-2 group-hover:text-rose-700 transition-colors">Khách Mời & Seating Canvas</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              Danh sách khách mời xác nhận RSVP thuần Việt. Sơ đồ bàn tiệc kéo thả, kiểm tra sức chứa bàn tiệc và cảnh báo quá tải tự động.
            </p>
          </div>
          <Link href="/wedding/guests" class="mt-6 text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
            Quản Lý Khách Mời →
          </Link>
        </div>

        <!-- Feature 5: Vendor CRM -->
        <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-xl hover:-translate-y-2 hover:border-rose-300 transition-all duration-300 flex flex-col justify-between group">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-sky-100 text-sky-800 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
              <Building2 class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-serif font-bold text-slate-900 mb-2 group-hover:text-rose-700 transition-colors">Vendor CRM & Hợp Đồng</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              Danh bạ studio, nhà hàng, trang điểm, hoa cưới. Theo dõi lịch giải ngân đợt 1/2/3 và lưu trữ chứng từ hợp đồng an toàn.
            </p>
          </div>
          <Link href="/wedding/vendors" class="mt-6 text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
            Mở Vendor CRM →
          </Link>
        </div>

        <!-- Feature 6: Grounded AI Assistant -->
        <div class="p-8 rounded-3xl bg-slate-900 text-white shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
          <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-rose-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
          <div>
            <div class="w-14 h-14 rounded-2xl bg-rose-500/20 text-rose-300 flex items-center justify-center mb-6 border border-rose-500/30 group-hover:scale-110 transition-transform">
              <Bot class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-serif font-bold text-white mb-2">Grounded AI Assistant</h3>
            <p class="text-slate-300 text-sm leading-relaxed">
              Trợ lý AI phân tích số liệu thực tế với phím tắt <kbd class="px-1.5 py-0.5 bg-slate-800 rounded font-mono text-xs text-rose-300 border border-slate-700">Cmd + K</kbd>. Cam kết Zero Hallucination, giải đáp dòng tiền & công việc quá hạn.
            </p>
          </div>
          <Link href="/wedding/vendors" class="mt-6 text-xs font-bold text-rose-400 hover:text-rose-300 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
            Trải nghiệm Grounded AI →
          </Link>
        </div>
      </div>
    </section>

    <!-- Templates Collection Showcase Section -->
    <section id="templates" class="py-24 px-6 bg-white border-y border-rose-100 relative z-10">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
          <div>
            <span class="text-xs font-bold text-rose-600 uppercase tracking-widest bg-rose-100 px-3 py-1 rounded-full">BỘ COLLECTION THIỆP CƯỚI</span>
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mt-3">
              4 Mẫu Thiệp Mời Độc Bản Được Ưa Chuộng Nhất
            </h2>
          </div>
          <Link href="/portfolio" class="mt-4 md:mt-0 text-sm font-semibold text-rose-700 hover:text-rose-900 flex items-center gap-1 group">
            Xem tất cả thiệp mời online <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </Link>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
          <div 
            v-for="template in (templates && templates.length > 0 ? templates : defaultTemplates)" 
            :key="template.id" 
            class="p-6 rounded-3xl bg-[#FAF8F5] border border-slate-200/80 flex flex-col justify-between hover:border-rose-300 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 group"
          >
            <div>
              <div class="w-full aspect-4/3 rounded-2xl bg-white border border-slate-200/80 mb-4 flex items-center justify-center p-4 text-center shadow-2xs group-hover:scale-102 transition-transform">
                <span class="font-serif text-lg font-bold" :style="{ color: template.theme_color || '#881337' }">
                  {{ template.name }}
                </span>
              </div>
              <span v-if="template.badge" class="text-[10px] font-bold uppercase tracking-wider text-rose-800 bg-rose-100 px-2 py-0.5 rounded-full">
                {{ template.badge }}
              </span>
              <h4 class="font-serif font-bold text-slate-900 text-lg mt-2 mb-1 group-hover:text-rose-700 transition-colors">{{ template.name }}</h4>
              <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed">{{ template.description }}</p>
            </div>
            <Link href="/wedding" class="mt-4 text-xs font-bold text-slate-800 group-hover:text-rose-700 flex items-center gap-1">
              Xem Thiệp Live →
            </Link>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Onboarding CTA Footer Banner -->
    <section class="max-w-7xl mx-auto px-6 py-20 relative z-10">
      <div class="p-10 md:p-16 rounded-3xl bg-gradient-to-r from-rose-100/90 via-amber-50 to-rose-50 border border-rose-200 text-center relative overflow-hidden shadow-xl">
        <div class="relative z-10 max-w-2xl mx-auto">
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-rose-800 text-xs font-bold shadow-2xs mb-4">
            <Heart class="w-3.5 h-3.5 text-rose-600 fill-rose-600" /> Bắt Đầu Hoàn Toàn Miễn Phí
          </div>
          <h3 class="text-3xl md:text-4xl font-serif font-bold text-slate-900">Sẵn Sàng Cho Đám Cưới Mơ Ước?</h3>
          <p class="mt-3 text-sm md:text-base text-slate-600 leading-relaxed">
            Khởi tạo Workspace Đám cưới chỉ trong 2 phút với luồng Onboarding Wizard đơn giản & trải nghiệm giao diện Pastel tinh tế.
          </p>
          <div class="mt-8 flex flex-wrap justify-center gap-4">
            <Link href="/register" class="px-8 py-4 rounded-2xl bg-slate-900 text-white font-bold text-sm shadow-xl hover:bg-slate-800 hover:scale-105 active:scale-95 transition-all">
              Đăng Ký Tài Khoản Dâu Rể
            </Link>
            <Link href="/onboarding" class="px-8 py-4 rounded-2xl bg-white text-slate-800 border border-rose-200 font-bold text-sm shadow-2xs hover:bg-rose-50 hover:scale-105 active:scale-95 transition-all">
              Thiết Lập Kế Hoạch 4 Bước
            </Link>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 px-6 border-t border-slate-800 relative z-10">
      <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
        <div>
          <div class="flex items-center gap-2 text-white font-serif font-bold text-xl mb-4">
            <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-8 w-auto rounded-lg" />
            <span>Eloria Wedding OS</span>
          </div>
          <p class="text-xs text-slate-400 leading-relaxed">
            Hệ điều hành lập kế hoạch cưới chuyên nghiệp dành cho Cô dâu, Chú rể và Wedding Planner Việt Nam.
          </p>
        </div>

        <div>
          <h5 class="text-xs font-semibold uppercase tracking-wider text-slate-200 mb-4">Sản Phẩm</h5>
          <ul class="space-y-2 text-xs">
            <li><Link href="/wedding" class="hover:text-white transition">Thiệp Cưới Online</Link></li>
            <li><Link href="/wedding/timeline" class="hover:text-white transition">Planning Workspace</Link></li>
            <li><Link href="/wedding/budget" class="hover:text-white transition">Quản Lý Ngân Sách</Link></li>
            <li><Link href="/wedding/guests" class="hover:text-white transition">Seating Planner Canvas</Link></li>
          </ul>
        </div>

        <div>
          <h5 class="text-xs font-semibold uppercase tracking-wider text-slate-200 mb-4">Tài Nguyên</h5>
          <ul class="space-y-2 text-xs">
            <li><Link href="/portfolio" class="hover:text-white transition">Kho Mẫu Thiệp</Link></li>
            <li><Link href="/blog" class="hover:text-white transition">Blog Cẩm Nang Cưới</Link></li>
            <li><Link href="/sitemap.xml" class="hover:text-white transition">XML Sitemap</Link></li>
          </ul>
        </div>

        <div>
          <h5 class="text-xs font-semibold uppercase tracking-wider text-slate-200 mb-4">Bản Quyền</h5>
          <p class="text-xs text-slate-400">
            © 2026 Eloria Wedding OS. All rights reserved. Xây dựng với Laravel 13, Vue 3 & Inertia.js.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
