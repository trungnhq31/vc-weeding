<script setup lang="ts">
import { ref } from 'vue';
import { Plane, Heart, Calendar, MapPin, QrCode, Sparkles, CheckCircle2, Ticket } from 'lucide-vue-next';
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
const rsvpCeremony = ref<boolean>(props.guest?.rsvp_ceremony !== 'declined');
const rsvpReception = ref<boolean>(props.guest?.rsvp_reception !== 'declined');
const rsvpAfterparty = ref<boolean>(props.guest?.rsvp_afterparty !== 'declined');
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
            rsvp_ceremony: rsvpAttending.value === 'yes' && rsvpCeremony.value ? 'attending' : 'declined',
            rsvp_reception: rsvpAttending.value === 'yes' && rsvpReception.value ? 'attending' : 'declined',
            rsvp_afterparty: rsvpAttending.value === 'yes' && rsvpAfterparty.value ? 'attending' : 'declined',
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
  <div class="min-h-screen bg-gradient-to-b from-sky-200/80 via-cyan-100/50 to-[#F0F9FF] text-sky-950 font-sans relative">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Boarding Pass Ticket Hero -->
    <header class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20">
      
      <!-- Airline Boarding Pass Container -->
      <div class="max-w-3xl w-full bg-white rounded-3xl shadow-2xl border-2 border-sky-300 overflow-hidden relative font-sans text-left">
        
        <!-- Top Boarding Pass Header -->
        <div class="bg-gradient-to-r from-sky-600 to-cyan-600 text-white p-6 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="p-2.5 bg-white/20 rounded-xl">
              <Plane class="w-6 h-6 text-white" />
            </div>
            <div>
              <span class="text-[10px] font-mono tracking-widest uppercase opacity-80">BOARDING PASS / WEDDING FLIGHT</span>
              <h2 class="text-xl font-bold font-serif">LOVE AIRWAYS — WED2026</h2>
            </div>
          </div>

          <div class="text-right font-mono text-xs font-bold bg-white/10 px-3 py-1.5 rounded-lg border border-white/20">
            CLASS: FIRST CLASS VIP
          </div>
        </div>

        <!-- Main Ticket Body -->
        <div class="p-8 grid md:grid-cols-12 gap-8 items-center bg-sky-50/40">
          <div class="md:col-span-8 space-y-6">
            <div class="flex items-center justify-between text-xs font-mono font-bold text-sky-800">
              <div>
                <span class="text-slate-400 block text-[10px]">PASSENGER NAME</span>
                <span class="text-base text-sky-950 font-sans uppercase font-extrabold">{{ guest?.name || 'GUEST PASSENGER' }}</span>
              </div>
              <div class="text-right">
                <span class="text-slate-400 block text-[10px]">DATE & TIME</span>
                <span class="text-sm text-sky-950">24.10.2026 @ 11:30 AM</span>
              </div>
            </div>

            <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-sky-200 shadow-2xs font-mono text-xs">
              <div>
                <span class="text-[10px] text-slate-400 block">DEPARTURE</span>
                <span class="font-bold text-sky-950 text-sm">SINGLE LIFE</span>
              </div>
              <div class="text-sky-500 font-bold flex items-center gap-1">
                <span>✈</span>
                <span class="h-0.5 w-12 bg-sky-400 inline-block"></span>
              </div>
              <div class="text-right">
                <span class="text-[10px] text-slate-400 block">DESTINATION</span>
                <span class="font-bold text-sky-950 text-sm">HAPPY MARRIAGE</span>
              </div>
            </div>

            <div class="grid grid-cols-3 gap-3 text-center text-xs font-mono">
              <div class="bg-sky-100/80 p-2.5 rounded-xl border border-sky-200">
                <span class="text-[9px] text-sky-700 block">GATE</span>
                <span class="font-extrabold text-sky-950 text-sm">ASIANA</span>
              </div>
              <div class="bg-sky-100/80 p-2.5 rounded-xl border border-sky-200">
                <span class="text-[9px] text-sky-700 block">FLIGHT</span>
                <span class="font-extrabold text-sky-950 text-sm">WED-2026</span>
              </div>
              <div class="bg-sky-100/80 p-2.5 rounded-xl border border-sky-200">
                <span class="text-[9px] text-sky-700 block">SEAT</span>
                <span class="font-extrabold text-sky-950 text-sm">VIP RSVP</span>
              </div>
            </div>
          </div>

          <!-- Perforated Tear-off Ticket Stub -->
          <div class="md:col-span-4 border-t-2 md:border-t-0 md:border-l-2 border-dashed border-sky-300 pt-6 md:pt-0 md:pl-6 text-center space-y-4">
            <div class="w-24 h-24 mx-auto bg-white p-2 rounded-xl border border-sky-200 shadow-sm flex items-center justify-center">
              <QrCode class="w-full h-full text-sky-900" />
            </div>
            <span class="text-[10px] font-mono text-sky-700 block font-bold">SCAN FOR CHECK-IN</span>
            <div class="text-xs font-bold text-sky-950 font-serif">TRUNG & VÂN</div>
          </div>
        </div>

      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-16 relative z-10">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />
      <WeddingScheduleAndMap />

      <section class="bg-white/90 border border-sky-200 rounded-3xl p-8 shadow-xl">
        <h2 class="text-2xl font-serif font-bold text-center text-sky-950 mb-6 flex items-center justify-center gap-2">
          <Ticket class="w-5 h-5 text-sky-600" />
          <span>Album Chuyến Bay Tình Yêu</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- Boarding RSVP -->
      <section class="bg-white/95 text-sky-950 border border-sky-300 rounded-3xl p-6 md:p-12 shadow-xl shadow-sky-900/5 relative">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-mono font-bold tracking-widest text-sky-600 uppercase">FLIGHT BOARDING CONFIRMATION</span>
            <h2 class="text-3xl font-serif font-bold text-sky-950">Xác Nhận Đặt Vé</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4 pt-2">
            <div class="grid grid-cols-2 gap-3">
              <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-sky-600 text-white border-sky-600 shadow-md' : 'bg-sky-50 text-sky-800 border-sky-200 hover:bg-sky-100/50'">
                <CheckCircle2 class="w-4 h-4" />
                <span>BOARDING CONFIRMED</span>
              </button>
              <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-sky-50 text-sky-800 border-sky-200 hover:bg-sky-100/50'">
                <span>CANNOT BOARD</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="space-y-3 p-4 rounded-2xl bg-sky-50/50 border border-sky-100">
              <label class="block text-xs font-bold uppercase text-sky-900">Chọn chuyến bay / Sự kiện tham dự</label>
              <div class="space-y-2 text-xs font-mono">
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpCeremony" class="rounded border-sky-300 text-sky-600 focus:ring-sky-500 w-4 h-4" />
                  <span class="font-bold">Lễ Gia Tiên & Rước Dâu</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpReception" class="rounded border-sky-300 text-sky-600 focus:ring-sky-500 w-4 h-4" />
                  <span class="font-bold">Tiệc Cưới Chính (Khai Tiệc)</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpAfterparty" class="rounded border-sky-300 text-sky-600 focus:ring-sky-500 w-4 h-4" />
                  <span class="font-bold">Dư Tiệc / After-Party bạn thân</span>
                </label>
              </div>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-mono text-sky-800 mb-1">SỐ LƯỢNG HÀNH KHÁCH / GUEST COUNT</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-white border border-sky-200 text-slate-900 text-sm focus:outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-400 shadow-2xs animate-fade-in" />
              </div>
              <div>
                <label class="block text-xs font-mono text-sky-800 mb-1">KHẨU VỊ / DIETARY PREFERENCE</label>
                <input type="text" v-model="rsvpDietary" placeholder="VD: Ăn chay, dị ứng hải sản..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-sky-200 text-slate-900 text-sm focus:outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-400 shadow-2xs animate-fade-in" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-mono text-sky-800 mb-1">GHI CHÚ HÀNH TRÌNH / PASSENGER NOTES</label>
              <textarea v-model="rsvpNotes" rows="3" placeholder="Gửi lời chúc mừng đến Dâu Rể..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-sky-200 text-slate-900 text-sm focus:outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-400 shadow-2xs"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 text-white font-bold text-xs uppercase tracking-widest hover:opacity-95 shadow-md shadow-sky-600/10 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'CONFIRMING...' : 'CONFIRM BOARDING PASS' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-mono text-emerald-800 bg-emerald-50 py-2.5 rounded-xl border border-emerald-200">
              ✈ Boarding pass confirmed! See you at Gate Asiana!
            </p>
          </form>
        </div>
      </section>

      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-sky-950 text-sky-300 text-center py-8 text-xs font-sans">
      <p>© 2026 Eloria Ocean Breeze Airline Collection — Trung & Vân</p>
    </footer>
  </div>
</template>
