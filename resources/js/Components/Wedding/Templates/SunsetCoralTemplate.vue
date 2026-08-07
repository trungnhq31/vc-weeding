<script setup lang="ts">
import { ref } from 'vue';
import { Sun, Heart, Calendar, MapPin, Sparkles, CheckCircle2 } from 'lucide-vue-next';
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
  <div class="min-h-screen bg-gradient-to-b from-orange-200/80 via-stone-100/50 to-[#FFFBF7] text-orange-950 font-serif relative">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- 50/50 Split Hero Layout -->
    <header class="min-h-screen grid md:grid-cols-2 items-center">
      <!-- Left Photo Column -->
      <div class="h-full min-h-[50vh] relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80" alt="Sunset Couple" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-orange-950/60 via-transparent to-transparent"></div>
        <div class="absolute bottom-8 left-8 text-white space-y-1">
          <span class="text-xs font-sans tracking-widest uppercase text-orange-200">SUNSET CORAL COLLECTION</span>
          <h2 class="text-2xl font-bold font-serif">Quốc Trung & Hồng Vân</h2>
        </div>
      </div>

      <!-- Right Content Column -->
      <div class="p-8 md:p-16 flex flex-col justify-center text-center md:text-left space-y-6">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-100 text-orange-900 text-xs font-sans font-semibold border border-orange-300 self-center md:self-start">
          <Sun class="w-4 h-4 text-orange-600" />
          <span>Sunset Peach & Tropical Coral</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-orange-950 leading-tight">
          Lễ Thành Hôn<br />
          <span class="text-orange-600 font-serif italic text-3xl md:text-5xl">Ngập Nắng Bình Minh</span>
        </h1>

        <p class="text-base text-orange-900/80 font-sans leading-relaxed">
          Trân trọng kính mời bạn đến chung vui trong tiệc cưới ấm áp ngập tràn ánh hoàng hôn cùng gia đình chúng tôi.
        </p>

        <div class="pt-4 border-t border-orange-200 font-sans text-xs font-bold text-orange-800 tracking-wider">
          24 THÁNG 10 NĂM 2026 — TP. HỒ CHÍ MINH
        </div>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-16 relative z-10 font-sans">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />
      <WeddingScheduleAndMap />

      <section class="bg-white/90 border border-orange-200 rounded-3xl p-8 shadow-lg shadow-orange-100/50">
        <h2 class="text-2xl font-serif font-bold text-center text-orange-950 mb-6 flex items-center justify-center gap-2">
          <Sun class="w-5 h-5 text-orange-600" />
          <span>Album Bình Minh Tình Yêu</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- RSVP Section -->
      <section class="bg-white/95 text-orange-950 rounded-3xl p-6 md:p-12 shadow-xl shadow-orange-100/30 border-2 border-orange-200/80">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold tracking-widest text-orange-700 uppercase">XÁC NHẬN THAM DỰ</span>
            <h2 class="text-3xl font-serif font-bold text-orange-950">Gửi Lời Phúc Đáp</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4 pt-2">
            <div class="grid grid-cols-2 gap-3">
              <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-orange-600 text-white border-orange-600 shadow-md' : 'bg-orange-50 text-orange-900 border-orange-200 hover:bg-orange-100/50'">
                <CheckCircle2 class="w-4 h-4" />
                <span>SẼ THAM DỰ</span>
              </button>
              <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-orange-50 text-orange-900 border-orange-200 hover:bg-orange-100/50'">
                <span>VẮNG MẶT</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="space-y-3 p-4 rounded-2xl bg-orange-50/50 border border-orange-100">
              <label class="block text-xs font-bold text-orange-900 font-sans uppercase">Chọn sự kiện tham dự</label>
              <div class="space-y-2 text-xs font-sans">
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpCeremony" class="rounded border-orange-300 text-orange-600 focus:ring-orange-500 w-4 h-4" />
                  <span class="font-bold">Lễ Gia Tiên & Rước Dâu</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpReception" class="rounded border-orange-300 text-orange-600 focus:ring-orange-500 w-4 h-4" />
                  <span class="font-bold">Tiệc Cưới Chính (Khai Tiệc)</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpAfterparty" class="rounded border-orange-300 text-orange-600 focus:ring-orange-500 w-4 h-4" />
                  <span class="font-bold">Dư Tiệc / After-Party bạn thân</span>
                </label>
              </div>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs text-orange-850 font-bold mb-1">SỐ LƯỢNG KHÁCH</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-white border border-orange-200 text-orange-950 text-sm focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
              </div>
              <div>
                <label class="block text-xs text-orange-850 font-bold mb-1">KHẨU VỊ / DIETARY PREFERENCE</label>
                <input type="text" v-model="rsvpDietary" placeholder="VD: Ăn chay, không hải sản..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-orange-200 text-orange-950 text-sm focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
              </div>
            </div>

            <div>
              <label class="block text-xs text-orange-850 font-bold mb-1">LỜI NHẮN NÀY DÀNH CHO DÂU RỂ</label>
              <textarea v-model="rsvpNotes" rows="3" placeholder="Gửi những lời chúc thân thương nhất..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-orange-200 text-orange-950 text-sm focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-orange-600 text-white font-bold text-xs uppercase tracking-widest hover:bg-orange-700 shadow-md shadow-orange-600/10 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'ĐANG GỬI...' : 'XÁC NHẬN CÙNG THAM DỰ' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-emerald-800 bg-emerald-50 py-2.5 rounded-xl border border-emerald-200">
              ☀ Cảm ơn bạn đã gửi phản hồi tham dự!
            </p>
          </form>
        </div>
      </section>

      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-orange-950 text-orange-200 text-center py-8 text-xs font-sans">
      <p>© 2026 Eloria Sunset Coral Collection — Trung & Vân</p>
    </footer>
  </div>
</template>
