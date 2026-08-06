<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { 
  Calendar, 
  DollarSign, 
  Users, 
  FileText, 
  Store, 
  Palette, 
  Settings, 
  Sparkles, 
  Eye, 
  ChevronRight,
  ChevronLeft,
  Menu,
  X,
  Heart,
  LayoutDashboard,
  Search,
  Bell,
  Command,
  PanelLeftClose,
  PanelLeftOpen,
  PanelLeft,
  Image as ImageIcon,
  Clock as ClockIcon,
  Gift as GiftIcon
} from 'lucide-vue-next';
import GroundedAiDrawer from '@/Components/Wedding/GroundedAiDrawer.vue';
import CommandPaletteModal from '@/Components/Wedding/CommandPaletteModal.vue';

const props = defineProps<{
  title?: string;
  activeNav?: string;
}>();

const isAiDrawerOpen = ref(false);
const isMobileSidebarOpen = ref(false);
const isCommandPaletteOpen = ref(false);
const isSidebarCollapsed = ref(localStorage.getItem('eloria_sidebar_collapsed') === 'true');

const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
  localStorage.setItem('eloria_sidebar_collapsed', isSidebarCollapsed.value ? 'true' : 'false');
};

const navigationItems = [
  { id: 'timeline', label: 'Lộ trình & Task', href: '/wedding/timeline', icon: Calendar },
  { id: 'run-of-show', label: 'Kịch Bản Ngày Cưới', href: '/wedding/run-of-show', icon: ClockIcon },
  { id: 'budget', label: 'Ngân sách thu chi', href: '/wedding/budget', icon: DollarSign },
  { id: 'gift-log', label: 'Sổ Vàng Mừng Cưới', href: '/wedding/gift-log', icon: GiftIcon },
  { id: 'guests', label: 'Khách mời & Sơ đồ bàn', href: '/wedding/guests', icon: Users },
  { id: 'gallery', label: 'Album & Gallery Online', href: '/wedding/gallery', icon: ImageIcon },
  { id: 'invitation-editor', label: 'Tùy biến Thiệp cưới', href: '/wedding/invitation-editor', icon: Palette },
  { id: 'documents', label: 'Hợp đồng & Tài liệu', href: '/wedding/documents', icon: FileText },
  { id: 'vendors', label: 'Đối tác Vendor CRM', href: '/wedding/vendors', icon: Store },
  { id: 'settings', label: 'Cài đặt Workspace', href: '/wedding/settings', icon: Settings },
];

const page = usePage();

const handleGlobalKeyDown = (e: KeyboardEvent) => {
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault();
    isCommandPaletteOpen.value = !isCommandPaletteOpen.value;
  }
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'b') {
    e.preventDefault();
    toggleSidebar();
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleGlobalKeyDown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleGlobalKeyDown);
});
</script>

<template>
  <Head :title="title ? `${title} — Eloria OS` : 'Eloria — The Operating System for Planning a Wedding'" />

  <div class="min-h-screen bg-[#FAF8F5] bg-gradient-to-br from-rose-50/60 via-[#FAF8F5] to-amber-50/40 text-slate-900 font-sans flex antialiased">
    <!-- Desktop Left Sidebar Navigation (Collapsible) -->
    <aside 
      class="hidden lg:flex bg-white/90 backdrop-blur-xl border-r border-rose-100/80 flex-col justify-between sticky top-0 h-screen z-30 shadow-sm shrink-0 transition-all duration-300 ease-in-out overflow-hidden"
      :class="isSidebarCollapsed ? 'w-20' : 'w-64'"
    >
      <div class="p-4 space-y-6 overflow-y-auto overflow-x-hidden">
        <!-- Brand Logo & Workspace Header -->
        <div class="space-y-3">
          <div class="flex items-center justify-between" :class="isSidebarCollapsed ? 'flex-col gap-2' : ''">
            <Link href="/" class="flex items-center gap-3 group overflow-hidden">
              <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-10 w-10 min-w-10 rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
              <span v-if="!isSidebarCollapsed" class="font-serif text-2xl font-extrabold text-slate-900 tracking-tight whitespace-nowrap">Eloria</span>
            </Link>

            <!-- Toggle Collapse Button -->
            <button 
              @click="toggleSidebar"
              class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-rose-50 transition cursor-pointer"
              :title="isSidebarCollapsed ? 'Mở rộng Sidebar (⌘B)' : 'Thu gọn Sidebar (⌘B)'"
            >
              <PanelLeftOpen v-if="isSidebarCollapsed" class="w-5 h-5 text-rose-700" />
              <PanelLeftClose v-else class="w-5 h-5 text-slate-400" />
            </button>
          </div>

          <!-- Active Workspace Pill with Clearly Separated Groom & Bride Names -->
          <div 
            class="p-3 rounded-2xl bg-gradient-to-r from-rose-100/80 to-amber-50/70 border border-rose-200/60 text-rose-950 flex items-center justify-between shadow-2xs"
            :class="isSidebarCollapsed ? 'justify-center p-2.5' : ''"
          >
            <div v-if="!isSidebarCollapsed" class="text-xs space-y-1.5 min-w-0 flex-1 pr-2">
              <span class="text-[10px] font-extrabold text-rose-800 uppercase tracking-widest block whitespace-nowrap">WORKSPACE DÂU RỂ</span>
              <div class="space-y-1.5">
                <div class="flex items-center gap-1.5 text-[11px]">
                  <span class="px-1.5 py-0.5 rounded-md bg-rose-600 text-white font-extrabold text-[9px] uppercase shrink-0">Chú Rể</span>
                  <span class="font-bold text-slate-900 truncate">{{ ($page.props as any).workspace?.groom_name || 'Nguyễn Hoàng Quốc Trung' }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-[11px]">
                  <span class="px-1.5 py-0.5 rounded-md bg-pink-600 text-white font-extrabold text-[9px] uppercase shrink-0">Cô Dâu</span>
                  <span class="font-bold text-slate-900 truncate">{{ ($page.props as any).workspace?.bride_name || 'Lê Thị Hồng Vân' }}</span>
                </div>
              </div>
            </div>
            <Heart class="w-4 h-4 text-rose-500 fill-rose-500 shrink-0" :title="isSidebarCollapsed ? 'Workspace Dâu Rể Active' : ''" />
          </div>
        </div>

        <!-- Navigation Links Menu -->
        <nav class="space-y-1.5">
          <span v-if="!isSidebarCollapsed" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2 whitespace-nowrap">QUẢN LÝ KẾ HOẠCH</span>
          
          <Link
            v-for="item in navigationItems"
            :key="item.id"
            :href="item.href"
            :title="isSidebarCollapsed ? item.label : ''"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200"
            :class="[
              activeNav === item.id || page.url.startsWith(item.href)
                ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20 font-bold'
                : 'text-slate-600 hover:text-rose-900 hover:bg-rose-50/80',
              isSidebarCollapsed ? 'justify-center px-0' : ''
            ]"
          >
            <component :is="item.icon" class="w-4 h-4 shrink-0" />
            <span v-if="!isSidebarCollapsed" class="whitespace-nowrap">{{ item.label }}</span>
          </Link>
        </nav>
      </div>

      <!-- Left Sidebar Footer Actions -->
      <div class="p-4 border-t border-rose-100/80 space-y-2.5 bg-rose-50/30">
        <!-- AI Assistant Drawer Trigger Button -->
        <button
          @click="isAiDrawerOpen = true"
          :title="isSidebarCollapsed ? 'Grounded AI Assistant' : ''"
          class="w-full py-2.5 rounded-xl bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 text-white font-semibold text-xs shadow-md hover:shadow-lg transition flex items-center justify-between cursor-pointer"
          :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
        >
          <div class="flex items-center gap-2">
            <Sparkles class="w-4 h-4 text-rose-300 shrink-0" />
            <span v-if="!isSidebarCollapsed" class="whitespace-nowrap">Grounded AI</span>
          </div>
          <span v-if="!isSidebarCollapsed" class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
        </button>

        <!-- Preview Live Invitation -->
        <Link
          href="/wedding"
          target="_blank"
          :title="isSidebarCollapsed ? 'Xem Thiệp Khách Mời' : ''"
          class="w-full py-2 rounded-xl bg-white border border-rose-200/80 text-rose-900 font-semibold text-xs hover:bg-rose-50 transition flex items-center justify-center gap-2 cursor-pointer shadow-2xs"
          :class="isSidebarCollapsed ? 'px-0' : 'px-3.5'"
        >
          <Eye class="w-3.5 h-3.5 text-rose-600 shrink-0" />
          <span v-if="!isSidebarCollapsed" class="whitespace-nowrap">Xem Thiệp Khách Mời</span>
        </Link>
      </div>
    </aside>

    <!-- Mobile Top Navigation Header -->
    <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white/90 backdrop-blur-md border-b border-rose-100 px-4 h-16 flex items-center justify-between shadow-2xs">
      <Link href="/" class="flex items-center gap-2">
        <img src="/images/logo/eloria-logo-icon.jpg" alt="Logo" class="h-8 w-auto rounded-lg" />
        <span class="font-serif font-bold text-xl text-slate-900">Eloria</span>
      </Link>

      <button @click="isMobileSidebarOpen = !isMobileSidebarOpen" class="p-2 rounded-xl text-slate-700 hover:bg-rose-50 cursor-pointer">
        <Menu v-if="!isMobileSidebarOpen" class="w-6 h-6" />
        <X v-else class="w-6 h-6" />
      </button>
    </div>

    <!-- Mobile Navigation Drawer Overlay -->
    <div v-if="isMobileSidebarOpen" class="lg:hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex justify-start" @click="isMobileSidebarOpen = false">
      <div class="w-72 bg-white h-full p-6 space-y-6 shadow-2xl overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
          <span class="font-serif font-bold text-lg text-slate-900">Eloria OS</span>
          <button @click="isMobileSidebarOpen = false" class="cursor-pointer"><X class="w-5 h-5 text-slate-400" /></button>
        </div>

        <nav class="space-y-2">
          <Link
            v-for="item in navigationItems"
            :key="item.id"
            :href="item.href"
            @click="isMobileSidebarOpen = false"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold"
            :class="[
              activeNav === item.id || page.url.startsWith(item.href)
                ? 'bg-rose-600 text-white font-bold'
                : 'text-slate-600 hover:bg-rose-50'
            ]"
          >
            <component :is="item.icon" class="w-4 h-4" />
            <span>{{ item.label }}</span>
          </Link>
        </nav>
      </div>
    </div>

    <!-- Main Workspace Page Container -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Global Workspace Header Bar -->
      <header class="bg-white/80 backdrop-blur-md border-b border-rose-100/80 px-6 py-3.5 sticky top-0 z-20 flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-4 min-w-0">
          <button 
            @click="toggleSidebar"
            class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
            :title="isSidebarCollapsed ? 'Mở rộng Sidebar' : 'Thu gọn Sidebar'"
          >
            <PanelLeft class="w-4 h-4" />
          </button>

          <!-- Breadcrumb Navigation -->
          <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <span class="text-rose-900 font-bold uppercase tracking-wider text-[11px] bg-rose-50 px-2.5 py-1 rounded-md border border-rose-200/80">ELORIA OS WORKSPACE</span>
            <ChevronRight class="w-3.5 h-3.5 text-slate-300" />
            <span class="text-slate-900 font-bold truncate">{{ title || 'Bảng Điều Khiển' }}</span>
          </div>
        </div>

        <!-- Right Quick Actions Bar -->
        <div class="flex items-center gap-3">
          <!-- Command Palette Keyboard Shortcut Button -->
          <button 
            @click="isCommandPaletteOpen = true"
            class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-200/70 text-xs font-medium transition cursor-pointer shadow-2xs"
          >
            <Search class="w-3.5 h-3.5 text-slate-400" />
            <span>Tìm nhanh task, thu chi...</span>
            <kbd class="px-1.5 py-0.5 bg-white border border-slate-300 rounded text-[10px] font-mono text-slate-600 font-bold">⌘K</kbd>
          </button>

          <!-- AI Assistant Header Button -->
          <button 
            @click="isAiDrawerOpen = true" 
            class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-900 text-xs font-bold border border-rose-200/80 transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
          >
            <Sparkles class="w-3.5 h-3.5 text-rose-600 animate-spin-slow" />
            <span class="hidden sm:inline">Trợ Lý AI</span>
          </button>

          <!-- Active User Profile Avatar with Clearly Separated Couple Badges -->
          <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200/80">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-rose-500 to-amber-400 text-white font-bold flex items-center justify-center text-xs shadow-xs border border-white">
              {{ ($page.props as any).auth?.user?.name?.[0] || 'T' }}
            </div>
            <div class="hidden md:block text-left text-xs leading-tight">
              <span class="font-bold text-slate-900 block truncate max-w-[130px]">{{ ($page.props as any).auth?.user?.name || 'Nguyễn Hoàng Quốc Trung' }}</span>
              <span class="text-[10px] text-rose-800 font-extrabold block">Dâu Rể Owner</span>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Page Content Slot -->
      <div class="flex-1">
        <slot />
      </div>
    </div>

    <!-- Slide-over Grounded AI Assistant Drawer -->
    <GroundedAiDrawer :is-open="isAiDrawerOpen" @close="isAiDrawerOpen = false" />

    <!-- Command Palette Keyboard Modal (Cmd+K) -->
    <CommandPaletteModal :is-open="isCommandPaletteOpen" @close="isCommandPaletteOpen = false" />
  </div>
</template>
