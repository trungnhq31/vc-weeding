<script setup lang="ts">
import { ref } from 'vue';
import { BookOpen, Heart, Calendar, MapPin, Sparkles, CheckCircle2, Bookmark } from 'lucide-vue-next';
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
const activeChapter = ref<number>(1);

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
  <div class="min-h-screen bg-gradient-to-b from-amber-200/80 via-amber-100/50 to-[#FDF6E3] text-amber-950 font-serif relative p-4 md:p-8">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Open Book Container -->
    <div class="max-w-4xl mx-auto bg-amber-50/90 rounded-3xl shadow-2xl border-2 border-amber-300 p-6 md:p-12 relative overflow-hidden">
      
      <!-- Bookmark Chapter Header Tabs -->
      <div class="flex items-center justify-center gap-2 border-b border-amber-200 pb-4 mb-8 overflow-x-auto">
        <button @click="activeChapter = 1" class="px-4 py-2 rounded-xl text-xs font-serif font-bold transition flex items-center gap-1.5 cursor-pointer whitespace-nowrap" :class="activeChapter === 1 ? 'bg-amber-800 text-white shadow-md' : 'bg-amber-100 text-amber-900 hover:bg-amber-200'">
          <Bookmark class="w-3.5 h-3.5" />
          <span>CHƯƠNG I: ĐÔI LỨA</span>
        </button>
        <button @click="activeChapter = 2" class="px-4 py-2 rounded-xl text-xs font-serif font-bold transition flex items-center gap-1.5 cursor-pointer whitespace-nowrap" :class="activeChapter === 2 ? 'bg-amber-800 text-white shadow-md' : 'bg-amber-100 text-amber-900 hover:bg-amber-200'">
          <Bookmark class="w-3.5 h-3.5" />
          <span>CHƯƠNG II: CHUYỆN TÌNH</span>
        </button>
        <button @click="activeChapter = 3" class="px-4 py-2 rounded-xl text-xs font-serif font-bold transition flex items-center gap-1.5 cursor-pointer whitespace-nowrap" :class="activeChapter === 3 ? 'bg-amber-800 text-white shadow-md' : 'bg-amber-100 text-amber-900 hover:bg-amber-200'">
          <Bookmark class="w-3.5 h-3.5" />
          <span>CHƯƠNG III: NGÀY CƯỚI</span>
        </button>
        <button @click="activeChapter = 4" class="px-4 py-2 rounded-xl text-xs font-serif font-bold transition flex items-center gap-1.5 cursor-pointer whitespace-nowrap" :class="activeChapter === 4 ? 'bg-amber-800 text-white shadow-md' : 'bg-amber-100 text-amber-900 hover:bg-amber-200'">
          <Bookmark class="w-3.5 h-3.5" />
          <span>CHƯƠNG IV: PHÚC ĐÁP</span>
        </button>
      </div>

      <!-- Journal Chapter Content -->
      <main class="space-y-12">
        <!-- Chapter I -->
        <div v-show="activeChapter === 1" class="space-y-8 animate-fade-in">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold text-amber-700 tracking-widest uppercase">FAIRYTALE STORYBOOK</span>
            <h1 class="text-3xl md:text-5xl font-bold text-amber-950">Chương I: Ngày Chúng Ta Gặp Nhau</h1>
          </div>
          <WeddingCoupleProfiles />
        </div>

        <!-- Chapter II -->
        <div v-show="activeChapter === 2" class="space-y-8 animate-fade-in">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold text-amber-700 tracking-widest uppercase">LOVE STORY JOURNAL</span>
            <h2 class="text-3xl md:text-5xl font-bold text-amber-950">Chương II: Hành Trình Tình Yêu</h2>
          </div>
          <WeddingLoveStory />
        </div>

        <!-- Chapter III -->
        <div v-show="activeChapter === 3" class="space-y-8 animate-fade-in">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold text-amber-700 tracking-widest uppercase">WEDDING CEREMONY</span>
            <h2 class="text-3xl md:text-5xl font-bold text-amber-950">Chương III: Lễ Thành Hôn & Tiệc Cưới</h2>
          </div>
          <WeddingScheduleAndMap />
        </div>

        <!-- Chapter IV -->
        <div v-show="activeChapter === 4" class="space-y-12 animate-fade-in">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold text-amber-700 tracking-widest uppercase">ALBUM & RSVP</span>
            <h2 class="text-3xl md:text-5xl font-bold text-amber-950">Chương IV: Kỷ Niệm & Thư Phúc Đáp</h2>
          </div>

          <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />

          <!-- Storybook RSVP Form -->
          <section class="bg-amber-900 text-amber-100 rounded-3xl p-8 shadow-xl border border-amber-700">
            <div class="max-w-lg mx-auto space-y-6">
              <div class="text-center space-y-2">
                <span class="text-xs font-bold tracking-widest text-amber-300 uppercase">THƯ PHÚC ĐÁP</span>
                <h3 class="text-2xl font-serif font-bold text-white">Viết Tiếp Trang Sách Cùng Dâu Rể</h3>
              </div>

              <form @submit.prevent="handleRsvpSubmit" class="space-y-4 font-sans">
                <div class="grid grid-cols-2 gap-3">
                  <button type="button" @click="rsvpAttending = 'yes'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-amber-500 text-amber-950 border-amber-400' : 'bg-amber-950 text-amber-200 border-amber-800'">
                    <CheckCircle2 class="w-4 h-4" />
                    <span>SẼ THAM DỰ</span>
                  </button>
                  <button type="button" @click="rsvpAttending = 'no'" class="py-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-amber-500 text-amber-950 border-amber-400' : 'bg-amber-950 text-amber-200 border-amber-800'">
                    <span>VẮNG MẶT</span>
                  </button>
                </div>

                <div v-if="rsvpAttending === 'yes'">
                  <label class="block text-xs font-bold text-amber-200 mb-1">SỐ KHÁCH DỰ TIỆC</label>
                  <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-amber-950 border border-amber-700 text-white text-sm focus:outline-none" />
                </div>

                <div>
                  <label class="block text-xs font-bold text-amber-200 mb-1">LỜI CHÚC TRONG TRANG SÁCH</label>
                  <textarea v-model="rsvpNotes" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-amber-950 border border-amber-700 text-white text-sm focus:outline-none"></textarea>
                </div>

                <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 text-amber-950 font-bold text-xs uppercase tracking-widest hover:opacity-95 transition cursor-pointer">
                  {{ isSubmittingRsvp ? 'ĐANG LƯU TRANG SÁCH...' : 'GỬI LỜI CHÚC THÀNH HÔN' }}
                </button>

                <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-amber-300 bg-amber-950 py-2 rounded-lg border border-amber-700">
                  📖 Đã lưu trang sách phúc đáp của bạn!
                </p>
              </form>
            </div>
          </section>

          <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
        </div>
      </main>

      <footer class="mt-12 pt-6 border-t border-amber-200 text-center text-xs font-serif text-amber-800">
        <p>© 2026 Eloria Fairytale Storybook Collection — Trung & Vân</p>
      </footer>
    </div>
  </div>
</template>
