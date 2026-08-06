<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, Lock, ArrowRight, Heart, Sparkles, UserCheck, ShieldCheck } from 'lucide-vue-next';

const form = useForm({
  email: 'daure@eloria.vn',
  password: 'password',
  remember: true,
});

const demoRoles = [
  {
    role: 'owner',
    label: 'Chú Rể / Cô Dâu (Workspace Owner)',
    email: 'daure@eloria.vn',
    password: 'password',
    icon: '👑',
    badge: 'Dâu Rể',
    desc: 'Quản lý toàn bộ sảnh tiệc, ngân sách & khách mời',
  },
  {
    role: 'planner',
    label: 'Wedding Planner Pro (B2B SaaS)',
    email: 'planner@eloria.vn',
    password: 'password',
    icon: '📋',
    badge: 'Planner',
    desc: 'Đơn vị tổ chức cưới chuyên nghiệp điều phối sảnh',
  },
  {
    role: 'vendor',
    label: 'Đối Tác Vendor (Studio / Sảnh Tiệc)',
    email: 'vendor@eloria.vn',
    password: 'password',
    icon: '🏪',
    badge: 'Vendor',
    desc: 'Nhà cung cấp xem hợp đồng & lịch cọc',
  },
  {
    role: 'guest',
    label: 'Khách Mời V.I.P (RSVP & Thiệp Digital)',
    email: 'khachmoi@eloria.vn',
    password: 'password',
    icon: '💌',
    badge: 'Khách Mời',
    desc: 'Trải nghiệm thiệp cưới online & gửi hộp mừng VietQR',
  },
  {
    role: 'admin',
    label: 'Super Administrator (Eloria System)',
    email: 'admin@eloria.vn',
    password: 'password',
    icon: '🛡️',
    badge: 'Admin',
    desc: 'Quản trị viên toàn bộ hệ thống Eloria OS',
  },
];

const quickLogin = (email: string, pass: string) => {
  form.email = email;
  form.password = pass;
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};

const fillOnly = (email: string, pass: string) => {
  form.email = email;
  form.password = pass;
};

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <Head title="Đăng Nhập Workspace — Eloria OS" />

  <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans flex flex-col justify-between selection:bg-rose-100 selection:text-rose-900">
    <!-- Header -->
    <header class="py-6 px-8 flex items-center justify-between max-w-6xl mx-auto w-full">
      <Link href="/" class="flex items-center gap-2.5">
        <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria" class="h-9 w-auto rounded-xl shadow-xs" />
        <span class="font-serif font-bold text-2xl tracking-tight text-slate-900">Eloria</span>
      </Link>

      <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
        <span>Chưa có tài khoản?</span>
        <Link href="/register" class="px-4 py-2 rounded-full bg-rose-600 text-white font-bold hover:bg-rose-500 transition shadow-sm">
          Đăng Ký Workspace
        </Link>
      </div>
    </header>

    <!-- Main Content Grid -->
    <main class="max-w-6xl mx-auto px-6 py-8 w-full flex-1 flex flex-col lg:flex-row items-center justify-center gap-12">
      <!-- Left Column: Branding Info & Quick Login Options -->
      <div class="w-full lg:w-1/2 space-y-6">
        <div class="space-y-3">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-800 text-[11px] font-bold uppercase tracking-wider">
            <Sparkles class="w-3.5 h-3.5 text-rose-500" /> Multi-Role Workspace OS
          </div>
          <h1 class="text-3xl lg:text-4xl font-serif font-bold text-slate-900 leading-tight">
            Đăng Nhập Hệ Thống Lập Kế Hoạch Tiệc Cưới
          </h1>
          <p class="text-xs lg:text-sm text-slate-600 leading-relaxed font-medium">
            Chọn tài khoản theo từng vai trò bên dưới để trải nghiệm trực tiếp tính năng phân quyền Workspace của Eloria OS.
          </p>
        </div>

        <!-- Quick Login Demo Role Buttons List -->
        <div class="space-y-2.5">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">LỰA CHỌN TÀI KHOẢN ĐĂNG NHẬP NHANH (QUICK LOGIN)</span>
          <div 
            v-for="item in demoRoles" 
            :key="item.role"
            @click="fillOnly(item.email, item.password)"
            class="p-3.5 rounded-2xl bg-white border border-rose-100/90 shadow-2xs hover:border-rose-300 hover:shadow-md transition-all cursor-pointer flex items-center justify-between gap-3 group"
          >
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-lg shrink-0">
                {{ item.icon }}
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <span class="text-xs font-bold text-slate-900 group-hover:text-rose-700 transition">{{ item.label }}</span>
                  <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-rose-100/80 text-rose-900 uppercase">{{ item.badge }}</span>
                </div>
                <span class="text-[11px] text-slate-500 font-medium block mt-0.5">{{ item.desc }}</span>
              </div>
            </div>

            <button 
              @click.stop="quickLogin(item.email, item.password)"
              class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-900 hover:text-white text-xs font-bold border border-rose-200 hover:border-rose-600 transition flex items-center gap-1 shrink-0 cursor-pointer"
            >
              <span>Vào Ngay</span> <ArrowRight class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>

      <!-- Right Column: Login Form Box -->
      <div class="w-full lg:w-5/12 bg-white p-8 rounded-3xl border border-rose-100 shadow-xl shadow-rose-900/5 space-y-6">
        <div class="space-y-1">
          <h2 class="text-xl font-serif font-bold text-slate-900">Đăng Nhập Tài Khoản</h2>
          <p class="text-xs text-slate-500">Nhập email & mật khẩu tài khoản Workspace của bạn</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Địa Chỉ Email *</label>
            <div class="relative">
              <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input 
                v-model="form.email"
                type="email" 
                required
                placeholder="daure@eloria.vn" 
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400"
              />
            </div>
            <span v-if="form.errors.email" class="text-rose-600 text-[11px] mt-1 block font-medium">{{ form.errors.email }}</span>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Mật Khẩu *</label>
            <div class="relative">
              <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input 
                v-model="form.password"
                type="password" 
                required
                placeholder="••••••••" 
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400"
              />
            </div>
            <span v-if="form.errors.password" class="text-rose-600 text-[11px] mt-1 block font-medium">{{ form.errors.password }}</span>
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
              <input v-model="form.remember" type="checkbox" class="rounded text-rose-600 focus:ring-rose-400" />
              <span class="text-xs text-slate-600 font-medium">Ghi nhớ đăng nhập</span>
            </label>
            <a href="#" class="text-xs font-bold text-rose-600 hover:underline">Quên mật khẩu?</a>
          </div>

          <button 
            type="submit" 
            :disabled="form.processing"
            class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/25 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
          >
            <UserCheck class="w-4 h-4" />
            <span>{{ form.processing ? 'Đang xác thực...' : 'Đăng Nhập Workspace' }}</span>
          </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
          Bằng việc đăng nhập, bạn đồng ý với <a href="#" class="underline text-slate-700">Điều khoản dịch vụ</a> và <a href="#" class="underline text-slate-700">Chính sách bảo mật</a> của Eloria.
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 px-8 text-center text-xs text-slate-400 border-t border-rose-100/60 max-w-6xl mx-auto w-full">
      © 2026 Eloria Wedding OS. All rights reserved. Operating System for Planning a Wedding.
    </footer>
  </div>
</template>
