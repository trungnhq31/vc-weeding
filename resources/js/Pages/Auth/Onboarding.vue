<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { 
  Sparkles, 
  Heart, 
  Calendar, 
  DollarSign, 
  Users, 
  Palette, 
  ArrowRight, 
  ArrowLeft,
  CheckCircle2,
  Check
} from 'lucide-vue-next';

const currentStep = ref(1);

const form = useForm({
  groom_name: '',
  bride_name: '',
  wedding_date: '2026-10-24',
  budget_cap: 250000000,
  estimated_guests: 200,
  invitation_template: 'romantic-pastel',
});

const nextStep = () => {
  if (currentStep.value < 4) {
    currentStep.value++;
  } else {
    form.post('/onboarding');
  }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
  }
};
</script>

<template>
  <Head title="Thiết Lập Kế Hoạch Đám Cưới — Eloria OS" />

  <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans flex flex-col justify-between selection:bg-rose-100 selection:text-rose-900">
    <!-- Header Step Progress -->
    <header class="py-6 px-8 bg-white border-b border-rose-100 sticky top-0 z-40">
      <div class="max-w-4xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
          <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-8 w-auto rounded-lg border border-rose-200" />
          <span class="font-serif font-bold text-lg text-slate-900">Eloria <span class="text-rose-600 font-sans text-xs px-2 py-0.5 rounded-full bg-rose-100">OS</span></span>
        </div>

        <!-- Step Indicator Pills -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
          <span :class="currentStep >= 1 ? 'text-rose-800 font-bold' : ''">1. Dâu Rể</span>
          <span>→</span>
          <span :class="currentStep >= 2 ? 'text-rose-800 font-bold' : ''">2. Ngày Cưới</span>
          <span>→</span>
          <span :class="currentStep >= 3 ? 'text-rose-800 font-bold' : ''">3. Ngân Sách</span>
          <span>→</span>
          <span :class="currentStep >= 4 ? 'text-rose-800 font-bold' : ''">4. Mẫu Thiệp</span>
        </div>
      </div>
    </header>

    <!-- Main Wizard Card Container -->
    <main class="flex-1 flex items-center justify-center p-6">
      <div class="w-full max-w-2xl bg-white p-8 md:p-12 rounded-3xl border border-rose-100 shadow-sm">
        
        <!-- STEP 1: Groom & Bride Names -->
        <div v-if="currentStep === 1" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 1 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Tên Của Hai Bạn Lỡ Nhịp</h2>
            <p class="text-xs text-slate-500 mt-1">Thông tin này sẽ được khắc ghi trên Thiệp cưới điện tử & Workspace</p>
          </div>

          <div class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tên Chú Rể</label>
              <input v-model="form.groom_name" type="text" placeholder="Nguyễn Văn Anh" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tên Cô Dâu</label>
              <input v-model="form.bride_name" type="text" placeholder="Trần Thị Bích" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
          </div>
        </div>

        <!-- STEP 2: Wedding Date -->
        <div v-else-if="currentStep === 2" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 2 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Ngày Trọng Đại Của Đám Cưới</h2>
            <p class="text-xs text-slate-500 mt-1">Hệ thống sẽ tự động tính toán đồng hồ đếm ngược và nhắc nhở công việc</p>
          </div>

          <div class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Ngày Tổ Chức Tiệc Cưới</label>
              <input v-model="form.wedding_date" type="date" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
          </div>
        </div>

        <!-- STEP 3: Budget & Guest Count -->
        <div v-else-if="currentStep === 3" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 3 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Trần Ngân Sách & Số Khách Mời</h2>
            <p class="text-xs text-slate-500 mt-1">Cơ sở tính toán tự động cảnh báo vỡ ngân sách và lập sơ đồ bàn tiệc</p>
          </div>

          <div class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Trần Ngân Sách Dự Kiến (VND)</label>
              <input v-model="form.budget_cap" type="number" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Số Lượng Khách Mời Dự Kiến (Người)</label>
              <input v-model="form.estimated_guests" type="number" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
          </div>
        </div>

        <!-- STEP 4: Invitation Template -->
        <div v-else-if="currentStep === 4" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 4 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Chọn Phong Cách Mẫu Thiệp Độc Bản</h2>
            <p class="text-xs text-slate-500 mt-1">Bạn có thể dễ dàng thay đổi mẫu thiệp sau trong phần Customizer</p>
          </div>

          <div class="grid grid-cols-2 gap-4 pt-2">
            <button 
              @click="form.invitation_template = 'romantic-pastel'"
              type="button"
              class="p-4 rounded-2xl border text-left transition-all cursor-pointer"
              :class="form.invitation_template === 'romantic-pastel' ? 'bg-rose-50 border-rose-400 font-bold text-rose-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
            >
              <div class="text-xs font-bold">Romantic Pastel</div>
              <div class="text-[10px] text-slate-500 mt-1">Hồng phấn kem ấm ngọt ngào</div>
            </button>
            <button 
              @click="form.invitation_template = 'royal-gold'"
              type="button"
              class="p-4 rounded-2xl border text-left transition-all cursor-pointer"
              :class="form.invitation_template === 'royal-gold' ? 'bg-amber-50 border-amber-400 font-bold text-amber-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
            >
              <div class="text-xs font-bold">Royal Gold</div>
              <div class="text-[10px] text-slate-500 mt-1">Vàng sâm panh hoàng gia</div>
            </button>
            <button 
              @click="form.invitation_template = 'modern-slate'"
              type="button"
              class="p-4 rounded-2xl border text-left transition-all cursor-pointer"
              :class="form.invitation_template === 'modern-slate' ? 'bg-slate-100 border-slate-400 font-bold text-slate-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
            >
              <div class="text-xs font-bold">Modern Slate</div>
              <div class="text-[10px] text-slate-500 mt-1">Ghi sáng tối giản thanh lịch</div>
            </button>
            <button 
              @click="form.invitation_template = 'botanical-sage'"
              type="button"
              class="p-4 rounded-2xl border text-left transition-all cursor-pointer"
              :class="form.invitation_template === 'botanical-sage' ? 'bg-emerald-50 border-emerald-400 font-bold text-emerald-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
            >
              <div class="text-xs font-bold">Botanical Sage</div>
              <div class="text-[10px] text-slate-500 mt-1">Xanh lá thảo mộc tươi mát</div>
            </button>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
          <button 
            v-if="currentStep > 1" 
            @click="prevStep"
            type="button" 
            class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer"
          >
            <ArrowLeft class="w-4 h-4" /> Quay lại
          </button>
          <div v-else></div>

          <button 
            @click="nextStep"
            type="button"
            :disabled="form.processing"
            class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition flex items-center gap-1.5 shadow-md cursor-pointer"
          >
            <span>{{ currentStep === 4 ? 'Hoàn Tất & Vào Workspace' : 'Tiếp Theo' }}</span>
            <ArrowRight class="w-4 h-4 text-rose-400" />
          </button>
        </div>

      </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-400">
      © 2026 Eloria Wedding OS.
    </footer>
  </div>
</template>
