<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { 
  Image as ImageIcon, 
  UploadCloud, 
  Pin, 
  Trash2, 
  Eye, 
  EyeOff, 
  Share2, 
  QrCode, 
  Sparkles, 
  Heart, 
  Plus, 
  CheckCircle2, 
  ExternalLink,
  Layers,
  Camera,
  Star,
  Maximize2
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';

interface Memory {
  id: string;
  uploader_name: string;
  category: string;
  title?: string;
  description?: string;
  image_url: string;
  is_approved: boolean;
  is_pinned: boolean;
  created_at?: string;
}

interface Stats {
  totalPhotos: number;
  preWeddingCount: number;
  engagementCount: number;
  weddingDayCount: number;
  guestUploadCount: number;
  pinnedCount: number;
}

interface WorkspaceInfo {
  name?: string;
  groom_name?: string;
  bride_name?: string;
  slug?: string;
}

const props = defineProps<{
  workspace?: WorkspaceInfo;
  memories: Memory[];
  shareUrl: string;
  stats: Stats;
}>();

const selectedCategory = ref<string>('all');
const showUploadModal = ref(false);
const showQrModal = ref(false);

const uploadCategory = ref<string>('pre_wedding');
const uploadTitle = ref<string>('');
const uploadDescription = ref<string>('');
const selectedFiles = ref<File[]>([]);
const isUploading = ref(false);
const toastMessage = ref<string | null>(null);

const activeLightboxImage = ref<Memory | null>(null);

const localMemories = ref<Memory[]>([...props.memories]);

const filteredMemories = computed(() => {
  if (selectedCategory.value === 'all') return localMemories.value;
  if (selectedCategory.value === 'pinned') return localMemories.value.filter(m => m.is_pinned);
  return localMemories.value.filter(m => m.category === selectedCategory.value);
});

const handleFileSelect = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files) {
    selectedFiles.value = Array.from(target.files);
  }
};

const handleUpload = async () => {
  if (selectedFiles.value.length === 0) return;
  isUploading.value = true;

  const formData = new FormData();
  formData.append('category', uploadCategory.value);
  if (uploadTitle.value) formData.append('title', uploadTitle.value);
  if (uploadDescription.value) formData.append('description', uploadDescription.value);

  selectedFiles.value.forEach(file => {
    formData.append('images[]', file);
  });

  try {
    const res = await fetch('/wedding/gallery', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      },
      body: formData
    });
    const data = await res.json();
    if (data.success) {
      localMemories.value = [...data.memories, ...localMemories.value];
      showUploadModal.value = false;
      selectedFiles.value = [];
      uploadTitle.value = '';
      uploadDescription.value = '';
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error uploading images:', err);
  } finally {
    isUploading.value = false;
  }
};

const togglePin = async (memory: Memory) => {
  try {
    const res = await fetch(`/wedding/gallery/${memory.id}/pin`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      }
    });
    const data = await res.json();
    if (data.success) {
      memory.is_pinned = data.is_pinned;
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error pinning image:', err);
  }
};

const toggleApproval = async (memory: Memory) => {
  try {
    const res = await fetch(`/wedding/gallery/${memory.id}/approve`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      }
    });
    const data = await res.json();
    if (data.success) {
      memory.is_approved = data.is_approved;
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error approving image:', err);
  }
};

const deleteMemory = async (memory: Memory) => {
  if (!confirm('Bạn có chắc chắn muốn xóa ảnh này khỏi Gallery?')) return;

  try {
    const res = await fetch(`/wedding/gallery/${memory.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
      }
    });
    const data = await res.json();
    if (data.success) {
      localMemories.value = localMemories.value.filter(m => m.id !== memory.id);
      showToast(data.message);
    }
  } catch (err) {
    console.error('Error deleting image:', err);
  }
};

const copyShareUrl = () => {
  navigator.clipboard.writeText(props.shareUrl);
  showToast('Đã sao chép đường dẫn Gallery công khai!');
};

const showToast = (msg: string) => {
  toastMessage.value = msg;
  setTimeout(() => { toastMessage.value = null; }, 3000);
};

const getCategoryLabel = (cat: string) => {
  switch (cat) {
    case 'pre_wedding': return 'Bộ Ảnh Pre-Wedding';
    case 'engagement': return 'Lễ Ăn Hỏi & Gia Tiên';
    case 'wedding_day': return 'Tiệc Cưới Chính';
    case 'guest_upload': return 'Khách Mời Tải Lên';
    default: return 'Bộ Ảnh Tiệc Cưới';
  }
};
</script>

<template>
  <Head title="Album & Gallery Online Tiệc Cưới — Eloria OS" />

  <WorkspaceLayout title="Album & Gallery Online Tiệc Cưới" active-nav="gallery">
    <main class="max-w-6xl mx-auto px-6 py-8 space-y-8">
      
      <!-- Top Banner Header -->
      <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xl shadow-rose-900/5 space-y-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 border-b border-slate-100 pb-6">
          <div class="space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-900 text-[11px] font-extrabold uppercase tracking-wider">
              <Camera class="w-3.5 h-3.5 text-rose-600" /> BỘ BẢO TÀNG KỶ NIỆM ONLINE
            </div>

            <!-- Couple Name Header -->
            <div class="space-y-2">
              <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">
                Album & Gallery Kỷ Niệm Tiệc Cưới
              </h1>
              
              <div class="flex items-center gap-3 flex-wrap pt-1">
                <div class="px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold flex items-center gap-2">
                  <span class="text-rose-700 font-extrabold uppercase text-[10px] tracking-wider">Chú Rể:</span>
                  <span class="text-slate-900 font-bold">{{ workspace?.groom_name || 'Nguyễn Hoàng Quốc Trung' }}</span>
                </div>
                <span class="text-slate-300 font-bold">&</span>
                <div class="px-3 py-1.5 rounded-xl bg-pink-50 border border-pink-200 text-xs font-bold flex items-center gap-2">
                  <span class="text-pink-700 font-extrabold uppercase text-[10px] tracking-wider">Cô Dâu:</span>
                  <span class="text-slate-900 font-bold">{{ workspace?.bride_name || 'Lê Thị Hồng Vân' }}</span>
                </div>
              </div>
            </div>

            <p class="text-xs md:text-sm text-slate-600 font-medium">
              Tải lên và phân loại album ảnh Pre-wedding, Lễ gia tiên & quản lý hình ảnh do khách mời chụp và tải lên trong ngày cưới.
            </p>
          </div>

          <!-- Top Action Buttons -->
          <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <a 
              :href="shareUrl" 
              target="_blank" 
              class="px-4 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center gap-2 cursor-pointer shadow-md"
            >
              <ExternalLink class="w-4 h-4 text-rose-300" /> Xem Gallery Công Khai
            </a>

            <button 
              @click="showQrModal = true" 
              class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition flex items-center gap-2 cursor-pointer"
            >
              <QrCode class="w-4 h-4 text-rose-600" /> Mã QR Cho Khách
            </button>

            <button 
              @click="copyShareUrl" 
              class="px-4 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-900 text-xs font-bold border border-rose-200 transition flex items-center gap-2 cursor-pointer"
            >
              <Share2 class="w-4 h-4 text-rose-600" /> Chia Sẻ Link
            </button>

            <button 
              @click="showUploadModal = true" 
              class="px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-2 cursor-pointer"
            >
              <UploadCloud class="w-4 h-4" /> Tải Ảnh Mới
            </button>
          </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-medium text-slate-700">
          <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localMemories.length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Tổng Số Ảnh Kỷ Niệm</span>
            </div>
            <ImageIcon class="w-6 h-6 text-rose-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localMemories.filter(m => m.is_pinned).length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Ảnh Được Ghim Đẹp</span>
            </div>
            <Pin class="w-6 h-6 text-amber-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localMemories.filter(m => m.category === 'guest_upload').length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Khách Mời Đã Tải Lên</span>
            </div>
            <Camera class="w-6 h-6 text-blue-500" />
          </div>

          <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex items-center justify-between">
            <div>
              <span class="font-extrabold text-slate-900 text-base block">{{ localMemories.filter(m => m.is_approved).length }}</span>
              <span class="text-[11px] text-slate-600 font-medium">Ảnh Đã Duyệt Công Khai</span>
            </div>
            <CheckCircle2 class="w-6 h-6 text-emerald-500" />
          </div>
        </div>
      </div>

      <!-- Toast Notification -->
      <div v-if="toastMessage" class="p-4 rounded-2xl bg-emerald-600 text-white font-bold text-xs shadow-lg flex items-center justify-between animate-fade-in">
        <span>{{ toastMessage }}</span>
        <button @click="toastMessage = null" class="font-bold">✕</button>
      </div>

      <!-- Album Category Tabs Switcher -->
      <div class="flex items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-rose-100 shadow-2xs overflow-x-auto">
        <div class="flex items-center gap-2 shrink-0">
          <button 
            @click="selectedCategory = 'all'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="selectedCategory === 'all' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Tất Cả Album ({{ localMemories.length }})
          </button>
          
          <button 
            @click="selectedCategory = 'pre_wedding'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="selectedCategory === 'pre_wedding' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Pre-Wedding
          </button>

          <button 
            @click="selectedCategory = 'engagement'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="selectedCategory === 'engagement' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Lễ Ăn Hỏi & Gia Tiên
          </button>

          <button 
            @click="selectedCategory = 'wedding_day'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="selectedCategory === 'wedding_day' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Tiệc Cưới
          </button>

          <button 
            @click="selectedCategory = 'guest_upload'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
            :class="selectedCategory === 'guest_upload' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
          >
            Khách Mời Tải Lên
          </button>

          <button 
            @click="selectedCategory = 'pinned'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5"
            :class="selectedCategory === 'pinned' ? 'bg-amber-600 text-white shadow-2xs' : 'bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100'"
          >
            <Pin class="w-3.5 h-3.5" /> Ảnh Đã Ghim
          </button>
        </div>
      </div>

      <!-- Photo Grid List -->
      <div v-if="filteredMemories.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div 
          v-for="item in filteredMemories" 
          :key="item.id"
          class="group relative bg-white rounded-3xl border border-rose-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
        >
          <!-- Image Thumbnail View -->
          <div class="relative aspect-4/3 overflow-hidden bg-slate-100 cursor-pointer" @click="activeLightboxImage = item" title="Click để xem hình ảnh khổ lớn">
            <img 
              :src="item.image_url" 
              :alt="item.title || 'Ảnh kỷ niệm'"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
            />

            <!-- Hover Prompt Banner -->
            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5">
              <Maximize2 class="w-4 h-4 text-rose-300" /> Xem Ảnh Khổ Lớn
            </div>

            <!-- Pinned Badge -->
            <div v-if="item.is_pinned" class="absolute top-3 left-3 px-2.5 py-1 rounded-xl bg-amber-500 text-white text-[10px] font-extrabold flex items-center gap-1 shadow-md">
              <Pin class="w-3 h-3 fill-white" /> Đã Ghim
            </div>

            <!-- Approval Badge -->
            <div class="absolute top-3 right-3">
              <span v-if="item.is_approved" class="px-2.5 py-1 rounded-xl bg-emerald-500/90 backdrop-blur-xs text-white text-[10px] font-extrabold flex items-center gap-1">
                <CheckCircle2 class="w-3 h-3" /> Công Khai
              </span>
              <span v-else class="px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-extrabold flex items-center gap-1">
                <EyeOff class="w-3 h-3 text-slate-300" /> Đang Ẩn
              </span>
            </div>
          </div>

          <!-- Card Description Footer -->
          <div class="p-4 space-y-3 flex-1 flex flex-col justify-between text-xs">
            <div>
              <div class="flex items-center justify-between gap-2 text-[10px] text-slate-500 mb-1 font-semibold">
                <span class="uppercase tracking-wider font-extrabold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md">
                  {{ getCategoryLabel(item.category) }}
                </span>
                <span>{{ item.uploader_name }}</span>
              </div>
              <h4 class="font-bold text-slate-900 text-xs line-clamp-1 mt-1">{{ item.title || 'Kỷ niệm cưới' }}</h4>
              <p v-if="item.description" class="text-[11px] text-slate-600 line-clamp-2 mt-0.5 font-medium">{{ item.description }}</p>
            </div>

            <!-- Card Actions Bar -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <button 
                @click="togglePin(item)"
                class="px-2.5 py-1.5 rounded-xl text-[11px] font-bold border transition flex items-center gap-1 cursor-pointer"
                :class="item.is_pinned ? 'bg-amber-50 text-amber-900 border-amber-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                :title="item.is_pinned ? 'Bỏ ghim' : 'Ghim ưu tiên'"
              >
                <Pin class="w-3.5 h-3.5" />
                <span>{{ item.is_pinned ? 'Bỏ Ghim' : 'Ghim' }}</span>
              </button>

              <button 
                @click="toggleApproval(item)"
                class="px-2.5 py-1.5 rounded-xl text-[11px] font-bold border transition flex items-center gap-1 cursor-pointer"
                :class="item.is_approved ? 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' : 'bg-emerald-50 text-emerald-900 border-emerald-200'"
              >
                <Eye v-if="!item.is_approved" class="w-3.5 h-3.5 text-emerald-600" />
                <EyeOff v-else class="w-3.5 h-3.5 text-slate-500" />
                <span>{{ item.is_approved ? 'Ẩn' : 'Duyệt' }}</span>
              </button>

              <button 
                @click="deleteMemory(item)"
                class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                title="Xóa ảnh khỏi Album"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-white rounded-3xl border border-rose-100 p-8 space-y-4">
        <ImageIcon class="w-12 h-12 text-rose-300 mx-auto" />
        <h3 class="text-base font-serif font-bold text-slate-900">Chưa có ảnh nào trong Album này</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto">Nhấn nút "Tải Ảnh Mới" để bắt đầu lưu trữ những khoảnh khắc Pre-wedding & Tiệc cưới tuyệt đẹp.</p>
        <button @click="showUploadModal = true" class="px-5 py-2.5 rounded-2xl bg-rose-600 text-white font-bold text-xs hover:bg-rose-500 transition shadow-md">
          Tải Ảnh Lên Album
        </button>
      </div>

      <!-- Multi-Image Upload Modal -->
      <div v-if="showUploadModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white p-6 rounded-3xl border border-rose-100 shadow-2xl space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              <UploadCloud class="w-5 h-5 text-rose-600" /> Tải Ảnh Kỷ Niệm Mới
            </h3>
            <button @click="showUploadModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
          </div>

          <div class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Chọn Album Phân Loại *</label>
              <select v-model="uploadCategory" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-hidden">
                <option value="pre_wedding">Bộ Ảnh Pre-Wedding</option>
                <option value="engagement">Lễ Ăn Hỏi & Gia Tiên</option>
                <option value="wedding_day">Tiệc Cưới Chính</option>
                <option value="honeymoon">Hành Trình Trăng Mật</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Tiêu Đề Album / Ảnh</label>
              <input v-model="uploadTitle" type="text" placeholder="VD: Ảnh Cổng Ngoại Cảnh Đà Lạt..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Mô Tả Kỷ Niệm</label>
              <textarea v-model="uploadDescription" rows="2" placeholder="Ghi chú thêm khoảnh khắc kỷ niệm..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">Chọn Tập Tin Ảnh (Hỗ trợ tải nhiều ảnh) *</label>
              <input type="file" multiple accept="image/*" @change="handleFileSelect" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-900 hover:file:bg-rose-100 cursor-pointer" />
              <span v-if="selectedFiles.length > 0" class="text-[11px] text-emerald-700 font-bold block mt-1">Đã chọn {{ selectedFiles.length }} tập tin ảnh</span>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <button @click="showUploadModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">Hủy Bỏ</button>
            <button @click="handleUpload" :disabled="isUploading || selectedFiles.length === 0" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 cursor-pointer disabled:opacity-50">
              {{ isUploading ? 'Đang Tải Lên...' : 'Tải Lên Gallery' }}
            </button>
          </div>
        </div>
      </div>

      <!-- QR Code Share Modal -->
      <div v-if="showQrModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white p-6 rounded-3xl border border-rose-100 shadow-2xl text-center space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              <QrCode class="w-5 h-5 text-rose-600" /> Mã QR Gallery Cho Bàn Tiệc
            </h3>
            <button @click="showQrModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
          </div>

          <div class="space-y-3 text-xs">
            <p class="text-slate-600 font-medium">In hoặc lưu mã QR này đặt trên bàn tiệc cưới để khách mời dễ dàng quét mã xem và tải ảnh chụp cùng Dâu Rể!</p>
            <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 inline-block">
              <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(shareUrl)}`" alt="Mã QR Gallery" class="w-48 h-48 mx-auto" />
            </div>
            <span class="block text-[11px] font-mono text-slate-500 truncate">{{ shareUrl }}</span>
          </div>

          <button @click="copyShareUrl" class="w-full py-2.5 rounded-xl bg-rose-600 text-white text-xs font-bold shadow-md hover:bg-rose-500 transition">
            Sao Chép Đường Dẫn Gallery
          </button>
        </div>
      </div>

      <!-- Lightbox Image Preview Modal -->
      <div v-if="activeLightboxImage" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4" @click="activeLightboxImage = null">
        <div class="max-w-4xl w-full max-h-screen p-4 flex flex-col items-center gap-4" @click.stop>
          <img :src="activeLightboxImage.image_url" class="max-h-[75vh] w-auto object-contain rounded-2xl shadow-2xl border border-white/20" />
          <div class="text-center text-white space-y-1">
            <h3 class="font-serif text-lg font-bold">{{ activeLightboxImage.title || 'Ảnh Kỷ Niệm Tiệc Cưới' }}</h3>
            <p class="text-xs text-rose-200 font-medium">{{ activeLightboxImage.uploader_name }} • {{ getCategoryLabel(activeLightboxImage.category) }}</p>
            <p v-if="activeLightboxImage.description" class="text-xs text-slate-300 max-w-md mx-auto">{{ activeLightboxImage.description }}</p>
          </div>
          <button @click="activeLightboxImage = null" class="px-6 py-2 rounded-full bg-white/20 hover:bg-white/30 text-white font-bold text-xs transition cursor-pointer">
            Đóng Lại
          </button>
        </div>
      </div>

    </main>
  </WorkspaceLayout>
</template>
