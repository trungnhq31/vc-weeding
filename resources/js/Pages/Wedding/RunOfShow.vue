<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { 
  Clock, 
  Plus, 
  Check, 
  Trash2, 
  MapPin, 
  User, 
  Phone, 
  Sun, 
  Moon, 
  Sparkles, 
  FileText,
  Calendar,
  CheckCircle2,
  ListTodo
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';

interface RunOfShowItem {
  id: string;
  session_type: 'morning_ceremony' | 'evening_reception' | 'party';
  time_slot: string;
  title: string;
  description?: string;
  person_in_charge?: string;
  pic_phone?: string;
  location_note?: string;
  is_completed: boolean;
  order_index: number;
}

interface WorkspaceInfo {
  name?: string;
  groom_name?: string;
  bride_name?: string;
  wedding_date?: string;
}

interface Stats {
  totalItems: number;
  completedCount: number;
  morningCount: number;
  eveningCount: number;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  items: RunOfShowItem[];
  stats: Stats;
}>();

const localItems = ref<RunOfShowItem[]>([...props.items]);
const activeTab = ref<'all' | 'morning_ceremony' | 'evening_reception' | 'party'>('all');
const showAddModal = ref(false);

const newSessionType = ref<'morning_ceremony' | 'evening_reception' | 'party'>('morning_ceremony');
const newTimeSlot = ref('');
const newTitle = ref('');
const newDescription = ref('');
const newPic = ref('');
const newPicPhone = ref('');
const newLocation = ref('');
const isSubmitting = ref(false);
const toastMessage = ref<string | null>(null);

const filteredItems = computed(() => {
  if (activeTab.value === 'all') return localItems.value;
  return localItems.value.filter(i => i.session_type === activeTab.value);
});

const toggleItem = async (item: RunOfShowItem) => {
  item.is_completed = !item.is_completed;
  try {
    const res = await fetch(`/wedding/run-of-show/${item.id}/toggle`, {
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
    console.error('Error toggling item:', err);
  }
};

const handleAddItem = async () => {
  if (!newTimeSlot.value || !newTitle.value) return;
  isSubmitting.value = true;

  try {
    const res = await fetch('/wedding/run-of-show', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      },
      body: JSON.stringify({
        session_type: newSessionType.value,
        time_slot: newTimeSlot.value,
        title: newTitle.value,
        description: newDescription.value,
        person_in_charge: newPic.value,
        pic_phone: newPicPhone.value,
        location_note: newLocation.value,
      })
    });
    const data = await res.json();
    if (data.success) {
      localItems.value.push(data.item);
      showAddModal.value = false;
      newTimeSlot.value = '';
      newTitle.value = '';
      newDescription.value = '';
      newPic.value = '';
      newPicPhone.value = '';
      newLocation.value = '';
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error adding item:', err);
  } finally {
    isSubmitting.value = false;
  }
};

const deleteItem = async (item: RunOfShowItem) => {
  if (!confirm('Bạn có chắc chắn muốn xóa hạng mục này khỏi kịch bản?')) return;
  try {
    const res = await fetch(`/wedding/run-of-show/${item.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      }
    });
    const data = await res.json();
    if (data.success) {
      localItems.value = localItems.value.filter(i => i.id !== item.id);
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error deleting item:', err);
  }
};

const showToast = (msg: string) => {
  toastMessage.value = msg;
  setTimeout(() => { toastMessage.value = null; }, 3000);
};

const getSessionBadge = (type: string) => {
  switch (type) {
    case 'morning_ceremony': return { text: 'Lễ Gia Tiên Sáng', class: 'bg-amber-100 text-amber-900 border-amber-200' };
    case 'evening_reception': return { text: 'Tiệc Cưới Tối', class: 'bg-rose-100 text-rose-900 border-rose-200' };
    case 'party': return { text: 'Giao Lưu Party', class: 'bg-blue-100 text-blue-900 border-blue-200' };
    default: return { text: 'Kịch Bản', class: 'bg-slate-100 text-slate-800 border-slate-200' };
  }
};
</script>

<template>
  <Head title="Kịch Bản Ngày Cưới & Phân Công — Eloria OS" />

  <WorkspaceLayout title="Kịch Bản Ngày Cưới & Phân Công" active-nav="run-of-show">
    <main class="max-w-5xl mx-auto px-6 py-8 space-y-8">
      
      <!-- Top Banner Header -->
      <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xl shadow-rose-900/5 space-y-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 border-b border-slate-100 pb-6">
          <div class="space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-900 text-[11px] font-extrabold uppercase tracking-wider">
              <Clock class="w-3.5 h-3.5 text-rose-600" /> KỊCH BẢN NGÀY CƯỚI CHI TIẾT
            </div>

            <div class="space-y-2">
              <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">
                Kịch Bản Diễn Biến & Phân Công PIC
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
              Lập kịch bản diễn biến theo từng mốc giờ trong ngày cưới, phân công người phụ trách (PIC) cho MC, chụp ảnh và ban điều hành tiệc.
            </p>
          </div>

          <button 
            @click="showAddModal = true" 
            class="px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-2 cursor-pointer shrink-0"
          >
            <Plus class="w-4 h-4" /> Thêm Hạng Mục Kịch Bản
          </button>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-medium text-slate-700">
          <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localItems.length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Tổng Hạng Mục Kịch Bản</span>
            </div>
            <ListTodo class="w-6 h-6 text-rose-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localItems.filter(i => i.is_completed).length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Đã Hoàn Thành</span>
            </div>
            <CheckCircle2 class="w-6 h-6 text-emerald-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localItems.filter(i => i.session_type === 'morning_ceremony').length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Lễ Gia Tiên Buổi Sáng</span>
            </div>
            <Sun class="w-6 h-6 text-amber-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localItems.filter(i => i.session_type === 'evening_reception').length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Tiệc Cưới Buổi Tối</span>
            </div>
            <Moon class="w-6 h-6 text-blue-500" />
          </div>
        </div>
      </div>

      <!-- Toast Notification -->
      <div v-if="toastMessage" class="p-4 rounded-2xl bg-emerald-600 text-white font-bold text-xs shadow-lg flex items-center justify-between animate-fade-in">
        <span>{{ toastMessage }}</span>
        <button @click="toastMessage = null" class="font-bold">✕</button>
      </div>

      <!-- Session Filter Tabs -->
      <div class="flex items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-rose-100 shadow-2xs overflow-x-auto">
        <div class="flex items-center gap-2 shrink-0">
          <button 
            @click="activeTab = 'all'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeTab === 'all' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Tất Cả Kịch Bản ({{ localItems.length }})
          </button>
          
          <button 
            @click="activeTab = 'morning_ceremony'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeTab === 'morning_ceremony' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Lễ Gia Tiên Sáng
          </button>

          <button 
            @click="activeTab = 'evening_reception'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeTab === 'evening_reception' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Tiệc Cưới Tối
          </button>

          <button 
            @click="activeTab = 'party'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="activeTab === 'party' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            After Party Giao Lưu
          </button>
        </div>
      </div>

      <!-- Run-of-Show Timeline List -->
      <div class="space-y-4">
        <div 
          v-for="item in filteredItems" 
          :key="item.id"
          class="bg-white rounded-3xl border transition-all p-6 shadow-sm hover:shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-4"
          :class="item.is_completed ? 'border-emerald-200 bg-emerald-50/20' : 'border-rose-100 bg-white'"
        >
          <div class="flex items-start gap-4 min-w-0 flex-1">
            <button 
              @click="toggleItem(item)"
              class="w-6 h-6 rounded-full border mt-0.5 flex items-center justify-center shrink-0 transition cursor-pointer"
              :class="item.is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 bg-white hover:border-rose-400'"
            >
              <Check v-if="item.is_completed" class="w-4 h-4 stroke-[3]" />
            </button>

            <div class="space-y-1.5 min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase border" :class="getSessionBadge(item.session_type).class">
                  {{ getSessionBadge(item.session_type).text }}
                </span>
                <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-900 font-extrabold text-[11px] font-mono border border-slate-200 flex items-center gap-1">
                  <Clock class="w-3 h-3 text-slate-500" /> {{ item.time_slot }}
                </span>
              </div>

              <h3 class="text-base font-bold text-slate-900" :class="{ 'line-through text-slate-400': item.is_completed }">
                {{ item.title }}
              </h3>

              <p v-if="item.description" class="text-xs text-slate-500 font-medium leading-relaxed">{{ item.description }}</p>

              <div class="flex items-center gap-4 text-xs font-semibold text-slate-600 pt-1 flex-wrap">
                <span v-if="item.person_in_charge" class="flex items-center gap-1.5 text-rose-900 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200/60">
                  <User class="w-3.5 h-3.5 text-rose-600" /> PIC: {{ item.person_in_charge }}
                  <a v-if="item.pic_phone" :href="`tel:${item.pic_phone}`" class="text-rose-600 underline font-mono text-[11px]">({{ item.pic_phone }})</a>
                </span>

                <span v-if="item.location_note" class="flex items-center gap-1.5 text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                  <MapPin class="w-3.5 h-3.5 text-slate-500" /> {{ item.location_note }}
                </span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
            <button 
              @click="deleteItem(item)"
              class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
              title="Xóa khỏi kịch bản"
            >
              <Trash2 class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>

      <!-- Add Item Modal -->
      <div v-if="showAddModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white p-6 rounded-3xl border border-rose-100 shadow-2xl space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              <Plus class="w-5 h-5 text-rose-600" /> Thêm Hạng Mục Kịch Bản Ngày Cưới
            </h3>
            <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
          </div>

          <div class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Buổi / Giai Đoạn *</label>
              <select v-model="newSessionType" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-hidden">
                <option value="morning_ceremony">Lễ Gia Tiên Buổi Sáng</option>
                <option value="evening_reception">Tiệc Cưới Buổi Tối</option>
                <option value="party">After Party / Giao Lưu</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1.5">Khung Giờ (VD: 08:30 - 09:30) *</label>
                <input v-model="newTimeSlot" type="text" placeholder="08:30 - 09:30" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1.5">Địa Điểm / Địa Chỉ</label>
                <input v-model="newLocation" type="text" placeholder="VD: Nhà Cô Dâu, Sảnh A..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Tên Hạng Mục Nghi Thức *</label>
              <input v-model="newTitle" type="text" placeholder="VD: Trao nhẫn cưới & Trao nữ trang..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1.5">Người Phụ Trách (PIC)</label>
                <input v-model="newPic" type="text" placeholder="VD: MC Tuấn, Bác Trưởng Đoàn..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1.5">Số Điện Thoại PIC</label>
                <input v-model="newPicPhone" type="text" placeholder="0901234567" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Ghi Chú Chi Tiết Kịch Bản</label>
              <textarea v-model="newDescription" rows="2" placeholder="Chi tiết diễn biến và chuẩn bị đạo cụ..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400"></textarea>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <button @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">Hủy Bỏ</button>
            <button @click="handleAddItem" :disabled="isSubmitting || !newTimeSlot || !newTitle" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 cursor-pointer disabled:opacity-50">
              {{ isSubmitting ? 'Đang Lưu...' : 'Lưu Vào Kịch Bản' }}
            </button>
          </div>
        </div>
      </div>

    </main>
  </WorkspaceLayout>
</template>
