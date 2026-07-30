<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

interface GroundedMetrics {
  workspace: { id: string; name: string; budget_cap: number; wedding_date?: string };
  budget: { total_actual: number; budget_cap: number; remaining_balance: number; is_overrun_alert: boolean; upcoming_payments_count: number };
  tasks: { total_tasks: number; completed_tasks: number; pending_tasks: number; progress_percentage: number; overdue_count: number; overdue_tasks: Array<any> };
  vendors: { total_contracts: number; total_paid: number; remaining_unpaid: number; vendors_count: number; unpaid_vendors_count: number; upcoming_due_vendors: Array<any> };
  guests: { total_guests: number; attending_guests: number; unseated_guests: number; total_tables: number; over_capacity_tables_count: number };
}

interface GroundedResponse {
  intent: string;
  metrics: GroundedMetrics;
  summary_text: string;
  insights: string[];
  recommendations: string[];
}

const props = defineProps<{
  isOpen: boolean;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
}>();

const queryInput = ref('');
const isLoading = ref(false);
const resultData = ref<GroundedResponse | null>(null);

const formatVnd = (num: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num || 0);
};

const executeQuery = async (queryText: string = 'overview') => {
  isLoading.value = true;
  queryInput.value = queryText === 'overview' ? '' : queryText;
  try {
    const res = await fetch('/wedding/ai-query', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '' },
      body: JSON.stringify({ query: queryText }),
    });
    const json = await res.json();
    if (json.success) {
      resultData.value = json.data;
    }
  } catch (err) {
    console.error('Grounded AI query failed', err);
  } finally {
    isLoading.value = false;
  }
};

const handleKeyDown = (e: KeyboardEvent) => {
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault();
    if (props.isOpen) {
      emit('close');
    } else {
      executeQuery('overview');
    }
  }
  if (e.key === 'Escape' && props.isOpen) {
    emit('close');
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
  if (props.isOpen && !resultData.value) {
    executeQuery('overview');
  }
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex justify-end bg-slate-900/40 backdrop-blur-xs transition-opacity">
      <!-- Backdrop Click to Close -->
      <div class="fixed inset-0" @click="emit('close')"></div>

      <!-- Drawer Content -->
      <div class="relative z-10 w-full max-w-xl bg-slate-50 h-full shadow-2xl flex flex-col border-l border-slate-200">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 bg-white flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
              AI
            </div>
            <div>
              <h2 class="text-sm font-semibold text-slate-900">Grounded AI Assistant</h2>
              <p class="text-xs text-slate-500">Zero Hallucination • Truy vấn từ dữ liệu thực tế</p>
            </div>
          </div>
          <button @click="emit('close')" class="p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 text-xs">
            Esc
          </button>
        </div>

        <!-- Body / Search Input & Preset Quick Chips -->
        <div class="p-6 flex-1 overflow-y-auto space-y-6">
          <!-- Search Input -->
          <form @submit.prevent="executeQuery(queryInput)" class="flex gap-2">
            <input
              v-model="queryInput"
              type="text"
              placeholder="Nhập câu hỏi (ví dụ: dòng tiền, task quá hạn, nợ vendor)..."
              class="flex-1 px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"
            />
            <button
              type="submit"
              :disabled="isLoading"
              class="px-4 py-2 text-xs font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 disabled:opacity-50"
            >
              {{ isLoading ? 'Đang phân tích...' : 'Hỏi AI' }}
            </button>
          </form>

          <!-- Quick Preset Chips -->
          <div class="flex flex-wrap gap-2">
            <button
              @click="executeQuery('ngân sách')"
              class="px-2.5 py-1 text-xs font-medium bg-white text-slate-700 border border-slate-200 rounded-md hover:bg-slate-100 transition"
            >
              💰 Dòng tiền & Ngân sách
            </button>
            <button
              @click="executeQuery('công việc quá hạn')"
              class="px-2.5 py-1 text-xs font-medium bg-white text-slate-700 border border-slate-200 rounded-md hover:bg-slate-100 transition"
            >
              ⏱️ Tiến độ & Overdue Tasks
            </button>
            <button
              @click="executeQuery('nhà cung cấp')"
              class="px-2.5 py-1 text-xs font-medium bg-white text-slate-700 border border-slate-200 rounded-md hover:bg-slate-100 transition"
            >
              🤝 Hạn thanh toán Vendors
            </button>
            <button
              @click="executeQuery('khách mời bàn tiệc')"
              class="px-2.5 py-1 text-xs font-medium bg-white text-slate-700 border border-slate-200 rounded-md hover:bg-slate-100 transition"
            >
              🪑 Khách mời & Sơ đồ bàn
            </button>
          </div>

          <!-- Loading State -->
          <div v-if="isLoading" class="py-12 text-center text-slate-500 text-sm">
            <div class="inline-block animate-spin w-5 h-5 border-2 border-slate-400 border-t-transparent rounded-full mb-2"></div>
            <p>Đang truy vấn dữ liệu thực từ Workspace...</p>
          </div>

          <!-- Grounded Output Cards -->
          <div v-else-if="resultData" class="space-y-4">
            <!-- Summary Callout -->
            <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
              <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">TỔNG QUAN</div>
              <p class="text-sm font-medium text-slate-800 leading-relaxed">{{ resultData.summary_text }}</p>
            </div>

            <!-- Metrics Snapshot Table/Grid -->
            <div class="grid grid-cols-2 gap-3">
              <div class="p-3 bg-white border border-slate-200 rounded-lg">
                <div class="text-xs text-slate-500">Đã chi thực tế</div>
                <div class="text-sm font-semibold text-slate-900 mt-0.5">
                  {{ formatVnd(resultData.metrics.budget.total_actual) }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                  Trần: {{ formatVnd(resultData.metrics.budget.budget_cap) }}
                </div>
              </div>

              <div class="p-3 bg-white border border-slate-200 rounded-lg">
                <div class="text-xs text-slate-500">Tiến độ Tasks</div>
                <div class="text-sm font-semibold text-slate-900 mt-0.5">
                  {{ resultData.metrics.tasks.progress_percentage }}% ({{ resultData.metrics.tasks.completed_tasks }}/{{ resultData.metrics.tasks.total_tasks }})
                </div>
                <div class="text-[11px] text-rose-600 mt-1" v-if="resultData.metrics.tasks.overdue_count > 0">
                  {{ resultData.metrics.tasks.overdue_count }} task quá hạn
                </div>
                <div class="text-[11px] text-slate-400 mt-1" v-else>
                  Không có task quá hạn
                </div>
              </div>

              <div class="p-3 bg-white border border-slate-200 rounded-lg">
                <div class="text-xs text-slate-500">Nợ Hợp đồng Vendors</div>
                <div class="text-sm font-semibold text-slate-900 mt-0.5">
                  {{ formatVnd(resultData.metrics.vendors.remaining_unpaid) }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                  {{ resultData.metrics.vendors.unpaid_vendors_count }} NCC chưa hoàn tất
                </div>
              </div>

              <div class="p-3 bg-white border border-slate-200 rounded-lg">
                <div class="text-xs text-slate-500">Khách mời & Bàn tiệc</div>
                <div class="text-sm font-semibold text-slate-900 mt-0.5">
                  {{ resultData.metrics.guests.total_guests }} khách ({{ resultData.metrics.guests.attending_guests }} tham dự)
                </div>
                <div class="text-[11px] text-amber-600 mt-1" v-if="resultData.metrics.guests.unseated_guests > 0">
                  {{ resultData.metrics.guests.unseated_guests }} khách chưa xếp bàn
                </div>
                <div class="text-[11px] text-slate-400 mt-1" v-else>
                  Đã xếp bàn hoàn tất
                </div>
              </div>
            </div>

            <!-- Insights Section -->
            <div v-if="resultData.insights.length > 0" class="p-4 bg-white border border-slate-200 rounded-xl space-y-2">
              <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">PHÂN TÍCH CHI TIẾT</div>
              <ul class="space-y-1.5 text-xs text-slate-700">
                <li v-for="(insight, idx) in resultData.insights" :key="idx" class="flex items-start gap-2">
                  <span class="text-slate-400">•</span>
                  <span>{{ insight }}</span>
                </li>
              </ul>
            </div>

            <!-- Recommendations Section -->
            <div v-if="resultData.recommendations.length > 0" class="p-4 bg-slate-900 text-white rounded-xl space-y-2">
              <div class="text-xs font-semibold uppercase tracking-wider text-rose-300">KHUYẾN NGHỊ HÀNH ĐỘNG</div>
              <ul class="space-y-1.5 text-xs text-slate-200">
                <li v-for="(rec, idx) in resultData.recommendations" :key="idx" class="flex items-start gap-2">
                  <span class="text-rose-400">➜</span>
                  <span>{{ rec }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3 border-t border-slate-200 bg-white text-xs text-slate-400 flex items-center justify-between">
          <span>Grounded Data Engine • Zero Hallucination</span>
          <span>Bấm <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded font-mono text-[10px]">Cmd + K</kbd> để bật/tắt</span>
        </div>
      </div>
    </div>
  </Teleport>
</template>
