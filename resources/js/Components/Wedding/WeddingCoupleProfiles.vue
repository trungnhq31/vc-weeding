<script setup lang="ts">
import { Heart, Sparkles } from 'lucide-vue-next';
</script>

<template>
    <section class="py-24 px-6 relative bg-gradient-to-b from-[#FAF8F5] via-rose-50/40 to-[#FAF8F5]">
        <div class="max-w-5xl mx-auto space-y-16">
            
            <!-- Section Header -->
            <div class="text-center space-y-3">
                <span class="text-xs uppercase tracking-[0.4em] text-rose-800 font-mono font-bold block">Groom & Bride</span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-rose-950">
                    Chú Rể & Cô Dâu
                </h2>
                <div class="w-16 h-0.5 bg-gradient-to-r from-rose-300 via-rose-500 to-amber-300 mx-auto rounded-full"></div>
            </div>

            <!-- Couple Profiles Grid (Guaranteed 100% Visible, No Opacity Glitches) -->
            <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-center">
                
                <!-- Groom Card -->
                <div class="p-8 rounded-3xl bg-white/95 border border-rose-200/90 shadow-xl shadow-rose-950/5 text-center flex flex-col items-center space-y-5 backdrop-blur-md relative transform transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-rose-100 border border-rose-300 text-rose-900 text-xs font-serif font-bold uppercase tracking-wider shadow-sm">
                        GROOM • Chú Rể
                    </div>

                    <!-- Groom Portrait -->
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-full p-1.5 bg-gradient-to-tr from-rose-400 via-amber-300 to-rose-600 shadow-lg mt-2 overflow-hidden">
                        <div class="w-full h-full rounded-full overflow-hidden bg-rose-50 relative">
                            <img 
                                src="/images/gallery/wedding_portrait.png" 
                                alt="Chú Rể Nguyễn Hoàng Quốc Trung" 
                                class="w-full h-full object-cover object-left"
                            />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-2xl font-serif font-bold text-rose-950">Nguyễn Hoàng Quốc Trung</h3>
                        <p class="text-xs font-mono font-semibold text-rose-700 uppercase tracking-widest">Kỹ Sư Lập Trình</p>
                    </div>

                    <p class="text-sm font-serif italic text-slate-600 leading-relaxed max-w-xs">
                        "Là một chàng trai điềm tĩnh và ấm áp. Anh tin rằng trên đời này điều tuyệt vời nhất là tìm thấy người lắng nghe mình trong từng khoảnh khắc."
                    </p>
                </div>

                <!-- Bride Card -->
                <div class="p-8 rounded-3xl bg-white/95 border border-rose-200/90 shadow-xl shadow-rose-950/5 text-center flex flex-col items-center space-y-5 backdrop-blur-md relative transform transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-rose-100 border border-rose-300 text-rose-900 text-xs font-serif font-bold uppercase tracking-wider shadow-sm">
                        BRIDE • Cô Dâu
                    </div>

                    <!-- Bride Portrait -->
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-full p-1.5 bg-gradient-to-tr from-rose-400 via-amber-300 to-rose-600 shadow-lg mt-2 overflow-hidden">
                        <div class="w-full h-full rounded-full overflow-hidden bg-rose-50 relative">
                            <img 
                                src="/images/gallery/wedding_portrait.png" 
                                alt="Cô Dâu Lê Thị Hồng Vân" 
                                class="w-full h-full object-cover object-right"
                            />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-2xl font-serif font-bold text-rose-950">Lê Thị Hồng Vân</h3>
                        <p class="text-xs font-mono font-semibold text-rose-700 uppercase tracking-widest">Thiết Kế Thời Trang</p>
                    </div>

                    <p class="text-sm font-serif italic text-slate-600 leading-relaxed max-w-xs">
                        "Một cô gái dịu dàng và luôn tràn đầy nụ cười. Cô luôn vun vén cho tình yêu bằng sự tận tụy và ước mơ về một mái ấm đong đầy yêu thương."
                    </p>
                </div>

            </div>
        </div>
    </section>
</template>
