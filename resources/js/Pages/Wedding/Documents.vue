<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { 
  FileText, 
  Upload, 
  Download, 
  Eye, 
  Trash2, 
  Plus, 
  Search, 
  CheckCircle2, 
  FileCheck, 
  ShieldCheck, 
  Building2,
  Calendar,
  DollarSign
} from 'lucide-vue-next';

interface Document {
  id: string;
  title: string;
  category: string;
  vendorName: string;
  amount: number;
  signedDate: string;
  fileSize: string;
  fileType: string;
}

const searchQuery = ref('');
const selectedCategory = ref('all');

const documents = ref<Document[]>([
  {
    id: 'doc-1',
    title: 'Hợp Đồng Thêu Trang Trí Tiệc Cưới',
    category: 'decor',
    vendorName: 'L’Amour Wedding Decor',
    amount: 65000000,
    signedDate: '2026-05-10',
    fileSize: '2.4 MB',
    fileType: 'PDF'
  },
  {
    id: 'doc-2',
    title: 'Hợp Đồng Thuê Sảnh Tiệc & Thực Đơn',
    category: 'venue',
    vendorName: 'Trung Tâm Tiệc Cưới',
    amount: 120000000,
    signedDate: '2026-04-15',
    fileSize: '4.1 MB',
    fileType: 'PDF'
  },
  {
    id: 'doc-3',
    title: 'Gói Phim Phóng Sự & Ảnh Pre-wedding',
    category: 'studio',
    vendorName: 'Studio Chụp Ảnh Cưới',
    amount: 35000000,
    signedDate: '2026-06-01',
    fileSize: '1.8 MB',
    fileType: 'PDF'
  }
]);

const showUploadModal = ref(false);
const newDocTitle = ref('');
const newVendorName = ref('');
const newCategory = ref('decor');
const newAmount = ref<number | null>(null);

const addDocument = () => {
  if (!newDocTitle.value) return;
  documents.value.push({
    id: `doc-${Date.now()}`,
    title: newDocTitle.value,
    category: newCategory.value,
    vendorName: newVendorName.value || 'Chưa xác định',
    amount: newAmount.value || 0,
    signedDate: new Date().toISOString().split('T')[0],
    fileSize: '1.2 MB',
    fileType: 'PDF'
  });
  newDocTitle.value = '';
  newVendorName.value = '';
  newAmount.value = null;
  showUploadModal.value = false;
};

const formatCurrency = (val: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
};
</script>

<template>
  <WorkspaceLayout>
    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-6 py-8">
      <!-- Section Header -->
      <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h2 class="text-2xl font-serif font-bold text-slate-900 flex items-center gap-2">
            <FileCheck class="w-6 h-6 text-rose-600" />
            Kho Tài Liệu & Hợp Đồng Ký Kết
          </h2>
          <p class="text-xs text-slate-600 mt-1">Lưu trữ tập trung báo giá, phụ lục & chứng từ thanh toán đợt cọc của các nhà cung cấp.</p>
        </div>

        <!-- Metric Summary Pills -->
        <div class="flex items-center gap-3">
          <div class="px-4 py-2 rounded-xl bg-white border border-rose-200/80 shadow-2xs text-xs">
            <span class="text-slate-500">Tổng tài liệu:</span> <strong class="text-slate-900 font-bold">{{ documents.length }} Hợp đồng</strong>
          </div>
        </div>
      </div>

      <!-- Filter & Search Bar -->
      <div class="p-4 rounded-2xl bg-white border border-rose-100 shadow-2xs mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="relative w-full md:w-80">
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Tìm tên hợp đồng, nhà cung cấp..." 
            class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-rose-300"
          />
        </div>

        <div class="flex items-center gap-2 text-xs">
          <span class="text-slate-500 font-medium">Danh mục:</span>
          <button @click="selectedCategory = 'all'" class="px-3 py-1.5 rounded-lg border transition cursor-pointer" :class="selectedCategory === 'all' ? 'bg-rose-50 border-rose-300 font-bold text-rose-900' : 'bg-slate-50 border-slate-200 text-slate-600'">Tất cả</button>
          <button @click="selectedCategory = 'venue'" class="px-3 py-1.5 rounded-lg border transition cursor-pointer" :class="selectedCategory === 'venue' ? 'bg-rose-50 border-rose-300 font-bold text-rose-900' : 'bg-slate-50 border-slate-200 text-slate-600'">Địa điểm</button>
          <button @click="selectedCategory = 'decor'" class="px-3 py-1.5 rounded-lg border transition cursor-pointer" :class="selectedCategory === 'decor' ? 'bg-rose-50 border-rose-300 font-bold text-rose-900' : 'bg-slate-50 border-slate-200 text-slate-600'">Trang trí</button>
          <button @click="selectedCategory = 'studio'" class="px-3 py-1.5 rounded-lg border transition cursor-pointer" :class="selectedCategory === 'studio' ? 'bg-rose-50 border-rose-300 font-bold text-rose-900' : 'bg-slate-50 border-slate-200 text-slate-600'">Studio/Chụp ảnh</button>
        </div>
      </div>

      <!-- Document Cards Grid -->
      <div class="grid md:grid-cols-3 gap-6">
        <div v-for="doc in documents" :key="doc.id" class="p-6 rounded-2xl bg-white border border-rose-100 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between group">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-1 rounded-md bg-rose-50 text-rose-800 text-[10px] font-bold uppercase tracking-wider border border-rose-200/80">
                {{ doc.fileType }} • {{ doc.fileSize }}
              </span>
              <span class="text-[11px] text-slate-400 font-medium flex items-center gap-1">
                <Calendar class="w-3.5 h-3.5 text-slate-400" /> Ký: {{ doc.signedDate }}
              </span>
            </div>

            <h3 class="text-base font-serif font-bold text-slate-900 mb-1 group-hover:text-rose-700 transition-colors">
              {{ doc.title }}
            </h3>

            <div class="flex items-center gap-1.5 text-xs text-slate-600 mb-4">
              <Building2 class="w-3.5 h-3.5 text-slate-400" />
              <span>{{ doc.vendorName }}</span>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-800">
              <span class="text-slate-500 font-normal">Giá trị hợp đồng:</span>
              <span class="text-rose-900 font-bold">{{ formatCurrency(doc.amount) }}</span>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
            <button class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-medium transition flex items-center gap-1 cursor-pointer">
              <Eye class="w-3.5 h-3.5" /> Xem trước
            </button>
            <button class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-900 hover:bg-rose-100 text-xs font-semibold transition flex items-center gap-1 cursor-pointer">
              <Download class="w-3.5 h-3.5" /> Tải file
            </button>
          </div>
        </div>
      </div>
    </main>

    <!-- Upload Document Modal -->
    <div v-if="showUploadModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl p-6 max-w-md w-full border border-rose-100 shadow-xl">
        <h3 class="text-lg font-serif font-bold text-slate-900 mb-4 flex items-center gap-2">
          <Upload class="w-5 h-5 text-rose-600" /> Tải Lên Hợp Đồng Mới
        </h3>

        <div class="space-y-4 text-xs">
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Tên hợp đồng</label>
            <input v-model="newDocTitle" type="text" placeholder="VD: Hợp đồng trang trí tiệc cưới" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-hidden focus:border-rose-300" />
          </div>

          <div>
            <label class="font-semibold text-slate-700 block mb-1">Tên nhà cung cấp (Vendor)</label>
            <input v-model="newVendorName" type="text" placeholder="VD: L'Amour Wedding" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-hidden focus:border-rose-300" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="font-semibold text-slate-700 block mb-1">Hạng mục</label>
              <select v-model="newCategory" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-hidden focus:border-rose-300">
                <option value="decor">Trang trí</option>
                <option value="venue">Địa điểm</option>
                <option value="studio">Studio/Phim ảnh</option>
                <option value="catering">Catering/Cỗ cưới</option>
              </select>
            </div>
            <div>
              <label class="font-semibold text-slate-700 block mb-1">Giá trị (VND)</label>
              <input v-model.number="newAmount" type="number" placeholder="50000000" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-hidden focus:border-rose-300" />
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button @click="showUploadModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold cursor-pointer">Hủy</button>
          <button @click="addDocument" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold shadow-md cursor-pointer">Lưu Hợp Đồng</button>
        </div>
      </div>
    </div>
  </WorkspaceLayout>
</template>
