<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { 
  Gift, 
  Plus, 
  Check, 
  Trash2, 
  DollarSign, 
  QrCode, 
  User, 
  CheckCircle2, 
  CreditCard, 
  HeartHandshake, 
  MessageSquare,
  Sparkles,
  Share2,
  MailCheck
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';

interface GiftLogItem {
  id: string;
  giver_name: string;
  relationship: 'groom_friend' | 'bride_friend' | 'family' | 'colleague' | 'other';
  amount: number;
  gift_type: 'cash' | 'transfer' | 'gift_item';
  gift_item_description?: string;
  wish_message?: string;
  thank_you_sent: boolean;
  created_at?: string;
}

interface WorkspaceInfo {
  name?: string;
  groom_name?: string;
  bride_name?: string;
  wedding_date?: string;
}

interface Stats {
  totalAmount: number;
  totalGivers: number;
  cashCount: number;
  transferCount: number;
  thankYouSentCount: number;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  giftLogs: GiftLogItem[];
  stats: Stats;
}>();

const localLogs = ref<GiftLogItem[]>([...props.giftLogs]);
const activeRelationshipFilter = ref<string>('all');
const showAddModal = ref(false);
const showVietQrModal = ref(false);

const newGiverName = ref('');
const newRelationship = ref<'groom_friend' | 'bride_friend' | 'family' | 'colleague' | 'other'>('groom_friend');
const newAmount = ref<number | null>(null);
const newGiftType = ref<'cash' | 'transfer' | 'gift_item'>('cash');
const newGiftDescription = ref('');
const newWishMessage = ref('');
const isSubmitting = ref(false);
const toastMessage = ref<string | null>(null);

const bankName = ref('MBBank');
const accountNumber = ref('0901234567');
const accountName = ref('NGUYEN HOANG QUOC TRUNG');

const filteredLogs = computed(() => {
  if (activeRelationshipFilter.value === 'all') return localLogs.value;
  return localLogs.value.filter(l => l.relationship === activeRelationshipFilter.value);
});

const formatCurrency = (val: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
};

const toggleThankYou = async (log: GiftLogItem) => {
  log.thank_you_sent = !log.thank_you_sent;
  try {
    const res = await fetch(`/wedding/gift-log/${log.id}/thank-you`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      }
    });
    const data = await res.json();
    if (data.success) {
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error toggling thank you status:', err);
  }
};

const handleAddLog = async () => {
  if (!newGiverName.value || newAmount.value === null) return;
  isSubmitting.value = true;

  try {
    const res = await fetch('/wedding/gift-log', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      },
      body: JSON.stringify({
        giver_name: newGiverName.value,
        relationship: newRelationship.value,
        amount: newAmount.value,
        gift_type: newGiftType.value,
        gift_item_description: newGiftDescription.value,
        wish_message: newWishMessage.value,
      })
    });
    const data = await res.json();
    if (data.success) {
      localLogs.value.unshift(data.giftLog);
      showAddModal.value = false;
      newGiverName.value = '';
      newAmount.value = null;
      newGiftDescription.value = '';
      newWishMessage.value = '';
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error adding gift log:', err);
  } finally {
    isSubmitting.value = false;
  }
};

const deleteLog = async (log: GiftLogItem) => {
  if (!confirm('Bạn có chắc chắn muốn xóa mục ghi nhận này khỏi Sổ Vàng?')) return;
  try {
    const res = await fetch(`/wedding/gift-log/${log.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      }
    });
    const data = await res.json();
    if (data.success) {
      localLogs.value = localLogs.value.filter(l => l.id !== log.id);
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error deleting gift log:', err);
  }
};

const showToast = (msg: string) => {
  toastMessage.value = msg;
  setTimeout(() => { toastMessage.value = null; }, 3000);
};

const getRelationshipLabel = (rel: string) => {
  switch (rel) {
    case 'groom_friend': return 'Bạn Chú Rể';
    case 'bride_friend': return 'Bạn Cô Dâu';
    case 'family': return 'Họ Hàng Gia Đình';
    case 'colleague': return 'Đồng Nghiệp';
    default: return 'Khách Mời';
  }
};

const vietQrUrl = computed(() => {
  return `https://img.vietqr.io/image/${bankName.value}-${accountNumber.value}-compact2.png?amount=0&addInfo=Mung%20Cuoi%20Dau%20Re&accountName=${encodeURIComponent(accountName.value)}`;
});
</script>

<template>
  <Head title="Sổ Vàng Mừng Cưới & VietQR — Eloria OS" />

  <WorkspaceLayout title="Sổ Vàng Mừng Cưới & VietQR" active-nav="gift-log">
    <main class="max-w-6xl mx-auto px-6 py-8 space-y-8">
      
      <!-- Top Banner Header -->
      <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xl shadow-rose-900/5 space-y-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 border-b border-slate-100 pb-6">
          <div class="space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-900 text-[11px] font-extrabold uppercase tracking-wider">
              <Gift class="w-3.5 h-3.5 text-rose-600" /> SỔ VÀNG MỪNG CƯỚI ĐIỆN TỬ
            </div>

            <div class="space-y-2">
              <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">
                Quản Lý Tiền Mừng & Sổ Vàng Mừng Cưới
              </h1>
              
              <div class="flex items-center gap-3 flex-wrap pt-1">
                <div class="px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold flex items-center gap-2">
                  <span class="text-rose-700 font-extrabold uppercase text-[10px] tracking-wider">Chú Rể:</span>
                  <span class="text-slate-900 font-bold">{{ workspace?.groom_name || 'Nguyễn Hoàng Quốc Trung' }}</span>
                </div>
                <span class="text-slate-300 font-bold">&</span>
                <div class="px-3 py-1.5 rounded-xl bg-pink-50 border border-pink-200 text-xs font-bold flex items-center gap-2">
                  <span class="text-pink-700 font-extrabold uppercase text-[10px] tracking-wider">Cô Dâu:</span>
                  <span class="text-slate-900 font-bold">{{ workspace?.bride_name || 'Lê Thị Hồng Vân' }}</span>
                </div>
              </div>
            </div>

            <p class="text-xs md:text-sm text-slate-600 font-medium">
              Ghi nhận mừng cưới minh bạch, quản lý chuyển khoản VietQR và gửi tin nhắn Cảm Ơn tự động đến khách mời sau lễ cưới.
            </p>
          </div>

          <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <button 
              @click="showVietQrModal = true" 
              class="px-4 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center gap-2 cursor-pointer shadow-md"
            >
              <QrCode class="w-4 h-4 text-rose-300" /> Mã VietQR Chuyển Khoản
            </button>

            <button 
              @click="showAddModal = true" 
              class="px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-2 cursor-pointer"
            >
              <Plus class="w-4 h-4" /> Ghi Nhận Mừng Cưới
            </button>
          </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-medium text-slate-700">
          <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ formatCurrency(localLogs.reduce((acc, l) => acc + Number(l.amount), 0)) }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Tổng Tiền Mừng Thu Về</span>
            </div>
            <DollarSign class="w-6 h-6 text-rose-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localLogs.length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Lượt Khách Mừng Cưới</span>
            </div>
            <Gift class="w-6 h-6 text-amber-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localLogs.filter(l => l.gift_type === 'transfer').length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Mừng Qua Chuyển Khoản</span>
            </div>
            <CreditCard class="w-6 h-6 text-blue-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localLogs.filter(l => l.thank_you_sent).length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Đã Gửi Lời Cảm Ơn</span>
            </div>
            <MailCheck class="w-6 h-6 text-emerald-500" />
          </div>
        </div>
      </div>

      <!-- Toast Notification -->
      <div v-if="toastMessage" class="p-4 rounded-2xl bg-emerald-600 text-white font-bold text-xs shadow-lg flex items-center justify-between animate-fade-in">
        <span>{{ toastMessage }}</span>
        <button @click="toastMessage = null" class="font-bold">✕</button>
      </div>

      <!-- Filter Tabs -->
      <div class="flex items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-rose-100 shadow-2xs overflow-x-auto">
        <div class="flex items-center gap-2 shrink-0">
          <button 
            @click="activeRelationshipFilter = 'all'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeRelationshipFilter === 'all' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Tất Cả Sổ Vàng ({{ localLogs.length }})
          </button>
          
          <button 
            @click="activeRelationshipFilter = 'groom_friend'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeRelationshipFilter === 'groom_friend' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Bạn Chú Rể
          </button>

          <button 
            @click="activeRelationshipFilter = 'bride_friend'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeRelationshipFilter === 'bride_friend' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Bạn Cô Dâu
          </button>

          <button 
            @click="activeRelationshipFilter = 'family'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeRelationshipFilter === 'family' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Họ Hàng Gia Đình
          </button>

          <button 
            @click="activeRelationshipFilter = 'colleague'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeRelationshipFilter === 'colleague' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Đồng Nghiệp
          </button>
        </div>
      </div>

      <!-- Gift Log Table List -->
      <div class="bg-white rounded-3xl border border-rose-100 shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-rose-50/60 border-b border-rose-100 text-slate-700 font-bold uppercase tracking-wider text-[10px]">
              <tr>
                <th class="py-3.5 px-6">Người Mừng Cưới</th>
                <th class="py-3.5 px-4">Mối Quan Hệ</th>
                <th class="py-3.5 px-4">Số Tiền / Quà Tặng</th>
                <th class="py-3.5 px-4">Hình Thức</th>
                <th class="py-3.5 px-4">Trạng Thái Cảm Ơn</th>
                <th class="py-3.5 px-6 text-right">Thao Tác</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
              <tr v-for="log in filteredLogs" :key="log.id" class="hover:bg-rose-50/30 transition">
                <td class="py-4 px-6 font-bold text-slate-900">
                  {{ log.giver_name }}
                  <span v-if="log.wish_message" class="block text-[11px] font-normal text-slate-500 truncate max-w-xs mt-0.5">
                    "{{ log.wish_message }}"
                  </span>
                </td>
                <td class="py-4 px-4">
                  <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 font-bold text-[10px]">
                    {{ getRelationshipLabel(log.relationship) }}
                  </span>
                </td>
                <td class="py-4 px-4 font-black text-rose-900 text-sm">
                  {{ formatCurrency(Number(log.amount)) }}
                  <span v-if="log.gift_item_description" class="block text-[11px] font-normal text-slate-600">
                    + {{ log.gift_item_description }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <span v-if="log.gift_type === 'transfer'" class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-900 font-extrabold text-[10px] border border-blue-200">
                    Chuyển Khoản
                  </span>
                  <span v-else-if="log.gift_type === 'cash'" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-900 font-extrabold text-[10px] border border-emerald-200">
                    Phong Bì Tiền Mặt
                  </span>
                  <span v-else class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-900 font-extrabold text-[10px] border border-amber-200">
                    Quà Tặng Hiện Vật
                  </span>
                </td>
                <td class="py-4 px-4">
                  <button 
                    @click="toggleThankYou(log)"
                    class="px-3 py-1.5 rounded-xl text-[11px] font-bold border transition flex items-center gap-1 cursor-pointer"
                    :class="log.thank_you_sent ? 'bg-emerald-50 text-emerald-900 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                  >
                    <CheckCircle2 v-if="log.thank_you_sent" class="w-3.5 h-3.5 text-emerald-600" />
                    <span>{{ log.thank_you_sent ? 'Đã Cảm Ơn' : 'Chưa Cảm Ơn' }}</span>
                  </button>
                </td>
                <td class="py-4 px-6 text-right">
                  <button 
                    @click="deleteLog(log)"
                    class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                    title="Xóa khỏi Sổ Vàng"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Add Gift Log Modal -->
      <div v-if="showAddModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white p-6 rounded-3xl border border-rose-100 shadow-2xl space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              <Gift class="w-5 h-5 text-rose-600" /> Ghi Nhận Mừng Cưới Về Sổ Vàng
            </h3>
            <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
          </div>

          <div class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Tên Người Mừng Cưới *</label>
              <input v-model="newGiverName" type="text" placeholder="VD: Bác Hai, Anh Tuấn..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1.5">Mối Quan Hệ *</label>
                <select v-model="newRelationship" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-hidden">
                  <option value="groom_friend">Bạn Chú Rể</option>
                  <option value="bride_friend">Bạn Cô Dâu</option>
                  <option value="family">Họ Hàng Gia Đình</option>
                  <option value="colleague">Đồng Nghiệp</option>
                  <option value="other">Khách Mời Khác</option>
                </select>
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1.5">Hình Thức *</label>
                <select v-model="newGiftType" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-hidden">
                  <option value="cash">Phong Bì Tiền Mặt</option>
                  <option value="transfer">Chuyển Khoản VietQR</option>
                  <option value="gift_item">Quà Tặng Hiện Vật</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Số Tiền Mừng (VNĐ) *</label>
              <input v-model.number="newAmount" type="number" placeholder="5000000" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-rose-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Mô Tả Quà Tặng (Nếu có)</label>
              <input v-model="newGiftDescription" type="text" placeholder="VD: Bộ chỉ vàng SJC, Tủ lạnh..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Lời Chúc / Ghi Chú</label>
              <textarea v-model="newWishMessage" rows="2" placeholder="Lời chúc mừng cưới..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400"></textarea>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <button @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">Hủy Bỏ</button>
            <button @click="handleAddLog" :disabled="isSubmitting || !newGiverName || newAmount === null" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 cursor-pointer disabled:opacity-50">
              {{ isSubmitting ? 'Đang Lưu...' : 'Lưu Vào Sổ Vàng' }}
            </button>
          </div>
        </div>
      </div>

      <!-- VietQR Settings Modal -->
      <div v-if="showVietQrModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white p-6 rounded-3xl border border-rose-100 shadow-2xl text-center space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              <QrCode class="w-5 h-5 text-rose-600" /> Mã VietQR Mừng Cưới Chuyển Khoản
            </h3>
            <button @click="showVietQrModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
          </div>

          <div class="space-y-3 text-xs text-left">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Ngân Hàng *</label>
              <input v-model="bankName" type="text" placeholder="MBBank, Vietcombank, Techcombank..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Số Tài Khoản *</label>
              <input v-model="accountNumber" type="text" placeholder="0901234567" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Tên Chủ Tài Khoản *</label>
              <input v-model="accountName" type="text" placeholder="NGUYEN HOANG QUOC TRUNG" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase" />
            </div>

            <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 text-center space-y-2 mt-4">
              <span class="text-[11px] font-bold text-rose-900 uppercase tracking-wider block">Xem Trước Mã VietQR Mừng Cưới</span>
              <img :src="vietQrUrl" alt="VietQR Mừng Cưới" class="w-44 h-44 mx-auto rounded-xl shadow-xs bg-white p-2 border border-slate-200" />
            </div>
          </div>

          <button @click="showVietQrModal = false" class="w-full py-2.5 rounded-xl bg-rose-600 text-white text-xs font-bold shadow-md hover:bg-rose-500 transition">
            Hoàn Tất Cấu Hình VietQR
          </button>
        </div>
      </div>

    </main>
  </WorkspaceLayout>
</template>
