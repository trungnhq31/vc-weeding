<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { 
  Users, 
  Plus, 
  Search, 
  CheckCircle2, 
  XCircle, 
  Clock, 
  Grid, 
  Layers, 
  Move, 
  AlertTriangle,
  Mail,
  Phone,
  Heart,
  Download,
  Share2,
  Copy,
  Check,
  X,
  Sparkles,
  UserPlus,
  Zap,
  FileSpreadsheet,
  ArrowUpDown,
  ArrowUp,
  ArrowDown,
  ChevronLeft,
  ChevronRight
} from 'lucide-vue-next';

interface GuestItem {
  id: string;
  name: string;
  group?: string;
  role?: string;
  phone?: string;
  table_name?: string;
  table?: string;
  dietary_preference?: string;
  diet?: string;
  rsvp_status?: string;
  status?: string;
  notes?: string;
}

interface WorkspaceInfo {
  name?: string;
  slug?: string;
  groom_name?: string;
  bride_name?: string;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  dbGuests?: GuestItem[];
  shareUrl?: string;
}>();

const activeTab = ref<'list' | 'seating'>('list');
const draggedGuest = ref<any | null>(null);
const showShareModal = ref(false);
const showBatchAddModal = ref(false);
const isCopied = ref(false);
const searchQuery = ref('');

// Quick Add Form State
const quickName = ref('');
const quickGroup = ref('Nhà Trai');
const quickPhone = ref('');
const quickDiet = ref('Bình thường');
const isQuickStoring = ref(false);
const batchText = ref('');
const isBatchStoring = ref(false);

// Sorting & Pagination State
type SortField = 'name' | 'role' | 'phone' | 'table' | 'diet' | 'status';
type SortOrder = 'asc' | 'desc';

const sortField = ref<SortField>('name');
const sortOrder = ref<SortOrder>('asc');
const currentPage = ref(1);
const pageSize = ref(10);

const groupsList = [
  'Nhà Trai',
  'Nhà Gái',
  'Bạn Chú Rể',
  'Bạn Cô Dâu',
  'Đồng Nghiệp',
  'Họ Hàng'
];

const sampleGuests = [
  { id: '1', name: 'Nguyễn Văn Anh', role: 'Nhà Trai', phone: '0901234567', status: 'attending', table: 'Bàn VIP 1 (Họ Hàng)', diet: 'Không cay', notes: 'Thêm bởi: Chú rể' },
  { id: '2', name: 'Trần Thị Bích', role: 'Nhà Gái', phone: '0909876543', status: 'attending', table: 'Bàn VIP 1 (Họ Hàng)', diet: 'Bình thường', notes: 'Thêm bởi: Cô dâu' },
  { id: '3', name: 'Lê Hoàng Nam', role: 'Bạn Chú Rể', phone: '0912345678', status: 'attending', table: 'Bàn Bạn Học 1', diet: 'Ăn chay', notes: 'Thêm bởi: Bạn học' },
  { id: '4', name: 'Phạm Minh Tâm', role: 'Đồng Nghiệp', phone: '0987654321', status: 'pending', table: 'Bàn Công Ty', diet: 'Bình thường', notes: 'Thêm bởi: Đồng nghiệp' },
  { id: '5', name: 'Đặng Tuấn Kiệt', role: 'Họ Hàng Dâu', phone: '0933445566', status: 'attending', table: 'Chưa xếp', diet: '-', notes: 'Thêm qua Share Link: Mẹ Cô Dâu' },
  { id: '6', name: 'Vũ Quốc Huy', role: 'Bạn Chú Rể', phone: '0977889900', status: 'attending', table: 'Chưa xếp', diet: 'Ăn chay', notes: 'Thêm qua Share Link: Phù rể' },
  { id: '7', name: 'Hoàng Kim Ngân', role: 'Bạn Cô Dâu', phone: '0911223344', status: 'attending', table: 'Bàn Bạn Học 2', diet: 'Bình thường', notes: 'Thêm bởi: Cô dâu' },
  { id: '8', name: 'Ngô Tấn Tài', role: 'Đồng Nghiệp', phone: '0988776655', status: 'pending', table: 'Bàn Công Ty', diet: 'Dị ứng hải sản', notes: 'Thêm bởi: Chú rể' },
];

const localGuests = ref<any[]>(
  props.dbGuests && props.dbGuests.length > 0 
    ? props.dbGuests.map(g => ({
        id: g.id,
        name: g.name,
        role: g.group || g.role || 'Khách Mời',
        phone: g.phone || '-',
        table: g.table_name || g.table || 'Chưa xếp',
        diet: g.dietary_preference || g.diet || '-',
        status: (g.rsvp_status as string) || g.status || 'attending',
        notes: g.notes || 'Thêm qua hệ thống'
      }))
    : sampleGuests
);

const filteredGuests = computed(() => {
  if (!searchQuery.value.trim()) return localGuests.value;
  const q = searchQuery.value.toLowerCase();
  return localGuests.value.filter(g => 
    g.name.toLowerCase().includes(q) || 
    g.phone.includes(q) || 
    g.role.toLowerCase().includes(q) ||
    g.table.toLowerCase().includes(q)
  );
});

// Toggle Sorting
const toggleSort = (field: SortField) => {
  if (sortField.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortOrder.value = 'asc';
  }
};

const sortedGuests = computed(() => {
  let list = [...filteredGuests.value];
  list.sort((a, b) => {
    let valA = (a[sortField.value] || '').toString().toLowerCase();
    let valB = (b[sortField.value] || '').toString().toLowerCase();
    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1;
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1;
    return 0;
  });
  return list;
});

// Reset page when search or filters change
watch([searchQuery, pageSize], () => {
  currentPage.value = 1;
});

const totalPages = computed(() => Math.ceil(sortedGuests.value.length / pageSize.value) || 1);

const paginatedGuests = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  return sortedGuests.value.slice(start, start + pageSize.value);
});

// Quick Single Add Handler
const handleQuickAdd = async () => {
  if (!quickName.value.trim()) return;
  isQuickStoring.value = true;

  try {
    const res = await fetch('/wedding/guests/quick-store', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        name: quickName.value,
        phone: quickPhone.value,
        group: quickGroup.value,
        dietary_preference: quickDiet.value,
        notes: 'Thêm nhanh từ Workspace',
      }),
    });

    const data = await res.json();
    if (data.success && data.guest) {
      localGuests.value.unshift({
        id: data.guest.id,
        name: data.guest.name,
        role: data.guest.group,
        phone: data.guest.phone || '-',
        table: 'Chưa xếp',
        diet: data.guest.dietary_preference || '-',
        status: 'attending',
        notes: 'Thêm nhanh 1s',
      });

      // Clear input
      quickName.value = '';
      quickPhone.value = '';
    }
  } catch (e) {
    console.error('Quick add failed:', e);
  } finally {
    isQuickStoring.value = false;
  }
};

// Batch Paste Add Handler
const handleBatchAdd = async () => {
  if (!batchText.value.trim()) return;
  isBatchStoring.value = true;

  const names = batchText.value
    .split('\n')
    .map(n => n.trim())
    .filter(n => n.length > 0);

  for (const name of names) {
    try {
      const res = await fetch('/wedding/guests/quick-store', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
          name: name,
          group: quickGroup.value,
          notes: 'Nhập hàng loạt Zalo/Excel',
        }),
      });
      const data = await res.json();
      if (data.success && data.guest) {
        localGuests.value.unshift({
          id: data.guest.id,
          name: data.guest.name,
          role: data.guest.group,
          phone: '-',
          table: 'Chưa xếp',
          diet: '-',
          status: 'attending',
          notes: 'Nhập hàng loạt',
        });
      }
    } catch (e) {
      console.error('Batch item failed:', e);
    }
  }

  batchText.value = '';
  showBatchAddModal.value = false;
  isBatchStoring.value = false;
};

const publicShareUrl = computed(() => {
  if (props.shareUrl) return props.shareUrl;
  const slug = props.workspace?.slug || 'quoc-trung-hong-van';
  return `${window.location.origin}/wedding/share-guest-list/${slug}`;
});

const copyShareLink = async () => {
  try {
    await navigator.clipboard.writeText(publicShareUrl.value);
    isCopied.value = true;
    setTimeout(() => {
      isCopied.value = false;
    }, 2500);
  } catch (e) {
    console.error('Copy failed:', e);
  }
};

const tables = ref([
  { id: 't1', name: 'Bàn VIP 1 (Họ Hàng)', capacity: 10, assignedCount: 2, shape: 'round', zone: 'Sân Chính', isOverloaded: false },
  { id: 't2', name: 'Bàn VIP 2 (Họ Hàng Dâu)', capacity: 10, assignedCount: 0, shape: 'round', zone: 'Sân Chính', isOverloaded: false },
  { id: 't3', name: 'Bàn Bạn Học 1', capacity: 10, assignedCount: 1, shape: 'round', zone: 'Khu Bên Trái', isOverloaded: false },
  { id: 't4', name: 'Bàn Công Ty', capacity: 10, assignedCount: 1, shape: 'round', zone: 'Khu Bên Phải', isOverloaded: false },
]);

const unassignedGuests = computed(() => localGuests.value.filter(g => g.table === 'Chưa xếp' || !g.table));

const getTableGuests = (tableName: string) => {
  return localGuests.value.filter(g => g.table === tableName);
};

const onDragStart = (guest: any) => {
  draggedGuest.value = guest;
};

const onDropOnTable = (table: any) => {
  if (!draggedGuest.value) return;
  draggedGuest.value.table = table.name;
  recalculateTableCounts();
  draggedGuest.value = null;
};

const unseatGuest = (guest: any) => {
  guest.table = 'Chưa xếp';
  recalculateTableCounts();
};

const recalculateTableCounts = () => {
  tables.value.forEach(table => {
    const count = localGuests.value.filter(g => g.table === table.name).length;
    table.assignedCount = count;
    table.isOverloaded = count > table.capacity;
  });
};

const totalGuests = computed(() => localGuests.value.length);
const confirmedCount = computed(() => localGuests.value.filter(g => g.status === 'confirmed' || g.status === 'attending').length);
const pendingCount = computed(() => localGuests.value.filter(g => g.status === 'pending').length);
const declinedCount = computed(() => localGuests.value.filter(g => g.status === 'declined').length);
</script>

<template>
  <WorkspaceLayout title="Khách Mời & Sơ Đồ Bàn Tiệc" active-nav="guests">
    <div class="space-y-8 font-sans max-w-7xl mx-auto px-2 md:px-6 py-6">
      
      <!-- Overview Metrics Grid (Balanced 4-Card Layout) -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Tổng Khách Mời</span>
          <div class="text-3xl font-extrabold text-slate-900 mt-1 font-mono">{{ totalGuests }} Người</div>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Đã Xác Nhận</span>
          <div class="text-3xl font-extrabold text-emerald-600 mt-1 font-mono">{{ confirmedCount }} Người</div>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Chờ Phản Hồi</span>
          <div class="text-3xl font-extrabold text-amber-600 mt-1 font-mono">{{ pendingCount }} Người</div>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Từ Chối / Bận</span>
          <div class="text-3xl font-extrabold text-slate-400 mt-1 font-mono">{{ declinedCount }} Người</div>
        </div>
      </div>

      <!-- Header Action & Tab Switcher Bar -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-rose-100 pb-4">
        <div class="flex items-center gap-2">
          <button 
            @click="activeTab = 'list'"
            class="px-5 py-2.5 rounded-2xl font-bold text-xs transition-all cursor-pointer"
            :class="activeTab === 'list' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
          >
            Danh Sách Khách Mời
          </button>
          <button 
            @click="activeTab = 'seating'"
            class="px-5 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer"
            :class="activeTab === 'seating' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
          >
            <Grid class="w-4 h-4 text-rose-400" />
            Sơ Đồ Bàn Tiệc
          </button>
        </div>

        <div class="flex items-center gap-3">
          <!-- Batch Add Modal Button -->
          <button 
            @click="showBatchAddModal = true"
            class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition flex items-center gap-2 shadow-2xs cursor-pointer"
          >
            <FileSpreadsheet class="w-4 h-4 text-slate-500" />
            <span>Nhập Hàng Loạt</span>
          </button>

          <!-- Shareable Link CTA Button -->
          <button 
            @click="showShareModal = true"
            class="px-5 py-2.5 rounded-2xl bg-[#881337] hover:bg-[#70102d] text-white font-extrabold text-xs transition flex items-center gap-2 shadow-lg cursor-pointer transform hover:-translate-y-0.5"
          >
            <Share2 class="w-4 h-4 text-amber-300" />
            <span>Chia Sẻ Link Thêm Khách</span>
          </button>
        </div>
      </div>

      <!-- Tab 1: Guest List & Quick Add Engine -->
      <div v-if="activeTab === 'list'" class="space-y-6">

        <!-- ⚡ INLINE ADD GUEST BAR -->
        <div class="p-5 md:p-6 rounded-3xl bg-gradient-to-r from-rose-950 via-[#881337] to-rose-900 text-white shadow-xl space-y-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <UserPlus class="w-5 h-5 text-amber-300" />
              <h3 class="font-serif font-extrabold text-white text-base">Thêm Khách Mời</h3>
            </div>
          </div>

          <form @submit.prevent="handleQuickAdd" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
            <!-- Guest Name -->
            <div class="sm:col-span-5">
              <input 
                v-model="quickName"
                type="text"
                required
                placeholder="Họ và tên khách mời..."
                class="w-full px-4 py-3 rounded-2xl bg-white/95 text-slate-900 font-bold placeholder-slate-400 focus:bg-white outline-none border border-transparent focus:border-amber-300 text-xs shadow-inner"
              />
            </div>

            <!-- Group Select -->
            <div class="sm:col-span-3">
              <select 
                v-model="quickGroup"
                class="w-full px-4 py-3 rounded-2xl bg-white/95 text-slate-900 font-bold outline-none border border-transparent focus:border-amber-300 text-xs shadow-inner"
              >
                <option v-for="g in groupsList" :key="g" :value="g">🏷️ {{ g }}</option>
              </select>
            </div>

            <!-- Phone Number -->
            <div class="sm:col-span-2">
              <input 
                v-model="quickPhone"
                type="tel"
                placeholder="Số điện thoại..."
                class="w-full px-3 py-3 rounded-2xl bg-white/95 text-slate-900 font-mono text-xs placeholder-slate-400 focus:bg-white outline-none border border-transparent focus:border-amber-300 shadow-inner"
              />
            </div>

            <!-- Submit Button -->
            <div class="sm:col-span-2">
              <button 
                type="submit"
                :disabled="isQuickStoring"
                class="w-full h-full py-3 px-3 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-extrabold text-xs shadow-md flex items-center justify-center gap-1 cursor-pointer transition active:scale-95 disabled:opacity-50"
              >
                <Plus class="w-4 h-4 text-slate-950 stroke-[3]" />
                <span>+ THÊM KHÁCH MỜI</span>
              </button>
            </div>
          </form>
        </div>

        <!-- RE-STYLED GUEST LIST TABLE CONTAINER WITH SORTING & PAGINATION -->
        <div class="bg-white rounded-3xl border border-rose-100 shadow-lg overflow-hidden space-y-0">
          
          <!-- Table Toolbar -->
          <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="relative w-full sm:w-80">
              <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Tìm kiếm khách mời..." 
                class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs w-full focus:outline-hidden focus:border-[#881337] shadow-2xs" 
              />
            </div>
            
            <div class="flex items-center gap-4">
              <!-- Page Size Selector -->
              <div class="flex items-center gap-1.5 text-xs text-slate-500">
                <span>Hiển thị:</span>
                <select 
                  v-model.number="pageSize" 
                  class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white font-bold text-slate-800 text-xs outline-none focus:border-[#881337]"
                >
                  <option :value="10">10 dòng / trang</option>
                  <option :value="20">20 dòng / trang</option>
                  <option :value="50">50 dòng / trang</option>
                </select>
              </div>

              <a 
                href="/wedding/guests/export" 
                target="_blank"
                class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition flex items-center gap-1.5 shadow-2xs"
              >
                <Download class="w-4 h-4 text-slate-500" /> Xuất CSV
              </a>
            </div>
          </div>

          <!-- Table Content with Interactive Click-to-Sort Headers -->
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold text-[11px]">
                  
                  <!-- Clickable Sort: Name -->
                  <th @click="toggleSort('name')" class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition select-none">
                    <div class="flex items-center gap-1.5">
                      <span>Họ và tên</span>
                      <ArrowUp v-if="sortField === 'name' && sortOrder === 'asc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowDown v-else-if="sortField === 'name' && sortOrder === 'desc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowUpDown v-else class="w-3.5 h-3.5 text-slate-300" />
                    </div>
                  </th>

                  <!-- Clickable Sort: Group -->
                  <th @click="toggleSort('role')" class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition select-none">
                    <div class="flex items-center gap-1.5">
                      <span>Nhóm / Mối quan hệ</span>
                      <ArrowUp v-if="sortField === 'role' && sortOrder === 'asc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowDown v-else-if="sortField === 'role' && sortOrder === 'desc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowUpDown v-else class="w-3.5 h-3.5 text-slate-300" />
                    </div>
                  </th>

                  <!-- Clickable Sort: Phone -->
                  <th @click="toggleSort('phone')" class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition select-none">
                    <div class="flex items-center gap-1.5">
                      <span>Số điện thoại</span>
                      <ArrowUp v-if="sortField === 'phone' && sortOrder === 'asc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowDown v-else-if="sortField === 'phone' && sortOrder === 'desc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowUpDown v-else class="w-3.5 h-3.5 text-slate-300" />
                    </div>
                  </th>

                  <!-- Clickable Sort: Table -->
                  <th @click="toggleSort('table')" class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition select-none">
                    <div class="flex items-center gap-1.5">
                      <span>Vị trí bàn tiệc</span>
                      <ArrowUp v-if="sortField === 'table' && sortOrder === 'asc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowDown v-else-if="sortField === 'table' && sortOrder === 'desc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowUpDown v-else class="w-3.5 h-3.5 text-slate-300" />
                    </div>
                  </th>

                  <!-- Clickable Sort: Diet -->
                  <th @click="toggleSort('diet')" class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition select-none">
                    <div class="flex items-center gap-1.5">
                      <span>Ghi chú & Khẩu vị</span>
                      <ArrowUp v-if="sortField === 'diet' && sortOrder === 'asc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowDown v-else-if="sortField === 'diet' && sortOrder === 'desc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowUpDown v-else class="w-3.5 h-3.5 text-slate-300" />
                    </div>
                  </th>

                  <th class="px-6 py-4">Nguồn khai báo</th>

                  <!-- Clickable Sort: Status -->
                  <th @click="toggleSort('status')" class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition select-none">
                    <div class="flex items-center gap-1.5">
                      <span>Trạng thái tham dự</span>
                      <ArrowUp v-if="sortField === 'status' && sortOrder === 'asc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowDown v-else-if="sortField === 'status' && sortOrder === 'desc'" class="w-3.5 h-3.5 text-[#881337]" />
                      <ArrowUpDown v-else class="w-3.5 h-3.5 text-slate-300" />
                    </div>
                  </th>

                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr 
                  v-for="guest in paginatedGuests" 
                  :key="guest.id" 
                  class="hover:bg-rose-50/40 transition-colors border-l-4 border-l-transparent hover:border-l-[#881337]"
                >
                  <td class="px-6 py-4.5 font-extrabold text-slate-900 text-sm">
                    {{ guest.name }}
                  </td>

                  <td class="px-6 py-4.5">
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-800 font-bold text-xs shadow-2xs border border-slate-200/60">
                      {{ guest.role }}
                    </span>
                  </td>
                  
                  <td class="px-6 py-4.5 text-slate-600 font-mono text-xs font-semibold">
                    {{ guest.phone }}
                  </td>

                  <td class="px-6 py-4.5 font-bold text-slate-800">
                    {{ guest.table }}
                  </td>

                  <td class="px-6 py-4.5 text-slate-600 font-medium">
                    {{ guest.diet }}
                  </td>

                  <td class="px-6 py-4.5">
                    <span class="px-2.5 py-1 rounded-lg bg-rose-50 border border-rose-200/80 text-rose-900 text-[11px] font-bold inline-block">
                      {{ guest.notes }}
                    </span>
                  </td>

                  <td class="px-6 py-4.5">
                    <span v-if="guest.status === 'confirmed' || guest.status === 'attending'" class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center gap-1.5 w-max">
                      <CheckCircle2 class="w-3.5 h-3.5 text-emerald-600" /> Tham dự
                    </span>
                    <span v-else-if="guest.status === 'pending'" class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold flex items-center gap-1.5 w-max">
                      <Clock class="w-3.5 h-3.5 text-amber-600" /> Chờ phản hồi
                    </span>
                    <span v-else class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold flex items-center gap-1.5 w-max">
                      <XCircle class="w-3.5 h-3.5 text-slate-400" /> Bận
                    </span>
                  </td>
                </tr>

                <tr v-if="paginatedGuests.length === 0">
                  <td colspan="7" class="p-8 text-center text-slate-400 text-xs">
                    Không tìm thấy khách mời nào phù hợp!
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Table Footer Pagination Bar -->
          <div class="p-4 md:p-6 bg-slate-50 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-medium text-slate-600">
            <div>
              Hiển thị <strong class="text-slate-900 font-bold">{{ (currentPage - 1) * pageSize + 1 }}</strong> - <strong class="text-slate-900 font-bold">{{ Math.min(currentPage * pageSize, sortedGuests.length) }}</strong> trên tổng số <strong class="text-[#881337] font-bold">{{ sortedGuests.length }}</strong> khách mời
            </div>

            <!-- Page Number Navigation Buttons -->
            <div class="flex items-center gap-1.5">
              <button 
                @click="currentPage--"
                :disabled="currentPage === 1"
                class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 disabled:opacity-40 disabled:cursor-not-allowed transition flex items-center gap-1 font-bold cursor-pointer"
              >
                <ChevronLeft class="w-4 h-4" /> Trang trước
              </button>

              <div class="flex items-center gap-1 px-2">
                <button 
                  v-for="page in totalPages" 
                  :key="page"
                  @click="currentPage = page"
                  class="w-8 h-8 rounded-xl font-bold text-xs transition cursor-pointer"
                  :class="currentPage === page ? 'bg-[#881337] text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100'"
                >
                  {{ page }}
                </button>
              </div>

              <button 
                @click="currentPage++"
                :disabled="currentPage === totalPages"
                class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 disabled:opacity-40 disabled:cursor-not-allowed transition flex items-center gap-1 font-bold cursor-pointer"
              >
                Trang sau <ChevronRight class="w-4 h-4" />
              </button>
            </div>
          </div>

        </div>
      </div>

      <!-- Tab 2: Interactive Seating Planner Canvas -->
      <div v-else class="space-y-6">
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-base font-bold text-slate-900">Sơ Đồ Bàn Tiệc Kéo Thả (Drag & Drop Seating Canvas)</h3>
              <p class="text-xs text-slate-500">Kéo thẻ khách mời từ danh sách bên trái thả vào từng bàn tiệc để tự động xếp chỗ</p>
            </div>
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition cursor-pointer">
              + Thêm Bàn Tiệc Mới
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Left Sidebar: Unassigned Guests Queue -->
            <div class="md:col-span-4 bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
              <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Khách Chưa Xếp Bàn</span>
                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">{{ unassignedGuests.length }}</span>
              </div>

              <div class="space-y-2 max-h-[500px] overflow-y-auto pr-1">
                <div v-if="unassignedGuests.length === 0" class="p-6 text-center text-slate-400 text-xs bg-white rounded-xl border border-dashed border-slate-200">
                  Tất cả khách đã được xếp bàn!
                </div>

                <div 
                  v-for="guest in unassignedGuests"
                  :key="guest.id"
                  draggable="true"
                  @dragstart="onDragStart(guest)"
                  class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs hover:border-rose-300 hover:shadow-xs transition cursor-grab active:cursor-grabbing flex items-center justify-between"
                >
                  <div>
                    <span class="font-bold text-xs text-slate-900 block">{{ guest.name }}</span>
                    <span class="text-[10px] text-slate-500">{{ guest.role }} • {{ guest.phone }}</span>
                  </div>
                  <Move class="w-4 h-4 text-slate-400 shrink-0" />
                </div>
              </div>
            </div>

            <!-- Right Area: Table Grid Drop Targets -->
            <div class="md:col-span-8 grid sm:grid-cols-2 gap-4">
              <div 
                v-for="table in tables" 
                :key="table.id"
                @dragover.prevent
                @drop="onDropOnTable(table)" 
                class="p-5 rounded-2xl border transition-all flex flex-col justify-between"
                :class="table.isOverloaded ? 'bg-rose-50/70 border-rose-300' : 'bg-slate-50/50 border-slate-200 hover:border-rose-200'"
              >
                <div>
                  <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-600">
                      {{ table.zone }}
                    </span>
                    <span v-if="table.isOverloaded" class="text-[10px] font-bold text-rose-700 flex items-center gap-1">
                      <AlertTriangle class="w-3.5 h-3.5 text-rose-600" /> Quá tải!
                    </span>
                  </div>
                  <h4 class="font-serif font-bold text-slate-900 text-base mb-1">{{ table.name }}</h4>
                  <div class="text-xs text-slate-600 mb-3">
                    Sức chứa: <strong class="text-slate-900 font-bold">{{ getTableGuests(table.name).length }}/{{ table.capacity }}</strong> Khách
                  </div>

                  <!-- Drop Zone Box & Assigned Guest Pills -->
                  <div class="min-h-[100px] p-2.5 rounded-xl border-2 border-dashed border-slate-300 bg-white space-y-1.5">
                    <div v-if="getTableGuests(table.name).length === 0" class="h-full flex items-center justify-center text-xs text-slate-400 py-6">
                      Kéo thả thẻ khách vào đây
                    </div>

                    <div 
                      v-for="g in getTableGuests(table.name)"
                      :key="g.id"
                      class="px-2.5 py-1.5 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-between text-xs"
                    >
                      <span class="font-medium text-slate-800">{{ g.name }}</span>
                      <button @click="unseatGuest(g)" class="text-[10px] text-rose-600 hover:underline cursor-pointer">Gỡ</button>
                    </div>
                  </div>
                </div>

                <div class="mt-3 pt-2 border-t border-slate-200 flex justify-between text-xs text-slate-500 font-medium">
                  <span>Dạng: {{ table.shape }}</span>
                  <span class="text-rose-700 font-bold">Thả để thêm →</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Batch Add Paste List Modal -->
    <div v-if="showBatchAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
      <div class="w-full max-w-lg bg-white rounded-3xl border border-rose-100 shadow-2xl overflow-hidden font-sans">
        <div class="px-6 py-4 bg-gradient-to-r from-rose-900 via-[#881337] to-rose-950 text-white flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <FileSpreadsheet class="w-5 h-5 text-amber-300" />
            <h3 class="font-serif font-extrabold text-white text-base">Nhập Nhanh Hàng Loạt Từ Zalo / Excel</h3>
          </div>
          <button @click="showBatchAddModal = false" class="text-white hover:opacity-80 cursor-pointer"><X class="w-5 h-5" /></button>
        </div>

        <div class="p-6 space-y-4 text-xs">
          <div class="space-y-1">
            <label class="block font-bold text-slate-700">Nhóm Khách Mời Cho Danh Sách Này</label>
            <select v-model="quickGroup" class="w-full p-2.5 rounded-xl border border-slate-200 font-bold bg-white text-xs">
              <option v-for="g in groupsList" :key="g" :value="g">🏷️ {{ g }}</option>
            </select>
          </div>

          <p class="text-slate-600">Dán danh sách tên khách mời (mỗi dòng 1 tên) để chèn hàng loạt vào hệ thống:</p>
          <textarea 
            v-model="batchText"
            rows="7"
            placeholder="Nguyễn Văn A&#10;Trần Thị B&#10;Lê Hoàng C..."
            class="w-full p-4 rounded-2xl border border-slate-200 font-mono text-xs text-slate-900 focus:border-[#881337] outline-none"
          ></textarea>

          <div class="flex justify-end gap-2">
            <button @click="showBatchAddModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold cursor-pointer">Hủy</button>
            <button 
              @click="handleBatchAdd"
              :disabled="isBatchStoring"
              class="px-6 py-2 rounded-xl bg-[#881337] text-white font-extrabold shadow-md cursor-pointer hover:bg-[#70102d]"
            >
              {{ isBatchStoring ? 'Đang lưu...' : 'Nhập Hàng Loạt Ngay' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Shareable Collaborative Guest Link Modal -->
    <div v-if="showShareModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
      <div class="w-full max-w-lg bg-white rounded-3xl border border-rose-100 shadow-2xl overflow-hidden font-sans space-y-0">
        <div class="px-6 py-4 bg-gradient-to-r from-rose-900 via-[#881337] to-rose-950 text-white flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center">
              <Share2 class="w-5 h-5 text-amber-300" />
            </div>
            <div>
              <h3 class="font-serif font-extrabold text-white text-base">Chia Sẻ Link Thêm Khách Mời</h3>
              <p class="text-[11px] text-rose-200 font-medium">Để Ba Mẹ hai bên & Bạn bè cùng nhập khách trực tiếp</p>
            </div>
          </div>
          <button @click="showShareModal = false" class="w-8 h-8 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition cursor-pointer">
            <X class="w-4 h-4" />
          </button>
        </div>

        <div class="p-6 space-y-5">
          <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200/80 text-xs text-slate-700 leading-relaxed font-medium space-y-1">
            <span class="font-bold text-[#881337] block">💡 Tiện ích cộng tác người thân:</span>
            <p>Gửi đường link này qua Zalo/Messenger cho Ba Mẹ, Anh chị em hoặc Phù rể/Phù dâu. Họ chỉ cần mở link trên điện thoại là có thể tự điền danh sách khách mời của từng nhà mà không cần tạo tài khoản!</p>
          </div>

          <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-800">Đường Link Công Khai Nhập Khách Mời</label>
            <div class="flex items-center gap-2">
              <input 
                :value="publicShareUrl"
                readonly
                type="text"
                class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-mono text-xs text-slate-900 outline-none select-all"
              />
              <button 
                @click="copyShareLink"
                class="px-4 py-2.5 rounded-xl bg-[#881337] hover:bg-[#70102d] text-white font-extrabold text-xs shadow-md transition flex items-center gap-1.5 shrink-0 cursor-pointer"
              >
                <Check v-if="isCopied" class="w-4 h-4 text-emerald-300" />
                <Copy v-else class="w-4 h-4 text-white" />
                <span>{{ isCopied ? 'Đã sao chép!' : 'Sao chép link' }}</span>
              </button>
            </div>
          </div>

          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3 text-xs text-slate-600">
            <Sparkles class="w-5 h-5 text-amber-500 shrink-0" />
            <span>Mọi khách mời được nhập qua link sẽ tự động đồng bộ realtime vào bảng quản lý của Dâu Rể.</span>
          </div>
        </div>

        <div class="p-5 bg-slate-50 border-t border-slate-100 flex justify-end">
          <button @click="showShareModal = false" class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition cursor-pointer">
            Hoàn tất
          </button>
        </div>
      </div>
    </div>
  </WorkspaceLayout>
</template>
