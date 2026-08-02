<script setup lang="ts">
import { ref } from 'vue';
import { Sparkles, CheckCircle2, ShieldCheck, Crown, ArrowRight, X, Globe, Zap } from 'lucide-vue-next';

const props = defineProps<{
  isOpen: boolean;
  currentPlan?: string;
}>();

const emit = defineEmits(['close', 'upgraded']);

const selectedPlan = ref<'free' | 'pro' | 'enterprise'>('pro');
const customSubdomain = ref('trung-van');
const isUpgrading = ref(false);
const upgradeSuccess = ref(false);

const plans = [
  {
    id: 'free',
    name: 'Free (Lễ Đính Hôn)',
    price: '0 VNĐ',
    period: 'Miễn phí mãi mãi',
    badge: 'Cơ Bản',
    badgeColor: 'bg-slate-100 text-slate-700',
    features: [
      '1 Workspace đám cưới',
      'Tối đa 50 Khách mời',
      '3 Template Thiệp cưới cơ bản',
      'Quản lý Ngân sách cơ bản',
    ],
  },
  {
    id: 'pro',
    name: 'Pro (Ngày Chung Đôi)',
    price: '499.000 VNĐ',
    period: 'Trọn gói 1 Đám Cưới',
    badge: 'KHUYÊN DÙNG',
    badgeColor: 'bg-rose-500 text-white font-bold',
    recommended: true,
    features: [
      'Khách mời KHÔNG GIỚI HẠN',
      'Trọn bộ 10 Template Thiệp Cưới Cao Cấp',
      'Tùy chỉnh Subdomain riêng (trung-van.eloria.vn)',
      'Sơ đồ Bàn tiệc Kéo Thả (Drag & Drop)',
      'Grounded AI Assistant trợ lý không hallucinate',
      'Xuất file Báo cáo CSV Ngân sách & Khách mời',
    ],
  },
  {
    id: 'enterprise',
    name: 'Planner Enterprise',
    price: '1.990.000 VNĐ',
    period: 'Thanh toán hàng tháng',
    badge: 'CHO WEDDING PLANNER',
    badgeColor: 'bg-slate-900 text-amber-300 font-bold',
    features: [
      'Multi-Workspace không giới hạn đám cưới',
      'Trọn bộ 10 Mẫu + Custom Brand Logo',
      'Domain riêng Custom Domain',
      'Hỗ trợ kỹ thuật 24/7 ưu tiên cao nhất',
    ],
  },
];

const handleUpgrade = async () => {
  isUpgrading.value = true;
  try {
    const response = await fetch('/wedding/subscription/upgrade', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
      },
      body: JSON.stringify({
        plan: selectedPlan.value,
        custom_subdomain: customSubdomain.value,
      }),
    });

    if (response.ok) {
      upgradeSuccess.value = true;
      setTimeout(() => {
        upgradeSuccess.value = false;
        emit('upgraded');
        emit('close');
      }, 2000);
    }
  } catch (e) {
    console.error('Error upgrading subscription:', e);
  } finally {
    isUpgrading.value = false;
  }
};
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md transition-all">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-4xl w-full p-6 md:p-8 space-y-6 max-h-[90vh] overflow-y-auto relative">
      <!-- Close Button -->
      <button @click="emit('close')" class="absolute top-6 right-6 p-2 rounded-full text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer">
        <X class="w-5 h-5" />
      </button>

      <!-- Modal Title -->
      <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-100 border border-rose-200 text-rose-900 text-xs font-bold uppercase tracking-wider">
          <Sparkles class="w-4 h-4 text-rose-600" />
          Nâng Cấp Gói Cước Eloria Wedding OS
        </div>
        <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">Mở Khóa Toàn Bộ Chức Năng SaaS Cao Cấp</h2>
        <p class="text-xs text-slate-500 max-w-xl mx-auto">Lựa chọn gói cước phù hợp để sở hữu trọn bộ 10 template thiệp cưới cao cấp, kéo thả bàn tiệc và subdomain riêng biệt.</p>
      </div>

      <!-- Plan Cards Grid -->
      <div class="grid md:grid-cols-3 gap-4 pt-2">
        <div
          v-for="plan in plans"
          :key="plan.id"
          @click="selectedPlan = plan.id as any"
          class="p-5 rounded-2xl border transition-all cursor-pointer relative flex flex-col justify-between"
          :class="[
            selectedPlan === plan.id ? 'border-rose-500 ring-2 ring-rose-400 bg-rose-50/30 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300',
            plan.recommended ? 'md:-translate-y-1' : ''
          ]"
        >
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider text-[10px]" :class="plan.badgeColor">
                {{ plan.badge }}
              </span>
              <CheckCircle2 v-if="selectedPlan === plan.id" class="w-5 h-5 text-rose-600" />
            </div>

            <div>
              <h3 class="text-base font-serif font-bold text-slate-900">{{ plan.name }}</h3>
              <div class="mt-2 flex items-baseline gap-1">
                <span class="text-xl md:text-2xl font-extrabold text-slate-900">{{ plan.price }}</span>
              </div>
              <span class="text-[10px] text-slate-500">{{ plan.period }}</span>
            </div>

            <ul class="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-700">
              <li v-for="(feat, idx) in plan.features" :key="idx" class="flex items-start gap-2">
                <span class="text-rose-500 font-bold">✓</span>
                <span class="text-[11px] leading-snug">{{ feat }}</span>
              </li>
            </ul>
          </div>

          <div class="mt-6 pt-4">
            <button
              type="button"
              class="w-full py-2.5 rounded-xl font-semibold text-xs transition text-center cursor-pointer"
              :class="selectedPlan === plan.id ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
            >
              {{ selectedPlan === plan.id ? 'Đã Chọn Gói Này' : 'Chọn Gói Này' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Custom Subdomain Config Field -->
      <div v-if="selectedPlan !== 'free'" class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
        <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
          <Globe class="w-4 h-4 text-rose-600" />
          Đăng Ký Tên Miền Subdomain Riêng Đám Cưới
        </label>
        <div class="flex items-center gap-2">
          <span class="text-xs font-mono text-slate-400">https://</span>
          <input
            v-model="customSubdomain"
            type="text"
            placeholder="trung-van"
            class="flex-1 px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-rose-950 focus:outline-hidden focus:border-rose-400 font-mono"
          />
          <span class="text-xs font-mono text-slate-600 font-semibold">.eloria.vn</span>
        </div>
      </div>

      <!-- Submit Action Bar -->
      <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
        <span v-if="upgradeSuccess" class="text-xs font-bold text-emerald-600 flex items-center gap-1">
          <CheckCircle2 class="w-4 h-4 text-emerald-600" />
          Đã nâng cấp Gói Cước thành công!
        </span>
        <span v-else class="text-xs text-slate-500">Xác nhận đơn hàng và kích hoạt ngay tức thì.</span>

        <div class="flex items-center gap-3">
          <button @click="emit('close')" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer">
            Hủy Bỏ
          </button>

          <button
            @click="handleUpgrade"
            :disabled="isUpgrading"
            class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shadow-md transition flex items-center gap-2 cursor-pointer disabled:opacity-50"
          >
            <Zap class="w-4 h-4 text-rose-400" />
            <span>{{ isUpgrading ? 'Đang kích hoạt...' : 'Kích Hoạt Gói SaaS Ngay' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
