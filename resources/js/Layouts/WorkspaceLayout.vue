<script setup lang="ts">
import { ref } from 'vue';
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
  Menu,
  X,
  Heart,
  LayoutDashboard,
  Search,
  Bell
} from 'lucide-vue-next';
import GroundedAiDrawer from '@/Components/Wedding/GroundedAiDrawer.vue';

const props = defineProps<{
  title?: string;
  activeNav?: string;
}>();

const isAiDrawerOpen = ref(false);
const isMobileSidebarOpen = ref(false);

const navigationItems = [
  { id: 'timeline', label: 'Lộ trình & Task', href: '/wedding/timeline', icon: Calendar },
  { id: 'budget', label: 'Ngân sách thu chi', href: '/wedding/budget', icon: DollarSign },
  { id: 'guests', label: 'Khách mời & Sơ đồ bàn', href: '/wedding/guests', icon: Users },
  { id: 'invitation-editor', label: 'Tùy biến Thiệp cưới', href: '/wedding/invitation-editor', icon: Palette },
  { id: 'documents', label: 'Hợp đồng & Tài liệu', href: '/wedding/documents', icon: FileText },
  { id: 'vendors', label: 'Đối tác Vendor CRM', href: '/wedding/vendors', icon: Store },
  { id: 'settings', label: 'Cài đặt Workspace', href: '/wedding/settings', icon: Settings },
];

const page = usePage();
</script>

<template>
  <Head :title="title ? `${title} — Eloria OS` : 'Eloria — The Operating System for Planning a Wedding'" />

  <div class="min-h-screen bg-[#FAF8F5] bg-gradient-to-br from-rose-50/60 via-[#FAF8F5] to-amber-50/40 text-slate-900 font-sans flex antialiased">
    <!-- Desktop Left Sidebar Navigation -->
    <aside class="hidden lg:flex w-64 bg-white/90 backdrop-blur-xl border-r border-rose-100/80 flex-col justify-between sticky top-0 h-screen z-30 shadow-sm shrink-0">
      <div class="p-6 space-y-8 overflow-y-auto">
        <!-- Brand Logo & Workspace Header -->
        <div class="space-y-3">
          <Link href="/" class="flex items-center gap-3 group">
            <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-10 w-auto rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
            <span class="font-serif text-2xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
          </Link>

          <!-- Active Workspace Pill -->
          <div class="p-3.5 rounded-2xl bg-gradient-to-r from-rose-100/80 to-amber-50/70 border border-rose-200/60 text-rose-950 flex items-center justify-between shadow-2xs">
            <div class="text-xs space-y-0.5">
              <span class="text-[10px] font-bold text-rose-700 uppercase tracking-widest block">WORKSPACE DÂU RỂ</span>
              <span class="font-bold font-serif text-slate-900 block truncate max-w-[150px]" :title="($page.props as any).workspace?.name">
                {{ ($page.props as any).workspace?.groom_name && ($page.props as any).workspace?.bride_name ? `Đám Cưới ${($page.props as any).workspace.groom_name} & ${($page.props as any).workspace.bride_name}` : (($page.props as any).workspace?.name || 'Đám Cưới Quốc Trung & Hồng Vân') }}
              </span>
            </div>
            <Heart class="w-4 h-4 text-rose-500 fill-rose-500 shrink-0" />
          </div>
        </div>

        <!-- Navigation Links Menu -->
        <nav class="space-y-1.5">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2">QUẢN LÝ KẾ HOẠCH</span>
          <Link
            v-for="item in navigationItems"
            :key="item.id"
            :href="item.href"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200"
            :class="[
              activeNav === item.id || page.url.startsWith(item.href)
                ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20 font-bold'
                : 'text-slate-600 hover:text-rose-900 hover:bg-rose-50/80'
            ]"
          >
            <component :is="item.icon" class="w-4 h-4 shrink-0" />
            <span>{{ item.label }}</span>
          </Link>
        </nav>
      </div>

      <!-- Left Sidebar Footer Actions -->
      <div class="p-6 border-t border-rose-100/80 space-y-3 bg-rose-50/30">
        <!-- AI Assistant Drawer Trigger Button -->
        <button
          @click="isAiDrawerOpen = true"
          class="w-full px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 text-white font-semibold text-xs shadow-md hover:shadow-lg transition flex items-center justify-between cursor-pointer"
        >
          <div class="flex items-center gap-2">
            <Sparkles class="w-4 h-4 text-rose-300" />
            <span>Grounded AI</span>
          </div>
          <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
        </button>

        <!-- Preview Live Invitation -->
        <Link
          href="/wedding"
          target="_blank"
          class="w-full px-3.5 py-2 rounded-xl bg-white border border-rose-200/80 text-rose-900 font-semibold text-xs hover:bg-rose-50 transition flex items-center justify-center gap-2 cursor-pointer shadow-2xs"
        >
          <Eye class="w-3.5 h-3.5 text-rose-600" />
          <span>Xem Thiệp Khách Mời</span>
        </Link>
      </div>
    </aside>

    <!-- Mobile Top Navigation Header -->
    <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white/90 backdrop-blur-md border-b border-rose-100 px-4 h-16 flex items-center justify-between shadow-2xs">
      <Link href="/" class="flex items-center gap-2">
        <img src="/images/logo/eloria-logo-icon.jpg" alt="Logo" class="h-8 w-auto rounded-lg" />
        <span class="font-serif font-bold text-xl text-slate-900">Eloria</span>
      </Link>

      <button @click="isMobileSidebarOpen = !isMobileSidebarOpen" class="p-2 rounded-xl text-slate-700 hover:bg-rose-50">
        <Menu v-if="!isMobileSidebarOpen" class="w-6 h-6" />
        <X v-else class="w-6 h-6" />
      </button>
    </div>

    <!-- Mobile Navigation Drawer Overlay -->
    <div v-if="isMobileSidebarOpen" class="lg:hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex justify-start" @click="isMobileSidebarOpen = false">
      <div class="w-72 bg-white h-full p-6 space-y-6 shadow-2xl overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
          <span class="font-serif font-bold text-lg text-slate-900">Eloria OS</span>
          <button @click="isMobileSidebarOpen = false"><X class="w-5 h-5 text-slate-400" /></button>
        </div>

        <nav class="space-y-2">
          <Link
            v-for="item in navigationItems"
            :key="item.id"
            :href="item.href"
            @click="isMobileSidebarOpen = false"
            class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-semibold transition"
            :class="[
              activeNav === item.id || page.url.startsWith(item.href)
                ? 'bg-rose-600 text-white font-bold'
                : 'text-slate-700 hover:bg-rose-50'
            ]"
          >
            <component :is="item.icon" class="w-4 h-4" />
            <span>{{ item.label }}</span>
          </Link>
        </nav>
      </div>
    </div>

    <!-- Main Workspace Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto pt-16 lg:pt-0">
      <!-- Sticky Glassmorphism Top Header Bar (Desktop) -->
      <header class="hidden lg:flex sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-rose-100/80 px-8 h-16 items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3 text-xs">
          <span class="font-bold text-rose-600 uppercase tracking-widest text-[10px]">ELORIA OS WORKSPACE</span>
          <span class="text-slate-300">/</span>
          <h1 class="font-serif font-bold text-slate-900 text-sm">{{ title || 'Workspace Management' }}</h1>
        </div>

        <div class="flex items-center gap-4">
          <!-- Quick Search Bar -->
          <div class="relative w-64">
            <input type="text" placeholder="Tìm nhanh task, ngân sách... (Cmd+K)" class="w-full pl-9 pr-3 py-1.5 bg-rose-50/50 border border-rose-200/60 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-hidden focus:border-rose-400" />
            <Search class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" />
          </div>

          <!-- Notifications Bell -->
          <button class="relative p-2 rounded-xl text-slate-600 hover:bg-rose-50 transition cursor-pointer">
            <Bell class="w-4 h-4 text-slate-700" />
            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
          </button>

          <!-- User Profile Pill -->
          <div class="flex items-center gap-2.5 pl-3 border-l border-rose-100">
            <img src="/images/logo/eloria-logo-icon.jpg" alt="Avatar" class="w-8 h-8 rounded-full border border-rose-200 object-cover" />
            <div class="text-left text-xs hidden xl:block">
              <span class="font-bold text-slate-900 block truncate max-w-[120px]">{{ ($page.props as any).workspace?.groom_name || 'Quốc Trung' }}</span>
              <span class="text-[10px] text-slate-400 block">Dâu Rể Owner</span>
            </div>
          </div>
        </div>
      </header>

      <slot />
    </div>

    <!-- Grounded AI Assistant Drawer -->
    <GroundedAiDrawer :is-open="isAiDrawerOpen" @close="isAiDrawerOpen = false" />
  </div>
</template>
