<script setup lang="ts">
import { ref } from 'vue';
import { Heart, Sparkles, MailOpen } from 'lucide-vue-next';

const emit = defineEmits(['open']);

const isOpen = ref(false);
const isOpening = ref(false);

const openEnvelope = () => {
    if (isOpening.value || isOpen.value) return;
    isOpening.value = true;
    
    setTimeout(() => {
        isOpen.value = true;
        emit('open');
    }, 800);
};
</script>

<template>
    <div 
        v-if="!isOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#FAF8F5]/95 backdrop-blur-xl transition-all duration-1000 px-4"
        :class="{ 'opacity-0 pointer-events-none scale-105': isOpening }"
    >
        <!-- Background Ambient Glow -->
        <div class="absolute inset-0 bg-gradient-to-tr from-rose-100/50 via-amber-50/40 to-rose-50/60"></div>
        
        <!-- Floating Rose Petals Decoration -->
        <div class="absolute top-10 left-10 text-rose-300/40 animate-pulse"><Sparkles class="w-8 h-8" /></div>
        <div class="absolute bottom-12 right-12 text-amber-300/40 animate-pulse"><Heart class="w-10 h-10" /></div>

        <!-- Premium Envelope Card Container -->
        <div class="relative w-full max-w-lg bg-white rounded-3xl border border-rose-200/80 shadow-2xl p-8 md:p-12 text-center space-y-6 transform transition-transform duration-700">
            
            <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-rose-100/80 text-rose-900 text-xs font-bold uppercase tracking-widest border border-rose-300/60">
                <Sparkles class="w-3.5 h-3.5 text-rose-600 fill-rose-400" /> Thiệp Mời Đám Cưới
            </div>

            <!-- Envelope Header -->
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-[0.4em] text-rose-800 font-mono font-bold block">Wedding Invitation</span>
                <h1 class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 font-serif font-bold text-rose-950 tracking-tight">
                    <span class="text-3xl sm:text-4xl md:text-5xl whitespace-nowrap">Quốc Trung</span>
                    <span class="text-2xl sm:text-3xl md:text-4xl text-rose-500 font-light font-serif">&</span>
                    <span class="text-3xl sm:text-4xl md:text-5xl whitespace-nowrap">Hồng Vân</span>
                </h1>
                <p class="text-xs md:text-sm font-serif italic text-slate-600">
                    Trân trọng kính mời quý khách tới tham dự lễ thành hôn
                </p>
            </div>

            <!-- Interactive Wax Seal Stamp Button -->
            <div class="pt-6 pb-2 flex flex-col items-center justify-center space-y-4">
                <button 
                    @click="openEnvelope"
                    class="group relative w-24 h-24 md:w-28 md:h-28 rounded-full bg-gradient-to-br from-rose-600 via-rose-700 to-rose-900 shadow-xl shadow-rose-900/30 border-4 border-amber-300/80 flex items-center justify-center cursor-pointer transition-all duration-300 hover:scale-105 active:scale-95"
                >
                    <div class="absolute inset-1 rounded-full border border-amber-200/50"></div>
                    <div class="text-center text-amber-200 font-serif font-bold tracking-widest text-xs flex flex-col items-center">
                        <Heart class="w-5 h-5 text-amber-300 fill-amber-300 group-hover:animate-ping mb-1" />
                        <span>MỞ THIỆP</span>
                    </div>
                </button>

                <span class="text-xs font-serif italic text-rose-800 animate-bounce flex items-center gap-1">
                    <MailOpen class="w-4 h-4" /> Bấm vào dấu sáp để mở thiệp cưới
                </span>
            </div>

            <!-- Envelope Date Footer -->
            <div class="pt-4 border-t border-rose-100 flex items-center justify-between text-xs text-slate-500 font-serif">
                <span>Thứ Bảy, 19.12.2026</span>
                <span>Asiana Plaza, TP.HCM</span>
            </div>
        </div>
    </div>
</template>
