<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  Palette, 
  Music, 
  Sliders, 
  Eye, 
  Save, 
  Check, 
  Sparkles, 
  QrCode, 
  Lock, 
  Share2, 
  Heart,
  Type
} from 'lucide-vue-next';
import WeddingEnvelopeModal from '@/Components/Wedding/WeddingEnvelopeModal.vue';

const selectedTemplate = ref('romantic-pastel');
const groomName = ref('Quốc Trung');
const brideName = ref('Hồng Vân');
const weddingDate = ref('2026-12-19');
const venueName = ref('Asiana Plaza, TP. Hồ Chí Minh');
const enableWaxSeal = ref(true);
const enableBackgroundMusic = ref(true);
const enableQrCheckin = ref(true);
const isSaved = ref(false);

const saveCustomizer = () => {
  isSaved.value = true;
  setTimeout(() => { isSaved.value = false; }, 2500);
};
</script>

<template>
          <button @click="saveCustomizer" class="px-4 py-1.5 rounded-xl bg-slate-900 text-white font-semibold text-xs hover:bg-slate-800 transition flex items-center gap-1.5 cursor-pointer">
            <Check v-if="isSaved" class="w-4 h-4 text-emerald-400" />
            <Save v-else class="w-4 h-4" />
            {{ isSaved ? 'Đã lưu mẫu thiệp!' : 'Lưu Thay Đổi' }}
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid md:grid-cols-12 gap-8">
        <!-- Left Side: Controls Panel -->
        <div class="md:col-span-5 space-y-6">
          <!-- Template Selector -->
          <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <h2 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
              <Sparkles class="w-4 h-4 text-rose-600" />
              Chọn Mẫu Thiệp Cưới Đột Phá
            </h2>
            <div class="grid grid-cols-2 gap-3">
              <button 
                @click="selectedTemplate = 'romantic-pastel'"
                class="p-3 rounded-xl border text-left transition-all cursor-pointer"
                :class="selectedTemplate === 'romantic-pastel' ? 'bg-rose-50 border-rose-400 font-bold text-rose-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
              >
                <div class="text-xs">Romantic Pastel</div>
                <div class="text-[10px] text-slate-500 font-normal">Hồng phấn kem ấm mở sáp</div>
              </button>
              <button 
                @click="selectedTemplate = 'royal-gold'"
                class="p-3 rounded-xl border text-left transition-all cursor-pointer"
                :class="selectedTemplate === 'royal-gold' ? 'bg-amber-50 border-amber-400 font-bold text-amber-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
              >
                <div class="text-xs">Royal Gold</div>
                <div class="text-[10px] text-slate-500 font-normal">Vàng sâm panh hoàng gia</div>
              </button>
              <button 
                @click="selectedTemplate = 'modern-slate'"
                class="p-3 rounded-xl border text-left transition-all cursor-pointer"
                :class="selectedTemplate === 'modern-slate' ? 'bg-slate-100 border-slate-400 font-bold text-slate-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
              >
                <div class="text-xs">Modern Slate</div>
                <div class="text-[10px] text-slate-500 font-normal">Ghi sáng Notion tối giản</div>
              </button>
              <button 
                @click="selectedTemplate = 'botanical-sage'"
                class="p-3 rounded-xl border text-left transition-all cursor-pointer"
                :class="selectedTemplate === 'botanical-sage' ? 'bg-emerald-50 border-emerald-400 font-bold text-emerald-900 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700'"
              >
                <div class="text-xs">Botanical Sage</div>
                <div class="text-[10px] text-slate-500 font-normal">Xanh lá xô thơm thảo mộc</div>
              </button>
            </div>
          </div>

          <!-- Content Fields -->
          <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Type class="w-4 h-4 text-slate-700" />
              Thông Tin Tiêu Đề Dâu Rể
            </h2>
            <div>
              <label class="text-xs font-semibold text-slate-700">Tên Chú Rể</label>
              <input v-model="groomName" type="text" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-300" />
            </div>
            <div>
              <label class="text-xs font-semibold text-slate-700">Tên Cô Dâu</label>
              <input v-model="brideName" type="text" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-300" />
            </div>
            <div>
              <label class="text-xs font-semibold text-slate-700">Ngày Tổ Chức</label>
              <input v-model="weddingDate" type="date" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-300" />
            </div>
            <div>
              <label class="text-xs font-semibold text-slate-700">Địa Điểm Tổ Chức</label>
              <input v-model="venueName" type="text" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-300" />
            </div>
          </div>

          <!-- Interactive Toggles -->
          <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-3">
            <h2 class="text-sm font-bold text-slate-900">Tính Năng Tương Tác Thiệp</h2>
            <label class="flex items-center justify-between text-xs font-medium text-slate-700 cursor-pointer">
              <span>Nút Dấu sáp niêm phong Wax Seal "MỞ THIỆP"</span>
              <input type="checkbox" v-model="enableWaxSeal" class="accent-rose-600 w-4 h-4" />
            </label>
            <label class="flex items-center justify-between text-xs font-medium text-slate-700 cursor-pointer">
              <span>Tự động phát nhạc nền lãng mạn</span>
              <input type="checkbox" v-model="enableBackgroundMusic" class="accent-rose-600 w-4 h-4" />
            </label>
            <label class="flex items-center justify-between text-xs font-medium text-slate-700 cursor-pointer">
              <span>Mã QR Code Check-in độc bản</span>
              <input type="checkbox" v-model="enableQrCheckin" class="accent-rose-600 w-4 h-4" />
            </label>
          </div>
        </div>

        <!-- Right Side: Live Mockup Viewport -->
        <div class="md:col-span-7">
          <div class="p-8 rounded-3xl bg-[#FAF8F5] border border-rose-200/80 shadow-xs sticky top-24 text-center">
            <span class="text-[10px] font-bold uppercase tracking-widest text-rose-800 bg-rose-100 px-3 py-1 rounded-full border border-rose-200">
              TRÌNH MÔ PHỎNG THIỆP MỜI LIVE (TEMPLATE: {{ selectedTemplate.toUpperCase() }})
            </span>

            <div class="mt-6 max-w-md mx-auto p-8 rounded-3xl border shadow-md transition-all duration-500" 
                 :class="{
                   'bg-white border-rose-200/80 text-slate-900': selectedTemplate === 'romantic-pastel',
                   'bg-[#FFFDF9] border-amber-300 text-amber-950': selectedTemplate === 'royal-gold',
                   'bg-white border-slate-300 text-slate-900': selectedTemplate === 'modern-slate',
                   'bg-[#F0FDF4] border-emerald-300 text-emerald-950': selectedTemplate === 'botanical-sage'
                 }">
              <div v-if="enableWaxSeal" 
                   class="w-14 h-14 mx-auto rounded-full border-2 flex items-center justify-center mb-4 shadow-md font-serif font-bold text-xs"
                   :class="{
                     'bg-gradient-to-br from-rose-600 to-rose-800 text-amber-200 border-amber-300': selectedTemplate === 'romantic-pastel',
                     'bg-gradient-to-br from-amber-600 to-amber-800 text-amber-100 border-amber-200': selectedTemplate === 'royal-gold',
                     'bg-gradient-to-br from-slate-700 to-slate-900 text-slate-100 border-slate-300': selectedTemplate === 'modern-slate',
                     'bg-gradient-to-br from-emerald-600 to-emerald-800 text-emerald-100 border-emerald-200': selectedTemplate === 'botanical-sage'
                   }">
                MỞ THIỆP
              </div>

              <span class="text-[11px] font-semibold tracking-widest uppercase block mb-1">THIỆP MỜI ĐÁM CƯỚI</span>
              <h3 class="text-2xl md:text-3xl font-serif font-bold mt-2">
                {{ groomName }} <br />
                <span class="text-xs font-sans font-light opacity-60">&</span> <br />
                {{ brideName }}
              </h3>

              <div class="my-4 py-2 border-y border-slate-200/60 text-xs">
                Ngày: <strong>{{ weddingDate }}</strong>
              </div>

              <p class="text-xs opacity-80 leading-relaxed mb-4">{{ venueName }}</p>

              <div v-if="enableQrCheckin" class="w-16 h-16 mx-auto bg-white rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center text-[10px] text-slate-400">
                [QR Code]
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </WorkspaceLayout>
</template>
