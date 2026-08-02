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
  Sliders,
  Camera,
  MapPin,
  Music,
  QrCode,
  Gift
} from 'lucide-vue-next';
import ThreeWeddingAnimationCanvas from '@/Components/Wedding/ThreeWeddingAnimationCanvas.vue';
import WeddingScrollStorySection from '@/Components/Wedding/WeddingScrollStorySection.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

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
const tiltX = ref(0);
const tiltY = ref(0);

const handleMouseMove = (e: MouseEvent) => {
  const { clientX, clientY } = e;
  const { innerWidth, innerHeight } = window;
  tiltX.value = ((clientY / innerHeight) - 0.5) * -5;
  tiltY.value = ((clientX / innerWidth) - 0.5) * 5;
};

const updateScrollProgress = () => {
  const scrollTop = window.scrollY;
  const docHeight = document.documentElement.scrollHeight - window.innerHeight;
  if (docHeight > 0) {
    scrollProgress.value = Math.min(1, Math.max(0, scrollTop / docHeight));
  }
};

onMounted(() => {
  window.addEventListener('scroll', updateScrollProgress);
  window.addEventListener('mousemove', handleMouseMove);

  // GSAP Entrance Animations
  const heroTl = gsap.timeline({ defaults: { ease: 'power3.out', duration: 0.8 } });
  heroTl
    .from('.gsap-hero-badge', { opacity: 0, y: -20, scale: 0.9 })
    .from('.gsap-hero-title', { opacity: 0, y: 30 }, '-=0.5')
    .from('.gsap-hero-desc', { opacity: 0, y: 20 }, '-=0.5')
    .from('.gsap-hero-cta > a', { opacity: 0, y: 20, stagger: 0.15 }, '-=0.4')
    .from('.gsap-hero-stats', { opacity: 0, y: 25 }, '-=0.3')
    .from('.gsap-hero-3d', { opacity: 0, scale: 0.95, y: 30 }, '-=0.8');

  // Continuous subtle float on Hero image
  gsap.to('.gsap-hero-3d', {
    y: -6,
    duration: 4,
    repeat: -1,
    yoyo: true,
    ease: 'sine.easeInOut'
  });

  // Section reveals
  gsap.from('.gsap-metric-card', {
    opacity: 0,
    y: 35,
    stagger: 0.12,
    duration: 0.8,
    ease: 'power2.out',
    scrollTrigger: {
      trigger: '.gsap-metric-section',
      start: 'top 85%'
    }
  });

  gsap.from('.gsap-feature-card', {
    opacity: 0,
    y: 50,
    scale: 0.96,
    stagger: 0.15,
    duration: 0.8,
    ease: 'power3.out',
    scrollTrigger: {
      trigger: '#features',
      start: 'top 80%'
    }
  });

  gsap.from('.gsap-template-card', {
    opacity: 0,
    y: 40,
    stagger: 0.12,
    duration: 0.8,
    ease: 'power2.out',
    scrollTrigger: {
      trigger: '#templates',
      start: 'top 80%'
    }
  });
});

onUnmounted(() => {
  window.removeEventListener('scroll', updateScrollProgress);
  window.removeEventListener('mousemove', handleMouseMove);
});

const defaultTemplates = [
  { id: 'romantic-pastel', name: 'Romantic Pastel & Wax Seal', theme_color: '#EC4899', description: 'Hồng phấn kem ấm ngọt ngào, tem sáp niêm phong lãng mạn.', badge: 'Mở Sáp Nến', demoUrl: '/invitations/romantic-pastel' },
  { id: 'royal-gold', name: 'Royal Gold & Monogram Crest', theme_color: '#D97706', description: 'Vàng sâm panh hoàng gia sang trọng, viền dát vàng 24K.', badge: 'Hoàng Gia', demoUrl: '/invitations/royal-gold' },
  { id: 'modern-slate', name: 'Modern Editorial Magazine', theme_color: '#475569', description: 'Ghi sáng Notion/Linear minimalist tạp chí sắc nét.', badge: 'Tạp Chí 2 Cột', demoUrl: '/invitations/modern-slate' },
  { id: 'botanical-sage', name: 'Botanical Garden & Arch Cards', theme_color: '#10B981', description: 'Xanh lá thảo mộc tươi mát, họa tiết khung vòm hoa lá.', badge: 'Khung Ảnh Vòm', demoUrl: '/invitations/botanical-sage' },
];
</script>

<template>
  <Head title="Eloria Wedding OS — Hệ Điều Hành Lập Kế Hoạch Đám Cưới Thế Hệ Mới" />

  <div class="relative min-h-screen bg-[#FAF8F5] text-slate-900 font-sans selection:bg-rose-100 selection:text-rose-900 overflow-x-hidden">
    <!-- 3D Three.js WebGL Particle Background Canvas -->
    <ThreeWeddingAnimationCanvas :progress="scrollProgress" :canvasOpacity="0.25" />

    <!-- Unified Above-the-Fold Viewport (Header & Hero Fused seamlessly into 1 View) -->
    <div class="relative min-h-screen overflow-hidden">
      
      <!-- Organic Arc Background Sweep (LUXÉ Style) -->
      <div class="absolute top-0 right-0 w-full lg:w-[54%] h-full bg-gradient-to-br from-[#F5EBE6] via-[#FAF3F0] to-[#FDF2F8] rounded-bl-[160px] lg:rounded-bl-[280px] z-0 overflow-hidden shadow-inner hidden md:block">
        <img 
          src="/images/hero/luxury_wedding_hero.png" 
          alt="LUXÉ Studio Ambient Room" 
          class="w-full h-full object-cover opacity-70 mix-blend-overlay"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-[#FAF8F5] via-[#FAF8F5]/80 to-transparent"></div>
      </div>

      <!-- Navigation Header (100% Transparent & Integrated) -->
      <header class="relative z-50 bg-transparent py-4 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
          <!-- Brand Logo -->
          <Link href="/" class="flex items-center gap-3 group">
            <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-11 md:h-12 w-auto rounded-xl object-contain drop-shadow-xs group-hover:scale-105 transition-transform duration-300" />
            <span class="font-serif text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
          </Link>

          <!-- Navigation Links -->
          <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-700">
            <a href="#storyline" class="hover:text-[#881337] transition-colors py-1 font-semibold text-rose-900">Hành trình</a>
            <a href="#features" class="hover:text-[#881337] transition-colors py-1">Tính năng</a>
            <Link href="/portfolio" class="hover:text-[#881337] transition-colors py-1">Kho thiệp mẫu</Link>
            <Link href="/wedding/timeline" class="hover:text-[#881337] transition-colors py-1">Lập kế hoạch</Link>
            <Link href="/wedding/vendors" class="hover:text-[#881337] transition-colors py-1">Đối tác</Link>
            <Link href="/blog" class="hover:text-[#881337] transition-colors py-1">Cẩm nang</Link>
          </nav>

          <!-- Header Actions -->
          <div class="flex items-center gap-2 md:gap-3">
            <template v-if="authUser">
              <Link
                href="/wedding/timeline"
                class="px-6 py-2.5 rounded-full bg-[#881337] text-white hover:bg-[#70102d] font-bold text-xs md:text-sm transition-all shadow-md flex items-center gap-2.5 hover:scale-105 active:scale-95"
              >
                <span>Vào Workspace</span>
                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                  <ArrowRight class="w-3.5 h-3.5 text-white" />
                </div>
              </Link>
            </template>
            <template v-else>
              <Link
                href="/login"
                class="px-4 py-2 rounded-full text-slate-700 hover:text-[#881337] font-semibold text-xs md:text-sm transition-all"
              >
                Đăng nhập
              </Link>
              <Link
                href="/register"
                class="px-6 py-2.5 rounded-full bg-[#881337] text-white hover:bg-[#70102d] font-bold text-xs md:text-sm transition-all shadow-md flex items-center gap-2.5 hover:scale-105 active:scale-95"
              >
                <span>Đăng ký</span>
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                  <ArrowRight class="w-3 h-3 text-white" />
                </div>
              </Link>
            </template>
          </div>
        </div>
      </header>

      <!-- Ultra-Minimalist High-Fashion Hero Section -->
      <section class="relative pt-6 pb-16 md:pt-12 md:pb-24 px-6 z-10">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-12 gap-12 items-center relative z-20">
          
          <!-- Left Column: High-Fashion Copywriting & Capsule Action Buttons -->
          <div class="lg:col-span-6 text-center lg:text-left space-y-7">
            <div class="gsap-hero-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#F5EBE6] border border-[#E8D5CE] text-[#881337] text-[11px] font-extrabold tracking-widest uppercase shadow-2xs">
              <Sparkles class="w-3.5 h-3.5 text-[#881337]" />
              ✦ WHERE LOVE MEETS ELEGANCE ✦
            </div>

            <h1 class="gsap-hero-title text-4xl sm:text-5xl lg:text-6xl font-serif font-extrabold text-slate-900 tracking-tight leading-[1.12]">
              Khởi đầu ngày cưới, <br />
              <span class="font-serif italic font-normal text-[#881337]">
                trọn vẹn nhất.
              </span>
            </h1>

            <p class="gsap-hero-desc text-sm md:text-base text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-sans font-medium">
              Từ thiệp cưới điện tử 3D mở sáp nến lãng mạn tới quản lý ngân sách, sơ đồ bàn tiệc & đối tác — Eloria mang tới trải nghiệm cưới hoàn hảo nhất.
            </p>

            <!-- LUXÉ Capsule Action Buttons -->
            <div class="gsap-hero-cta pt-2 flex flex-wrap justify-center lg:justify-start items-center gap-4">
              <Link
                href="/onboarding"
                class="px-8 py-4 rounded-full bg-[#881337] hover:bg-[#70102d] text-white font-extrabold text-xs md:text-sm shadow-xl shadow-[#881337]/20 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3 cursor-pointer tracking-wider"
              >
                <span>Lập Kế Hoạch Đám Cưới</span>
                <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shadow-inner">
                  <ArrowRight class="w-4 h-4 text-white" />
                </div>
              </Link>

              <Link
                href="/portfolio"
                class="px-6 py-4 rounded-full bg-white/90 text-slate-800 border border-slate-200/80 hover:bg-rose-50/70 font-bold text-xs md:text-sm shadow-xs hover:shadow-md hover:scale-105 active:scale-95 transition-all flex items-center gap-2 cursor-pointer"
              >
                <Play class="w-4 h-4 text-[#881337] fill-[#881337]" />
                <span>Xem Demo Thiệp 3D</span>
              </Link>
            </div>

            <!-- LUXÉ Sleek Stat Chips Row (Minimal & Clean) -->
            <div class="gsap-hero-stats pt-4 flex items-center justify-center lg:justify-start gap-6 text-xs font-semibold text-slate-700">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#881337]"></span>
                <span><strong>15,000+</strong> Dâu Rể Tin Dùng</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                <span><strong>100%</strong> Tự Động RSVP</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                <span><strong>48Tr</strong> Tiết Kiệm</span>
              </div>
            </div>
          </div>

          <!-- Right Column: Clean Arched Studio Portrait + 1 Glass Calendar Widget (LUXÉ Style) -->
          <div class="lg:col-span-6 relative gsap-hero-3d pt-4 lg:pt-0">
            <div 
              class="relative transition-transform duration-200 ease-out"
              :style="{ transform: `perspective(1200px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`, transformStyle: 'preserve-3d' }"
            >
              <!-- Ambient Glow -->
              <div class="absolute -inset-4 bg-gradient-to-br from-rose-200/40 via-amber-100/30 to-purple-200/20 rounded-[80px] blur-2xl opacity-70 animate-pulse-slow"></div>

              <!-- Sleek Arched Studio Portrait Container (LUXÉ Style) -->
              <div class="relative w-full max-w-md mx-auto h-[460px] sm:h-[520px] rounded-t-[200px] rounded-b-3xl overflow-hidden border-4 border-white shadow-[0_25px_60px_-15px_rgba(136,19,55,0.18)] bg-slate-900 group">
                <img 
                  src="/images/hero/luxury_wedding_hero.png" 
                  alt="LUXÉ Wedding Studio Portrait" 
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-95" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>

                <!-- Bottom Couple Label -->
                <div class="absolute bottom-6 inset-x-6 text-center text-white space-y-1">
                  <h4 class="font-serif font-extrabold text-2xl text-white tracking-wide">Quốc Trung & Hồng Vân</h4>
                  <p class="text-xs text-rose-200 font-serif italic">Thứ Bảy, 24 Tháng 10 Năm 2026</p>
                </div>
              </div>

              <!-- LUXÉ Book Your Slot Mini Calendar Widget (Single Clean Floating Widget) -->
              <div class="absolute bottom-4 right-0 sm:-right-4 z-30 p-4 rounded-3xl bg-white/95 backdrop-blur-2xl border border-amber-200/80 text-slate-900 shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-300 space-y-2 max-w-[215px]">
                <div class="flex items-center justify-between text-xs font-serif font-bold text-slate-800">
                  <span class="flex items-center gap-1"><Calendar class="w-4 h-4 text-[#881337]" /> Đặt Lịch Cưới</span>
                  <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-100 text-[#881337] font-bold">OCT 2026</span>
                </div>
                <div class="grid grid-cols-7 gap-1 text-[9px] font-mono text-center text-slate-400 py-1">
                  <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                  <span class="text-slate-300">18</span><span class="text-slate-300">19</span><span class="text-slate-300">20</span><span class="text-slate-300">21</span><span class="text-slate-300">22</span><span class="text-slate-300">23</span>
                  <span class="font-bold text-white bg-[#881337] rounded-full w-4 h-4 mx-auto flex items-center justify-center shadow-xs">24</span>
                </div>
                <button class="w-full py-1.5 rounded-full bg-[#881337] text-white font-bold text-[10px] flex items-center justify-center gap-1 shadow-sm hover:bg-[#70102d] transition">
                  <span>Chọn Ngày & Giờ</span>
                  <ArrowRight class="w-3 h-3 text-white" />
                </button>
              </div>

            </div>
          </div>

        </div>
      </section>
    </div>

    <!-- Key Metrics Highlight Section -->
    <section class="gsap-metric-section py-12 px-6 max-w-7xl mx-auto border-y border-rose-100/80 bg-white/60 backdrop-blur-md my-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div class="gsap-metric-card space-y-1">
          <h3 class="text-3xl md:text-4xl font-serif font-extrabold text-slate-900">15,000+</h3>
          <p class="text-xs md:text-sm font-medium text-slate-600">Đám Cưới Đã Tạo</p>
        </div>
        <div class="gsap-metric-card space-y-1">
          <h3 class="text-3xl md:text-4xl font-serif font-extrabold text-[#881337]">100%</h3>
          <p class="text-xs md:text-sm font-medium text-slate-600">Tự Động RSVP & Nhắc Lịch</p>
        </div>
        <div class="gsap-metric-card space-y-1">
          <h3 class="text-3xl md:text-4xl font-serif font-extrabold text-amber-700">48 Triệu</h3>
          <p class="text-xs md:text-sm font-medium text-slate-600">Tiết Kiệm Trung Bình/Cặp</p>
        </div>
        <div class="gsap-metric-card space-y-1">
          <h3 class="text-3xl md:text-4xl font-serif font-extrabold text-emerald-700">4.9/5 ★</h3>
          <p class="text-xs md:text-sm font-medium text-slate-600">Đánh Giá Từ Cô Dâu Chú Rể</p>
        </div>
      </div>
    </section>

    <!-- Storyline Interactive Timeline Section -->
    <section id="storyline" class="py-16 md:py-24">
      <WeddingScrollStorySection />
    </section>

    <!-- Core Features Section -->
    <section id="features" class="py-16 md:py-24 px-6 max-w-7xl mx-auto space-y-12">
      <div class="text-center space-y-4 max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100 text-rose-900 text-xs font-bold uppercase tracking-wider">
          <Layers class="w-4 h-4 text-[#881337]" /> TẤT CẢ TRONG MỘT HỆ ĐIỀU HÀNH
        </div>
        <h2 class="text-3xl md:text-5xl font-serif font-extrabold text-slate-900">
          Công Cụ Lập Kế Hoạch Đám Cưới Toàn Diện
        </h2>
        <p class="text-slate-600 text-sm md:text-base">
          Thay thế các file Excel rời rạc bằng quy trình quản lý chuyên nghiệp chuẩn Linear cho ngày trọng đại.
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="gsap-feature-card p-8 rounded-3xl bg-white border border-rose-100 shadow-sm hover:shadow-xl transition-all space-y-4 group">
          <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#881337] flex items-center justify-center group-hover:scale-110 transition-transform">
            <Calendar class="w-6 h-6" />
          </div>
          <h3 class="text-xl font-serif font-bold text-slate-900">Tiến Độ & Milestone Kanban</h3>
          <p class="text-slate-600 text-xs leading-relaxed">
            Danh sách việc cần làm 12 tháng phân theo cột mốc cụ thể, nhắc nhở lịch tự động để Dâu Rể không bỏ lỡ công việc.
          </p>
        </div>

        <!-- Feature 2 -->
        <div class="gsap-feature-card p-8 rounded-3xl bg-white border border-amber-100 shadow-sm hover:shadow-xl transition-all space-y-4 group">
          <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
            <DollarSign class="w-6 h-6" />
          </div>
          <h3 class="text-xl font-serif font-bold text-slate-900">Quản Lý Dòng Tiền & Cọc Vendor</h3>
          <p class="text-slate-600 text-xs leading-relaxed">
            Theo dõi chi tiết dự toán ngân sách, lịch thanh toán cọc đối tác chụp ảnh, nhà hàng, trang điểm chính xác.
          </p>
        </div>

        <!-- Feature 3 -->
        <div class="gsap-feature-card p-8 rounded-3xl bg-white border border-emerald-100 shadow-sm hover:shadow-xl transition-all space-y-4 group">
          <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform">
            <Users class="w-6 h-6" />
          </div>
          <h3 class="text-xl font-serif font-bold text-slate-900">Sơ Đồ Bàn Tiệc & RSVP Trực Tuyến</h3>
          <p class="text-slate-600 text-xs leading-relaxed">
            Sắp xếp vị trí khách mời theo từng bàn tiệc, thu nhận lời xác nhận tham dự tự động kèm khẩu vị riêng.
          </p>
        </div>
      </div>
    </section>

    <!-- Templates Showcase Portfolio Section -->
    <section id="templates" class="py-16 md:py-24 px-6 bg-gradient-to-b from-[#FFFDF9] via-[#FAF8F5] to-rose-50/40 border-t border-rose-100/80">
      <div class="max-w-7xl mx-auto space-y-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div class="space-y-3">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100 text-amber-900 text-xs font-bold uppercase tracking-wider">
              <Palette class="w-4 h-4 text-amber-600" /> BỘ COLLECTION THIỆP 3D 2026
            </div>
            <h2 class="text-3xl md:text-5xl font-serif font-extrabold text-slate-900">
              Thiệp Đám Cưới Điện Tử Độc Bản
            </h2>
          </div>
          <Link href="/portfolio" class="px-6 py-3 rounded-full bg-[#881337] text-white font-bold text-xs hover:bg-[#70102d] transition shadow-md flex items-center gap-2 w-fit">
            <span>Xem Tất Cả 10 Mẫu</span>
            <ArrowRight class="w-4 h-4 text-rose-200" />
          </Link>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="item in defaultTemplates" 
            :key="item.id" 
            class="gsap-template-card p-6 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl transition-all space-y-4 group flex flex-col justify-between"
          >
            <div class="space-y-3">
              <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-rose-50 text-[#881337] border border-rose-200">
                {{ item.badge }}
              </span>
              <h3 class="text-lg font-serif font-bold text-slate-900 group-hover:text-[#881337] transition-colors">
                {{ item.name }}
              </h3>
              <p class="text-slate-600 text-xs leading-relaxed line-clamp-2">{{ item.description }}</p>
            </div>
            <a 
              :href="item.demoUrl" 
              target="_blank" 
              class="pt-4 border-t border-slate-100 text-xs font-bold text-[#881337] hover:text-rose-950 flex items-center gap-1.5"
            >
              <Eye class="w-3.5 h-3.5" /> Xem Demo Live
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 px-6 border-t border-slate-800">
      <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-10 text-xs space-y-6 md:space-y-0">
        <div class="space-y-3">
          <p class="font-serif font-bold text-xl text-white">Eloria Wedding OS</p>
          <p class="leading-relaxed">Hệ điều hành lập kế hoạch cưới chuyên nghiệp dành riêng cho Dâu Rể & Chuyên gia tiệc cưới.</p>
        </div>
        <div class="space-y-2">
          <p class="font-bold text-white uppercase tracking-wider text-xs">Tính năng chính</p>
          <p><a href="#features" class="hover:text-white transition">Tiến độ Kanban</a></p>
          <p><a href="#features" class="hover:text-white transition">Quản lý dòng tiền</a></p>
          <p><a href="#features" class="hover:text-white transition">Sơ đồ bàn tiệc</a></p>
        </div>
        <div class="space-y-2">
          <p class="font-bold text-white uppercase tracking-wider text-xs">Kho mẫu thiệp</p>
          <p><Link href="/portfolio" class="hover:text-white transition">Thiệp 3D Mở Sáp Nến</Link></p>
          <p><Link href="/portfolio" class="hover:text-white transition">Hoàng Gia Dát Vàng</Link></p>
          <p><Link href="/portfolio" class="hover:text-white transition">Tạp Chí Notion/Linear</Link></p>
        </div>
        <div class="space-y-2">
          <p class="font-bold text-white uppercase tracking-wider text-xs">Hỗ trợ & Liên hệ</p>
          <p>Email: support@eloria.wedding</p>
          <p>Hotline: 1900-ELORIA</p>
          <p>© 2026 Eloria Inc. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>
