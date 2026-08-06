<script setup lang="ts">
import { ref, computed } from 'vue';
import { 
  Sparkles, 
  Users, 
  MapPin, 
  AlertTriangle, 
  Camera, 
  Heart, 
  Grid, 
  Music, 
  Eye, 
  Info,
  CheckCircle2
} from 'lucide-vue-next';

interface TableItem {
  id: string;
  name: string;
  capacity: number;
  assignedCount: number;
  shape: string;
  zone: string;
  isOverloaded?: boolean;
}

const props = defineProps<{
  tables?: TableItem[];
  guestsCount?: number;
  venueName?: string;
}>();

const selectedZone = ref<any>(null);

const defaultTables = [
  { id: 't1', name: 'Bàn VIP 1 (Họ Hàng Rể)', capacity: 10, assignedCount: 8, shape: 'Tròn', zone: 'Khu VIP Đầu Sân Khấu' },
  { id: 't2', name: 'Bàn VIP 2 (Họ Hàng Dâu)', capacity: 10, assignedCount: 10, shape: 'Tròn', zone: 'Khu VIP Đầu Sân Khấu' },
  { id: 't3', name: 'Bàn Bạn Học Chú Rể', capacity: 10, assignedCount: 11, shape: 'Tròn', zone: 'Khu Bên Trái Lối Đi', isOverloaded: true },
  { id: 't4', name: 'Bàn Bạn Học Cô Dâu', capacity: 10, assignedCount: 9, shape: 'Tròn', zone: 'Khu Bên Trái Lối Đi' },
  { id: 't5', name: 'Bàn Đồng Nghiệp Rể', capacity: 10, assignedCount: 7, shape: 'Tròn', zone: 'Khu Bên Phải Lối Đi' },
  { id: 't6', name: 'Bàn Đồng Nghiệp Dâu', capacity: 10, assignedCount: 8, shape: 'Tròn', zone: 'Khu Bên Phải Lối Đi' },
];

const activeTables = computed(() => props.tables && props.tables.length > 0 ? props.tables : defaultTables);

const spatialZones = [
  {
    id: 'stage',
    name: 'Sân Khấu Chính & Tháp Bánh Cưới',
    category: 'Stage Area',
    color: 'from-amber-100 to-rose-100 border-amber-300 text-amber-950',
    description: 'Nơi diễn ra nghi thức trao nhẫn, rót tháp rượu champagne và cắt bánh cưới 5 tầng.',
    icon: '✨',
    pos: 'top-4 left-1/2 -translate-x-1/2 w-4/5 h-20',
  },
  {
    id: 'aisle',
    name: 'Lối Đi Thảm Đỏ & Hoa Tươi',
    category: 'Walkway',
    color: 'from-rose-500 to-pink-500 text-white border-rose-400',
    description: 'Lối đi thảm đỏ 20m phủ hoa tươi dẫn chú rể đón cô dâu tiến vào sân khấu chính.',
    icon: '🌹',
    pos: 'top-28 left-1/2 -translate-x-1/2 w-8 h-48',
  },
  {
    id: 'photobooth',
    name: 'Khu Chụp Ảnh Check-in Photo Booth',
    category: 'Entrance Zone',
    color: 'from-emerald-50 to-teal-100 border-emerald-300 text-emerald-950',
    description: 'Phông nền check-in hoa tươi pastel cao 3m kèm bảng tên dâu rể thiết kế độc bản.',
    icon: '📷',
    pos: 'bottom-4 left-6 w-1/3 h-20',
  },
  {
    id: 'entrance',
    name: 'Cổng Chào Hoa Tươi & Bàn Lễ Tân',
    category: 'Welcome Area',
    color: 'from-rose-100 to-pink-100 border-rose-300 text-rose-950',
    description: 'Nơi đặt thùng tiền ủng hộ hỷ sự, sách ký tên lưu niệm và quà đáp lễ cho khách.',
    icon: '⛩️',
    pos: 'bottom-4 right-6 w-1/3 h-20',
  },
];
</script>

<template>
  <div class="space-y-6">
    <!-- Visual Simulator Control Header -->
    <div class="p-6 rounded-3xl bg-white border border-rose-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <span class="text-[11px] font-bold uppercase tracking-widest text-rose-600">3D SPATIAL FLOOR PLAN VISUALIZER</span>
        <h2 class="text-xl font-serif font-bold text-slate-900 mt-0.5">Sơ Đồ Không Gian & Sảnh Tiệc Trực Quan</h2>
        <p class="text-xs text-slate-500 mt-1">
          Địa điểm: <strong class="text-slate-800">{{ venueName || 'White Palace Event Center' }}</strong> • 
          Tổng số bàn tiệc: <strong class="text-slate-800">{{ activeTables.length }} Bàn</strong> • 
          Sức chứa sảnh: <strong class="text-slate-800">~250 Khách</strong>
        </p>
      </div>

      <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-rose-50 text-rose-800 text-xs font-bold border border-rose-200">
          <Eye class="w-3.5 h-3.5 text-rose-600" /> Click vào khu vực để xem chi tiết
        </span>
      </div>
    </div>

    <!-- Spatial Canvas Viewport -->
    <div class="relative w-full min-h-[500px] p-6 rounded-3xl bg-gradient-to-br from-[#FAF8F5] via-[#FDF2F8] to-[#FFFDF9] border border-rose-200/80 shadow-md overflow-hidden">
      <!-- Grid Background Pattern -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:2rem_2rem] opacity-60"></div>

      <!-- Canvas Interior Floor Layout -->
      <div class="relative w-full h-[460px]">
        <!-- 1. Spatial Fixed Zones (Stage, Aisle, Photo Booth, Entrance) -->
        <div 
          v-for="zone in spatialZones" 
          :key="zone.id" 
          @click="selectedZone = zone"
          class="absolute rounded-2xl border-2 shadow-sm backdrop-blur-md p-3 flex flex-col items-center justify-center text-center transition-all duration-300 transform hover:scale-105 cursor-pointer z-10"
          :class="zone.color"
          :style="zone.pos ? undefined : ''"
        >
          <span class="text-lg mb-1">{{ zone.icon }}</span>
          <span class="text-xs font-bold font-serif leading-snug">{{ zone.name }}</span>
          <span class="text-[10px] opacity-75 font-semibold mt-0.5">{{ zone.category }}</span>
        </div>

        <!-- 2. Interactive Seating Round Tables Grid Left Side -->
        <div class="absolute top-28 left-8 w-1/3 grid grid-cols-2 gap-4 z-20">
          <div 
            v-for="table in activeTables.slice(0, 3)" 
            :key="table.id"
            @click="selectedZone = { name: table.name, category: 'Bàn Tiệc Ròn', description: `Sức chứa: ${table.assignedCount}/${table.capacity} Khách. Khu vực: ${table.zone}`, icon: '🍽️', isOverloaded: table.isOverloaded }"
            class="p-3 rounded-2xl border-2 bg-white/90 backdrop-blur-md shadow-xs text-center transition-all hover:border-rose-400 hover:shadow-md cursor-pointer group"
            :class="table.isOverloaded ? 'border-rose-400 bg-rose-50/90' : 'border-slate-200'"
          >
            <div class="w-10 h-10 mx-auto rounded-full border-2 border-rose-300 bg-rose-50 flex items-center justify-center text-xs font-bold text-rose-900 shadow-2xs group-hover:scale-110 transition-transform">
              {{ table.assignedCount }}
            </div>
            <div class="text-[11px] font-serif font-bold text-slate-900 mt-1 truncate" :title="table.name">{{ table.name }}</div>
            <span v-if="table.isOverloaded" class="text-[9px] font-bold text-rose-600 block mt-0.5">Quá tải!</span>
            <span v-else class="text-[9px] text-slate-400 block mt-0.5">{{ table.assignedCount }}/{{ table.capacity }} Khách</span>
          </div>
        </div>

        <!-- 3. Interactive Seating Round Tables Grid Right Side -->
        <div class="absolute top-28 right-8 w-1/3 grid grid-cols-2 gap-4 z-20">
          <div 
            v-for="table in activeTables.slice(3, 6)" 
            :key="table.id"
            @click="selectedZone = { name: table.name, category: 'Bàn Tiệc Ròn', description: `Sức chứa: ${table.assignedCount}/${table.capacity} Khách. Khu vực: ${table.zone}`, icon: '🍽️' }"
            class="p-3 rounded-2xl border-2 bg-white/90 backdrop-blur-md shadow-xs text-center transition-all hover:border-rose-400 hover:shadow-md cursor-pointer group border-slate-200"
          >
            <div class="w-10 h-10 mx-auto rounded-full border-2 border-rose-300 bg-rose-50 flex items-center justify-center text-xs font-bold text-rose-900 shadow-2xs group-hover:scale-110 transition-transform">
              {{ table.assignedCount }}
            </div>
            <div class="text-[11px] font-serif font-bold text-slate-900 mt-1 truncate" :title="table.name">{{ table.name }}</div>
            <span class="text-[9px] text-slate-400 block mt-0.5">{{ table.assignedCount }}/{{ table.capacity }} Khách</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Selected Zone Detail Drawer Popup -->
    <div v-if="selectedZone" class="p-6 rounded-3xl bg-white border border-rose-200 shadow-lg flex items-start justify-between gap-4 animate-in slide-in-from-bottom duration-200">
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 border border-rose-200 flex items-center justify-center text-2xl shrink-0">
          {{ selectedZone.icon }}
        </div>
        <div class="space-y-1">
          <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-100">
            {{ selectedZone.category }}
          </span>
          <h3 class="text-base font-serif font-bold text-slate-900">{{ selectedZone.name }}</h3>
          <p class="text-xs text-slate-600 leading-relaxed max-w-xl">{{ selectedZone.description }}</p>
        </div>
      </div>
      <button @click="selectedZone = null" class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 text-xs font-semibold">
        Đóng
      </button>
    </div>
  </div>
</template>
