<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Disc, Music, Volume2, VolumeX } from 'lucide-vue-next';

const isPlaying = ref(false);
const audioRef = ref<HTMLAudioElement | null>(null);

const toggleMusic = () => {
    if (!audioRef.value) return;
    
    if (isPlaying.value) {
        audioRef.value.pause();
        isPlaying.value = false;
    } else {
        audioRef.value.play().then(() => {
            isPlaying.value = true;
        }).catch((e) => {
            console.log('Audio autoplay prevented:', e);
        });
    }
};

const playMusic = () => {
    if (audioRef.value && !isPlaying.value) {
        audioRef.value.play().then(() => {
            isPlaying.value = true;
        }).catch(() => {});
    }
};

defineExpose({ playMusic });

onMounted(() => {
    // Soft audio loop sample
});
</script>

<template>
    <div>
        <!-- Audio Source -->
        <audio 
            ref="audioRef" 
            src="https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3?filename=romantic-wedding-acoustic-guitar-113579.mp3" 
            loop 
        />

        <!-- Floating Music Player Button -->
        <div class="fixed bottom-20 left-4 z-40 pointer-events-auto">
            <button 
                @click="toggleMusic"
                class="group relative px-3 py-2 rounded-full bg-white/90 border border-rose-200 shadow-xl backdrop-blur-md flex items-center gap-2 text-rose-950 text-xs font-serif font-bold transition-all duration-300 hover:scale-105 active:scale-95 cursor-pointer"
            >
                <!-- Rotating Vinyl Disc Icon -->
                <div 
                    class="w-7 h-7 rounded-full bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center text-white shadow-sm"
                    :class="{ 'animate-spin-slow': isPlaying }"
                >
                    <Disc class="w-4 h-4" />
                </div>

                <span class="hidden sm:inline">{{ isPlaying ? 'Bật Nhạc Nền' : 'Tắt Nhạc Nền' }}</span>

                <component :is="isPlaying ? Volume2 : VolumeX" class="w-4 h-4 text-rose-600" />

                <!-- Floating Musical Notes Animation -->
                <div v-if="isPlaying" class="absolute -top-3 -right-1 flex gap-1 pointer-events-none">
                    <Music class="w-3 h-3 text-rose-500 animate-bounce" />
                    <Music class="w-3.5 h-3.5 text-amber-500 animate-pulse delay-150" />
                </div>
            </button>
        </div>
    </div>
</template>

<style scoped>
@keyframes spinSlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin-slow {
    animation: spinSlow 5s linear infinite;
}
</style>
