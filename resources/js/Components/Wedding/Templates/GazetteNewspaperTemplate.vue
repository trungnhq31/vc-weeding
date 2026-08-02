<script setup lang="ts">
import { ref } from 'vue';
import { Newspaper, Heart, Calendar, MapPin, Sparkles, CheckCircle2 } from 'lucide-vue-next';
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
const rsvpNotes = ref<string>(props.guest?.notes || '');
const isSubmittingRsvp = ref(false);
const rsvpSuccess = ref(false);

const handleRsvpSubmit = async () => {
    isSubmittingRsvp.value = true;
    try {
        await props.submitRsvp({
            rsvp_status: rsvpAttending.value === 'yes' ? 'attending' : 'declined',
            confirmed_count: rsvpGuestsCount.value,
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
  <div class="min-h-screen bg-[#FDFBF7] text-stone-900 font-serif relative p-4 md:p-8 selection:bg-stone-900 selection:text-white">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Newspaper Container Frame -->
    <div class="max-w-5xl mx-auto border-4 border-stone-900 p-6 md:p-12 bg-[#FFFDF9] shadow-2xl relative">
      
      <!-- Top Newspaper Masthead Bar -->
      <div class="border-b-2 border-stone-900 pb-4 mb-6 text-center">
        <div class="flex items-center justify-between text-xs font-mono font-bold tracking-widest text-stone-600 uppercase border-b border-stone-400 pb-2 mb-4">
          <span>VOL. I ... NO. 10</span>
          <span>SPECIAL WEDDING EDITION</span>
          <span>OCTOBER 24, 2026</span>
        </div>

        <h1 class="text-4xl md:text-7xl font-black font-serif tracking-tight text-stone-900 uppercase leading-none">
          THE WEDDING GAZETTE
        </h1>
        <p class="text-xs font-mono uppercase tracking-widest text-stone-700 mt-2">
          EXTRA! EXTRA! READ ALL ABOUT THE WEDDING OF TRUNG & VÂN
        </p>
      </div>

      <!-- Main Headline Press Block -->
      <section class="grid md:grid-cols-12 gap-8 border-b-2 border-stone-900 pb-12 mb-12">
        <div class="md:col-span-8 space-y-4">
          <span class="text-xs font-mono font-bold bg-stone-900 text-white px-2 py-0.5 uppercase">BREAKING NEWS</span>
          <h2 class="text-3xl md:text-5xl font-bold font-serif text-stone-950 leading-tight">
            QUỐC TRUNG & HỒNG VÂN OFFICIAL ANNOUNCEMENT!
          </h2>
          <p class="text-sm text-stone-800 leading-relaxed font-serif first-letter:text-4xl first-letter:font-bold first-letter:float-left first-letter:mr-2">
            Sau 6 năm gắn bó đầy ắp kỷ niệm từ những ngày chung đôi dưới mái trường đại học, chú rể Nguyễn Hoàng Quốc Trung và cô dâu Lê Thị Hồng Vân chính thức thông báo tin vui lễ thành hôn sẽ diễn ra vào ngày 24 tháng 10 năm 2026.
          </p>
        </div>

        <div class="md:col-span-4 border-2 border-stone-900 p-2 bg-stone-100">
          <div class="aspect-3/4 overflow-hidden border border-stone-900">
            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80" alt="Gazette Photo" class="w-full h-full object-cover grayscale contrast-125" />
          </div>
          <p class="text-[10px] font-mono text-center text-stone-700 mt-2">PHOTO: PRE-WEDDING IN DALAT</p>
        </div>
      </section>

      <main class="space-y-16 font-sans">
        <WeddingCoupleProfiles />
        <WeddingLoveStory />
        <WeddingScheduleAndMap />

        <section class="border-2 border-stone-900 p-8 bg-stone-50">
          <h2 class="text-2xl font-serif font-bold text-stone-950 mb-6 flex items-center justify-center gap-2">
            <Newspaper class="w-5 h-5 text-stone-900" />
            <span>PRESS PHOTO GALLERY</span>
          </h2>
          <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
        </section>

        <!-- Press Stamp RSVP -->
        <section class="border-4 border-stone-900 p-8 md:p-12 bg-white text-stone-950 relative">
          <div class="max-w-lg mx-auto space-y-6">
            <div class="text-center space-y-2">
              <span class="text-xs font-mono font-bold tracking-widest text-stone-600 uppercase">OFFICIAL RSVP REGISTRATION</span>
              <h2 class="text-3xl font-serif font-bold text-stone-950">SUBMIT YOUR RSVP RESPONSE</h2>
            </div>

            <form @submit.prevent="handleRsvpSubmit" class="space-y-4 pt-2 font-mono">
              <div class="grid grid-cols-2 gap-3">
                <button type="button" @click="rsvpAttending = 'yes'" class="py-3 border-2 border-stone-900 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-stone-900 text-white' : 'bg-white text-stone-900'">
                  <CheckCircle2 class="w-4 h-4" />
                  <span>ATTENDING</span>
                </button>
                <button type="button" @click="rsvpAttending = 'no'" class="py-3 border-2 border-stone-900 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-stone-900 text-white' : 'bg-white text-stone-900'">
                  <span>DECLINE</span>
                </button>
              </div>

              <div v-if="rsvpAttending === 'yes'">
                <label class="block text-xs text-stone-700 mb-1">GUEST COUNT</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 border-2 border-stone-900 text-sm focus:outline-none" />
              </div>

              <div>
                <label class="block text-xs text-stone-700 mb-1">PRESS CONGRATULATION MESSAGE</label>
                <textarea v-model="rsvpNotes" rows="3" class="w-full px-4 py-2.5 border-2 border-stone-900 text-sm focus:outline-none"></textarea>
              </div>

              <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 bg-stone-900 text-white font-bold text-xs uppercase tracking-widest hover:bg-stone-800 transition cursor-pointer">
                {{ isSubmittingRsvp ? 'PRINTING...' : 'PUBLISH RSVP RESPONSE' }}
              </button>

              <p v-if="rsvpSuccess" class="text-center text-xs font-mono text-emerald-700 bg-emerald-50 py-2 border border-emerald-500">
                📰 RSVP PRESS RELEASE PUBLISHED! THANK YOU!
              </p>
            </form>
          </div>
        </section>

        <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
      </main>

      <footer class="mt-12 border-t-2 border-stone-900 pt-6 text-center text-xs font-mono text-stone-600">
        <p>© 2026 THE WEDDING GAZETTE — TRUNG & VÂN SPECIAL EDITION</p>
      </footer>
    </div>
  </div>
</template>
