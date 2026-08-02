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
  Bot,
  Layers,
  Heart,
  Download
} from 'lucide-vue-next';

const formatVND = (num: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num || 0);
};

const maxBudget = ref(250000000); // 250 Trăm triệu
const budgetItems = ref([
  { id: '1', category: 'Venue', name: 'Đặt cọc Nhà hàng White Palace', estimated: 80000000, actual: 85000000, paid: 40000000, status: 'partially_paid', dueDate: '2026-08-15' },
  { id: '2', category: 'Media', name: 'Gói Chụp Ảnh Cưới Pre-wedding & Day', estimated: 35000000, actual: 32000000, paid: 32000000, status: 'fully_paid', dueDate: '2026-07-20' },
  { id: '3', category: 'Attire', name: 'Thuê Váy Cưới Dâu & Vest Chú Rể', estimated: 25000000, actual: 28000000, paid: 10000000, status: 'partially_paid', dueDate: '2026-09-01' },
  { id: '4', category: 'Ceremony', name: 'Trang Trí Gia Tiên Pastel Theme', estimated: 20000000, actual: 20000000, paid: 20000000, status: 'fully_paid', dueDate: '2026-06-10' },
  { id: '5', category: 'Reception', name: 'Quà Cảm Ơn Khách Mời & Nến Thơm', estimated: 15000000, actual: 12000000, paid: 0, status: 'unpaid', dueDate: '2026-08-05' },
]);

const totalEstimated = computed(() => budgetItems.value.reduce((acc, i) => acc + i.estimated, 0));
const totalActual = computed(() => budgetItems.value.reduce((acc, i) => acc + i.actual, 0));
const totalPaid = computed(() => budgetItems.value.reduce((acc, i) => acc + i.paid, 0));
const totalPending = computed(() => totalActual.value - totalPaid.value);
const isOverrun = computed(() => totalActual.value > maxBudget.value);

const autoAllocateBudget = () => {
  const cap = maxBudget.value;
  budgetItems.value = [
    { id: '1', category: 'Venue', name: 'Sảnh tiệc & Thực đơn (50%)', estimated: cap * 0.50, actual: cap * 0.50, paid: cap * 0.20, status: 'partially_paid', dueDate: '2026-08-15' },
    { id: '2', category: 'Media', name: 'Phim ảnh & Pre-wedding (15%)', estimated: cap * 0.15, actual: cap * 0.15, paid: cap * 0.05, status: 'partially_paid', dueDate: '2026-07-20' },
    { id: '3', category: 'Attire', name: 'Trang phục & Trang điểm (10%)', estimated: cap * 0.10, actual: cap * 0.10, paid: cap * 0.10, status: 'fully_paid', dueDate: '2026-09-01' },
    { id: '4', category: 'Ceremony', name: 'Trang trí Gia tiên (10%)', estimated: cap * 0.10, actual: cap * 0.10, paid: cap * 0.05, status: 'partially_paid', dueDate: '2026-06-10' },
    { id: '5', category: 'Reception', name: 'Quà đáp lễ & Dự phòng (15%)', estimated: cap * 0.15, actual: cap * 0.12, paid: 0, status: 'unpaid', dueDate: '2026-08-05' },
  ];
};
</script>

<template>
  <WorkspaceLayout title="Ngân sách thu chi" active-nav="budget">
    <main class="max-w-7xl mx-auto px-6 py-8">
      <!-- Top Alert Banner if Overrun -->
      <div v-if="isOverrun" class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3">
          <AlertTriangle class="w-5 h-5 text-rose-600 shrink-0" />
          <div class="text-xs">
            <span class="font-bold text-sm">Cảnh báo vỡ ngân sách!</span>
            <p>Tổng chi phí thực tế đã vượt trần quy định <strong>{{ formatVND(totalActual - maxBudget) }}</strong>. Hãy xem xét tinh chỉnh lại các khoản chi chưa thanh toán.</p>
          </div>
        </div>
        <kbd class="px-2 py-1 bg-white border border-rose-200 rounded text-[10px] font-mono text-rose-800">Cmd + K AI Fix</kbd>
      </div>

      <!-- Financial Metrics Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Trần Ngân Sách</span>
          <div class="text-xl font-bold text-slate-900 mt-1">{{ formatVND(maxBudget) }}</div>
          <span class="text-[10px] text-slate-400">Thiết lập ban đầu</span>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Tổng Chi Phí Thực Tế</span>
          <div class="text-xl font-bold mt-1" :class="isOverrun ? 'text-rose-600' : 'text-slate-900'">
            {{ formatVND(totalActual) }}
          </div>
          <span class="text-[10px] text-emerald-600 font-medium">Dự kiến: {{ formatVND(totalEstimated) }}</span>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Đã Thanh Toán (Cọc)</span>
          <div class="text-xl font-bold text-emerald-700 mt-1">{{ formatVND(totalPaid) }}</div>
          <span class="text-[10px] text-slate-400">Đã giải ngân</span>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Còn Phải Thanh Toán</span>
          <div class="text-xl font-bold text-amber-700 mt-1">{{ formatVND(totalPending) }}</div>
          <span class="text-[10px] text-amber-800 font-medium">Cần chuẩn bị dòng tiền</span>
        </div>
      </div>

      <!-- Cash Flow Table Section -->
      <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
          <div>
            <h2 class="text-base font-bold text-slate-900">Danh Mục Chi Phí & Dòng Tiền</h2>
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
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-medium text-xs hover:bg-slate-800 transition flex items-center gap-1.5 cursor-pointer shadow-2xs">
              <Plus class="w-4 h-4" /> Thêm Khoản Chi
            </button>
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
              <td class="p-4 text-slate-600">{{ formatVND(item.estimated) }}</td>
              <td class="p-4 font-semibold text-slate-900">{{ formatVND(item.actual) }}</td>
              <td class="p-4 font-semibold text-emerald-700">{{ formatVND(item.paid) }}</td>
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
