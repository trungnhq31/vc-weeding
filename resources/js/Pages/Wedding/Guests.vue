<script setup lang="ts">
import { ref, computed } from 'vue';
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
  UserPlus
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
const isCopied = ref(false);
const searchQuery = ref('');

const sampleGuests = [
  { id: '1', name: 'Nguyễn Văn Anh', role: 'Nhà Trai', phone: '0901234567', status: 'confirmed', table: 'Bàn VIP 1 (Họ Hàng)', diet: 'Không cay', notes: 'Thêm bởi: Chú rể' },
  { id: '2', name: 'Trần Thị Bích', role: 'Nhà Gái', phone: '0909876543', status: 'confirmed', table: 'Bàn VIP 1 (Họ Hàng)', diet: 'Bình thường', notes: 'Thêm bởi: Cô dâu' },
  { id: '3', name: 'Lê Hoàng Nam', role: 'Bạn Chú Rể', phone: '0912345678', status: 'confirmed', table: 'Bàn Bạn Học 1', diet: 'Ăn chay', notes: 'Thêm bởi: Bạn học' },
  { id: '4', name: 'Phạm Minh Tâm', role: 'Đồng Nghiệp', phone: '0987654321', status: 'pending', table: 'Bàn Công Ty', diet: 'Bình thường', notes: 'Thêm bởi: Đồng nghiệp' },
  { id: '5', name: 'Đặng Tuấn Kiệt', role: 'Họ Hàng Dâu', phone: '0933445566', status: 'confirmed', table: 'Chưa xếp', diet: '-', notes: 'Thêm qua Share Link bởi: Mẹ Cô Dâu' },
  { id: '6', name: 'Vũ Quốc Huy', role: 'Bạn Chú Rể', phone: '0977889900', status: 'confirmed', table: 'Chưa xếp', diet: 'Ăn chay', notes: 'Thêm qua Share Link bởi: Phù rể' },
];

const guestsList = computed(() => {
  if (props.dbGuests && props.dbGuests.length > 0) {
    return props.dbGuests.map(g => ({
      id: g.id,
      name: g.name,
      role: g.group || g.role || 'Khách Mời',
      phone: g.phone || '-',
      table: g.table_name || g.table || 'Chưa xếp',
      diet: g.dietary_preference || g.diet || '-',
      status: (g.rsvp_status as string) || g.status || 'confirmed',
      notes: g.notes || 'Thêm qua hệ thống'
    }));
  }
  return sampleGuests;
});

const filteredGuests = computed(() => {
  if (!searchQuery.value.trim()) return guestsList.value;
  const q = searchQuery.value.toLowerCase();
  return guestsList.value.filter(g => 
    g.name.toLowerCase().includes(q) || 
    g.phone.includes(q) || 
    g.role.toLowerCase().includes(q)
  );
});

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

const unassignedGuests = computed(() => guestsList.value.filter(g => g.table === 'Chưa xếp' || !g.table));

const getTableGuests = (tableName: string) => {
  return guestsList.value.filter(g => g.table === tableName);
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
    const count = guestsList.value.filter(g => g.table === table.name).length;
    table.assignedCount = count;
    table.isOverloaded = count > table.capacity;
  });
};

const totalGuests = computed(() => guestsList.value.length);
const confirmedCount = computed(() => guestsList.value.filter(g => g.status === 'confirmed' || g.status === 'attending').length);
const pendingCount = computed(() => guestsList.value.filter(g => g.status === 'pending').length);
const declinedCount = computed(() => guestsList.value.filter(g => g.status === 'declined').length);
</script>

<template>
  <WorkspaceLayout title="Khách Mời & Sơ Đồ Bàn Tiệc" active-nav="guests">
    <div class="space-y-8 font-sans">
      <!-- Overview Metrics -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Tổng Khách Mời</span>
          <div class="text-2xl font-bold text-slate-900 mt-1 font-mono">{{ totalGuests }} Người</div>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Đã Xác Nhận (RSVP Yes)</span>
          <div class="text-2xl font-bold text-emerald-600 mt-1 font-mono">{{ confirmedCount }} Người</div>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Chờ Phản Hồi</span>
          <div class="text-2xl font-bold text-amber-600 mt-1 font-mono">{{ pendingCount }} Người</div>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Từ Chối / Bận</span>
          <div class="text-2xl font-bold text-slate-400 mt-1 font-mono">{{ declinedCount }} Người</div>
        </div>
      </div>

      <!-- Tab Navigation & Collaborative Share Bar -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-slate-200 pb-4">
        <div class="flex items-center gap-2">
          <button 
            @click="activeTab = 'list'"
            class="px-4 py-2 rounded-xl font-semibold text-xs transition-all cursor-pointer"
            :class="activeTab === 'list' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
          >
            Danh Sách Khách Mời (RSVP)
          </button>
          <button 
            @click="activeTab = 'seating'"
            class="px-4 py-2 rounded-xl font-semibold text-xs transition-all flex items-center gap-1.5 cursor-pointer"
            :class="activeTab === 'seating' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
          >
            <Grid class="w-4 h-4 text-rose-400" />
            Sơ Đồ Bàn Tiệc Kéo Thả (Seating Canvas)
          </button>
        </div>

        <!-- Shareable Link CTA Button -->
        <button 
          @click="showShareModal = true"
          class="px-4 py-2 rounded-xl bg-[#881337] hover:bg-[#70102d] text-white font-extrabold text-xs transition flex items-center gap-2 shadow-md cursor-pointer transform hover:-translate-y-0.5"
        >
          <Share2 class="w-4 h-4 text-amber-300" />
          <span>🔗 Chia Sẻ Link Thêm Khách</span>
        </button>
      </div>

      <!-- Tab 1: Guest List Table -->
      <div v-if="activeTab === 'list'" class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div class="relative w-full sm:w-72">
            <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3" />
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Tìm tên, SĐT, nhóm..." 
              class="pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs w-full focus:outline-hidden focus:border-[#881337]" 
            />
          </div>
          <div class="flex items-center gap-2">
            <a 
              href="/wedding/guests/export" 
              target="_blank"
              class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition flex items-center gap-1.5 shadow-2xs"
            >
              <Download class="w-4 h-4 text-slate-500" /> Xuất CSV
            </a>
            <button @click="showShareModal = true" class="px-4 py-2 rounded-xl bg-rose-50 text-rose-900 border border-rose-200 hover:bg-rose-100 font-semibold text-xs transition flex items-center gap-1.5 cursor-pointer">
              <UserPlus class="w-4 h-4 text-[#881337]" /> Mời Người Thân Nhập Khách
            </button>
          </div>
        </div>

        <table class="w-full text-left text-xs border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-semibold">
              <th class="p-4">Họ và tên</th>
              <th class="p-4">Nhóm / Mối quan hệ</th>
              <th class="p-4">Số điện thoại</th>
              <th class="p-4">Vị trí bàn tiệc</th>
              <th class="p-4">Ghi chú & Khẩu vị</th>
              <th class="p-4">Nguồn khai báo</th>
              <th class="p-4">Xác nhận RSVP</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="guest in filteredGuests" :key="guest.id" class="hover:bg-slate-50/80 transition-colors">
              <td class="p-4 font-bold text-slate-900">{{ guest.name }}</td>
              <td class="p-4">
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-semibold">{{ guest.role }}</span>
              </td>
              <td class="p-4 text-slate-600 font-mono">{{ guest.phone }}</td>
              <td class="p-4 font-semibold text-slate-800">{{ guest.table }}</td>
              <td class="p-4 text-slate-600 font-medium">{{ guest.diet }}</td>
              <td class="p-4">
                <span class="px-2 py-0.5 rounded-md bg-rose-50 border border-rose-100 text-rose-800 text-[10px] font-bold">
                  {{ guest.notes }}
                </span>
              </td>
              <td class="p-4">
                <span v-if="guest.status === 'confirmed' || guest.status === 'attending'" class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold flex items-center gap-1 w-max">
                  <CheckCircle2 class="w-3 h-3 text-emerald-600" /> Tham dự
                </span>
                <span v-else-if="guest.status === 'pending'" class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold flex items-center gap-1 w-max">
                  <Clock class="w-3 h-3 text-amber-600" /> Chờ phản hồi
                </span>
                <span v-else class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-semibold flex items-center gap-1 w-max">
                  <XCircle class="w-3 h-3 text-slate-400" /> Bận
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Tab 2: Interactive Seating Planner Canvas -->
      <div v-else class="space-y-6">
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-base font-bold text-slate-900">Sơ Đồ Bàn Tiệc Kéo Thả (Drag & Drop Seating Canvas)</h3>
              <p class="text-xs text-slate-500">Kéo thẻ khách mời từ danh sách bên trái thả vào từng bàn tiệc để tự động xếp chỗ</p>
            </div>
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition">
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

    <!-- Shareable Collaborative Guest Link Modal -->
    <div v-if="showShareModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
      <div class="w-full max-w-lg bg-white rounded-3xl border border-rose-100 shadow-2xl overflow-hidden font-sans space-y-0">
        
        <!-- Modal Header -->
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

        <!-- Modal Body -->
        <div class="p-6 space-y-5">
          <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200/80 text-xs text-slate-700 leading-relaxed font-medium space-y-1">
            <span class="font-bold text-[#881337] block">💡 Tiện ích cộng tác người thân:</span>
            <p>Gửi đường link này qua Zalo/Messenger cho Ba Mẹ, Anh chị em hoặc Phù rể/Phù dâu. Họ chỉ cần mở link trên điện thoại là có thể tự điền danh sách khách mời của từng nhà mà không cần tạo tài khoản!</p>
          </div>

          <!-- Share Link Copy Field -->
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

          <!-- Helper Instructions -->
          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3 text-xs text-slate-600">
            <Sparkles class="w-5 h-5 text-amber-500 shrink-0" />
            <span>Mọi khách mời được nhập qua link sẽ tự động đồng bộ realtime vào bảng quản lý của Dâu Rể.</span>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-5 bg-slate-50 border-t border-slate-100 flex justify-end">
          <button @click="showShareModal = false" class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition cursor-pointer">
            Hoàn tất
          </button>
        </div>

      </div>
    </div>
  </WorkspaceLayout>
</template>
