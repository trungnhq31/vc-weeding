<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, Lock, ArrowRight, Heart, Sparkles, UserCheck, ShieldCheck } from 'lucide-vue-next';

const form = useForm({
  email: 'groom@eloria.vn',
  password: 'password',
  remember: true,
});

const demoAccounts = [
  { name: 'Chú Rể Quốc Trung', role: 'Dâu Rể Owner', email: 'groom@eloria.vn', password: 'password', icon: '🤵' },
  { name: 'Cô Dâu Hồng Vân', role: 'Dâu Rể Co-Owner', email: 'bride@eloria.vn', password: 'password', icon: '👰' },
  { name: 'Eloria Admin', role: 'System Admin', email: 'admin@eloria.vn', password: 'password', icon: '👑' },
];

const fillDemo = (email: string, pwd: string) => {
  form.email = email;
  form.password = pwd;
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
    <header class="py-6 px-8 flex items-center justify-between">
      <Link href="/" class="flex items-center gap-3">
        <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-9 w-auto rounded-xl shadow-xs border border-rose-200" />
        <span class="font-serif text-xl font-bold text-slate-900 tracking-tight">Eloria <span class="text-rose-600 font-sans text-xs px-2 py-0.5 rounded-full bg-rose-100 border border-rose-200">OS</span></span>
      </Link>

      <Link href="/register" class="text-xs font-semibold text-slate-700 hover:text-rose-700 transition">
        Chưa có tài khoản? <span class="text-rose-700 font-bold underline">Đăng ký ngay</span>
      </Link>
    </header>

    <!-- Main Login Card -->
    <main class="flex-1 flex items-center justify-center p-6">
      <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-3xl border border-rose-100 shadow-sm space-y-6">
        <div class="text-center">
          <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">Chào Mừng Quay Lại!</h1>
          <p class="text-xs text-slate-500 mt-1">Đăng nhập vào Workspace đám cưới của bạn</p>
        </div>

        <!-- Pre-filled Quick Demo Credentials Switcher -->
        <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200/80 space-y-2.5">
          <div class="flex items-center justify-between text-xs font-bold text-rose-950">
            <span class="flex items-center gap-1.5">
              <Sparkles class="w-4 h-4 text-rose-600" />
              Chọn nhanh tài khoản Demo (1-Click Fill)
            </span>
            <span class="text-[10px] font-mono text-rose-700 bg-white px-2 py-0.5 rounded border border-rose-200">Password: password</span>
          </div>

          <div class="grid grid-cols-3 gap-2">
            <button
              v-for="acc in demoAccounts"
              :key="acc.email"
              type="button"
              @click="fillDemo(acc.email, acc.password)"
              class="p-2.5 rounded-xl border text-center transition cursor-pointer flex flex-col items-center justify-center gap-1"
              :class="form.email === acc.email ? 'bg-white border-rose-500 shadow-xs ring-1 ring-rose-400 font-bold text-rose-950' : 'bg-white/80 border-rose-100 text-slate-700 hover:bg-white'"
            >
              <span class="text-base">{{ acc.icon }}</span>
              <span class="text-[11px] font-semibold truncate w-full">{{ acc.name.split(' ')[2] || acc.name }}</span>
              <span class="text-[9px] text-slate-400 truncate w-full">{{ acc.role }}</span>
            </button>
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Email -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Đăng Nhập</label>
            <div class="relative">
              <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" />
              <input 
                v-model="form.email"
                type="email"
                required
                placeholder="daure@eloria.app"
                class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400 transition font-medium"
              />
            </div>
            <span v-if="form.errors.email" class="text-[11px] text-rose-600 mt-1 block">{{ form.errors.email }}</span>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Mật khẩu</label>
            <div class="relative">
              <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" />
              <input 
                v-model="form.password"
                type="password"
                required
                placeholder="••••••••"
                class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400 transition font-medium"
              />
            </div>
          </div>

          <!-- Remember me -->
          <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 cursor-pointer text-slate-600">
              <input type="checkbox" v-model="form.remember" class="accent-rose-600 rounded" />
              <span>Ghi nhớ đăng nhập</span>
            </label>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            :disabled="form.processing"
            class="w-full mt-2 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shadow-md transition flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>Vào Workspace Đám Cưới</span>
            <ArrowRight class="w-4 h-4 text-rose-400" />
          </button>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-400">
      © 2026 Eloria Wedding OS.
    </footer>
  </div>
</template>
