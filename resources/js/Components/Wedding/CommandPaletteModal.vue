<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import { 
  Search, 
  Calendar, 
  DollarSign, 
  Users, 
  Palette, 
  FileText, 
  Store, 
  Settings, 
  Sparkles, 
  PlusCircle, 
  ArrowRight,
  Command,
  X
} from 'lucide-vue-next';

const props = defineProps<{
  isOpen: boolean;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
  (e: 'open-ai'): void;
  (e: 'create-task'): void;
  (e: 'create-budget'): void;
  (e: 'create-guest'): void;
  (e: 'create-vendor'): void;
}>();

const searchQuery = ref('');
const selectedIndex = ref(0);
const inputRef = ref<HTMLInputElement | null>(null);

const items = [
  // Navigation
  { id: 'nav-timeline', type: 'nav', title: 'Lộ trình & Task', subtitle: 'Quản lý danh sách việc cần làm và tiến độ đám cưới', icon: Calendar, action: () => router.visit('/wedding/timeline'), category: 'Điều hướng' },
  { id: 'nav-budget', type: 'nav', title: 'Ngân sách thu chi', subtitle: 'Theo dõi dòng tiền, chi phí thực tế và hạn thanh toán', icon: DollarSign, action: () => router.visit('/wedding/budget'), category: 'Điều hướng' },
  { id: 'nav-guests', type: 'nav', title: 'Khách mời & Sơ đồ bàn', subtitle: 'Danh sách khách, trạng thái RSVP và xếp bàn tiệc', icon: Users, action: () => router.visit('/wedding/guests'), category: 'Điều hướng' },
  { id: 'nav-invitation', type: 'nav', title: 'Tùy biến Thiệp cưới', subtitle: 'Chỉnh sửa giao diện thiệp online và nhạc nền', icon: Palette, action: () => router.visit('/wedding/invitation-editor'), category: 'Điều hướng' },
  { id: 'nav-documents', type: 'nav', title: 'Hợp đồng & Tài liệu', subtitle: 'Lưu trữ tài liệu, hợp đồng vendor và báo giá', icon: FileText, action: () => router.visit('/wedding/documents'), category: 'Điều hướng' },
  { id: 'nav-vendors', type: 'nav', title: 'Đối tác Vendor CRM', subtitle: 'Danh bạ đối tác, dịch vụ chụp ảnh, thi công, trang điểm', icon: Store, action: () => router.visit('/wedding/vendors'), category: 'Điều hướng' },
  { id: 'nav-settings', type: 'nav', title: 'Cài đặt Workspace', subtitle: 'Thông tin chú rể - cô dâu, ngày cưới, phân quyền', icon: Settings, action: () => router.visit('/wedding/settings'), category: 'Điều hướng' },

  // Quick Actions
  { id: 'act-ai', type: 'action', title: 'Hỏi Grounded AI Assistant', subtitle: 'Tra cứu thu chi, task quá hạn hay hợp đồng qua AI', icon: Sparkles, action: () => { emit('close'); emit('open-ai'); }, category: 'Thao tác nhanh' },
  { id: 'act-task', type: 'action', title: 'Tạo Task mới', subtitle: 'Thêm công việc vào lộ trình chuẩn bị', icon: PlusCircle, action: () => { emit('close'); emit('create-task'); }, category: 'Thao tác nhanh' },
  { id: 'act-budget', type: 'action', title: 'Thêm khoản Thu / Chi', subtitle: 'Ghi nhận chi phí hoặc cọc vendor mới', icon: PlusCircle, action: () => { emit('close'); emit('create-budget'); }, category: 'Thao tác nhanh' },
  { id: 'act-guest', type: 'action', title: 'Thêm Khách mời', subtitle: 'Đăng ký thông tin khách mới vào danh sách', icon: PlusCircle, action: () => { emit('close'); emit('create-guest'); }, category: 'Thao tác nhanh' },
  { id: 'act-vendor', type: 'action', title: 'Thêm Vendor mới', subtitle: 'Lưu trữ thông tin nhà cung cấp dịch vụ', icon: PlusCircle, action: () => { emit('close'); emit('create-vendor'); }, category: 'Thao tác nhanh' },
];

const filteredItems = computed(() => {
  if (!searchQuery.value.trim()) return items;
  const q = searchQuery.value.toLowerCase().trim();
  return items.filter(item => 
    item.title.toLowerCase().includes(q) || 
    item.subtitle.toLowerCase().includes(q) ||
    item.category.toLowerCase().includes(q)
  );
});

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    searchQuery.value = '';
    selectedIndex.value = 0;
    nextTick(() => {
      inputRef.value?.focus();
    });
  }
});

watch(searchQuery, () => {
  selectedIndex.value = 0;
});

const executeSelected = () => {
  if (filteredItems.value.length > 0 && selectedIndex.value < filteredItems.value.length) {
    const item = filteredItems.value[selectedIndex.value];
    item.action();
    emit('close');
  }
};

const handleKeyDown = (e: KeyboardEvent) => {
  if (!props.isOpen) return;

  if (e.key === 'ArrowDown') {
    e.preventDefault();
    selectedIndex.value = (selectedIndex.value + 1) % Math.max(1, filteredItems.value.length);
  } else if (e.key === 'ArrowUp') {
    e.preventDefault();
    selectedIndex.value = (selectedIndex.value - 1 + filteredItems.value.length) % Math.max(1, filteredItems.value.length);
  } else if (e.key === 'Enter') {
    e.preventDefault();
    executeSelected();
  } else if (e.key === 'Escape') {
    e.preventDefault();
    emit('close');
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-150 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="isOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-16 sm:pt-24 px-4">
        <!-- Backdrop Overlay -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" @click="emit('close')" />

        <!-- Command Modal Panel -->
        <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden z-10 flex flex-col max-h-[80vh]">
          <!-- Input Search Header -->
          <div class="p-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <Search class="w-5 h-5 text-slate-400 shrink-0" />
            <input
              ref="inputRef"
              v-model="searchQuery"
              type="text"
              placeholder="Gõ lệnh, tìm trang hoặc thao tác nhanh... (Cmd+K)"
              class="w-full bg-transparent text-sm text-slate-800 placeholder:text-slate-400 focus:outline-hidden"
            />
            <div class="flex items-center gap-1.5 shrink-0">
              <kbd class="px-2 py-0.5 text-[10px] font-mono font-semibold text-slate-500 bg-white border border-slate-200 rounded-md shadow-2xs">ESC</kbd>
            </div>
          </div>

          <!-- Items List -->
          <div class="overflow-y-auto p-2 space-y-1 divide-y divide-slate-100">
            <div v-if="filteredItems.length === 0" class="p-8 text-center text-slate-400 text-xs">
              Không tìm thấy lệnh hoặc chức năng phù hợp với "{{ searchQuery }}".
            </div>

            <div
              v-for="(item, index) in filteredItems"
              :key="item.id"
              @click="item.action(); emit('close')"
              @mouseenter="selectedIndex = index"
              class="px-3.5 py-3 rounded-xl flex items-center justify-between cursor-pointer transition-colors duration-150"
              :class="[
                selectedIndex === index ? 'bg-slate-100 text-slate-900' : 'text-slate-700 hover:bg-slate-50'
              ]"
            >
              <div class="flex items-center gap-3.5 min-w-0">
                <div 
                  class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                  :class="[
                    item.type === 'action' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-slate-100 text-slate-600'
                  ]"
                >
                  <component :is="item.icon" class="w-4 h-4" />
                </div>

                <div class="min-w-0">
                  <div class="flex items-center gap-2">
                    <span class="text-xs font-bold truncate text-slate-900">{{ item.title }}</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full font-medium bg-slate-200/60 text-slate-600 shrink-0">
                      {{ item.category }}
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ item.subtitle }}</p>
                </div>
              </div>

              <div class="flex items-center gap-1.5 shrink-0 pl-2">
                <ArrowRight v-if="selectedIndex === index" class="w-4 h-4 text-slate-400" />
              </div>
            </div>
          </div>

          <!-- Modal Footer Hints -->
          <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
            <div class="flex items-center gap-3">
              <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-white border rounded text-[10px]">↑↓</kbd> Di chuyển</span>
              <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-white border rounded text-[10px]">↵</kbd> Chọn</span>
            </div>
            <div class="flex items-center gap-1">
              <Command class="w-3.5 h-3.5 text-slate-400" />
              <span>Eloria OS Palette</span>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
