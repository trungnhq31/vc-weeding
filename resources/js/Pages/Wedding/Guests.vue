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
  Download
} from 'lucide-vue-next';

const activeTab = ref<'list' | 'seating'>('list');
const draggedGuest = ref<any | null>(null);

const guests = ref([
  { id: '1', name: 'Nguyễn Văn Anh', role: 'Chú Rể', phone: '0901234567', status: 'confirmed', table: 'Bàn VIP 1 (Họ Hàng)', diet: 'Không cay' },
  { id: '2', name: 'Trần Thị Bích', role: 'Cô Dâu', phone: '0909876543', status: 'confirmed', table: 'Bàn VIP 1 (Họ Hàng)', diet: 'Bình thường' },
  { id: '3', name: 'Lê Hoàng Nam', role: 'Bạn Học', phone: '0912345678', status: 'confirmed', table: 'Bàn Bạn Học 1', diet: 'Ăn chay' },
  { id: '4', name: 'Phạm Minh Tâm', role: 'Đồng Nghiệp', phone: '0987654321', status: 'pending', table: 'Bàn Công Ty', diet: 'Bình thường' },
  { id: '5', name: 'Đặng Tuấn Kiệt', role: 'Họ Hàng Dâu', phone: '0933445566', status: 'confirmed', table: 'Chưa xếp', diet: '-' },
  { id: '6', name: 'Vũ Quốc Huy', role: 'Bạn Chú Rể', phone: '0977889900', status: 'confirmed', table: 'Chưa xếp', diet: 'Ăn chay' },
]);

const tables = ref([
  { id: 't1', name: 'Bàn VIP 1 (Họ Hàng)', capacity: 10, assignedCount: 2, shape: 'round', zone: 'Sân Chính', isOverloaded: false },
  { id: 't2', name: 'Bàn VIP 2 (Họ Hàng Dâu)', capacity: 10, assignedCount: 0, shape: 'round', zone: 'Sân Chính', isOverloaded: false },
  { id: 't3', name: 'Bàn Bạn Học 1', capacity: 10, assignedCount: 1, shape: 'round', zone: 'Khu Bên Trái', isOverloaded: false },
  { id: 't4', name: 'Bàn Công Ty', capacity: 10, assignedCount: 1, shape: 'round', zone: 'Khu Bên Phải', isOverloaded: false },
]);

const unassignedGuests = computed(() => guests.value.filter(g => g.table === 'Chưa xếp' || !g.table));

const getTableGuests = (tableName: string) => {
  return guests.value.filter(g => g.table === tableName);
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
    const count = guests.value.filter(g => g.table === table.name).length;
    table.assignedCount = count;
    table.isOverloaded = count > table.capacity;
  });
};

const totalGuests = computed(() => guests.value.length);
const confirmedCount = computed(() => guests.value.filter(g => g.status === 'confirmed').length);
const pendingCount = computed(() => guests.value.filter(g => g.status === 'pending').length);
const declinedCount = computed(() => guests.value.filter(g => g.status === 'declined').length);
</script>

<template>
  <WorkspaceLayout title="Khách Mời & Sơ Đồ Bàn Tiệc" active-nav="guests">
    <div class="space-y-8">
      <!-- Overview Metrics -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Tổng Khách Mời</span>
          <div class="text-2xl font-bold text-slate-900 mt-1">{{ totalGuests }} Người</div>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Đã Xác Nhận (RSVP Yes)</span>
          <div class="text-2xl font-bold text-emerald-600 mt-1">{{ confirmedCount }} Người</div>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Chờ Phản Hồi</span>
          <div class="text-2xl font-bold text-amber-600 mt-1">{{ pendingCount }} Người</div>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
          <span class="text-xs text-slate-500 font-medium">Từ Chối / Bận</span>
          <div class="text-2xl font-bold text-slate-400 mt-1">{{ declinedCount }} Người</div>
        </div>
      </div>

      <!-- Tab Navigation Switcher -->
      <div class="flex items-center gap-2 mb-6 border-b border-slate-200 pb-3">
        <button 
          @click="activeTab = 'list'"
          class="px-4 py-2 rounded-xl font-semibold text-xs transition-all"
          :class="activeTab === 'list' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
        >
          Danh Sách Khách Mời (RSVP)
        </button>
        <button 
          @click="activeTab = 'seating'"
          class="px-4 py-2 rounded-xl font-semibold text-xs transition-all flex items-center gap-1.5"
          :class="activeTab === 'seating' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
        >
          <Grid class="w-4 h-4 text-rose-400" />
          Sơ Đồ Bàn Tiệc Kéo Thả (Seating Canvas)
        </button>
      </div>

      <!-- Tab 1: Guest List Table -->
      <div v-if="activeTab === 'list'" class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
          <div class="relative w-64">
            <Search class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input type="text" placeholder="Tìm tên, SĐT khách..." class="pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs w-full focus:outline-hidden focus:border-rose-300" />
          </div>
          <div class="flex items-center gap-2">
            <a 
              href="/wedding/guests/export" 
              target="_blank"
              class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition flex items-center gap-1.5 shadow-2xs"
            >
              <Download class="w-4 h-4 text-slate-500" /> Xuất CSV
            </a>
            <button class="px-4 py-2 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 hover:bg-rose-100 font-semibold text-xs transition flex items-center gap-1.5 cursor-pointer">
              <Plus class="w-4 h-4" /> Thêm Khách Mời
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
              <th class="p-4">Ghi chú khẩu vị</th>
              <th class="p-4">Xác nhận RSVP</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="guest in guests" :key="guest.id" class="hover:bg-slate-50/80 transition-colors">
              <td class="p-4 font-bold text-slate-900">{{ guest.name }}</td>
              <td class="p-4">
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-medium">{{ guest.role }}</span>
              </td>
              <td class="p-4 text-slate-600 font-mono">{{ guest.phone }}</td>
              <td class="p-4 font-semibold text-slate-800">{{ guest.table }}</td>
              <td class="p-4 text-slate-500">{{ guest.diet }}</td>
              <td class="p-4">
                <span v-if="guest.status === 'confirmed'" class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-semibold flex items-center gap-1 w-max">
                  <CheckCircle2 class="w-3 h-3 text-emerald-600" /> Tham dự
                </span>
                <span v-else-if="guest.status === 'pending'" class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-semibold flex items-center gap-1 w-max">
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
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
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
  </WorkspaceLayout>
</template>
