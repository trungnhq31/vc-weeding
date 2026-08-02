<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import PastelTemplate from '@/Components/Wedding/Templates/PastelTemplate.vue';
import RoyalGoldTemplate from '@/Components/Wedding/Templates/RoyalGoldTemplate.vue';
import ModernSlateTemplate from '@/Components/Wedding/Templates/ModernSlateTemplate.vue';
import BotanicalSageTemplate from '@/Components/Wedding/Templates/BotanicalSageTemplate.vue';
import IndochineTemplate from '@/Components/Wedding/Templates/IndochineTemplate.vue';
import BoardingPassTemplate from '@/Components/Wedding/Templates/BoardingPassTemplate.vue';
import EmeraldLuxeTemplate from '@/Components/Wedding/Templates/EmeraldLuxeTemplate.vue';
import SunsetCoralTemplate from '@/Components/Wedding/Templates/SunsetCoralTemplate.vue';
import GazetteNewspaperTemplate from '@/Components/Wedding/Templates/GazetteNewspaperTemplate.vue';
import StorybookJournalTemplate from '@/Components/Wedding/Templates/StorybookJournalTemplate.vue';

interface Wish {
    id: string;
    sender_name: string;
    message: string;
    created_at: string;
}

interface Memory {
    id: string;
    uploader_name: string;
    category: string;
    title?: string;
    description?: string;
    image_url: string;
}

const props = defineProps<{
    wishes: Wish[];
    memories?: Memory[];
    guest?: any;
    templateSlug?: string;
}>();

const TEMPLATE_COMPONENTS: Record<string, any> = {
  'romantic-pastel': PastelTemplate,
  'royal-gold': RoyalGoldTemplate,
  'modern-slate': ModernSlateTemplate,
  'botanical-sage': BotanicalSageTemplate,
  'indochine-traditional': IndochineTemplate,
  'crimson-velvet': IndochineTemplate,
  'celestial-blue': BoardingPassTemplate,
  'ocean-breeze': BoardingPassTemplate,
  'emerald-luxe': EmeraldLuxeTemplate,
  'sunset-coral': SunsetCoralTemplate,
  'gazette-newspaper': GazetteNewspaperTemplate,
  'vintage-sepia': StorybookJournalTemplate,
  'storybook-journal': StorybookJournalTemplate,
};

const activeTemplateComponent = computed(() => {
  const slug = props.templateSlug || 'romantic-pastel';
  return TEMPLATE_COMPONENTS[slug] || PastelTemplate;
});

const defaultGuest = props.guest || {
    id: 'demo-guest',
    guest_slug: 'anh-tuan-va-chi-lan',
    name: 'Anh Tuấn & Chị Lan',
    salutation: 'Trân trọng kính mời Anh Tuấn & Chị Lan',
    estimated_count: 2,
    confirmed_count: 2,
    rsvp_status: 'attending',
};

const submitRsvp = async (data: any) => {
    try {
        await fetch('/wedding/rsvp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                guest_slug: defaultGuest.guest_slug,
                guest_name: defaultGuest.name,
                ...data,
            }),
        });
    } catch (e) {
        console.error('Error submitting RSVP:', e);
    }
};

const submitWish = async (sender: string, message: string) => {
    try {
        await fetch('/wedding/wishes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                sender_name: sender,
                message: message,
            }),
        });
    } catch (e) {
        console.error('Error submitting wish:', e);
    }
};

const uploadMemory = async (file: File, name: string, title?: string, desc?: string) => {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('uploader_name', name || defaultGuest.name || 'Khách Mời');
    if (title) formData.append('title', title);
    if (desc) formData.append('description', desc);

    await fetch('/wedding/memories/upload', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
        },
        body: formData,
    });
};
</script>

<template>
  <Head title="Thiệp Cưới Online — Eloria OS" />
  
  <component 
    :is="activeTemplateComponent" 
    :guest="defaultGuest" 
    :wishes="wishes" 
    :memories="memories"
    :submitRsvp="submitRsvp"
    :submitWish="submitWish"
    :uploadMemory="uploadMemory"
  />
</template>
