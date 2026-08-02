<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { Sparkles, Save, Type, Calendar, MapPin, Music, Palette, Gift, QrCode, Heart, CheckCircle2, ShieldCheck } from 'lucide-vue-next';

const props = defineProps<{
  workspace?: any;
  invitation?: any;
  templates?: any[];
}>();

const activeTab = ref<'templates' | 'details' | 'design' | 'features'>('templates');
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

const templateCatalog = [
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

const saveCms = async () => {
  isSaving.value = true;
  try {
    const response = await fetch('/wedding/invitation-editor/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
      },
      body: JSON.stringify(form.data()),
    });

    if (response.ok) {
      isSaved.value = true;
      saveMessage.value = 'Đã lưu cấu hình CMS Thiệp Cưới thành công!';
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
</script>

<template>
  <WorkspaceLayout>
    <Head title="Trình Thiết Kế & CMS Thiệp Cưới Online — Eloria OS" />

    <!-- Top Action Header -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-2xs">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
            <span>🎨 CMS Tự Chỉnh Sửa Thiệp Cưới Online</span>
            <span class="text-[10px] font-sans px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 font-semibold border border-rose-200">Live Customizer</span>
          </h1>
          <p class="text-xs text-slate-500 mt-0.5">Tùy biến 10 template, màu sắc, lời xưng hô, nhạc nền và hộp mừng cưới thời gian thực</p>
        </div>

        <div class="flex items-center gap-3">
          <span v-if="isSaved" class="text-xs font-bold text-emerald-600 flex items-center gap-1 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200">
            <CheckCircle2 class="w-4 h-4 text-emerald-600" />
            {{ saveMessage }}
          </span>

          <button 
            @click="saveCms"
            :disabled="isSaving"
            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-md transition flex items-center gap-2 cursor-pointer disabled:opacity-50"
          >
            <Save v-if="!isSaving" class="w-4 h-4" />
            <span v-else class="animate-spin text-xs">⏳</span>
            {{ isSaving ? 'Đang lưu CMS...' : 'Lưu Thay Đổi CMS' }}
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid md:grid-cols-12 gap-8">
        <!-- Left Side: 4-Tab CMS Controls Panel -->
        <div class="md:col-span-5 space-y-6">
          <!-- CMS Navigation Tabs -->
          <div class="p-1 bg-slate-100 rounded-2xl border border-slate-200 flex items-center gap-1 text-xs font-semibold">
            <button 
              @click="activeTab = 'templates'"
              class="flex-1 py-2 rounded-xl text-center transition cursor-pointer"
              :class="activeTab === 'templates' ? 'bg-white text-rose-950 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Mẫu Thiệp
            </button>
            <button 
              @click="activeTab = 'details'"
              class="flex-1 py-2 rounded-xl text-center transition cursor-pointer"
              :class="activeTab === 'details' ? 'bg-white text-rose-950 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Lễ Cưới
            </button>
            <button 
              @click="activeTab = 'design'"
              class="flex-1 py-2 rounded-xl text-center transition cursor-pointer"
              :class="activeTab === 'design' ? 'bg-white text-rose-950 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Giao Diện
            </button>
            <button 
              @click="activeTab = 'features'"
              class="flex-1 py-2 rounded-xl text-center transition cursor-pointer"
              :class="activeTab === 'features' ? 'bg-white text-rose-950 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Tính Năng
            </button>
          </div>

          <!-- TAB 1: TEMPLATES SELECTION -->
          <div v-if="activeTab === 'templates'" class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Sparkles class="w-4 h-4 text-rose-600" />
              Chọn Mẫu Thiệp Cưới Đột Phá (10 Templates)
            </h2>
            <div class="grid grid-cols-2 gap-2.5 max-h-[440px] overflow-y-auto pr-1">
              <button 
                v-for="tpl in templateCatalog"
                :key="tpl.id"
                @click="form.template_id = tpl.id"
                class="p-3 rounded-xl border text-left transition-all cursor-pointer"
                :class="form.template_id === tpl.id ? 'bg-rose-50 border-rose-400 font-bold text-rose-950 shadow-xs ring-1 ring-rose-400' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100'"
              >
                <div class="text-xs flex items-center justify-between">
                  <span>{{ tpl.name }}</span>
                  <span class="text-[9px] px-1.5 py-0.5 rounded font-semibold" :class="tpl.badgeColor">{{ tpl.badge }}</span>
                </div>
                <div class="text-[10px] text-slate-500 font-normal mt-1">{{ tpl.desc }}</div>
              </button>
            </div>
          </div>

          <!-- TAB 2: WEDDING EVENT DETAILS -->
          <div v-if="activeTab === 'details'" class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Type class="w-4 h-4 text-rose-600" />
              Thông Tin Dâu Rể & Lễ Cưới
            </h2>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs font-semibold text-slate-700">Tên Chú Rể</label>
                <input v-model="form.groom_name" type="text" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700">Tên Cô Dâu</label>
                <input v-model="form.bride_name" type="text" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs font-semibold text-slate-700">Đơn Vị Nhà Trai</label>
                <input v-model="form.groom_parents" type="text" placeholder="Ông... & Bà..." class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700">Đơn Vị Nhà Gái</label>
                <input v-model="form.bride_parents" type="text" placeholder="Ông... & Bà..." class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs font-semibold text-slate-700">Ngày Tổ Chức</label>
                <input v-model="form.wedding_date" type="date" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700">Giờ Tổ Chức</label>
                <input v-model="form.event_time" type="text" placeholder="11:30 Sáng" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
              </div>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-700">Tên Tỉnh / Thành Phố</label>
              <input v-model="form.wedding_location" type="text" placeholder="TP. Hồ Chí Minh" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-700">Trung Tâm / Địa Điểm Tiệc</label>
              <input v-model="form.venue_name" type="text" placeholder="Asiana Plaza..." class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-700">Link Bản Đồ Google Maps</label>
              <input v-model="form.google_maps_url" type="url" placeholder="https://maps.google.com/?q=..." class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400 font-mono text-[11px]" />
            </div>
          </div>

          <!-- TAB 3: DESIGN & AUDIO -->
          <div v-if="activeTab === 'design'" class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-5">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Palette class="w-4 h-4 text-rose-600" />
              Tông Màu Chủ Đạo & Font Chữ
            </h2>

            <div>
              <label class="text-xs font-semibold text-slate-700 block mb-2">Chọn Bảng Màu Preset</label>
              <div class="grid grid-cols-4 gap-2">
                <button
                  v-for="color in colorPresets"
                  :key="color.hex"
                  type="button"
                  @click="form.primary_color = color.hex"
                  class="p-2 rounded-xl border text-center transition cursor-pointer flex flex-col items-center gap-1"
                  :class="form.primary_color === color.hex ? 'border-slate-900 ring-2 ring-rose-400' : 'border-slate-200'"
                >
                  <span class="w-6 h-6 rounded-full border shadow-2xs" :style="{ backgroundColor: color.hex }"></span>
                  <span class="text-[9px] font-medium truncate w-full text-slate-600">{{ color.name }}</span>
                </button>
              </div>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-700">Font Chữ Chủ Đạo</label>
              <select v-model="form.font_family" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400">
                <option v-for="font in fontPresets" :key="font.name" :value="font.name">{{ font.name }}</option>
              </select>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-700 flex items-center gap-1">
                <Music class="w-3.5 h-3.5 text-rose-600" />
                Link Nhạc Nền MP3 (Lãng mạn)
              </label>
              <input v-model="form.music_url" type="url" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-[11px] focus:outline-hidden focus:border-rose-400" />
            </div>
          </div>

          <!-- TAB 4: INTERACTIVE FEATURES & GIFT BOX -->
          <div v-if="activeTab === 'features'" class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Gift class="w-4 h-4 text-rose-600" />
              Tính Năng Tương Tác & Hộp Mừng Cưới
            </h2>

            <div class="space-y-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
              <label class="flex items-center justify-between text-xs font-medium text-slate-700 cursor-pointer">
                <span>Bật hiệu ứng Mở Bì Thư Sáp Nến (Wax Seal)</span>
                <input type="checkbox" v-model="form.enable_wax_seal" class="accent-rose-600 w-4 h-4" />
              </label>

              <label class="flex items-center justify-between text-xs font-medium text-slate-700 cursor-pointer">
                <span>Mã QR Code Check-in độc bản cho khách</span>
                <input type="checkbox" v-model="form.enable_qr_checkin" class="accent-rose-600 w-4 h-4" />
              </label>

              <label class="flex items-center justify-between text-xs font-medium text-slate-700 cursor-pointer">
                <span>Bật Khung Hộp Mừng Cưới Chuyển Khoản QR</span>
                <input type="checkbox" v-model="form.enable_gift_box" class="accent-rose-600 w-4 h-4" />
              </label>
            </div>

            <div v-if="form.enable_gift_box" class="space-y-3 pt-2">
              <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                <ShieldCheck class="w-4 h-4 text-emerald-600" />
                Thông Tin Tài Khoản Mừng Cưới Chú Rể / Cô Dâu
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-700">Tên Ngân Hàng</label>
                <input v-model="form.bank_name" type="text" placeholder="Vietcombank, MB Bank..." class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-400" />
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-700">Số Tài Khoản Chuyển Khoản</label>
                <input v-model="form.bank_account_number" type="text" placeholder="1029384756" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:outline-hidden focus:border-rose-400" />
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-700">Tên Chủ Tài Khoản</label>
                <input v-model="form.bank_account_holder" type="text" placeholder="NGUYEN HOANG QUOC TRUNG" class="mt-1 w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs uppercase focus:outline-hidden focus:border-rose-400" />
              </div>
            </div>
          </div>
        </div>

        <!-- Right Side: Live Mockup Viewport -->
        <div class="md:col-span-7">
          <div class="p-8 rounded-3xl bg-[#FAF8F5] border border-rose-200/80 shadow-xs sticky top-24 text-center">
            <span class="text-[10px] font-bold uppercase tracking-widest text-rose-800 bg-rose-100 px-3.5 py-1 rounded-full border border-rose-200 inline-flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              TRÌNH MÔ PHỎNG THIỆP MỜI LIVE ({{ form.template_id.toUpperCase() }})
            </span>

            <div class="mt-6 max-w-md mx-auto p-8 rounded-3xl border shadow-md transition-all duration-500 space-y-4" 
                 :class="{
                   'bg-white border-rose-200 text-slate-900': form.template_id === 'romantic-pastel',
                   'bg-[#FFFDF9] border-amber-300 text-amber-950': form.template_id === 'royal-gold',
                   'bg-white border-slate-300 text-slate-900': form.template_id === 'modern-slate',
                   'bg-[#F0FDF4] border-emerald-300 text-emerald-950': form.template_id === 'botanical-sage',
                   'bg-[#FAF5FF] border-purple-300 text-purple-950': form.template_id === 'lavender-bliss',
                   'bg-[#F0F9FF] border-sky-300 text-sky-950': form.template_id === 'celestial-blue',
                   'bg-[#ECFDF5] border-emerald-600 text-emerald-950': form.template_id === 'emerald-luxe',
                   'bg-[#FFF7ED] border-orange-300 text-orange-950': form.template_id === 'sunset-coral',
                   'bg-[#FFF1F2] border-rose-500 text-rose-950': form.template_id === 'crimson-velvet',
                   'bg-[#FEF3C7] border-amber-500 text-amber-950 font-serif': form.template_id === 'vintage-sepia'
                 }">

              <div v-if="form.enable_wax_seal" 
                   class="w-14 h-14 mx-auto rounded-full border-2 flex items-center justify-center shadow-md font-serif font-bold text-[11px] text-white"
                   :style="{ backgroundColor: form.primary_color }">
                MỞ THIỆP
              </div>

              <div class="space-y-1">
                <span class="text-[11px] font-semibold tracking-widest uppercase block" :style="{ color: form.primary_color }">
                  {{ form.custom_title || 'THIỆP MỜI ĐÁM CƯỚI' }}
                </span>

                <div class="text-[11px] opacity-75 font-serif italic">
                  {{ form.groom_parents }} & {{ form.bride_parents }}
                </div>

                <h3 class="text-2xl md:text-3xl font-serif font-bold pt-2">
                  {{ form.groom_name }} <br />
                  <span class="text-xs font-sans font-light opacity-60">&</span> <br />
                  {{ form.bride_name }}
                </h3>
              </div>

              <div class="my-4 py-2 border-y border-slate-200/60 text-xs space-y-1">
                <div>Ngày: <strong>{{ form.wedding_date }}</strong> ({{ form.event_time }})</div>
                <div class="text-slate-600 font-medium">Địa điểm: {{ form.venue_name }}, {{ form.wedding_location }}</div>
              </div>

              <!-- Gift Box Preview -->
              <div v-if="form.enable_gift_box" class="p-3 bg-white/90 rounded-2xl border border-slate-200/80 shadow-2xs text-left space-y-1 text-xs">
                <div class="font-bold text-rose-950 flex items-center gap-1">
                  <Gift class="w-3.5 h-3.5 text-rose-600" />
                  Hộp Mừng Cưới (Bank Transfer)
                </div>
                <div class="text-[11px] text-slate-600">Ngân hàng: <strong>{{ form.bank_name }}</strong></div>
                <div class="text-[11px] text-slate-600 font-mono">STK: <strong>{{ form.bank_account_number }}</strong></div>
                <div class="text-[10px] text-slate-500 uppercase">Chủ TK: {{ form.bank_account_holder }}</div>
              </div>

              <div v-if="form.enable_qr_checkin" class="w-16 h-16 mx-auto bg-white rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center text-[10px] text-slate-400">
                [QR Code]
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </WorkspaceLayout>
</template>
