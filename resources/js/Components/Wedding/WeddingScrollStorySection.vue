<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { 
  AlertCircle, 
  CheckCircle2, 
  Sparkles, 
  Calendar, 
  DollarSign, 
  Heart, 
  ArrowRight,
  ShieldCheck,
  Zap,
  TrendingUp,
  Clock
} from 'lucide-vue-next';

const activeTab = ref<number>(0);

const steps = [
  {
    phase: 'GIAI ĐOẠN 1: KHỞI ĐẦU',
    title: 'Áp Lực Chuẩn Bị & Nỗi Lo Vỡ Ngân Sách',
    tagline: 'Trước Khi Tìm Thấy Eloria OS',
    description: 'Dâu Rể thường gặp vướng mắc ở hơn 40+ hạng mục: quên tiền cọc nhà cung cấp, danh sách khách mời ghi chép rải rác và chi phí phát sinh ngoài tầm kiểm soát.',
    badgeColor: 'bg-rose-100 text-[#881337] border-rose-300',
    accentColor: 'text-[#881337]',
    metrics: [
      { label: 'Dự toán chi phí', val: 'Vỡ quỹ 35%', status: 'Cảnh báo', statusColor: 'text-rose-700 bg-rose-50 border-rose-200' },
      { label: 'Danh sách khách mời', val: '280 Khách', status: 'Phân tán Excel/Note', statusColor: 'text-amber-800 bg-amber-50 border-amber-200' },
      { label: 'Hạn cọc Vendor', val: '3 Đợt trễ hẹn', status: 'Chưa kiểm soát', statusColor: 'text-rose-700 bg-rose-50 border-rose-200' }
    ]
  },
  {
    phase: 'GIAI ĐOẠN 2: TỰ ĐỘNG HÓA',
    title: 'Eloria OS Tự Động Hóa Tiến Độ & Dòng Tiền',
    tagline: 'Quản Lý Đám Cưới Tập Trung',
    description: 'Tất cả được chuẩn hóa trên 1 giao diện duy nhất: Tiến độ Kanban tự động phân bổ, thuật toán tính Cashflow chống vỡ quỹ và Thiệp cưới điện tử nhận RSVP thời gian thực.',
    badgeColor: 'bg-amber-100 text-amber-900 border-amber-300',
    accentColor: 'text-amber-700',
    metrics: [
      { label: 'Tiến độ Kanban', val: '40+ Mốc tự động', status: 'Phân bổ chuẩn', statusColor: 'text-amber-800 bg-amber-50 border-amber-200' },
      { label: 'Dòng tiền cọc', val: 'Cashflow Calculator', status: 'Cảnh báo thông minh', statusColor: 'text-emerald-800 bg-emerald-50 border-emerald-200' },
      { label: 'Thiệp điện tử', val: '10 Mẫu 3D Live', status: 'Mở sáp & RSVP', statusColor: 'text-[#881337] bg-rose-50 border-rose-200' }
    ]
  },
  {
    phase: 'GIAI ĐOẠN 3: VIÊN MÃN',
    title: 'Thong Dong Tận Hưởng Đám Cưới Mơ Ước',
    tagline: 'Ngày Trọng Đại Hoàn Hảo 100%',
    description: 'Mọi chi tiết được chuẩn bị chu đáo, dâu rể thong dong bước vào tiệc cưới ngập tràn hoa tươi và lời chúc mừng từ toàn thể khách mời.',
    badgeColor: 'bg-emerald-100 text-emerald-900 border-emerald-300',
    accentColor: 'text-emerald-700',
    metrics: [
      { label: 'Tiến độ thiết lập', val: '100% Hoàn hảo', status: 'Chuẩn xác', statusColor: 'text-emerald-800 bg-emerald-50 border-emerald-200' },
      { label: 'Phản hồi RSVP', val: '200 / 200 Khách', status: 'Đã xác nhận', statusColor: 'text-emerald-800 bg-emerald-50 border-emerald-200' },
      { label: 'Rủi ro vỡ quỹ', val: '0% Vỡ ngân sách', status: 'An toàn tuyệt đối', statusColor: 'text-emerald-800 bg-emerald-50 border-emerald-200' }
    ]
  }
];
</script>

<template>
  <section class="py-16 md:py-24 px-6 max-w-7xl mx-auto font-sans relative z-10">
    <div class="p-8 md:p-14 rounded-3xl bg-white/90 border border-rose-200/80 shadow-2xl text-slate-900 relative overflow-hidden backdrop-blur-2xl">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-10 space-y-3">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100/80 border border-rose-200 text-xs font-bold tracking-widest text-[#881337] uppercase shadow-2xs">
          <Zap class="w-3.5 h-3.5 text-[#881337]" />
          HÀNH TRÌNH TỪ KHÓ KHĂN ĐẾN ĐÁM CƯỚI MƠ ƯỚC
        </div>
        <h2 class="text-3xl md:text-5xl font-serif font-extrabold text-slate-900 tracking-tight">
          Giải Quyết Toàn Bộ Áp Lực Đám Cưới
        </h2>
        <p class="text-slate-600 text-xs md:text-sm font-medium">
          Xem cách Eloria OS chuyển hóa sự phức tạp thành trải nghiệm thong dong cho Dâu Rể.
        </p>
      </div>

      <!-- Step Navigation Bar -->
      <div class="grid grid-cols-3 gap-2 md:gap-4 max-w-3xl mx-auto mb-10 p-1.5 rounded-2xl bg-rose-50/70 border border-rose-100">
        <button 
          @click="activeTab = 0"
          class="py-3 px-2 rounded-xl text-xs font-mono font-bold transition-all text-center cursor-pointer"
          :class="activeTab === 0 ? 'bg-white text-[#881337] border border-rose-200 shadow-md' : 'text-slate-500 hover:text-slate-800'"
        >
          01. BẮT ĐẦU: ÁP LỰC
        </button>
        <button 
          @click="activeTab = 1"
          class="py-3 px-2 rounded-xl text-xs font-mono font-bold transition-all text-center cursor-pointer"
          :class="activeTab === 1 ? 'bg-white text-amber-900 border border-amber-200 shadow-md' : 'text-slate-500 hover:text-slate-800'"
        >
          02. ELORIA TỰ ĐỘNG HÓA
        </button>
        <button 
          @click="activeTab = 2"
          class="py-3 px-2 rounded-xl text-xs font-mono font-bold transition-all text-center cursor-pointer"
          :class="activeTab === 2 ? 'bg-white text-emerald-900 border border-emerald-200 shadow-md' : 'text-slate-500 hover:text-slate-800'"
        >
          03. ĐÁM CƯỚI MƠ ƯỚC
        </button>
      </div>

      <!-- Compact Active Step Card Body -->
      <div class="max-w-4xl mx-auto p-8 rounded-2xl bg-white border border-rose-100 shadow-lg grid md:grid-cols-12 gap-8 items-center">
        
        <!-- Left Text Description Column -->
        <div class="md:col-span-7 space-y-4 text-left">
          <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase border" :class="steps[activeTab].badgeColor">
              {{ steps[activeTab].phase }}
            </span>
            <span class="text-xs font-semibold text-slate-500">{{ steps[activeTab].tagline }}</span>
          </div>

          <h3 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 leading-tight">
            {{ steps[activeTab].title }}
          </h3>

          <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-sans font-medium">
            {{ steps[activeTab].description }}
          </p>

          <div v-if="activeTab === 2" class="pt-2">
            <Link 
              href="/onboarding" 
              class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full bg-[#881337] text-white font-extrabold text-xs hover:bg-[#70102d] transition shadow-lg cursor-pointer"
            >
              <span>BẮT ĐẦU ĐÁM CƯỚI CỦA BẠN</span>
              <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                <ArrowRight class="w-3 h-3 text-white" />
              </div>
            </Link>
          </div>
        </div>

        <!-- Right Metric Pills Breakdown -->
        <div class="md:col-span-5 space-y-3 font-mono">
          <div 
            v-for="(m, idx) in steps[activeTab].metrics" 
            :key="idx" 
            class="p-4 rounded-xl bg-slate-50/80 border border-slate-100 flex items-center justify-between text-xs"
          >
            <div>
              <span class="text-slate-500 text-[10px] block uppercase">{{ m.label }}</span>
              <span class="font-extrabold text-slate-900 text-sm font-sans">{{ m.val }}</span>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border" :class="m.statusColor">
              {{ m.status }}
            </span>
          </div>
        </div>

      </div>

    </div>
  </section>
</template>
