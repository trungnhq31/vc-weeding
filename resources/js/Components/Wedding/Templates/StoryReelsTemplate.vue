<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Sparkles, Heart, CheckCircle2, ChevronLeft, ChevronRight, Gift, HelpCircle, Trophy, Volume2, Calendar, MapPin } from 'lucide-vue-next';
import WeddingMusicPlayer from '../WeddingMusicPlayer.vue';
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
const currentSlide = ref<number>(0);
const totalSlides = 6;
const isAutoPlay = ref<boolean>(true);
let autoPlayTimer: any = null;

// Quiz State
const quizAnswers = ref<Record<number, number>>({});
const quizSubmitted = ref(false);
const showConfetti = ref(false);

const quizQuestions = [
  {
    question: "Dâu Rể gặp nhau lần đầu tiên ở đâu?",
    options: ["Thư viện Đại Học", "Quán Cafe Phố Cổ", "Chuyến du lịch Đà Lạt", "Buổi tiệc sinh nhật bạn thân"],
    correct: 0
  },
  {
    question: "Ai là người ngỏ lời tỏ tình trước?",
    options: ["Chú rể Quốc Trung", "Cô dâu Hồng Vân", "Cả hai cùng lúc", "Được bạn bè gán ghép thành đôi"],
    correct: 0
  },
  {
    question: "Địa điểm dâu rể yêu thích nhất cho kỳ nghỉ?",
    options: ["Đà Lạt mộng mơ", "Biển Phú Quốc", "Sapa sương mù", "Nha Trang nắng vàng"],
    correct: 1
  }
];

const quizScore = computed(() => {
  let score = 0;
  quizQuestions.forEach((q, idx) => {
    if (quizAnswers.value[idx] === q.correct) {
      score += 1;
    }
  });
  return Math.round((score / quizQuestions.length) * 100);
});

const handleQuizSelect = (qIdx: number, oIdx: number) => {
  quizAnswers.value[qIdx] = oIdx;
};

const finishQuiz = () => {
  quizSubmitted.value = true;
  showConfetti.value = true;
  setTimeout(() => {
    showConfetti.value = false;
  }, 4000);
};

// RSVP State
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

const nextSlide = () => {
  if (currentSlide.value < totalSlides - 1) {
    currentSlide.value++;
  } else {
    currentSlide.value = 0;
  }
};

const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

const startTimer = () => {
  stopTimer();
  autoPlayTimer = setInterval(() => {
    if (isAutoPlay.value && !quizSubmitted.value) {
      nextSlide();
    }
  }, 7000);
};

const stopTimer = () => {
  if (autoPlayTimer) clearInterval(autoPlayTimer);
};

onMounted(() => {
  startTimer();
});

onUnmounted(() => {
  stopTimer();
});
</script>

<template>
  <div class="min-h-screen bg-rose-950 flex items-center justify-center p-0 md:p-6 font-sans relative overflow-hidden selection:bg-rose-200">
    <WeddingMusicPlayer ref="musicPlayerRef" />

    <!-- Confetti Overlay -->
    <div v-if="showConfetti" class="fixed inset-0 pointer-events-none z-50 flex items-center justify-center overflow-hidden">
      <div class="animate-bounce text-6xl">🎉 ✨ 💖 🥂 🎉</div>
    </div>

    <!-- Mobile Instagram Story Container -->
    <div class="w-full max-w-md h-screen md:h-[840px] md:rounded-[40px] bg-gradient-to-b from-rose-100/90 via-amber-50/60 to-[#FAF8F5] shadow-2xl relative flex flex-col justify-between overflow-hidden border border-rose-200/50">
      
      <!-- Top Story Progress Bar Header -->
      <div class="absolute top-0 left-0 right-0 z-30 p-4 pt-3 bg-gradient-to-b from-black/60 via-black/20 to-transparent text-white space-y-2">
        <div class="flex gap-1.5 h-1">
          <div 
            v-for="idx in totalSlides" 
            :key="idx" 
            class="h-full flex-1 rounded-full bg-white/30 overflow-hidden"
          >
            <div 
              class="h-full bg-white transition-all duration-300"
              :style="{ width: currentSlide === idx - 1 ? '100%' : (currentSlide > idx - 1 ? '100%' : '0%') }"
            ></div>
          </div>
        </div>

        <div class="flex items-center justify-between text-xs pt-1">
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-rose-500 text-white font-bold flex items-center justify-center text-[10px] border border-white">
              T&V
            </div>
            <div>
              <span class="font-bold block leading-none">Trung & Vân</span>
              <span class="text-[10px] text-rose-200 opacity-90">Wedding Reels Story</span>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <span class="px-2 py-0.5 rounded-full bg-white/20 text-[10px] font-mono font-bold">
              {{ currentSlide + 1 }}/{{ totalSlides }}
            </span>
          </div>
        </div>
      </div>

      <!-- Left/Right Tap Controls -->
      <div @click="prevSlide" class="absolute left-0 top-16 bottom-16 w-1/4 z-20 cursor-pointer"></div>
      <div @click="nextSlide" class="absolute right-0 top-16 bottom-16 w-1/4 z-20 cursor-pointer"></div>

      <!-- Slide 0: Cover Unboxing -->
      <div v-if="currentSlide === 0" class="h-full flex flex-col items-center justify-center p-8 text-center space-y-6 relative z-10 animate-fade-in">
        <div class="w-32 h-32 rounded-full p-2 bg-gradient-to-tr from-rose-400 via-amber-300 to-rose-500 shadow-xl relative animate-pulse">
          <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80" alt="Couple" class="w-full h-full object-cover rounded-full border-2 border-white" />
        </div>

        <div class="space-y-2">
          <span class="px-3.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-extrabold tracking-widest uppercase border border-rose-200">
            LỄ THÀNH HÔN
          </span>
          <h1 class="text-3xl font-bold text-rose-950 font-serif leading-tight">
            Quốc Trung & Hồng Vân
          </h1>
          <p class="text-xs font-mono text-rose-800 font-bold">
            24 THÁNG 10 NĂM 2026 — TP. HCM
          </p>
        </div>

        <div class="p-4 rounded-2xl bg-white/80 backdrop-blur-md border border-rose-200 shadow-sm text-xs text-rose-900 italic font-serif max-w-xs">
          "Chạm vào bên phải màn hình để bắt đầu hành trình đám cưới dâu rể! ✨"
        </div>
      </div>

      <!-- Slide 1: Couple Profiles -->
      <div v-else-if="currentSlide === 1" class="h-full flex flex-col items-center justify-center p-6 text-center space-y-6 relative z-10 animate-fade-in">
        <span class="text-xs font-bold tracking-widest uppercase text-rose-700">GẶP GỠ DÂU RỂ</span>

        <div class="grid grid-cols-2 gap-4 w-full">
          <div class="p-4 rounded-2xl bg-white/90 border border-rose-200 shadow-sm space-y-2">
            <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border-2 border-rose-300">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80" alt="Groom" class="w-full h-full object-cover" />
            </div>
            <h2 class="text-sm font-bold text-rose-950">Quốc Trung</h2>
            <span class="text-[10px] text-rose-600 font-mono font-semibold block">CHÚ RỂ</span>
            <p class="text-[11px] text-slate-600 italic">"Chàng trai luôn đồng hành và bảo vệ giấc mơ dâu."</p>
          </div>

          <div class="p-4 rounded-2xl bg-white/90 border border-rose-200 shadow-sm space-y-2">
            <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border-2 border-rose-300">
              <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80" alt="Bride" class="w-full h-full object-cover" />
            </div>
            <h2 class="text-sm font-bold text-rose-950">Hồng Vân</h2>
            <span class="text-[10px] text-rose-600 font-mono font-semibold block">CÔ DÂU</span>
            <p class="text-[11px] text-slate-600 italic">"Cô gái nhỏ nhắn với nụ cười làm ấm lòng rể."</p>
          </div>
        </div>
      </div>

      <!-- Slide 2: Timeline Story -->
      <div v-else-if="currentSlide === 2" class="h-full flex flex-col items-center justify-center p-6 space-y-5 relative z-10 animate-fade-in text-left">
        <div class="text-center space-y-1">
          <span class="text-xs font-bold text-rose-700 tracking-widest uppercase">LOVE STORY</span>
          <h2 class="text-xl font-serif font-bold text-rose-950">Hành Trình 6 Năm Chung Đôi</h2>
        </div>

        <div class="space-y-3 w-full text-xs font-sans">
          <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-2xs flex items-start gap-3">
            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-800 font-bold flex items-center justify-center text-[10px] shrink-0">01</span>
            <div>
              <span class="font-bold text-rose-950 block">15.09.2020 — Lần đầu chạm mắt</span>
              <p class="text-slate-600 text-[11px]">Gặp gỡ dưới mái trường đại học đầy kỷ niệm thơ mộng.</p>
            </div>
          </div>

          <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-2xs flex items-start gap-3">
            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-800 font-bold flex items-center justify-center text-[10px] shrink-0">02</span>
            <div>
              <span class="font-bold text-rose-950 block">24.12.2023 — Lời cầu hôn ngọt ngào</span>
              <p class="text-slate-600 text-[11px]">Bên bờ biển Đà Nẵng lung linh ánh nến và ngàn lời hứa.</p>
            </div>
          </div>

          <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-2xs flex items-start gap-3">
            <span class="w-6 h-6 rounded-full bg-rose-500 text-white font-bold flex items-center justify-center text-[10px] shrink-0">03</span>
            <div>
              <span class="font-bold text-rose-950 block">24.10.2026 — Lễ cưới viên mãn</span>
              <p class="text-slate-600 text-[11px]">Chính thức nắm tay nhau đi hết chặng đường đời còn lại.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 3: Interactive Love Quiz Widget -->
      <div v-else-if="currentSlide === 3" class="h-full flex flex-col items-center justify-center p-6 text-center space-y-4 relative z-10 animate-fade-in overflow-y-auto">
        <div class="space-y-1">
          <span class="px-3 py-0.5 rounded-full bg-rose-200 text-rose-900 text-[10px] font-extrabold tracking-widest uppercase">
            MINI GAME TƯƠNG TÁC
          </span>
          <h2 class="text-lg font-serif font-bold text-rose-950">Bạn Hiểu Dâu Rể Bao Nhiêu %?</h2>
        </div>

        <div v-if="!quizSubmitted" class="w-full space-y-3 text-left">
          <div v-for="(q, qIdx) in quizQuestions" :key="qIdx" class="p-3 rounded-2xl bg-white/95 border border-rose-200 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-rose-950 block">Câu {{ qIdx + 1 }}: {{ q.question }}</span>
            <div class="grid grid-cols-2 gap-1.5">
              <button
                v-for="(opt, oIdx) in q.options"
                :key="oIdx"
                @click.stop="handleQuizSelect(qIdx, oIdx)"
                class="p-2 rounded-xl border text-[11px] font-medium transition cursor-pointer text-center truncate"
                :class="quizAnswers[qIdx] === oIdx ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
              >
                {{ opt }}
              </button>
            </div>
          </div>

          <button 
            @click.stop="finishQuiz"
            :disabled="Object.keys(quizAnswers).length < quizQuestions.length"
            class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-amber-600 text-white font-bold text-xs shadow-md disabled:opacity-50 cursor-pointer uppercase tracking-wider"
          >
            Xem Thẻ Kết Quả Của Bạn 🎉
          </button>
        </div>

        <div v-else class="w-full p-6 rounded-3xl bg-white border-2 border-rose-300 shadow-lg space-y-4 animate-fade-in text-center">
          <div class="w-16 h-16 mx-auto rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
            <Trophy class="w-8 h-8" />
          </div>
          <div>
            <span class="text-xs font-bold text-rose-700 uppercase tracking-widest">ĐIỂM THÂN THIẾT CỦA BẠN</span>
            <div class="text-4xl font-extrabold text-rose-950 font-mono my-1">{{ quizScore }}%</div>
            <p class="text-xs text-rose-800 font-bold">
              {{ quizScore === 100 ? '🎉 Bạn là Tri Kỷ Hoàng Gia của Dâu Rể!' : '💖 Cảm ơn bạn đã luôn quan tâm và yêu thương Dâu Rể!' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Slide 4: Timeline Schedule & Maps -->
      <div v-else-if="currentSlide === 4" class="h-full flex flex-col items-center justify-center p-6 text-center space-y-4 relative z-10 animate-fade-in">
        <div class="space-y-1">
          <span class="text-xs font-bold text-rose-700 tracking-widest uppercase">LỊCH TRÌNH TIỆC CƯỚI</span>
          <h2 class="text-xl font-serif font-bold text-rose-950">Thời Gian & Địa Điểm</h2>
        </div>

        <div class="w-full space-y-3 font-sans text-xs text-left">
          <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-2xs space-y-1">
            <div class="flex items-center justify-between font-bold text-rose-950">
              <span class="flex items-center gap-1.5"><Calendar class="w-3.5 h-3.5 text-rose-600" /> Lễ Gia Tiên</span>
              <span class="font-mono text-rose-700">08:00 AM</span>
            </div>
            <p class="text-[11px] text-slate-600">Tư gia Nhà Gái — Quận 3, TP. Hồ Chí Minh</p>
          </div>

          <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-2xs space-y-1">
            <div class="flex items-center justify-between font-bold text-rose-950">
              <span class="flex items-center gap-1.5"><Calendar class="w-3.5 h-3.5 text-rose-600" /> Tiệc Cưới Chính</span>
              <span class="font-mono text-rose-700">11:30 AM</span>
            </div>
            <p class="text-[11px] text-slate-600">Trung Tâm Asiana Plaza — 45 Phan Đăng Lưu, Bình Thạnh</p>
          </div>

          <div class="p-3.5 rounded-2xl bg-white/90 border border-rose-200 shadow-2xs space-y-1">
            <div class="flex items-center justify-between font-bold text-rose-950">
              <span class="flex items-center gap-1.5"><Calendar class="w-3.5 h-3.5 text-rose-600" /> After-Party</span>
              <span class="font-mono text-rose-700">14:00 PM</span>
            </div>
            <p class="text-[11px] text-slate-600">Rooftop Lounge Asiana Plaza</p>
          </div>
        </div>

        <a 
          href="https://maps.google.com" 
          target="_blank" 
          @click.stop
          class="w-full py-3 rounded-xl bg-rose-900 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm"
        >
          <MapPin class="w-4 h-4 text-rose-300" />
          <span>Mở Chỉ Đường Google Maps</span>
        </a>
      </div>

      <!-- Slide 5: Multi-Event RSVP Form -->
      <div v-else-if="currentSlide === 5" class="h-full flex flex-col items-center justify-center p-6 text-center space-y-4 relative z-10 animate-fade-in overflow-y-auto">
        <div class="space-y-1">
          <span class="text-xs font-bold text-rose-700 tracking-widest uppercase">PHÚC ĐÁP THAM DỰ</span>
          <h2 class="text-xl font-serif font-bold text-rose-950">Xác Nhận Tham Dự Lễ Cưới</h2>
        </div>

        <form @submit.prevent="handleRsvpSubmit" @click.stop class="w-full space-y-3 text-left font-sans text-xs">
          <div class="grid grid-cols-2 gap-2">
            <button type="button" @click="rsvpAttending = 'yes'" class="py-2.5 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer" :class="rsvpAttending === 'yes' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-rose-900 border-rose-200'">
              <CheckCircle2 class="w-3.5 h-3.5" />
              <span>SẼ THAM DỰ</span>
            </button>
            <button type="button" @click="rsvpAttending = 'no'" class="py-2.5 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer" :class="rsvpAttending === 'no' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-rose-900 border-rose-200'">
              <span>VẮNG MẶT</span>
            </button>
          </div>

          <div v-if="rsvpAttending === 'yes'" class="space-y-2 p-3 rounded-xl bg-white/90 border border-rose-200">
            <label class="block text-[11px] font-bold text-rose-950 uppercase">Chọn sự kiện bạn sẽ tham dự</label>
            <div class="space-y-1.5 text-[11px]">
              <label class="flex items-center gap-2 cursor-pointer select-none text-slate-800">
                <input type="checkbox" v-model="rsvpCeremony" class="rounded border-rose-300 text-rose-600 focus:ring-rose-500 w-3.5 h-3.5" />
                <span class="font-bold">Lễ Gia Tiên</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer select-none text-slate-800">
                <input type="checkbox" v-model="rsvpReception" class="rounded border-rose-300 text-rose-600 focus:ring-rose-500 w-3.5 h-3.5" />
                <span class="font-bold">Tiệc Cưới Chính</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer select-none text-slate-800">
                <input type="checkbox" v-model="rsvpAfterparty" class="rounded border-rose-300 text-rose-600 focus:ring-rose-500 w-3.5 h-3.5" />
                <span class="font-bold">After-Party</span>
              </label>
            </div>
          </div>

          <div v-if="rsvpAttending === 'yes'" class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-[10px] font-bold text-rose-900 mb-0.5">SỐ KHÁCH</label>
              <input type="number" min="1" max="5" v-model="rsvpGuestsCount" class="w-full px-3 py-2 rounded-lg bg-white border border-rose-200 text-slate-900 text-xs focus:outline-none" />
            </div>
            <div>
              <label class="block text-[10px] font-bold text-rose-900 mb-0.5">KHẨU VỊ</label>
              <input type="text" v-model="rsvpDietary" placeholder="VD: Ăn chay..." class="w-full px-3 py-2 rounded-lg bg-white border border-rose-200 text-slate-900 text-xs focus:outline-none" />
            </div>
          </div>

          <div>
            <label class="block text-[10px] font-bold text-rose-900 mb-0.5">LỜI CHÚC MỪNG</label>
            <textarea v-model="rsvpNotes" rows="2" placeholder="Viết lời chúc mừng..." class="w-full px-3 py-2 rounded-lg bg-white border border-rose-200 text-slate-900 text-xs focus:outline-none"></textarea>
          </div>

          <button type="submit" :disabled="isSubmittingRsvp" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-amber-600 text-white font-bold text-xs uppercase tracking-wider shadow-md cursor-pointer">
            {{ isSubmittingRsvp ? 'ĐANG GỬI...' : 'GỬI PHÚC ĐÁP NGAY' }}
          </button>

          <p v-if="rsvpSuccess" class="text-center text-[11px] font-bold text-emerald-800 bg-emerald-50 py-2 rounded-lg border border-emerald-200">
            ✨ Cảm ơn bạn đã xác nhận tham dự!
          </p>
        </form>
      </div>

      <!-- Bottom Bar & Gift Modal trigger -->
      <div class="p-4 bg-white/80 backdrop-blur-md border-t border-rose-100 flex items-center justify-between text-xs z-30">
        <button @click="prevSlide" class="p-2 rounded-full bg-rose-100 text-rose-900 font-bold hover:bg-rose-200 transition cursor-pointer">
          <ChevronLeft class="w-4 h-4" />
        </button>

        <WeddingGiftBoxModal :wishes="wishes" :guest="guest" :submitWish="submitWish" />

        <button @click="nextSlide" class="p-2 rounded-full bg-rose-100 text-rose-900 font-bold hover:bg-rose-200 transition cursor-pointer">
          <ChevronRight class="w-4 h-4" />
        </button>
      </div>

    </div>
  </div>
</template>
