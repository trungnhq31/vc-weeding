<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import SubscriptionUpgradeModal from '@/Components/Wedding/SubscriptionUpgradeModal.vue';
import { Crown, Users, DollarSign, Heart, ExternalLink, Sparkles, Plus, Layers, ArrowUpRight, Zap } from 'lucide-vue-next';

const props = defineProps<{
  workspaces: any[];
  totalManagedBudget: number;
  totalGuestsCount: number;
  totalWorkspacesCount: number;
}>();

const isUpgradeModalOpen = ref(false);

const formatVND = (amount: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount || 0);
};
</script>

<template>
  <WorkspaceLayout>
    <Head title="Wedding Planner Multi-Workspace OS — Eloria SaaS" />

    <!-- Top Action Header -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-2xs">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
            <Crown class="w-5 h-5 text-amber-500" />
            <span>Wedding Planner Enterprise OS</span>
            <span class="text-[10px] font-sans px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 font-bold border border-amber-300">SaaS Multi-Workspace</span>
          </h1>
          <p class="text-xs text-slate-500 mt-0.5">Quản lý tập trung toàn bộ danh sách đám cưới, ngân sách & thiệp cưới online cho các dự án dâu rể</p>
        </div>

        <div class="flex items-center gap-3">
          <button 
            @click="isUpgradeModalOpen = true"
            class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold text-xs shadow-md transition flex items-center gap-2 cursor-pointer"
          >
            <Zap class="w-4 h-4 text-slate-950" />
            Nâng Cấp SaaS Plan
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">
      <!-- High-Level KPI Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs space-y-2">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Tổng Số Dự Án Đám Cưới</span>
            <Layers class="w-4 h-4 text-rose-500" />
          </div>
          <div class="text-2xl font-bold text-slate-900">{{ totalWorkspacesCount }} Workspaces</div>
          <div class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
            <ArrowUpRight class="w-3.5 h-3.5" />
            Đang quản lý đồng thời
          </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs space-y-2">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Tổng Khách Mời Hệ Thống</span>
            <Users class="w-4 h-4 text-sky-500" />
          </div>
          <div class="text-2xl font-bold text-slate-900">{{ totalGuestsCount }} Khách Mời</div>
          <div class="text-[11px] text-slate-500">Đã cập nhật thiệp & RSVP</div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs space-y-2">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Tổng Ngân Sách Quản Lý</span>
            <DollarSign class="w-4 h-4 text-emerald-500" />
          </div>
          <div class="text-xl font-bold text-slate-900 truncate">{{ formatVND(totalManagedBudget) }}</div>
          <div class="text-[11px] text-slate-500">Tổng chi phí thực tế phát sinh</div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs space-y-2">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Trạng Thái Đăng Ký SaaS</span>
            <Crown class="w-4 h-4 text-amber-500" />
          </div>
          <div class="text-lg font-bold text-amber-700 uppercase">Enterprise Plan</div>
          <div class="text-[11px] text-emerald-600 font-semibold">Mở khóa 100% tính năng</div>
        </div>
      </div>

      <!-- Workspace List Section -->
      <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs p-6 space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-base font-serif font-bold text-slate-900">Danh Sách Workspaces Đám Cưới Đang Thực Hiện</h2>
            <p class="text-xs text-slate-500">Chọn workspace để vào trực tiếp quản lý timeline, ngân sách và thiệp cưới</p>
          </div>

          <Link href="/onboarding" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
            <Plus class="w-4 h-4 text-rose-400" />
            Tạo Workspace Mới
          </Link>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-slate-200 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                <th class="py-3 px-4">Tên Workspace / Dâu Rể</th>
                <th class="py-3 px-4">Ngày Tổ Chức</th>
                <th class="py-3 px-4">Số Khách</th>
                <th class="py-3 px-4">Template Thiệp</th>
                <th class="py-3 px-4">Gói SaaS</th>
                <th class="py-3 px-4 text-right">Thao Tác</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
              <tr v-for="ws in workspaces" :key="ws.id" class="hover:bg-slate-50/80 transition">
                <td class="py-4 px-4">
                  <div class="font-bold text-slate-900 text-sm">{{ ws.name }}</div>
                  <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                    <Heart class="w-3 h-3 text-rose-500 fill-rose-500" />
                    {{ ws.groom_name }} & {{ ws.bride_name }}
                  </div>
                </td>

                <td class="py-4 px-4 text-slate-700">
                  <div class="font-semibold">{{ ws.wedding_date ? ws.wedding_date.substring(0, 10) : 'Chưa xếp' }}</div>
                  <div class="text-[10px] text-slate-400">{{ ws.wedding_location || 'TP. HCM' }}</div>
                </td>

                <td class="py-4 px-4 text-slate-700 font-semibold">
                  {{ ws.guests_count || 0 }} khách
                </td>

                <td class="py-4 px-4">
                  <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-900 border border-rose-200 text-[10px] font-bold">
                    {{ ws.invitation?.template?.name || 'Romantic Pastel' }}
                  </span>
                </td>

                <td class="py-4 px-4">
                  <span 
                    class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                    :class="{
                      'bg-slate-100 text-slate-700 border border-slate-200': ws.subscription_plan === 'free',
                      'bg-rose-100 text-rose-900 border border-rose-300': ws.subscription_plan === 'pro',
                      'bg-amber-100 text-amber-950 border border-amber-300': ws.subscription_plan === 'enterprise' || !ws.subscription_plan
                    }"
                  >
                    {{ (ws.subscription_plan || 'pro').toUpperCase() }}
                  </span>
                </td>

                <td class="py-4 px-4 text-right">
                  <Link href="/wedding" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-900 text-slate-800 text-xs font-bold transition">
                    <span>Quản Lý</span>
                    <ExternalLink class="w-3 h-3 text-slate-500" />
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Subscription Upgrade Modal Component -->
    <SubscriptionUpgradeModal
      :isOpen="isUpgradeModalOpen"
      @close="isUpgradeModalOpen = false"
      @upgraded="router.reload()"
    />
  </WorkspaceLayout>
</template>
