<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Code, BookOpen, Heart, Sparkles, Terminal } from 'lucide-vue-next';

interface Post {
    id: string;
    title: string;
    slug: string;
    excerpt: string;
    reading_time_minutes: number;
    published_at: string;
}

defineProps<{
    latestPosts: Post[];
}>();
</script>

<template>
    <Head title="Developer Portfolio & Tech Hub" />

    <div class="min-h-screen bg-slate-950 text-slate-100 font-sans">
        <!-- Header / Navigation -->
        <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3 font-bold text-xl tracking-tight text-white">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-8 w-auto rounded-lg border border-slate-700 shadow-xs" />
                    <span>Eloria <span class="text-indigo-400">OS</span></span>
                </div>
                <nav class="flex items-center gap-6 text-sm font-medium text-slate-300">
                    <Link href="/" class="hover:text-indigo-400 transition-colors">Portfolio</Link>
                    <Link href="/blog" class="hover:text-indigo-400 transition-colors">Blog</Link>
                    <a href="/wedding" class="px-4 py-2 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-all flex items-center gap-1.5 font-semibold">
                        <Heart class="w-4 h-4 text-rose-400 fill-rose-400/30" />
                        Eloria Wedding OS
                    </a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 py-24 flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold uppercase tracking-wider mb-6">
                <Sparkles class="w-3.5 h-3.5" /> Full-Stack Software Developer
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight max-w-4xl leading-tight">
                Crafting Scalable Backend Systems & Beautiful Web Experiences
            </h1>
            <p class="mt-6 text-lg text-slate-400 max-w-2xl leading-relaxed">
                Specialized in Laravel 13, Vue 3, Realtime Architectures, Docker & AI Automation.
            </p>
            <div class="mt-8 flex gap-4">
                <Link href="/blog" class="px-6 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                    <BookOpen class="w-4 h-4" /> Explore Technical Articles
                </Link>
                <a href="/wedding" class="px-6 py-3 rounded-lg bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 font-medium transition-all flex items-center gap-2">
                    <Heart class="w-4 h-4 text-rose-400" /> Wedding Online
                </a>
            </div>
        </section>

        <!-- Latest Blog Posts Section -->
        <section class="max-w-7xl mx-auto px-6 py-12 border-t border-slate-800">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <Code class="w-6 h-6 text-indigo-400" /> Latest Engineering Articles
                </h2>
                <Link href="/blog" class="text-sm font-medium text-indigo-400 hover:underline">View all articles →</Link>
            </div>

            <div v-if="latestPosts.length > 0" class="grid md:grid-cols-3 gap-6">
                <article v-for="post in latestPosts" :key="post.id" class="p-6 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition-all flex flex-col justify-between">
                    <div>
                        <div class="text-xs text-slate-400 mb-2">{{ post.reading_time_minutes }} min read • {{ new Date(post.published_at).toLocaleDateString() }}</div>
                        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 hover:text-indigo-400 transition-colors">
                            <Link :href="`/blog/${post.slug}`">{{ post.title }}</Link>
                        </h3>
                        <p class="text-slate-400 text-sm line-clamp-3 mb-4">{{ post.excerpt }}</p>
                    </div>
                    <Link :href="`/blog/${post.slug}`" class="text-xs font-semibold text-indigo-400 hover:underline flex items-center gap-1">
                        Read full article →
                    </Link>
                </article>
            </div>
            <div v-else class="p-12 text-center rounded-xl bg-slate-900/50 border border-slate-800 text-slate-400">
                Chưa có bài viết mới. Vui lòng quay lại sau!
            </div>
        </section>
    </div>
</template>
