<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
  demoUrl: string;
  templateName: string;
  themeColor?: string;
}>();

const isLoading = ref(true);

const handleIframeLoad = () => {
  isLoading.value = false;
};
</script>

<template>
  <div class="relative mx-auto w-[252px] h-[532px] rounded-[42px] bg-slate-950 border-[6px] border-slate-800 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.35)] group transition-transform duration-300 shrink-0 select-none">
    
    <!-- iPhone Top Dynamic Island / Notch Bar -->
    <div class="absolute top-2.5 left-1/2 -translate-x-1/2 w-20 h-3.5 bg-black rounded-full z-40 flex items-center justify-between px-2 shadow-sm pointer-events-none">
      <div class="w-2 h-2 rounded-full bg-slate-900 border border-slate-800"></div>
      <div class="w-1 h-1 rounded-full bg-blue-900/60"></div>
    </div>

    <!-- iPhone Side Buttons (Volume & Power) -->
    <div class="absolute -left-[8px] top-20 w-[3px] h-7 bg-slate-700 rounded-l-sm"></div>
    <div class="absolute -left-[8px] top-32 w-[3px] h-10 bg-slate-700 rounded-l-sm"></div>
    <div class="absolute -left-[8px] top-45 w-[3px] h-10 bg-slate-700 rounded-l-sm"></div>
    <div class="absolute -right-[8px] top-28 w-[3px] h-14 bg-slate-700 rounded-r-sm"></div>

    <!-- Inner Screen Area (Exact 240px x 520px) -->
    <div class="relative w-[240px] h-[520px] rounded-[36px] overflow-hidden bg-white">
      
      <!-- Loading Skeleton Overlay -->
      <div 
        v-if="isLoading" 
        class="absolute inset-0 z-30 bg-slate-900 text-white flex flex-col items-center justify-center space-y-3 p-4 text-center"
      >
        <div class="w-7 h-7 rounded-full border-2 border-rose-500 border-t-transparent animate-spin"></div>
        <span class="text-[10px] font-mono text-slate-400">Đang tải thiệp...</span>
      </div>

      <!-- Exact Pixel-Perfect Scaled Mobile Viewport (Scrollbars Hidden Completely) -->
      <div class="absolute top-0 left-0 w-[375px] h-[812.5px] origin-top-left pointer-events-none overflow-hidden" style="transform: scale(0.64);">
        <iframe 
          :src="demoUrl" 
          :title="templateName"
          @load="handleIframeLoad"
          scrolling="no"
          class="w-[375px] h-[812.5px] border-0 outline-none m-0 p-0 block select-none overflow-hidden [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
          loading="lazy"
        ></iframe>
      </div>

      <!-- Screen Top Glare Glass Gradient Overlay -->
      <div class="absolute inset-0 pointer-events-none z-20 bg-gradient-to-tr from-transparent via-white/5 to-white/10"></div>
      
      <!-- iPhone Bottom Home Indicator Bar -->
      <div class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-24 h-1 bg-slate-800/80 rounded-full z-40 pointer-events-none"></div>
    </div>
  </div>
</template>
