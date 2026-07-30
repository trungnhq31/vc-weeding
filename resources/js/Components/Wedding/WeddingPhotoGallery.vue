<script setup lang="ts">
import { ref, computed } from 'vue';
import { Sparkles, Maximize2, X, UploadCloud, Heart, Image as ImageIcon, CheckCircle2 } from 'lucide-vue-next';

interface Memory {
    id: string;
    uploader_name: string;
    category: string;
    title?: string;
    description?: string;
    image_url: string;
    created_at?: string;
}

const props = defineProps<{
    memories?: Memory[];
    guestId?: string | null;
}>();

const emit = defineEmits(['uploaded']);

// Default photo collection if memories prop is empty
const defaultPhotos = [
    {
        id: 'def-1',
        uploader_name: 'Chú Rể Quốc Trung',
        category: 'pre_wedding',
        title: 'Chân Dung Đôi Lứa',
        description: 'Bộ ảnh ngoại cảnh Đà Lạt ngập tràn ánh nắng.',
        image_url: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80',
    },
    {
        id: 'def-2',
        uploader_name: 'Cô Dâu Hồng Vân',
        category: 'engagement',
        title: 'Khoảnh Khắc Cầu Hôn',
        description: 'Lời hứa chân thành dưới hoàng hôn biển Phú Quốc.',
        image_url: 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1200&q=80',
    },
    {
        id: 'def-3',
        uploader_name: 'Quốc Trung & Hồng Vân',
        category: 'wedding_day',
        title: 'Sóng Bước Bên Nhau',
        description: 'Hành trình vạn dặm đong đầy yêu thương.',
        image_url: 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=1200&q=80',
    },
    {
        id: 'def-4',
        uploader_name: 'Nhiếp Ảnh Gia',
        category: 'pre_wedding',
        title: 'Bình Minh Trên Đồi Thông',
        description: 'Nụ cười rạng rỡ của dâu rể trong tiết trời se lạnh.',
        image_url: 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=1200&q=80',
    },
    {
        id: 'def-5',
        uploader_name: 'Gia Đình Hai Họ',
        category: 'engagement',
        title: 'Nghi Lễ Trao Nhẫn Đính Hôn',
        description: 'Sự chứng kiến ấm áp của hai gia đình.',
        image_url: 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=1200&q=80',
    },
];

const selectedCategory = ref<string>('all');
const activePhoto = ref<Memory | null>(null);

// Upload Modal State
const isUploadOpen = ref(false);
const uploaderName = ref('');
const memoryTitle = ref('');
const memoryDesc = ref('');
const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(null);
const isUploading = ref(false);
const uploadSuccess = ref(false);

const categories = [
    { key: 'all', label: 'Tất Cả Album' },
    { key: 'pre_wedding', label: 'Pre-Wedding' },
    { key: 'engagement', label: 'Lễ Đính Hôn' },
    { key: 'wedding_day', label: 'Tiệc Cưới' },
    { key: 'guest_upload', label: 'Kỷ Niệm Cùng Dâu Rể' },
];

const allPhotos = computed(() => {
    if (props.memories && props.memories.length > 0) {
        return [...props.memories, ...defaultPhotos];
    }
    return defaultPhotos;
});

const filteredPhotos = computed(() => {
    if (selectedCategory.value === 'all') return allPhotos.value;
    return allPhotos.value.filter(p => p.category === selectedCategory.value);
});

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submitMemory = async () => {
    if (!imageFile.value || !uploaderName.value) return;
    isUploading.value = true;

    const formData = new FormData();
    formData.append('image', imageFile.value);
    formData.append('uploader_name', uploaderName.value);
    if (memoryTitle.value) formData.append('title', memoryTitle.value);
    if (memoryDesc.value) formData.append('description', memoryDesc.value);
    if (props.guestId) formData.append('guest_id', props.guestId);

    try {
        const response = await fetch('/wedding/memories/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: formData,
        });

        if (response.ok) {
            uploadSuccess.value = true;
            uploaderName.value = '';
            memoryTitle.value = '';
            memoryDesc.value = '';
            imageFile.value = null;
            imagePreview.value = null;
            setTimeout(() => {
                uploadSuccess.value = false;
                isUploadOpen.value = false;
            }, 2500);
            emit('uploaded');
        }
    } catch (e) {
        console.error('Lỗi khi tải ảnh kỷ niệm:', e);
    } finally {
        isUploading.value = false;
    }
};
</script>

<template>
    <section class="py-24 px-6 relative bg-gradient-to-b from-[#FAF8F5] via-rose-50/40 to-[#FAF8F5]">
        <div class="max-w-6xl mx-auto space-y-12">
            
            <!-- Section Header -->
            <div class="text-center space-y-3">
                <span class="text-xs uppercase tracking-[0.4em] text-rose-800 font-mono font-bold block">Wedding Memories</span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-rose-950">
                    Kho Kỷ Niệm Đám Cưới
                </h2>
                <p class="text-sm font-serif italic text-slate-600 max-w-xl mx-auto">
                    "Từng khoảnh khắc trôi qua là một mảnh ghép thanh xuân rực rỡ nhất của hai chúng mình."
                </p>

                <!-- Action Button: Guest Upload Portal -->
                <div class="pt-2">
                    <button 
                        @click="isUploadOpen = true"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-rose-200 transition-all cursor-pointer"
                    >
                        <UploadCloud class="w-4 h-4" /> Tải Ảnh Kỷ Niệm Cùng Dâu Rể
                    </button>
                </div>
            </div>

            <!-- Category Filter Tabs -->
            <div class="flex items-center justify-center flex-wrap gap-2.5">
                <button 
                    v-for="cat in categories"
                    :key="cat.key"
                    @click="selectedCategory = cat.key"
                    class="px-5 py-2.5 rounded-full text-xs font-bold transition-all cursor-pointer border"
                    :class="selectedCategory === cat.key ? 'bg-rose-800 text-white border-rose-800 shadow-md shadow-rose-200' : 'bg-white/80 text-rose-900 border-rose-200 hover:bg-rose-100/60'"
                >
                    {{ cat.label }}
                </button>
            </div>

            <!-- Photos Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="photo in filteredPhotos" 
                    :key="photo.id"
                    @click="activePhoto = photo"
                    class="group relative rounded-3xl overflow-hidden bg-white border border-rose-200/80 shadow-lg shadow-rose-950/5 cursor-pointer transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
                >
                    <div class="aspect-[4/5] w-full relative overflow-hidden bg-rose-50">
                        <img 
                            :src="photo.image_url" 
                            :alt="photo.title || 'Ảnh kỷ niệm'"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-rose-950/85 via-rose-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 text-white">
                            <span class="text-xs font-mono uppercase tracking-widest text-amber-300 font-bold mb-1">
                                {{ photo.uploader_name }}
                            </span>
                            <h4 class="font-serif font-bold text-xl leading-snug">{{ photo.title || 'Kỷ niệm đẹp' }}</h4>
                            <p v-if="photo.description" class="text-xs text-rose-100/90 line-clamp-2 mt-1 font-serif italic">{{ photo.description }}</p>
                        </div>
                    </div>
                    <div class="p-4 bg-white border-t border-rose-100 flex items-center justify-between">
                        <div>
                            <span class="font-serif font-bold text-rose-950 text-sm block leading-tight">{{ photo.title || 'Khoảnh Khắc Cưới' }}</span>
                            <span class="text-[11px] text-slate-500 font-serif italic">Bởi: {{ photo.uploader_name }}</span>
                        </div>
                        <Maximize2 class="w-4 h-4 text-rose-600 opacity-70 group-hover:opacity-100 transition-opacity shrink-0" />
                    </div>
                </div>
            </div>

            <!-- Lightbox Zoom Modal -->
            <div 
                v-if="activePhoto"
                @click="activePhoto = null"
                class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4"
            >
                <button 
                    @click="activePhoto = null"
                    class="absolute top-6 right-6 p-3 rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors cursor-pointer"
                >
                    <X class="w-6 h-6" />
                </button>
                
                <div @click.stop class="max-w-4xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[85vh]">
                    <div class="md:w-2/3 bg-black flex items-center justify-center p-2">
                        <img 
                            :src="activePhoto.image_url" 
                            :alt="activePhoto.title || 'Ảnh phóng to'" 
                            class="max-w-full max-h-[75vh] object-contain"
                        />
                    </div>
                    <div class="md:w-1/3 p-8 flex flex-col justify-between bg-[#FAF8F5] border-l border-rose-100 space-y-4">
                        <div class="space-y-3">
                            <span class="text-xs font-mono uppercase tracking-widest text-rose-700 font-bold block">
                                {{ activePhoto.uploader_name }}
                            </span>
                            <h3 class="text-2xl font-serif font-bold text-rose-950 leading-snug">
                                {{ activePhoto.title || 'Bức Ảnh Kỷ Niệm' }}
                            </h3>
                            <p v-if="activePhoto.description" class="text-sm font-serif italic text-slate-700 leading-relaxed">
                                "{{ activePhoto.description }}"
                            </p>
                        </div>
                        <div class="pt-4 border-t border-rose-200 flex items-center justify-between text-xs text-rose-800 font-bold">
                            <span class="flex items-center gap-1"><Heart class="w-4 h-4 fill-rose-500 text-rose-500" /> VCWedding Memory</span>
                            <button @click="activePhoto = null" class="text-slate-500 hover:text-slate-800">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Photo Modal -->
            <div 
                v-if="isUploadOpen"
                @click.self="isUploadOpen = false"
                class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4"
            >
                <div class="relative w-full max-w-lg bg-[#FAF8F5] rounded-3xl border border-rose-200 shadow-2xl p-6 md:p-8 space-y-6">
                    <button 
                        @click="isUploadOpen = false"
                        class="absolute top-4 right-4 p-2 rounded-full bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors"
                    >
                        <X class="w-5 h-5" />
                    </button>

                    <div class="text-center space-y-2">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-rose-100 text-rose-900 text-xs font-bold uppercase tracking-wider">
                            <UploadCloud class="w-4 h-4 text-rose-600" /> Tải Ảnh Kỷ Niệm
                        </div>
                        <h3 class="text-2xl font-serif font-bold text-rose-950">Gửi Kỷ Niệm Cùng Dâu Rể</h3>
                        <p class="text-xs font-serif text-slate-600 italic">Chia sẻ bức ảnh bạn đã chụp cùng Quốc Trung & Hồng Vân nhé!</p>
                    </div>

                    <form @submit.prevent="submitMemory" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Tên Của Bạn *</label>
                            <input 
                                v-model="uploaderName" 
                                type="text" 
                                required 
                                placeholder="Nhập tên người gửi..." 
                                class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-white"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Tiêu Đề Ảnh</label>
                            <input 
                                v-model="memoryTitle" 
                                type="text" 
                                placeholder="VD: Ảnh chụp tại cổng hoa cưới..." 
                                class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-white"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Lời Nhắn / Mô Tả</label>
                            <textarea 
                                v-model="memoryDesc" 
                                rows="2" 
                                placeholder="Viết vài dòng chia sẻ kỷ niệm..." 
                                class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:border-rose-500 outline-none text-slate-800 bg-white"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1">Chọn Bức Ảnh *</label>
                            <input 
                                type="file" 
                                accept="image/*" 
                                required
                                @change="handleFileChange" 
                                class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-100 file:text-rose-900 hover:file:bg-rose-200 cursor-pointer"
                            />
                        </div>

                        <div v-if="imagePreview" class="w-full h-36 rounded-2xl overflow-hidden bg-rose-50 border border-rose-200 flex items-center justify-center">
                            <img :src="imagePreview" alt="Xem trước ảnh" class="h-full object-contain" />
                        </div>

                        <button 
                            type="submit" 
                            :disabled="isUploading"
                            class="w-full py-3.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-md shadow-rose-200 transition-all flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <UploadCloud class="w-5 h-5" /> {{ isUploading ? 'Đang Tải Ảnh Lên...' : 'Tải Ảnh Lên Album' }}
                        </button>

                        <div v-if="uploadSuccess" class="p-3 rounded-xl bg-emerald-100 text-emerald-800 text-sm font-semibold text-center flex items-center justify-center gap-2">
                            <CheckCircle2 class="w-4 h-4 text-emerald-600" /> Tải ảnh lên thành công! Cảm ơn bạn.
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</template>
