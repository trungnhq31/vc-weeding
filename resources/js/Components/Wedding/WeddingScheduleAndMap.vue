<script setup lang="ts">
import { Calendar, Clock, MapPin, Navigation, Sparkles, Utensils, Music2, PartyPopper } from 'lucide-vue-next';

const schedule = [
    { time: '17:30', title: 'Đón Tiếp Khách Mời', desc: 'Chụp hình kỷ niệm cùng Chú Rể & Cô Dâu', icon: Clock },
    { time: '18:00', title: 'Lễ Thành Hôn', desc: 'Nghi lễ chính thức & trao nhẫn cưới', icon: Sparkles },
    { time: '18:30', title: 'Khai Tiệc Mừng Cưới', desc: 'Thưởng thức tiệc tối sang trọng', icon: Utensils },
    { time: '19:30', title: 'Giao Lưu & Âm Nhạc', desc: 'Trò chơi may mắn & gửi lời chúc yêu thương', icon: PartyPopper },
];
</script>

<template>
    <section class="py-20 px-6 relative bg-[#FAF8F5]">
        <div class="max-w-5xl mx-auto space-y-16">
            
            <!-- Header -->
            <div class="text-center space-y-3">
                <span class="text-xs uppercase tracking-[0.4em] text-rose-800 font-mono font-bold block">Event Details</span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-rose-950">
                    Chương Trình Lễ Cưới & Địa Điểm
                </h2>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-start">
                
                <!-- Schedule List -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-serif font-bold text-rose-950 flex items-center gap-2">
                        <Calendar class="w-6 h-6 text-rose-600" /> Chương Trình Tiệc Cưới
                    </h3>

                    <div class="space-y-4">
                        <div 
                            v-for="(item, idx) in schedule" 
                            :key="idx"
                            class="p-5 rounded-2xl bg-white border border-rose-200/80 shadow-md shadow-rose-950/5 flex items-start gap-4"
                        >
                            <div class="px-3 py-1.5 rounded-xl bg-rose-100 text-rose-900 text-sm font-bold font-mono border border-rose-300/50 whitespace-nowrap">
                                {{ item.time }}
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="font-serif font-bold text-rose-950 text-base flex items-center gap-1.5">
                                    <component :is="item.icon" class="w-4 h-4 text-rose-500" />
                                    {{ item.title }}
                                </h4>
                                <p class="text-xs font-serif text-slate-600">{{ item.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Interactive Navigation -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-serif font-bold text-rose-950 flex items-center gap-2">
                        <MapPin class="w-6 h-6 text-rose-600" /> Địa Điểm Tổ Chức
                    </h3>

                    <div class="p-8 rounded-3xl bg-white border border-rose-200/80 shadow-xl shadow-rose-950/5 space-y-6">
                        <div class="space-y-2">
                            <h4 class="text-2xl font-serif font-bold text-rose-950">Trung Tâm Hội Nghị Asiana Plaza</h4>
                            <p class="text-sm font-serif text-slate-600 font-medium">45-47 Phan Đăng Lưu, Phường 3, Quận Bình Thạnh, TP. Hồ Chí Minh</p>
                        </div>

                        <div class="pt-2 flex flex-wrap gap-4">
                            <a 
                                href="https://maps.google.com/?q=Asiana+Plaza+Binh+Thanh" 
                                target="_blank" 
                                class="px-6 py-3 rounded-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-200 transition-all flex items-center gap-2"
                            >
                                <Navigation class="w-4 h-4" /> Xem Chỉ Đường Google Maps
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</template>
