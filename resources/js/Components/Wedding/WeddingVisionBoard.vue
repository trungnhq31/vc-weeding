<script setup lang="ts">
import { ref } from 'vue';
import { 
  Palette, 
  Sparkles, 
  Heart, 
  Check, 
  Eye, 
  Shirt, 
  Flower2, 
  Image as ImageIcon 
} from 'lucide-vue-next';

const selectedTheme = ref('romantic-pastel');

const colorThemes = [
  {
    id: 'romantic-pastel',
    name: 'Romantic Pastel Rose Gold',
    desc: 'Hồng phấn kem ấm niêm phong sáp Wax Seal lãng mạn',
    colors: ['#FAF8F5', '#FDF2F8', '#FCE7F3', '#EC4899', '#881337'],
    dressCode: 'Hồng Phấn Pastel, Kem Trắng Warm Ivory, Rose Gold',
    floralConcept: 'Hoa hồng Ohara kem hồng, hoa linh lan & lá bạch đàn bạt phủ',
  },
  {
    id: 'royal-gold',
    name: 'Royal Champagne Gold',
    desc: 'Vàng sâm panh hoàng gia sang trọng & quý phái',
    colors: ['#FFFDF9', '#FEF3C7', '#F59E0B', '#D97706', '#78350F'],
    dressCode: 'Vàng Sâm Panh, Trắng Kem, Nâu Be Champagne',
    floralConcept: 'Hoa tú cầu kem vàng, hoa hướng dương mini & hoa baby vàng',
  },
  {
    id: 'botanical-sage',
    name: 'Botanical Sage Green',
    desc: 'Xanh lá xô thơm thảo mộc thiên nhiên tươi mát',
    colors: ['#F0FDF4', '#DCFCE7', '#10B981', '#047857', '#064E3B'],
    dressCode: 'Xanh Thảo Mộc Sage Green, Trắng Tinh Khôi, Xám Nhạt',
    floralConcept: 'Lá xô thơm, hoa hồng trắng Avalanche & nhánh ô liu',
  },
  {
    id: 'modern-slate',
    name: 'Modern Slate Minimalist',
    desc: 'Ghi sáng tối giản phong cách Notion/Linear hiện đại',
    colors: ['#F8FAFC', '#E2E8F0', '#64748B', '#1E293B', '#0F172A'],
    dressCode: 'Ghi Sáng Slate, Đen Tuxedo, Trắng Minimalist',
    floralConcept: 'Hoa lan hồ điệp trắng đơn sắc & cấu trúc tối giản',
  },
];
</script>

<template>
  <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-sm space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-rose-100 pb-4">
      <div>
        <span class="text-[11px] font-bold uppercase tracking-widest text-rose-600">CONCEPT & MOODBOARD VISUALIZER</span>
        <h2 class="text-xl font-serif font-bold text-slate-900 mt-0.5">Bảng Palette Màu Sắc & Concept Đám Cưới</h2>
        <p class="text-xs text-slate-500 mt-1">Trực quan hóa tông màu chủ đạo, hoa tươi trang trí và quy định trang phục (Dress Code)</p>
      </div>

      <span class="px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-900 font-bold text-xs border border-rose-200 flex items-center gap-1.5">
        <Palette class="w-3.5 h-3.5 text-rose-600" /> Live Theme Swatch
      </span>
    </div>

    <!-- Color Theme Picker Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div 
        v-for="theme in colorThemes" 
        :key="theme.id" 
        @click="selectedTheme = theme.id"
        class="p-5 rounded-2xl border-2 transition-all duration-300 cursor-pointer space-y-3 flex flex-col justify-between"
        :class="selectedTheme === theme.id ? 'bg-rose-50/60 border-rose-400 shadow-md transform -translate-y-1' : 'bg-[#FAF8F5] border-slate-200 hover:border-rose-200'"
      >
        <div>
          <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700">
              CONCEPT
            </span>
            <Check v-if="selectedTheme === theme.id" class="w-4 h-4 text-rose-600" />
          </div>

          <h3 class="font-serif font-bold text-slate-900 text-sm mb-1">{{ theme.name }}</h3>
          <p class="text-[11px] text-slate-500 leading-relaxed mb-3">{{ theme.desc }}</p>
        </div>

        <!-- Color Swatch Row -->
        <div class="flex items-center gap-1.5 p-2 rounded-xl bg-white border border-slate-200">
          <div 
            v-for="(hex, idx) in theme.colors" 
            :key="idx"
            class="flex-1 h-6 rounded-lg border border-black/10 shadow-2xs transition-transform hover:scale-110"
            :style="{ backgroundColor: hex }"
            :title="hex"
          ></div>
        </div>
      </div>
    </div>

    <!-- Active Selected Theme Deep Dive Concept Details -->
    <div v-if="selectedTheme" class="p-6 rounded-3xl bg-gradient-to-r from-rose-50/70 via-[#FAF8F5] to-amber-50/60 border border-rose-200/80 shadow-sm space-y-6">
      <div class="flex items-center gap-2">
        <Sparkles class="w-5 h-5 text-rose-600" />
        <h3 class="text-base font-serif font-bold text-rose-950">
          Chi Tiết Concept Trang Trí: {{ colorThemes.find(t => t.id === selectedTheme)?.name }}
        </h3>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Guest Dress Code Card -->
        <div class="p-5 rounded-2xl bg-white border border-rose-100 shadow-2xs space-y-2">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <Shirt class="w-4 h-4 text-rose-500" /> Quy Định Trang Phục Khách Mời (Dress Code)
          </span>
          <p class="text-xs text-slate-800 font-semibold leading-relaxed">
            {{ colorThemes.find(t => t.id === selectedTheme)?.dressCode }}
          </p>
          <p class="text-[11px] text-slate-500">Khuyên dùng khách mời mặc đúng gam màu chủ đạo để ảnh chụp lễ đường đạt thẩm mỹ hoàn hảo.</p>
        </div>

        <!-- Floral Concept Card -->
        <div class="p-5 rounded-2xl bg-white border border-rose-100 shadow-2xs space-y-2">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <Flower2 class="w-4 h-4 text-emerald-500" /> Concept Hoa Tươi & Mẫu Cổng Chào
          </span>
          <p class="text-xs text-slate-800 font-semibold leading-relaxed">
            {{ colorThemes.find(t => t.id === selectedTheme)?.floralConcept }}
          </p>
          <p class="text-[11px] text-slate-500">Toàn bộ hoa tươi trang trí được thiết kế đồng bộ từ cổng chào, lối đi đến sân khấu chính.</p>
        </div>
      </div>
    </div>
  </div>
</template>
