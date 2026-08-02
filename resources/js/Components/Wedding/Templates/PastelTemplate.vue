<script setup lang="ts">
import { ref } from 'vue';
import { Heart, Calendar, MapPin, Sparkles, Send, Clock, CheckCircle2, UserCheck, Bus, QrCode, Utensils } from 'lucide-vue-next';
import WeddingEnvelopeModal from '../WeddingEnvelopeModal.vue';
import WeddingMusicPlayer from '../WeddingMusicPlayer.vue';
import WeddingCoupleProfiles from '../WeddingCoupleProfiles.vue';
import WeddingLoveStory from '../WeddingLoveStory.vue';
import WeddingPhotoGallery from '../WeddingPhotoGallery.vue';
import WeddingScheduleAndMap from '../WeddingScheduleAndMap.vue';
import WeddingGiftBoxModal from '../WeddingGiftBoxModal.vue';

const props = defineProps<{
    guest?: any;
    wishes: any[];
    memories?: any[];
    submitRsvp: (data: any) => Promise<void>;
    submitWish: (sender: string, message: string) => Promise<void>;
    uploadMemory: (file: File, name: string, title?: string, desc?: string) => Promise<void>;
}>();

const musicPlayerRef = ref<any>(null);

const rsvpAttending = ref<string>(props.guest?.rsvp_status === 'attending' ? 'yes' : 'yes');
const rsvpGuestsCount = ref<number>(props.guest?.confirmed_count || props.guest?.estimated_count || 1);
const rsvpDietary = ref<string>(props.guest?.dietary_preference || '');
const rsvpShuttleBus = ref<string>(props.guest?.shuttle_bus || 'no');
const rsvpNotes = ref<string>(props.guest?.notes || '');
const isSubmittingRsvp = ref(false);
const rsvpSuccess = ref(false);

const wishSender = ref(props.guest?.name || '');
const wishMessage = ref('');
const isSubmittingWish = ref(false);
const wishSuccess = ref(false);

const onEnvelopeOpen = () => {
    musicPlayerRef.value?.playMusic();
};

const handleRsvpSubmit = async () => {
    isSubmittingRsvp.value = true;
    try {
        await props.submitRsvp({
            rsvp_status: rsvpAttending.value === 'yes' ? 'attending' : 'declined',
            confirmed_count: rsvpGuestsCount.value,
            dietary_preference: rsvpDietary.value,
            shuttle_bus: rsvpShuttleBus.value,
            notes: rsvpNotes.value,
        });
        rsvpSuccess.value = true;
        setTimeout(() => { rsvpSuccess.value = false; }, 4000);
    } finally {
        isSubmittingRsvp.value = false;
    }
};

const handleWishSubmit = async () => {
    if (!wishSender.value || !wishMessage.value) return;
    isSubmittingWish.value = true;
    try {
        await props.submitWish(wishSender.value, wishMessage.value);
        wishMessage.value = '';
        wishSuccess.value = true;
        setTimeout(() => { wishSuccess.value = false; }, 4000);
    } finally {
        isSubmittingWish.value = false;
    }
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-rose-100/70 via-amber-50/40 to-[#FAF8F5] text-rose-950 font-serif relative selection:bg-rose-200">
    <WeddingEnvelopeModal 
      :groomName="'Nguyễn Hoàng Quốc Trung'" 
      :brideName="'Lê Thị Hồng Vân'" 
      :weddingDate="'24 . 10 . 2026'"
      :guestName="guest?.name"
      :salutation="guest?.salutation"
      @open="onEnvelopeOpen"
    />
    
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Hero Section -->
    <header class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20 overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-rose-200/40 via-transparent to-transparent pointer-events-none"></div>

      <div class="relative z-10 space-y-6 max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/80 border border-rose-200 text-rose-800 text-xs tracking-widest uppercase shadow-2xs">
          <Sparkles class="w-3.5 h-3.5 text-rose-500" />
          <span>Lễ Thành Hôn — Romantic Pastel</span>
          <Sparkles class="w-3.5 h-3.5 text-rose-500" />
        </div>

        <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-rose-950 leading-tight">
          Quốc Trung <span class="text-rose-500 font-serif italic text-3xl md:text-5xl">&</span> Hồng Vân
        </h1>

        <div class="h-0.5 w-24 bg-gradient-to-r from-transparent via-rose-400 to-transparent mx-auto"></div>

        <p class="text-base md:text-lg text-rose-900/80 font-sans tracking-wide">
          Thứ Bảy, ngày 24 tháng 10 năm 2026 (Nhằm ngày 15/09 Năm Bính Ngọ)
        </p>

        <div class="pt-8">
          <div class="w-48 h-48 md:w-56 md:h-56 mx-auto rounded-full p-2 bg-white/90 shadow-xl border border-rose-300 relative group overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80" 
              alt="Couple Portrait" 
              class="w-full h-full object-cover rounded-full group-hover:scale-105 transition duration-700"
            />
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-20 relative z-10 font-sans">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />
      <WeddingScheduleAndMap />

      <!-- Photo Gallery -->
      <section class="bg-white/90 rounded-3xl p-8 shadow-xl shadow-rose-100/50 border border-rose-200">
        <h2 class="text-2xl font-serif font-bold text-center text-rose-950 mb-8 flex items-center justify-center gap-2">
          <Heart class="w-5 h-5 text-rose-500 fill-rose-500" />
          <span>Album Kỷ Niệm Tình Yêu</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- RSVP Section -->
      <section class="bg-white/90 rounded-3xl p-8 md:p-12 shadow-xl shadow-rose-100/50 border border-rose-200 relative overflow-hidden">
        <div class="max-w-xl mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-semibold text-rose-600 tracking-widest uppercase">Xác Nhận Tham Dự</span>
            <h2 class="text-3xl font-serif font-bold text-rose-950">Gửi Lời Xác Nhận RSVP</h2>
            <p class="text-sm text-slate-600">Sự hiện diện của bạn là niềm vinh hạnh to lớn đối với gia đình chúng tôi!</p>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-5 pt-4">
            <div>
              <label class="block text-xs font-bold uppercase text-slate-700 mb-2">Bạn sẽ tham dự cùng chúng tôi chứ?</label>
              <div class="grid grid-cols-2 gap-3">
                <button 
                  type="button" 
                  @click="rsvpAttending = 'yes'"
                  class="py-3 px-4 rounded-xl border text-sm font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                  :class="rsvpAttending === 'yes' ? 'bg-rose-600 text-white border-rose-600 shadow-md' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                >
                  <CheckCircle2 class="w-4 h-4" />
                  <span>Sẽ Tham Dự</span>
                </button>
                <button 
                  type="button" 
                  @click="rsvpAttending = 'no'"
                  class="py-3 px-4 rounded-xl border text-sm font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                  :class="rsvpAttending === 'no' ? 'bg-rose-950 text-white border-rose-950 shadow-md' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                >
                  <span>Rất Tiếc Vắng Mặt</span>
                </button>
              </div>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Số người tham dự</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none" />
              </div>
              <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Khẩu vị / Chế độ ăn</label>
                <input type="text" v-model="rsvpDietary" placeholder="VD: Ăn chay, không cay..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Lời nhắn gửi Dâu Rể</label>
              <textarea v-model="rsvpNotes" rows="3" placeholder="Gửi lời chúc mừng hoặc ghi chú đặc biệt..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 text-white font-bold text-sm shadow-lg shadow-rose-600/30 hover:opacity-95 transition cursor-pointer disabled:opacity-50">
              {{ isSubmittingRsvp ? 'Đang gửi xác nhận...' : 'Xác Nhận Tham Dự' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-emerald-600 bg-emerald-50 py-2 rounded-lg border border-emerald-200">
              ✨ Cảm ơn bạn đã gửi xác nhận RSVP cho Dâu Rể!
            </p>
          </form>
        </div>
      </section>

      <!-- Gift Box & Wishes -->
      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-rose-950 text-rose-200 text-center py-8 text-xs font-sans">
      <p>© 2026 Eloria Wedding OS — Nguyễn Hoàng Quốc Trung & Lê Thị Hồng Vân</p>
    </footer>
  </div>
</template>
