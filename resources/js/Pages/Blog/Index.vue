<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Search, Sparkles, Tag, ArrowRight, Calendar, Clock } from 'lucide-vue-next';

defineProps<{
    posts: any;
    categories?: any[];
    tags?: any[];
    filters?: any;
}>();

const page = usePage();
const authUser = computed(() => (page.props as any).auth?.user);
</script>

<template>
    <Head title="Blog Kinh Nghiệm & Cẩm Nang Lập Kế Hoạch Cưới — Eloria OS" />

    <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans selection:bg-rose-100 selection:text-rose-900">
        <!-- Header / Navigation -->
        <header class="sticky top-0 z-50 bg-[#FAF8F5]/90 backdrop-blur-md border-b border-rose-100/80">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <!-- Brand Logo: Only Logo Image (Larger) + Eloria Text -->
                <Link href="/" class="flex items-center gap-3 group">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-12 md:h-14 w-auto rounded-xl object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300" />
                    <span class="font-serif text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Eloria</span>
                </Link>

                <!-- Navigation Links: 100% Short Vietnamese -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-700">
                    <Link href="/" class="hover:text-rose-700 transition-colors">Trang chủ</Link>
                    <Link href="/portfolio" class="hover:text-rose-700 transition-colors">Mẫu thiệp</Link>
                    <Link href="/wedding/timeline" class="hover:text-rose-700 transition-colors">Lập kế hoạch</Link>
                    <Link href="/wedding/vendors" class="hover:text-rose-700 transition-colors">Đối tác</Link>
                    <Link href="/blog" class="text-rose-800 font-semibold border-b-2 border-rose-500 pb-0.5">Cẩm nang</Link>
                </nav>

                <!-- Header Actions -->
                <div class="flex items-center gap-2 md:gap-3">
                    <template v-if="authUser">
                        <Link href="/wedding/timeline" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md flex items-center gap-1.5 hover:scale-105 active:scale-95">
                            Vào Workspace
                            <ArrowRight class="w-4 h-4 text-rose-400" />
                        </Link>
                    </template>
                    <template v-else>
                        <Link href="/login" class="px-3.5 py-2 rounded-xl text-slate-700 hover:text-rose-700 hover:bg-rose-50 font-semibold text-xs md:text-sm transition-all">
                            Đăng nhập
                        </Link>
                        <Link href="/register" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-semibold text-xs md:text-sm transition-all shadow-md shadow-slate-900/10 flex items-center gap-1.5 hover:scale-105 active:scale-95">
                            Đăng ký
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Hero Header -->
        <section class="py-16 md:py-20 px-6 bg-gradient-to-b from-[#FFFDF9] via-[#FAF8F5] to-rose-50/30 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-100 border border-rose-200 text-rose-900 text-xs font-semibold uppercase tracking-wider mb-6">
                    <Sparkles class="w-4 h-4 text-rose-600" />
                    Cẩm Nang Lập Kế Hoạch Cưới Chuẩn Chuyên Gia
                </div>
                <h1 class="text-4xl md:text-5xl font-serif font-extrabold text-slate-900 tracking-tight leading-tight">
                    Kinh Nghiệm Cưới & <br />
                    <span class="bg-gradient-to-r from-rose-700 via-rose-600 to-amber-600 bg-clip-text text-transparent">Bí Quyết Tối Ưu Ngân Sách</span>
                </h1>
                <p class="mt-4 text-base text-slate-600 max-w-xl mx-auto">
                    Tổng hợp kiến thức tổ chức đám cưới, quản lý ngân sách dòng tiền, chọn nhà cung cấp và thiết kế thiệp điện tử độc bản.
                </p>
            </div>
        </section>

        <!-- Blog Catalog Section -->
        <main class="max-w-6xl mx-auto px-6 py-12">
            <div v-if="posts?.data && posts.data.length > 0" class="grid md:grid-cols-3 gap-8">
                <article v-for="post in posts.data" :key="post.id" class="p-6 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="w-full aspect-16/9 rounded-2xl bg-rose-50 border border-rose-100 mb-4 flex items-center justify-center text-rose-300">
                            <BookOpen class="w-10 h-10 text-rose-300" />
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                            <span class="flex items-center gap-1"><Clock class="w-3.5 h-3.5" /> {{ post.reading_time_minutes || 5 }} phút đọc</span>
                            <span>•</span>
                            <span class="flex items-center gap-1"><Calendar class="w-3.5 h-3.5" /> {{ new Date(post.published_at || Date.now()).toLocaleDateString('vi-VN') }}</span>
                        </div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-2 group-hover:text-rose-700 transition-colors line-clamp-2">
                            <Link :href="`/blog/${post.slug}`">{{ post.title }}</Link>
                        </h2>
                        <p class="text-slate-600 text-sm line-clamp-3 leading-relaxed mb-4">{{ post.excerpt }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <Link :href="`/blog/${post.slug}`" class="text-xs font-semibold text-rose-800 hover:text-rose-900 flex items-center gap-1">
                            Đọc bài viết <ArrowRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>
                </article>
            </div>

            <!-- Fallback Static Sample Posts if DB is empty -->
            <div v-else class="grid md:grid-cols-3 gap-8">
                <article class="p-6 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-full aspect-16/9 rounded-2xl bg-rose-50 border border-rose-100 mb-4 flex items-center justify-center">
                            <BookOpen class="w-10 h-10 text-rose-400" />
                        </div>
                        <span class="text-xs font-semibold text-rose-800 bg-rose-100 px-2.5 py-1 rounded-full">Quản lý Ngân sách</span>
                        <h2 class="text-lg font-serif font-bold text-slate-900 mt-3 mb-2">5 Bước Phổ Biến Tránh Vỡ Ngân Sách Đám Cưới</h2>
                        <p class="text-slate-600 text-xs line-clamp-3 leading-relaxed mb-4">Hướng dẫn chi tiết cách thiết lập trần chi phí, quản lý tiền cọc và theo dõi dòng tiền thanh toán đợt 1, 2, 3.</p>
                    </div>
                    <Link href="/blog" class="text-xs font-semibold text-rose-800 hover:underline flex items-center gap-1">Đọc bài viết →</Link>
                </article>

                <article class="p-6 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-full aspect-16/9 rounded-2xl bg-amber-50 border border-amber-100 mb-4 flex items-center justify-center">
                            <BookOpen class="w-10 h-10 text-amber-500" />
                        </div>
                        <span class="text-xs font-semibold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full">Thiệp Cưới Online</span>
                        <h2 class="text-lg font-serif font-bold text-slate-900 mt-3 mb-2">Xu Hướng Thiệp Cưới Điện Tử Pastel Năm 2026</h2>
                        <p class="text-slate-600 text-xs line-clamp-3 leading-relaxed mb-4">Khám phá phong cách thiệp cưới tích hợp nhạc nền, tem sáp niêm phong animation và tính năng RSVP xác nhận tự động.</p>
                    </div>
                    <Link href="/blog" class="text-xs font-semibold text-rose-800 hover:underline flex items-center gap-1">Đọc bài viết →</Link>
                </article>

                <article class="p-6 rounded-3xl bg-white border border-rose-100 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-full aspect-16/9 rounded-2xl bg-emerald-50 border border-emerald-100 mb-4 flex items-center justify-center">
                            <BookOpen class="w-10 h-10 text-emerald-500" />
                        </div>
                        <span class="text-xs font-semibold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full">Sơ Đồ Bàn Tiệc</span>
                        <h2 class="text-lg font-serif font-bold text-slate-900 mt-3 mb-2">Bí Quyết Sắp Xếp Sơ Đồ Bàn Tiệc Kéo Thả Tránh Quá Tải</h2>
                        <p class="text-slate-600 text-xs line-clamp-3 leading-relaxed mb-4">Cách phân chia vị trí khách mời gia đình, bạn bè và đối tác linh hoạt trên canvas trực quan.</p>
                    </div>
                    <Link href="/blog" class="text-xs font-semibold text-rose-800 hover:underline flex items-center gap-1">Đọc bài viết →</Link>
                </article>
            </div>
        </main>
    </div>
</template>
