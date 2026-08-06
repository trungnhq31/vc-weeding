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
  Check,
  Building2,
  MapPin,
  Church,
  TreeSun,
  ShieldCheck
} from 'lucide-vue-next';

const currentStep = ref(1);

const form = useForm({
  groom_name: '',
  bride_name: '',
  wedding_date: '2026-10-24',
  budget_cap: 350000000,
  estimated_guests: 200,
  ceremony_type: 'traditional_south',
  wedding_vibe: 'pastel',
  region: 'hcm',
  wedding_location: 'TP. Hồ Chí Minh',
  venue_name: 'Trung tâm Tiệc cưới Center Palace',
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
  <Head title="Thiết Lập Kế Hoạch Cá Nhân Hóa — Eloria OS" />

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
          <span :class="currentStep >= 2 ? 'text-rose-800 font-bold' : ''">2. Nghi Lễ & Vibe</span>
          <span>→</span>
          <span :class="currentStep >= 3 ? 'text-rose-800 font-bold' : ''">3. Địa Điểm</span>
          <span>→</span>
          <span :class="currentStep >= 4 ? 'text-rose-800 font-bold' : ''">4. Ngân Sách</span>
        </div>
      </div>
    </header>

    <!-- Main Wizard Card Container -->
    <main class="flex-1 flex items-center justify-center p-6">
      <div class="w-full max-w-2xl bg-white p-8 md:p-12 rounded-3xl border border-rose-100 shadow-sm">
        
        <!-- STEP 1: Groom & Bride Names & Date -->
        <div v-if="currentStep === 1" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 1 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Tên Của Hai Bạn & Ngày Trọng Đại</h2>
            <p class="text-xs text-slate-500 mt-1">Thông tin này sẽ được khắc ghi trên Thiệp cưới điện tử & Workspace</p>
          </div>

          <div class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tên Chú Rể *</label>
              <input v-model="form.groom_name" type="text" placeholder="Nguyễn Hoàng Quốc Trung" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tên Cô Dâu *</label>
              <input v-model="form.bride_name" type="text" placeholder="Lê Thị Hồng Vân" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Ngày Tổ Chức Đám Cưới *</label>
              <input v-model="form.wedding_date" type="date" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
          </div>
        </div>

        <!-- STEP 2: Ceremony Type & Wedding Vibe -->
        <div v-else-if="currentStep === 2" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 2 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Chọn Nghi Lễ & Phong Cách Vibe</h2>
            <p class="text-xs text-slate-500 mt-1">Thuật toán sẽ tự động tạo mốc công việc & danh mục cọc riêng cho bạn</p>
          </div>

          <div class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-2">Hình Thức Nghi Lễ Cưới</label>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <button 
                  type="button"
                  @click="form.ceremony_type = 'traditional_south'"
                  class="p-4 rounded-2xl border text-left transition cursor-pointer flex items-start gap-3"
                  :class="form.ceremony_type === 'traditional_south' ? 'bg-rose-50 border-rose-500 ring-2 ring-rose-400' : 'bg-[#FAF8F5] border-slate-200 hover:bg-rose-50/50'"
                >
                  <span class="text-xl p-2 rounded-xl bg-white border border-rose-100">🏮</span>
                  <div>
                    <div class="text-xs font-bold text-slate-900">Gia Tiên Miền Nam</div>
                    <div class="text-[11px] text-slate-500">6 mâm quả tráp rồng phụng & rước dâu</div>
                  </div>
                </button>

                <button 
                  type="button"
                  @click="form.ceremony_type = 'catholic_church'"
                  class="p-4 rounded-2xl border text-left transition cursor-pointer flex items-start gap-3"
                  :class="form.ceremony_type === 'catholic_church' ? 'bg-rose-50 border-rose-500 ring-2 ring-rose-400' : 'bg-[#FAF8F5] border-slate-200 hover:bg-rose-50/50'"
                >
                  <span class="text-xl p-2 rounded-xl bg-white border border-rose-100">⛪</span>
                  <div>
                    <div class="text-xs font-bold text-slate-900">Lễ Nhà Thờ Công Giáo</div>
                    <div class="text-[11px] text-slate-500">Khóa giáo lý hôn nhân & ca đoàn</div>
                  </div>
                </button>

                <button 
                  type="button"
                  @click="form.ceremony_type = 'destination_outdoor'"
                  class="p-4 rounded-2xl border text-left transition cursor-pointer flex items-start gap-3"
                  :class="form.ceremony_type === 'destination_outdoor' ? 'bg-rose-50 border-rose-500 ring-2 ring-rose-400' : 'bg-[#FAF8F5] border-slate-200 hover:bg-rose-50/50'"
                >
                  <span class="text-xl p-2 rounded-xl bg-white border border-rose-100">🌊</span>
                  <div>
                    <div class="text-xs font-bold text-slate-900">Outdoor / Bãi Biển</div>
                    <div class="text-[11px] text-slate-500">Sunset ceremony & After-party DJ</div>
                  </div>
                </button>

                <button 
                  type="button"
                  @click="form.ceremony_type = 'hotel_luxury'"
                  class="p-4 rounded-2xl border text-left transition cursor-pointer flex items-start gap-3"
                  :class="form.ceremony_type === 'hotel_luxury' ? 'bg-rose-50 border-rose-500 ring-2 ring-rose-400' : 'bg-[#FAF8F5] border-slate-200 hover:bg-rose-50/50'"
                >
                  <span class="text-xl p-2 rounded-xl bg-white border border-rose-100">👑</span>
                  <div>
                    <div class="text-xs font-bold text-slate-900">Khách Sạn 5 Sao</div>
                    <div class="text-[11px] text-slate-500">Sảnh Grand Ballroom & tiệc cao cấp</div>
                  </div>
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-2">Vibe Màu Sắc 主 Đạo</label>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <button type="button" @click="form.wedding_vibe = 'pastel'" class="p-3 rounded-xl border text-center text-xs font-bold transition cursor-pointer" :class="form.wedding_vibe === 'pastel' ? 'bg-rose-600 text-white border-rose-600' : 'bg-[#FAF8F5] text-slate-700 border-slate-200'">
                  🌸 Pastel Romantic
                </button>
                <button type="button" @click="form.wedding_vibe = 'royal_gold'" class="p-3 rounded-xl border text-center text-xs font-bold transition cursor-pointer" :class="form.wedding_vibe === 'royal_gold' ? 'bg-amber-600 text-white border-amber-600' : 'bg-[#FAF8F5] text-slate-700 border-slate-200'">
                  👑 Royal Gold
                </button>
                <button type="button" @click="form.wedding_vibe = 'botanical'" class="p-3 rounded-xl border text-center text-xs font-bold transition cursor-pointer" :class="form.wedding_vibe === 'botanical' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-[#FAF8F5] text-slate-700 border-slate-200'">
                  🌿 Botanical Sage
                </button>
                <button type="button" @click="form.wedding_vibe = 'minimalist'" class="p-3 rounded-xl border text-center text-xs font-bold transition cursor-pointer" :class="form.wedding_vibe === 'minimalist' ? 'bg-slate-900 text-white border-slate-900' : 'bg-[#FAF8F5] text-slate-700 border-slate-200'">
                  ⚡ Modern Slate
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 3: Location & Guests -->
        <div v-else-if="currentStep === 3" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 3 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Địa Điểm & Quy Mô Khách Mời</h2>
            <p class="text-xs text-slate-500 mt-1">Thiết lập sơ đồ bàn tiệc & danh sách khách mời dự kiến</p>
          </div>

          <div class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tỉnh / Thành Phố Tổ Chức</label>
              <input v-model="form.wedding_location" type="text" placeholder="TP. Hồ Chí Minh" class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tên Nhà Hàng / Sảnh Tiệc Dự Kiến</label>
              <input v-model="form.venue_name" type="text" placeholder="White Palace / Gem Center / Asiana Plaza" class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Dự Kiến Số Lượng Khách Mời (Người)</label>
              <input v-model.number="form.estimated_guests" type="number" min="10" max="2000" class="w-full px-4 py-3 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400 font-bold" />
            </div>
          </div>
        </div>

        <!-- STEP 4: Budget Cap & Smart Allocation -->
        <div v-else-if="currentStep === 4" class="space-y-6">
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-rose-700 bg-rose-100 px-3 py-1 rounded-full">BƯỚC 4 / 4</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-3">Trần Ngân Sách & Thuật Toán 50/15/10/10/15</h2>
            <p class="text-xs text-slate-500 mt-1">Hệ thống sẽ tự động chia nhỏ ngân sách thành các khoản cọc cụ thể</p>
          </div>

          <div class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tổng Trần Ngân Sách Dự Kiến (VNĐ) *</label>
              <input v-model.number="form.budget_cap" type="number" step="5000000" min="20000000" required class="w-full px-4 py-3 bg-[#FAF8F5] border border-rose-300 rounded-xl text-sm font-extrabold text-rose-900 focus:outline-hidden focus:border-rose-500" />
            </div>

            <!-- Smart 50/15/10/10/15 Breakdown Preview -->
            <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200 space-y-2.5">
              <span class="text-[11px] font-bold text-rose-900 uppercase tracking-wider block">Bảng Phân Bổ Ngân Sách Tự Động (Auto-Calculated):</span>
              <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="p-2.5 rounded-xl bg-white border border-rose-100">
                  <span class="text-[10px] text-slate-500 block">🏛️ Tiệc & Sảnh (50%)</span>
                  <span class="font-extrabold text-slate-900">{{ new Intl.NumberFormat('vi-VN').format(form.budget_cap * 0.5) }} đ</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white border border-rose-100">
                  <span class="text-[10px] text-slate-500 block">📷 Phim Ảnh (15%)</span>
                  <span class="font-extrabold text-slate-900">{{ new Intl.NumberFormat('vi-VN').format(form.budget_cap * 0.15) }} đ</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white border border-rose-100">
                  <span class="text-[10px] text-slate-500 block">🌸 Decor (10%)</span>
                  <span class="font-extrabold text-slate-900">{{ new Intl.NumberFormat('vi-VN').format(form.budget_cap * 0.10) }} đ</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white border border-rose-100">
                  <span class="text-[10px] text-slate-500 block">👗 Váy & Nhẫn (10%)</span>
                  <span class="font-extrabold text-slate-900">{{ new Intl.NumberFormat('vi-VN').format(form.budget_cap * 0.10) }} đ</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Wizard Navigation Buttons -->
        <div class="pt-8 flex items-center justify-between border-t border-rose-100 mt-8">
          <button v-if="currentStep > 1" type="button" @click="prevStep" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
            <ArrowLeft class="w-4 h-4" /> Quay Lại
          </button>
          <div v-else></div>

          <button type="button" @click="nextStep" :disabled="form.processing || (currentStep === 1 && (!form.groom_name || !form.bride_name))" class="px-7 py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-2 cursor-pointer disabled:opacity-50">
            <span>{{ currentStep === 4 ? '🎉 KHỞI TẠO PLANNER CÁ NHÂN HÓA' : 'Tiếp Theo' }}</span>
            <ArrowRight v-if="currentStep < 4" class="w-4 h-4" />
          </button>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-500 border-t border-rose-100 bg-white">
      Eloria Wedding OS • Operating System for Planning a Wedding
    </footer>
  </div>
</template>
