<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { 
    Heart, Calendar, MapPin, Sparkles, Send, Clock, 
    CheckCircle2, UserCheck, Bus, QrCode, Utensils, LayoutGrid
} from 'lucide-vue-next';

import WeddingEnvelopeModal from '../../Components/Wedding/WeddingEnvelopeModal.vue';
import WeddingMusicPlayer from '../../Components/Wedding/WeddingMusicPlayer.vue';
import WeddingCoupleProfiles from '../../Components/Wedding/WeddingCoupleProfiles.vue';
import WeddingLoveStory from '../../Components/Wedding/WeddingLoveStory.vue';
import WeddingPhotoGallery from '../../Components/Wedding/WeddingPhotoGallery.vue';
import WeddingScheduleAndMap from '../../Components/Wedding/WeddingScheduleAndMap.vue';
import WeddingGiftBoxModal from '../../Components/Wedding/WeddingGiftBoxModal.vue';
import WeddingScrollProgressBar from '../../Components/Wedding/WeddingScrollProgressBar.vue';

interface Guest {
    id: string;
    guest_slug: string;
    name: string;
    salutation: string;
    group?: string;
    estimated_count: number;
    confirmed_count: number;
    dietary_preference?: string;
    shuttle_bus?: string;
    qr_code_token?: string;
    table_name?: string;
    rsvp_status: string;
    notes?: string;
}

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

const props = defineProps<{
    guest: Guest;
    wishes: Wish[];
    memories?: Memory[];
}>();

const musicPlayerRef = ref<any>(null);

// RSVP Form State prefilled with guest data
const rsvpAttending = ref<string>(props.guest.rsvp_status === 'attending' ? 'yes' : (props.guest.rsvp_status === 'declined' ? 'no' : 'yes'));
const rsvpGuestsCount = ref<number>(props.guest.confirmed_count || props.guest.estimated_count || 1);
const rsvpDietary = ref<string>(props.guest.dietary_preference || '');
const rsvpShuttleBus = ref<string>(props.guest.shuttle_bus || 'no');
const rsvpNotes = ref<string>(props.guest.notes || '');
const isSubmittingRsvp = ref(false);
const rsvpSuccess = ref(false);

// Wish Form State
const wishSender = ref(props.guest.name || '');
const wishMessage = ref('');
const isSubmittingWish = ref(false);
const wishSuccess = ref(false);

const onEnvelopeOpen = () => {
    musicPlayerRef.value?.playMusic();
};

const submitRsvp = async () => {
    isSubmittingRsvp.value = true;
    try {
        const response = await fetch('/wedding/rsvp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                guest_slug: props.guest.guest_slug,
                guest_name: props.guest.name,
                rsvp_status: rsvpAttending.value === 'yes' ? 'attending' : 'declined',
                confirmed_count: rsvpGuestsCount.value,
                dietary_preference: rsvpDietary.value,
                shuttle_bus: rsvpShuttleBus.value,
                notes: rsvpNotes.value,
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
            wishMessage.value = '';
            setTimeout(() => { wishSuccess.value = false; }, 4000);
        }
    } catch (e) {
        console.error('Error submitting wish:', e);
    } finally {
        isSubmittingWish.value = false;
    }
};
</script>

<template>
    <Head :title="`Thiệp Cưới Dành Tặng ${guest.salutation || guest.name}`" />

    <!-- 1. Interactive Wax Seal Envelope Opening Modal customized for this Guest -->
    <WeddingEnvelopeModal @open="onEnvelopeOpen" />

    <!-- 2. Background Romantic Music Player -->
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- 3. Viewport Scroll Progress Bar -->
    <WeddingScrollProgressBar />

    <!-- Main Content -->
    <main class="relative z-20 font-sans bg-[#FAF8F5] pb-24 text-slate-800 selection:bg-rose-500 selection:text-white">
        
        <!-- Header Nav -->
        <nav class="fixed top-0 left-0 right-0 z-40 backdrop-blur-md bg-white/80 border-b border-rose-200/50 shadow-sm">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="font-serif font-bold text-base md:text-lg flex items-center gap-2.5 text-rose-950">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-8 w-auto rounded-lg shadow-xs border border-rose-200/60" />
                    <span class="text-rose-950 font-serif">Eloria • Quốc Trung & Hồng Vân</span>
                </div>
                <div class="flex items-center gap-3">
                    <Link href="/wedding" class="text-xs font-semibold px-4 py-2 rounded-full bg-rose-100/80 hover:bg-rose-200 text-rose-900 transition-all">
                        Trang Báo Hỷ
                    </Link>
                </div>
            </div>
        </nav>

        <!-- PERSONALIZED HERO BANNER -->
        <section class="min-h-screen flex flex-col justify-center items-center px-6 text-center pt-24 pb-20 bg-gradient-to-b from-rose-100/70 via-amber-50/40 to-[#FAF8F5] relative overflow-hidden">
            
            <div class="max-w-3xl mx-auto space-y-8 relative z-10">
                <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/90 border border-rose-300 text-rose-900 text-xs font-bold uppercase tracking-widest shadow-sm backdrop-blur-md">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Icon" class="w-4 h-4 rounded-full" />
                    ✨ Eloria Wedding OS
                </div>

                <div class="space-y-3">
                    <span class="text-xs font-mono uppercase tracking-[0.4em] text-rose-800 font-bold block">TRÂN TRỌNG KÍNH MỜI</span>
                    <h1 class="text-4xl md:text-6xl font-serif font-extrabold text-rose-950 tracking-tight leading-tight">
                        {{ guest.salutation || `Kính mời ${guest.name}` }}
                    </h1>
                    <p class="text-lg md:text-xl text-slate-700 font-serif italic max-w-lg mx-auto">
                        Đến tham dự lễ thành hôn mừng hạnh phúc lứa đôi của <span class="font-bold text-rose-800">Quốc Trung & Hồng Vân</span>
                    </p>
                </div>

                <!-- Guest Invitation Card Info Box -->
                <div class="p-6 md:p-8 rounded-3xl bg-white border border-rose-200/90 shadow-xl shadow-rose-200/50 max-w-xl mx-auto space-y-6">
                    <div class="grid grid-cols-2 gap-4 divide-x divide-rose-100 text-center">
                        <div class="space-y-1">
                            <Calendar class="w-6 h-6 text-rose-500 mx-auto mb-1" />
                            <span class="text-xs text-rose-800 font-bold uppercase block">Thời Gian</span>
                            <span class="font-serif font-extrabold text-rose-950 text-base block">Thứ Bảy, 19/12/2026</span>
                            <span class="text-xs text-slate-500 font-medium">Đón khách: 18:00 • Nhập tiệc: 18:30</span>
                        </div>
                        <div class="space-y-1">
                            <MapPin class="w-6 h-6 text-rose-500 mx-auto mb-1" />
                            <span class="text-xs text-rose-800 font-bold uppercase block">Địa Điểm</span>
                            <span class="font-serif font-extrabold text-rose-950 text-base block">Asiana Plaza</span>
                            <span class="text-xs text-slate-500 font-medium">Sảnh Tiệc Sang Trọng • TP.HCM</span>
                        </div>
                    </div>

                    <!-- Unique Guest QR Code & Table Assignment -->
                    <div class="pt-4 border-t border-rose-100 grid sm:grid-cols-2 gap-4 items-center bg-rose-50/40 p-4 rounded-2xl">
                        <div class="text-left space-y-1">
                            <span class="text-xs font-mono font-bold text-rose-700 uppercase flex items-center gap-1">
                                <LayoutGrid class="w-3.5 h-3.5" /> Bàn Ăn Được Phân Công:
                            </span>
                            <p class="font-serif font-bold text-rose-950 text-sm">
                                {{ guest.table_name || 'Bàn VIP Trân Trọng' }}
                            </p>
                            <span class="text-[11px] text-slate-500 block">Vui lòng trình mã QR khi đến bàn lễ tân check-in</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 bg-white rounded-xl border border-rose-200 shadow-xs">
                            <img 
                                :src="`https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(guest.qr_code_token || guest.guest_slug)}`" 
                                alt="Mã QR Check-in Khách Mời"
                                class="w-24 h-24 object-contain"
                            />
                            <span class="text-[10px] font-mono text-slate-400 mt-1 font-bold">{{ guest.qr_code_token || 'QR-GUEST-2026' }}</span>
                        </div>
                    </div>

                    <!-- Call to action buttons -->
                    <div class="flex flex-wrap justify-center gap-4 text-sm font-bold pt-2">
                        <a href="#guest-rsvp" class="px-8 py-3.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white shadow-lg shadow-rose-200 transition-all flex items-center gap-2 cursor-pointer">
                            <UserCheck class="w-4 h-4" /> Xác Nhận Tham Dự
                        </a>
                        <WeddingGiftBoxModal />
                    </div>
                </div>
            </div>
        </section>

        <!-- Groom & Bride Profiles -->
        <WeddingCoupleProfiles />

        <!-- Love Story -->
        <WeddingLoveStory />

        <!-- Photo Gallery with Guest Uploads -->
        <WeddingPhotoGallery :memories="memories" :guest-id="guest.id" />

        <!-- Schedule & Interactive Map -->
        <WeddingScheduleAndMap />

        <!-- PERSONALIZED ATTENDANCE & WISHES SECTION -->
        <section id="guest-rsvp" class="py-24 px-6 relative bg-white/90 border-t border-rose-100">
            <div class="max-w-5xl mx-auto space-y-16">
                
                <div class="text-center space-y-3">
                    <span class="text-xs uppercase tracking-[0.3em] text-rose-600 font-mono font-bold">Xác Nhận Dành Cho {{ guest.name }}</span>
                    <h2 class="text-4xl md:text-5xl font-serif font-bold text-rose-950">
                        Xác Nhận Tham Dự & Lời Chúc
                    </h2>
                </div>

                <div class="grid lg:grid-cols-2 gap-10">
                    <!-- Personalized Attendance Form -->
                    <div class="p-8 rounded-3xl bg-white border border-rose-200/90 shadow-xl shadow-rose-100/60">
                        <h3 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                            <UserCheck class="w-6 h-6 text-rose-500" /> Biểu Mẫu Xác Nhận Tham Dự
                        </h3>

                        <form @submit.prevent="submitRsvp" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Khách Mời</label>
                                <input 
                                    :value="guest.name" 
                                    disabled 
                                    class="w-full px-4 py-3 rounded-xl border border-rose-200 bg-rose-50/50 text-slate-700 font-bold"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Xác Nhận Đến Dự</label>
                                    <select 
                                        v-model="rsvpAttending"
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-rose-50/20 font-medium"
                                    >
                                        <option value="yes">Sẽ Đến Tham Dự</option>
                                        <option value="no">Tiếc Là Không Thể</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Số Người Tham Dự</label>
                                    <input 
                                        v-model.number="rsvpGuestsCount" 
                                        type="number" 
                                        min="1" 
                                        max="6"
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-rose-50/20"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1 flex items-center gap-1">
                                    <Utensils class="w-3.5 h-3.5 text-rose-600" /> Khẩu Vị / Chế Độ Ăn
                                </label>
                                <input 
                                    v-model="rsvpDietary" 
                                    type="text" 
                                    placeholder="VD: Món chay / Dị ứng hải sản..." 
                                    class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-rose-50/20"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1 flex items-center gap-1">
                                    <Bus class="w-3.5 h-3.5 text-rose-600" /> Xe Đưa Đón Của Dâu Rể
                                </label>
                                <select 
                                    v-model="rsvpShuttleBus"
                                    class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-rose-50/20 font-medium"
                                >
                                    <option value="no">Tự Túc Di Chuyển</option>
                                    <option value="yes">Đăng Ký Đón Tại Điểm Tập Trung</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Ghi Chú Bổ Sung</label>
                                <textarea 
                                    v-model="rsvpNotes" 
                                    rows="2" 
                                    placeholder="Lời nhắn thêm cho dâu rể..." 
                                    class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-rose-50/20"
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
                                Cảm ơn {{ guest.name }}! Phản hồi tham dự đã được cập nhật thành công.
                            </div>
                        </form>
                    </div>

                    <!-- Wish Form -->
                    <div class="p-8 rounded-3xl bg-white border border-rose-200/90 shadow-xl shadow-rose-100/60 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                                <Send class="w-6 h-6 text-rose-500" /> Lời Chúc Yêu Thương
                            </h3>

                            <form @submit.prevent="submitWish" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Tên Người Gửi</label>
                                    <input 
                                        v-model="wishSender" 
                                        type="text" 
                                        required 
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-rose-50/20"
                                    />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Nội Dung Lời Chúc</label>
                                    <textarea 
                                        v-model="wishMessage" 
                                        rows="4" 
                                        required 
                                        placeholder="Gửi tâm tư, lời chúc đến Quốc Trung & Hồng Vân..." 
                                        class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-rose-50/20"
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
                                    Cảm ơn lời chúc ngọt ngào của bạn!
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Wishes Stream -->
                <div class="pt-8">
                    <h3 class="text-2xl font-serif font-bold text-rose-950 mb-6 flex items-center gap-2">
                        <Sparkles class="w-5 h-5 text-rose-500" /> Sổ Lời Chúc Yêu Thương
                    </h3>
                    <div v-if="wishes && wishes.length > 0" class="grid sm:grid-cols-2 gap-4">
                        <div v-for="wish in wishes" :key="wish.id" class="p-6 rounded-2xl bg-white border border-rose-200/70 shadow-xs">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-rose-950 text-base">{{ wish.sender_name }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ new Date(wish.created_at).toLocaleTimeString() }}</span>
                            </div>
                            <p class="text-slate-700 text-sm leading-relaxed font-medium">{{ wish.message }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
</template>
