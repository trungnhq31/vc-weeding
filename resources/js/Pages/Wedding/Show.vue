<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Heart, Calendar, MapPin, CheckCircle2, Clock } from 'lucide-vue-next';

interface Guest {
    id: string;
    name: string;
    guest_slug: string;
    salutation: string;
    rsvp_status: string;
    attending_count: number;
}

interface Wish {
    id: string;
    sender_name: string;
    message: string;
    created_at: string;
}

const props = defineProps<{
    guest: Guest;
    wishes: Wish[];
}>();
</script>

<template>
    <Head :title="`Thiệp Cưới Dành Tặng ${guest.salutation} ${guest.name}`" />

    <!-- Bright Warm Pastel Cream Background -->
    <div class="min-h-screen bg-[#FAF8F5] text-slate-800 font-sans bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-rose-100/60 via-amber-50/40 to-[#FAF8F5] pb-24">
        <!-- Header banner -->
        <header class="py-20 text-center px-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100 border border-rose-300 text-rose-800 text-xs font-semibold uppercase tracking-wider mb-6 shadow-xs">
                <Heart class="w-4 h-4 text-rose-500 fill-rose-500/40" /> Lời Mời Trân Trọng
            </div>
            
            <div class="text-sm font-bold text-rose-800 uppercase tracking-widest mb-2">Trân Trọng Kính Mời</div>
            <h1 class="text-4xl md:text-6xl font-serif font-extrabold text-rose-950 mb-4 tracking-tight">
                {{ guest.salutation }} {{ guest.name }}
            </h1>
            <p class="text-lg text-slate-700 font-serif italic max-w-lg mx-auto">
                Đến tham dự tiệc cưới mừng hạnh phúc của <span class="font-bold text-rose-800">Vân & Cẩm</span>
            </p>
        </header>

        <!-- Details Card -->
        <div class="max-w-3xl mx-auto px-6 grid md:grid-cols-2 gap-6 mb-16">
            <div class="p-8 rounded-3xl bg-white border border-rose-200/80 shadow-md shadow-rose-100/50 text-center">
                <Calendar class="w-8 h-8 text-rose-500 mx-auto mb-3" />
                <h3 class="text-lg font-serif font-bold text-rose-950 mb-1">Thời Gian</h3>
                <p class="text-rose-800 font-bold text-base">Thứ Bảy, 19/12/2026</p>
                <p class="text-slate-600 text-xs mt-1 font-medium">Khai tiệc lúc 18:30</p>
            </div>

            <div class="p-8 rounded-3xl bg-white border border-rose-200/80 shadow-md shadow-rose-100/50 text-center">
                <MapPin class="w-8 h-8 text-rose-500 mx-auto mb-3" />
                <h3 class="text-lg font-serif font-bold text-rose-950 mb-1">Địa Điểm</h3>
                <p class="text-rose-800 font-bold text-base">Asiana Plaza</p>
                <p class="text-slate-600 text-xs mt-1 font-medium">Sảnh Tiệc Sang Trọng • TP.HCM</p>
            </div>
        </div>

        <div class="text-center">
            <Link href="/wedding/timeline" class="inline-flex items-center gap-2 text-sm font-bold text-rose-700 hover:text-rose-900 bg-rose-100 border border-rose-200 px-6 py-3 rounded-full shadow-xs">
                <Clock class="w-4 h-4 text-rose-600" /> Xem Lộ Trình Chuẩn Bị Cưới 5 Tháng
            </Link>
        </div>
    </div>
</template>
