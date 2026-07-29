<script setup lang="ts">
import { Heart, Sparkles, Camera, Gift, Coffee } from 'lucide-vue-next';

const milestones = [
    {
        year: 'Tháng 10, 2023',
        title: 'Lần Đầu Gặp Mặt',
        subtitle: 'Khoảnh khắc ánh mắt chạm nhau',
        desc: 'Một buổi chiều thu dịu mát, ánh mắt chạm nhau trong một cuộc hẹn tình cờ của nhóm bạn thân. Khoảnh khắc ấy, cả hai đều cảm nhận được tín hiệu của định mệnh.',
        image: '/images/gallery/wedding_portrait.png',
        icon: Coffee,
    },
    {
        year: 'Tháng 02, 2024',
        title: 'Buổi Hẹn Hò Đầu Tiên',
        subtitle: 'Khởi đầu một hành trình ngọt ngào',
        desc: 'Ly cà phê ấm nóng và những câu chuyện kéo dài không hồi kết. Nụ cười rạng rỡ của Hồng Vân đã xua tan đi mọi âu lo trong lòng Quốc Trung.',
        image: '/images/gallery/wedding_couple_walk.png',
        icon: Camera,
    },
    {
        year: 'Tháng 03, 2026',
        title: 'Lời Cầu Hôn Ngọt Ngào',
        subtitle: 'Dưới ánh nến lung linh bên bờ biển',
        desc: 'Dưới ánh nến lung linh bên bờ biển, Quốc Trung trao chiếc nhẫn nhỏ cùng lời hứa: "Hãy để anh được chăm sóc em suốt phần đời còn lại". Hồng Vân mỉm cười gật đầu trong hạnh phúc.',
        image: '/images/gallery/wedding_proposal.png',
        icon: Gift,
    },
    {
        year: '19 Tháng 12, 2026',
        title: 'Ngày Về Chung Một Nhà',
        subtitle: 'Lễ thành hôn đong đầy yêu thương',
        desc: 'Hành trình vạn dặm đong đầy hạnh phúc chính thức bước sang trang mới, nắm chặt tay nhau tiến vào Lễ Đường trước sự chứng kiến của gia đình và bạn bè.',
        image: '/images/clipart/wedding_altar_gate.png',
        icon: Sparkles,
    },
];
</script>

<template>
    <section class="py-24 pb-40 px-6 relative bg-gradient-to-b from-[#FAF8F5] via-rose-50/30 to-[#FAF8F5]">
        <div class="max-w-5xl mx-auto space-y-20">
            
            <!-- Header -->
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100/80 text-rose-900 text-xs font-bold uppercase tracking-widest border border-rose-200">
                    <Heart class="w-3.5 h-3.5 text-rose-600 fill-rose-500/40" /> Our Love Journey
                </div>
                <h2 class="text-4xl md:text-6xl font-serif font-bold text-rose-950 tracking-tight">
                    Chuyện Tình Yêu
                </h2>
                <p class="text-base font-serif italic text-slate-600 max-w-md mx-auto">
                    "Mỗi khoảnh khắc bên nhau đều là một mảnh ghép kỳ diệu kiến tạo nên hạnh phúc."
                </p>
            </div>

            <!-- Alternating Magazine Timeline Grid -->
            <div class="relative">
                <!-- Center Vertical Spine Line (Desktop) -->
                <div class="hidden md:block absolute left-1/2 top-4 bottom-4 w-1 bg-gradient-to-b from-rose-300 via-rose-500 to-amber-300 transform -translate-x-1/2 rounded-full opacity-60"></div>

                <div class="space-y-16 md:space-y-24">
                    <div 
                        v-for="(item, idx) in milestones" 
                        :key="idx" 
                        class="relative flex flex-col md:flex-row items-center gap-8 md:gap-12"
                        :class="idx % 2 === 1 ? 'md:flex-row-reverse' : ''"
                    >
                        <!-- Timeline Heart Center Node (Desktop) -->
                        <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 w-12 h-12 rounded-full bg-white border-4 border-rose-500 shadow-lg items-center justify-center text-rose-600 z-10">
                            <component :is="item.icon" class="w-5 h-5 fill-rose-100 text-rose-600" />
                        </div>

                        <!-- Image Card Half -->
                        <div class="w-full md:w-1/2">
                            <div class="group relative rounded-3xl overflow-hidden bg-white p-3 border border-rose-200/80 shadow-xl shadow-rose-950/5 transform transition-transform duration-500 hover:scale-[1.02]">
                                <div class="aspect-[4/3] w-full rounded-2xl overflow-hidden bg-rose-50 relative">
                                    <img 
                                        :src="item.image" 
                                        :alt="item.title"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Content Card Half -->
                        <div class="w-full md:w-1/2 text-left space-y-4">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-rose-100 text-rose-900 text-xs font-mono font-bold uppercase tracking-wider border border-rose-200">
                                <Sparkles class="w-3 h-3 text-rose-600" /> {{ item.year }}
                            </div>

                            <div class="space-y-1">
                                <h3 class="text-2xl md:text-3xl font-serif font-bold text-rose-950">{{ item.title }}</h3>
                                <p class="text-xs font-mono font-bold text-rose-700 uppercase tracking-widest">{{ item.subtitle }}</p>
                            </div>

                            <p class="text-sm md:text-base font-serif text-slate-600 leading-relaxed font-medium">
                                {{ item.desc }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>
</template>
