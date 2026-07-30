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

const filterRecommendations = (vibe: string) => {
  selectedVibeState.value = vibe;
  router.get('/wedding/vendors', { vibe, location: selectedLocationState.value }, { preserveState: true, preserveScroll: true });
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

      <!-- Curated Recommendations Grid -->
      <div class="space-y-6">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-serif font-bold text-rose-950 flex items-center gap-2">
            <Sparkles class="w-5 h-5 text-rose-600" />
            Danh Sách Đối Tác Ghép Đôi Cao Nhất (Highest Match Score)
          </h2>
          <span class="text-xs font-semibold text-slate-500">Hiển thị {{ recommendations.length }} đối tác chọn lọc</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="rec in recommendations"
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
            <div class="pt-4 border-t border-rose-100 flex items-center justify-between gap-3">
              <div class="text-xs">
                <span class="text-[10px] text-slate-400 block">KHOẢNG GIÁ DỰ KIẾN</span>
                <span class="font-bold text-rose-950 text-xs">{{ rec.price_label }}</span>
              </div>

              <button
                @click="bookRecommendedVendor(rec)"
                class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer"
              >
                + Chốt Vendor
              </button>
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

    <!-- Grounded AI Drawer -->
    <GroundedAiDrawer :is-open="isAiDrawerOpen" @close="isAiDrawerOpen = false" />
  </WorkspaceLayout>
</template>
