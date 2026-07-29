<script setup lang="ts">
import { ref } from 'vue';
import { Sparkles, Maximize2, X } from 'lucide-vue-next';

const photos = [
    { id: 1, src: '/images/gallery/wedding_portrait.png', title: 'Chân Dung Đôi Lứa', desc: 'Nguyễn Hoàng Quốc Trung & Lê Thị Hồng Vân' },
    { id: 2, src: '/images/gallery/wedding_proposal.png', title: 'Khoảnh Khắc Cầu Hôn', desc: 'Lời hứa chân thành dưới ánh chiều tà' },
    { id: 3, src: '/images/gallery/wedding_couple_walk.png', title: 'Sóng Bước Bên Nhau', desc: 'Hành trình vạn dặm đong đầy yêu thương' },
];

const activePhoto = ref<string | null>(null);

const openLightbox = (src: string) => {
    activePhoto.value = src;
};

const closeLightbox = () => {
    activePhoto.value = null;
};
</script>

<template>
    <section class="py-20 px-6 relative bg-gradient-to-b from-[#FAF8F5] via-rose-50/50 to-[#FAF8F5]">
        <div class="max-w-6xl mx-auto space-y-12">
            
            <!-- Header -->
            <div class="text-center space-y-3">
                <span class="text-xs uppercase tracking-[0.4em] text-rose-800 font-mono font-bold block">Gallery</span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-rose-950">
                    Album Ảnh Cưới
                </h2>
                <p class="text-sm font-serif italic text-slate-600">
                    "Lưu giữ từng khoảnh khắc hạnh phúc rực rỡ nhất."
                </p>
            </div>

            <!-- Photos Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="photo in photos" 
                    :key="photo.id"
                    @click="openLightbox(photo.src)"
                    class="group relative rounded-3xl overflow-hidden bg-white border border-rose-200/80 shadow-xl shadow-rose-950/5 cursor-pointer transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
                >
                    <div class="aspect-[4/5] w-full relative overflow-hidden bg-rose-50">
                        <img 
                            :src="photo.src" 
                            :alt="photo.title"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-rose-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 text-white">
                            <span class="text-xs font-mono uppercase tracking-widest text-amber-300 font-bold mb-1">{{ photo.desc }}</span>
                            <h4 class="font-serif font-bold text-xl">{{ photo.title }}</h4>
                        </div>
                    </div>
                    <div class="p-4 text-center bg-white border-t border-rose-100 flex items-center justify-between">
                        <span class="font-serif font-bold text-rose-950 text-sm">{{ photo.title }}</span>
                        <Maximize2 class="w-4 h-4 text-rose-600 opacity-70 group-hover:opacity-100 transition-opacity" />
                    </div>
                </div>
            </div>

            <!-- Lightbox Modal -->
            <div 
                v-if="activePhoto"
                @click="closeLightbox"
                class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4"
            >
                <button 
                    @click="closeLightbox"
                    class="absolute top-6 right-6 p-3 rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors cursor-pointer"
                >
                    <X class="w-6 h-6" />
                </button>
                
                <div class="max-w-4xl max-h-[85vh] rounded-3xl overflow-hidden shadow-2xl flex items-center justify-center">
                    <img 
                        :src="activePhoto" 
                        alt="Ảnh phóng to" 
                        class="max-w-full max-h-[80vh] object-contain rounded-2xl"
                    />
                </div>
            </div>

        </div>
    </section>
</template>
