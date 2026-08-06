<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  Sparkles, 
  Eye, 
  Grid, 
  Clock, 
  Palette, 
  Download, 
  ChevronRight,
  Heart,
  FileText,
  ShieldCheck,
  UserCheck
} from 'lucide-vue-next';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import WeddingFloorPlanCanvas from '@/Components/Wedding/WeddingFloorPlanCanvas.vue';
import WeddingDayRunOfShow from '@/Components/Wedding/WeddingDayRunOfShow.vue';
import WeddingVisionBoard from '@/Components/Wedding/WeddingVisionBoard.vue';
import StaffDutyRoster from '@/Components/Wedding/StaffDutyRoster.vue';
import ContingencyRiskMatrix from '@/Components/Wedding/ContingencyRiskMatrix.vue';

interface WorkspaceInfo {
  name?: string;
  groom_name?: string;
  bride_name?: string;
  wedding_date?: string;
  wedding_location?: string;
  venue_name?: string;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  tables?: any[];
}>();

const activeTab = ref<'floorplan' | 'runofshow' | 'visionboard' | 'logistics'>('floorplan');
</script>

<template>
  <WorkspaceLayout title="Mô Phỏng Trực Quan Đám Cưới (Visualizer)" active-nav="visualizer">
    <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">
      <!-- Top Banner -->
      <div class="p-8 rounded-3xl bg-gradient-to-r from-rose-100/90 via-amber-50/80 to-pink-100/90 text-rose-950 shadow-lg shadow-rose-900/5 backdrop-blur-md flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-white/80">
        <div class="space-y-2">
          <span class="px-3.5 py-1 rounded-full bg-rose-200/60 text-rose-900 text-[11px] font-bold uppercase tracking-widest border border-rose-300/50">
            ELORIA WEDDING OS • VISUAL PLANNING SUITE
          </span>
          <h1 class="text-2xl md:text-3xl font-serif font-bold text-rose-950 tracking-tight">
            Bộ Mô Phỏng Trực Quan Đám Cưới (Spatial Visualizer)
          </h1>
          <p class="text-xs md:text-sm text-rose-900/90 font-medium">
            Tái hiện không gian sảnh tiệc, sơ đồ vị trí bàn tiệc, kịch bản ngày cưới, moodboard & phân công ban khánh tiết.
          </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
          <a href="/wedding/export-excel" target="_blank" class="px-4 py-2.5 rounded-2xl bg-white border border-rose-200 text-rose-900 text-xs font-bold shadow-2xs hover:bg-rose-50 transition flex items-center gap-1.5 cursor-pointer">
            <Download class="w-4 h-4 text-rose-600" /> Xuất Plan Excel
          </a>
        </div>
      </div>

      <!-- Tab Switcher Navigation -->
      <div class="flex items-center gap-3 border-b border-slate-200 pb-3 overflow-x-auto">
        <button 
          @click="activeTab = 'floorplan'"
          class="px-4 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0"
          :class="activeTab === 'floorplan' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
        >
          <Grid class="w-4 h-4 text-rose-400" />
          Sơ Đồ Không Gian Sảnh Tiệc (Spatial Floor Plan)
        </button>

        <button 
          @click="activeTab = 'runofshow'"
          class="px-4 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0"
          :class="activeTab === 'runofshow' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
        >
          <Clock class="w-4 h-4 text-rose-400" />
          Kịch Bản Trực Quan Ngày Cưới (Run of Show)
        </button>

        <button 
          @click="activeTab = 'visionboard'"
          class="px-4 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0"
          :class="activeTab === 'visionboard' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
        >
          <Palette class="w-4 h-4 text-rose-400" />
          Palette Màu & Concept Moodboard
        </button>

        <button 
          @click="activeTab = 'logistics'"
          class="px-4 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0"
          :class="activeTab === 'logistics' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
        >
          <ShieldCheck class="w-4 h-4 text-rose-400" />
          Ban Khánh Tiết & Rủi Ro Dự Phòng (Logistics)
        </button>
      </div>

      <!-- Tab 1: Spatial Floor Plan Visualizer -->
      <div v-if="activeTab === 'floorplan'">
        <WeddingFloorPlanCanvas :tables="tables" :venue-name="workspace?.venue_name" />
      </div>

      <!-- Tab 2: Day-of-Wedding Schedule Run of Show -->
      <div v-else-if="activeTab === 'runofshow'">
        <WeddingDayRunOfShow />
      </div>

      <!-- Tab 3: Vision Board & Color Palette Swatch Generator -->
      <div v-else-if="activeTab === 'visionboard'">
        <WeddingVisionBoard />
      </div>

      <!-- Tab 4: Logistics, Duty Roster & Contingency Risk Matrix -->
      <div v-else-if="activeTab === 'logistics'" class="space-y-8">
        <StaffDutyRoster />
        <ContingencyRiskMatrix />
      </div>
    </main>
  </WorkspaceLayout>
</template>
