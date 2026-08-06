<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
  Camera, 
  UploadCloud, 
  Heart, 
  CheckCircle2, 
  Sparkles, 
  ImageIcon, 
  X, 
  ArrowLeft 
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface Memory {
  id: string;
  uploader_name: string;
  category: string;
  title?: string;
  description?: string;
  image_url: string;
  is_approved: boolean;
  is_pinned: boolean;
}

interface WorkspaceInfo {
  name?: string;
  groom_name?: string;
  bride_name?: string;
  wedding_date?: string;
  wedding_location?: string;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  memories: Memory[];
  shareSlug: string;
}>();

const selectedCategory = ref<string>('all');
const showUploadModal = ref(false);
const activeLightbox = ref<Memory | null>(null);

const uploaderName = ref('');
const uploadDescription = ref('');
const selectedImageFile = ref<File | null>(null);
const isUploading = ref(false);
const successMessage = ref<string | null>(null);

const localMemories = ref<Memory[]>([...props.memories]);

const filteredMemories = computed(() => {
  if (selectedCategory.value === 'all') return localMemories.value;
  return localMemories.value.filter(m => m.category === selectedCategory.value);
});

const handleFileSelect = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    selectedImageFile.value = target.files[0];
  }
};

const submitGuestUpload = async () => {
  if (!selectedImageFile.value || !uploaderName.value) return;
  isUploading.value = true;

  const formData = new FormData();
  formData.append('uploader_name', uploaderName.value);
  if (uploadDescription.value) formData.append('description', uploadDescription.value);
  formData.append('image', selectedImageFile.value);

  try {
    const res = await fetch(`/wedding/shared-gallery/${props.shareSlug}/upload`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      },
      body: formData
    });
    const data = await res.json();
    if (data.success) {
      localMemories.value = [data.memory, ...localMemories.value];
      showUploadModal.value = false;
      uploaderName.value = '';
      uploadDescription.value = '';
      selectedImageFile.value = null;
      successMessage.value = data.message;
      setTimeout(() => { successMessage.value = null; }, 4000);
    }
  } catch (err) {
    console.error('Error uploading guest image:', err);
  } finally {
    isUploading.value = false;
  }
};
</script>

<template>
  <Head :title="`Gallery Kỷ Niệm — Đám Cưới ${workspace?.groom_name || 'Quốc Trung'} & ${workspace?.bride_name || 'Hồng Vân'}`" />

  <div class="min-h-screen bg-[#FAF8F5] text-slate-900 font-sans flex flex-col justify-between selection:bg-rose-100 selection:text-rose-900">
    <!-- Top Header Navigation -->
    <header class="py-6 px-6 max-w-5xl mx-auto w-full flex items-center justify-between border-b border-rose-100/80">
      <Link href="/wedding" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-rose-900 transition">
        <ArrowLeft class="w-4 h-4" /> Về Trang Thiệp
      </Link>

      <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full bg-rose-100/80 text-rose-900 text-xs font-extrabold flex items-center gap-1.5">
          <Heart class="w-3.5 h-3.5 fill-rose-600 text-rose-600" />
          <span>{{ workspace?.groom_name || 'Quốc Trung' }} & {{ workspace?.bride_name || 'Hồng Vân' }}</span>
        </span>
      </div>
    </header>

    <!-- Main Content Stream -->
    <main class="max-w-5xl mx-auto px-6 py-8 w-full flex-1 space-y-8">
      
      <!-- Welcome Hero Banner -->
      <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xl shadow-rose-900/5 text-center space-y-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-900 text-[11px] font-extrabold uppercase tracking-wider">
          <Camera class="w-3.5 h-3.5 text-rose-600" /> BẢO TÀNG ẢNH KỶ NIỆM TIỆC CƯỚI
        </div>

        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">
          Kho Ảnh Kỷ Niệm Đám Cưới
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto font-medium leading-relaxed">
          Nơi lưu trữ trọn vẹn những bộ ảnh Pre-wedding và khoảnh khắc đẹp do khách mời chụp và tải lên trực tiếp tại tiệc cưới!
        </p>

        <div class="pt-2">
          <button 
            @click="showUploadModal = true"
            class="px-6 py-3 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/20 transition flex items-center gap-2 mx-auto cursor-pointer"
          >
            <Camera class="w-4 h-4" /> Đăng Ảnh Chụp Cùng Dâu Rể
          </button>
        </div>
      </div>

      <!-- Success Notification Alert -->
      <div v-if="successMessage" class="p-4 rounded-2xl bg-emerald-600 text-white font-bold text-xs shadow-lg flex items-center justify-between animate-fade-in">
        <span class="flex items-center gap-2">
          <CheckCircle2 class="w-4 h-4" /> {{ successMessage }}
        </span>
        <button @click="successMessage = null" class="font-bold">✕</button>
      </div>

      <!-- Category Filter Buttons -->
      <div class="flex items-center justify-center gap-2 overflow-x-auto pb-2">
        <button 
          @click="selectedCategory = 'all'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer shrink-0"
          :class="selectedCategory === 'all' ? 'bg-rose-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-rose-50'"
        >
          Tất Cả Ảnh ({{ localMemories.length }})
        </button>
        <button 
          @click="selectedCategory = 'pre_wedding'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer shrink-0"
          :class="selectedCategory === 'pre_wedding' ? 'bg-rose-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-rose-50'"
        >
          Pre-Wedding
        </button>
        <button 
          @click="selectedCategory = 'engagement'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer shrink-0"
          :class="selectedCategory === 'engagement' ? 'bg-rose-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-rose-50'"
        >
          Lễ Gia Tiên
        </button>
        <button 
          @click="selectedCategory = 'wedding_day'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer shrink-0"
          :class="selectedCategory === 'wedding_day' ? 'bg-rose-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-rose-50'"
        >
          Tiệc Cưới
        </button>
        <button 
          @click="selectedCategory = 'guest_upload'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer shrink-0"
          :class="selectedCategory === 'guest_upload' ? 'bg-rose-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-rose-50'"
        >
          Khách Tải Lên
        </button>
      </div>

      <!-- Public Gallery Image Grid -->
      <div v-if="filteredMemories.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <div 
          v-for="item in filteredMemories" 
          :key="item.id"
          @click="activeLightbox = item"
          class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-100 shadow-xs hover:shadow-xl transition-all duration-300 cursor-pointer"
        >
          <img 
            :src="item.image_url" 
            :alt="item.title || 'Ảnh kỷ niệm'"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
          />

          <!-- Overlay gradient info -->
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-3 flex flex-col justify-end text-white">
            <span class="text-[11px] font-bold truncate block">{{ item.uploader_name }}</span>
            <span v-if="item.description" class="text-[10px] text-rose-200 line-clamp-1 font-medium">{{ item.description }}</span>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-12 bg-white rounded-3xl border border-rose-100 p-6 space-y-3">
        <ImageIcon class="w-10 h-10 text-rose-300 mx-auto" />
        <p class="text-xs text-slate-500 font-medium">Chưa có ảnh nào trong hạng mục này.</p>
      </div>
    </main>

    <!-- Guest Upload Modal -->
    <div v-if="showUploadModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
      <div class="w-full max-w-md bg-white p-6 rounded-3xl border border-rose-100 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <Camera class="w-5 h-5 text-rose-600" /> Tải Ảnh Chụp Cùng Dâu Rể
          </h3>
          <button @click="showUploadModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <div class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Tên Của Bạn (Người đăng) *</label>
            <input v-model="uploaderName" type="text" placeholder="VD: Anh Tuấn & Chị Lan..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Lời Chúc / Ghi Chú</label>
            <textarea v-model="uploadDescription" rows="2" placeholder="Chúc hai bạn trăm năm hạnh phúc..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400"></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Chọn Ảnh Từ Điện Thoại *</label>
            <input type="file" accept="image/*" @change="handleFileSelect" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-900 hover:file:bg-rose-100 cursor-pointer" />
          </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
          <button @click="showUploadModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">Hủy Bỏ</button>
          <button @click="submitGuestUpload" :disabled="isUploading || !selectedImageFile || !uploaderName" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 cursor-pointer disabled:opacity-50">
            {{ isUploading ? 'Đang Đăng Ảnh...' : 'Gửi Ảnh Cho Dâu Rể' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Lightbox Modal -->
    <div v-if="activeLightbox" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4" @click="activeLightbox = null">
      <div class="max-w-3xl w-full flex flex-col items-center gap-4" @click.stop>
        <img :src="activeLightbox.image_url" class="max-h-[75vh] w-auto object-contain rounded-2xl shadow-2xl border border-white/20" />
        <div class="text-center text-white space-y-1">
          <h3 class="font-serif text-lg font-bold">{{ activeLightbox.title || 'Ảnh Kỷ Niệm Cưới' }}</h3>
          <p class="text-xs text-rose-200 font-medium">Người đăng: {{ activeLightbox.uploader_name }}</p>
          <p v-if="activeLightbox.description" class="text-xs text-slate-300 max-w-md mx-auto">{{ activeLightbox.description }}</p>
        </div>
        <button @click="activeLightbox = null" class="px-6 py-2 rounded-full bg-white/20 hover:bg-white/30 text-white font-bold text-xs transition cursor-pointer">
          Đóng Lại
        </button>
      </div>
    </div>

    <!-- Footer -->
    <footer class="py-6 px-6 text-center text-xs text-slate-400 border-t border-rose-100/60 max-w-5xl mx-auto w-full">
      © 2026 Eloria Wedding OS. All rights reserved.
    </footer>
  </div>
</template>
