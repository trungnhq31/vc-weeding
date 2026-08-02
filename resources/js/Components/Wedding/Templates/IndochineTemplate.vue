<script setup lang="ts">
import { ref } from 'vue';
import { Heart, Calendar, MapPin, Sparkles, CheckCircle2 } from 'lucide-vue-next';
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
const rsvpNotes = ref<string>(props.guest?.notes || '');
const isSubmittingRsvp = ref(false);
const rsvpSuccess = ref(false);

const handleRsvpSubmit = async () => {
    isSubmittingRsvp.value = true;
    try {
        await props.submitRsvp({
            rsvp_status: rsvpAttending.value === 'yes' ? 'attending' : 'declined',
            confirmed_count: rsvpGuestsCount.value,
            dietary_preference: rsvpDietary.value,
            notes: rsvpNotes.value,
        });
        rsvpSuccess.value = true;
        setTimeout(() => { rsvpSuccess.value = false; }, 4000);
    } finally {
        isSubmittingRsvp.value = false;
    }
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-red-100 via-rose-50 to-[#FFF5F5] text-red-950 font-serif relative">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Traditional Indochine Double Happiness Hero -->
    <header class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20">
      <div class="max-w-2xl w-full border-4 border-amber-500/80 p-8 md:p-14 bg-red-900 text-amber-100 shadow-2xl rounded-3xl relative overflow-hidden">
        
        <!-- Golden Double Happiness Symbol (囍) -->
        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-amber-600 to-yellow-400 text-red-950 flex items-center justify-center text-5xl font-bold shadow-lg border-2 border-amber-300 mb-6">
          囍
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-sans font-bold tracking-widest uppercase border border-amber-500/40 mb-4">
          <span>🧧 INDOCHINE TRADITIONAL & SONG HỶ 🧧</span>
        </div>

        <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-amber-300 uppercase leading-tight mb-2">
          Quốc Trung & Hồng Vân
        </h1>

        <p class="text-sm font-sans tracking-widest uppercase text-amber-200 font-semibold mb-6">
          TRÂN TRỌNG KÍNH MỜI QUÝ KHÁCH DỰ LỄ THÀNH HÔN
        </p>

        <div class="h-0.5 w-32 bg-gradient-to-r from-transparent via-amber-400 to-transparent mx-auto mb-6"></div>

        <p class="text-base text-amber-100/90 italic font-serif">
          "Trăm năm tình viên mãn — Thứ Bảy, Ngày 24 Tháng 10 Năm 2026 (15/09 Bính Ngọ)"
        </p>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-16 relative z-10 font-sans">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />
      <WeddingScheduleAndMap />

      <section class="bg-white/90 border-2 border-red-300 rounded-3xl p-8 shadow-xl">
        <h2 class="text-2xl font-serif font-bold text-center text-red-950 mb-6 flex items-center justify-center gap-2">
          <span class="text-xl">🏮</span>
          <span>Kỷ Niệm Ngày Chung Đôi</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- Traditional Red Velvet RSVP Form -->
      <section class="bg-red-950 text-amber-100 rounded-3xl p-8 md:p-12 shadow-2xl border-2 border-amber-500/50">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold tracking-widest text-amber-400 uppercase">THƯ PHÚC ĐÁP</span>
            <h2 class="text-3xl font-serif font-bold text-amber-200">Xác Nhận Tham Dự Lễ Cưới</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
              <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-amber-500 text-red-950 border-amber-400' : 'bg-red-900 text-amber-200 border-red-800'">
                <CheckCircle2 class="w-4 h-4" />
                <span>THAM DỰ</span>
              </button>
              <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-amber-500 text-red-950 border-amber-400' : 'bg-red-900 text-amber-200 border-red-800'">
                <span>CÁO LỖI VẮNG MẶT</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'">
              <label class="block text-xs font-bold text-amber-300 mb-1">SỐ LƯỢNG NGUYÊN QUÁN / KHÁCH DỰ</label>
              <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-red-900 border border-amber-500/40 text-amber-100 text-sm focus:outline-none" />
            </div>

            <div>
              <label class="block text-xs font-bold text-amber-300 mb-1">LỜI CHÚC PHÚC THÂN TÌNH</label>
              <textarea v-model="rsvpNotes" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-red-900 border border-amber-500/40 text-amber-100 text-sm focus:outline-none"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-red-950 font-bold text-sm shadow-lg hover:opacity-95 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'ĐANG GỬI THƯ...' : 'GỬI PHÚC ĐÁP THÀNH HÔN' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-amber-300 bg-red-900/80 py-2 rounded-lg border border-amber-500/40">
              🧧 Cảm ơn quý khách đã phúc đáp cho dâu rể!
            </p>
          </form>
        </div>
      </section>

      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-red-950 text-amber-300 text-center py-8 text-xs font-sans border-t border-amber-800">
      <p>© 2026 Eloria Indochine Collection — Lễ Thành Hôn Trung & Vân</p>
    </footer>
  </div>
</template>
