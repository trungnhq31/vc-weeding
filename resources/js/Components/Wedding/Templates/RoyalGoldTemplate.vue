<script setup lang="ts">
import { ref } from 'vue';
import { Heart, Calendar, MapPin, Sparkles, Award, Crown, CheckCircle2 } from 'lucide-vue-next';
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
  <div class="min-h-screen bg-gradient-to-b from-amber-100/90 via-yellow-50/50 to-[#FFFDF9] text-amber-950 font-serif relative">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Royal Monogram Hero Section -->
    <header class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20">
      <!-- Outer Royal Gold Frame -->
      <div class="max-w-2xl w-full border-4 border-double border-amber-400 p-8 md:p-14 bg-amber-50/80 shadow-2xl shadow-amber-300/40 rounded-3xl relative backdrop-blur-sm">
        
        <!-- Corner Ornate Flourishes -->
        <div class="absolute top-3 left-3 text-amber-500 text-xs font-serif">❖ ROYAL INVITATION ❖</div>
        <div class="absolute top-3 right-3 text-amber-500 text-xs font-serif">❖ 2026 ❖</div>

        <!-- Royal Monogram Badge -->
        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-amber-600 via-yellow-500 to-amber-700 text-white flex items-center justify-center shadow-lg border-2 border-amber-200 mb-6">
          <span class="text-3xl font-serif font-bold tracking-widest">T & V</span>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-amber-100/90 text-amber-900 text-xs font-bold tracking-widest uppercase border border-amber-300 mb-4">
          <Crown class="w-4 h-4 text-amber-700" />
          <span>Royal Champagne & Gold</span>
        </div>

        <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-amber-950 uppercase leading-tight mb-2">
          Quốc Trung & Hồng Vân
        </h1>

        <p class="text-sm font-sans tracking-widest uppercase text-amber-800 font-semibold mb-6">
          Trân Trọng Kính Mời Kính Dự Lễ Thành Hôn
        </p>

        <div class="h-0.5 w-32 bg-gradient-to-r from-transparent via-amber-500 to-transparent mx-auto mb-6"></div>

        <p class="text-base md:text-lg font-serif italic text-amber-900">
          "Hành trình tình yêu hoang gia & hai tâm hồn hòa chung một nhịp đập"
        </p>

        <div class="mt-8 pt-6 border-t border-amber-200/80 text-xs font-sans font-bold text-amber-800 tracking-wider">
          24 . 10 . 2026 — TP. HỒ CHÍ MINH
        </div>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-16 relative z-10 font-sans">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />
      <WeddingScheduleAndMap />

      <section class="bg-amber-50/90 border-2 border-amber-300 rounded-3xl p-8 shadow-xl shadow-amber-200/40">
        <h2 class="text-2xl font-serif font-bold text-center text-amber-950 mb-6 flex items-center justify-center gap-2">
          <Crown class="w-5 h-5 text-amber-600" />
          <span>Album Ảnh Hoàng Gia</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- Royal RSVP Form -->
      <section class="bg-white/90 rounded-3xl p-8 md:p-12 shadow-xl shadow-amber-200/30 border-2 border-amber-300">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold tracking-widest text-amber-700 uppercase">Hoàng Gia Kính Mời</span>
            <h2 class="text-3xl font-serif font-bold text-amber-950">Phúc Đáp Tham Dự</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
              <button 
                type="button" 
                @click="rsvpAttending = 'yes'"
                class="py-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                :class="rsvpAttending === 'yes' ? 'bg-amber-600 text-white border-amber-600 shadow-md' : 'bg-amber-50 text-amber-900 border-amber-200'"
              >
                <CheckCircle2 class="w-4 h-4" />
                <span>Sẽ Tham Dự Tiệc</span>
              </button>
              <button 
                type="button" 
                @click="rsvpAttending = 'no'"
                class="py-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                :class="rsvpAttending === 'no' ? 'bg-amber-950 text-white border-amber-950 shadow-md' : 'bg-amber-50 text-amber-900 border-amber-200'"
              >
                <span>Xin Phép Vắng Mặt</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'">
              <label class="block text-xs font-bold text-amber-900 mb-1">Số lượng khách tham dự</label>
              <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl border border-amber-300 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" />
            </div>

            <div>
              <label class="block text-xs font-bold text-amber-900 mb-1">Lời chúc mừng dành cho cặp đôi</label>
              <textarea v-model="rsvpNotes" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-amber-300 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 text-white font-bold text-sm shadow-lg shadow-amber-600/30 hover:opacity-95 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'Đang gửi...' : 'Gửi Phúc Đáp Hoàng Gia' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-emerald-700 bg-emerald-50 py-2 rounded-lg border border-emerald-200">
              ✨ Đã gửi phúc đáp thành công!
            </p>
          </form>
        </div>
      </section>

      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-amber-950 text-amber-200 text-center py-8 text-xs font-sans border-t border-amber-800">
      <p>© 2026 Eloria Royal Collection — Trung & Vân Wedding</p>
    </footer>
  </div>
</template>
