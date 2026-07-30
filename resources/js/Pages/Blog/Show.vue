<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Clock, Calendar, Share2, Sparkles } from 'lucide-vue-next';

defineProps<{
    post: any;
}>();
</script>

<template>
    <Head :title="`${post.title} — Eloria Blog`" />

    <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans selection:bg-rose-100 selection:text-rose-900">
        <!-- Header Nav -->
        <header class="sticky top-0 z-50 bg-[#FAF8F5]/90 backdrop-blur-md border-b border-rose-100/80">
            <div class="max-w-5xl mx-auto px-6 h-20 flex items-center justify-between">
                <!-- Brand Logo: Only Logo Image (Larger) + Eloria Text -->
                <Link href="/" class="flex items-center gap-3 group">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-12 md:h-14 w-auto rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
                    <span class="font-serif text-2xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
                </Link>

                <div class="flex items-center gap-2 md:gap-3">
                    <Link href="/blog" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-rose-700 transition-colors mr-2">
                        <ArrowLeft class="w-4 h-4 text-rose-600" /> Cẩm nang
                    </Link>
                    <Link href="/login" class="px-3.5 py-2 rounded-xl text-slate-700 hover:text-rose-700 hover:bg-rose-50 font-semibold text-xs md:text-sm transition-all">
                        Đăng nhập
                    </Link>
                    <Link href="/register" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md shadow-slate-900/10 flex items-center gap-1.5">
                        Đăng ký
                    </Link>
                </div>
            </div>
        </header>

        <!-- Article Detail Container -->
        <main class="max-w-4xl mx-auto px-6 py-12">
            <article class="bg-white rounded-3xl p-8 md:p-12 border border-rose-100 shadow-xs">
                <!-- Category Badge -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-semibold mb-4">
                    <Sparkles class="w-3.5 h-3.5 text-rose-600" /> {{ post.category?.name || 'Cẩm Nang Cưới' }}
                </div>

                <!-- Article Title -->
                <h1 class="text-3xl md:text-5xl font-serif font-extrabold text-slate-900 leading-tight mb-6">
                    {{ post.title }}
                </h1>

                <!-- Article Meta info -->
                <div class="flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500 pb-6 border-b border-slate-100 mb-8">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1"><Calendar class="w-4 h-4 text-rose-500" /> {{ new Date(post.published_at || Date.now()).toLocaleDateString('vi-VN') }}</span>
                        <span class="flex items-center gap-1"><Clock class="w-4 h-4 text-rose-500" /> {{ post.reading_time_minutes || 5 }} phút đọc</span>
                    </div>
                </div>

                <!-- Excerpt / Intro Highlight -->
                <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-rose-100/80 text-slate-700 italic text-sm md:text-base leading-relaxed mb-8">
                    "{{ post.excerpt }}"
                </div>

                <!-- Article Body Content -->
                <div class="prose prose-rose max-w-none text-slate-700 text-sm md:text-base leading-relaxed space-y-6">
                    <div v-if="post.content" v-html="post.content"></div>
                    <div v-else class="space-y-4 text-slate-600">
                        <p>
                            Việc chuẩn bị cho một lễ cưới hoàn hảo đòi hỏi sự phối hợp nhịp nhàng giữa nhiều hạng mục khác nhau: từ chọn địa điểm tổ chức (Venue), trang phục cưới (Attire), đơn vị quay phim chụp ảnh (Media) cho đến lên kế hoạch thực đơn tiệc (Reception).
                        </p>
                        <h3 class="text-xl font-serif font-bold text-slate-900 mt-6">1. Thiết Lập Trần Ngân Sách Thực Tế</h3>
                        <p>
                            Để tránh rủi ro phát sinh ngoài ý muốn, bạn nên ghi nhận rõ ràng khoản ngân sách dự kiến và trần chi phí tối đa cho từng hạng mục. Với tính năng Cash Flow Calculator trong Eloria OS, hệ thống sẽ tự động cảnh báo khi chi phí thực tế vượt quá trần quy định.
                        </p>
                        <h3 class="text-xl font-serif font-bold text-slate-900 mt-6">2. Tận Dụng Thiệp Cưới Điện Tử & QR Check-in</h3>
                        <p>
                            Thiệp cưới điện tử không chỉ mang phong cách lãng mạn, tinh tế mà còn giúp tiết kiệm thời gian gửi thiệp. Khách mời có thể dễ dàng xác nhận tham dự (RSVP), gửi lời chúc và upload hình ảnh kỷ niệm ngay trên điện thoại di động.
                        </p>
                    </div>
                </div>
            </article>

            <!-- Back CTA -->
            <div class="mt-8 text-center">
                <Link href="/wedding/timeline" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white font-semibold text-sm shadow-md hover:bg-slate-800 transition">
                    Trải Nghiệm Lập Kế Hoạch Đám Cưới
                </Link>
            </div>
        </main>
    </div>
</template>
