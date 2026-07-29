<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

defineProps<{
    post: any;
}>();
</script>

<template>
    <Head :title="post.title" />

    <div class="min-h-screen bg-slate-950 text-slate-100 font-sans py-12 px-6">
        <div class="max-w-3xl mx-auto">
            <Link href="/blog" class="inline-flex items-center gap-2 text-sm text-indigo-400 hover:underline mb-8">
                <ArrowLeft class="w-4 h-4" /> Back to all articles
            </Link>

            <article>
                <h1 class="text-4xl font-extrabold text-white mb-4 leading-tight">{{ post.title }}</h1>
                <div class="text-sm text-slate-400 mb-8 border-b border-slate-800 pb-4">
                    Published on {{ new Date(post.published_at).toLocaleDateString() }} • {{ post.reading_time_minutes }} min read
                </div>
                <div class="prose prose-invert max-w-none text-slate-300">
                    <p>{{ post.content || post.excerpt }}</p>
                </div>
            </article>
        </div>
    </div>
</template>
