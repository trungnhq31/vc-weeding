<script setup lang="ts">
import { ref, computed } from 'vue';
import { 
  Zap, 
  X, 
  CheckCircle2, 
  DollarSign, 
  Users, 
  Sparkles, 
  Building2,
  ArrowRight
} from 'lucide-vue-next';

interface Task {
  id: string;
  title: string;
  vendor_info?: string;
  estimated_cost?: number;
  actual_cost?: number;
  notes?: string;
}

const props = defineProps<{
  show: boolean;
  task: Task | null;
  workspaceBudgetCap?: number;
  estimatedGuests?: number;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
  (e: 'execute', payload: { taskId: string; input: Record<string, any> }): void;
}>();

const isSubmitting = ref(false);

// Form Inputs with Smart Defaults
const inputBudgetCap = ref<number>(props.workspaceBudgetCap || 250000000);
const inputGuestCount = ref<number>(props.estimatedGuests || 200);
const inputTemplateSlug = ref<string>('royal-gold');
const inputVendorName = ref<string>('');
const inputActualCost = ref<number>(0);

const categoryType = computed(() => {
  if (!props.task) return 'generic';
  const titleLower = props.task.title.toLowerCase();
  if (titleLower.includes('ngân sách') || titleLower.includes('chi phí')) return 'budget';
  if (titleLower.includes('khách mời') || titleLower.includes('sơ đồ bàn')) return 'guests';
  if (titleLower.includes('thiệp') || titleLower.includes('mẫu thiệp') || titleLower.includes('decor') || titleLower.includes('tone màu')) return 'invitation';
  if (titleLower.includes('sảnh tiệc') || titleLower.includes('nhà hàng') || titleLower.includes('chụp ảnh') || titleLower.includes('nhẫn') || titleLower.includes('vendor')) return 'vendor';
  return 'generic';
});

const handleConfirm = () => {
  if (!props.task) return;
  isSubmitting.value = true;

  const payload: Record<string, any> = {};
  if (categoryType.value === 'budget') {
    payload.budget_cap = inputBudgetCap.value;
  } else if (categoryType.value === 'guests') {
    payload.estimated_guests = inputGuestCount.value;
  } else if (categoryType.value === 'invitation') {
    payload.template_slug = inputTemplateSlug.value;
  } else if (categoryType.value === 'vendor') {
    payload.vendor_name = inputVendorName.value || props.task.vendor_info || 'Nhà Cung Cấp Tiệc Cưới';
    payload.actual_cost = inputActualCost.value || props.task.estimated_cost || 35000000;
  }

  emit('execute', {
    taskId: props.task.id,
    input: payload,
  });

  isSubmitting.value = false;
};
</script>

<template>
  <div v-if="show && task" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
    <div class="w-full max-w-lg bg-white rounded-3xl border border-rose-100 shadow-2xl overflow-hidden font-sans space-y-0">
      
      <!-- Modal Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-rose-50 via-amber-50/50 to-white border-b border-rose-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-xl bg-[#881337] text-white flex items-center justify-center shadow-xs">
            <Zap class="w-4 h-4 text-amber-300" />
          </div>
          <div>
            <h3 class="font-serif font-extrabold text-slate-900 text-base">Thực Hiện 1-Click Qua Hệ Thống</h3>
            <p class="text-[11px] text-slate-500 font-medium">Tự động hóa dữ liệu SaaS & Hoàn thành 100%</p>
          </div>
        </div>
        <button @click="emit('close')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition cursor-pointer">
          <X class="w-4 h-4" />
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6 space-y-5">
        <!-- Selected Task Badge -->
        <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 space-y-1">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-[#881337] text-white">
            Công việc đang chọn
          </span>
          <h4 class="font-serif font-bold text-slate-900 text-sm leading-snug">
            {{ task.title }}
          </h4>
        </div>

        <!-- Dynamic Category Specific Inputs -->
        <!-- 1. Budget Category -->
        <div v-if="categoryType === 'budget'" class="space-y-3">
          <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
            <DollarSign class="w-4 h-4 text-emerald-600" /> Ngân Sách Trần Đám Cưới (VND)
          </label>
          <input 
            v-model.number="inputBudgetCap"
            type="number"
            step="10000000"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 font-mono font-bold text-lg text-slate-900 outline-none"
            placeholder="250000000"
          />
          <p class="text-[11px] text-slate-500">Hệ thống sẽ cập nhật ngân sách trần và tính toán tỷ lệ Cashflow tự động.</p>
        </div>

        <!-- 2. Guests Category -->
        <div v-else-if="categoryType === 'guests'" class="space-y-3">
          <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
            <Users class="w-4 h-4 text-rose-600" /> Số Lượng Khách Mời Dự Kiến
          </label>
          <input 
            v-model.number="inputGuestCount"
            type="number"
            step="10"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 font-mono font-bold text-lg text-slate-900 outline-none"
            placeholder="200"
          />
          <p class="text-[11px] text-slate-500">Khởi tạo ngay danh sách khách mời VIP và phân bố sơ đồ bàn tiệc.</p>
        </div>

        <!-- 3. Invitation Category -->
        <div v-else-if="categoryType === 'invitation'" class="space-y-3">
          <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
            <Sparkles class="w-4 h-4 text-amber-600" /> Chọn Mẫu Thiệp 3D Độc Bản
          </label>
          <select 
            v-model="inputTemplateSlug"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 font-bold text-sm text-slate-900 outline-none bg-white"
          >
            <option value="royal-gold">👑 Royal Gold (Hoàng Gia Dát Vàng 24K)</option>
            <option value="romantic-pastel">🌸 Romantic Pastel (Hồng Phấn Mở Sáp Nến)</option>
            <option value="modern-slate">📰 Modern Slate (Tạp Chí Editorial 2 Cột)</option>
            <option value="botanical-sage">🌿 Botanical Sage (Xanh Thảo Mộc Khung Vòm)</option>
          </select>
        </div>

        <!-- 4. Vendor Category -->
        <div v-else-if="categoryType === 'vendor'" class="space-y-3">
          <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
              <Building2 class="w-4 h-4 text-sky-600" /> Tên Nhà Cung Cấp / Đối Tác
            </label>
            <input 
              v-model="inputVendorName"
              type="text"
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 text-sm font-bold text-slate-900 outline-none"
              :placeholder="task.vendor_info || 'Trung Tâm Tiệc Cưới Luxury Palace'"
            />
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-700">Chi Phí / Tiền Cọc Đợt 1 (VND)</label>
            <input 
              v-model.number="inputActualCost"
              type="number"
              step="1000000"
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 font-mono font-bold text-base text-slate-900 outline-none"
              :placeholder="String(task.estimated_cost || 35000000)"
            />
          </div>
        </div>

        <!-- Generic Quick Confirmation -->
        <div v-else class="text-xs text-slate-600 leading-relaxed font-medium bg-slate-50 p-4 rounded-xl border border-slate-200">
          Xác nhận thực hiện công việc này qua hệ thống Eloria OS. Tất cả các công việc phụ (subtasks) sẽ tự động được đánh dấu hoàn thành 100%.
        </div>
      </div>

      <!-- Modal Footer Action -->
      <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
        <button 
          @click="emit('close')" 
          class="px-5 py-2.5 rounded-full text-xs font-bold text-slate-600 hover:bg-slate-200 transition cursor-pointer"
        >
          Hủy bỏ
        </button>
        <button 
          @click="handleConfirm"
          :disabled="isSubmitting"
          class="px-7 py-3 rounded-full bg-[#881337] hover:bg-[#70102d] text-white font-extrabold text-xs shadow-lg flex items-center gap-2 cursor-pointer transition active:scale-95 disabled:opacity-50"
        >
          <span>XÁC NHẬN & HOÀN THÀNH 1-CLICK</span>
          <ArrowRight class="w-4 h-4 text-white" />
        </button>
      </div>

    </div>
  </div>
</template>
