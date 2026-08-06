<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
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
  Palette,
  Map as MapIcon,
  Columns,
  Table as TableIcon,
  Filter,
  Phone,
  Mail,
  Navigation,
  Search,
  Info,
  Eye,
  Percent,
  Keyboard
} from 'lucide-vue-next';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
<<<<<<< HEAD
import OpenStreetMapVendorView from '@/Components/Wedding/OpenStreetMapVendorView.vue';
=======
import VendorMap, { MapVendorItem } from '@/Components/Vendor/VendorMap.vue';
import VendorDetailDrawer from '@/Components/Vendor/VendorDetailDrawer.vue';
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)

interface RecommendedVendor {
  id: string;
  name: string;
  category: string;
  category_name: string;
  vibe_category: string;
  vibe_label: string;
  city: string;
  district: string;
  address?: string | null;
  latitude?: number | null;
  longitude?: number | null;
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
  address?: string | null;
  latitude?: number | null;
  longitude?: number | null;
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
<<<<<<< HEAD
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
=======
const selectedCategoryFilter = ref<string>('all');
const searchQuery = ref<string>('');
const viewMode = ref<'split' | 'map' | 'table'>('split');
const selectedVendorId = ref<string | null>(null);
const isMapFullScreen = ref(false);
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)

const toggleMapFullScreen = () => {
  isMapFullScreen.value = !isMapFullScreen.value;
};

<<<<<<< HEAD
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

=======
const handleKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && isMapFullScreen.value) {
    isMapFullScreen.value = false;
  }
  if ((e.key === 'm' || e.key === 'M') && !['INPUT', 'TEXTAREA'].includes((e.target as HTMLElement)?.tagName)) {
    isMapFullScreen.value = !isMapFullScreen.value;
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeydown);
});

const searchInputRef = ref<HTMLInputElement | null>(null);

// Drawer State
const isDrawerOpen = ref(false);
const drawerVendor = ref<MapVendorItem | null>(null);

>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)
const vendorsList = ref<VendorItem[]>([...props.vendors]);
const summaryData = ref<VendorSummary>({ ...props.summary });

// Preset Coordinates Map for Districts
const districtPresets: Record<string, { lat: number; lng: number }> = {
  'Quận 1': { lat: 10.7768, lng: 106.7008 },
  'Quận 3': { lat: 10.7792, lng: 106.6918 },
  'Quận 7': { lat: 10.7258, lng: 106.7118 },
  'Phú Nhuận': { lat: 10.8045, lng: 106.6713 },
  'Bình Thạnh': { lat: 10.8015, lng: 106.6895 },
  'Tân Bình': { lat: 10.8012, lng: 106.6589 },
};

// Category Icon Map
const getCategoryIcon = (category: string) => {
  switch (category) {
    case 'venue': return '🏛️';
    case 'studio': return '📷';
    case 'makeup': return '💄';
    case 'florist': return '🌸';
    case 'attire': return '👗';
    case 'catering': return '🍷';
    default: return '✨';
  }
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

<<<<<<< HEAD
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
=======
// Unified Vendors map items with Search & Category Filtering
const allMapVendors = computed<MapVendorItem[]>(() => {
  const bookedMapItems: MapVendorItem[] = vendorsList.value.map((v) => ({
    id: v.id,
    name: v.name,
    category: v.category,
    category_name: getCategoryLabel(v.category),
    vibe_category: v.vibe_category || 'pastel',
    vibe_label: 'Hợp Đồng Đã Chốt',
    city: v.city || 'TP. Hồ Chí Minh',
    district: v.district || 'Quận 1',
    address: v.address || `${v.district || 'Quận 1'}, ${v.city || 'TP. Hồ Chí Minh'}`,
    latitude: v.latitude ?? (districtPresets[v.district || 'Quận 1']?.lat || 10.7768),
    longitude: v.longitude ?? (districtPresets[v.district || 'Quận 1']?.lng || 10.6983),
    contact_name: v.contact_name,
    phone: v.phone,
    email: v.email,
    is_booked: true,
    contract_amount: v.contract_amount,
    paid_amount: v.paid_amount,
    payment_status: v.payment_status,
  }));

  const recommendedMapItems: MapVendorItem[] = props.recommendations.map((r) => ({
    id: r.id,
    name: r.name,
    category: r.category,
    category_name: r.category_name,
    vibe_category: r.vibe_category,
    vibe_label: r.vibe_label,
    city: r.city,
    district: r.district,
    address: r.address || `${r.district}, ${r.city}`,
    latitude: r.latitude ?? 10.7768,
    longitude: r.longitude ?? 10.7008,
    price_tier: r.price_tier,
    price_label: r.price_label,
    rating: r.rating,
    capacity_text: r.capacity_text,
    contact_name: r.contact_name,
    phone: r.phone,
    email: r.email,
    portfolio_images: r.portfolio_images,
    highlights: r.highlights,
    match_score: r.match_score,
    is_booked: false,
  }));

  let merged = [...bookedMapItems, ...recommendedMapItems];

  if (selectedCategoryFilter.value !== 'all') {
    merged = merged.filter((item) => item.category === selectedCategoryFilter.value);
  }

  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase().trim();
    merged = merged.filter(
      (item) =>
        item.name.toLowerCase().includes(q) ||
        (item.district && item.district.toLowerCase().includes(q)) ||
        (item.address && item.address.toLowerCase().includes(q)) ||
        (item.phone && item.phone.includes(q))
    );
  }

  return merged;
});

// Category Counts Breakdown
const categoryCounts = computed(() => {
  const counts: Record<string, number> = { all: props.recommendations.length + vendorsList.value.length };
  [...vendorsList.value, ...props.recommendations].forEach((item) => {
    counts[item.category] = (counts[item.category] || 0) + 1;
  });
  return counts;
});

// Deposit Paid Percentage
const depositProgressPercent = computed(() => {
  if (!summaryData.value.total_contracts || summaryData.value.total_contracts <= 0) return 0;
  return Math.min(100, Math.round((summaryData.value.total_paid / summaryData.value.total_contracts) * 100));
});

// Filtered recommendations list
const filteredRecommendations = computed(() => {
  let list = props.recommendations;
  if (selectedCategoryFilter.value !== 'all') {
    list = list.filter((r) => r.category === selectedCategoryFilter.value);
  }
  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter(
      (r) =>
        r.name.toLowerCase().includes(q) ||
        r.district.toLowerCase().includes(q) ||
        (r.address && r.address.toLowerCase().includes(q))
    );
  }
  return list;
});

// Filtered Booked Contracts
const filteredBookedVendors = computed(() => {
  let list = vendorsList.value;
  if (selectedCategoryFilter.value !== 'all') {
    list = list.filter((v) => v.category === selectedCategoryFilter.value);
  }
  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter(
      (v) =>
        v.name.toLowerCase().includes(q) ||
        (v.district && v.district.toLowerCase().includes(q)) ||
        (v.address && v.address.toLowerCase().includes(q))
    );
  }
  return list;
});

// Modals state
const isAddModalOpen = ref(false);
const isPaymentModalOpen = ref(false);
const selectedVendorForPayment = ref<VendorItem | null>(null);

const newVendorForm = ref({
  name: '',
  category: 'venue',
  district: 'Quận 1',
  address: '',
  latitude: 10.7768,
  longitude: 106.7008,
  contact_name: '',
  phone: '',
  email: '',
  contract_amount: 0,
  paid_amount: 0,
  due_date: '',
  notes: '',
});

const onDistrictChange = () => {
  const preset = districtPresets[newVendorForm.value.district];
  if (preset) {
    newVendorForm.value.latitude = preset.lat;
    newVendorForm.value.longitude = preset.lng;
  }
};

const paymentAmountInput = ref<number>(0);
const isSubmitting = ref(false);

const filterRecommendationsByVibe = (vibe: string) => {
  selectedVibeState.value = vibe;
  router.get('/wedding/vendors', { vibe, location: selectedLocationState.value }, { preserveState: true, preserveScroll: true });
};

const formatVnd = (num: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num || 0);
};

const getStatusBadgeClass = (status: string) => {
  switch (status) {
    case 'fully_paid':
      return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    case 'partially_paid':
      return 'bg-amber-50 text-amber-700 border-amber-200';
    default:
      return 'bg-rose-50 text-rose-700 border-rose-200';
  }
};

const getStatusLabel = (status: string) => {
  switch (status) {
    case 'fully_paid':
      return 'Đã hoàn tất thanh toán';
    case 'partially_paid':
      return 'Đã cọc 1 phần';
    default:
      return 'Chưa cọc (Nợ)';
  }
};

const openVendorDrawer = (vendor: MapVendorItem | RecommendedVendor | VendorItem) => {
  selectedVendorId.value = vendor.id;
  const matchItem = allMapVendors.value.find((v) => v.id === vendor.id);
  drawerVendor.value = matchItem || (vendor as MapVendorItem);
  isDrawerOpen.value = true;
};

const selectVendorOnMap = (vendor: MapVendorItem | RecommendedVendor | VendorItem) => {
  selectedVendorId.value = vendor.id;
};

const bookRecommendedVendor = (rec: RecommendedVendor | MapVendorItem) => {
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)
  newVendorForm.value = {
    name: rec.name,
    category: rec.category,
    district: rec.district || 'Quận 1',
    address: rec.address || `${rec.district}, ${rec.city}`,
    latitude: rec.latitude || 10.7768,
    longitude: rec.longitude || 106.7008,
    contact_name: rec.contact_name || '',
    phone: rec.phone || '',
    email: rec.email || '',
    contract_amount: rec.price_tier === 'luxury' ? 300000000 : (rec.price_tier === 'premium' ? 150000000 : 50000000),
    paid_amount: 20000000,
    due_date: '2026-09-01',
    notes: `Chốt từ Smart Matchmaker Engine (${rec.match_score || 90}% Match • Vibe: ${rec.vibe_label || 'Pastel'})`,
  };
  isAddModalOpen.value = true;
};

const handleCreateVendor = async () => {
  isSubmitting.value = true;
  try {
    const res = await axios.post('/wedding/vendors', newVendorForm.value);
    if (res.data.success) {
      vendorsList.value.unshift({
        id: res.data.vendor.id,
        name: res.data.vendor.name,
        category: res.data.vendor.category,
        vibe_category: res.data.vendor.vibe_category || 'pastel',
        city: res.data.vendor.city || 'TP. Hồ Chí Minh',
        district: res.data.vendor.district || 'Quận 1',
        address: res.data.vendor.address,
        latitude: res.data.vendor.latitude,
        longitude: res.data.vendor.longitude,
        contact_name: res.data.vendor.contact_name,
        phone: res.data.vendor.phone,
        email: res.data.vendor.email,
        contract_amount: Number(res.data.vendor.contract_amount),
        paid_amount: Number(res.data.vendor.paid_amount),
        unpaid_balance: Number(res.data.vendor.contract_amount) - Number(res.data.vendor.paid_amount),
        payment_status: res.data.vendor.payment_status,
        due_date: res.data.vendor.due_date,
        contract_file: res.data.vendor.contract_file,
        notes: res.data.vendor.notes,
      });
      if (res.data.summary) summaryData.value = res.data.summary;
      isAddModalOpen.value = false;
    }
  } catch (err) {
    console.error('Error creating vendor:', err);
  } finally {
    isSubmitting.value = false;
  }
};

const openPaymentModal = (vendor: VendorItem | MapVendorItem) => {
  const match = vendorsList.value.find((v) => v.id === vendor.id);
  if (match) {
    selectedVendorForPayment.value = match;
    paymentAmountInput.value = match.unpaid_balance;
    isPaymentModalOpen.value = true;
  }
};

const handleRecordPayment = async () => {
  if (!selectedVendorForPayment.value) return;
  isSubmitting.value = true;
  try {
    const res = await axios.post(`/wedding/vendors/${selectedVendorForPayment.value.id}/payment`, {
      amount: paymentAmountInput.value,
    });
    if (res.data.success) {
      const idx = vendorsList.value.findIndex((v) => v.id === selectedVendorForPayment.value?.id);
      if (idx !== -1) {
        vendorsList.value[idx].paid_amount = res.data.vendor.paid_amount;
        vendorsList.value[idx].unpaid_balance = res.data.vendor.unpaid_balance;
        vendorsList.value[idx].payment_status = res.data.vendor.payment_status;
      }
      if (res.data.summary) summaryData.value = res.data.summary;
      isPaymentModalOpen.value = false;
    }
  } catch (err) {
    console.error('Error recording payment:', err);
  } finally {
    isSubmitting.value = false;
  }
};

// Fast Keyboard Shortcuts Listener
const handleKeyDown = (e: KeyboardEvent) => {
  if (e.target instanceof HTMLInputElement || e.target instanceof HTMLTextAreaElement) return;

  if (e.key === '/' || (e.key === 'k' && (e.metaKey || e.ctrlKey))) {
    e.preventDefault();
    searchInputRef.value?.focus();
  } else if (e.key === 'm' || e.key === 'M') {
    viewMode.value = 'map';
  } else if (e.key === 's' || e.key === 'S') {
    viewMode.value = 'split';
  } else if (e.key === 't' || e.key === 'T') {
    viewMode.value = 'table';
  } else if (e.key === 'n' || e.key === 'N') {
    isAddModalOpen.value = true;
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
});

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
  <WorkspaceLayout title="Smart Vendor Matchmaker & CRM" active-nav="vendors">
    <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">
      <!-- Top Header & Action Bar -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-rose-100 shadow-2xs">
        <div>
          <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-800 text-[11px] font-extrabold uppercase tracking-widest border border-rose-200">
              ELORIA OS • VENDOR MAP & CRM
            </span>
            <span class="text-[11px] text-slate-400 font-semibold hidden md:inline-flex items-center gap-1">
              <Keyboard class="w-3.5 h-3.5 text-slate-400" /> Nhấn <kbd class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[10px] border border-slate-300">/</kbd> để tìm nhanh
            </span>
          </div>
          <h1 class="text-2xl font-serif font-bold text-slate-900 mt-1.5">
            Quản Lý Đối Tác Cưới & Bản Đồ Phân Cụm
          </h1>
          <p class="text-xs text-slate-500 mt-0.5">
            Ghép đôi đối tác thông minh theo điểm <strong>% Match Score</strong> & theo dõi tiến độ giải ngân hợp đồng.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <!-- View Mode Switcher -->
          <div class="flex items-center p-1 bg-slate-100/80 rounded-2xl border border-slate-200 text-xs font-bold">
            <button
              @click="viewMode = 'split'"
              class="px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 cursor-pointer"
              :class="viewMode === 'split' ? 'bg-white text-rose-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900'"
              title="Phím tắt: S"
            >
              <Columns class="w-3.5 h-3.5 text-rose-600" /> Bản Đồ & Card
            </button>
            <button
              @click="viewMode = 'map'"
              class="px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 cursor-pointer"
              :class="viewMode === 'map' ? 'bg-white text-rose-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900'"
              title="Phím tắt: M"
            >
              <MapIcon class="w-3.5 h-3.5 text-rose-600" /> Full Map
            </button>
            <button
              @click="viewMode = 'table'"
              class="px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 cursor-pointer"
              :class="viewMode === 'table' ? 'bg-white text-rose-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900'"
              title="Phím tắt: T"
            >
              <TableIcon class="w-3.5 h-3.5 text-rose-600" /> Sổ Hợp Đồng
            </button>
          </div>

          <button
            @click="isAddModalOpen = true"
            class="px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-2 cursor-pointer shrink-0"
            title="Phím tắt: N"
          >
            <Plus class="w-4 h-4" /> + Nhập Vendor
          </button>
        </div>
      </div>

      <!-- Financial Deposit Progress Stat Bar -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-rose-100 shadow-2xs space-y-1">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">HỢP ĐỒNG ĐÃ KÝ KẾT</span>
          <div class="text-xl font-extrabold text-slate-900">{{ vendorsList.length }} Nhà cung cấp</div>
          <div class="text-[11px] text-slate-500">Tổng giá trị: <strong class="text-slate-900">{{ formatVnd(summaryData.total_contracts) }}</strong></div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-rose-100 shadow-2xs space-y-1">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ĐÃ GIẢI NGÂN / CỌC</span>
          <div class="text-xl font-extrabold text-emerald-700">{{ formatVnd(summaryData.total_paid) }}</div>
          <div class="space-y-1 pt-1">
            <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
              <div class="h-full bg-emerald-500 transition-all duration-500" :style="{ width: `${depositProgressPercent}%` }"></div>
            </div>
            <div class="text-[10px] font-bold text-emerald-700 text-right">{{ depositProgressPercent }}% Hoàn tất cọc</div>
          </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-rose-100 shadow-2xs space-y-1">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">DƯ NỢ CẦN THANH TOÁN</span>
          <div class="text-xl font-extrabold text-rose-700">{{ formatVnd(summaryData.remaining_unpaid) }}</div>
          <div class="text-[11px] text-slate-500">Cần trả đúng hạn theo hợp đồng</div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-rose-200/80 shadow-2xs space-y-1 flex flex-col justify-between">
          <div>
            <span class="text-[11px] font-bold text-rose-800 uppercase tracking-wider block">SMART MATCHMAKER AI</span>
            <div class="text-base font-serif font-bold text-slate-900">Gợi Ý Theo Vibe {{ selectedVibeState }}</div>
          </div>
          <div class="text-[11px] text-slate-500 font-semibold flex items-center gap-1">
            <Sparkles class="w-3.5 h-3.5 text-rose-600" /> Tự động tính điểm % Match
          </div>
        </div>
      </div>

<<<<<<< HEAD
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
=======
      <!-- Real-time Search & Multi-Category Filter Bar -->
      <div class="p-5 rounded-3xl bg-white border border-rose-100 shadow-2xs space-y-3.5">
        <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
          <!-- Instant Search Box -->
          <div class="relative flex-1">
            <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input
              ref="searchInputRef"
              v-model="searchQuery"
              type="text"
              placeholder="Tìm kiếm đối tác theo tên, quận, địa chỉ..."
              class="w-full pl-10 pr-4 py-2 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400"
            />
            <button
              v-if="searchQuery"
              @click="searchQuery = ''"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold"
            >
              ✕
            </button>
          </div>

          <!-- Vibe selection -->
          <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
            <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider shrink-0">VIBE:</span>
            <button
              @click="filterRecommendationsByVibe('pastel')"
              class="px-3 py-1 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0"
              :class="selectedVibeState === 'pastel' ? 'bg-rose-600 text-white border-rose-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
            >
              🌸 Pastel
            </button>
            <button
              @click="filterRecommendationsByVibe('royal')"
              class="px-3 py-1 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0"
              :class="selectedVibeState === 'royal' ? 'bg-rose-600 text-white border-rose-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
            >
              🏛️ Royal Gold
            </button>
            <button
              @click="filterRecommendationsByVibe('garden')"
              class="px-3 py-1 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0"
              :class="selectedVibeState === 'garden' ? 'bg-rose-600 text-white border-rose-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
            >
              🌿 Botanical
            </button>
            <button
              @click="filterRecommendationsByVibe('minimalist')"
              class="px-3 py-1 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0"
              :class="selectedVibeState === 'minimalist' ? 'bg-rose-600 text-white border-rose-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
            >
              ⚡ Minimalist
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)
            </button>
          </div>
        </div>

<<<<<<< HEAD
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
=======
        <!-- Service Category Pill Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pt-2 border-t border-slate-100">
          <button
            @click="selectedCategoryFilter = 'all'"
            class="px-3 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0 flex items-center gap-1.5"
            :class="selectedCategoryFilter === 'all' ? 'bg-rose-950 text-white border-rose-950' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)
          >
            Tất cả dịch vụ <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20 text-current font-extrabold">{{ categoryCounts.all }}</span>
          </button>

          <button
            @click="selectedCategoryFilter = 'venue'"
            class="px-3 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0 flex items-center gap-1.5"
            :class="selectedCategoryFilter === 'venue' ? 'bg-rose-950 text-white border-rose-950' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            🏛️ Sảnh tiệc <span v-if="categoryCounts.venue" class="px-1.5 py-0.5 rounded-md text-[10px] bg-amber-100 text-amber-900 font-extrabold">{{ categoryCounts.venue }}</span>
          </button>

          <button
            @click="selectedCategoryFilter = 'studio'"
            class="px-3 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0 flex items-center gap-1.5"
            :class="selectedCategoryFilter === 'studio' ? 'bg-rose-950 text-white border-rose-950' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            📷 Chụp ảnh <span v-if="categoryCounts.studio" class="px-1.5 py-0.5 rounded-md text-[10px] bg-indigo-100 text-indigo-900 font-extrabold">{{ categoryCounts.studio }}</span>
          </button>

          <button
            @click="selectedCategoryFilter = 'makeup'"
            class="px-3 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0 flex items-center gap-1.5"
            :class="selectedCategoryFilter === 'makeup' ? 'bg-rose-950 text-white border-rose-950' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            💄 Trang điểm <span v-if="categoryCounts.makeup" class="px-1.5 py-0.5 rounded-md text-[10px] bg-rose-100 text-rose-900 font-extrabold">{{ categoryCounts.makeup }}</span>
          </button>

          <button
            @click="selectedCategoryFilter = 'florist'"
            class="px-3 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0 flex items-center gap-1.5"
            :class="selectedCategoryFilter === 'florist' ? 'bg-rose-950 text-white border-rose-950' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            🌸 Decor <span v-if="categoryCounts.florist" class="px-1.5 py-0.5 rounded-md text-[10px] bg-emerald-100 text-emerald-900 font-extrabold">{{ categoryCounts.florist }}</span>
          </button>

          <button
            @click="selectedCategoryFilter = 'attire'"
            class="px-3 py-1.5 rounded-xl text-xs font-bold transition border cursor-pointer shrink-0 flex items-center gap-1.5"
            :class="selectedCategoryFilter === 'attire' ? 'bg-rose-950 text-white border-rose-950' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
          >
            👗 Váy cưới <span v-if="categoryCounts.attire" class="px-1.5 py-0.5 rounded-md text-[10px] bg-purple-100 text-purple-900 font-extrabold">{{ categoryCounts.attire }}</span>
          </button>
        </div>
      </div>

      <!-- FULL MAP VIEW MODE -->
      <div v-if="viewMode === 'map'" class="space-y-4">
        <VendorMap
          :vendors="allMapVendors"
          :selected-vendor-id="selectedVendorId"
          height="680px"
          @select-vendor="openVendorDrawer"
          @book-vendor="bookRecommendedVendor"
          @toggle-fullscreen="toggleMapFullScreen"
        />
      </div>

      <!-- SPLIT VIEW MODE (DEFAULT): Cards List + Sticky Interactive Map -->
      <div v-else-if="viewMode === 'split'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Side: Cards & Recommendations -->
        <div class="lg:col-span-7 space-y-8">
          <!-- Active Booked Contracts Banner -->
          <div class="p-6 rounded-3xl bg-white border border-rose-100 shadow-2xs space-y-4">
            <div class="flex items-center justify-between border-b border-rose-100/60 pb-3">
              <h2 class="text-base font-serif font-bold text-rose-950 flex items-center gap-2">
                <Store class="w-5 h-5 text-rose-600" /> Hợp Đồng Đối Tác Đã Chốt ({{ filteredBookedVendors.length }})
              </h2>
              <span class="text-xs font-semibold text-slate-500">Tổng: <strong class="text-rose-950">{{ formatVnd(summaryData.total_contracts) }}</strong></span>
            </div>

            <div class="space-y-3">
<<<<<<< HEAD
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
=======
              <div
                v-for="v in filteredBookedVendors"
                :key="v.id"
                @click="openVendorDrawer(v)"
                class="p-4 rounded-2xl bg-[#FAF8F5] border border-slate-200/80 hover:border-rose-300 transition cursor-pointer flex items-center justify-between gap-4 group"
                :class="selectedVendorId === v.id ? 'ring-2 ring-rose-500 bg-rose-50/50' : ''"
              >
                <div class="space-y-1">
                  <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-rose-700 transition-colors">{{ v.name }}</h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-900 border border-rose-200">
                      {{ getCategoryLabel(v.category) }}
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 flex items-center gap-1">
                    <MapPin class="w-3.5 h-3.5 text-rose-500" /> {{ v.address || `${v.district || 'Quận 1'}, ${v.city || 'TP. Hồ Chí Minh'}` }}
                  </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                  <div class="text-right">
                    <div class="text-sm font-extrabold text-rose-950">{{ formatVnd(v.contract_amount) }}</div>
                    <div class="text-[11px] text-slate-500">Đã cọc: <span class="text-emerald-700 font-bold">{{ formatVnd(v.paid_amount) }}</span></div>
                  </div>
                  <button
                    @click.stop="openVendorDrawer(v)"
                    class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-rose-50 text-slate-700 transition cursor-pointer"
                    title="Xem chi tiết"
                  >
                    <Eye class="w-4 h-4 text-rose-600" />
                  </button>
                </div>
              </div>

              <div v-if="filteredBookedVendors.length === 0" class="text-xs text-slate-400 text-center py-4">
                Chưa có hợp đồng nào phù hợp bộ lọc.
              </div>
            </div>
          </div>

          <!-- AI Curated Recommendations Grid -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <h2 class="text-lg font-serif font-bold text-rose-950 flex items-center gap-2">
                <Sparkles class="w-5 h-5 text-rose-600" />
                Đối Tác Đề Xuất Phù Hợp Vibe Dâu Rể
              </h2>
              <span class="text-xs font-semibold text-slate-500">Hiển thị {{ filteredRecommendations.length }} gợi ý</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div
                v-for="rec in filteredRecommendations"
                :key="rec.id"
                @click="openVendorDrawer(rec)"
                class="p-5 rounded-3xl bg-white border border-rose-100 shadow-md hover:border-rose-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-4 cursor-pointer group"
                :class="selectedVendorId === rec.id ? 'ring-2 ring-rose-500 border-rose-400' : ''"
              >
                <div class="space-y-3">
                  <div class="relative h-40 rounded-2xl overflow-hidden bg-rose-50 border border-rose-100">
                    <img :src="rec.portfolio_images[0]" :alt="rec.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div class="absolute top-2.5 right-2.5 px-3 py-1 rounded-full bg-rose-950/90 text-white text-xs font-extrabold border border-rose-400/40 shadow-sm">
                      🔥 {{ rec.match_score }}% Match
                    </div>
                    <div class="absolute bottom-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-white/90 text-rose-950 text-[10px] font-bold">
                      {{ rec.category_name }}
                    </div>
                  </div>

                  <div>
                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                      {{ rec.vibe_label }}
                    </span>
                    <h3 class="text-base font-serif font-bold text-slate-900 group-hover:text-rose-700 transition-colors mt-1">
                      {{ rec.name }}
                    </h3>
                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                      <MapPin class="w-3.5 h-3.5 text-rose-500 shrink-0" /> {{ rec.address || `${rec.district}, ${rec.city}` }}
                    </p>
                  </div>
                </div>

                <div class="pt-3 border-t border-rose-100 flex items-center justify-between gap-2">
                  <div class="text-xs">
                    <span class="text-[10px] text-slate-400 block">KHOẢNG GIÁ</span>
                    <span class="font-bold text-rose-950 text-xs">{{ rec.price_label }}</span>
                  </div>

                  <div class="flex items-center gap-2">
                    <button
                      @click.stop="openVendorDrawer(rec)"
                      class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition cursor-pointer"
                      title="Xem chi tiết"
                    >
                      <Eye class="w-4 h-4 text-slate-600" />
                    </button>
                    <button
                      @click.stop="bookRecommendedVendor(rec)"
                      class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-sm transition cursor-pointer"
                    >
                      + Chốt
                    </button>
                  </div>
                </div>
              </div>

              <div v-if="filteredRecommendations.length === 0" class="col-span-2 p-8 text-center text-slate-400 text-xs bg-white rounded-3xl border border-rose-100">
                Không tìm thấy đối tác đề xuất nào phù hợp với bộ lọc tìm kiếm "{{ searchQuery }}".
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)
              </div>
            </div>
          </div>
        </div>
<<<<<<< HEAD

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
=======
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)

        <!-- Right Side: Sticky Cluster Map -->
        <div class="lg:col-span-5 sticky top-6 space-y-4">
          <VendorMap
            :vendors="allMapVendors"
            :selected-vendor-id="selectedVendorId"
            height="620px"
            @select-vendor="openVendorDrawer"
            @book-vendor="bookRecommendedVendor"
            @toggle-fullscreen="toggleMapFullScreen"
          />

          <!-- Quick Tip Card -->
          <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200 text-xs text-amber-900 space-y-1">
            <div class="font-bold flex items-center gap-1.5 text-amber-950">
              💡 Mẹo tương tác bản đồ & phím tắt
            </div>
            <p class="text-[11px] leading-relaxed text-amber-800">
              Sử dụng các nút <strong>"BAY TỚI KHU VỰC"</strong> trên góc bản đồ để di chuyển nhanh tới Quận 1, Quận 3, Phú Nhuận, Bình Thạnh hay Quận 7. 
              Nhấn <strong>"M"</strong> để phóng to full bản đồ, <strong>"S"</strong> để về chia đôi màn hình.
            </p>
          </div>
        </div>
      </div>

      <!-- TABLE LEDGER VIEW MODE -->
      <div v-else-if="viewMode === 'table'" class="space-y-6">
        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
          <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900">Danh bạ Nhà cung cấp & Hợp đồng Active</h2>
            <span class="text-xs text-slate-500">Hiển thị {{ filteredBookedVendors.length }} đối tác</span>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-medium uppercase tracking-wider">
                <tr>
                  <th class="px-6 py-3">Tên Nhà Cung Cấp</th>
                  <th class="px-6 py-3">Phân Loại</th>
                  <th class="px-6 py-3">Địa Chỉ / Tọa Độ</th>
                  <th class="px-6 py-3">Giá Trị Hợp Đồng</th>
                  <th class="px-6 py-3">Đã Trả</th>
                  <th class="px-6 py-3">Nợ Còn Lại</th>
                  <th class="px-6 py-3">Trạng Thái</th>
                  <th class="px-6 py-3 text-right">Thao Tác</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-slate-800">
                <tr v-for="vendor in filteredBookedVendors" :key="vendor.id" class="hover:bg-slate-50/80 transition">
                  <td class="px-6 py-4 font-semibold text-slate-900">
                    <div class="flex items-center gap-2">
                      <span class="text-sm">{{ getCategoryIcon(vendor.category) }}</span>
                      <span>{{ vendor.name }}</span>
                    </div>
                    <div class="text-[11px] font-normal text-slate-400 mt-0.5" v-if="vendor.due_date">
                      Hạn trả: {{ vendor.due_date }}
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="px-2 py-0.5 rounded border border-slate-200 text-slate-600 bg-slate-50">
                      {{ getCategoryLabel(vendor.category) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-slate-600">
                    <div>{{ vendor.address || `${vendor.district || 'Quận 1'}, ${vendor.city || 'TP. Hồ Chí Minh'}` }}</div>
                    <div class="text-[10px] text-rose-600" v-if="vendor.latitude && vendor.longitude">
                      📍 Lat: {{ vendor.latitude }}, Lng: {{ vendor.longitude }}
                    </div>
                  </td>
                  <td class="px-6 py-4 font-medium">{{ formatVnd(vendor.contract_amount) }}</td>
                  <td class="px-6 py-4 text-emerald-600 font-medium">{{ formatVnd(vendor.paid_amount) }}</td>
                  <td class="px-6 py-4 text-rose-600 font-medium">{{ formatVnd(vendor.unpaid_balance) }}</td>
                  <td class="px-6 py-4">
                    <span class="px-2 py-0.5 rounded-full border text-[11px] font-medium" :class="getStatusBadgeClass(vendor.payment_status)">
                      {{ getStatusLabel(vendor.payment_status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                    <button
                      @click="openVendorDrawer(vendor)"
                      class="px-2.5 py-1 text-[11px] font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-md transition cursor-pointer"
                    >
                      Chi tiết
                    </button>
                    <button
                      v-if="vendor.payment_status !== 'fully_paid'"
                      @click="openPaymentModal(vendor)"
                      class="px-2.5 py-1 text-[11px] font-medium text-white bg-emerald-600 hover:bg-emerald-500 rounded-md transition cursor-pointer shadow-xs"
                    >
                      + Trả thêm
                    </button>
                  </td>
                </tr>

                <tr v-if="filteredBookedVendors.length === 0">
                  <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                    Chưa có nhà cung cấp nào. Bấm nút "+ Nhập Vendor" để khởi tạo.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- Slide-over Vendor Detail Drawer -->
    <VendorDetailDrawer
      :vendor="drawerVendor"
      :is-open="isDrawerOpen"
      @close="isDrawerOpen = false"
      @book-vendor="bookRecommendedVendor"
      @record-payment="openPaymentModal"
      @fly-to-map="(v) => { isDrawerOpen = false; selectVendorOnMap(v); }"
    />

    <!-- Create Vendor Modal -->
    <div v-if="isAddModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs p-4">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
          <h3 class="text-sm font-bold text-slate-900">Thêm Nhà Cung Cấp Mới & Tọa Độ Bản Đồ</h3>
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
                <option value="venue">Sảnh tiệc & Nhà hàng</option>
                <option value="studio">Chụp ảnh & Quay phim</option>
                <option value="catering">Ẩm thực & Đồ uống</option>
                <option value="makeup">Trang điểm & Làm tóc</option>
                <option value="florist">Hoa tươi & Trang trí</option>
                <option value="attire">Váy cưới & Vest</option>
                <option value="other">Khác</option>
              </select>
            </div>
            <div>
              <label class="block font-medium text-slate-700 mb-1">Quận / Khu vực (Kèm tọa độ chuẩn)</label>
              <select
                v-model="newVendorForm.district"
                @change="onDistrictChange"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-rose-500/20"
              >
                <option value="Quận 1">Quận 1</option>
                <option value="Quận 3">Quận 3</option>
                <option value="Quận 7">Quận 7</option>
                <option value="Phú Nhuận">Phú Nhuận</option>
                <option value="Bình Thạnh">Bình Thạnh</option>
                <option value="Tân Bình">Tân Bình</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block font-medium text-slate-700 mb-1">Địa chỉ chi tiết</label>
            <input
              v-model="newVendorForm.address"
              type="text"
              placeholder="Ví dụ: 194 Hoàng Văn Thụ, Phường 9, Phú Nhuận"
              class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900"
            />
          </div>

          <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
            <div>
              <label class="block font-medium text-slate-600 text-[11px] mb-1">Vĩ độ (Latitude)</label>
              <input
                v-model.number="newVendorForm.latitude"
                type="number"
                step="0.0001"
                class="w-full px-2.5 py-1.5 border border-slate-300 rounded-md text-slate-900 bg-white"
              />
            </div>
            <div>
              <label class="block font-medium text-slate-600 text-[11px] mb-1">Kinh độ (Longitude)</label>
              <input
                v-model.number="newVendorForm.longitude"
                type="number"
                step="0.0001"
                class="w-full px-2.5 py-1.5 border border-slate-300 rounded-md text-slate-900 bg-white"
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
              class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-lg font-bold disabled:opacity-50 cursor-pointer shadow-sm"
            >
              Lưu Nhà Cung Cấp & Tọa Độ
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
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium disabled:opacity-50 cursor-pointer"
            >
              Xác Nhận Giải Ngân
            </button>
          </div>
        </form>
      </div>
    </div>

<<<<<<< HEAD
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
=======
    <!-- FULLSCREEN 2-COLUMN MAP MODAL OVERLAY -->
    <div v-if="isMapFullScreen" class="fixed inset-0 z-50 bg-slate-950 flex flex-col lg:flex-row overflow-hidden animate-in fade-in duration-300">
      <!-- LEFT COLUMN: Search, Filters, Selected Vendor Info & Recommendation List (35% width) -->
      <div class="w-full lg:w-[420px] xl:w-[460px] h-full bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 z-20 shadow-2xl">
        <!-- Sidebar Header -->
        <div class="p-5 border-b border-slate-800 flex items-center justify-between bg-slate-900/90 backdrop-blur-md">
          <div class="flex items-center gap-2.5">
            <span class="p-2 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30">🗺️</span>
            <div>
              <h2 class="text-sm font-bold text-white font-serif">Bản Đồ Đối Tác Cưới Full Screen</h2>
              <p class="text-[11px] text-slate-400">Hiển thị {{ filteredRecommendations.length }} địa điểm tại {{ selectedLocationState }}</p>
            </div>
          </div>

          <button 
            @click="isMapFullScreen = false" 
            class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition cursor-pointer font-bold text-xs"
            title="Đóng Full Screen (Esc)"
          >
            ✕ Đóng (Esc)
          </button>
        </div>

        <!-- Sidebar Search & Category Filter Pills -->
        <div class="p-4 border-b border-slate-800 space-y-3 bg-slate-900/50">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Tìm theo tên đối tác, quận, địa chỉ..." 
            class="w-full px-3.5 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-rose-500"
          />

          <div class="flex gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            <button 
              @click="selectedCategoryFilter = 'all'" 
              class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition cursor-pointer shrink-0"
              :class="selectedCategoryFilter === 'all' ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-800 text-slate-400 border-slate-700 hover:bg-slate-700'"
            >
              Tất cả
            </button>
            <button 
              @click="selectedCategoryFilter = 'venue'" 
              class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition cursor-pointer shrink-0"
              :class="selectedCategoryFilter === 'venue' ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-800 text-slate-400 border-slate-700 hover:bg-slate-700'"
            >
              🏛️ Sảnh tiệc
            </button>
            <button 
              @click="selectedCategoryFilter = 'studio'" 
              class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition cursor-pointer shrink-0"
              :class="selectedCategoryFilter === 'studio' ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-800 text-slate-400 border-slate-700 hover:bg-slate-700'"
            >
              📷 Quay chụp
            </button>
            <button 
              @click="selectedCategoryFilter = 'makeup'" 
              class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition cursor-pointer shrink-0"
              :class="selectedCategoryFilter === 'makeup' ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-800 text-slate-400 border-slate-700 hover:bg-slate-700'"
            >
              💄 Makeup
            </button>
          </div>
        </div>

        <!-- Sidebar Content: Selected Vendor Detail Card OR List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
          <!-- Selected Vendor Information Card -->
          <div v-if="drawerVendor" class="p-4 rounded-2xl bg-slate-800 border-2 border-rose-500/60 shadow-xl space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-md bg-rose-500/20 text-rose-300 border border-rose-500/30">
                📌 THÔNG TIN ĐỐI TÁC ĐANG CHỌN
              </span>
              <button @click="drawerVendor = null" class="text-xs text-slate-400 hover:text-white font-bold">✕ Bỏ chọn</button>
            </div>

            <div class="flex items-start gap-3">
              <img 
                :src="drawerVendor.portfolio_images?.[0] || 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=400&q=80'" 
                class="w-16 h-16 rounded-xl object-cover border border-slate-700 shrink-0"
              />
              <div class="space-y-1">
                <h3 class="font-serif font-bold text-white text-sm leading-tight">{{ drawerVendor.name }}</h3>
                <p class="text-xs text-rose-400 font-semibold">{{ drawerVendor.category_name || drawerVendor.category }} • {{ drawerVendor.district || 'TP.HCM' }}</p>
                <div class="text-[11px] text-amber-400 font-bold">⭐ {{ drawerVendor.rating || 4.9 }} / 5.0 • Match {{ drawerVendor.match_score || 95 }}%</div>
              </div>
            </div>

            <p class="text-xs text-slate-300 flex items-start gap-1 font-medium leading-relaxed">
              📍 {{ drawerVendor.address || 'Quận 1, TP. Hồ Chí Minh' }}
            </p>

            <div class="pt-3 border-t border-slate-700 flex items-center justify-between">
              <span class="text-xs font-bold text-amber-300">{{ drawerVendor.price_label || '150 - 350 Triệu' }}</span>
              <button 
                @click="bookRecommendedVendor(drawerVendor)" 
                class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-md transition cursor-pointer"
              >
                + Chốt Hợp Đồng
              </button>
            </div>
          </div>

          <!-- List of Vendor Cards in Sidebar -->
          <div class="space-y-2.5">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Danh Sách Đối Tác Cùng Khu Vực ({{ filteredRecommendations.length }})</div>
            <div 
              v-for="rec in filteredRecommendations" 
              :key="rec.id"
              @click="selectedVendorId = rec.id; drawerVendor = rec"
              class="p-3.5 rounded-xl border transition cursor-pointer flex items-center justify-between gap-3"
              :class="selectedVendorId === rec.id ? 'bg-slate-800 border-rose-500 shadow-md ring-1 ring-rose-500' : 'bg-slate-900/60 border-slate-800 hover:bg-slate-800/80'"
            >
              <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full" :class="selectedVendorId === rec.id ? 'bg-rose-500 animate-ping' : 'bg-slate-600'"></span>
                <div>
                  <h4 class="text-xs font-bold text-white leading-tight">{{ rec.name }}</h4>
                  <p class="text-[11px] text-slate-400">{{ rec.category_name || rec.category }} • {{ rec.district }}</p>
                </div>
              </div>

              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-950 text-rose-300 border border-rose-800 shrink-0">
                {{ rec.match_score }}% Match
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: Large Interactive Map Canvas (65% to 70% width) -->
      <div class="flex-1 h-full min-h-[500px] relative">
        <VendorMap
          :vendors="allMapVendors"
          :selected-vendor-id="selectedVendorId"
          height="100%"
          :is-full-screen="true"
          @select-vendor="openVendorDrawer"
          @book-vendor="bookRecommendedVendor"
          @toggle-fullscreen="isMapFullScreen = false"
        />
      </div>
    </div>
>>>>>>> 8836845 (feat(vendor): add leaflet vendor map with district fly-to and fullscreen crm view)
  </WorkspaceLayout>
</template>
