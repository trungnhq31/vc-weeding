<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Heart, Sparkles, Church } from 'lucide-vue-next';

const scrollPercent = ref(0);
const isScrolling = ref(false);
const currentFrame = ref(0); // 30 FPS frame index

let scrollTimeout: number | null = null;
let animFrameId: number | null = null;
let lastTime = performance.now();
const fpsInterval = 1000 / 30; // 33.33ms per frame (30 FPS)

// Processed 100% transparent PNG Data URLs (White pixels stripped)
const transparentApproachingUrl = ref<string>('/images/clipart/clipart_approaching.png');
const transparentHoldingUrl = ref<string>('/images/clipart/clipart_holding.png');
const transparentAltarGateUrl = ref<string>('/images/clipart/wedding_altar_gate.png');

// Client-side White Background Removal Helper
const removeWhiteBackground = (imgSrc: string): Promise<string> => {
    return new Promise((resolve) => {
        const img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            const ctx = canvas.getContext('2d');
            if (!ctx) return resolve(imgSrc);

            ctx.drawImage(img, 0, 0);
            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imgData.data;

            // Loop through pixels and set alpha = 0 for white / off-white pixels (RGB > 210)
            for (let i = 0; i < data.length; i += 4) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                
                if (r > 210 && g > 210 && b > 210) {
                    data[i + 3] = 0; // Transparent
                }
            }

            ctx.putImageData(imgData, 0, 0);
            resolve(canvas.toDataURL('image/png'));
        };
        img.onerror = () => resolve(imgSrc);
        img.src = imgSrc;
    });
};

const tick30FPS = (currentTime: number) => {
    const elapsed = currentTime - lastTime;
    
    if (elapsed >= fpsInterval) {
        lastTime = currentTime - (elapsed % fpsInterval);
        currentFrame.value = (currentFrame.value + 1) % 30;
    }
    
    animFrameId = requestAnimationFrame(tick30FPS);
};

const handleScroll = () => {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    
    if (scrollHeight > 0) {
        scrollPercent.value = Math.min(100, Math.max(0, (scrollTop / scrollHeight) * 100));
    }

    isScrolling.value = true;
    if (scrollTimeout !== null) clearTimeout(scrollTimeout);
    scrollTimeout = window.setTimeout(() => {
        isScrolling.value = false;
    }, 250);
};

// 30 FPS Walking Step Displacement
const stepY = computed(() => {
    if (!isScrolling.value) {
        return Math.sin((currentFrame.value / 30) * Math.PI * 2) * 1.5;
    }
    return -Math.abs(Math.sin((currentFrame.value / 30) * Math.PI * 4)) * 5;
});

const stepRotate = computed(() => {
    if (!isScrolling.value) return 0;
    return Math.sin((currentFrame.value / 30) * Math.PI * 4) * 2;
});

const crossfade = computed(() => {
    if (scrollPercent.value < 15) return 0;
    if (scrollPercent.value > 35) return 1;
    return (scrollPercent.value - 15) / 20;
});

const speechBadgeText = computed(() => {
    if (scrollPercent.value < 20) return 'Sảnh Đón Khách';
    if (scrollPercent.value > 85) return '✨ Lễ Đường Hạnh Phúc';
    return 'Sảnh Đường • Trung & Vân';
});

onMounted(async () => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
    animFrameId = requestAnimationFrame(tick30FPS);

    // Process transparent PNGs dynamically
    transparentApproachingUrl.value = await removeWhiteBackground('/images/clipart/clipart_approaching.png');
    transparentHoldingUrl.value = await removeWhiteBackground('/images/clipart/clipart_holding.png');
    transparentAltarGateUrl.value = await removeWhiteBackground('/images/clipart/wedding_altar_gate.png');
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    if (scrollTimeout !== null) clearTimeout(scrollTimeout);
    if (animFrameId !== null) cancelAnimationFrame(animFrameId);
});
</script>

<template>
    <!-- Ultra-Sleek Floating Bottom Progress Capsule -->
    <div class="fixed bottom-4 left-4 right-4 md:left-8 md:right-8 z-50 pointer-events-none">
        <div class="max-w-5xl mx-auto relative">
            
            <!-- Moving Couple Clipart (Guaranteed 100% Transparent, No White Box) -->
            <div 
                class="absolute -top-12 md:-top-14 transition-all duration-100 ease-out flex flex-col items-center transform -translate-x-1/2 z-20"
                :style="{ left: `${Math.max(5, Math.min(92, scrollPercent))}%` }"
            >
                <!-- Elegant Romantic Speech Tooltip -->
                <div class="mb-1 px-3 py-1 rounded-full bg-white/95 text-rose-950 text-[10px] md:text-xs font-serif font-bold shadow-lg border border-rose-200/90 flex items-center gap-1.5 whitespace-nowrap backdrop-blur-md">
                    <Heart class="w-3 h-3 text-rose-600 fill-rose-500/40" />
                    <span>{{ speechBadgeText }}</span>
                    <span class="text-[9px] text-rose-700 font-mono">({{ Math.round(scrollPercent) }}%)</span>
                </div>

                <!-- 30 FPS Clipart Character (Pure Transparent PNG) -->
                <div 
                    class="w-12 h-12 md:w-14 md:h-14 relative transition-transform duration-75"
                    :style="{ 
                        transform: `translateY(${stepY}px) rotate(${stepRotate}deg) scaleX(-1)` 
                    }"
                >
                    <!-- Clipart Approaching -->
                    <img 
                        :src="transparentApproachingUrl" 
                        alt="Chú rể & Cô dâu vươn tay" 
                        class="absolute inset-0 w-full h-full object-contain transition-opacity duration-200"
                        :style="{ opacity: 1 - crossfade }"
                    />

                    <!-- Clipart Holding Hands -->
                    <img 
                        :src="transparentHoldingUrl" 
                        alt="Chú rể nắm tay cô dâu" 
                        class="absolute inset-0 w-full h-full object-contain transition-opacity duration-200"
                        :style="{ opacity: crossfade }"
                    />
                </div>
            </div>

            <!-- Floating Glass Progress Bar Track -->
            <div class="w-full h-2.5 md:h-3 bg-white/90 border border-rose-200/80 shadow-md rounded-full p-0.5 backdrop-blur-md relative overflow-hidden flex items-center">
                <!-- Progress Fill Line -->
                <div 
                    class="h-full bg-gradient-to-r from-rose-500 via-rose-400 to-amber-500 rounded-full transition-all duration-100 ease-out relative shadow-[0_0_8px_rgba(244,63,94,0.4)]"
                    :style="{ width: `${scrollPercent}%` }"
                >
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"></div>
                </div>
            </div>

            <!-- Altar Destination Badge at 100% (Far Right) -->
            <div class="absolute -right-2 -top-10 md:-top-12 flex items-center gap-1 z-10 pointer-events-none">
                <div 
                    class="px-2.5 py-1 rounded-full text-[10px] md:text-xs font-serif font-bold uppercase tracking-wider border transition-all duration-300 flex items-center gap-1.5 shadow-md backdrop-blur-md"
                    :class="scrollPercent > 90 
                        ? 'bg-amber-400 text-rose-950 border-amber-200 animate-pulse scale-105 shadow-amber-300/40' 
                        : 'bg-white/95 text-rose-950 border-rose-200/80'"
                >
                    <Church class="w-3.5 h-3.5 text-rose-600" />
                    <span>Lễ Đường</span>
                    <Sparkles class="w-3 h-3 text-amber-500" />
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
.animate-shimmer {
    animation: shimmer 2s infinite linear;
}
</style>
