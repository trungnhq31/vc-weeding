<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    Check, 
    ChevronRight, 
    ChevronDown,
    X, 
    ArrowLeft,
    Sliders,
    Camera,
    Paperclip,
    Save,
    Trash2,
    Plus,
    FileText,
    Store,
    DollarSign,
    Image as ImageIcon
} from 'lucide-vue-next';
import { ref } from 'vue';

interface Task {
    id: string;
    title: string;
    notes?: string;
    vendor_info?: string;
    attachments?: string[];
    is_completed: boolean;
    due_date?: string;
    estimated_cost?: number;
    actual_cost?: number;
}

interface Milestone {
    id: string;
    title: string;
    slug: string;
    timeframe: string;
    icon: string;
    order: number;
    status: 'completed' | 'in_progress' | 'pending';
    summary: string;
    notes?: string;
    attachments?: string[];
    budget_allocated: number;
    budget_spent: number;
    progress_percentage: number;
    tasks: Task[];
}

interface Stats {
    overallProgress: number;
    totalTasks: number;
    completedTasks: number;
    totalBudgetAllocated: number;
    totalBudgetSpent: number;
}

const props = defineProps<{
    milestones: Milestone[];
    stats: Stats;
}>();

const selectedMilestone = ref<Milestone | null>(null);
const expandedTaskId = ref<string | null>(null);
const savingTaskId = ref<string | null>(null);
const uploadingTaskId = ref<string | null>(null);

// Active modal preview for images
const previewImageUrl = ref<string | null>(null);

const openDetail = (milestone: Milestone) => {
    selectedMilestone.value = milestone;
};

const closeDetail = () => {
    selectedMilestone.value = null;
    expandedTaskId.value = null;
};

const toggleExpandTask = (taskId: string) => {
    if (expandedTaskId.value === taskId) {
        expandedTaskId.value = null;
    } else {
        expandedTaskId.value = taskId;
    }
};

const formatCurrency = (val: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
};

const toggleTask = async (task: Task) => {
    task.is_completed = !task.is_completed;
    try {
        const response = await fetch(`/wedding/tasks/${task.id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
        });
        const data = await response.json();
        if (selectedMilestone.value) {
            selectedMilestone.value.progress_percentage = data.milestoneProgress;
        }
    } catch (e) {
        console.error('Error toggling task:', e);
    }
};

const saveTaskDetails = async (task: Task) => {
    savingTaskId.value = task.id;
    try {
        const response = await fetch(`/wedding/tasks/${task.id}/details`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                notes: task.notes,
                vendor_info: task.vendor_info,
                actual_cost: task.actual_cost,
            }),
        });
        const data = await response.json();
        if (data.success && selectedMilestone.value) {
            selectedMilestone.value.budget_spent = data.milestoneSpent;
        }
    } catch (e) {
        console.error('Error saving task details:', e);
    } finally {
        savingTaskId.value = null;
    }
};

const handleFileUpload = async (event: Event, task: Task) => {
    const target = event.target as HTMLInputElement;
    if (!target.files || target.files.length === 0) return;

    const file = target.files[0];
    const formData = new FormData();
    formData.append('image', file);

    uploadingTaskId.value = task.id;

    try {
        const response = await fetch(`/wedding/tasks/${task.id}/upload-image`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: formData,
        });

        const data = await response.json();
        if (data.success) {
            task.attachments = data.attachments;
        }
    } catch (e) {
        console.error('Error uploading image:', e);
    } finally {
        uploadingTaskId.value = null;
        target.value = '';
    }
};

const deleteImage = async (task: Task, imageUrl: string) => {
    try {
        const response = await fetch(`/wedding/tasks/${task.id}/delete-image`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({ url: imageUrl }),
        });

        const data = await response.json();
        if (data.success) {
            task.attachments = data.attachments;
        }
    } catch (e) {
        console.error('Error deleting image:', e);
    }
};
</script>

<template>
    <Head title="Lộ Trình Chuẩn Bị Cưới (Tối Giản & Lưu Trữ Ảnh)" />

    <!-- Minimalist Clean Neutral Layout -->
    <div class="min-h-screen bg-slate-50 text-slate-900 font-sans pb-20 antialiased">
        <!-- Minimalist Header -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
            <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="/images/logo/eloria-logo-icon.jpg" alt="Eloria Logo" class="h-8 w-auto rounded-lg shadow-xs border border-slate-200" />
                    <Link href="/wedding" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1">
                        <ArrowLeft class="w-3.5 h-3.5" /> Thiệp Cưới
                    </Link>
                    <span class="text-slate-300">/</span>
                    <h1 class="text-sm font-semibold text-slate-800">Eloria Wedding OS • Kế Hoạch Cưới</h1>
                </div>

                <div class="flex items-center gap-4 text-xs">
                    <a href="/admin" class="text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-1 font-medium">
                        <Sliders class="w-3.5 h-3.5 text-slate-400" /> Admin Panel
                    </a>
                </div>
            </div>
        </header>

        <!-- Minimalist Overview Section -->
        <div class="max-w-5xl mx-auto px-6 pt-10 pb-8">
            <div class="mb-8">
                <span class="text-xs font-semibold text-rose-600 uppercase tracking-widest">Kế hoạch 5 tháng (15/07 - 19/12/2026)</span>
                <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-1">Lộ Trình Chuẩn Bị Cưới</h2>
                <p class="text-slate-500 text-xs md:text-sm mt-1">Sảnh tiệc Asiana Plaza • Quy mô 25 bàn tiệc (~250 khách)</p>
            </div>

            <!-- Minimalist Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Overall Progress -->
                <div class="p-5 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-slate-500">Tiến độ tổng thể</span>
                        <span class="text-xl font-bold text-slate-900">{{ stats.overallProgress }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-rose-500 h-1.5 rounded-full transition-all duration-300" :style="{ width: `${stats.overallProgress}%` }"></div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">
                        Hoàn thành {{ stats.completedTasks }}/{{ stats.totalTasks }} mục công việc
                    </div>
                </div>

                <!-- Budget Allocated -->
                <div class="p-5 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-medium text-slate-500 block mb-1">Ngân sách trần</span>
                    <div class="text-xl font-bold text-slate-900">{{ formatCurrency(stats.totalBudgetAllocated) }}</div>
                    <div class="mt-1 text-xs text-slate-400">Trọn gói tiệc & gia tiên</div>
                </div>

                <!-- Budget Spent -->
                <div class="p-5 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-medium text-slate-500 block mb-1">Chi phí đã dùng</span>
                    <div class="text-xl font-bold text-emerald-700">{{ formatCurrency(stats.totalBudgetSpent) }}</div>
                    <div class="mt-1 text-xs text-slate-400">Đã chốt cọc nhà hàng & pre-wedding</div>
                </div>
            </div>
        </div>

        <!-- Minimalist Timeline Stream -->
        <main class="max-w-5xl mx-auto px-6 py-4">
            <div class="relative border-l border-slate-200 ml-3 md:ml-32 space-y-6">
                <div 
                    v-for="milestone in milestones" 
                    :key="milestone.id" 
                    @click="openDetail(milestone)"
                    class="relative pl-6 md:pl-8 group cursor-pointer"
                >
                    <!-- Timeframe Badge (Left side on desktop) -->
                    <div class="hidden md:block absolute -left-36 top-3 text-right w-28 text-xs font-medium text-slate-400">
                        {{ milestone.timeframe }}
                    </div>

                    <!-- Timeline Point Dot -->
                    <div 
                        class="absolute -left-[13px] top-3.5 w-6 h-6 rounded-full border bg-white flex items-center justify-center transition-transform group-hover:scale-110 shadow-xs"
                        :class="{
                            'border-emerald-500 text-emerald-600 bg-emerald-50': milestone.status === 'completed',
                            'border-rose-500 text-rose-600 bg-rose-50': milestone.status === 'in_progress',
                            'border-slate-300 text-slate-400': milestone.status === 'pending'
                        }"
                    >
                        <Check v-if="milestone.status === 'completed'" class="w-3 h-3 stroke-[3]" />
                        <span v-else class="w-1.5 h-1.5 rounded-full" :class="milestone.status === 'in_progress' ? 'bg-rose-500' : 'bg-slate-300'"></span>
                    </div>

                    <!-- Minimalist Card -->
                    <div class="p-5 rounded-xl bg-white border border-slate-200/80 shadow-xs group-hover:border-slate-300 group-hover:shadow-sm transition-all">
                        <div class="flex items-center justify-between gap-4 mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="md:hidden text-[11px] font-medium text-slate-400">
                                    {{ milestone.timeframe }}
                                </span>
                                <span 
                                    class="text-[11px] font-medium px-2 py-0.5 rounded"
                                    :class="{
                                        'bg-emerald-50 text-emerald-700': milestone.status === 'completed',
                                        'bg-rose-50 text-rose-700': milestone.status === 'in_progress',
                                        'bg-slate-100 text-slate-500': milestone.status === 'pending'
                                    }"
                                >
                                    {{ milestone.status === 'completed' ? 'Hoàn thành' : (milestone.status === 'in_progress' ? 'Đang thực hiện' : 'Chờ chuẩn bị') }}
                                </span>
                            </div>
                            <span class="text-xs font-medium text-slate-400 group-hover:text-slate-700 flex items-center gap-0.5 transition-colors">
                                Chi tiết & Lưu trữ ảnh <ChevronRight class="w-3.5 h-3.5" />
                            </span>
                        </div>

                        <h3 class="text-base font-semibold text-slate-900 group-hover:text-rose-700 transition-colors mb-1">
                            {{ milestone.title }}
                        </h3>

                        <p class="text-slate-500 text-xs leading-relaxed mb-3 line-clamp-2">
                            {{ milestone.summary }}
                        </p>

                        <!-- Progress Bar & Budget -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <div class="flex items-center gap-2 flex-1 max-w-[200px]">
                                <div class="flex-1 bg-slate-100 rounded-full h-1 overflow-hidden">
                                    <div class="bg-slate-700 h-1 rounded-full" :style="{ width: `${milestone.progress_percentage}%` }"></div>
                                </div>
                                <span class="font-medium text-slate-700 text-[11px]">{{ milestone.progress_percentage }}%</span>
                            </div>

                            <div class="text-[11px]">
                                Ngân sách: <span class="font-semibold text-slate-800">{{ formatCurrency(Number(milestone.budget_allocated)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Minimalist Drawer Modal with Upload & Note Features -->
        <div v-if="selectedMilestone" class="fixed inset-0 z-50 overflow-hidden bg-slate-900/30 backdrop-blur-xs flex justify-end">
            <div class="w-full max-w-xl bg-white border-l border-slate-200 h-full flex flex-col shadow-2xl overflow-y-auto animate-in slide-in-from-right duration-200">
                <!-- Drawer Header -->
                <div class="p-5 border-b border-slate-100 bg-white sticky top-0 flex items-center justify-between z-10">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ selectedMilestone.timeframe }}</span>
                        <h2 class="text-lg font-semibold text-slate-900">{{ selectedMilestone.title }}</h2>
                    </div>
                    <button @click="closeDetail" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                        <X class="w-4 h-4" />
                    </button>
                </div>

                <!-- Drawer Content -->
                <div class="p-5 space-y-5 flex-1">
                    <div class="p-4 rounded-lg bg-slate-50 border border-slate-200/60 text-xs">
                        <p class="text-slate-600 leading-relaxed mb-3">{{ selectedMilestone.summary }}</p>
                        <div class="flex justify-between pt-2 border-t border-slate-200/60">
                            <span>Ngân sách dự kiến: <strong class="text-slate-800">{{ formatCurrency(Number(selectedMilestone.budget_allocated)) }}</strong></span>
                            <span>Đã chi: <strong class="text-emerald-700">{{ formatCurrency(Number(selectedMilestone.budget_spent)) }}</strong></span>
                        </div>
                    </div>

                    <!-- Checklist Tasks with Expandable Note & Attachment Editor -->
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 mb-3 flex justify-between items-center">
                            <span>Danh sách công việc & Lưu giữ kỷ niệm</span>
                            <span class="text-xs text-slate-500 font-normal">Tiến độ: {{ selectedMilestone.progress_percentage }}%</span>
                        </h3>

                        <div class="space-y-3">
                            <div 
                                v-for="task in selectedMilestone.tasks" 
                                :key="task.id" 
                                class="rounded-lg border bg-white border-slate-200/80 shadow-xs transition-all overflow-hidden"
                            >
                                <!-- Task Row Bar -->
                                <div class="p-3 flex items-start justify-between gap-3 bg-white hover:bg-slate-50/50 transition-colors">
                                    <div class="flex items-start gap-2.5 flex-1 cursor-pointer" @click="toggleTask(task)">
                                        <div 
                                            class="w-4 h-4 rounded border mt-0.5 flex items-center justify-center transition-colors shadow-xs"
                                            :class="task.is_completed ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-300 bg-white'"
                                        >
                                            <Check v-if="task.is_completed" class="w-3 h-3 stroke-[3]" />
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900" :class="{ 'line-through text-slate-400': task.is_completed }">
                                                {{ task.title }}
                                            </div>
                                            <div v-if="task.vendor_info" class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                                <Store class="w-3 h-3 text-slate-400" /> {{ task.vendor_info }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- Attachments Counter Badge -->
                                        <span v-if="task.attachments && task.attachments.length > 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-medium">
                                            <ImageIcon class="w-3 h-3" /> {{ task.attachments.length }} ảnh
                                        </span>

                                        <!-- Expand Toggle Button -->
                                        <button 
                                            @click="toggleExpandTask(task.id)"
                                            class="p-1 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                                            title="Chi tiết & Upload ảnh"
                                        >
                                            <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expandedTaskId === task.id }" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Expanded Task Details Form (Notes, Vendor, Actual Cost & Image Gallery) -->
                                <div v-if="expandedTaskId === task.id" class="p-4 border-t border-slate-100 bg-slate-50/60 space-y-4 text-xs">
                                    <!-- Inputs: Vendor Info & Actual Cost -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-medium text-slate-500 mb-1">Nhà cung cấp / Đơn vị</label>
                                            <input 
                                                v-model="task.vendor_info" 
                                                type="text" 
                                                placeholder="VD: Asiana Plaza, Studio..." 
                                                class="w-full px-2.5 py-1.5 rounded border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-slate-400 text-xs"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-medium text-slate-500 mb-1">Chi phí thực tế (VNĐ)</label>
                                            <input 
                                                v-model.number="task.actual_cost" 
                                                type="number" 
                                                placeholder="0" 
                                                class="w-full px-2.5 py-1.5 rounded border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-slate-400 text-xs font-semibold"
                                            />
                                        </div>
                                    </div>

                                    <!-- Input: Notes & Journal Entry -->
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-500 mb-1">Ghi chú & Thông tin lưu giữ</label>
                                        <textarea 
                                            v-model="task.notes" 
                                            rows="2" 
                                            placeholder="Ghi chú thêm thông tin hợp đồng, địa chỉ, lưu giữ kỷ niệm..." 
                                            class="w-full px-2.5 py-1.5 rounded border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-slate-400 text-xs"
                                        ></textarea>
                                    </div>

                                    <!-- Save Button for Notes & Details -->
                                    <div class="flex justify-end">
                                        <button 
                                            @click="saveTaskDetails(task)" 
                                            :disabled="savingTaskId === task.id"
                                            class="px-3 py-1.5 rounded bg-slate-900 hover:bg-slate-800 text-white font-medium text-[11px] flex items-center gap-1 shadow-xs transition-colors cursor-pointer"
                                        >
                                            <Save class="w-3 h-3" /> {{ savingTaskId === task.id ? 'Đang lưu...' : 'Lưu Ghi Chú & Chi Phí' }}
                                        </button>
                                    </div>

                                    <!-- Image Attachments Gallery & Upload Section -->
                                    <div class="pt-3 border-t border-slate-200/60">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-slate-700 text-[11px] flex items-center gap-1">
                                                <Camera class="w-3.5 h-3.5 text-slate-500" /> Hình ảnh & Chứng từ lưu giữ
                                            </span>
                                            
                                            <!-- Image Upload Button -->
                                            <label class="px-2.5 py-1 rounded bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium text-[11px] flex items-center gap-1 cursor-pointer transition-colors">
                                                <Plus class="w-3 h-3" /> Tải ảnh lên
                                                <input type="file" accept="image/*" class="hidden" @change="(e) => handleFileUpload(e, task)" />
                                            </label>
                                        </div>

                                        <div v-if="uploadingTaskId === task.id" class="text-slate-500 text-[11px] italic mb-2">
                                            Đang tải ảnh lên...
                                        </div>

                                        <!-- Thumbnails Grid -->
                                        <div v-if="task.attachments && task.attachments.length > 0" class="grid grid-cols-4 gap-2 mt-2">
                                            <div v-for="(imgUrl, idx) in task.attachments" :key="idx" class="relative group aspect-square rounded border border-slate-200 overflow-hidden bg-slate-100">
                                                <img :src="imgUrl" alt="Attachment" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity" @click="previewImageUrl = imgUrl" />
                                                <button 
                                                    @click="deleteImage(task, imgUrl)"
                                                    class="absolute top-1 right-1 p-1 bg-red-600/80 hover:bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity"
                                                    title="Xóa ảnh"
                                                >
                                                    <Trash2 class="w-3 h-3" />
                                                </button>
                                            </div>
                                        </div>
                                        <div v-else class="text-slate-400 text-[11px] italic">
                                            Chưa có hình ảnh nào được lưu giữ cho mục này.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Lightbox Modal Preview -->
        <div v-if="previewImageUrl" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click="previewImageUrl = null">
            <div class="relative max-w-3xl max-h-[85vh] bg-white rounded-lg p-2 overflow-hidden shadow-2xl" @click.stop>
                <button @click="previewImageUrl = null" class="absolute top-3 right-3 p-1.5 rounded-full bg-slate-900/70 text-white hover:bg-slate-900 transition-colors z-10">
                    <X class="w-4 h-4" />
                </button>
                <img :src="previewImageUrl" alt="Full Preview" class="max-w-full max-h-[80vh] rounded object-contain" />
            </div>
        </div>
    </div>
</template>
