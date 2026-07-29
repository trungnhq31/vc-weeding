<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Heart, Calendar, MapPin, Sparkles, Send, Clock, Gift, Music } from 'lucide-vue-next';
import { ref } from 'vue';

interface Wish {
    id: string;
    sender_name: string;
    message: string;
    created_at: string;
}

defineProps<{
    wishes: Wish[];
    guest?: any;
}>();

const wishSender = ref('');
const wishMessage = ref('');
const isSubmitting = ref(false);
const wishSuccess = ref(false);

const submitWish = async () => {
    if (!wishSender.value || !wishMessage.value) return;
    isSubmitting.value = true;

    try {
        const response = await fetch('/wedding/wishes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                sender_name: wishSender.value,
                message: wishMessage.value,
            }),
        });

        if (response.ok) {
            wishSuccess.value = true;
            wishSender.value = '';
            wishMessage.value = '';
            setTimeout(() => { wishSuccess.value = false; }, 4000);
        }
    } catch (e) {
        console.error('Error submitting wish:', e);
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <Head title="Thiệp Cưới Online - Vân & Cẩm (19/12/2026)" />

    <!-- Bright Warm Cream Background with Soft Pastel Rose Gradients -->
    <div class="min-h-screen bg-[#FAF8F5] text-slate-800 font-sans bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-rose-100/60 via-amber-50/40 to-[#FAF8F5]">
        <!-- Top Navigation -->
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-rose-200/60 shadow-xs">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="font-serif font-bold text-xl text-rose-950 flex items-center gap-2">
                    <Heart class="w-5 h-5 text-rose-500 fill-rose-500/30" /> Vân & Cẩm
                </div>
                <div class="flex items-center gap-4 text-xs md:text-sm font-semibold">
                    <Link href="/wedding/timeline" class="px-4 py-2 rounded-full bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors flex items-center gap-1.5 shadow-xs">
                        <Clock class="w-4 h-4 text-rose-600" /> Lộ Trình Chuẩn Bị Cưới
                    </Link>
                    <Link href="/" class="text-slate-600 hover:text-rose-950 transition-colors">Portfolio</Link>
                </div>
            </div>
        </nav>

        <!-- Elegant Hero Banner -->
        <header class="py-20 text-center px-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-rose-100/50 via-transparent to-transparent pointer-events-none"></div>

            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100 border border-rose-300 text-rose-800 text-xs font-semibold uppercase tracking-wider mb-6 shadow-xs">
                <Heart class="w-4 h-4 text-rose-500 fill-rose-500/40" /> Save The Date • 19.12.2026
            </div>
            
            <h1 class="text-6xl md:text-8xl font-serif font-bold text-rose-950 tracking-tight leading-none mb-4">
                Vân & Cẩm
            </h1>

            <p class="mt-4 text-lg md:text-xl text-slate-700 font-serif italic max-w-lg mx-auto">
                "Hành trình vạn dặm bắt đầu từ một cái nắm tay..."
            </p>

            <div class="mt-8 flex justify-center gap-3 text-xs md:text-sm font-bold">
                <Link href="/wedding/timeline" class="px-6 py-3 rounded-full bg-rose-600 hover:bg-rose-700 text-white shadow-md shadow-rose-200 transition-all flex items-center gap-2">
                    <Clock class="w-4 h-4" /> Xem Tiến Độ Chuẩn Bị
                </Link>
            </div>
        </header>

        <!-- Wedding Details Cards Section -->
        <section class="max-w-4xl mx-auto px-6 grid md:grid-cols-2 gap-6 mb-16">
            <div class="p-8 rounded-3xl bg-white border border-rose-200/80 shadow-md shadow-rose-100/50 text-center flex flex-col items-center">
                <div class="p-4 rounded-2xl bg-rose-100 text-rose-600 mb-4 border border-rose-200">
                    <Calendar class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-serif font-bold text-rose-950 mb-2">Thời Gian Tổ Chức</h3>
                <p class="text-rose-800 font-bold text-base">Thứ Bảy, Ngày 19 Tháng 12 Năm 2026</p>
                <p class="text-slate-600 text-sm mt-1 font-medium">Đón khách: 17:30 • Khai tiệc: 18:30</p>
            </div>

            <div class="p-8 rounded-3xl bg-white border border-rose-200/80 shadow-md shadow-rose-100/50 text-center flex flex-col items-center">
                <div class="p-4 rounded-2xl bg-rose-100 text-rose-600 mb-4 border border-rose-200">
                    <MapPin class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-serif font-bold text-rose-950 mb-2">Địa Điểm Tổ Chức</h3>
                <p class="text-rose-800 font-bold text-base">Trung Tâm Tiệc Cưới Asiana Plaza</p>
                <p class="text-slate-600 text-sm mt-1 font-medium">Sảnh Tiệc Cao Cấp • TP. Hồ Chí Minh</p>
            </div>
        </section>

        <!-- Realtime Wishes Form & Stream Section -->
        <section class="max-w-4xl mx-auto px-6 pb-24">
            <div class="p-8 rounded-3xl bg-white border border-rose-200/80 shadow-lg shadow-rose-100/50 mb-12">
                <h2 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                    <Send class="w-6 h-6 text-rose-500" /> Gửi Lời Chúc Mừng
                </h2>

                <form @submit.prevent="submitWish" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Tên Của Bạn</label>
                        <input 
                            v-model="wishSender" 
                            type="text" 
                            required 
                            placeholder="Ví dụ: Anh Tuấn & Chị Lan" 
                            class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/30"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Lời Chúc Dành Tặng Cô Dâu & Chú Rể</label>
                        <textarea 
                            v-model="wishMessage" 
                            rows="3" 
                            required 
                            placeholder="Gửi những lời chúc yêu thương đến hai bạn..." 
                            class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/30"
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        :disabled="isSubmitting"
                        class="px-8 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-md shadow-rose-200 transition-all flex items-center gap-2 cursor-pointer"
                    >
                        <Heart class="w-4 h-4 fill-white" /> Gửi Lời Chúc Realtime
                    </button>
                    <div v-if="wishSuccess" class="p-3 rounded-xl bg-emerald-100 text-emerald-800 text-sm font-semibold">
                        Cảm ơn bạn! Lời chúc đã được cập nhật thành công.
                    </div>
                </form>
            </div>

            <!-- Wishes List -->
            <h2 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                <Sparkles class="w-5 h-5 text-rose-500" /> Sổ Lời Chúc Realtime
            </h2>
            <div v-if="wishes && wishes.length > 0" class="space-y-4">
                <div v-for="wish in wishes" :key="wish.id" class="p-6 rounded-2xl bg-white border border-rose-200/70 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-rose-950 text-base">{{ wish.sender_name }}</span>
                        <span class="text-xs text-slate-400 font-medium">{{ new Date(wish.created_at).toLocaleTimeString() }}</span>
                    </div>
                    <p class="text-slate-700 text-sm leading-relaxed font-medium">{{ wish.message }}</p>
                </div>
            </div>
            <div v-else class="p-8 text-center rounded-2xl bg-white border border-rose-200 text-slate-500">
                Hãy là người đầu tiên gửi lời chúc mừng đến cô dâu và chú rể!
            </div>
        </section>
    </div>
</template>
