<script setup lang="ts">
import { ref } from 'vue';
import { Leaf, Heart, Calendar, MapPin, Sparkles, CheckCircle2 } from 'lucide-vue-next';
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
  <div class="min-h-screen bg-gradient-to-b from-emerald-100/70 via-teal-50/40 to-[#F0FDF4] text-emerald-950 font-serif relative">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Botanical Arch Hero Section -->
    <header class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20">
      <div class="max-w-xl mx-auto space-y-8">
        
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100/80 text-emerald-900 text-xs font-sans font-semibold border border-emerald-300">
          <Leaf class="w-4 h-4 text-emerald-600" />
          <span>Botanical Sage & Garden Wedding</span>
        </div>

        <div class="relative mx-auto w-64 h-80 rounded-t-full overflow-hidden border-4 border-emerald-200 shadow-2xl p-2 bg-white/90">
          <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80" alt="Botanical Couple" class="w-full h-full object-cover rounded-t-full" />
        </div>

        <div>
          <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-emerald-950 leading-tight">
            Quốc Trung & Hồng Vân
          </h1>
          <p class="text-sm font-sans text-emerald-800 tracking-widest uppercase mt-2">
            24 THÁNG 10 NĂM 2026
          </p>
        </div>

        <p class="text-base text-emerald-900/80 italic font-serif max-w-md mx-auto">
          "Giữa ngàn hoa lá cỏ cây, chúng mình chọn nắm tay nhau đi hết cuộc đời."
        </p>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-16 relative z-10 font-sans">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />
      <WeddingScheduleAndMap />

      <section class="bg-white/90 border border-emerald-200 rounded-3xl p-8 shadow-lg shadow-emerald-100/50">
        <h2 class="text-2xl font-serif font-bold text-center text-emerald-950 mb-6 flex items-center justify-center gap-2">
          <Leaf class="w-5 h-5 text-emerald-600" />
          <span>Kỷ Niệm Khu Vườn Tình Yêu</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- RSVP Section -->
      <section class="bg-white/95 text-emerald-950 rounded-3xl p-6 md:p-12 shadow-xl shadow-emerald-900/5 border border-emerald-200 relative overflow-hidden">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-semibold text-emerald-700 tracking-widest uppercase">KÍNH MỜI DỰ TIỆC</span>
            <h2 class="text-3xl font-serif font-bold text-emerald-950">Xác Nhận Tham Dự</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4 pt-2">
            <div class="grid grid-cols-2 gap-3">
              <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-emerald-600 text-white border-emerald-500 shadow-md' : 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100/50'">
                <CheckCircle2 class="w-4 h-4" />
                <span>SẼ THAM DỰ</span>
              </button>
              <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100/50'">
                <span>VẮNG MẶT</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="space-y-3 p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
              <label class="block text-xs font-bold text-emerald-900 font-sans uppercase">Chọn sự kiện tham dự</label>
              <div class="space-y-2 text-xs font-sans">
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpCeremony" class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4" />
                  <span class="font-bold">Lễ Gia Tiên & Rước Dâu</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpReception" class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4" />
                  <span class="font-bold">Tiệc Cưới Chính (Khai Tiệc)</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpAfterparty" class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4" />
                  <span class="font-bold">Dư Tiệc / After-Party bạn thân</span>
                </label>
              </div>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs text-emerald-800 font-bold mb-1">SỐ NGƯỜI THAM DỰ</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-white border border-emerald-200 text-slate-900 text-sm focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" />
              </div>
              <div>
                <label class="block text-xs text-emerald-800 font-bold mb-1">KHẨU VỊ / CHẾ ĐỘ ĂN</label>
                <input type="text" v-model="rsvpDietary" placeholder="VD: Ăn chay, không hải sản..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-emerald-200 text-slate-900 text-sm focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" />
              </div>
            </div>

            <div>
              <label class="block text-xs text-emerald-800 font-bold mb-1">LỜI NHẮN</label>
              <textarea v-model="rsvpNotes" rows="3" placeholder="Gửi lời chúc mừng hoặc ghi chú đặc biệt..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-emerald-200 text-slate-900 text-sm focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-xs uppercase tracking-widest hover:opacity-95 shadow-md shadow-emerald-600/10 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'ĐANG GỬI...' : 'GỬI XÁC NHẬN THAM DỰ' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs text-emerald-800 bg-emerald-50 py-2.5 rounded-xl border border-emerald-200">
              🌿 Đã nhận phản hồi RSVP của bạn!
            </p>
          </form>
        </div>
      </section>

      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-emerald-900 text-emerald-100 text-center py-8 text-xs font-sans">
      <p>© 2026 Eloria Botanical Sage Collection — Trung & Vân</p>
    </footer>
  </div>
</template>
