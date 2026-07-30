<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Heart, Sparkles, MailOpen } from 'lucide-vue-next';

const props = withDefaults(defineProps<{
    templateId?: string;
    groomName?: string;
    brideName?: string;
    weddingDateText?: string;
    locationText?: string;
}>(), {
    templateId: 'romantic-pastel',
    groomName: 'Quốc Trung',
    brideName: 'Hồng Vân',
    weddingDateText: 'Thứ Bảy, 19.12.2026',
    locationText: 'Asiana Plaza, TP.HCM',
});

const emit = defineEmits(['open']);

const isOpen = ref(false);
const isOpening = ref(false);

const activeTemplate = computed(() => {
    // Check URL search params first if prop isn't passed explicitly
    const urlParams = typeof window !== 'undefined' ? new URLSearchParams(window.location.search) : null;
    const tId = urlParams?.get('template') || props.templateId;

    switch (tId) {
        case 'royal-gold':
            return {
                id: 'royal-gold',
                badgeText: 'THIỆP MỜI HOÀNG GIA',
                badgeStyle: 'bg-amber-100 text-amber-900 border-amber-300',
                cardStyle: 'bg-[#FFFDF9] border-amber-300/80 shadow-amber-200/50',
                waxSealBtn: 'from-amber-600 via-amber-700 to-amber-900 border-amber-300 shadow-amber-900/30 text-amber-100',
                titleColor: 'text-amber-950',
                ampersandColor: 'text-amber-600',
                subtextColor: 'text-amber-800',
            };
        case 'modern-slate':
            return {
                id: 'modern-slate',
                badgeText: 'THIỆP MỜI TỐI GIẢN',
                badgeStyle: 'bg-slate-200 text-slate-900 border-slate-300',
                cardStyle: 'bg-white border-slate-300 shadow-slate-200/80',
                waxSealBtn: 'from-slate-700 via-slate-800 to-slate-900 border-slate-300 shadow-slate-900/40 text-slate-100',
                titleColor: 'text-slate-900',
                ampersandColor: 'text-indigo-600',
                subtextColor: 'text-slate-600',
            };
        case 'botanical-sage':
            return {
                id: 'botanical-sage',
                badgeText: 'THIỆP MỜI BOTANICAL',
                badgeStyle: 'bg-emerald-100 text-emerald-900 border-emerald-300',
                cardStyle: 'bg-[#F0FDF4] border-emerald-300/80 shadow-emerald-200/50',
                waxSealBtn: 'from-emerald-600 via-emerald-700 to-emerald-900 border-emerald-300 shadow-emerald-900/30 text-emerald-100',
                titleColor: 'text-emerald-950',
                ampersandColor: 'text-emerald-600',
                subtextColor: 'text-emerald-800',
            };
        case 'romantic-pastel':
        default:
            return {
                id: 'romantic-pastel',
                badgeText: 'THIỆP MỜI ĐÁM CƯỚI',
                badgeStyle: 'bg-rose-100 text-rose-900 border-rose-300/60',
                cardStyle: 'bg-white border-rose-200/80 shadow-2xl',
                waxSealBtn: 'from-rose-600 via-rose-700 to-rose-900 border-amber-300/80 shadow-rose-900/30 text-amber-200',
                titleColor: 'text-rose-950',
                ampersandColor: 'text-rose-500',
                subtextColor: 'text-rose-800',
            };
    }
});

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
        
        <!-- Floating Sparkles Decoration -->
        <div class="absolute top-10 left-10 text-rose-300/40 animate-pulse"><Sparkles class="w-8 h-8" /></div>
        <div class="absolute bottom-12 right-12 text-amber-300/40 animate-pulse"><Heart class="w-10 h-10" /></div>

        <!-- Premium Envelope Card Container -->
        <div class="relative w-full max-w-lg rounded-3xl border shadow-2xl p-8 md:p-12 text-center space-y-6 transform transition-transform duration-700" :class="activeTemplate.cardStyle">
            
            <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest border" :class="activeTemplate.badgeStyle">
                <Sparkles class="w-3.5 h-3.5" /> {{ activeTemplate.badgeText }}
            </div>

            <!-- Envelope Header -->
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-[0.4em] font-mono font-bold block" :class="activeTemplate.subtextColor">WEDDING INVITATION</span>
                <h1 class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 font-serif font-bold tracking-tight" :class="activeTemplate.titleColor">
                    <span class="text-3xl sm:text-4xl md:text-5xl whitespace-nowrap">{{ groomName }}</span>
                    <span class="text-2xl sm:text-3xl md:text-4xl font-light font-serif" :class="activeTemplate.ampersandColor">&</span>
                    <span class="text-3xl sm:text-4xl md:text-5xl whitespace-nowrap">{{ brideName }}</span>
                </h1>
                <p class="text-xs md:text-sm font-serif italic text-slate-600">
                    Trân trọng kính mời quý khách tới tham dự lễ thành hôn
                </p>
            </div>

            <!-- Interactive Wax Seal Stamp Button -->
            <div class="pt-6 pb-2 flex flex-col items-center justify-center space-y-4">
                <button 
                    @click="openEnvelope"
                    class="group relative w-24 h-24 md:w-28 md:h-28 rounded-full bg-gradient-to-br border-4 shadow-xl flex items-center justify-center cursor-pointer transition-all duration-300 hover:scale-105 active:scale-95"
                    :class="activeTemplate.waxSealBtn"
                >
                    <div class="absolute inset-1 rounded-full border border-white/20"></div>
                    <div class="text-center font-serif font-bold tracking-widest text-xs flex flex-col items-center">
                        <Heart class="w-5 h-5 group-hover:animate-ping mb-1" />
                        <span>MỞ THIỆP</span>
                    </div>
                </button>

                <span class="text-xs font-serif italic animate-bounce flex items-center gap-1" :class="activeTemplate.subtextColor">
                    <MailOpen class="w-4 h-4" /> Bấm vào dấu sáp để mở thiệp cưới
                </span>
            </div>

            <!-- Envelope Date Footer -->
            <div class="pt-4 border-t border-rose-100 flex items-center justify-between text-xs text-slate-500 font-serif">
                <span>{{ weddingDateText }}</span>
                <span>{{ locationText }}</span>
            </div>
        </div>
    </div>
</template>
