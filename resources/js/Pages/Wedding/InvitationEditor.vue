<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { 
  Sparkles, 
  Save, 
  Type, 
  Calendar, 
  MapPin, 
  Music, 
  Palette, 
  Gift, 
  QrCode, 
  Heart, 
  CheckCircle2, 
  ShieldCheck,
  ExternalLink,
  Smartphone,
  Monitor,
  Check,
  Layers,
  LayoutGrid,
  FileText,
  Sliders,
  SlidersHorizontal
} from 'lucide-vue-next';

// Import All 10 Template Components
import PastelTemplate from '@/Components/Wedding/Templates/PastelTemplate.vue';
import RoyalGoldTemplate from '@/Components/Wedding/Templates/RoyalGoldTemplate.vue';
import ModernSlateTemplate from '@/Components/Wedding/Templates/ModernSlateTemplate.vue';
import BotanicalSageTemplate from '@/Components/Wedding/Templates/BotanicalSageTemplate.vue';
import IndochineTemplate from '@/Components/Wedding/Templates/IndochineTemplate.vue';
import BoardingPassTemplate from '@/Components/Wedding/Templates/BoardingPassTemplate.vue';
import EmeraldLuxeTemplate from '@/Components/Wedding/Templates/EmeraldLuxeTemplate.vue';
import SunsetCoralTemplate from '@/Components/Wedding/Templates/SunsetCoralTemplate.vue';
import GazetteNewspaperTemplate from '@/Components/Wedding/Templates/GazetteNewspaperTemplate.vue';
import StorybookJournalTemplate from '@/Components/Wedding/Templates/StorybookJournalTemplate.vue';

interface TemplateItem {
  id: string;
  name: string;
  desc: string;
  badge: string;
  badgeColor: string;
}

const props = defineProps<{
  workspace?: any;
  invitation?: any;
  templates?: TemplateItem[];
}>();

const activeSubNav = ref<'templates' | 'details' | 'design' | 'features'>('templates');
const previewMode = ref<'mobile' | 'desktop'>('mobile');
const isSaved = ref(false);
const isSaving = ref(false);
const saveMessage = ref('');

const form = useForm({
  template_id: props.invitation?.template_id || 'romantic-pastel',
  custom_title: props.invitation?.custom_title || 'Lễ Thành Hôn',
  groom_name: props.workspace?.groom_name || 'Nguyễn Hoàng Quốc Trung',
  bride_name: props.workspace?.bride_name || 'Lê Thị Hồng Vân',
  groom_parents: props.invitation?.groom_parents || 'Ông N.V. Nam & Bà T.T. Mai',
  bride_parents: props.invitation?.bride_parents || 'Ông L.V. Hùng & Bà P.T. Cúc',
  wedding_date: props.workspace?.wedding_date ? props.workspace.wedding_date.substring(0, 10) : '2026-10-24',
  event_time: props.invitation?.event_time || '11:30 Sáng',
  wedding_location: props.workspace?.wedding_location || 'TP. Hồ Chí Minh',
  venue_name: props.workspace?.venue_name || 'Trung Tâm Hội Nghị Asiana Plaza',
  google_maps_url: props.invitation?.google_maps_url || 'https://maps.google.com/?q=Asiana+Plaza',
  primary_color: props.invitation?.primary_color || '#EC4899',
  font_family: props.invitation?.font_family || 'Playfair Display',
  music_url: props.invitation?.music_url || 'https://assets.mixkit.co/music/preview/mixkit-romantic-wedding-piano-1100.mp3',
  bank_name: props.invitation?.bank_name || 'Vietcombank',
  bank_account_number: props.invitation?.bank_account_number || '1029384756',
  bank_account_holder: props.invitation?.bank_account_holder || 'NGUYEN HOANG QUOC TRUNG',
  enable_wax_seal: props.invitation?.enable_wax_seal ?? true,
  enable_qr_checkin: props.invitation?.enable_qr_checkin ?? true,
  enable_gift_box: props.invitation?.enable_gift_box ?? true,
});

const colorPresets = [
  { name: 'Rose Gold', hex: '#EC4899' },
  { name: 'Champagne Gold', hex: '#D97706' },
  { name: 'Lavender Bliss', hex: '#8B5CF6' },
  { name: 'Celestial Blue', hex: '#0284C7' },
  { name: 'Imperial Emerald', hex: '#064E3B' },
  { name: 'Sunset Coral', hex: '#EA580C' },
  { name: 'Crimson Velvet', hex: '#BE123C' },
  { name: 'Vintage Sepia', hex: '#92400E' },
];

const fontPresets = [
  { name: 'Playfair Display', family: 'font-serif' },
  { name: 'Cormorant Garamond', family: 'font-serif' },
  { name: 'Inter Minimalist', family: 'font-sans' },
  { name: 'Dancing Script', family: 'font-serif italic' },
];

const templateCatalog: TemplateItem[] = [
  { id: 'romantic-pastel', name: '1. Romantic Pastel & Wax Seal', desc: 'Thiệp pastel lãng mạn kèm phong bì sáp nến', badge: 'Mở Sáp Nến', badgeColor: 'bg-rose-100 text-rose-900 border-rose-200' },
  { id: 'royal-gold', name: '2. Royal Gold & Monogram Crest', desc: 'Khung vàng hoàng gia & biểu tượng Monogram T&V', badge: 'Hoàng Gia', badgeColor: 'bg-amber-100 text-amber-900 border-amber-200' },
  { id: 'modern-slate', name: '3. Modern Editorial Magazine', desc: 'Bố cục tạp chí thời trang asymmetric 2 cột', badge: 'Tạp Chí', badgeColor: 'bg-slate-200 text-slate-800 border-slate-300' },
  { id: 'botanical-sage', name: '4. Botanical Garden & Arch Cards', desc: 'Khung vòm hoa lá thiên nhiên màu xô thơm tươi mát', badge: 'Thiên Nhiên', badgeColor: 'bg-emerald-100 text-emerald-900 border-emerald-200' },
  { id: 'indochine-traditional', name: '5. Indochine Red Velvet & Song Hỷ', desc: 'Hoa văn truyền thống Đông Dương & Song Hỷ dát vàng', badge: 'Truyền Thống', badgeColor: 'bg-red-800 text-amber-200 border-amber-400' },
  { id: 'celestial-blue', name: '6. Ocean Breeze Boarding Pass Ticket', desc: 'Vé máy bay chuyến bay tình yêu xé góc', badge: 'Vé Máy Bay', badgeColor: 'bg-sky-100 text-sky-900 border-sky-200' },
  { id: 'emerald-luxe', name: '7. Imperial Emerald Glass Ring', desc: 'Kính mờ xanh ngọc bảo & vòng đếm ngược kim tuyến', badge: 'Ngọc Bảo', badgeColor: 'bg-emerald-900 text-amber-300 border-amber-400' },
  { id: 'sunset-coral', name: '8. Tropical Sunset 50/50 Split View', desc: 'Bố cục 50/50 chia đôi màn hình màu cam san hô', badge: 'Hoàng Hôn', badgeColor: 'bg-orange-100 text-orange-900 border-orange-200' },
  { id: 'crimson-velvet', name: '9. The Wedding Gazette Newspaper', desc: 'Tờ báo tin tức tiệc cưới cổ điển 3 cột', badge: 'Tờ Báo Cổ', badgeColor: 'bg-stone-900 text-white border-stone-700' },
  { id: 'vintage-sepia', name: '10. Storybook Fairytale Journal', desc: 'Cuốn sách câu chuyện tình yêu phân chương tab', badge: 'Sách Tình Yêu', badgeColor: 'bg-amber-200 text-amber-950 border-amber-400' },
];

const selectedTemplate = computed(() => {
  return templateCatalog.find(t => t.id === form.template_id) || templateCatalog[0];
});

// Map Selected Template ID directly to the Real Vue Template Component!
const activeTemplateComponent = computed(() => {
  const map: Record<string, any> = {
    'romantic-pastel': PastelTemplate,
    'royal-gold': RoyalGoldTemplate,
    'modern-slate': ModernSlateTemplate,
    'botanical-sage': BotanicalSageTemplate,
    'indochine-traditional': IndochineTemplate,
    'celestial-blue': BoardingPassTemplate,
    'ocean-breeze': BoardingPassTemplate,
    'emerald-luxe': EmeraldLuxeTemplate,
    'sunset-coral': SunsetCoralTemplate,
    'crimson-velvet': GazetteNewspaperTemplate,
    'vintage-sepia': StorybookJournalTemplate,
    'boho-chic': PastelTemplate,
    'minimalist-ivory': ModernSlateTemplate,
  };
  return map[form.template_id] || PastelTemplate;
});

const saveCms = async () => {
  isSaving.value = true;
  try {
    const response = await fetch('/wedding/invitation-editor/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(form.data()),
    });

    if (response.ok) {
      isSaved.value = true;
      saveMessage.value = 'Đã lưu cấu hình thiệp cưới thành công!';
      setTimeout(() => {
        isSaved.value = false;
      }, 3000);
    }
  } catch (e) {
    console.error('Error saving CMS configuration:', e);
  } finally {
    isSaving.value = false;
  }
};

const externalPreviewUrl = computed(() => {
  return `/invitations/${form.template_id}`;
});
</script>

<template>
  <WorkspaceLayout title="Tùy Biến Thiệp Cưới" active-nav="invitation_editor">
    <div class="space-y-6 font-sans max-w-7xl mx-auto px-2 md:px-6 py-6">
      
      <!-- Top Main Action Bar -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-rose-100 pb-4">
        <div>
          <h1 class="text-2xl font-serif font-extrabold text-slate-900 flex items-center gap-2">
            <Sparkles class="w-6 h-6 text-[#881337]" />
            CMS Tùy Biến Thiệp Cưới
          </h1>
          <p class="text-xs text-slate-500">Tùy chỉnh kho 10 giao diện thiệp cưới, thông tin lễ tiệc và xem trước trực tiếp</p>
        </div>

        <div class="flex items-center gap-3">
          <a 
            :href="externalPreviewUrl" 
            target="_blank" 
            class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-2xs flex items-center gap-1.5 cursor-pointer transition"
          >
            <ExternalLink class="w-4 h-4 text-slate-500" />
            <span>Mở Thiệp Trong Tab Mới</span>
          </a>

          <button 
            @click="saveCms"
            :disabled="isSaving"
            class="px-6 py-2.5 rounded-2xl bg-[#881337] hover:bg-[#70102d] text-white font-extrabold text-xs shadow-lg flex items-center gap-2 cursor-pointer transition disabled:opacity-50"
          >
            <Save class="w-4 h-4 text-amber-300" />
            <span>{{ isSaving ? 'Đang lưu...' : 'Lưu Thay Đổi CMS' }}</span>
          </button>
        </div>
      </div>

      <!-- Success Notification Toast -->
      <div v-if="isSaved" class="p-4 rounded-2xl bg-emerald-900 text-white shadow-xl flex items-center justify-between animate-fade-in border border-emerald-700">
        <div class="flex items-center gap-3">
          <CheckCircle2 class="w-5 h-5 text-emerald-300 shrink-0" />
          <span class="text-xs font-bold">{{ saveMessage }}</span>
        </div>
        <button @click="isSaved = false" class="text-xs underline font-bold cursor-pointer">Đóng</button>
      </div>

      <!-- HORIZONTAL SUB NAVIGATION BAR (Thanh Điều Hướng Phụ CMS) -->
      <div class="bg-white p-2 rounded-3xl border border-rose-100 shadow-md flex items-center justify-between overflow-x-auto gap-2">
        <div class="flex items-center gap-2">
          <button 
            @click="activeSubNav = 'templates'"
            class="px-6 py-3 rounded-2xl font-extrabold text-xs transition-all flex items-center gap-2 cursor-pointer whitespace-nowrap"
            :class="activeSubNav === 'templates' ? 'bg-[#881337] text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/60'"
          >
            <LayoutGrid class="w-4 h-4 text-amber-300" />
            <span>1. Chọn Mẫu Thiệp (10 Templates)</span>
          </button>

          <button 
            @click="activeSubNav = 'details'"
            class="px-6 py-3 rounded-2xl font-extrabold text-xs transition-all flex items-center gap-2 cursor-pointer whitespace-nowrap"
            :class="activeSubNav === 'details' ? 'bg-[#881337] text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/60'"
          >
            <Calendar class="w-4 h-4 text-amber-300" />
            <span>2. Thông Tin Lễ Cưới & Địa Điểm</span>
          </button>

          <button 
            @click="activeSubNav = 'design'"
            class="px-6 py-3 rounded-2xl font-extrabold text-xs transition-all flex items-center gap-2 cursor-pointer whitespace-nowrap"
            :class="activeSubNav === 'design' ? 'bg-[#881337] text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/60'"
          >
            <Palette class="w-4 h-4 text-amber-300" />
            <span>3. Tông Màu & Font Chữ</span>
          </button>

          <button 
            @click="activeSubNav = 'features'"
            class="px-6 py-3 rounded-2xl font-extrabold text-xs transition-all flex items-center gap-2 cursor-pointer whitespace-nowrap"
            :class="activeSubNav === 'features' ? 'bg-[#881337] text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/60'"
          >
            <Gift class="w-4 h-4 text-amber-300" />
            <span>4. Tính Năng & Hộp Mừng Cưới</span>
          </button>
        </div>

        <div class="px-4 py-2 rounded-2xl bg-rose-50 text-[#881337] font-bold text-xs shrink-0 hidden lg:flex items-center gap-2 border border-rose-100">
          <Sparkles class="w-4 h-4 text-amber-500" />
          <span>Mẫu Đang Chọn: <strong>{{ selectedTemplate.name }}</strong></span>
        </div>
      </div>

      <!-- Main Workspace 2-Column Grid (Edit Area + Live Template Preview Frame) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT EDIT AREA (Expanded Width Area for CMS Options) -->
        <div class="lg:col-span-5 space-y-6">
          
          <!-- SUB NAV PANEL 1: KHO MẪU THIỆP -->
          <div v-if="activeSubNav === 'templates'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div>
                <h2 class="text-base font-serif font-extrabold text-slate-900">Kho 10 Mẫu Thiệp Mời Cưới Độc Bản</h2>
                <p class="text-xs text-slate-500">Click chọn mẫu để tải trực tiếp giao diện thực tế sang khung xem trước bên phải</p>
              </div>
            </div>

            <div class="space-y-3 max-h-[620px] overflow-y-auto pr-1">
              <div 
                v-for="tpl in templateCatalog" 
                :key="tpl.id"
                @click="form.template_id = tpl.id"
                class="p-4.5 rounded-2xl border transition-all cursor-pointer flex items-center justify-between group"
                :class="form.template_id === tpl.id ? 'border-[#881337] bg-rose-50/50 shadow-md ring-2 ring-rose-200' : 'border-slate-200 hover:border-rose-300 bg-white'"
              >
                <div class="space-y-1">
                  <div class="flex items-center gap-2">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-[#881337] transition">{{ tpl.name }}</h3>
                    <span :class="['px-2.5 py-0.5 rounded-full text-[10px] font-bold border', tpl.badgeColor]">{{ tpl.badge }}</span>
                  </div>
                  <p class="text-xs text-slate-500 font-medium">{{ tpl.desc }}</p>
                </div>
                <div class="w-6 h-6 rounded-full border flex items-center justify-center shrink-0" :class="form.template_id === tpl.id ? 'border-[#881337] bg-[#881337] text-white' : 'border-slate-300'">
                  <Check v-if="form.template_id === tpl.id" class="w-4 h-4 stroke-[3]" />
                </div>
              </div>
            </div>
          </div>

          <!-- SUB NAV PANEL 2: ĐỊA ĐIỂM & LỄ CƯỚI -->
          <div v-if="activeSubNav === 'details'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-base font-serif font-extrabold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
              <Calendar class="w-5 h-5 text-[#881337]" />
              Thông Tin Lễ Cưới & Địa Điểm Tiệc
            </h2>

            <div class="space-y-4 text-xs">
              <div>
                <label class="font-bold text-slate-700 block mb-1">Tiêu Đề Lễ</label>
                <input v-model="form.custom_title" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Tên Chú Rể</label>
                  <input v-model="form.groom_name" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Tên Cô Dâu</label>
                  <input v-model="form.bride_name" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Đơn Vị Nhà Trai</label>
                  <input v-model="form.groom_parents" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:border-[#881337] outline-none" />
                </div>
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Đơn Vị Nhà Gái</label>
                  <input v-model="form.bride_parents" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:border-[#881337] outline-none" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Ngày Tổ Chức</label>
                  <input v-model="form.wedding_date" type="date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Giờ Tổ Chức Tiệc</label>
                  <input v-model="form.event_time" type="text" placeholder="11:30 Sáng" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
              </div>

              <div>
                <label class="font-bold text-slate-700 block mb-1">Tên Tỉnh / Thành Phố</label>
                <input v-model="form.wedding_location" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-bold text-slate-700 block mb-1">Trung Tâm / Địa Điểm Tiệc</label>
                <input v-model="form.venue_name" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-bold text-slate-700 block mb-1">Link Google Maps Chỉ Đường</label>
                <input v-model="form.google_maps_url" type="url" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-mono text-xs text-slate-900 focus:border-[#881337] outline-none" />
              </div>
            </div>
          </div>

          <!-- SUB NAV PANEL 3: TÔNG MÀU & FONT CHỮ -->
          <div v-if="activeSubNav === 'design'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-5">
            <h2 class="text-base font-serif font-extrabold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
              <Palette class="w-5 h-5 text-[#881337]" />
              Tông Màu Chủ Đạo & Font Chữ
            </h2>

            <div>
              <label class="text-xs font-bold text-slate-700 block mb-2">Chọn Bảng Màu Preset</label>
              <div class="grid grid-cols-4 gap-2.5">
                <button
                  v-for="color in colorPresets"
                  :key="color.hex"
                  type="button"
                  @click="form.primary_color = color.hex"
                  class="p-2.5 rounded-2xl border text-center transition cursor-pointer flex flex-col items-center gap-1.5"
                  :class="form.primary_color === color.hex ? 'border-slate-900 ring-2 ring-rose-300' : 'border-slate-200 hover:border-rose-200'"
                >
                  <span class="w-7 h-7 rounded-full border shadow-2xs" :style="{ backgroundColor: color.hex }"></span>
                  <span class="text-[10px] font-bold truncate w-full text-slate-700">{{ color.name }}</span>
                </button>
              </div>
            </div>

            <div>
              <label class="text-xs font-bold text-slate-700 block mb-1">Font Chữ Chủ Đạo</label>
              <select v-model="form.font_family" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-900 focus:border-[#881337] outline-none">
                <option v-for="font in fontPresets" :key="font.name" :value="font.name">{{ font.name }}</option>
              </select>
            </div>

            <div>
              <label class="text-xs font-bold text-slate-700 flex items-center gap-1 mb-1">
                <Music class="w-4 h-4 text-[#881337]" />
                Link Nhạc Nền MP3 (Lãng mạn)
              </label>
              <input v-model="form.music_url" type="url" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-mono focus:border-[#881337] outline-none" />
            </div>
          </div>

          <!-- SUB NAV PANEL 4: TÍNH NĂNG & HỘP MỪNG CƯỚI -->
          <div v-if="activeSubNav === 'features'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-base font-serif font-extrabold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
              <Gift class="w-5 h-5 text-[#881337]" />
              Tính Năng Tương Tác & Hộp Mừng Cưới
            </h2>

            <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs">
              <label class="flex items-center justify-between font-bold text-slate-700 cursor-pointer">
                <span>Hiệu ứng Mở Bì Thư Sáp Nến (Wax Seal)</span>
                <input type="checkbox" v-model="form.enable_wax_seal" class="accent-[#881337] w-4 h-4" />
              </label>

              <label class="flex items-center justify-between font-bold text-slate-700 cursor-pointer">
                <span>Mã QR Code Check-in độc bản cho khách</span>
                <input type="checkbox" v-model="form.enable_qr_checkin" class="accent-[#881337] w-4 h-4" />
              </label>

              <label class="flex items-center justify-between font-bold text-slate-700 cursor-pointer">
                <span>Khung Hộp Mừng Cưới Chuyển Khoản QR</span>
                <input type="checkbox" v-model="form.enable_gift_box" class="accent-[#881337] w-4 h-4" />
              </label>
            </div>

            <div v-if="form.enable_gift_box" class="space-y-4 pt-2 text-xs">
              <div class="font-bold text-slate-900 flex items-center gap-1.5">
                <ShieldCheck class="w-4 h-4 text-emerald-600" />
                Thông Tin Tài Khoản Mừng Cưới Chú Rể / Cô Dâu
              </div>

              <div>
                <label class="font-semibold text-slate-700 block mb-1">Tên Ngân Hàng</label>
                <input v-model="form.bank_name" type="text" placeholder="Vietcombank, MB Bank..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-semibold text-slate-700 block mb-1">Số Tài Khoản Chuyển Khoản</label>
                <input v-model="form.bank_account_number" type="text" placeholder="1029384756" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-mono font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-semibold text-slate-700 block mb-1">Tên Chủ Tài Khoản</label>
                <input v-model="form.bank_account_holder" type="text" placeholder="NGUYEN HOANG QUOC TRUNG" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl uppercase font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT COLUMN: REAL TEMPLATE DYNAMIC PREVIEW FRAME (Loads Real Selected Template Component) -->
        <div class="lg:col-span-7 sticky top-6 space-y-3">
          
          <!-- Viewport Toolbar & Selected Template Indicator -->
          <div class="p-3.5 bg-white rounded-2xl border border-slate-200 shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-slate-700">Chế độ xem:</span>
              <button 
                @click="previewMode = 'mobile'" 
                class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition cursor-pointer flex items-center gap-1.5"
                :class="previewMode === 'mobile' ? 'bg-[#881337] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
              >
                <Smartphone class="w-3.5 h-3.5" /> Mobile
              </button>
              <button 
                @click="previewMode = 'desktop'" 
                class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition cursor-pointer flex items-center gap-1.5"
                :class="previewMode === 'desktop' ? 'bg-[#881337] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
              >
                <Monitor class="w-3.5 h-3.5" /> Desktop
              </button>
            </div>

            <div class="text-xs font-extrabold text-[#881337] flex items-center gap-1.5">
              <Sparkles class="w-4 h-4 text-amber-500 animate-spin" />
              <span>Đang Tải Giao Diện: {{ selectedTemplate.badge }}</span>
            </div>
          </div>

          <!-- FULLY SCROLLABLE REAL TEMPLATE COMPONENT CONTAINER -->
          <div 
            class="mx-auto transition-all duration-300 rounded-3xl border-4 border-slate-900 bg-white shadow-2xl overflow-hidden"
            :class="previewMode === 'mobile' ? 'max-w-[420px]' : 'w-full'"
          >
            <!-- Phone Notch Top Bar Header -->
            <div class="bg-slate-900 text-white px-4 py-2 flex items-center justify-between text-[10px] font-mono select-none">
              <span>9:41 AM</span>
              <div class="w-16 h-3 rounded-full bg-slate-800 border border-slate-700"></div>
              <span>100% 🔋</span>
            </div>

            <!-- SCROLLABLE CONTAINER rendering REAL TEMPLATE COMPONENT -->
            <div class="max-h-[680px] overflow-y-auto scroll-smooth">
              <component :is="activeTemplateComponent" :wishes="[]" :memories="[]" />
            </div>
          </div>

        </div>

      </div>
    </div>
  </WorkspaceLayout>
</template>
