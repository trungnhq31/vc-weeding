<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
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
  Eye, 
  Plus, 
  X,
  ArrowRight,
  Command,
  FileSpreadsheet
} from 'lucide-vue-next';

const props = defineProps<{
  isOpen: boolean;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
  (e: 'open-ai'): void;
  (e: 'open-add-task'): void;
  (e: 'open-add-budget'): void;
  (e: 'open-add-guest'): void;
}>();

const searchQuery = ref('');

const navigationCommands = [
  { id: 'nav-timeline', name: 'Chuyển đến: Lộ trình & Task', section: 'Điều Hướng', href: '/wedding/timeline', icon: Calendar },
  { id: 'nav-visualizer', name: 'Chuyển đến: Mô Phỏng Trực Quan 3D (Visualizer)', section: 'Điều Hướng', href: '/wedding/visualizer', icon: Sparkles },
  { id: 'nav-budget', name: 'Chuyển đến: Ngân sách thu chi', section: 'Điều Hướng', href: '/wedding/budget', icon: DollarSign },
  { id: 'nav-guests', name: 'Chuyển đến: Khách mời & Bàn tiệc', section: 'Điều Hướng', href: '/wedding/guests', icon: Users },
  { id: 'nav-invitations', name: 'Chuyển đến: Tùy biến Thiệp cưới', section: 'Điều Hướng', href: '/wedding/invitation-editor', icon: Palette },
  { id: 'nav-documents', name: 'Chuyển đến: Hợp đồng & Tài liệu', section: 'Điều Hướng', href: '/wedding/documents', icon: FileText },
  { id: 'nav-vendors', name: 'Chuyển đến: Đối tác Vendor CRM', section: 'Điều Hướng', href: '/wedding/vendors', icon: Store },
  { id: 'nav-settings', name: 'Chuyển đến: Cài đặt Workspace', section: 'Điều Hướng', href: '/wedding/settings', icon: Settings },
];

const actionCommands = [
  { id: 'act-export-excel', name: 'Xuất toàn bộ Plan Cưới ra file Excel (.xlsx)', section: 'Xuất Dữ Liệu', icon: FileSpreadsheet, action: () => window.open('/wedding/export-excel', '_blank') },
  { id: 'act-ai', name: 'Hỏi Trợ lý AI Grounded Data', section: 'Thao Tác Nhanh', icon: Sparkles, action: () => emit('open-ai') },
  { id: 'act-preview', name: 'Xem Thiệp Cưới Khách Mời Live', section: 'Thao Tác Nhanh', icon: Eye, action: () => window.open('/wedding', '_blank') },
  { id: 'act-add-task', name: 'Tạo Task mới vào Lộ trình', section: 'Tạo Mới', icon: Plus, action: () => emit('open-add-task') },
  { id: 'act-add-budget', name: 'Thêm Khoản Chi Ngân Sách mới', section: 'Tạo Mới', icon: Plus, action: () => emit('open-add-budget') },
  { id: 'act-add-guest', name: 'Thêm Khách Mời mới vào RSVP', section: 'Tạo Mới', icon: Plus, action: () => emit('open-add-guest') },
];

const filteredCommands = computed(() => {
  const q = searchQuery.value.toLowerCase().trim();
  if (!q) return [...actionCommands, ...navigationCommands];
  return [...actionCommands, ...navigationCommands].filter(cmd => 
    cmd.name.toLowerCase().includes(q) || cmd.section.toLowerCase().includes(q)
  );
});

const executeCommand = (cmd: any) => {
  emit('close');
  searchQuery.value = '';
  if (cmd.href) {
    router.visit(cmd.href);
  } else if (cmd.action) {
    cmd.action();
  }
};
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-start justify-center pt-20 p-4" @click.self="emit('close')">
    <div class="w-full max-w-xl bg-white rounded-3xl border border-rose-100 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
      <!-- Search Input Header -->
      <div class="p-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
        <Search class="w-5 h-5 text-slate-400 shrink-0" />
        <input 
          v-model="searchQuery"
          type="text" 
          placeholder="Nhập lệnh hoặc tìm kiếm trang, công việc... (Cmd+K)" 
          class="w-full bg-transparent text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none"
          autofocus
        />
        <kbd class="hidden sm:inline-flex items-center gap-0.5 px-2 py-0.5 bg-white border border-slate-200 rounded text-[10px] text-slate-500 font-mono">
          ESC
        </kbd>
        <button @click="emit('close')" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
          <X class="w-4 h-4" />
        </button>
      </div>

      <!-- Commands List -->
      <div class="max-h-96 overflow-y-auto p-3 space-y-1">
        <div v-if="filteredCommands.length === 0" class="p-8 text-center text-xs text-slate-400">
          Không tìm thấy kết quả phù hợp với "{{ searchQuery }}"
        </div>

        <button
          v-for="cmd in filteredCommands"
          :key="cmd.id"
          @click="executeCommand(cmd)"
          class="w-full px-3.5 py-2.5 rounded-2xl flex items-center justify-between text-xs transition-colors hover:bg-rose-50/80 group cursor-pointer text-left"
        >
          <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-slate-100 text-slate-600 group-hover:bg-rose-100 group-hover:text-rose-800 transition-colors">
              <component :is="cmd.icon" class="w-4 h-4" />
            </div>
            <div>
              <div class="font-bold text-slate-900 group-hover:text-rose-950">{{ cmd.name }}</div>
              <div class="text-[10px] text-slate-400">{{ cmd.section }}</div>
            </div>
          </div>
          <ArrowRight class="w-4 h-4 text-slate-300 group-hover:text-rose-600 transition-transform group-hover:translate-x-0.5" />
        </button>
      </div>

      <!-- Command Palette Footer -->
      <div class="p-3 border-t border-slate-100 bg-slate-50/80 text-[10px] text-slate-500 flex items-center justify-between px-5">
        <div class="flex items-center gap-2">
          <Command class="w-3.5 h-3.5 text-rose-500" />
          <span>Eloria Universal Command Palette</span>
        </div>
        <span>Bấm <kbd class="font-mono bg-white px-1.5 py-0.5 border border-slate-200 rounded text-slate-700">Enter</kbd> để chọn</span>
      </div>
    </div>
  </div>
</template>
