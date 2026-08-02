<script setup lang="ts">
import { ref } from 'vue';
import { Sparkles, Heart, Calendar, MapPin, Award, CheckCircle2, ShieldCheck } from 'lucide-vue-next';
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
  <div class="min-h-screen bg-gradient-to-b from-emerald-950 via-slate-950 to-emerald-900 text-emerald-50 font-serif relative">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Emerald Glassmorphic Hero Section -->
    <header class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20">
      <div class="max-w-2xl w-full bg-emerald-900/40 backdrop-blur-xl border border-amber-400/40 p-8 md:p-14 shadow-2xl rounded-3xl relative">
        
        <!-- Glowing Gold Countdown Ring Placeholder -->
        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 text-emerald-950 flex items-center justify-center shadow-lg shadow-amber-500/20 border-2 border-amber-200 mb-6">
          <Sparkles class="w-10 h-10 text-emerald-950" />
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-950/80 text-amber-300 text-xs font-sans font-bold tracking-widest uppercase border border-amber-500/40 mb-4">
          <Award class="w-4 h-4 text-amber-400" />
          <span>IMPERIAL EMERALD & GOLD</span>
        </div>

        <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-white uppercase leading-tight mb-2">
          Quốc Trung <span class="text-amber-400">&</span> Hồng Vân
        </h1>

        <p class="text-sm font-sans tracking-widest uppercase text-amber-200 font-semibold mb-6">
          TRÂN TRỌNG KÍNH MỜI KÍNH DỰ LỄ THÀNH HÔN
        </p>

        <div class="h-0.5 w-32 bg-gradient-to-r from-transparent via-amber-400 to-transparent mx-auto mb-6"></div>

        <p class="text-base text-emerald-200/90 italic font-serif">
          "Đỉnh cao sang trọng — Đánh dấu khởi đầu hành trình hạnh phúc viên mãn."
        </p>

        <div class="mt-8 pt-6 border-t border-emerald-800 text-xs font-sans font-bold text-amber-400 tracking-wider">
          24 THÁNG 10 NĂM 2026 — TP. HỒ CHÍ MINH
        </div>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-16 relative z-10 font-sans">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />
      <WeddingScheduleAndMap />

      <section class="bg-emerald-900/50 backdrop-blur-md border border-amber-400/30 rounded-3xl p-8 shadow-2xl">
        <h2 class="text-2xl font-serif font-bold text-center text-amber-300 mb-6 flex items-center justify-center gap-2">
          <Sparkles class="w-5 h-5 text-amber-400" />
          <span>Kỷ Niệm Ngọc Bảo Hoàng Gia</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- Emerald RSVP -->
      <section class="bg-emerald-900/80 backdrop-blur-xl border-2 border-amber-400/40 rounded-3xl p-8 md:p-12 shadow-2xl">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold tracking-widest text-amber-400 uppercase">PHÚC ĐÁP THAM DỰ</span>
            <h2 class="text-3xl font-serif font-bold text-white">Xác Nhận Tham Dự Lễ Cưới</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4 pt-2">
            <div class="grid grid-cols-2 gap-3">
              <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-amber-400 text-emerald-950 border-amber-400' : 'bg-emerald-950 text-emerald-200 border-emerald-800'">
                <CheckCircle2 class="w-4 h-4" />
                <span>THAM DỰ</span>
              </button>
              <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-amber-400 text-emerald-950 border-amber-400' : 'bg-emerald-950 text-emerald-200 border-emerald-800'">
                <span>VẮNG MẶT</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'">
              <label class="block text-xs font-bold text-amber-300 mb-1">SỐ LƯỢNG KHÁCH</label>
              <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-emerald-950 border border-amber-500/30 text-white text-sm focus:outline-none" />
            </div>

            <div>
              <label class="block text-xs font-bold text-amber-300 mb-1">GHI CHÚ / LỜI CHÚC</label>
              <textarea v-model="rsvpNotes" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-emerald-950 border border-amber-500/30 text-white text-sm focus:outline-none"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 text-emerald-950 font-bold text-xs uppercase tracking-widest hover:opacity-95 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'ĐANG GỬI...' : 'GỬI PHÚC ĐÁP HOÀNG GIA' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-amber-300 bg-emerald-950 py-2 rounded-lg border border-amber-500/30">
              ✨ Đã gửi phúc đáp thành công!
            </p>
          </form>
        </div>
      </section>

      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-slate-950 text-emerald-400 text-center py-8 text-xs font-sans border-t border-emerald-900">
      <p>© 2026 Eloria Emerald Luxe Collection — Trung & Vân</p>
    </footer>
  </div>
</template>
