<script setup lang="ts">
import { ref } from 'vue';
import { Heart, Calendar, MapPin, Sparkles, LayoutGrid, CheckCircle2, FileText } from 'lucide-vue-next';
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
const activeSection = ref('hero');

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

const scrollTo = (id: string) => {
    activeSection.value = id;
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
};
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 font-sans relative selection:bg-slate-900 selection:text-white">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <div class="max-w-7xl mx-auto px-4 md:px-8 py-8 grid md:grid-cols-12 gap-8">
      
      <!-- Sticky Editorial Left Navigation Column -->
      <aside class="md:col-span-3 hidden md:block sticky top-8 h-[calc(100vh-4rem)] bg-white border border-slate-200 rounded-3xl p-6 shadow-2xs flex flex-col justify-between">
        <div class="space-y-8">
          <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 text-[10px] font-bold tracking-widest uppercase">
              <FileText class="w-3 h-3 text-slate-700" />
              <span>ISSUE NO. 10 / 2026</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-3 font-serif">
              EDITORIAL GAZETTE
            </h1>
            <p class="text-xs text-slate-500 font-mono mt-1">THE WEDDING OF TRUNG & VÂN</p>
          </div>

          <nav class="space-y-2 text-xs font-semibold">
            <button @click="scrollTo('hero')" class="w-full text-left px-3 py-2 rounded-xl transition flex items-center justify-between cursor-pointer" :class="activeSection === 'hero' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'">
              <span>01. COVER STORY</span>
              <span>→</span>
            </button>
            <button @click="scrollTo('profiles')" class="w-full text-left px-3 py-2 rounded-xl transition flex items-center justify-between cursor-pointer" :class="activeSection === 'profiles' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'">
              <span>02. THE COUPLE</span>
              <span>→</span>
            </button>
            <button @click="scrollTo('schedule')" class="w-full text-left px-3 py-2 rounded-xl transition flex items-center justify-between cursor-pointer" :class="activeSection === 'schedule' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'">
              <span>03. TIMELINE & MAP</span>
              <span>→</span>
            </button>
            <button @click="scrollTo('gallery')" class="w-full text-left px-3 py-2 rounded-xl transition flex items-center justify-between cursor-pointer" :class="activeSection === 'gallery' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'">
              <span>04. EDITORIAL GALLERY</span>
              <span>→</span>
            </button>
            <button @click="scrollTo('rsvp')" class="w-full text-left px-3 py-2 rounded-xl transition flex items-center justify-between cursor-pointer" :class="activeSection === 'rsvp' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'">
              <span>05. RSVP RESPONSES</span>
              <span>→</span>
            </button>
          </nav>
        </div>

        <div class="pt-6 border-t border-slate-100 text-[11px] text-slate-400 font-mono">
          OCTOBER 24, 2026<br />
          ASIANA PLAZA SAIGON
        </div>
      </aside>

      <!-- Main Editorial Content Column -->
      <main class="md:col-span-9 space-y-16">
        
        <!-- Hero Cover -->
        <section id="hero" class="bg-white border border-slate-200 rounded-3xl p-8 md:p-14 shadow-sm relative overflow-hidden">
          <div class="grid md:grid-cols-12 gap-8 items-center">
            <div class="md:col-span-7 space-y-6">
              <span class="text-xs font-mono font-bold tracking-widest text-slate-400 uppercase">EXCLUSIVE EDITORIAL COVER</span>
              <h1 class="text-4xl md:text-6xl font-black tracking-tight text-slate-900 leading-none">
                QUỐC TRUNG<br />
                <span class="text-slate-400 font-serif italic font-normal">& HỒNG VÂN</span>
              </h1>
              <p class="text-sm text-slate-600 font-serif leading-relaxed">
                "A minimalist modern celebration of love, design, and timeless companionship."
              </p>
              <div class="flex items-center gap-4 text-xs font-mono text-slate-500 pt-4 border-t border-slate-100">
                <span>DATE: 24.10.2026</span>
                <span>•</span>
                <span>LOCATION: TP. HCM</span>
              </div>
            </div>

            <div class="md:col-span-5">
              <div class="aspect-4/5 rounded-2xl overflow-hidden border border-slate-200 shadow-md">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80" alt="Editorial Cover" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-700" />
              </div>
            </div>
          </div>
        </section>

        <section id="profiles">
          <WeddingCoupleProfiles />
        </section>

        <section id="schedule">
          <WeddingScheduleAndMap />
        </section>

        <section id="gallery" class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
          <h2 class="text-2xl font-bold font-serif text-slate-900 mb-6">04. Editorial Photo Collection</h2>
          <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
        </section>

        <!-- RSVP Form Section -->
        <section id="rsvp" class="bg-slate-900 text-white rounded-3xl p-8 md:p-12 shadow-xl relative">
          <div class="max-w-xl mx-auto space-y-6">
            <div class="text-center space-y-2">
              <span class="text-xs font-mono font-bold tracking-widest text-slate-400 uppercase">ATTENDANCE FORM</span>
              <h2 class="text-3xl font-serif font-bold text-white">CONFIRM YOUR RSVP</h2>
            </div>

            <form @submit.prevent="handleRsvpSubmit" class="space-y-4 pt-2">
              <div class="grid grid-cols-2 gap-3">
                <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-white text-slate-900 border-white' : 'bg-slate-800 text-slate-300 border-slate-700'">
                  <CheckCircle2 class="w-4 h-4" />
                  <span>ATTENDING</span>
                </button>
                <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-white text-slate-900 border-white' : 'bg-slate-800 text-slate-300 border-slate-700'">
                  <span>REGRETFULLY DECLINE</span>
                </button>
              </div>

              <div v-if="rsvpAttending === 'yes'">
                <label class="block text-xs font-mono text-slate-300 mb-1">GUESTS COUNT</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-white" />
              </div>

              <div>
                <label class="block text-xs font-mono text-slate-300 mb-1">MESSAGE / NOTES</label>
                <textarea v-model="rsvpNotes" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-white"></textarea>
              </div>

              <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-white text-slate-900 font-bold text-xs uppercase tracking-widest hover:bg-slate-100 transition cursor-pointer">
                {{ isSubmittingRsvp ? 'SUBMITTING...' : 'SUBMIT RSVP RESPONSE' }}
              </button>

              <p v-if="rsvpSuccess" class="text-center text-xs font-mono text-emerald-400 bg-emerald-950/50 py-2 rounded-lg border border-emerald-800">
                ✓ RSVP SUBMISSION RECEIVED. THANK YOU!
              </p>
            </form>
          </div>
        </section>

        <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
      </main>

    </div>
  </div>
</template>
