<script setup lang="ts">
import { ref, watch } from 'vue';
import { 
  X, 
  MapPin, 
  Star, 
  Phone, 
  Mail, 
  DollarSign, 
  Check, 
  Calendar, 
  Sparkles, 
  Building2, 
  ShieldCheck, 
  ExternalLink,
  Plus,
  CreditCard,
  Image as ImageIcon
} from 'lucide-vue-next';
import { MapVendorItem } from './VendorMap.vue';

const props = defineProps<{
  vendor: MapVendorItem | null;
  isOpen: boolean;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
  (e: 'book-vendor', vendor: MapVendorItem): void;
  (e: 'record-payment', vendor: MapVendorItem): void;
  (e: 'fly-to-map', vendor: MapVendorItem): void;
}>();

const activeImageIndex = ref(0);

watch(
  () => props.vendor,
  () => {
    activeImageIndex.value = 0;
  }
);

const formatVnd = (num?: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num || 0);
};

const getCategoryLabel = (cat?: string) => {
  const map: Record<string, string> = {
    venue: 'Sảnh tiệc & Nhà hàng',
    studio: 'Chụp ảnh & Quay phim',
    catering: 'Ẩm thực & Đồ uống',
    makeup: 'Trang điểm & Làm tóc',
    florist: 'Hoa tươi & Decor',
    attire: 'Váy cưới & Suit',
    other: 'Khác',
  };
  return map[cat || ''] || cat || 'Dịch vụ Cưới';
};
</script>

<template>
  <Teleport to="body">
    <!-- Backdrop Overlay -->
    <div
      v-if="isOpen && vendor"
      @click="emit('close')"
      class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300"
    ></div>

    <!-- Slide-over Drawer Panel -->
    <div
      v-if="vendor"
      class="fixed top-0 right-0 bottom-0 z-50 w-full max-w-lg bg-white shadow-2xl border-l border-slate-200 transform transition-transform duration-300 overflow-y-auto flex flex-col justify-between"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >
      <!-- Header -->
      <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-md z-10">
        <div class="flex items-center gap-2">
          <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-200">
            {{ getCategoryLabel(vendor.category) }}
          </span>
          <span v-if="vendor.is_booked" class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-900 text-amber-300 border border-amber-400">
            ✓ Đã Chốt Hợp Đồng
          </span>
        </div>
        <button
          @click="emit('close')"
          class="p-2 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
        >
          <X class="w-5 h-5" />
        </button>
      </div>

      <!-- Main Body Content -->
      <div class="p-6 space-y-6 flex-1">
        <!-- Title & Rating -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
              {{ vendor.vibe_label || 'Pastel Romantic & Luxury' }}
            </span>
            <div v-if="vendor.rating" class="flex items-center gap-1 text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
              <Star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" /> {{ vendor.rating }}
            </div>
          </div>
          <h2 class="text-2xl font-serif font-bold text-slate-900 leading-tight">
            {{ vendor.name }}
          </h2>
          <p class="text-xs text-slate-600 flex items-center gap-1.5">
            <MapPin class="w-4 h-4 text-rose-500 shrink-0" /> {{ vendor.address || `${vendor.district || ''}, ${vendor.city || ''}` }}
          </p>
        </div>

        <!-- Portfolio Image Gallery Carousel -->
        <div v-if="vendor.portfolio_images && vendor.portfolio_images.length > 0" class="space-y-3">
          <div class="relative h-64 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shadow-xs">
            <img
              :src="vendor.portfolio_images[activeImageIndex]"
              :alt="vendor.name"
              class="w-full h-full object-cover transition-all duration-300"
            />
            <div v-if="vendor.match_score" class="absolute top-3 right-3 px-3 py-1 rounded-full bg-rose-950/90 text-white text-xs font-extrabold border border-rose-400/40 shadow-md">
              🔥 {{ vendor.match_score }}% Match
            </div>
          </div>

          <!-- Thumbnail Selector Strip -->
          <div v-if="vendor.portfolio_images.length > 1" class="flex gap-2 overflow-x-auto pb-1">
            <button
              v-for="(img, idx) in vendor.portfolio_images"
              :key="idx"
              @click="activeImageIndex = idx"
              class="w-16 h-16 rounded-xl overflow-hidden border-2 transition shrink-0 cursor-pointer"
              :class="activeImageIndex === idx ? 'border-rose-600 scale-105 shadow-sm' : 'border-transparent opacity-70 hover:opacity-100'"
            >
              <img :src="img" :alt="vendor.name" class="w-full h-full object-cover" />
            </button>
          </div>
        </div>

        <!-- Price Range & Capacity Details -->
        <div class="grid grid-cols-2 gap-4 p-4 rounded-2xl bg-rose-50/60 border border-rose-100 text-xs">
          <div>
            <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold block">KHOẢNG GIÁ DỰ KIẾN</span>
            <span class="font-extrabold text-rose-950 text-sm block mt-0.5">{{ vendor.price_label || 'Theo báo giá thỏa thuận' }}</span>
          </div>
          <div>
            <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold block">SUẤT PHỤC VỤ</span>
            <span class="font-bold text-slate-900 text-xs block mt-0.5">{{ vendor.capacity_text || 'Đặt lịch theo tiệc' }}</span>
          </div>
        </div>

        <!-- Service Highlights -->
        <div v-if="vendor.highlights && vendor.highlights.length > 0" class="space-y-2">
          <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">ĐIỂM NỔI BẬT DỊCH VỤ:</h4>
          <div class="flex flex-wrap gap-2">
            <span v-for="(hl, idx) in vendor.highlights" :key="idx" class="px-3 py-1 rounded-xl bg-slate-100 text-slate-800 text-xs font-medium border border-slate-200">
              ✓ {{ hl }}
            </span>
          </div>
        </div>

        <!-- Booked Contract Financial Status -->
        <div v-if="vendor.is_booked" class="p-5 rounded-2xl bg-slate-900 text-white space-y-3 border border-slate-800">
          <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <span class="text-xs font-bold text-amber-300 uppercase tracking-wider">HỢP ĐỒNG ĐÃ KÝ KẾT</span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold border border-emerald-500/30">
              Active
            </span>
          </div>

          <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="p-2 rounded-xl bg-slate-800/80">
              <span class="text-[10px] text-slate-400 block">Giá trị HĐ</span>
              <span class="font-bold text-white text-xs">{{ formatVnd(vendor.contract_amount) }}</span>
            </div>
            <div class="p-2 rounded-xl bg-slate-800/80">
              <span class="text-[10px] text-slate-400 block">Đã cọc/trả</span>
              <span class="font-bold text-emerald-400 text-xs">{{ formatVnd(vendor.paid_amount) }}</span>
            </div>
            <div class="p-2 rounded-xl bg-slate-800/80">
              <span class="text-[10px] text-slate-400 block">Nợ còn lại</span>
              <span class="font-bold text-rose-400 text-xs">{{ formatVnd((vendor.contract_amount || 0) - (vendor.paid_amount || 0)) }}</span>
            </div>
          </div>
        </div>

        <!-- Contact Information -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-3">
          <h4 class="font-bold text-slate-900 uppercase tracking-wider">THÔNG TIN LIÊN HỆ ĐỐI TÁC</h4>
          <div class="space-y-2">
            <div v-if="vendor.contact_name" class="flex items-center gap-2 text-slate-700">
              <Building2 class="w-4 h-4 text-slate-400 shrink-0" />
              <span>Đại diện: <strong>{{ vendor.contact_name }}</strong></span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2 text-slate-700">
                <Phone class="w-4 h-4 text-rose-500 shrink-0" />
                <span>{{ vendor.phone || 'Chưa cập nhật SĐT' }}</span>
              </div>
              <a v-if="vendor.phone" :href="`tel:${vendor.phone}`" class="px-3 py-1 rounded-lg bg-rose-600 text-white font-bold text-[11px] hover:bg-rose-500 transition">
                Gọi ngay
              </a>
            </div>
            <div v-if="vendor.email" class="flex items-center gap-2 text-slate-700">
              <Mail class="w-4 h-4 text-slate-400 shrink-0" />
              <span class="truncate">{{ vendor.email }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Footer -->
      <div class="p-6 border-t border-slate-100 bg-white space-y-3 sticky bottom-0 z-10">
        <div class="flex gap-3">
          <button
            @click="emit('fly-to-map', vendor)"
            class="flex-1 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition flex items-center justify-center gap-2 cursor-pointer"
          >
            <MapPin class="w-4 h-4 text-rose-600" /> Định vị Bản đồ
          </button>

          <button
            v-if="!vendor.is_booked"
            @click="emit('book-vendor', vendor)"
            class="flex-1 py-3 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition flex items-center justify-center gap-1.5 cursor-pointer"
          >
            <Plus class="w-4 h-4" /> + Chốt Hợp Đồng
          </button>

          <button
            v-else-if="((vendor.contract_amount || 0) - (vendor.paid_amount || 0)) > 0"
            @click="emit('record-payment', vendor)"
            class="flex-1 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-1.5 cursor-pointer"
          >
            <CreditCard class="w-4 h-4" /> + Ghi nhận Trả thêm
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>
