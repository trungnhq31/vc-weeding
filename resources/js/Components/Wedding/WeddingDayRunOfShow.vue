<script setup lang="ts">
import { ref } from 'vue';
import { 
  Clock, 
  MapPin, 
  Users, 
  Camera, 
  Music, 
  Sparkles, 
  Heart, 
  GlassWater, 
  Gift, 
  CheckCircle2 
} from 'lucide-vue-next';

interface ScheduleSlot {
  time: string;
  title: string;
  location: string;
  vendor: string;
  description: string;
  icon: string;
  tag: string;
  isCompleted?: boolean;
}

const scheduleSlots = ref<ScheduleSlot[]>([
  {
    time: '07:30 - 09:30',
    title: 'Lễ Gia Tiên & Rước Dâu Tại Tư Gia',
    location: 'Tư gia Nhà Chú Rể & Cô Dâu',
    vendor: 'Trang Trí L’Amour & Studio Elite',
    description: 'Nghi thức trao mâm quả 8 lễ, thắp hương tổ tiên và đón cô dâu về nhà trai.',
    icon: '⛩️',
    tag: 'Tư Gia',
    isCompleted: true,
  },
  {
    time: '11:00 - 12:00',
    title: 'Đón Khách & Chụp Ảnh Check-in Photo Booth',
    location: 'Sảnh Đón Khách Grand Ballroom',
    vendor: 'Band Âm Nhạc & Teabreak Catering',
    description: 'Đón tiếp khách mời, ký tên sổ lưu niệm, chụp ảnh check-in và thưởng thức cocktail welcome drinks.',
    icon: '🥂',
    tag: 'Sảnh Tiệc',
    isCompleted: false,
  },
  {
    time: '12:00 - 12:30',
    title: 'Nghi Thức Lễ Cưới & Rót Rượu Champagne',
    location: 'Sân Khấu Chính Grand Stage',
    vendor: 'MC Đám Cưới & Đội Lễ Tân',
    description: 'Chú rể đón cô dâu vào lễ đường, trao nhẫn cưới, rót tháp rượu vang & cắt bánh cưới 5 tầng.',
    icon: '💍',
    tag: 'Trọng Tâm',
    isCompleted: false,
  },
  {
    time: '12:30 - 14:00',
    title: 'Khai Tiệc Cỗ Cưới & Giao Lưu Âm Nhạc',
    location: 'Sảnh Tiệc 20 Bàn',
    vendor: 'Nhà Hàng White Palace',
    description: 'Phục vụ thực đơn tiệc cưới 6 món cao cấp, dâu rể đi từng bàn nâng ly cảm ơn quan khách.',
    icon: '🍷',
    tag: 'Thực Đơn',
    isCompleted: false,
  },
  {
    time: '14:00 - 15:30',
    title: 'After-Party, Tung Hoa Cưới & Quà Đáp Lễ',
    location: 'Khu Lounge Sân Vườn',
    vendor: 'DJ & Bartender Staff',
    description: 'Tung hoa cưới cho hội bạn thân, quẩy nhạc sau tiệc và trao quà đáp lễ nến thơm cao cấp.',
    icon: '🎉',
    tag: 'Sau Tiệc',
    isCompleted: false,
  },
]);
</script>

<template>
  <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-sm space-y-6">
    <div class="flex items-center justify-between border-b border-rose-100 pb-4">
      <div>
        <span class="text-[11px] font-bold uppercase tracking-widest text-rose-600">DAY-OF-WEDDING VISUAL RUN OF SHOW</span>
        <h2 class="text-xl font-serif font-bold text-slate-900 mt-0.5">Kịch Bản Trực Quan Ngày Trọng Đại (Visual Run of Show)</h2>
        <p class="text-xs text-slate-500 mt-1">Lịch trình mốc thời gian chi tiết từng hoạt động trong ngày cưới</p>
      </div>

      <span class="px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-900 font-bold text-xs border border-rose-200">
        5 Mốc Hoạt Động Chốt
      </span>
    </div>

    <!-- Schedule Stream Grid -->
    <div class="relative border-l-2 border-rose-200 ml-4 md:ml-32 space-y-8 my-4">
      <div 
        v-for="(slot, idx) in scheduleSlots" 
        :key="idx" 
        class="relative pl-6 md:pl-8 group cursor-pointer"
      >
        <!-- Time Badge Left Desktop -->
        <div class="hidden md:flex absolute -left-36 top-1 text-right w-28 justify-end">
          <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-800 font-mono font-bold text-xs border border-slate-200">
            {{ slot.time }}
          </span>
        </div>

        <!-- Point Dot -->
        <div 
          class="absolute -left-[11px] top-2 w-5 h-5 rounded-full border-2 bg-white flex items-center justify-center transition-transform group-hover:scale-125 shadow-2xs"
          :class="slot.isCompleted ? 'border-emerald-500 bg-emerald-50 text-emerald-600' : 'border-rose-400 bg-rose-50 text-rose-600'"
        >
          <span class="w-2 h-2 rounded-full" :class="slot.isCompleted ? 'bg-emerald-500' : 'bg-rose-500'"></span>
        </div>

        <!-- Schedule Visual Card -->
        <div class="p-5 rounded-2xl bg-[#FAF8F5] border border-rose-100 shadow-2xs group-hover:border-rose-300 group-hover:shadow-md transition-all duration-300">
          <div class="flex items-center justify-between gap-3 mb-2">
            <div class="flex items-center gap-2">
              <span class="md:hidden text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700">
                {{ slot.time }}
              </span>
              <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-100/80 text-rose-900 border border-rose-200">
                {{ slot.tag }}
              </span>
            </div>
            <span class="text-xl">{{ slot.icon }}</span>
          </div>

          <h3 class="text-base font-serif font-bold text-slate-900 group-hover:text-rose-700 transition-colors mb-1">
            {{ slot.title }}
          </h3>

          <p class="text-xs text-slate-600 leading-relaxed mb-3">{{ slot.description }}</p>

          <div class="flex flex-wrap items-center justify-between text-[11px] font-medium text-slate-500 pt-2 border-t border-rose-200/50">
            <span class="flex items-center gap-1">
              <MapPin class="w-3.5 h-3.5 text-rose-500" /> {{ slot.location }}
            </span>
            <span class="flex items-center gap-1 text-rose-800">
              <Users class="w-3.5 h-3.5 text-rose-500" /> {{ slot.vendor }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
