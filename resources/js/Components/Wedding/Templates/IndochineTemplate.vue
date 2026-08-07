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

      <!-- Traditional Red Velvet RSVP Form (Pastel Crimson) -->
      <section class="bg-[#FFFDF9] text-rose-950 rounded-3xl p-6 md:p-12 shadow-xl border-2 border-red-200/80">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold tracking-widest text-red-700 uppercase">THƯ PHÚC ĐÁP</span>
            <h2 class="text-3xl font-serif font-bold text-red-950">Xác Nhận Tham Dự Lễ Cưới</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
              <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-red-700 text-white border-red-700 shadow-md' : 'bg-red-50 text-red-900 border-red-200 hover:bg-red-100/50'">
                <CheckCircle2 class="w-4 h-4" />
                <span>THAM DỰ</span>
              </button>
              <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-red-50 text-red-900 border-red-200 hover:bg-red-100/50'">
                <span>CÁO LỖI VẮNG MẶT</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="space-y-3 p-4 rounded-2xl bg-red-50/50 border border-red-100">
              <label class="block text-xs font-bold text-red-900 font-sans uppercase">Chọn sự kiện tham dự</label>
              <div class="space-y-2 text-xs font-sans">
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpCeremony" class="rounded border-red-300 text-red-600 focus:ring-red-500 w-4 h-4" />
                  <span class="font-bold">Lễ Gia Tiên & Rước Dâu</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpReception" class="rounded border-red-300 text-red-600 focus:ring-red-500 w-4 h-4" />
                  <span class="font-bold">Tiệc Cưới Chính (Khai Tiệc)</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpAfterparty" class="rounded border-red-300 text-red-600 focus:ring-red-500 w-4 h-4" />
                  <span class="font-bold">Dư Tiệc / After-Party bạn thân</span>
                </label>
              </div>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-red-800 mb-1">SỐ LƯỢNG KHÁCH DỰ</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-white border border-red-200 text-rose-950 text-sm focus:outline-none focus:border-red-400 focus:ring-1 focus:ring-red-400" />
              </div>
              <div>
                <label class="block text-xs font-bold text-red-800 mb-1">KHẨU VỊ / CHẾ ĐỘ ĂN</label>
                <input type="text" v-model="rsvpDietary" placeholder="VD: Ăn chay, không hải sản..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-red-200 text-rose-950 text-sm focus:outline-none focus:border-red-400 focus:ring-1 focus:ring-red-400" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-red-800 mb-1">LỜI CHÚC PHÚC THÂN TÌNH</label>
              <textarea v-model="rsvpNotes" rows="3" placeholder="Gửi lời chúc tốt đẹp nhất đến cô dâu chú rể..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-red-200 text-rose-950 text-sm focus:outline-none focus:border-red-400 focus:ring-1 focus:ring-red-400"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 text-white font-bold text-sm shadow-md shadow-red-600/10 hover:opacity-95 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'ĐANG GỬI THƯ...' : 'GỬI PHÚC ĐÁP THÀNH HÔN' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-emerald-800 bg-emerald-50 py-2.5 rounded-xl border border-emerald-200">
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
