<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { 
  Heart, 
  Users, 
  Plus, 
  CheckCircle2, 
  UserPlus, 
  Phone, 
  Tag, 
  Sparkles,
  Utensils
} from 'lucide-vue-next';

interface WorkspaceInfo {
  name?: string;
  groom_name?: string;
  bride_name?: string;
  wedding_date?: string;
  wedding_location?: string;
}

interface GuestItem {
  id: string;
  name: string;
  group: string;
  belongs_to?: string;
  notes?: string;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  recentGuests?: GuestItem[];
  shareToken: string;
}>();

const inputName = ref('');
const inputPhone = ref('');
const inputGroup = ref('Nhà Trai (Họ Hàng)');
const inputBelongsTo = ref('Chú Rể');
const inputAddedBy = ref('');
const inputDiet = ref('Bình thường');
const inputPax = ref(1);

const isSubmitting = ref(false);
const showSuccessToast = ref(false);
const submittedGuestName = ref('');

const localRecentGuests = ref<GuestItem[]>(props.recentGuests || []);

const groupsList = [
  'Nhà Trai (Họ Hàng)',
  'Nhà Gái (Họ Hàng)',
  'Bạn Chú Rể',
  'Bạn Cô Dâu',
  'Đồng Nghiệp',
  'Họ Hàng / Người Thân'
];

const belongsToList = [
  'Chú Rể',
  'Cô Dâu',
  'Bố Chú Rể',
  'Mẹ Chú Rể',
  'Bố Cô Dâu',
  'Mẹ Cô Dâu',
  'Chung Dâu Rể'
];

const handleSubmit = async () => {
  if (!inputName.value.trim()) return;
  isSubmitting.value = true;

  try {
    const res = await fetch(`/wedding/share-guest-list/${props.shareToken}/add`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        name: inputName.value,
        phone: inputPhone.value,
        group: inputGroup.value,
        belongs_to: inputBelongsTo.value,
        added_by: inputAddedBy.value,
        dietary_preference: inputDiet.value,
        estimated_count: inputPax.value,
      }),
    });

    const data = await res.json();
    if (data.success) {
      submittedGuestName.value = inputName.value;
      showSuccessToast.value = true;
      if (data.guest) {
        localRecentGuests.value.unshift(data.guest);
      }
      // Reset form
      inputName.value = '';
      inputPhone.value = '';
    }
  } catch (e) {
    console.error('Error adding shared guest:', e);
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <Head title="Đóng Góp Khách Mời Đám Cưới" />

  <div class="min-h-screen bg-[#FAF8F5] font-sans text-slate-900 pb-16">
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-rose-950 via-[#881337] to-rose-900 text-white py-12 px-6 shadow-xl relative overflow-hidden text-center">
      <div class="max-w-2xl mx-auto space-y-3 relative z-10">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-amber-300 text-[11px] font-bold uppercase tracking-widest">
          <Heart class="w-3.5 h-3.5 text-rose-300 fill-rose-300" />
          Sổ Tay Khách Mời Đám Cưới
        </div>
        
        <h1 class="text-2xl md:text-4xl font-serif font-extrabold text-white tracking-tight">
          {{ workspace?.groom_name || 'Quốc Trung' }} & {{ workspace?.bride_name || 'Hồng Vân' }}
        </h1>

        <p class="text-xs md:text-sm text-rose-100 font-medium">
          Kính mời Ba Mẹ hai bên, Họ Hàng & Bạn Bè hỗ trợ đóng góp thông tin khách mời để gia đình chuẩn bị chu đáo nhất.
        </p>

        <div class="pt-2 text-[11px] text-amber-200/90 font-mono flex items-center justify-center gap-3">
          <span>📅 {{ workspace?.wedding_date || '24/10/2026' }}</span>
          <span>·</span>
          <span>📍 {{ workspace?.wedding_location || 'TP. Hồ Chí Minh' }}</span>
        </div>
      </div>
    </header>

    <!-- Main Content Form Box -->
    <main class="max-w-xl mx-auto px-4 -mt-6 relative z-20 space-y-6">
      
      <!-- Success Notification Banner -->
      <div v-if="showSuccessToast" class="p-4 rounded-2xl bg-emerald-900 text-white shadow-xl flex items-center justify-between animate-fade-in border border-emerald-700">
        <div class="flex items-center gap-3">
          <CheckCircle2 class="w-5 h-5 text-emerald-300 shrink-0" />
          <div class="text-xs">
            <strong class="text-sm block">Đã thêm thành công!</strong>
            <span>Khách mời <strong>{{ submittedGuestName }}</strong> đã được lưu vào danh sách tiệc cưới.</span>
          </div>
        </div>
        <button @click="showSuccessToast = false" class="text-xs font-bold underline px-2 cursor-pointer">Đóng</button>
      </div>

      <!-- Guest Input Card -->
      <div class="p-6 md:p-8 rounded-3xl bg-white border border-rose-100 shadow-xl space-y-5">
        <div class="border-b border-rose-100 pb-3 flex items-center gap-2">
          <UserPlus class="w-5 h-5 text-[#881337]" />
          <h2 class="font-serif font-bold text-slate-900 text-base">Thêm Khách Mời Mới</h2>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4 text-xs">
          <!-- Full Name Input -->
          <div class="space-y-1">
            <label class="block font-bold text-slate-700">Họ & Tên Khách Mời <span class="text-rose-600">*</span></label>
            <input 
              v-model="inputName"
              type="text"
              required
              placeholder="VD: Bác Hai, Anh Hoàng Nguyễn, Chị Mai..."
              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 font-bold text-sm text-slate-900 outline-none"
            />
          </div>

          <!-- Belongs To (Khách của ai) -->
          <div class="space-y-1">
            <label class="block font-bold text-slate-700">Khách Thuộc Sở Hữu Của Ai? <span class="text-rose-600">*</span></label>
            <select 
              v-model="inputBelongsTo"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 font-extrabold text-xs text-slate-900 outline-none bg-white"
            >
              <option v-for="b in belongsToList" :key="b" :value="b">👤 Khách của: {{ b }}</option>
            </select>
          </div>

          <!-- Group Select -->
          <div class="space-y-1">
            <label class="block font-bold text-slate-700">Nhóm / Mối Quan Hệ <span class="text-rose-600">*</span></label>
            <select 
              v-model="inputGroup"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#881337] focus:ring-2 focus:ring-rose-100 font-bold text-xs text-slate-900 outline-none bg-white"
            >
              <option v-for="g in groupsList" :key="g" :value="g">{{ g }}</option>
            </select>
          </div>

          <!-- Phone Number & Pax Grid -->
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block font-bold text-slate-700">Số Điện Thoại</label>
              <input 
                v-model="inputPhone"
                type="tel"
                placeholder="0901234567"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#881337] font-mono text-xs text-slate-900 outline-none"
              />
            </div>
            <div class="space-y-1">
              <label class="block font-bold text-slate-700">Số Người Đi Cùng</label>
              <input 
                v-model.number="inputPax"
                type="number"
                min="1"
                max="10"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#881337] font-mono font-bold text-xs text-slate-900 outline-none"
              />
            </div>
          </div>

          <!-- Added By Input -->
          <div class="space-y-1">
            <label class="block font-bold text-slate-700">Người Nhập Khai Báo Thông Tin</label>
            <input 
              v-model="inputAddedBy"
              type="text"
              placeholder="VD: Mẹ chú rể, Mẹ cô dâu, Bạn chú rể..."
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#881337] text-xs text-slate-900 outline-none"
            />
          </div>

          <!-- Dietary Preference -->
          <div class="space-y-1">
            <label class="block font-bold text-slate-700">Ghi Chú Khẩu Vị / Ăn Chay</label>
            <select 
              v-model="inputDiet"
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#881337] text-xs text-slate-900 outline-none bg-white"
            >
              <option value="Bình thường">Bình thường (Ăn mặn)</option>
              <option value="Ăn chay">Ăn chay</option>
              <option value="Không ăn cay">Không ăn cay</option>
              <option value="Dị ứng hải sản">Dị ứng hải sản</option>
            </select>
          </div>

          <!-- Submit Button -->
          <div class="pt-2">
            <button 
              type="submit"
              :disabled="isSubmitting"
              class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-[#881337] to-rose-900 hover:from-[#70102d] hover:to-rose-950 text-white font-extrabold text-xs shadow-lg flex items-center justify-center gap-2 cursor-pointer transition active:scale-95 disabled:opacity-50"
            >
              <Sparkles class="w-4 h-4 text-amber-300 animate-pulse" />
              <span>GỬI DỮ LIỆU KHÁCH MỜI VÀO DANH SÁCH</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Recent Submitted Guests List Preview -->
      <div v-if="localRecentGuests.length > 0" class="p-6 rounded-3xl bg-white border border-rose-100 shadow-md space-y-3">
        <h3 class="font-serif font-bold text-slate-900 text-sm flex items-center gap-2 border-b border-rose-50 pb-2">
          <Users class="w-4 h-4 text-[#881337]" />
          Khách Mời Đã Thêm Mới Đây ({{ localRecentGuests.length }})
        </h3>

        <div class="space-y-2">
          <div 
            v-for="g in localRecentGuests" 
            :key="g.id"
            class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs"
          >
            <div>
              <strong class="text-slate-900 block font-bold">{{ g.name }}</strong>
              <span class="text-[10px] text-slate-500">Khách của: {{ g.belongs_to || 'Gia đình' }} • {{ g.group }}</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Đã lưu</span>
          </div>
        </div>
      </div>

    </main>
  </div>
</template>
