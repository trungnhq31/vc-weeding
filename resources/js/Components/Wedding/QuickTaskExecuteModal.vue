<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { 
  Sparkles, 
  X, 
  CheckCircle2, 
  DollarSign, 
  Users, 
  Building2,
  ArrowRight,
  UserCheck,
  Calendar,
  MapPin,
  Heart
} from 'lucide-vue-next';

interface Task {
  id: string;
  title: string;
  vendor_info?: string;
  estimated_cost?: number;
  actual_cost?: number;
  notes?: string;
}

interface WorkspaceContext {
  couple_name: string;
  wedding_date: string;
  wedding_location: string;
  budget_cap: number;
  estimated_guests: number;
  venue_name: string;
}

interface AiRecommendation {
  title: string;
  description: string;
  suggestedInput: Record<string, any>;
}

interface AiDataPayload {
  workspaceContext: WorkspaceContext;
  aiRecommendation: AiRecommendation;
}

const props = defineProps<{
  show: boolean;
  task: Task | null;
  aiData: AiDataPayload | null;
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

watch(() => props.aiData, (newVal) => {
  if (newVal?.aiRecommendation?.suggestedInput) {
    const s = newVal.aiRecommendation.suggestedInput;
    if (s.budget_cap) inputBudgetCap.value = s.budget_cap;
    if (s.estimated_guests) inputGuestCount.value = s.estimated_guests;
    if (s.template_slug) inputTemplateSlug.value = s.template_slug;
    if (s.vendor_name) inputVendorName.value = s.vendor_name;
    if (s.actual_cost) inputActualCost.value = s.actual_cost;
  }
}, { immediate: true });

const categoryType = computed(() => {
  if (!props.task) return 'generic';
  const titleLower = props.task.title.toLowerCase();
  if (titleLower.includes('ngân sách') || titleLower.includes('chi phí')) return 'budget';
  if (titleLower.includes('khách mời') || titleLower.includes('sơ đồ bàn')) return 'guests';
  if (titleLower.includes('thiệp') || titleLower.includes('mẫu thiệp') || titleLower.includes('decor') || titleLower.includes('tone màu')) return 'invitation';
  if (titleLower.includes('sảnh tiệc') || titleLower.includes('nhà hàng') || titleLower.includes('chụp ảnh') || titleLower.includes('nhẫn') || titleLower.includes('vendor')) return 'vendor';
  return 'generic';
});

const formatCurrency = (val: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
};

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
    <div class="w-full max-w-xl bg-white rounded-3xl border border-rose-100 shadow-2xl overflow-hidden font-sans space-y-0 max-h-[90vh] flex flex-col">
      
      <!-- Modal Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-rose-900 via-rose-950 to-slate-900 text-white flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center shadow-xs">
            <Sparkles class="w-5 h-5 text-amber-300 animate-pulse" />
          </div>
          <div>
            <h3 class="font-serif font-extrabold text-white text-base tracking-wide">✨ AI Smart Suggest — Gợi Ý Thông Minh</h3>
            <p class="text-[11px] text-rose-200 font-medium">Tự động tổng hợp hồ sơ Dâu Rể & Đề xuất bước thực hiện</p>
          </div>
        </div>
        <button @click="emit('close')" class="w-8 h-8 rounded-full bg-white/10 text-rose-200 flex items-center justify-center hover:bg-white/20 transition cursor-pointer">
          <X class="w-4 h-4 text-white" />
        </button>
      </div>

      <!-- Modal Body (Scrollable) -->
      <div class="p-6 space-y-5 overflow-y-auto flex-1">
        
        <!-- 1. Couple Profile Context Panel -->
        <div v-if="aiData?.workspaceContext" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
          <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-950 flex items-center gap-1.5">
              <Heart class="w-3.5 h-3.5 text-rose-600 fill-rose-600" />
              Hồ Sơ & Bối Cảnh Dâu Rể (Workspace Context)
            </span>
            <span class="text-[10px] font-bold bg-rose-100 text-rose-800 px-2 py-0.5 rounded-full">Grounded AI Sync</span>
          </div>

          <div class="grid grid-cols-2 gap-2 text-xs font-medium text-slate-700">
            <div class="flex items-center gap-1.5 truncate">
              <UserCheck class="w-3.5 h-3.5 text-slate-400 shrink-0" />
              <span class="text-slate-500">Cặp đôi:</span>
              <strong class="text-slate-900 truncate">{{ aiData.workspaceContext.couple_name }}</strong>
            </div>
            <div class="flex items-center gap-1.5">
              <Calendar class="w-3.5 h-3.5 text-slate-400 shrink-0" />
              <span class="text-slate-500">Ngày cưới:</span>
              <strong class="text-slate-900 font-mono">{{ aiData.workspaceContext.wedding_date }}</strong>
            </div>
            <div class="flex items-center gap-1.5">
              <MapPin class="w-3.5 h-3.5 text-slate-400 shrink-0" />
              <span class="text-slate-500">Địa điểm:</span>
              <strong class="text-slate-900">{{ aiData.workspaceContext.wedding_location }}</strong>
            </div>
            <div class="flex items-center gap-1.5">
              <Users class="w-3.5 h-3.5 text-slate-400 shrink-0" />
              <span class="text-slate-500">Khách mời:</span>
              <strong class="text-slate-900 font-mono">{{ aiData.workspaceContext.estimated_guests }} khách</strong>
            </div>
            <div class="flex items-center gap-1.5 col-span-2">
              <DollarSign class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
              <span class="text-slate-500">Ngân sách trần:</span>
              <strong class="text-emerald-700 font-mono font-bold">{{ formatCurrency(aiData.workspaceContext.budget_cap) }}</strong>
            </div>
          </div>
        </div>

        <!-- 2. AI Recommendation Card -->
        <div v-if="aiData?.aiRecommendation" class="p-4 rounded-2xl bg-gradient-to-r from-rose-50 to-amber-50/60 border border-rose-200/90 space-y-2">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
            <h4 class="font-serif font-bold text-rose-950 text-xs uppercase tracking-wide">
              {{ aiData.aiRecommendation.title }}
            </h4>
          </div>
          <p class="text-xs text-slate-700 leading-relaxed font-medium">
            {{ aiData.aiRecommendation.description }}
          </p>
        </div>

        <!-- 3. Target Task Highlight -->
        <div class="p-3.5 rounded-xl bg-slate-100/80 border border-slate-200 space-y-1">
          <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block">Bước công việc đang chọn</span>
          <h4 class="font-serif font-bold text-slate-900 text-sm">
            {{ task.title }}
          </h4>
        </div>

        <!-- 4. Dynamic Category Form Inputs -->
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
        </div>

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
        </div>

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

      </div>

      <!-- Modal Footer Action -->
      <div class="p-5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
        <button 
          @click="emit('close')" 
          class="px-5 py-2.5 rounded-full text-xs font-bold text-slate-600 hover:bg-slate-200 transition cursor-pointer"
        >
          Đóng
        </button>
        <button 
          @click="handleConfirm"
          :disabled="isSubmitting"
          class="px-7 py-3.5 rounded-full bg-gradient-to-r from-[#881337] to-rose-900 hover:from-[#70102d] hover:to-rose-950 text-white font-extrabold text-xs shadow-lg flex items-center gap-2 cursor-pointer transition active:scale-95 disabled:opacity-50"
        >
          <Sparkles class="w-4 h-4 text-amber-300 animate-pulse" />
          <span>✨ ÁP DỤNG GỢI Ý AI & HOÀN THÀNH</span>
          <ArrowRight class="w-4 h-4 text-white" />
        </button>
      </div>

    </div>
  </div>
</template>
