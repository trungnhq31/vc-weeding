<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/dist/ScrollTrigger';

const animProgress = ref(0);

let triggerInstance: ScrollTrigger | null = null;

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    triggerInstance = ScrollTrigger.create({
        trigger: '#clipart-hand-section',
        start: 'top top',
        end: '+=150%',
        pin: true,
        scrub: 0.5,
        onUpdate: (self) => {
            animProgress.value = self.progress;
        },
    });
});

onUnmounted(() => {
    if (triggerInstance) triggerInstance.kill();
});

// Interpolation parameters
const isClasped = computed(() => animProgress.value >= 0.55);

// Crossfade factor between Clip Art 1 (Approaching) and Clip Art 2 (Holding)
const crossfade = computed(() => {
    if (animProgress.value < 0.35) return 0;
    if (animProgress.value > 0.70) return 1;
    return (animProgress.value - 0.35) / 0.35;
});

// Subtle scale & parallax
const imageScale = computed(() => 0.95 + animProgress.value * 0.1);
</script>

<template>
    <!-- Clip Art Style Pinned Section Stage -->
    <div id="clipart-hand-section" class="w-full min-h-screen flex flex-col items-center justify-center relative overflow-hidden py-12">
        
        <!-- Header Text -->
        <div class="relative z-20 text-center space-y-4 mb-6 px-6 max-w-xl">
            <span class="text-[10px] uppercase tracking-[0.5em] text-neutral-400 font-mono">Chương II</span>
            <h2 class="text-4xl md:text-6xl font-serif font-light tracking-tight transition-colors duration-500"
                :class="isClasped ? 'text-amber-100' : 'text-neutral-200'"
            >
                {{ isClasped ? 'Nắm chặt tay nhau' : 'Hồng Vân vươn tay đón Quốc Trung' }}
            </h2>
            <p class="text-sm font-serif italic text-neutral-400">
                {{ isClasped ? 'Quốc Trung & Hồng Vân hòa cùng một nhịp' : 'Đưa bàn tay ra đâm xuyên qua khoảng tối' }}
            </p>
        </div>

        <!-- High Quality Clip Art Illustration Container -->
        <div class="relative w-full max-w-2xl h-80 md:h-[420px] mx-auto flex items-center justify-center px-4">
            
            <!-- Soft Minimal Glow Frame -->
            <div 
                class="absolute inset-4 rounded-3xl border border-white/20 bg-black/40 backdrop-blur-sm shadow-2xl transition-all duration-700 pointer-events-none"
                :class="isClasped ? 'border-amber-300/40 shadow-amber-500/10' : ''"
            ></div>

            <!-- Clip Art Image Layer 1: Approaching -->
            <img 
                src="/images/clipart/clipart_approaching.png" 
                alt="Clip Art Hồng Vân vươn tay đón Quốc Trung" 
                class="absolute max-w-full max-h-full object-contain transition-transform duration-100 ease-out p-6 rounded-2xl"
                :style="{ 
                    opacity: 1 - crossfade,
                    transform: `scale(${imageScale})` 
                }"
            />

            <!-- Clip Art Image Layer 2: Holding Hands & Running Together -->
            <img 
                src="/images/clipart/clipart_holding.png" 
                alt="Clip Art Quốc Trung & Hồng Vân nắm tay nhau" 
                class="absolute max-w-full max-h-full object-contain transition-transform duration-100 ease-out p-6 rounded-2xl"
                :style="{ 
                    opacity: crossfade,
                    transform: `scale(${imageScale})` 
                }"
            />
        </div>

        <!-- Scroll Indicator Hint -->
        <div class="relative z-20 mt-6 text-center">
            <span class="text-[11px] font-mono tracking-widest text-neutral-400 uppercase">
                {{ isClasped ? '✨ Quốc Trung & Hồng Vân đã nắm chặt tay!' : '↓ Cuộn chuột để trải nghiệm hình ảnh nắm tay' }}
            </span>
        </div>
    </div>
</template>
