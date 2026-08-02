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
  Clock,
  Send,
  MessageSquare,
  Check,
  Zap,
  Layers,
  ChevronDown
} from 'lucide-vue-next';

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

const activeTab = ref<'templates' | 'details' | 'design' | 'features'>('templates');
const previewMode = ref<'mobile' | 'desktop'>('mobile');
const isSaved = ref(false);
const isSaving = ref(false);
const saveMessage = ref('');
const isWaxSealOpened = ref(false);

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
  return `/wedding/invitation/nguyen-van-anh`;
});
</script>

<template>
  <WorkspaceLayout title="Tùy Biến Thiệp Cưới" active-nav="invitation_editor">
    <div class="space-y-6 font-sans max-w-7xl mx-auto px-2 md:px-6 py-6">
      
      <!-- Top Action Bar -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-rose-100 pb-4">
        <div>
          <h1 class="text-2xl font-serif font-extrabold text-slate-900 flex items-center gap-2">
            <Sparkles class="w-6 h-6 text-[#881337]" />
            CMS Tùy Biến Thiệp Cưới Live Customizer
          </h1>
          <p class="text-xs text-slate-500">Tùy chỉnh 10 giao diện thiệp cưới, màu sắc, thông tin tiệc và hộp mừng cưới thời gian thực</p>
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
        <button @click="isSaved = false" class="text-xs underline font-bold">Đóng</button>
      </div>

      <!-- Main Workspace 2-Column Grid -->
      <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: CMS Control Controls & Tabs -->
        <div class="md:col-span-5 space-y-6">
          
          <!-- Sub-Tab Navigation Bar -->
          <div class="flex items-center gap-1 p-1.5 rounded-2xl bg-slate-100 border border-slate-200/80 text-xs font-bold">
            <button 
              @click="activeTab = 'templates'"
              class="flex-1 py-2 rounded-xl transition cursor-pointer"
              :class="activeTab === 'templates' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Mẫu Thiệp
            </button>
            <button 
              @click="activeTab = 'details'"
              class="flex-1 py-2 rounded-xl transition cursor-pointer"
              :class="activeTab === 'details' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Lễ Cưới
            </button>
            <button 
              @click="activeTab = 'design'"
              class="flex-1 py-2 rounded-xl transition cursor-pointer"
              :class="activeTab === 'design' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Giao Diện
            </button>
            <button 
              @click="activeTab = 'features'"
              class="flex-1 py-2 rounded-xl transition cursor-pointer"
              :class="activeTab === 'features' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Tính Năng
            </button>
          </div>

          <!-- TAB 1: TEMPLATES CATALOG -->
          <div v-if="activeTab === 'templates'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <div class="flex items-center justify-between">
              <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <Layers class="w-4 h-4 text-[#881337]" />
                Kho 10 Mẫu Thiệp Mời Cưới Độc Bản
              </h2>
              <span class="text-[10px] text-slate-400 font-mono">Click để chọn</span>
            </div>

            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">
              <div 
                v-for="tpl in templateCatalog" 
                :key="tpl.id"
                @click="form.template_id = tpl.id"
                class="p-4 rounded-2xl border transition-all cursor-pointer flex items-center justify-between"
                :class="form.template_id === tpl.id ? 'border-[#881337] bg-rose-50/50 shadow-md ring-2 ring-rose-200' : 'border-slate-200 hover:border-rose-200 bg-white'"
              >
                <div class="space-y-1">
                  <div class="flex items-center gap-2">
                    <h3 class="font-bold text-xs text-slate-900">{{ tpl.name }}</h3>
                    <span :class="['px-2 py-0.5 rounded-full text-[9px] font-bold border', tpl.badgeColor]">{{ tpl.badge }}</span>
                  </div>
                  <p class="text-[11px] text-slate-500 font-medium">{{ tpl.desc }}</p>
                </div>
                <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0" :class="form.template_id === tpl.id ? 'border-[#881337] bg-[#881337] text-white' : 'border-slate-300'">
                  <Check v-if="form.template_id === tpl.id" class="w-3.5 h-3.5 stroke-[3]" />
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 2: WEDDING DETAILS FORM -->
          <div v-if="activeTab === 'details'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Calendar class="w-4 h-4 text-[#881337]" />
              Thông Tin Lễ Cưới & Địa Điểm Tiệc
            </h2>

            <div class="space-y-3 text-xs">
              <div>
                <label class="font-bold text-slate-700 block mb-1">Tên Tiêu Đề Lễ</label>
                <input v-model="form.custom_title" type="text" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Tên Chú Rể</label>
                  <input v-model="form.groom_name" type="text" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Tên Cô Dâu</label>
                  <input v-model="form.bride_name" type="text" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Đơn Vị Nhà Trai</label>
                  <input v-model="form.groom_parents" type="text" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:border-[#881337] outline-none" />
                </div>
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Đơn Vị Nhà Gái</label>
                  <input v-model="form.bride_parents" type="text" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:border-[#881337] outline-none" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Ngày Tổ Chức</label>
                  <input v-model="form.wedding_date" type="date" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
                <div>
                  <label class="font-bold text-slate-700 block mb-1">Giờ Tổ Chức Tiệc</label>
                  <input v-model="form.event_time" type="text" placeholder="11:30 Sáng" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
                </div>
              </div>

              <div>
                <label class="font-bold text-slate-700 block mb-1">Tên Tỉnh / Thành Phố</label>
                <input v-model="form.wedding_location" type="text" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-bold text-slate-700 block mb-1">Trung Tâm / Địa Điểm Tiệc</label>
                <input v-model="form.venue_name" type="text" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-bold text-slate-700 block mb-1">Link Google Maps Chỉ Đường</label>
                <input v-model="form.google_maps_url" type="url" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-[11px] text-slate-900 focus:border-[#881337] outline-none" />
              </div>
            </div>
          </div>

          <!-- TAB 3: DESIGN & AUDIO -->
          <div v-if="activeTab === 'design'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-5">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Palette class="w-4 h-4 text-[#881337]" />
              Tông Màu Chủ Đạo & Font Chữ
            </h2>

            <div>
              <label class="text-xs font-bold text-slate-700 block mb-2">Chọn Bảng Màu Preset</label>
              <div class="grid grid-cols-4 gap-2">
                <button
                  v-for="color in colorPresets"
                  :key="color.hex"
                  type="button"
                  @click="form.primary_color = color.hex"
                  class="p-2 rounded-xl border text-center transition cursor-pointer flex flex-col items-center gap-1"
                  :class="form.primary_color === color.hex ? 'border-slate-900 ring-2 ring-rose-300' : 'border-slate-200 hover:border-rose-200'"
                >
                  <span class="w-6 h-6 rounded-full border shadow-2xs" :style="{ backgroundColor: color.hex }"></span>
                  <span class="text-[9px] font-bold truncate w-full text-slate-700">{{ color.name }}</span>
                </button>
              </div>
            </div>

            <div>
              <label class="text-xs font-bold text-slate-700 block mb-1">Font Chữ Chủ Đạo</label>
              <select v-model="form.font_family" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:border-[#881337] outline-none">
                <option v-for="font in fontPresets" :key="font.name" :value="font.name">{{ font.name }}</option>
              </select>
            </div>

            <div>
              <label class="text-xs font-bold text-slate-700 flex items-center gap-1 mb-1">
                <Music class="w-3.5 h-3.5 text-[#881337]" />
                Link Nhạc Nền MP3 (Lãng mạn)
              </label>
              <input v-model="form.music_url" type="url" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-[11px] focus:border-[#881337] outline-none" />
            </div>
          </div>

          <!-- TAB 4: INTERACTIVE FEATURES & GIFT BOX -->
          <div v-if="activeTab === 'features'" class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Gift class="w-4 h-4 text-[#881337]" />
              Tính Năng Tương Tác & Hộp Mừng Cưới
            </h2>

            <div class="space-y-3 p-3 bg-slate-50 rounded-2xl border border-slate-200 text-xs">
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

            <div v-if="form.enable_gift_box" class="space-y-3 pt-2 text-xs">
              <div class="font-bold text-slate-900 flex items-center gap-1.5">
                <ShieldCheck class="w-4 h-4 text-emerald-600" />
                Thông Tin Tài Khoản Mừng Cưới Chú Rể / Cô Dâu
              </div>

              <div>
                <label class="font-semibold text-slate-700 block mb-1">Tên Ngân Hàng</label>
                <input v-model="form.bank_name" type="text" placeholder="Vietcombank, MB Bank..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-semibold text-slate-700 block mb-1">Số Tài Khoản Chuyển Khoản</label>
                <input v-model="form.bank_account_number" type="text" placeholder="1029384756" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>

              <div>
                <label class="font-semibold text-slate-700 block mb-1">Tên Chủ Tài Khoản</label>
                <input v-model="form.bank_account_holder" type="text" placeholder="NGUYEN HOANG QUOC TRUNG" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl uppercase font-bold text-slate-900 focus:border-[#881337] outline-none" />
              </div>
            </div>
          </div>

        </div>

        <!-- Right Column: Interactive Scrollable Mobile/Desktop Live Preview Canvas -->
        <div class="md:col-span-7 sticky top-6 space-y-3">
          
          <!-- Viewport Device Switcher Bar -->
          <div class="p-3 bg-white rounded-2xl border border-slate-200 shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-slate-700">Chế độ xem:</span>
              <button 
                @click="previewMode = 'mobile'" 
                class="px-3 py-1.5 rounded-xl font-bold text-xs transition cursor-pointer flex items-center gap-1.5"
                :class="previewMode === 'mobile' ? 'bg-[#881337] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
              >
                <Smartphone class="w-3.5 h-3.5" /> Mobile
              </button>
              <button 
                @click="previewMode = 'desktop'" 
                class="px-3 py-1.5 rounded-xl font-bold text-xs transition cursor-pointer flex items-center gap-1.5"
                :class="previewMode === 'desktop' ? 'bg-[#881337] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
              >
                <Monitor class="w-3.5 h-3.5" /> Desktop
              </button>
            </div>

            <div class="text-[11px] font-bold text-[#881337] flex items-center gap-1">
              <Sparkles class="w-3.5 h-3.5 text-amber-500 animate-spin" />
              <span>Adapt Mẫu: {{ selectedTemplate.badge }}</span>
            </div>
          </div>

          <!-- Fully Scrollable Live Invitation Viewport Frame -->
          <div 
            class="mx-auto transition-all duration-300 rounded-3xl border-4 border-slate-900 bg-white shadow-2xl overflow-hidden"
            :class="previewMode === 'mobile' ? 'max-w-[400px]' : 'w-full'"
          >
            <!-- Phone Notch Top Bar Header -->
            <div class="bg-slate-900 text-white px-4 py-2 flex items-center justify-between text-[10px] font-mono select-none">
              <span>9:41 AM</span>
              <div class="w-16 h-3 rounded-full bg-slate-800 border border-slate-700"></div>
              <span>100% 🔋</span>
            </div>

            <!-- SCROLLABLE INVITATION CONTAINER (Kéo xuống như thiệp thật) -->
            <div class="max-h-[680px] overflow-y-auto space-y-0 text-center font-sans scroll-smooth">
              
              <!-- SECTION 1: WAX SEAL & HERO COVER ENVELOPE -->
              <div 
                class="min-h-[500px] p-8 flex flex-col items-center justify-center space-y-5 transition-all duration-500 relative"
                :class="{
                  'bg-gradient-to-b from-rose-50 to-white text-slate-900': form.template_id === 'romantic-pastel',
                  'bg-gradient-to-b from-[#FFFDF9] to-[#FEF3C7] text-amber-950 border-4 border-amber-300': form.template_id === 'royal-gold',
                  'bg-slate-950 text-white': form.template_id === 'modern-slate',
                  'bg-gradient-to-b from-[#F0FDF4] to-[#DCFCE7] text-emerald-950': form.template_id === 'botanical-sage',
                  'bg-gradient-to-b from-[#FEF2F2] via-[#991B1B] to-[#7F1D1D] text-amber-100': form.template_id === 'indochine-traditional',
                  'bg-gradient-to-b from-[#F0F9FF] to-[#E0F2FE] text-sky-950': form.template_id === 'celestial-blue',
                  'bg-gradient-to-b from-[#064E3B] to-[#022C22] text-amber-200': form.template_id === 'emerald-luxe',
                  'bg-gradient-to-b from-[#FFF7ED] to-[#FFEDD5] text-orange-950': form.template_id === 'sunset-coral',
                  'bg-gradient-to-b from-[#1C1917] to-[#0C0A09] text-stone-100 font-serif': form.template_id === 'crimson-velvet',
                  'bg-gradient-to-b from-[#FEF3C7] to-[#FDE68A] text-amber-950 font-serif': form.template_id === 'vintage-sepia'
                }"
              >
                <!-- Wax Seal Toggle Button -->
                <button 
                  v-if="form.enable_wax_seal"
                  @click="isWaxSealOpened = !isWaxSealOpened"
                  class="w-16 h-16 rounded-full border-2 border-white/60 shadow-xl flex items-center justify-center font-serif font-extrabold text-[11px] text-white cursor-pointer transition transform hover:scale-105 active:scale-95 animate-pulse"
                  :style="{ backgroundColor: form.primary_color }"
                >
                  {{ isWaxSealOpened ? 'ĐÃ MỞ' : 'MỞ THIỆP' }}
                </button>

                <div class="space-y-2 max-w-xs mx-auto">
                  <span class="text-xs font-extrabold tracking-widest uppercase block" :style="{ color: form.primary_color }">
                    {{ form.custom_title || 'THIỆP MỜI ĐÁM CƯỚI' }}
                  </span>

                  <div class="text-xs opacity-80 font-serif italic">
                    {{ form.groom_parents }} & {{ form.bride_parents }}
                  </div>

                  <h2 class="text-3xl font-serif font-extrabold tracking-tight py-3">
                    {{ form.groom_name }} <br />
                    <span class="text-sm font-sans font-light opacity-60">&</span> <br />
                    {{ form.bride_name }}
                  </h2>
                </div>

                <div class="p-4 rounded-2xl bg-white/80 backdrop-blur-sm border border-slate-200/60 shadow-xs max-w-xs text-xs space-y-1 text-slate-800 font-medium">
                  <div>📅 Ngày: <strong>{{ form.wedding_date }}</strong> ({{ form.event_time }})</div>
                  <div class="text-[11px] text-slate-600">📍 Địa điểm: {{ form.venue_name }}, {{ form.wedding_location }}</div>
                </div>

                <div class="pt-4 text-xs font-mono opacity-60 animate-bounce">
                  Scroll xuống xem chi tiết ↓
                </div>
              </div>

              <!-- SECTION 2: COUNTDOWN TIMER & LOVE STORY -->
              <div class="p-8 bg-white border-t border-slate-100 space-y-4">
                <h3 class="font-serif font-extrabold text-slate-900 text-lg flex items-center justify-center gap-2">
                  <Clock class="w-5 h-5 text-[#881337]" />
                  Đếm Ngược Ngày Trọng Đại
                </h3>

                <div class="grid grid-cols-4 gap-2 max-w-xs mx-auto text-center font-mono">
                  <div class="p-3 rounded-2xl bg-rose-50 border border-rose-100 text-rose-950">
                    <div class="text-xl font-extrabold">82</div>
                    <div class="text-[9px] text-slate-500 font-sans">NÀY</div>
                  </div>
                  <div class="p-3 rounded-2xl bg-rose-50 border border-rose-100 text-rose-950">
                    <div class="text-xl font-extrabold">14</div>
                    <div class="text-[9px] text-slate-500 font-sans">GIỜ</div>
                  </div>
                  <div class="p-3 rounded-2xl bg-rose-50 border border-rose-100 text-rose-950">
                    <div class="text-xl font-extrabold">30</div>
                    <div class="text-[9px] text-slate-500 font-sans">PHÚT</div>
                  </div>
                  <div class="p-3 rounded-2xl bg-rose-50 border border-rose-100 text-rose-950">
                    <div class="text-xl font-extrabold">45</div>
                    <div class="text-[9px] text-slate-500 font-sans">GIÂY</div>
                  </div>
                </div>
              </div>

              <!-- SECTION 3: CEREMONY & RECEPTION LOCATION -->
              <div class="p-8 bg-slate-50 border-t border-slate-200/80 space-y-4 text-left">
                <h3 class="font-serif font-extrabold text-slate-900 text-base text-center">Lịch Trình Tiệc Cưới</h3>
                
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-2 text-xs">
                  <div class="font-bold text-[#881337]">💒 Lễ Gia Tiên (Tư Gia)</div>
                  <div class="text-slate-600">Thời gian: 09:00 Sáng</div>
                  <div class="text-slate-600">Địa chỉ: Tư gia Nhà Trai & Nhà Gái</div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-2 text-xs">
                  <div class="font-bold text-[#881337]">🍷 Tiệc Cưới Đón Khách</div>
                  <div class="text-slate-600">Thời gian: {{ form.event_time }}</div>
                  <div class="text-slate-900 font-bold">{{ form.venue_name }}</div>
                  <div class="text-slate-600">{{ form.wedding_location }}</div>

                  <a 
                    :href="form.google_maps_url" 
                    target="_blank" 
                    class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-[11px] shadow-2xs hover:bg-slate-800 transition"
                  >
                    <MapPin class="w-3.5 h-3.5 text-rose-400" /> Xem Google Maps
                  </a>
                </div>
              </div>

              <!-- SECTION 4: HỘP MỪNG CƯỚI & CHUYỂN KHOẢN QR -->
              <div v-if="form.enable_gift_box" class="p-8 bg-white border-t border-slate-100 space-y-4 text-left">
                <h3 class="font-serif font-extrabold text-slate-900 text-base text-center flex items-center justify-center gap-2">
                  <Gift class="w-5 h-5 text-rose-600" />
                  Hộp Mừng Cưới Chuyển Khoản
                </h3>

                <div class="p-5 rounded-3xl bg-gradient-to-r from-rose-50 to-amber-50 border border-rose-200/80 shadow-xs space-y-3 text-xs">
                  <div class="flex items-center justify-between border-b border-rose-200/60 pb-2">
                    <span class="font-bold text-slate-800">Ngân Hàng Mừng Cưới</span>
                    <span class="font-extrabold text-[#881337]">{{ form.bank_name }}</span>
                  </div>

                  <div class="flex items-center justify-between border-b border-rose-200/60 pb-2">
                    <span class="font-bold text-slate-800">Số Tài Khoản</span>
                    <span class="font-mono font-extrabold text-slate-900 text-sm">{{ form.bank_account_number }}</span>
                  </div>

                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">Chủ Tài Khoản</span>
                    <span class="font-extrabold text-slate-900 uppercase">{{ form.bank_account_holder }}</span>
                  </div>
                </div>

                <div v-if="form.enable_qr_checkin" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                  <div class="w-32 h-32 mx-auto bg-white border-2 border-slate-300 rounded-2xl flex items-center justify-center text-xs font-mono text-slate-400">
                    [ Mã VietQR ]
                  </div>
                  <span class="text-[11px] text-slate-500 font-medium">Quét mã QR để mừng cưới trực tiếp</span>
                </div>
              </div>

              <!-- SECTION 5: CONFIRMATION FORM -->
              <div class="p-8 bg-slate-50 border-t border-slate-200/80 space-y-4 text-left">
                <h3 class="font-serif font-extrabold text-slate-900 text-base text-center">Xác Nhận Tham Dự</h3>
                
                <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-2xs space-y-3 text-xs">
                  <div>
                    <label class="font-bold text-slate-700 block mb-1">Họ và tên của bạn</label>
                    <input type="text" placeholder="Nhập tên..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs" />
                  </div>

                  <div>
                    <label class="font-bold text-slate-700 block mb-1">Số điện thoại</label>
                    <input type="tel" placeholder="Nhập SĐT..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs" />
                  </div>

                  <div class="space-y-1">
                    <label class="font-bold text-slate-700 block">Bạn sẽ tham dự chứ?</label>
                    <div class="flex items-center gap-3">
                      <label class="flex items-center gap-1 font-bold text-emerald-700">
                        <input type="radio" name="rsvp_preview" checked /> Có tham dự
                      </label>
                      <label class="flex items-center gap-1 text-slate-500 font-medium">
                        <input type="radio" name="rsvp_preview" /> Rất tiếc bận
                      </label>
                    </div>
                  </div>

                  <button type="button" class="w-full py-3 rounded-2xl bg-[#881337] text-white font-extrabold text-xs shadow-md">
                    Gửi Phản Hồi
                  </button>
                </div>
              </div>

              <!-- SECTION 6: WISHING BOARD -->
              <div class="p-8 bg-white border-t border-slate-100 space-y-4 text-left">
                <h3 class="font-serif font-extrabold text-slate-900 text-base text-center flex items-center justify-center gap-2">
                  <MessageSquare class="w-5 h-5 text-rose-600" />
                  Sổ Lời Chúc Đám Cưới
                </h3>

                <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 text-xs space-y-2">
                  <div class="font-bold text-slate-900">Bác Hai & Gia đình</div>
                  <p class="text-slate-600 italic">"Chúc hai cháu Quốc Trung & Hồng Vân trăm năm hạnh phúc, đầu bạc răng long!"</p>
                </div>
              </div>

            </div>
          </div>

        </div>

      </div>
    </div>
  </WorkspaceLayout>
</template>
