<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
  Sparkles, 
  Store, 
  MapPin, 
  Star, 
  Check, 
  Plus, 
  ExternalLink,
  DollarSign,
  Heart,
  Users,
  ShieldCheck,
  Building2,
  Palette
} from 'lucide-vue-next';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import OpenStreetMapVendorView from '@/Components/Wedding/OpenStreetMapVendorView.vue';

interface RecommendedVendor {
  id: string;
  name: string;
  category: string;
  category_name: string;
  vibe_category: string;
  vibe_label: string;
  city: string;
  district: string;
  price_tier: string;
  price_label: string;
  rating: number;
  capacity_text: string;
  contact_name: string;
  phone: string;
  email: string;
  portfolio_images: string[];
  highlights: string[];
  match_score: number;
}

interface VendorItem {
  id: string;
  name: string;
  category: string;
  vibe_category?: string;
  city?: string;
  district?: string;
  contact_name: string | null;
  phone: string | null;
  email: string | null;
  contract_amount: number;
  paid_amount: number;
  unpaid_balance: number;
  payment_status: 'unpaid' | 'partially_paid' | 'fully_paid';
  due_date: string | null;
  contract_file: string | null;
  notes: string | null;
}

interface VendorSummary {
  total_contracts: number;
  total_paid: number;
  remaining_unpaid: number;
  vendors_count: number;
  unpaid_vendors_count: number;
  upcoming_due_vendors: Array<any>;
}

const props = defineProps<{
  workspace: { id: string; name: string; groom_name?: string; bride_name?: string; budget_cap?: number; wedding_location?: string };
  vendors: VendorItem[];
  summary: VendorSummary;
  recommendations: RecommendedVendor[];
  selectedVibe: string;
  selectedLocation: string;
}>();

const selectedVibeState = ref(props.selectedVibe || 'pastel');
const selectedLocationState = ref(props.selectedLocation || props.workspace?.wedding_location || 'TP. Hồ Chí Minh');
const selectedCategoryFilter = ref('all');
const selectedCategoryTab = ref('all');
const viewMode = ref<'grid' | 'map'>('grid');
const activeMapVendor = ref<RecommendedVendor | null>(null);
const selectedVenueForHalls = ref<RecommendedVendor | null>(null);
const selectedVendorForMap = ref<RecommendedVendor | null>(props.recommendations[0] || null);

const selectVendorForMap = (vendor: RecommendedVendor) => {
  selectedVendorForMap.value = vendor;
};

const filteredRecommendations = computed(() => {
  if (selectedCategoryTab.value === 'all') return props.recommendations;
  return props.recommendations.filter(r => r.category === selectedCategoryTab.value);
});

// Map projection helpers for TP.HCM bounds
const getMapX = (lng?: number) => {
  if (!lng) return 50;
  const minLng = 106.65, maxLng = 106.75;
  const pct = ((lng - minLng) / (maxLng - minLng)) * 100;
  return Math.min(88, Math.max(12, pct));
};

const getMapY = (lat?: number) => {
  if (!lat) return 50;
  const minLat = 10.70, maxLat = 10.82;
  const pct = 100 - (((lat - minLat) / (maxLat - minLat)) * 100);
  return Math.min(88, Math.max(12, pct));
};

const filterRecommendations = (vibe: string) => {
  selectedVibeState.value = vibe;
  router.get('/wedding/vendors', { vibe, location: selectedLocationState.value }, { preserveState: true, preserveScroll: true });
};

const getVendorCategoryIcon = (cat: string) => {
  const map: Record<string, string> = {
    venue: '🏛️',
    studio: '📸',
    photography: '📸',
    bridal: '👗',
    attire: '👗',
    florist: '💐',
    decor: '💐',
    makeup: '💄',
    catering: '🍷',
  };
  return map[cat] || '📍';
};

const vendorsList = ref<VendorItem[]>([...props.vendors]);
const summaryData = ref<VendorSummary>({ ...props.summary });

// Modals state
const isAddModalOpen = ref(false);
const isPaymentModalOpen = ref(false);
const selectedVendorForPayment = ref<VendorItem | null>(null);

const newVendorForm = ref({
  name: '',
  category: 'venue',
  contact_name: '',
  phone: '',
  email: '',
  contract_amount: 0,
  paid_amount: 0,
  due_date: '',
  notes: '',
});

const paymentAmountInput = ref<number>(0);
const isSubmitting = ref(false);

const formatVnd = (num: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num || 0);
};

const getCategoryLabel = (cat: string) => {
  const map: Record<string, string> = {
    venue: 'Sảnh tiệc & Nhà hàng',
    studio: 'Chụp ảnh & Quay phim',
    catering: 'Ẩm thực & Đồ uống',
    makeup: 'Trang điểm & Làm tóc',
    florist: 'Hoa tươi & Decor',
    attire: 'Váy cưới & Suit',
    other: 'Khác',
  };
  return map[cat] || cat;
};

const openPaymentModal = (vendor: VendorItem) => {
  selectedVendorForPayment.value = vendor;
  paymentAmountInput.value = Math.min(10000000, vendor.unpaid_balance);
  isPaymentModalOpen.value = true;
};

const handleRecordPayment = async () => {
  if (!selectedVendorForPayment.value || paymentAmountInput.value <= 0) return;
  isSubmitting.value = true;

  try {
    const response = await fetch(`/wedding/vendors/${selectedVendorForPayment.value.id}/payment`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      },
      body: JSON.stringify({ amount: paymentAmountInput.value })
    });
    const data = await response.json();
    if (data.success || response.ok) {
      selectedVendorForPayment.value.paid_amount += paymentAmountInput.value;
      selectedVendorForPayment.value.unpaid_balance = Math.max(0, selectedVendorForPayment.value.contract_amount - selectedVendorForPayment.value.paid_amount);
      if (selectedVendorForPayment.value.unpaid_balance === 0) {
        selectedVendorForPayment.value.payment_status = 'fully_paid';
      } else {
        selectedVendorForPayment.value.payment_status = 'partially_paid';
      }
      isPaymentModalOpen.value = false;
    }
  } catch (e) {
    console.error('Error recording vendor payment:', e);
  } finally {
    isSubmitting.value = false;
  }
};

const handleCreateVendor = async () => {
  if (!newVendorForm.value.name) return;
  isSubmitting.value = true;

  try {
    const response = await fetch('/wedding/vendors', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      },
      body: JSON.stringify(newVendorForm.value)
    });
    const data = await response.json();
    if (data.success && data.vendor) {
      vendorsList.value.unshift(data.vendor);
      isAddModalOpen.value = false;
    } else {
      router.reload({ preserveScroll: true });
      isAddModalOpen.value = false;
    }
  } catch (e) {
    console.error('Error creating vendor:', e);
  } finally {
    isSubmitting.value = false;
  }
};

const bookRecommendedVendor = (rec: RecommendedVendor) => {
  newVendorForm.value = {
    name: rec.name,
    category: rec.category,
    contact_name: rec.contact_name,
    phone: rec.phone,
    email: rec.email,
    contract_amount: rec.price_tier === 'luxury' ? 300000000 : (rec.price_tier === 'premium' ? 150000000 : 50000000),
    paid_amount: 20000000,
    due_date: '2026-09-01',
    notes: `Chốt từ Smart Matchmaker Engine (${rec.match_score}% Match • Vibe: ${rec.vibe_label})`,
  };
  isAddModalOpen.value = true;
};
</script>

<template>
  <WorkspaceLayout title="Smart Vendor Matchmaker & CRM" active-nav="vendors">
    <main class="max-w-7xl mx-auto px-6 py-8 space-y-10">
      <!-- Matchmaker Header & Criteria Selector -->
      <div class="p-8 rounded-3xl bg-gradient-to-r from-rose-100/90 via-amber-50/80 to-pink-100/90 border border-white/80 shadow-lg shadow-rose-900/5 backdrop-blur-md space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-rose-200/60 pb-6">
          <div class="space-y-1">
            <span class="px-3.5 py-1 rounded-full bg-rose-200/70 text-rose-950 text-[11px] font-bold uppercase tracking-widest border border-rose-300/50">
              SMART MATCHMAKER ENGINE • AI ALGORITHM
            </span>
            <h1 class="text-2xl md:text-3xl font-serif font-bold text-rose-950">
              Đề Xuất Đối Tác Chuẩn Vibe & Vị Trí Dâu Rể
            </h1>
            <p class="text-xs md:text-sm text-rose-900/90 leading-relaxed font-medium">
              Tự động tính toán điểm <strong>% Match Score</strong> dựa trên phong cách yêu thích, ngân sách và khu vực của cặp đôi 
              <strong class="text-rose-950">{{ workspace?.groom_name || 'Quốc Trung' }} & {{ workspace?.bride_name || 'Hồng Vân' }}</strong>.
            </p>
          </div>

          <button @click="isAddModalOpen = true" class="px-5 py-3 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-2 shrink-0 cursor-pointer">
            <Plus class="w-4 h-4" /> + Nhập Thủ Công Vendor
          </button>
        </div>

        <!-- Filter Criteria Chips -->
        <div class="space-y-3">
          <span class="text-xs font-bold text-rose-950 uppercase tracking-wider block">CHỌN PHONG CÁCH VIBE DÂU RỂ MONG MUỐN:</span>
          <div class="flex flex-wrap gap-3">
            <button
              @click="filterRecommendations('pastel')"
              class="px-4 py-2 rounded-2xl text-xs font-bold transition border shadow-xs flex items-center gap-2 cursor-pointer"
              :class="selectedVibeState === 'pastel' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white/80 text-slate-700 border-rose-200 hover:bg-rose-50'"
            >
              🌸 Pastel Romantic & Luxury Glass
            </button>

            <button
              @click="filterRecommendations('royal')"
              class="px-4 py-2 rounded-2xl text-xs font-bold transition border shadow-xs flex items-center gap-2 cursor-pointer"
              :class="selectedVibeState === 'royal' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white/80 text-slate-700 border-rose-200 hover:bg-rose-50'"
            >
              🏛️ Royal Gold & Classic Hoàng Gia
            </button>

            <button
              @click="filterRecommendations('garden')"
              class="px-4 py-2 rounded-2xl text-xs font-bold transition border shadow-xs flex items-center gap-2 cursor-pointer"
              :class="selectedVibeState === 'garden' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white/80 text-slate-700 border-rose-200 hover:bg-rose-50'"
            >
              🌿 Botanical Garden & Outdoor
            </button>

            <button
              @click="filterRecommendations('minimalist')"
              class="px-4 py-2 rounded-2xl text-xs font-bold transition border shadow-xs flex items-center gap-2 cursor-pointer"
              :class="selectedVibeState === 'minimalist' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white/80 text-slate-700 border-rose-200 hover:bg-rose-50'"
            >
              ⚡ Minimalist Modern Line
            </button>
          </div>
        </div>
      </div>

      <!-- Curated Recommendations Grid & Map Section -->
      <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div class="space-y-1">
            <h2 class="text-xl font-serif font-bold text-rose-950 flex items-center gap-2">
              <Sparkles class="w-5 h-5 text-rose-600" />
              Danh Sách Đối Tác Ghép Đôi AI Matchmaking
            </h2>
            <p class="text-xs text-slate-500">Master Catalog do Admin kiểm duyệt & gợi ý theo bối cảnh cặp đôi</p>
          </div>

          <!-- View Mode Switcher -->
          <div class="p-1 rounded-2xl bg-white border border-rose-200/80 flex items-center gap-1 shadow-2xs">
            <button 
              @click="viewMode = 'grid'" 
              class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5"
              :class="viewMode === 'grid' ? 'bg-rose-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              📋 Danh Sách (Grid)
            </button>
            <button 
              @click="viewMode = 'map'" 
              class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5"
              :class="viewMode === 'map' ? 'bg-rose-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            >
              🗺️ Bản Đồ Tương Tác (Map View)
            </button>
          </div>
        </div>

        <!-- Categorized Category Tabs -->
        <div class="flex flex-wrap gap-2 pb-2 border-b border-rose-100">
          <button 
            @click="selectedCategoryTab = 'all'" 
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer"
            :class="selectedCategoryTab === 'all' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            Tất Cả Hạng Mục ({{ recommendations.length }})
          </button>
          <button 
            @click="selectedCategoryTab = 'venue'" 
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer"
            :class="selectedCategoryTab === 'venue' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            🏛️ Sảnh Tiệc & Nhà Hàng
          </button>
          <button 
            @click="selectedCategoryTab = 'studio'" 
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer"
            :class="selectedCategoryTab === 'studio' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            📸 Chụp Ảnh & Quay Phim
          </button>
          <button 
            @click="selectedCategoryTab = 'attire'" 
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer"
            :class="selectedCategoryTab === 'attire' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            👗 Váy Cưới & Trang Phục
          </button>
          <button 
            @click="selectedCategoryTab = 'florist'" 
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer"
            :class="selectedCategoryTab === 'florist' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            💐 Trang Trí & Decor
          </button>
          <button 
            @click="selectedCategoryTab = 'makeup'" 
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer"
            :class="selectedCategoryTab === 'makeup' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            💄 Makeup & Hair
          </button>
        </div>

        <!-- 1. Grid View Mode -->
        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="rec in filteredRecommendations"
            :key="rec.id"
            class="p-6 rounded-3xl bg-white/90 backdrop-blur-xl border border-rose-100/90 shadow-lg shadow-rose-900/5 hover:border-rose-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-4 group"
          >
            <div class="space-y-3">
              <!-- Portfolio Preview Banner -->
              <div class="relative h-44 rounded-2xl overflow-hidden bg-rose-50 border border-rose-100">
                <img :src="rec.portfolio_images[0]" :alt="rec.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-rose-950/90 backdrop-blur-md text-white text-xs font-extrabold border border-rose-400/40 flex items-center gap-1 shadow-md">
                  🔥 {{ rec.match_score }}% Match
                </div>
                <div class="absolute bottom-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-md text-rose-950 text-[11px] font-bold border border-white">
                  {{ rec.category_name }}
                </div>
              </div>

              <!-- Vendor Info -->
              <div class="space-y-1">
                <div class="flex items-center justify-between">
                  <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-md border border-amber-200">
                    {{ rec.vibe_label }}
                  </span>
                  <div class="flex items-center gap-1 text-xs font-bold text-amber-600">
                    <Star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" /> {{ rec.rating }}
                  </div>
                </div>

                <h3 class="text-lg font-serif font-bold text-slate-900 group-hover:text-rose-700 transition-colors">
                  {{ rec.name }}
                </h3>

                <p class="text-xs text-slate-500 flex items-center gap-1">
                  <MapPin class="w-3.5 h-3.5 text-rose-500 shrink-0" /> {{ rec.district }}, {{ rec.city }} • <strong class="text-slate-700">{{ rec.capacity_text }}</strong>
                </p>
              </div>

              <!-- Highlights Tags -->
              <div class="flex flex-wrap gap-1.5 pt-2 border-t border-rose-50">
                <span v-for="(hl, idx) in rec.highlights" :key="idx" class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-rose-50/80 text-rose-900 border border-rose-100">
                  ✓ {{ hl }}
                </span>
              </div>
            </div>

            <!-- Booking & Budget Action -->
            <div class="pt-4 border-t border-rose-100 flex items-center justify-between gap-2">
              <div class="text-xs">
                <span class="text-[10px] text-slate-400 block">KHOẢNG GIÁ DỰ KIẾN</span>
                <span class="font-bold text-rose-950 text-xs">{{ rec.price_label }}</span>
              </div>

              <div class="flex items-center gap-1.5">
                <button
                  v-if="rec.category === 'venue' || (rec as any).halls"
                  @click="selectedVenueForHalls = rec"
                  class="px-3 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 font-bold text-xs transition flex items-center gap-1 cursor-pointer"
                >
                  🏛️ Chi Tiết Sảnh
                </button>
                <button
                  @click="bookRecommendedVendor(rec)"
                  class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1 cursor-pointer"
                >
                  + Chốt Vendor
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 2. Interactive Fullview Right Column OpenStreetMap View Mode -->
        <div v-else-if="viewMode === 'map'" class="space-y-8">
          
          <!-- Top Split View: Left Vendor List + Right OpenStreetMap -->
          <div class="grid lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Scrollable Vendor List with Click Zoom -->
            <div class="lg:col-span-4 space-y-3 max-h-[600px] overflow-y-auto pr-2">
              <div
                v-for="rec in filteredRecommendations"
                :key="rec.id"
                @click="selectVendorForMap(rec)"
                class="p-4 rounded-2xl border transition-all duration-300 space-y-2 cursor-pointer group"
                :class="selectedVendorForMap?.id === rec.id ? 'bg-rose-50/80 border-rose-500 shadow-md ring-2 ring-rose-400/30' : 'bg-white border-rose-100 hover:border-rose-300 hover:shadow-sm'"
              >
                <div class="flex items-center justify-between">
                  <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                    {{ rec.vibe_label }}
                  </span>
                  <span class="text-xs font-extrabold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">
                    🔥 {{ rec.match_score }}% Match
                  </span>
                </div>

                <div>
                  <h4 class="text-sm font-serif font-bold text-slate-900 group-hover:text-rose-700 transition-colors">{{ rec.name }}</h4>
                  <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                    <MapPin class="w-3 h-3 text-rose-500 shrink-0" /> {{ rec.district }}, {{ rec.city }}
                  </p>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-rose-100/60 text-xs">
                  <span class="font-bold text-slate-900 text-xs">{{ rec.price_label }}</span>
                  <span class="text-[10px] font-bold text-rose-700 group-hover:underline flex items-center gap-1">
                    Xem Chi Tiết →
                  </span>
                </div>
              </div>
            </div>

            <!-- Right Column: Full-Height OpenStreetMap View -->
            <div class="lg:col-span-8 sticky top-24 h-[600px]">
              <OpenStreetMapVendorView 
                :vendors="filteredRecommendations" 
                :selectedVendorId="selectedVendorForMap?.id"
                @select-vendor="selectVendorForMap"
                @book-vendor="bookRecommendedVendor"
              />
            </div>

          </div>

          <!-- Bottom Rich Contextual Vendor Details Panel (Đẩy Thông Tin Chi Tiết Lên Bên Dưới) -->
          <div v-if="selectedVendorForMap" class="p-6 md:p-8 rounded-3xl bg-white border border-rose-200 shadow-xl space-y-6 animate-fade-in">
            
            <!-- Vendor Selected Header Banner -->
            <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-rose-100">
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <span class="px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold">
                    {{ selectedVendorForMap.vibe_label }}
                  </span>
                  <span class="px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-800 border border-rose-200 text-xs font-bold">
                    {{ selectedVendorForMap.category_name }}
                  </span>
                  <div class="flex items-center gap-1 text-xs font-bold text-amber-600">
                    <Star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" /> {{ selectedVendorForMap.rating }}
                  </div>
                </div>

                <h3 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">
                  {{ selectedVendorForMap.name }}
                </h3>

                <p class="text-xs text-slate-500 flex items-center gap-1">
                  <MapPin class="w-3.5 h-3.5 text-rose-500" /> {{ selectedVendorForMap.district }}, {{ selectedVendorForMap.city }} • <strong class="text-slate-800">{{ selectedVendorForMap.capacity_text }}</strong>
                </p>
              </div>

              <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                  <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-medium">KHOẢNG GIÁ DỰ KIẾN</span>
                  <span class="text-base font-bold text-rose-950">{{ selectedVendorForMap.price_label }}</span>
                </div>
                <button
                  @click="bookRecommendedVendor(selectedVendorForMap)"
                  class="px-6 py-3 rounded-2xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2 cursor-pointer"
                >
                  <Plus class="w-4 h-4 text-rose-400" />
                  Chốt Đối Tác Vào Kế Hoạch
                </button>
              </div>
            </div>

            <!-- IF VENUE / RESTAURANT: Render Hall Catalog & Menu Specs -->
            <div v-if="selectedVendorForMap.category === 'venue'" class="space-y-6">
              
              <!-- Hall Catalog Grid -->
              <div>
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-base font-serif font-bold text-slate-900 flex items-center gap-2">
                    <Building2 class="w-4 h-4 text-rose-600" />
                    Danh Sách Sảnh Tiệc & Sức Chứa (Hall Specifications)
                  </h4>
                  <span class="text-xs font-semibold text-rose-700">3 Sảnh Tiệc Đủ Tiêu Chuẩn</span>
                </div>

                <div class="grid md:grid-cols-3 gap-4 font-sans">
                  <!-- Hall 1 -->
                  <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-200/80 space-y-3 hover:bg-rose-50 transition">
                    <div class="flex items-center justify-between">
                      <h5 class="font-bold text-slate-900 text-sm">Sảnh Grand Ballroom (Tầng 3)</h5>
                      <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 text-[10px] font-bold">Lớn Nhất</span>
                    </div>
                    <ul class="text-xs text-slate-600 space-y-1.5 font-medium">
                      <li>• Sức chứa: <strong>35 - 50 bàn</strong> (350 - 500 khách)</li>
                      <li>• Độ cao trần: <strong>7.5m</strong> không cột che tầm nhìn</li>
                      <li>• Trang bị: Màn hình LED 8K Curved 400 inch</li>
                      <li>• Giá tối thiểu: <strong>8,500,000đ / Bàn</strong></li>
                    </ul>
                    <button 
                      @click="bookRecommendedVendor(selectedVendorForMap)"
                      class="w-full py-2 rounded-xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs transition cursor-pointer text-center"
                    >
                      + Chọn Sảnh Này
                    </button>
                  </div>

                  <!-- Hall 2 -->
                  <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-200/80 space-y-3 hover:bg-rose-50 transition">
                    <div class="flex items-center justify-between">
                      <h5 class="font-bold text-slate-900 text-sm">Sảnh Crystal Suite (Tầng 1)</h5>
                      <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold">Ấm Củng</span>
                    </div>
                    <ul class="text-xs text-slate-600 space-y-1.5 font-medium">
                      <li>• Sức chứa: <strong>15 - 25 bàn</strong> (150 - 250 khách)</li>
                      <li>• Thiết kế: Kính mờ Châu Âu & thảm hoa hồng</li>
                      <li>• Sân khấu: Sân khấu xoay 360° sang trọng</li>
                      <li>• Giá tối thiểu: <strong>6,800,000đ / Bàn</strong></li>
                    </ul>
                    <button 
                      @click="bookRecommendedVendor(selectedVendorForMap)"
                      class="w-full py-2 rounded-xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs transition cursor-pointer text-center"
                    >
                      + Chọn Sảnh Này
                    </button>
                  </div>

                  <!-- Hall 3 -->
                  <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-200/80 space-y-3 hover:bg-rose-50 transition">
                    <div class="flex items-center justify-between">
                      <h5 class="font-bold text-slate-900 text-sm">Rooftop Sky Garden (Sân Thượng)</h5>
                      <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">Ngoài Trời</span>
                    </div>
                    <ul class="text-xs text-slate-600 space-y-1.5 font-medium">
                      <li>• Sức chứa: <strong>10 - 20 bàn</strong> (Long Table ngoài trời)</li>
                      <li>• Bối cảnh: Ngắm hoàng hôn & lung linh đèn LED</li>
                      <li>• Phong cách: Boho Chic & Outdoor Garden</li>
                      <li>• Giá tối thiểu: <strong>9,200,000đ / Bàn</strong></li>
                    </ul>
                    <button 
                      @click="bookRecommendedVendor(selectedVendorForMap)"
                      class="w-full py-2 rounded-xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs transition cursor-pointer text-center"
                    >
                      + Chọn Sảnh Này
                    </button>
                  </div>
                </div>
              </div>

              <!-- Set Menu & Perks Breakdown -->
              <div class="grid md:grid-cols-2 gap-6 pt-4 border-t border-rose-100">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                  <h5 class="font-serif font-bold text-slate-900 text-sm">Thực Đơn Tiệc Cưới Mẫu 8 Món Cao Cấp</h5>
                  <p class="text-xs text-slate-600 font-medium">1. Khai vị 3 món Á-Âu • 2. Súp hải sâm tổ yến • 3. Tôm hùm đút lò phô mai • 4. Bò Mỹ sốt rượu vang đỏ • 5. Cá tầm hấp Hongkong • 6. Lẩu nấm hải sản thượng hạng • 7. Cơm chiên hải sản hoàng kim • 8. Chè tuyết yến hạt chia.</p>
                </div>

                <div class="p-5 rounded-2xl bg-rose-50/60 border border-rose-200/80 space-y-2">
                  <h5 class="font-serif font-bold text-slate-900 text-sm">Chính Sách Ưu Đãi Độc Quyền Dành Cho Dâu Rể Eloria</h5>
                  <ul class="text-xs text-slate-700 space-y-1 font-medium">
                    <li>✓ Miễn phí tháp rượu champagne & bánh cưới 5 tầng</li>
                    <li>✓ Miễn phí nước ngọt & bia suốt 2.5 giờ diễn ra tiệc</li>
                    <li>✓ Tặng gói trang trí hoa tươi cơ bản bàn tiệc & cổng đón khách</li>
                  </ul>
                </div>
              </div>

            </div>

            <!-- IF NON-VENUE (STUDIO / BRIDAL / DECOR / MAKEUP): Render Package Plans -->
            <div v-else class="space-y-6">
              <div>
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-base font-serif font-bold text-slate-900 flex items-center gap-2">
                    <Sparkles class="w-4 h-4 text-rose-600" />
                    Bảng Gói Dịch Vụ Trọn Gói (Service Packages & Pricing)
                  </h4>
                  <span class="text-xs font-semibold text-rose-700">Ưu Đãi Trực Tiếp Trên Eloria</span>
                </div>

                <div class="grid md:grid-cols-2 gap-6 font-sans">
                  <!-- Package 1 -->
                  <div class="p-6 rounded-2xl bg-gradient-to-br from-rose-50 to-amber-50/50 border border-rose-200 space-y-4">
                    <div class="flex items-center justify-between">
                      <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-700 block">GÓI CAO CẤP</span>
                        <h5 class="font-serif font-bold text-slate-900 text-lg">Gói Signature Luxe</h5>
                      </div>
                      <span class="text-lg font-bold text-rose-950">25,000,000đ</span>
                    </div>

                    <ul class="text-xs text-slate-700 space-y-2 font-medium border-t border-rose-200/60 pt-3">
                      <li class="flex items-center gap-2">✓ Chụp Studio 3 concept nghệ thuật độc quyền</li>
                      <li class="flex items-center gap-2">✓ 2 Bộ váy cưới dòng Haute Couture cao cấp nhất</li>
                      <li class="flex items-center gap-2">✓ Make-up & làm tóc ngày cưới chuyên nghiệp</li>
                      <li class="flex items-center gap-2">✓ Album Photobook 30x30cm 30 trang ép kim</li>
                      <li class="flex items-center gap-2">✓ 2 Ảnh cổng lớn kích thước 60x90cm ép gỗ cao cấp</li>
                    </ul>

                    <button 
                      @click="bookRecommendedVendor(selectedVendorForMap)"
                      class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition cursor-pointer text-center"
                    >
                      + Đăng Ký Gói Signature
                    </button>
                  </div>

                  <!-- Package 2 -->
                  <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between">
                      <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">GÓI TIÊU CHUẨN</span>
                        <h5 class="font-serif font-bold text-slate-900 text-lg">Gói Basic Elegant</h5>
                      </div>
                      <span class="text-lg font-bold text-slate-900">15,000,000đ</span>
                    </div>

                    <ul class="text-xs text-slate-700 space-y-2 font-medium border-t border-slate-100 pt-3">
                      <li class="flex items-center gap-2">✓ Chụp Studio 2 concept phong cách Hàn Quốc</li>
                      <li class="flex items-center gap-2">✓ 1 Váy cưới dòng Premium + 1 Suit chú rể</li>
                      <li class="flex items-center gap-2">✓ Trang điểm 1 lần tại Studio</li>
                      <li class="flex items-center gap-2">✓ Album Photobook 20x30cm 20 trang</li>
                      <li class="flex items-center gap-2">✓ 1 Ảnh cổng lớn kích thước 60x90cm</li>
                    </ul>

                    <button 
                      @click="bookRecommendedVendor(selectedVendorForMap)"
                      class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-900 font-bold text-xs transition cursor-pointer text-center"
                    >
                      + Đăng Ký Gói Basic
                    </button>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>

      <!-- Existing Vendor CRM Ledger Section -->
      <div class="pt-6 space-y-6">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-serif font-bold text-slate-900">
            Sổ Quản Lý Hợp Đồng Đối Tác Đã Đăng Ký
          </h2>
          <span class="text-xs font-medium text-slate-500">{{ vendorsList.length }} Hợp đồng active</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="p-6 rounded-2xl bg-white border border-rose-100 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Tổng Giá Trị Hợp Đồng</span>
            <div class="text-2xl font-extrabold text-slate-900">{{ formatVnd(summaryData.total_contracts) }}</div>
          </div>

          <div class="p-6 rounded-2xl bg-white border border-rose-100 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Đã Thanh Toán / Cọc</span>
            <div class="text-2xl font-extrabold text-emerald-700">{{ formatVnd(summaryData.total_paid) }}</div>
          </div>

          <div class="p-6 rounded-2xl bg-white border border-rose-100 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Dư Nợ Còn Lại</span>
            <div class="text-2xl font-extrabold text-rose-700">{{ formatVnd(summaryData.remaining_unpaid) }}</div>
          </div>
        </div>
      </div>

      <!-- Vendors Table List Section -->
      <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
          <h2 class="text-sm font-semibold text-slate-900">Danh bạ Nhà cung cấp & Hợp đồng</h2>
          <span class="text-xs text-slate-500">Hiển thị {{ vendorsList.length }} đối tác</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-medium uppercase tracking-wider">
              <tr>
                <th class="px-6 py-3">Tên Nhà Cung Cấp</th>
                <th class="px-6 py-3">Phân Loại</th>
                <th class="px-6 py-3">Người Liên Hệ / SĐT</th>
                <th class="px-6 py-3">Giá Trị Hợp Đồng</th>
                <th class="px-6 py-3">Đã Trả</th>
                <th class="px-6 py-3">Nợ Còn Lại</th>
                <th class="px-6 py-3">Trạng Thái</th>
                <th class="px-6 py-3 text-right">Thao Tác</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-800">
              <tr v-for="vendor in vendorsList" :key="vendor.id" class="hover:bg-slate-50/80 transition">
                <td class="px-6 py-4 font-semibold text-slate-900">
                  {{ vendor.name }}
                  <div class="text-[11px] font-normal text-slate-400 mt-0.5" v-if="vendor.due_date">
                    Hạn trả: {{ vendor.due_date }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="px-2 py-0.5 rounded border border-slate-200 text-slate-600 bg-slate-50">
                    {{ getCategoryLabel(vendor.category) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div>{{ vendor.contact_name || '—' }}</div>
                  <div class="text-slate-400 text-[11px]">{{ vendor.phone || vendor.email || '' }}</div>
                </td>
                <td class="px-6 py-4 font-medium">{{ formatVnd(vendor.contract_amount) }}</td>
                <td class="px-6 py-4 text-emerald-600 font-medium">{{ formatVnd(vendor.paid_amount) }}</td>
                <td class="px-6 py-4 text-rose-600 font-medium">{{ formatVnd(vendor.unpaid_balance) }}</td>
                <td class="px-6 py-4">
                  <span class="px-2 py-0.5 rounded-full border text-[11px] font-medium" :class="getStatusBadgeClass(vendor.payment_status)">
                    {{ getStatusLabel(vendor.payment_status) }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <button
                    v-if="vendor.payment_status !== 'fully_paid'"
                    @click="openPaymentModal(vendor)"
                    class="px-2.5 py-1 text-[11px] font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-md transition"
                  >
                    + Trả thêm
                  </button>
                  <span v-else class="text-slate-400 text-[11px]">Đã hoàn tất</span>
                </td>
              </tr>

              <tr v-if="vendorsList.length === 0">
                <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                  Chưa có nhà cung cấp nào. Bấm nút "+ Thêm Nhà cung cấp" ở trên để khởi tạo.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Create Vendor Modal -->
    <div v-if="isAddModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs p-4">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
          <h3 class="text-sm font-semibold text-slate-900">Thêm Nhà Cung Cấp Mới</h3>
          <button @click="isAddModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
        </div>

        <form @submit.prevent="handleCreateVendor" class="p-6 space-y-4 text-xs">
          <div>
            <label class="block font-medium text-slate-700 mb-1">Tên Nhà cung cấp / Studio / Nhà hàng *</label>
            <input
              v-model="newVendorForm.name"
              required
              type="text"
              placeholder="Ví dụ: Trung tâm Tiệc cưới White Palace"
              class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-medium text-slate-700 mb-1">Phân loại Dịch vụ</label>
              <select
                v-model="newVendorForm.category"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-rose-500/20"
              >
                <option value="venue">Địa điểm & Tiệc</option>
                <option value="studio">Chụp ảnh & Quay phim</option>
                <option value="catering">Ẩm thực & Đồ uống</option>
                <option value="makeup">Trang điểm & Làm tóc</option>
                <option value="florist">Hoa tươi & Trang trí</option>
                <option value="music">Âm thanh & MC</option>
                <option value="other">Khác</option>
              </select>
            </div>
            <div>
              <label class="block font-medium text-slate-700 mb-1">Hạn thanh toán đợt tiếp</label>
              <input
                v-model="newVendorForm.due_date"
                type="date"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-rose-500/20"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-medium text-slate-700 mb-1">Tổng Giá trị Hợp đồng (VNĐ)</label>
              <input
                v-model.number="newVendorForm.contract_amount"
                type="number"
                min="0"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-rose-500/20"
              />
            </div>
            <div>
              <label class="block font-medium text-slate-700 mb-1">Số tiền đã cọc/trả trước (VNĐ)</label>
              <input
                v-model.number="newVendorForm.paid_amount"
                type="number"
                min="0"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-rose-500/20"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-medium text-slate-700 mb-1">Người đại diện liên hệ</label>
              <input
                v-model="newVendorForm.contact_name"
                type="text"
                placeholder="Anh Hùng (Manager)"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900"
              />
            </div>
            <div>
              <label class="block font-medium text-slate-700 mb-1">Số điện thoại</label>
              <input
                v-model="newVendorForm.phone"
                type="text"
                placeholder="0901234567"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900"
              />
            </div>
          </div>

          <div class="pt-4 flex justify-end gap-2 border-t border-slate-200">
            <button
              type="button"
              @click="isAddModalOpen = false"
              class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium"
            >
              Hủy
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-medium disabled:opacity-50"
            >
              Lưu Nhà Cung Cấp
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Record Payment Modal -->
    <div v-if="isPaymentModalOpen && selectedVendorForPayment" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs p-4">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
          <h3 class="text-sm font-semibold text-slate-900">Ghi nhận Thanh toán đối tác</h3>
          <button @click="isPaymentModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
        </div>

        <form @submit.prevent="handleRecordPayment" class="p-6 space-y-4 text-xs">
          <div>
            <div class="text-xs text-slate-500">Đối tác:</div>
            <div class="text-sm font-bold text-slate-900">{{ selectedVendorForPayment.name }}</div>
            <div class="text-xs text-slate-500 mt-1">
              Dư nợ hiện tại: <span class="font-semibold text-rose-600">{{ formatVnd(selectedVendorForPayment.unpaid_balance) }}</span>
            </div>
          </div>

          <div>
            <label class="block font-medium text-slate-700 mb-1">Số tiền thanh toán thêm (VNĐ) *</label>
            <input
              v-model.number="paymentAmountInput"
              required
              type="number"
              min="1"
              :max="selectedVendorForPayment.unpaid_balance"
              class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 font-semibold"
            />
          </div>

          <div class="pt-4 flex justify-end gap-2 border-t border-slate-200">
            <button
              type="button"
              @click="isPaymentModalOpen = false"
              class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium"
            >
              Hủy
            </button>
            <button
              type="submit"
              :disabled="isSubmitting || paymentAmountInput <= 0"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium disabled:opacity-50"
            >
              Xác Nhận Giải Ngân
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Ballroom Halls View Modal for Venue Vendors -->
    <div v-if="selectedVenueForHalls" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-md">
      <div class="max-w-3xl w-full max-h-[88vh] overflow-y-auto rounded-3xl bg-white p-6 space-y-6 shadow-2xl border border-rose-200">
        <div class="flex items-center justify-between border-b border-rose-100 pb-4">
          <div>
            <span class="text-[10px] font-extrabold text-rose-600 uppercase tracking-widest bg-rose-100 px-3 py-1 rounded-full border border-rose-200">
              THÔNG TIN SẢNH TIỆC & SỨC CHỨA BÀN TIỆC
            </span>
            <h2 class="text-xl md:text-2xl font-serif font-bold text-slate-900 mt-2 flex items-center gap-2">
              🏛️ {{ selectedVenueForHalls.name }}
            </h2>
            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
              <MapPin class="w-3.5 h-3.5 text-rose-500" /> {{ selectedVenueForHalls.district }}, {{ selectedVenueForHalls.city }} • 📞 {{ selectedVenueForHalls.phone }}
            </p>
          </div>
          <button @click="selectedVenueForHalls = null" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-700 flex items-center justify-center font-bold transition">
            ✕
          </button>
        </div>

        <!-- Halls List Grid -->
        <div class="grid md:grid-cols-2 gap-6">
          <div v-for="(hall, hIdx) in ((selectedVenueForHalls as any).halls || [])" :key="hIdx" class="p-5 rounded-2xl bg-rose-50/50 border border-rose-100 space-y-3.5 hover:shadow-md transition flex flex-col justify-between">
            <div class="space-y-3">
              <div class="h-40 rounded-xl overflow-hidden relative border border-rose-200">
                <img :src="hall.image" :alt="hall.name" class="w-full h-full object-cover" />
                <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-amber-300 text-[10px] font-bold">
                  {{ hall.price_per_table || 'Đơn giá thoả thuận' }}
                </div>
              </div>
              <div>
                <h3 class="font-serif font-bold text-base text-rose-950">{{ hall.name }}</h3>
                <p class="text-xs text-emerald-700 font-bold mt-1 flex items-center gap-1">
                  <Users class="w-3.5 h-3.5 text-emerald-600" /> Sức chứa: {{ hall.capacity_tables }}
                </p>
                <p class="text-xs text-slate-600 leading-relaxed mt-2">{{ hall.description }}</p>
              </div>
            </div>

            <button
              @click="bookRecommendedVendor(selectedVenueForHalls); selectedVenueForHalls = null"
              class="w-full py-2 rounded-xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition text-center mt-2 cursor-pointer"
            >
              + Chốt Vendor Sảnh Này
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Grounded AI Drawer -->
    <GroundedAiDrawer :is-open="isAiDrawerOpen" @close="isAiDrawerOpen = false" />
  </WorkspaceLayout>
</template>
