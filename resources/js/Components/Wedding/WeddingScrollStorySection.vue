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
    badgeColor: 'bg-red-950 text-red-400 border-red-800',
    accentColor: 'text-red-400',
    metrics: [
      { label: 'Dự toán chi phí', val: 'Vỡ quỹ 35%', status: 'Cảnh báo', statusColor: 'text-red-400 bg-red-950 border-red-900' },
      { label: 'Danh sách khách mời', val: '280 Khách', status: 'Phân tán Excel/Note', statusColor: 'text-amber-400 bg-amber-950 border-amber-900' },
      { label: 'Hạn cọc Vendor', val: '3 Đợt trễ hẹn', status: 'Chưa kiểm soát', statusColor: 'text-red-400 bg-red-950 border-red-900' }
    ]
  },
  {
    phase: 'GIAI ĐOẠN 2: TỰ ĐỘNG HÓA',
    title: 'Eloria OS Tự Động Hóa Tiến Độ & Dòng Tiền',
    tagline: 'Quản Lý Đám Cưới Tập Trung',
    description: 'Tất cả được chuẩn hóa trên 1 giao diện duy nhất: Tiến độ Kanban tự động phân bổ, thuật toán tính Cashflow chống vỡ quỹ và Thiệp cưới điện tử nhận RSVP thời gian thực.',
    badgeColor: 'bg-amber-950 text-amber-300 border-amber-800',
    accentColor: 'text-amber-400',
    metrics: [
      { label: 'Tiến độ Kanban', val: '40+ Mốc tự động', status: 'Phân bổ chuẩn', statusColor: 'text-amber-300 bg-amber-950 border-amber-800' },
      { label: 'Dòng tiền cọc', val: 'Cashflow Calculator', status: 'Cảnh báo thông minh', statusColor: 'text-emerald-400 bg-emerald-950 border-emerald-800' },
      { label: 'Thiệp điện tử', val: '10 Mẫu 3D Live', status: 'Mở sáp & RSVP', statusColor: 'text-rose-400 bg-rose-950 border-rose-900' }
    ]
  },
  {
    phase: 'GIAI ĐOẠN 3: VIÊN MÃN',
    title: 'Thong Dong Tận Hưởng Đám Cưới Mơ Ước',
    tagline: 'Ngày Trọng Đại Hoàn Hảo 100%',
    description: 'Mọi chi tiết được chuẩn bị chu đáo, dâu rể thong dong bước vào tiệc cưới ngập tràn hoa tươi và lời chúc mừng từ toàn thể khách mời.',
    badgeColor: 'bg-emerald-950 text-emerald-300 border-emerald-800',
    accentColor: 'text-emerald-400',
    metrics: [
      { label: 'Tiến độ thiết lập', val: '100% Hoàn hảo', status: 'Chuẩn xác', statusColor: 'text-emerald-300 bg-emerald-950 border-emerald-800' },
      { label: 'Phản hồi RSVP', val: '200 / 200 Khách', status: 'Đã xác nhận', statusColor: 'text-emerald-300 bg-emerald-950 border-emerald-800' },
      { label: 'Rủi ro vỡ quỹ', val: '0% Vỡ ngân sách', status: 'An toàn tuyệt đối', statusColor: 'text-emerald-300 bg-emerald-950 border-emerald-800' }
    ]
  }
];
</script>

<template>
  <section class="py-16 md:py-24 px-6 max-w-7xl mx-auto font-sans relative z-10">
    <div class="p-8 md:p-14 rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-10 space-y-3">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-xs font-mono font-bold tracking-widest text-slate-300 uppercase">
          <Zap class="w-3.5 h-3.5 text-amber-400" />
          HÀNH TRÌNH TỪ KHÓ KHĂN ĐẾN ĐÁM CƯỚI MƠ ƯỚC
        </div>
        <h2 class="text-3xl md:text-5xl font-serif font-bold text-white tracking-tight">
          Giải Quyết Toàn Bộ Áp Lực Đám Cưới
        </h2>
        <p class="text-slate-400 text-xs md:text-sm font-medium">
          Xem cách Eloria OS chuyển hóa sự phức tạp thành trải nghiệm thong dong cho Dâu Rể.
        </p>
      </div>

      <!-- Step Navigation Bar -->
      <div class="grid grid-cols-3 gap-2 md:gap-4 max-w-3xl mx-auto mb-10 p-1.5 rounded-2xl bg-slate-950 border border-slate-800">
        <button 
          @click="activeTab = 0"
          class="py-3 px-2 rounded-xl text-xs font-mono font-bold transition-all text-center cursor-pointer"
          :class="activeTab === 0 ? 'bg-red-950 text-red-400 border border-red-800 shadow-md' : 'text-slate-500 hover:text-slate-300'"
        >
          01. BẮT ĐẦU: ÁP LỰC
        </button>
        <button 
          @click="activeTab = 1"
          class="py-3 px-2 rounded-xl text-xs font-mono font-bold transition-all text-center cursor-pointer"
          :class="activeTab === 1 ? 'bg-amber-950 text-amber-300 border border-amber-800 shadow-md' : 'text-slate-500 hover:text-slate-300'"
        >
          02. ELORIA TỰ ĐỘNG HÓA
        </button>
        <button 
          @click="activeTab = 2"
          class="py-3 px-2 rounded-xl text-xs font-mono font-bold transition-all text-center cursor-pointer"
          :class="activeTab === 2 ? 'bg-emerald-950 text-emerald-300 border border-emerald-800 shadow-md' : 'text-slate-500 hover:text-slate-300'"
        >
          03. ĐÁM CƯỚI MƠ ƯỚC
        </button>
      </div>

      <!-- Compact Active Step Card Body -->
      <div class="max-w-4xl mx-auto p-8 rounded-2xl bg-slate-950/80 border border-slate-800 grid md:grid-cols-12 gap-8 items-center">
        
        <!-- Left Text Description Column -->
        <div class="md:col-span-7 space-y-4 text-left">
          <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase border" :class="steps[activeTab].badgeColor">
              {{ steps[activeTab].phase }}
            </span>
            <span class="text-xs font-semibold text-slate-400">{{ steps[activeTab].tagline }}</span>
          </div>

          <h3 class="text-2xl md:text-3xl font-serif font-bold text-white leading-tight">
            {{ steps[activeTab].title }}
          </h3>

          <p class="text-xs md:text-sm text-slate-300 leading-relaxed font-sans">
            {{ steps[activeTab].description }}
          </p>

          <div v-if="activeTab === 2" class="pt-2">
            <Link 
              href="/onboarding" 
              class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-100 text-slate-900 font-bold text-xs hover:bg-white transition shadow-md cursor-pointer"
            >
              <span>BẮT ĐẦU ĐÁM CƯỚI CỦA BẠN</span>
              <ArrowRight class="w-4 h-4 text-rose-600" />
            </Link>
          </div>
        </div>

        <!-- Right Metric Pills Breakdown -->
        <div class="md:col-span-5 space-y-3 font-mono">
          <div 
            v-for="(m, idx) in steps[activeTab].metrics" 
            :key="idx" 
            class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between text-xs"
          >
            <div>
              <span class="text-slate-400 text-[10px] block uppercase">{{ m.label }}</span>
              <span class="font-bold text-white text-sm font-sans">{{ m.val }}</span>
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
