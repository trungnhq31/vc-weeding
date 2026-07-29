<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen, Search } from 'lucide-vue-next';

defineProps<{
    posts: any;
    categories: any[];
    tags: any[];
    filters: any;
}>();
</script>

<template>
    <Head title="Technical Blog & Articles" />

    <div class="min-h-screen bg-slate-950 text-slate-100 font-sans py-12 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-bold text-white flex items-center justify-center gap-3">
                    <BookOpen class="w-8 h-8 text-indigo-400" /> Engineering Blog
                </h1>
                <p class="text-slate-400 mt-2">Chia sẻ kiến thức về Software Architecture, Laravel 13, Vue 3, Docker & AI Automation.</p>
            </div>

            <div class="space-y-6">
                <div v-for="post in posts.data" :key="post.id" class="p-6 rounded-xl bg-slate-900 border border-slate-800">
                    <h2 class="text-xl font-bold text-white mb-2">
                        <Link :href="`/blog/${post.slug}`" class="hover:text-indigo-400 transition-colors">{{ post.title }}</Link>
                    </h2>
                    <p class="text-slate-400 text-sm mb-4">{{ post.excerpt }}</p>
                    <Link :href="`/blog/${post.slug}`" class="text-xs font-semibold text-indigo-400 hover:underline">Read article →</Link>
                </div>
            </div>
        </div>
    </div>
</template>
