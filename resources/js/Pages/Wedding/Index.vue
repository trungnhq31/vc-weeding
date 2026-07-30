<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { 
    Heart, Calendar, MapPin, Sparkles, Send, Clock, 
    ChevronDown, CheckCircle2, UserCheck, Gift, ArrowRight
} from 'lucide-vue-next';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/dist/ScrollTrigger';
import Lenis from 'lenis';

import WeddingEnvelopeModal from '../../Components/Wedding/WeddingEnvelopeModal.vue';
import WeddingMusicPlayer from '../../Components/Wedding/WeddingMusicPlayer.vue';
import WeddingCoupleProfiles from '../../Components/Wedding/WeddingCoupleProfiles.vue';
import WeddingLoveStory from '../../Components/Wedding/WeddingLoveStory.vue';
import WeddingPhotoGallery from '../../Components/Wedding/WeddingPhotoGallery.vue';
import WeddingScheduleAndMap from '../../Components/Wedding/WeddingScheduleAndMap.vue';
import WeddingGiftBoxModal from '../../Components/Wedding/WeddingGiftBoxModal.vue';
import WeddingScrollProgressBar from '../../Components/Wedding/WeddingScrollProgressBar.vue';

interface Wish {
    id: string;
    sender_name: string;
    message: string;
    created_at: string;
}

interface Memory {
    id: string;
    uploader_name: string;
    category: string;
    title?: string;
    description?: string;
    image_url: string;
}

defineProps<{
    wishes: Wish[];
    memories?: Memory[];
    guest?: any;
}>();

const page = usePage();
const authUser = computed(() => (page.props as any).auth?.user);


const musicPlayerRef = ref<any>(null);

// Wish Form state
const wishSender = ref('');
const wishMessage = ref('');
const isSubmittingWish = ref(false);
const wishSuccess = ref(false);

// RSVP Form state
const rsvpName = ref('');
const rsvpAttending = ref('yes');
const rsvpGuestsCount = ref(1);
const rsvpMessage = ref('');
const isSubmittingRsvp = ref(false);
const rsvpSuccess = ref(false);

// Countdown Timer State (19.12.2026)
const days = ref(0);
const hours = ref(0);
const minutes = ref(0);
const seconds = ref(0);
let countdownInterval: number | null = null;

const updateCountdown = () => {
    const targetDate = new Date('2026-12-19T18:00:00+07:00').getTime();
    const now = new Date().getTime();
    const diff = targetDate - now;

    if (diff > 0) {
        days.value = Math.floor(diff / (1000 * 60 * 60 * 24));
        hours.value = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        minutes.value = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        seconds.value = Math.floor((diff % (1000 * 60)) / 1000);
    }
};

const onEnvelopeOpen = () => {
    musicPlayerRef.value?.playMusic();
};

let lenis: Lenis | null = null;

const submitWish = async () => {
    if (!wishSender.value || !wishMessage.value) return;
    isSubmittingWish.value = true;

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
        isSubmittingWish.value = false;
    }
};

const submitRsvp = async () => {
    if (!rsvpName.value) return;
    isSubmittingRsvp.value = true;

    try {
        const response = await fetch('/wedding/rsvp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                guest_name: rsvpName.value,
                status: rsvpAttending.value,
                guests_count: rsvpGuestsCount.value,
                note: rsvpMessage.value,
            }),
        });

        if (response.ok) {
            rsvpSuccess.value = true;
            setTimeout(() => { rsvpSuccess.value = false; }, 5000);
        }
    } catch (e) {
        console.error('Error submitting RSVP:', e);
    } finally {
        isSubmittingRsvp.value = false;
    }
};

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    updateCountdown();
    countdownInterval = window.setInterval(updateCountdown, 1000);

    lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        smoothTouch: false,
    });

    lenis.on('scroll', ScrollTrigger.update);

    const rafHandler = (time: number) => {
        lenis?.raf(time * 1000);
    };

    gsap.ticker.add(rafHandler);
    gsap.ticker.lagSmoothing(0);

    gsap.from('.stagger-card', {
        y: 40,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '#rsvp-wishes',
            start: 'top 70%',
        }
    });
});

onUnmounted(() => {
    if (countdownInterval !== null) clearInterval(countdownInterval);
    ScrollTrigger.getAll().forEach(t => t.kill());
    if (lenis) lenis.destroy();
});
</script>

<template>
    <Head title="Quốc Trung & Hồng Vân | Thiệp Cưới Online" />

    <!-- 1. Wax Seal Envelope Opening Modal -->
    <WeddingEnvelopeModal @open="onEnvelopeOpen" />

    <!-- 2. Background Romantic Music Player -->
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- 3. Fixed Bottom Viewport 30 FPS Progress Bar -->
    <WeddingScrollProgressBar />

    <!-- Main Container -->
    <main class="relative z-20 font-sans selection:bg-rose-500 selection:text-white bg-[#FAF8F5] pb-24 text-slate-800">
        
        <!-- Header Navigation Bar -->
        <nav class="fixed top-0 left-0 right-0 z-40 backdrop-blur-md bg-white/90 border-b border-rose-200/50 shadow-sm transition-colors duration-500">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <!-- Brand Logo: Only Logo Image (Larger) + Eloria Text -->
                <Link href="/" class="flex items-center gap-3 group">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-12 md:h-14 w-auto rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
                    <span class="font-serif text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
                </Link>

                <div class="flex items-center gap-2 md:gap-3">
                    <template v-if="authUser">
                        <Link href="/wedding/timeline" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md flex items-center gap-1.5 hover:scale-105 active:scale-95">
                            Vào Workspace
                            <ArrowRight class="w-4 h-4 text-rose-400" />
                        </Link>
                    </template>
                    <template v-else>
                        <Link href="/login" class="px-3.5 py-2 rounded-xl text-slate-700 hover:text-rose-700 hover:bg-rose-50 font-semibold text-xs md:text-sm transition-all">
                            Đăng nhập
                        </Link>
                        <Link href="/register" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md shadow-slate-900/10 flex items-center gap-1.5 hover:scale-105 active:scale-95">
                            Đăng ký
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- HERO BANNER: Thiệp Cưới Điện Tử Cao Cấp -->
        <section class="min-h-screen flex flex-col justify-center items-center px-6 relative text-center pt-20 pb-20 bg-gradient-to-b from-rose-50/80 via-amber-50/40 to-[#FAF8F5]">
            <div class="max-w-3xl mx-auto space-y-8">
                <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/90 border border-rose-200 text-rose-900 text-xs font-bold uppercase tracking-widest shadow-sm backdrop-blur-md">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Icon" class="w-4 h-4 rounded-full" />
                    <Sparkles class="w-4 h-4 text-rose-600 fill-rose-400/40" /> Eloria Wedding OS • 19.12.2026
                </div>

                <div class="space-y-4">
                    <span class="text-xs uppercase tracking-[0.5em] text-rose-800 font-mono font-bold block">Save The Date</span>
                    <h1 class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-6 font-serif font-bold text-rose-950 tracking-tight leading-tight">
                        <span class="text-4xl sm:text-6xl md:text-7xl lg:text-8xl whitespace-nowrap">Quốc Trung</span>
                        <span class="text-3xl sm:text-5xl md:text-6xl text-rose-500 font-light font-serif">&</span>
                        <span class="text-4xl sm:text-6xl md:text-7xl lg:text-8xl whitespace-nowrap">Hồng Vân</span>
                    </h1>
                </div>

                <p class="text-lg md:text-xl text-slate-700 font-serif italic max-w-md mx-auto leading-relaxed">
                    "Hành trình vạn dặm đong đầy hạnh phúc bắt đầu từ một cái nắm tay."
                </p>

                <!-- Wedding Countdown Timer Badge Grid -->
                <div class="pt-4 grid grid-cols-4 gap-3 max-w-md mx-auto">
                    <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-md text-center">
                        <span class="text-2xl md:text-3xl font-serif font-bold text-rose-950 block">{{ days }}</span>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-rose-700 font-bold">Ngày</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-md text-center">
                        <span class="text-2xl md:text-3xl font-serif font-bold text-rose-950 block">{{ hours }}</span>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-rose-700 font-bold">Giờ</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-md text-center">
                        <span class="text-2xl md:text-3xl font-serif font-bold text-rose-950 block">{{ minutes }}</span>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-rose-700 font-bold">Phút</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-md text-center">
                        <span class="text-2xl md:text-3xl font-serif font-bold text-rose-950 block">{{ seconds }}</span>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-rose-700 font-bold">Giây</span>
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div class="pt-4 flex flex-wrap justify-center gap-4 text-sm font-bold">
                    <a href="#rsvp-wishes" class="px-8 py-3.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white shadow-lg shadow-rose-200 transition-all flex items-center gap-2 cursor-pointer">
                        <Heart class="w-4 h-4 fill-white" /> Xác Nhận Tham Dự
                    </a>
                    
                    <WeddingGiftBoxModal />
                </div>
            </div>
        </section>

        <!-- 4. Groom & Bride Profiles -->
        <WeddingCoupleProfiles />

        <!-- 5. Love Story Timeline -->
        <WeddingLoveStory />

        <!-- 6. Pre-wedding Photo Gallery -->
        <WeddingPhotoGallery :memories="memories" />

        <!-- 7. Schedule Program & Interactive Location Map -->
        <WeddingScheduleAndMap />

        <!-- 8. Confirmation & Wishes Wall -->
        <section id="rsvp-wishes" class="py-24 px-6 relative bg-white/80 border-t border-rose-100">
            <div class="max-w-5xl mx-auto space-y-16">
                <!-- Section Header -->
                <div class="text-center space-y-3">
                    <span class="text-xs uppercase tracking-[0.3em] text-rose-600 font-mono">Phản Hồi & Lời Chúc</span>
                    <h2 class="text-4xl md:text-5xl font-serif font-bold text-rose-950">
                        Xác Nhận Tham Dự & Sổ Lời Chúc
                    </h2>
                </div>

                <!-- Interactive Forms Grid: Attendance & Wishes -->
                <div class="grid lg:grid-cols-2 gap-10">
                    <!-- Attendance Form -->
                    <div class="stagger-card p-8 rounded-3xl bg-white border border-rose-200/80 shadow-xl shadow-rose-100/60">
                        <h3 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                            <UserCheck class="w-6 h-6 text-rose-500" /> Xác Nhận Tham Dự
                        </h3>

                        <form @submit.prevent="submitRsvp" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Họ & Tên</label>
                                <input 
                                    v-model="rsvpName" 
                                    type="text" 
                                    required 
                                    placeholder="Nhập họ và tên..." 
                                    class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/20"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Xác Nhận</label>
                                    <select 
                                        v-model="rsvpAttending"
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/20 font-medium"
                                    >
                                        <option value="yes">Sẽ Tham Dự</option>
                                        <option value="no">Tiếc Là Không Thể</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Số Người Đi Kèm</label>
                                    <input 
                                        v-model.number="rsvpGuestsCount" 
                                        type="number" 
                                        min="1" 
                                        max="5"
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/20"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Ghi Chú</label>
                                <textarea 
                                    v-model="rsvpMessage" 
                                    rows="2" 
                                    placeholder="VD: Dùng món chay..." 
                                    class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/20"
                                ></textarea>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="isSubmittingRsvp"
                                class="w-full py-3.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-md shadow-rose-200 transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <CheckCircle2 class="w-5 h-5" /> Gửi Xác Nhận Tham Dự
                            </button>

                            <div v-if="rsvpSuccess" class="p-3 rounded-xl bg-emerald-100 text-emerald-800 text-sm font-semibold text-center">
                                Cảm ơn bạn! Xác nhận đã được phản hồi.
                            </div>
                        </form>
                    </div>

                    <!-- Wishes Form -->
                    <div class="stagger-card p-8 rounded-3xl bg-white border border-rose-200/80 shadow-xl shadow-rose-100/60 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                                <Send class="w-6 h-6 text-rose-500" /> Gửi Lời Chúc
                            </h3>

                            <form @submit.prevent="submitWish" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Tên Của Bạn</label>
                                    <input 
                                        v-model="wishSender" 
                                        type="text" 
                                        required 
                                        placeholder="Tên người gửi..." 
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/20"
                                    />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">Lời Chúc</label>
                                    <textarea 
                                        v-model="wishMessage" 
                                        rows="3" 
                                        required 
                                        placeholder="Gửi lời chúc đến Quốc Trung & Hồng Vân..." 
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none text-slate-800 bg-rose-50/20"
                                    ></textarea>
                                </div>
                                <button 
                                    type="submit" 
                                    :disabled="isSubmittingWish"
                                    class="w-full py-3.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-md shadow-rose-200 transition-all flex items-center justify-center gap-2 cursor-pointer"
                                >
                                    <Heart class="w-4 h-4 fill-white" /> Gửi Lời Chúc Mừng
                                </button>

                                <div v-if="wishSuccess" class="p-3 rounded-xl bg-emerald-100 text-emerald-800 text-sm font-semibold text-center">
                                    Cảm ơn bạn! Lời chúc đã được cập nhật.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Wishes Stream List -->
                <div class="stagger-card pt-8">
                    <h3 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                        <Sparkles class="w-5 h-5 text-rose-500" /> Sổ Lời Chúc Yêu Thương
                    </h3>
                    <div v-if="wishes && wishes.length > 0" class="grid sm:grid-cols-2 gap-4">
                        <div v-for="wish in wishes" :key="wish.id" class="p-6 rounded-2xl bg-white border border-rose-200/70 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-rose-950 text-base">{{ wish.sender_name }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ new Date(wish.created_at).toLocaleTimeString() }}</span>
                            </div>
                            <p class="text-slate-700 text-sm leading-relaxed font-medium">{{ wish.message }}</p>
                        </div>
                    </div>
                    <div v-else class="p-8 text-center rounded-2xl bg-rose-50/30 border border-rose-200 text-slate-500 font-serif italic">
                        Hãy là người đầu tiên gửi lời chúc mừng đến Quốc Trung & Hồng Vân!
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>
