<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { 
  DollarSign, 
  Plus, 
  AlertTriangle, 
  TrendingUp, 
  Calendar, 
  CheckCircle2, 
  PieChart, 
  ArrowUpRight, 
  Sliders, 
  Sparkles,
  Building2,
  MapPin,
  Star,
  Users,
  Download,
  Check
} from 'lucide-vue-next';

interface Pillar {
  key: string;
  title: string;
  percentage: number;
  allocated: number;
  icon: string;
  color: string;
  note: string;
}

interface BudgetBreakdown {
  total_budget: number;
  estimated_guests: number;
  total_tables: number;
  per_table_cap: number;
  pillars: Pillar[];
}

interface Venue {
  id: string;
  name: string;
  district: string;
  price_per_table: number;
  price_label: string;
  capacity_text: string;
  highlights: string[];
  rating: number;
  match_score: number;
  tier: string;
}

interface WorkspaceInfo {
  name?: string;
  venue_name?: string;
  budget_cap?: number;
  estimated_guests?: number;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  budgetBreakdown?: BudgetBreakdown;
  recommendedVenues?: Venue[];
}>();

const formatVND = (num: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num || 0);
};

const maxBudget = ref(props.budgetBreakdown?.total_budget || props.workspace?.budget_cap || 250000000);
const selectedVenueName = ref(props.workspace?.venue_name || '');
const isSelectingVenue = ref(false);

const defaultPillars = computed(() => props.budgetBreakdown?.pillars || [
  { key: 'venue', title: 'Sảnh Tiệc & Thực Đơn', percentage: 50, allocated: maxBudget.value * 0.50, icon: 'Building2', color: 'bg-[#881337] text-white', note: 'Tối đa 6.250.000 đ/bàn (20 bàn)' },
  { key: 'media', title: 'Pre-wedding & Phim Ảnh', percentage: 15, allocated: maxBudget.value * 0.15, icon: 'Camera', color: 'bg-amber-600 text-white', note: 'Chụp studio & quay phim cưới' },
  { key: 'decor', title: 'Trang Trí & Gia Tiên', percentage: 15, allocated: maxBudget.value * 0.15, icon: 'Sparkles', color: 'bg-rose-600 text-white', note: 'Backdrop đón khách & gia tiên' },
  { key: 'attire', title: 'Trang Phục & Trang Điểm', percentage: 10, allocated: maxBudget.value * 0.10, icon: 'Crown', color: 'bg-sky-600 text-white', note: 'Váy cưới dâu & Suit chú rể' },
  { key: 'contingency', title: 'Nhẫn Cưới & Dự Phòng', percentage: 10, allocated: maxBudget.value * 0.10, icon: 'DollarSign', color: 'bg-emerald-600 text-white', note: 'Nhẫn cưới & Quỹ dự phòng' },
]);

const budgetItems = ref([
  { id: '1', category: 'Venue', name: 'Đặt cọc Sảnh tiệc & Thực đơn (50%)', estimated: maxBudget.value * 0.50, actual: maxBudget.value * 0.50, paid: maxBudget.value * 0.20, status: 'partially_paid', dueDate: '2026-08-15' },
  { id: '2', category: 'Media', name: 'Gói Chụp Ảnh Cưới Pre-wedding & Day (15%)', estimated: maxBudget.value * 0.15, actual: maxBudget.value * 0.15, paid: maxBudget.value * 0.15, status: 'fully_paid', dueDate: '2026-07-20' },
  { id: '3', category: 'Attire', name: 'Thuê Váy Cưới Dâu & Vest Chú Rể (10%)', estimated: maxBudget.value * 0.10, actual: maxBudget.value * 0.10, paid: maxBudget.value * 0.05, status: 'partially_paid', dueDate: '2026-09-01' },
  { id: '4', category: 'Ceremony', name: 'Trang Trí Gia Tiên & Flower Decor (15%)', estimated: maxBudget.value * 0.15, actual: maxBudget.value * 0.15, paid: maxBudget.value * 0.10, status: 'partially_paid', dueDate: '2026-06-10' },
  { id: '5', category: 'Reception', name: 'Nhẫn Cưới, Quà Cảm Ơn & Dự Phòng (10%)', estimated: maxBudget.value * 0.10, actual: maxBudget.value * 0.08, paid: 0, status: 'unpaid', dueDate: '2026-08-05' },
]);

const totalEstimated = computed(() => budgetItems.value.reduce((acc, i) => acc + i.estimated, 0));
const totalActual = computed(() => budgetItems.value.reduce((acc, i) => acc + i.actual, 0));
const totalPaid = computed(() => budgetItems.value.reduce((acc, i) => acc + i.paid, 0));
const totalPending = computed(() => totalActual.value - totalPaid.value);

const handleSelectVenue = async (venue: Venue) => {
  isSelectingVenue.value = true;
  try {
    const res = await fetch('/wedding/budget/select-venue', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        venue_name: venue.name,
        deposit_amount: venue.price_per_table * 5,
      }),
    });
    const data = await res.json();
    if (data.success) {
      selectedVenueName.value = venue.name;
      alert(data.message || `Đã chốt thành công sảnh tiệc [${venue.name}]!`);
    }
  } catch (e) {
    console.error('Error selecting venue:', e);
  } finally {
    isSelectingVenue.value = false;
  }
};
</script>

<template>
  <WorkspaceLayout title="Ngân sách thu chi" active-nav="budget">
    <main class="max-w-7xl mx-auto px-6 py-8 font-sans space-y-8">
      
      <!-- Top Financial Metrics Bar -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Trần Ngân Sách</span>
          <div class="text-xl font-bold text-slate-900 mt-1 font-mono">{{ formatVND(maxBudget) }}</div>
          <span class="text-[10px] text-slate-400">Thiết lập ban đầu</span>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Chi Phí Đã Phân Cho Sảnh Tiệc</span>
          <div class="text-xl font-bold text-[#881337] mt-1 font-mono">
            {{ formatVND(maxBudget * 0.50) }}
          </div>
          <span class="text-[10px] text-rose-700 font-bold">50% Ngân sách trần</span>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Đã Thanh Toán (Cọc)</span>
          <div class="text-xl font-bold text-emerald-700 mt-1 font-mono">{{ formatVND(totalPaid) }}</div>
          <span class="text-[10px] text-slate-400">Đã giải ngân</span>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Còn Phải Thanh Toán</span>
          <div class="text-xl font-bold text-amber-700 mt-1 font-mono">{{ formatVND(totalPending) }}</div>
          <span class="text-[10px] text-amber-800 font-medium">Cần chuẩn bị dòng tiền</span>
        </div>
      </div>

      <!-- 1. 5-Pillar Meticulous Budget Allocation Section -->
      <div class="p-6 md:p-8 rounded-3xl bg-white border border-rose-100 shadow-lg space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-rose-100 pb-4">
          <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-900 border border-rose-200 text-[11px] font-bold uppercase tracking-wider mb-1">
              <PieChart class="w-3.5 h-3.5 text-[#881337]" />
              Phân Chia Ngân Sách Chỉn Chu (5 Trụ Cột Đám Cưới)
            </div>
            <h2 class="text-xl font-serif font-bold text-slate-900">Mô Hình Phân Bổ Ngân Sách Tối Ưu Chống Vỡ Quỹ</h2>
          </div>
          
          <!-- Per Table Calculation Badge -->
          <div class="px-4 py-2 rounded-2xl bg-gradient-to-r from-rose-50 to-amber-50/80 border border-rose-200 text-right">
            <span class="text-[10px] text-slate-500 font-bold uppercase block">Hệ thống tính toán trần 1 bàn tiệc</span>
            <span class="text-sm font-extrabold text-[#881337] font-mono">
              {{ formatVND(budgetBreakdown?.per_table_cap || 6250000) }} / bàn ({{ budgetBreakdown?.total_tables || 20 }} bàn)
            </span>
          </div>
        </div>

        <!-- 5-Pillar Visual Progress Segment Bar -->
        <div class="space-y-2">
          <div class="h-4 w-full rounded-full bg-slate-100 overflow-hidden flex shadow-inner border border-slate-200">
            <div 
              v-for="p in defaultPillars" 
              :key="p.key"
              :style="{ width: `${p.percentage}%` }"
              :class="p.color"
              class="h-full transition-all duration-500 relative group cursor-pointer"
              :title="`${p.title}: ${p.percentage}% (${formatVND(p.allocated)})`"
            ></div>
          </div>

          <!-- Pillar Legends Grid -->
          <div class="grid grid-cols-1 md:grid-cols-5 gap-3 pt-2">
            <div 
              v-for="p in defaultPillars" 
              :key="p.key"
              class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1"
            >
              <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-900 truncate">{{ p.title }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="p.color">{{ p.percentage }}%</span>
              </div>
              <div class="text-xs font-bold text-slate-800 font-mono">{{ formatVND(p.allocated) }}</div>
              <p class="text-[10px] text-slate-500 leading-tight">{{ p.note }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. AI Venue Matchmaker Section (Đề xuất nhà hàng sảnh tiệc) -->
      <div class="p-6 md:p-8 rounded-3xl bg-gradient-to-br from-slate-900 via-rose-950 to-slate-950 text-white shadow-2xl space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-rose-900/60 pb-4">
          <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-900/80 border border-rose-700 text-amber-300 text-[11px] font-bold uppercase tracking-wider mb-1">
              <Sparkles class="w-3.5 h-3.5 text-amber-300 animate-pulse" />
              AI Venue Matchmaker — Gợi Ý Sảnh Tiệc Phù Hợp Nhất
            </div>
            <h2 class="text-xl font-serif font-bold text-white">Đề Xuất Nhà Hàng & Sảnh Tiệc Theo Đúng Phân Khúc Chi Phí</h2>
            <p class="text-xs text-rose-200/80">Dựa trên ngân sách tiệc cưới {{ formatVND(maxBudget * 0.50) }} cho {{ budgetBreakdown?.estimated_guests || 200 }} khách mời</p>
          </div>

          <div v-if="selectedVenueName" class="px-4 py-2 rounded-2xl bg-emerald-950 border border-emerald-700 text-emerald-300 text-xs font-bold flex items-center gap-2">
            <Check class="w-4 h-4 text-emerald-400" />
            <span>Đã chốt: {{ selectedVenueName }}</span>
          </div>
        </div>

        <!-- Venue Matchmaker Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div 
            v-for="v in (recommendedVenues || [])" 
            :key="v.id"
            class="p-5 rounded-2xl bg-slate-900/80 border transition-all space-y-4 hover:border-amber-400/80 flex flex-col justify-between"
            :class="selectedVenueName === v.name ? 'border-emerald-500 bg-emerald-950/30' : 'border-slate-800'"
          >
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-400/20 text-amber-300 border border-amber-400/40">
                  ✨ Match Score: {{ v.match_score }}% Phù hợp
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ v.tier }}</span>
              </div>

              <h3 class="text-lg font-serif font-bold text-white leading-snug">
                {{ v.name }}
              </h3>

              <div class="flex items-center gap-3 text-xs text-rose-200/80">
                <span class="flex items-center gap-1"><MapPin class="w-3.5 h-3.5 text-amber-400 shrink-0" /> {{ v.district }}</span>
                <span>·</span>
                <span class="flex items-center gap-1"><Users class="w-3.5 h-3.5 text-sky-400 shrink-0" /> {{ v.capacity_text }}</span>
              </div>

              <!-- Price Badge -->
              <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 text-xs flex items-center justify-between font-mono">
                <span class="text-slate-400">Mức giá bàn tiệc:</span>
                <span class="font-extrabold text-amber-300">{{ v.price_label }}</span>
              </div>

              <!-- Highlights -->
              <div class="flex flex-wrap gap-1.5 pt-1">
                <span v-for="(h, idx) in v.highlights" :key="idx" class="px-2.5 py-0.5 rounded-md bg-slate-800 text-slate-300 text-[10px] font-medium">
                  ✓ {{ h }}
                </span>
              </div>
            </div>

            <!-- Lock Venue CTA Button -->
            <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
              <span class="text-[11px] text-slate-400 flex items-center gap-1">
                <Star class="w-3.5 h-3.5 text-amber-400 fill-amber-400" /> {{ v.rating }} / 5.0 Rating
              </span>
              <button 
                @click="handleSelectVenue(v)"
                :disabled="isSelectingVenue"
                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                :class="selectedVenueName === v.name ? 'bg-emerald-600 text-white' : 'bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 shadow-md'"
              >
                <Building2 class="w-3.5 h-3.5" />
                <span>{{ selectedVenueName === v.name ? 'Đã Chốt Sảnh Tiệc' : '🏛️ Chốt Sảnh Tiệc Này' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. Cash Flow Table Section -->
      <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
          <div>
            <h2 class="text-base font-bold text-slate-900">Danh Mục Chi Phí & Dòng Tiền Đã Giải Ngân</h2>
            <p class="text-xs text-slate-500">Quản lý theo dõi hạn cọc và hóa đơn nhà cung cấp</p>
          </div>
          <div class="flex items-center gap-2">
            <a 
              href="/wedding/budget/export" 
              target="_blank"
              class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-medium text-xs hover:bg-slate-50 transition flex items-center gap-1.5 shadow-2xs"
            >
              <Download class="w-4 h-4 text-slate-500" /> Xuất CSV
            </a>
          </div>
        </div>

        <table class="w-full text-left text-xs border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-semibold">
              <th class="p-4">Hạng mục</th>
              <th class="p-4">Tên khoản chi</th>
              <th class="p-4">Dự kiến</th>
              <th class="p-4">Thực tế</th>
              <th class="p-4">Đã thanh toán</th>
              <th class="p-4">Hạn cọc đợt tới</th>
              <th class="p-4">Trạng thái</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="item in budgetItems" :key="item.id" class="hover:bg-slate-50/80 transition-colors">
              <td class="p-4 font-semibold text-slate-700">
                <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-800 border border-rose-100">{{ item.category }}</span>
              </td>
              <td class="p-4 font-medium text-slate-900">{{ item.name }}</td>
              <td class="p-4 text-slate-600 font-mono">{{ formatVND(item.estimated) }}</td>
              <td class="p-4 font-semibold text-slate-900 font-mono">{{ formatVND(item.actual) }}</td>
              <td class="p-4 font-semibold text-emerald-700 font-mono">{{ formatVND(item.paid) }}</td>
              <td class="p-4 text-slate-500">{{ item.dueDate }}</td>
              <td class="p-4">
                <span v-if="item.status === 'fully_paid'" class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-semibold">Đã hoàn tất</span>
                <span v-else-if="item.status === 'partially_paid'" class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-semibold">Đã cọc 1 phần</span>
                <span v-else class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[10px] font-semibold">Chưa cọc</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </WorkspaceLayout>
</template>
