<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Heart, Sparkles, User, Mail, Lock, ArrowRight, ShieldCheck } from 'lucide-vue-next';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <Head title="Đăng Ký Tài Khoản Planning — Eloria OS" />

  <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans flex flex-col justify-between selection:bg-rose-100 selection:text-rose-900">
    <!-- Header -->
    <header class="py-6 px-8 flex items-center justify-between">
      <Link href="/" class="flex items-center gap-3">
        <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-9 w-auto rounded-xl shadow-xs border border-rose-200" />
        <span class="font-serif text-xl font-bold text-slate-900 tracking-tight">Eloria <span class="text-rose-600 font-sans text-xs px-2 py-0.5 rounded-full bg-rose-100 border border-rose-200">OS</span></span>
      </Link>

      <Link href="/login" class="text-xs font-semibold text-slate-700 hover:text-rose-700 transition">
        Đã có tài khoản? <span class="text-rose-700 font-bold underline">Đăng nhập</span>
      </Link>
    </header>

    <!-- Main Register Form Card -->
    <main class="flex-1 flex items-center justify-center p-6">
      <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-3xl border border-rose-100 shadow-sm">
        <div class="text-center mb-8">
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-semibold mb-3">
            <Sparkles class="w-3.5 h-3.5 text-rose-600" /> Bắt đầu lập kế hoạch cưới
          </div>
          <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">Tạo Tài Khoản Dâu Rể</h1>
          <p class="text-xs text-slate-500 mt-1">Đồng bộ lộ trình, quản lý ngân sách & tạo thiệp mời độc bản</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Họ và Tên Cô dâu / Chú rể</label>
            <div class="relative">
              <User class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" />
              <input 
                v-model="form.name"
                type="text"
                required
                placeholder="Nguyễn Văn Anh"
                class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400 transition"
              />
            </div>
            <span v-if="form.errors.name" class="text-[11px] text-rose-600 mt-1 block">{{ form.errors.name }}</span>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Địa chỉ Email</label>
            <div class="relative">
              <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" />
              <input 
                v-model="form.email"
                type="email"
                required
                placeholder="daure@eloria.app"
                class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400 transition"
              />
            </div>
            <span v-if="form.errors.email" class="text-[11px] text-rose-600 mt-1 block">{{ form.errors.email }}</span>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Mật khẩu (Tối thiểu 8 ký tự)</label>
            <div class="relative">
              <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" />
              <input 
                v-model="form.password"
                type="password"
                required
                placeholder="••••••••"
                class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400 transition"
              />
            </div>
            <span v-if="form.errors.password" class="text-[11px] text-rose-600 mt-1 block">{{ form.errors.password }}</span>
          </div>

          <!-- Password Confirmation -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Xác nhận Mật khẩu</label>
            <div class="relative">
              <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" />
              <input 
                v-model="form.password_confirmation"
                type="password"
                required
                placeholder="••••••••"
                class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F5] border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-hidden focus:border-rose-400 transition"
              />
            </div>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            :disabled="form.processing"
            class="w-full mt-2 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shadow-md transition flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>Tạo Tài Khoản & Bắt Đầu Thiết Lập</span>
            <ArrowRight class="w-4 h-4 text-rose-400" />
          </button>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-400">
      © 2026 Eloria Wedding OS. Nền tảng lập kế hoạch cưới thế hệ mới.
    </footer>
  </div>
</template>
