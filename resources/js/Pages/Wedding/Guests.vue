<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
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
  Heart
} from 'lucide-vue-next';

const activeTab = ref<'list' | 'seating'>('list');

const guests = ref([
  { id: '1', name: 'Nguyễn Văn Anh', role: 'Chú Rể', phone: '0901234567', status: 'confirmed', table: 'Bàn VIP 1', diet: 'Không cay' },
  { id: '2', name: 'Trần Thị Bích', role: 'Cô Dâu', phone: '0909876543', status: 'confirmed', table: 'Bàn VIP 1', diet: 'Bình thường' },
  { id: '3', name: 'Lê Hoàng Nam', role: 'Bạn Học', phone: '0912345678', status: 'confirmed', table: 'Bàn Bạn Học 1', diet: 'Ăn chay' },
  { id: '4', name: 'Phạm Minh Tâm', role: 'Đồng Nghiệp', phone: '0987654321', status: 'pending', table: 'Bàn Công Ty', diet: 'Bình thường' },
  { id: '5', name: 'Đặng Tuấn Kiệt', role: 'Họ Hàng Dâu', phone: '0933445566', status: 'declined', table: 'Chưa xếp', diet: '-' },
]);

const tables = ref([
  { id: 't1', name: 'Bàn VIP 1 (Họ Hàng)', capacity: 10, assignedCount: 8, shape: 'round', zone: 'Sân Sân Chính' },
  { id: 't2', name: 'Bàn VIP 2 (Họ Hàng Dâu)', capacity: 10, assignedCount: 10, shape: 'round', zone: 'Sân Chính' },
  { id: 't3', name: 'Bàn Bạn Học 1', capacity: 10, assignedCount: 11, shape: 'round', zone: 'Khu Bên Trái', isOverloaded: true },
  { id: 't4', name: 'Bàn Công Ty', capacity: 10, assignedCount: 6, shape: 'round', zone: 'Khu Bên Phải' },
]);

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
          <button class="px-4 py-2 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 hover:bg-rose-100 font-semibold text-xs transition flex items-center gap-1.5">
            <Plus class="w-4 h-4" /> Thêm Khách Mời
          </button>
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
              <h3 class="text-base font-bold text-slate-900">Sơ Đồ Bàn Tiệc (Seating Planner Canvas)</h3>
              <p class="text-xs text-slate-500">Kéo thả vị trí khách mời vào từng bàn tiệc & kiểm soát sức chứa</p>
            </div>
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition">
              + Thêm Bàn Tiệc Mới
            </button>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div 
              v-for="table in tables" 
              :key="table.id" 
              class="p-5 rounded-2xl border transition-all flex flex-col justify-between"
              :class="table.isOverloaded ? 'bg-rose-50/70 border-rose-300' : 'bg-[#FAF8F5] border-slate-200'"
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
                <div class="text-xs text-slate-600 mb-4">
                  Sức chứa: <strong>{{ table.assignedCount }}/{{ table.capacity }}</strong> Khách
                </div>

                <!-- Table visual indicator -->
                <div class="w-full h-24 rounded-2xl border-2 border-dashed border-slate-300 flex items-center justify-center text-xs font-medium text-slate-400 bg-white">
                  Drag & Drop Guest Here
                </div>
              </div>

              <div class="mt-4 pt-3 border-t border-slate-200 flex justify-between text-xs font-medium text-slate-600">
                <span>Dạng: {{ table.shape }}</span>
                <span class="text-rose-700 cursor-pointer hover:underline">Chi tiết →</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </WorkspaceLayout>
</template>
