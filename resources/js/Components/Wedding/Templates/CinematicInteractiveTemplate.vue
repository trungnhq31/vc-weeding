<script setup lang="ts">
import { ref, computed } from 'vue';
import { Crown, Sparkles, Calendar, MapPin, Search, CheckCircle2, Heart, Award, UserCheck } from 'lucide-vue-next';
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

// Interactive Guest Table Lookup Widget State
const searchQuery = ref('');
const searchResult = ref<any>(null);
const hasSearched = ref(false);

const sampleSeatingList = [
  { name: 'Nguyễn Văn An', phone: '0901234567', table: 'Bàn 01 — Họ Hàng Chú Rể', role: 'Họ Hàng' },
  { name: 'Trần Thị Bích', phone: '0912345678', table: 'Bàn 03 — Bạn Cấp 3 Dâu Rể', role: 'Bạn Cấp 3' },
  { name: 'Lê Hoàng Nam', phone: '0987654321', table: 'Bàn 05 — Đồng Nghiệp Công Ty', role: 'Đồng Nghiệp' },
  { name: 'Phạm Minh Đức', phone: '0978123456', table: 'Bàn 08 — Bạn Đại Học', role: 'Bạn Đại Học' },
];

const handleTableSearch = () => {
  hasSearched.value = true;
  if (!searchQuery.value) {
    searchResult.value = null;
    return;
  }
  const q = searchQuery.value.toLowerCase().trim();
  const matched = sampleSeatingList.find(s => s.name.toLowerCase().includes(q) || s.phone.includes(q));
  
  if (matched) {
    searchResult.value = matched;
  } else if (props.guest && props.guest.name && props.guest.name.toLowerCase().includes(q)) {
    searchResult.value = {
      name: props.guest.name,
      table: props.guest.table_name || 'Bàn VIP 02 — Khách Quý',
      role: props.guest.group || 'Khách Mời'
    };
  } else {
    searchResult.value = null;
  }
};

// Multi-Event RSVP State
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
  <div class="min-h-screen bg-gradient-to-b from-amber-50 via-yellow-50/40 to-[#FFFDF9] text-amber-950 font-serif relative selection:bg-amber-200">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Hero Section with Monogram Frame -->
    <header class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20">
      <div class="max-w-3xl w-full border-4 border-double border-amber-400/80 p-8 md:p-16 bg-white/90 shadow-2xl shadow-amber-300/30 rounded-3xl relative backdrop-blur-md">
        
        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-amber-600 via-yellow-500 to-amber-700 text-white flex items-center justify-center shadow-lg border-2 border-amber-200 mb-6">
          <Crown class="w-12 h-12 text-white" />
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100/90 text-amber-950 text-xs font-sans font-bold tracking-widest uppercase border border-amber-300 mb-4">
          <Award class="w-4 h-4 text-amber-700" />
          <span>CINEMATIC LUXURY CONCIERGE</span>
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold tracking-tight text-amber-950 uppercase leading-tight mb-3 break-words">
          Quốc Trung <span class="text-amber-600 font-serif italic text-3xl md:text-5xl">&</span> Hồng Vân
        </h1>

        <p class="text-xs sm:text-sm font-sans tracking-widest uppercase text-amber-800 font-semibold mb-6">
          TRÂN TRỌNG KÍNH MỜI KÍNH DỰ LỄ THÀNH HÔN
        </p>

        <div class="h-0.5 w-36 bg-gradient-to-r from-transparent via-amber-500 to-transparent mx-auto mb-6"></div>

        <p class="text-base md:text-lg font-serif italic text-amber-900/90 max-w-lg mx-auto">
          "Trăm năm tình viên mãn — Nắm tay nhau đi qua ngàn giông bão để cập bến hạnh phúc."
        </p>

        <div class="mt-8 pt-6 border-t border-amber-200/80 text-xs font-sans font-bold text-amber-800 tracking-wider">
          24 . 10 . 2026 — ASIANA PLAZA SAIGON
        </div>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-24 space-y-16 relative z-10 font-sans">
      <WeddingCoupleProfiles />
      <WeddingLoveStory />

      <!-- Interactive Guest Table Lookup Concierge Widget -->
      <section class="bg-white/95 border-2 border-amber-300 rounded-3xl p-6 md:p-10 shadow-xl shadow-amber-200/20">
        <div class="max-w-xl mx-auto text-center space-y-6">
          <div class="space-y-2">
            <span class="text-xs font-bold tracking-widest text-amber-700 uppercase">GUEST CONCIERGE</span>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-amber-950">Tra Cứu Vị Trí Bàn Tiệc</h2>
            <p class="text-xs text-amber-900/80 font-serif italic">
              Nhập họ tên hoặc số điện thoại của bạn để kiểm tra vị trí bàn tiệc được dâu rể trân trọng chuẩn bị:
            </p>
          </div>

          <div class="flex items-center gap-2 max-w-md mx-auto">
            <div class="relative flex-1">
              <Search class="w-4 h-4 text-amber-500 absolute left-3.5 top-3.5" />
              <input 
                type="text" 
                v-model="searchQuery"
                @keyup.enter="handleTableSearch"
                placeholder="VD: Nguyễn Văn An..." 
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-amber-300 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white"
              />
            </div>
            <button 
              @click="handleTableSearch"
              class="px-5 py-2.5 rounded-xl bg-amber-600 text-white font-bold text-xs hover:bg-amber-700 transition shadow-sm cursor-pointer"
            >
              Tra Cứu
            </button>
          </div>

          <!-- Result Card -->
          <div v-if="hasSearched" class="pt-2">
            <div v-if="searchResult" class="p-5 rounded-2xl bg-amber-50 border border-amber-300 shadow-2xs text-left space-y-2 animate-fade-in">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                  <UserCheck class="w-4 h-4 text-amber-600" />
                  THÔNG TIN KHÁCH MỜI
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-200 text-amber-950 text-[11px] font-bold">
                  {{ searchResult.role }}
                </span>
              </div>
              <div class="text-base font-bold text-amber-950">{{ searchResult.name }}</div>
              <div class="text-sm font-bold text-emerald-800 bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 font-mono">
                📌 {{ searchResult.table }}
              </div>
            </div>

            <div v-else class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 italic">
              Chưa tìm thấy thông tin trùng khớp. Đừng lo lắng, ban lễ tân tại Asiana Plaza sẽ đón tiếp và hướng dẫn bạn tận tình!
            </div>
          </div>
        </div>
      </section>

      <WeddingScheduleAndMap />

      <section class="bg-white/95 border border-amber-300 rounded-3xl p-6 md:p-8 shadow-xl">
        <h2 class="text-2xl font-serif font-bold text-center text-amber-950 mb-6 flex items-center justify-center gap-2">
          <Crown class="w-5 h-5 text-amber-600" />
          <span>Album Ảnh Hoàng Gia</span>
        </h2>
        <WeddingPhotoGallery :memories="memories" :guest="guest" :uploadMemory="uploadMemory" />
      </section>

      <!-- Multi-Event RSVP Form -->
      <section class="bg-white/95 rounded-3xl p-6 md:p-12 shadow-xl shadow-amber-200/30 border-2 border-amber-300">
        <div class="max-w-lg mx-auto space-y-6">
          <div class="text-center space-y-2">
            <span class="text-xs font-bold tracking-widest text-amber-700 uppercase">HOÀNG GIA KÍNH MỜI</span>
            <h2 class="text-3xl font-serif font-bold text-amber-950">Phúc Đáp Tham Dự</h2>
          </div>

          <form @submit.prevent="handleRsvpSubmit" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
              <button 
                type="button" 
                @click="rsvpAttending = 'yes'"
                class="py-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                :class="rsvpAttending === 'yes' ? 'bg-amber-600 text-white border-amber-600 shadow-md' : 'bg-amber-50 text-amber-900 border-amber-200 hover:bg-amber-100/50'"
              >
                <CheckCircle2 class="w-4 h-4" />
                <span>Sẽ Tham Dự Tiệc</span>
              </button>
              <button 
                type="button" 
                @click="rsvpAttending = 'no'"
                class="py-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                :class="rsvpAttending === 'no' ? 'bg-amber-950 text-white border-amber-950 shadow-md' : 'bg-amber-50 text-amber-900 border-amber-200 hover:bg-amber-100/50'"
              >
                <span>Xin Phép Vắng Mặt</span>
              </button>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="space-y-3 p-4 rounded-2xl bg-amber-50/50 border border-amber-200/80">
              <label class="block text-xs font-bold text-amber-900 font-sans uppercase">Chọn sự kiện tham dự</label>
              <div class="space-y-2 text-xs font-sans">
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpCeremony" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 w-4 h-4" />
                  <span class="font-bold">Lễ Gia Tiên & Rước Dâu</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpReception" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 w-4 h-4" />
                  <span class="font-bold">Tiệc Cưới Chính (Khai Tiệc)</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-slate-800">
                  <input type="checkbox" v-model="rsvpAfterparty" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 w-4 h-4" />
                  <span class="font-bold">Dư Tiệc / After-Party bạn thân</span>
                </label>
              </div>
            </div>

            <div v-if="rsvpAttending === 'yes'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-amber-900 mb-1">Số lượng khách tham dự</label>
                <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-4 py-2.5 rounded-xl bg-white border border-amber-300 text-slate-900 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" />
              </div>
              <div>
                <label class="block text-xs font-bold text-amber-900 mb-1">Khẩu vị / Chế độ ăn</label>
                <input type="text" v-model="rsvpDietary" placeholder="VD: Ăn chay, không hải sản..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-amber-300 text-slate-900 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-amber-900 mb-1">Lời chúc mừng dành cho cặp đôi</label>
              <textarea v-model="rsvpNotes" rows="3" placeholder="Gửi lời chúc mừng ấm áp..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-amber-300 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>
            </div>

            <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 text-white font-bold text-sm shadow-lg shadow-amber-600/25 hover:opacity-95 transition cursor-pointer">
              {{ isSubmittingRsvp ? 'Đang gửi...' : 'Gửi Phúc Đáp Hoàng Gia' }}
            </button>

            <p v-if="rsvpSuccess" class="text-center text-xs font-bold text-emerald-800 bg-emerald-50 py-2.5 rounded-xl border border-emerald-200">
              ✨ Đã gửi phúc đáp thành công!
            </p>
          </form>
        </div>
      </section>

      <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />
    </main>

    <footer class="bg-amber-950 text-amber-200 text-center py-8 text-xs font-sans border-t border-amber-800">
      <p>© 2026 Eloria Cinematic Concierge Collection — Trung & Vân</p>
    </footer>
  </div>
</template>
