<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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

interface Guest {
    id: string;
    guest_slug: string;
    name: string;
    salutation: string;
    group?: string;
    estimated_count: number;
    confirmed_count: number;
    dietary_preference?: string;
    shuttle_bus?: string;
    qr_code_token?: string;
    table_name?: string;
    rsvp_status: string;
    notes?: string;
}

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
    guest: Guest;
    wishes: Wish[];
    memories?: Memory[];
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

const submitRsvp = async (data: any) => {
    try {
        await fetch('/wedding/rsvp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                guest_slug: props.guest?.guest_slug,
                guest_name: props.guest?.name,
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
    formData.append('uploader_name', name || props.guest?.name || 'Khách Mời');
    if (title) formData.append('title', title);
    if (desc) formData.append('description', desc);
    if (props.guest?.id) formData.append('guest_id', props.guest.id);

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
  <Head :title="`Thiệp Cưới Online — ${guest?.name || 'Quốc Trung & Hồng Vân'}`" />
  
  <component 
    :is="activeTemplateComponent" 
    :guest="guest" 
    :wishes="wishes" 
    :memories="memories"
    :submitRsvp="submitRsvp"
    :submitWish="submitWish"
    :uploadMemory="uploadMemory"
  />
</template>
