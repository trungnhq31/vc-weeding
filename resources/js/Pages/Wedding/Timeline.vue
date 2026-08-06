<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { 
    Check, 
    ChevronRight, 
    ChevronDown,
    ChevronUp,
    X, 
    Plus, 
    AlertTriangle, 
    Sparkles, 
    Calendar, 
    Search, 
    Clock, 
    MapPin, 
    Users, 
    CheckCircle2, 
    ListTodo, 
    Target, 
    ArrowRight,
    HelpCircle,
    Flame,
    Zap,
    Bookmark,
    CircleDot,
    Filter,
    Coins,
    DollarSign,
    Building2,
    FileText,
    Store,
    Save,
    ExternalLink,
    Heart
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import GroundedAiDrawer from '@/Components/Wedding/GroundedAiDrawer.vue';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';

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
    priority?: 'urgent' | 'high' | 'medium' | 'low';
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

interface WorkspaceInfo {
    name?: string;
    groom_name?: string;
    bride_name?: string;
    wedding_date?: string;
    wedding_location?: string;
    budget_cap?: number;
    estimated_guests?: number;
    venue_name?: string;
    ceremony_type?: string;
    wedding_vibe?: string;
}

interface RemediationItem {
    task_id?: string | null;
    task_title: string;
    severity: string;
    advice: string;
    action_label: string;
}

const props = defineProps<{
    milestones: Milestone[];
    stats: Stats;
    workspace?: WorkspaceInfo;
    remediationSuggestions?: RemediationItem[];
    overdueCount?: number;
}>();

const isAiDrawerOpen = ref(false);
const selectedMilestone = ref<Milestone | null>(null);

// Collapsible State Map for Milestone Cards
const collapsedState = ref<Record<string, boolean>>({});

const toggleMilestoneCollapse = (milestoneId: string) => {
    collapsedState.value[milestoneId] = !collapsedState.value[milestoneId];
};

const isMilestoneCollapsed = (milestoneId: string, index: number) => {
    if (collapsedState.value[milestoneId] !== undefined) {
        return collapsedState.value[milestoneId];
    }
    return index > 0;
};

// Right Drawer for Selected Task Detail
const selectedTaskDetail = ref<{
    task: Task;
    milestoneTitle?: string;
    timeframe?: string;
} | null>(null);

const isSavingTask = ref(false);
const saveSuccessMsg = ref(false);

const showAddTaskModal = ref(false);
const newTaskTitle = ref('');
const selectedMilestoneForAdd = ref<Milestone | null>(null);
const newTaskEstimatedCost = ref<number | null>(null);
const newTaskPriority = ref<'urgent' | 'high' | 'medium' | 'low'>('high');

// Filters
const activeViewTab = ref<'roadmap' | 'today'>('roadmap');
const taskSearchQuery = ref('');
const selectedPriorityFilter = ref<string>('all');

const openMilestoneModal = (milestone: Milestone) => {
    selectedMilestone.value = milestone;
};

const openTaskDetail = (task: Task, milestoneTitle?: string, timeframe?: string) => {
    selectedTaskDetail.value = {
        task: { ...task },
        milestoneTitle: milestoneTitle || 'Kế hoạch chung',
        timeframe: timeframe || 'Đang diễn ra'
    };
};

const closeTaskDetail = () => {
    selectedTaskDetail.value = null;
    saveSuccessMsg.value = false;
};

const closeMilestoneDetail = () => {
    selectedMilestone.value = null;
};

const formatCurrency = (val: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
};

const toggleTask = async (task: Task) => {
    task.is_completed = !task.is_completed;
    if (selectedTaskDetail.value && selectedTaskDetail.value.task.id === task.id) {
        selectedTaskDetail.value.task.is_completed = task.is_completed;
    }
    try {
        const response = await fetch(`/wedding/tasks/${task.id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
            }
        });
        const data = await response.json();
        if (data.success && selectedMilestone.value) {
            selectedMilestone.value.progress_percentage = data.milestoneProgress;
        }
    } catch (e) {
        console.error('Error toggling task:', e);
    }
};

const saveTaskDetails = async () => {
    if (!selectedTaskDetail.value) return;
    isSavingTask.value = true;
    const task = selectedTaskDetail.value.task;

    try {
        const response = await fetch(`/wedding/tasks/${task.id}/details`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
            },
            body: JSON.stringify({
                notes: task.notes,
                vendor_info: task.vendor_info,
                actual_cost: task.actual_cost,
            })
        });
        const data = await response.json();
        if (data.success) {
            props.milestones.forEach(m => {
                const target = m.tasks.find(t => t.id === task.id);
                if (target) {
                    target.notes = task.notes;
                    target.vendor_info = task.vendor_info;
                    target.actual_cost = task.actual_cost;
                }
            });
            saveSuccessMsg.value = true;
            setTimeout(() => { saveSuccessMsg.value = false; }, 3000);
        }
    } catch (e) {
        console.error('Error saving task details:', e);
    } finally {
        isSavingTask.value = false;
    }
};

const handleAddTask = () => {
    if (!newTaskTitle.value || !selectedMilestoneForAdd.value) return;
    const newTask: Task = {
        id: `task-${Date.now()}`,
        title: newTaskTitle.value,
        priority: newTaskPriority.value,
        is_completed: false,
        estimated_cost: newTaskEstimatedCost.value || 0,
        actual_cost: 0,
        notes: 'Công việc mới khởi tạo'
    };
    selectedMilestoneForAdd.value.tasks.push(newTask);
    newTaskTitle.value = '';
    newTaskEstimatedCost.value = null;
    newTaskPriority.value = 'high';
    showAddTaskModal.value = false;
};

// Priority Helpers
const getPriorityBadgeClass = (priority?: string) => {
    switch (priority) {
        case 'urgent':
            return 'bg-rose-100 text-rose-800 border-rose-200';
        case 'high':
            return 'bg-amber-100 text-amber-900 border-amber-200';
        case 'medium':
            return 'bg-blue-100 text-blue-800 border-blue-200';
        case 'low':
            return 'bg-slate-100 text-slate-700 border-slate-200';
        default:
            return 'bg-amber-100 text-amber-900 border-amber-200';
    }
};

const getPriorityLabelText = (priority?: string) => {
    switch (priority) {
        case 'urgent':
            return 'Khẩn Cấp';
        case 'high':
            return 'Ưu Tiên Cao';
        case 'medium':
            return 'Trung Bình';
        case 'low':
            return 'Thấp';
        default:
            return 'Ưu Tiên Cao';
    }
};

// Days Until Wedding
const daysUntilWedding = computed(() => {
    if (!props.workspace?.wedding_date) return 78;
    const wedding = new Date(props.workspace.wedding_date).getTime();
    const today = new Date().getTime();
    const diff = Math.ceil((wedding - today) / (1000 * 3600 * 24));
    return diff > 0 ? diff : 0;
});

// Focus Today Tasks
const focusTodayTasks = computed(() => {
    const allPending: { task: Task; milestoneTitle: string; timeframe: string }[] = [];
    props.milestones.forEach(m => {
        (m.tasks || []).forEach(t => {
            if (!t.is_completed) {
                allPending.push({ task: t, milestoneTitle: m.title, timeframe: m.timeframe });
            }
        });
    });
    allPending.sort((a, b) => {
        const rank = { urgent: 1, high: 2, medium: 3, low: 4 };
        return (rank[a.task.priority || 'high'] || 2) - (rank[b.task.priority || 'high'] || 2);
    });
    return allPending.slice(0, 4);
});

// Filtered Milestones
const filteredMilestones = computed(() => {
    return props.milestones.map(m => {
        let tasks = m.tasks || [];
        
        if (selectedPriorityFilter.value !== 'all') {
            tasks = tasks.filter(t => (t.priority || 'high') === selectedPriorityFilter.value);
        }

        if (taskSearchQuery.value.trim()) {
            const q = taskSearchQuery.value.toLowerCase().trim();
            tasks = tasks.filter(t => t.title.toLowerCase().includes(q));
        }

        return {
            ...m,
            tasks,
            completedCount: tasks.filter(t => t.is_completed).length,
            totalCount: tasks.length,
            progress_percentage: m.progress_percentage || 0
        };
    });
});
</script>

<template>
    <Head title="Lộ Trình & Mức Ưu Tiên — Eloria OS" />

    <WorkspaceLayout title="Lộ Trình & Mức Ưu Tiên" active-nav="timeline">
        <main class="max-w-5xl mx-auto px-6 py-8 space-y-8">
            
            <!-- 1. Header with Clearly Separated Groom & Bride Name Badges -->
            <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-xl shadow-rose-900/5 space-y-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 border-b border-slate-100 pb-6">
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-900 text-[11px] font-extrabold uppercase tracking-wider">
                            <Target class="w-3.5 h-3.5 text-rose-600" /> BẢN ĐỒ LỘ TRÌNH KÈM MỨC ƯU TIÊN
                        </div>

                        <!-- Groom & Bride Separated Badges Title -->
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900">Lộ Trình Đám Cưới</h1>
                            
                            <div class="inline-flex items-center gap-2 p-1.5 px-3 rounded-2xl bg-gradient-to-r from-rose-50 to-pink-50 border border-rose-200/80 shadow-2xs">
                                <div class="px-2.5 py-1 rounded-xl bg-rose-600 text-white font-extrabold text-xs flex items-center gap-1.5 shadow-2xs">
                                    <span>🤵 Chú Rể:</span>
                                    <strong class="font-black">{{ workspace?.groom_name || 'Nguyễn Hoàng Quốc Trung' }}</strong>
                                </div>
                                
                                <Heart class="w-4 h-4 text-rose-500 fill-rose-500 animate-pulse shrink-0" />
                                
                                <div class="px-2.5 py-1 rounded-xl bg-pink-600 text-white font-extrabold text-xs flex items-center gap-1.5 shadow-2xs">
                                    <span>👰 Cô Dâu:</span>
                                    <strong class="font-black">{{ workspace?.bride_name || 'Lê Thị Hồng Vân' }}</strong>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs md:text-sm text-slate-600 font-medium">
                            Phân chia tự động mức độ ưu tiên công việc giúp dâu rể theo dõi và triển khai theo từng mốc thời gian.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <div class="px-4 py-2.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-950 font-bold text-xs flex items-center gap-2 shadow-2xs">
                            <Clock class="w-4 h-4 text-rose-600 animate-pulse" />
                            <span>Còn <strong class="text-base text-rose-600 font-black">{{ daysUntilWedding }}</strong> ngày đến ngày cưới</span>
                        </div>
                        <button 
                            @click="showAddTaskModal = true; selectedMilestoneForAdd = milestones[0] || null" 
                            class="px-4 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-1.5 cursor-pointer"
                        >
                            <Plus class="w-4 h-4" /> Thêm Việc Mới
                        </button>
                    </div>
                </div>

                <!-- Priority Status Indicators Banner -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs font-medium text-slate-700">
                    <div class="p-3 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center justify-between">
                        <span class="font-bold text-rose-900 flex items-center gap-1.5">
                            <Flame class="w-4 h-4 text-rose-600" /> Khẩn Cấp / Gấp
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-rose-200 text-rose-950 font-black text-[10px]">Ưu tiên #1</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-between">
                        <span class="font-bold text-amber-900 flex items-center gap-1.5">
                            <Zap class="w-4 h-4 text-amber-600" /> Ưu Tiên Cao
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-200 text-amber-950 font-black text-[10px]">Ưu tiên #2</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-blue-50 border border-blue-200/80 flex items-center justify-between">
                        <span class="font-bold text-blue-900 flex items-center gap-1.5">
                            <Bookmark class="w-4 h-4 text-blue-600" /> Trung Bình
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-200 text-blue-950 font-black text-[10px]">Ưu tiên #3</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-between">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            <CircleDot class="w-4 h-4 text-slate-500" /> Thấp
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-900 font-black text-[10px]">Ưu tiên #4</span>
                    </div>
                </div>
            </div>

            <!-- 2. Focus Today Block -->
            <div class="p-6 rounded-3xl bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 text-white shadow-xl space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-rose-400 animate-ping"></span>
                        <h2 class="text-base font-serif font-bold text-white flex items-center gap-2">
                            🎯 Việc Ưu Tiên Cao Cần Xử Lý Trước
                        </h2>
                    </div>
                    <span class="text-xs text-rose-200 font-medium">Click vào việc để xem chi tiết & cập nhật!</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div 
                        v-for="item in focusTodayTasks" 
                        :key="item.task.id"
                        @click="openTaskDetail(item.task, item.milestoneTitle, item.timeframe)"
                        class="p-4 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 transition cursor-pointer flex items-center justify-between gap-3 text-xs group"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <button 
                                @click.stop="toggleTask(item.task)"
                                class="w-5 h-5 rounded-full border border-rose-300 bg-white/10 flex items-center justify-center shrink-0 hover:bg-rose-500 transition cursor-pointer"
                            >
                                <Check v-if="item.task.is_completed" class="w-3.5 h-3.5 text-rose-300 stroke-[3]" />
                            </button>

                            <div class="truncate">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold border uppercase flex items-center gap-1" :class="getPriorityBadgeClass(item.task.priority)">
                                        <Flame v-if="item.task.priority === 'urgent'" class="w-3 h-3" />
                                        <Zap v-else-if="item.task.priority === 'high'" class="w-3 h-3" />
                                        <Bookmark v-else-if="item.task.priority === 'medium'" class="w-3 h-3" />
                                        <CircleDot v-else class="w-3 h-3" />
                                        <span>{{ getPriorityLabelText(item.task.priority) }}</span>
                                    </span>
                                </div>
                                <span class="font-bold text-white block truncate group-hover:text-rose-200 transition">{{ item.task.title }}</span>
                                <span class="text-[11px] text-rose-200/80">{{ item.timeframe }} • {{ item.milestoneTitle }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span v-if="item.task.estimated_cost" class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/30 text-rose-100 border border-rose-400/30">
                                {{ formatCurrency(item.task.estimated_cost) }}
                            </span>
                            <ChevronRight class="w-4 h-4 text-rose-300 group-hover:translate-x-0.5 transition" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Filters & Search Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-2xl border border-rose-100 shadow-2xs">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <Filter class="w-4 h-4 text-rose-600 shrink-0" />
                    <span class="text-xs font-bold text-slate-700 shrink-0">Lọc Mức Ưu Tiên:</span>
                    <select v-model="selectedPriorityFilter" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-hidden">
                        <option value="all">Tất Cả Mức Ưu Tiên</option>
                        <option value="urgent">🔥 Khẩn Cấp / Gấp</option>
                        <option value="high">⚡ Ưu Tiên Cao</option>
                        <option value="medium">📌 Trung Bình</option>
                        <option value="low">🌱 Thấp</option>
                    </select>
                </div>

                <div class="relative w-full sm:w-64">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input 
                        v-model="taskSearchQuery"
                        type="text" 
                        placeholder="Tìm công việc..." 
                        class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:border-rose-400"
                    />
                </div>
            </div>

            <!-- 4. Collapsible Step-by-Step Milestone Accordions -->
            <div class="space-y-4">
                <div 
                    v-for="(milestone, index) in filteredMilestones" 
                    :key="milestone.id"
                    class="bg-white rounded-3xl border border-rose-100 shadow-md shadow-rose-900/5 overflow-hidden transition-all hover:border-rose-200"
                >
                    <!-- Milestone Header -->
                    <div 
                        @click="toggleMilestoneCollapse(milestone.id)"
                        class="p-6 bg-gradient-to-r from-rose-50/50 via-white to-amber-50/30 border-b border-rose-100/80 flex items-center justify-between gap-4 cursor-pointer select-none group"
                    >
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-600 text-white font-extrabold text-[10px] uppercase">
                                    Bước {{ index + 1 }}
                                </span>
                                <span class="text-xs font-bold text-rose-900">{{ milestone.timeframe }}</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-serif font-bold text-slate-900 group-hover:text-rose-700 transition">
                                {{ milestone.title }}
                            </h3>
                            <p class="text-xs text-slate-500 font-medium truncate">{{ milestone.summary }}</p>
                        </div>

                        <!-- Milestone Stats Pill & Collapse Toggle -->
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="text-right hidden sm:block">
                                <span class="text-xs font-bold text-slate-900 block">{{ milestone.completedCount }}/{{ milestone.totalCount }} Hoàn Thành</span>
                                <span class="text-[11px] text-slate-500">Ngân sách: {{ formatCurrency(Number(milestone.budget_allocated)) }}</span>
                            </div>
                            
                            <button 
                                @click.stop="openMilestoneModal(milestone)"
                                class="px-3 py-1.5 rounded-xl bg-white border border-rose-200 text-rose-900 text-xs font-bold shadow-2xs hover:bg-rose-50 transition hidden md:flex items-center gap-1 cursor-pointer"
                            >
                                Tóm Tắt
                            </button>

                            <div class="w-8 h-8 rounded-full bg-rose-50 group-hover:bg-rose-100 border border-rose-200 flex items-center justify-center text-rose-700 transition">
                                <ChevronUp v-if="!isMilestoneCollapsed(milestone.id, index)" class="w-4 h-4" />
                                <ChevronDown v-else class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    <!-- Collapsible Task List Body -->
                    <div 
                        v-show="!isMilestoneCollapsed(milestone.id, index)" 
                        class="p-6 space-y-2.5 bg-slate-50/50"
                    >
                        <div 
                            v-for="task in milestone.tasks" 
                            :key="task.id"
                            @click="openTaskDetail(task, milestone.title, milestone.timeframe)"
                            class="p-3.5 rounded-2xl border transition flex items-center justify-between gap-4 text-xs cursor-pointer group"
                            :class="task.is_completed ? 'border-emerald-200 bg-emerald-50/40 text-slate-400 line-through' : 'border-slate-200/80 bg-white hover:bg-rose-50/30 text-slate-800 font-medium'"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <button 
                                    @click.stop="toggleTask(task)"
                                    class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 transition cursor-pointer"
                                    :class="task.is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 bg-white hover:border-rose-400'"
                                >
                                    <Check v-if="task.is_completed" class="w-3.5 h-3.5 stroke-[3]" />
                                </button>
                                <span class="truncate text-xs font-bold group-hover:text-rose-900 transition">{{ task.title }}</span>
                            </div>

                            <div class="flex items-center gap-2.5 shrink-0">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border uppercase flex items-center gap-1" :class="getPriorityBadgeClass(task.priority)">
                                    <Flame v-if="task.priority === 'urgent'" class="w-3 h-3" />
                                    <Zap v-else-if="task.priority === 'high'" class="w-3 h-3" />
                                    <Bookmark v-else-if="task.priority === 'medium'" class="w-3 h-3" />
                                    <CircleDot v-else class="w-3 h-3" />
                                    <span>{{ getPriorityLabelText(task.priority) }}</span>
                                </span>

                                <span v-if="task.estimated_cost" class="text-[11px] font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                    {{ formatCurrency(task.estimated_cost) }}
                                </span>

                                <ChevronRight class="w-4 h-4 text-slate-400 group-hover:text-rose-600 transition" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Right Navigation Drawer for Task Details -->
        <div v-if="selectedTaskDetail" class="fixed inset-0 z-50 overflow-hidden bg-slate-900/30 backdrop-blur-xs flex justify-end" @click="closeTaskDetail">
            <div class="w-full max-w-xl bg-white border-l border-slate-200 h-full flex flex-col shadow-2xl overflow-y-auto" @click.stop>
                <div class="p-6 border-b border-slate-100 bg-white sticky top-0 flex items-center justify-between z-10">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border uppercase flex items-center gap-1" :class="getPriorityBadgeClass(selectedTaskDetail.task.priority)">
                                <Flame v-if="selectedTaskDetail.task.priority === 'urgent'" class="w-3 h-3" />
                                <Zap v-else-if="selectedTaskDetail.task.priority === 'high'" class="w-3 h-3" />
                                <Bookmark v-else-if="selectedTaskDetail.task.priority === 'medium'" class="w-3 h-3" />
                                <CircleDot v-else class="w-3 h-3" />
                                <span>{{ getPriorityLabelText(selectedTaskDetail.task.priority) }}</span>
                            </span>
                            <span class="text-xs font-bold text-rose-800">{{ selectedTaskDetail.timeframe }}</span>
                        </div>
                        <h2 class="text-lg font-serif font-bold text-slate-900 leading-snug">
                            {{ selectedTaskDetail.task.title }}
                        </h2>
                    </div>
                    <button @click="closeTaskDetail" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 space-y-6 flex-1 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-900 block text-xs">Trạng Thái Công Việc</span>
                            <span class="text-[11px] text-slate-500">{{ selectedTaskDetail.task.is_completed ? 'Đã hoàn thành công việc này' : 'Chưa hoàn thành' }}</span>
                        </div>
                        <button 
                            @click="toggleTask(selectedTaskDetail.task)"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                            :class="selectedTaskDetail.task.is_completed ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-300 hover:border-rose-400'"
                        >
                            <Check v-if="selectedTaskDetail.task.is_completed" class="w-4 h-4" />
                            <span>{{ selectedTaskDetail.task.is_completed ? 'Đã Hoàn Thành' : 'Đánh Dấu Xong' }}</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block font-bold text-slate-700">Dự Chi Ban Đầu (VNĐ)</label>
                            <input 
                                :value="selectedTaskDetail.task.estimated_cost" 
                                disabled 
                                class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700" 
                            />
                        </div>
                        <div class="space-y-1">
                            <label class="block font-bold text-slate-700">Thực Chi Cọc / Đã Trả (VNĐ)</label>
                            <input 
                                v-model.number="selectedTaskDetail.task.actual_cost" 
                                type="number" 
                                placeholder="0" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-rose-900 focus:bg-white focus:outline-hidden focus:border-rose-400" 
                            />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700 flex items-center gap-1.5">
                            <Store class="w-4 h-4 text-rose-600" /> Đối Tác Vendor / Đơn Vị Phụ Trách
                        </label>
                        <input 
                            v-model="selectedTaskDetail.task.vendor_info" 
                            type="text" 
                            placeholder="VD: White Palace, TuArt Studio, Bliss Decor..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" 
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-bold text-slate-700 flex items-center gap-1.5">
                            <FileText class="w-4 h-4 text-rose-600" /> Ghi Chú & Kế Hoạch Thực Hiện
                        </label>
                        <textarea 
                            v-model="selectedTaskDetail.task.notes" 
                            rows="4" 
                            placeholder="Ghi chú thêm thông tin liên hệ, cọc đợt 1..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400"
                        ></textarea>
                    </div>

                    <div v-if="saveSuccessMsg" class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                        <CheckCircle2 class="w-4 h-4 text-emerald-600" /> Đã lưu thay đổi chi tiết công việc thành công!
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3">
                    <button @click="closeTaskDetail" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100">
                        Đóng lại
                    </button>
                    <button 
                        @click="saveTaskDetails" 
                        :disabled="isSavingTask"
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                    >
                        <Save class="w-4 h-4" />
                        <span>{{ isSavingTask ? 'Đang lưu...' : 'Lưu Thay Đổi' }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Task Add Modal -->
        <div v-if="showAddTaskModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white p-6 rounded-3xl border border-rose-100 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <Plus class="w-5 h-5 text-rose-600" /> Thêm Công Việc Mới
                    </h3>
                    <button @click="showAddTaskModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tên Công Việc *</label>
                        <input v-model="newTaskTitle" type="text" placeholder="VD: Đặt cọc địa điểm tiệc cưới..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Mức Độ Ưu Tiên *</label>
                        <select v-model="newTaskPriority" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-hidden">
                            <option value="urgent">🔥 Khẩn Cấp / Gấp (Xử lý ngay)</option>
                            <option value="high">⚡ Ưu Tiên Cao (Quan trọng)</option>
                            <option value="medium">📌 Trung Bình (Theo tiến độ)</option>
                            <option value="low">🌱 Thấp (Tham khảo)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Giai Đoạn Cưới</label>
                        <select v-model="selectedMilestoneForAdd" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-hidden">
                            <option v-for="m in milestones" :key="m.id" :value="m">{{ m.title }} ({{ m.timeframe }})</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Dự Chi Chi Phí (VNĐ)</label>
                        <input v-model="newTaskEstimatedCost" type="number" placeholder="10000000" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-hidden focus:border-rose-400" />
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button @click="showAddTaskModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">Hủy Bỏ</button>
                    <button @click="handleAddTask" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20">💾 Lưu Công Việc</button>
                </div>
            </div>
        </div>

        <!-- Milestone Detail Drawer -->
        <div v-if="selectedMilestone" class="fixed inset-0 z-50 overflow-hidden bg-slate-900/30 backdrop-blur-xs flex justify-end" @click="closeMilestoneDetail">
            <div class="w-full max-w-xl bg-white border-l border-slate-200 h-full flex flex-col shadow-2xl overflow-y-auto" @click.stop>
                <div class="p-6 border-b border-slate-100 bg-white sticky top-0 flex items-center justify-between z-10">
                    <div>
                        <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">{{ selectedMilestone.timeframe }}</span>
                        <h2 class="text-lg font-serif font-bold text-slate-900">{{ selectedMilestone.title }}</h2>
                    </div>
                    <button @click="closeMilestoneDetail" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 space-y-6 flex-1 text-xs">
                    <div class="space-y-2">
                        <h4 class="font-bold text-slate-400 uppercase tracking-widest text-[10px]">Tóm Tắt Giai Đoạn</h4>
                        <p class="text-xs text-slate-700 leading-relaxed font-medium bg-slate-50 p-4 rounded-2xl border border-slate-200/80">{{ selectedMilestone.summary }}</p>
                    </div>

                    <div class="space-y-3">
                        <h4 class="font-bold text-slate-400 uppercase tracking-widest text-[10px]">Danh Sách Công Việc Kèm Ưu Tiên</h4>
                        <div class="space-y-2">
                            <div 
                                v-for="task in selectedMilestone.tasks" 
                                :key="task.id" 
                                @click="openTaskDetail(task, selectedMilestone.title, selectedMilestone.timeframe)"
                                class="p-3.5 rounded-2xl border border-slate-200/80 bg-white flex items-center justify-between gap-3 shadow-2xs hover:border-rose-300 transition cursor-pointer"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <button @click.stop="toggleTask(task)" class="w-5 h-5 rounded-full border flex items-center justify-center transition shrink-0 cursor-pointer" :class="task.is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 bg-white'">
                                        <Check v-if="task.is_completed" class="w-3.5 h-3.5 stroke-[3]" />
                                    </button>
                                    <span class="text-xs font-bold text-slate-900 truncate" :class="{ 'line-through text-slate-400': task.is_completed }">{{ task.title }}</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold border uppercase flex items-center gap-1" :class="getPriorityBadgeClass(task.priority)">
                                        <Flame v-if="task.priority === 'urgent'" class="w-3 h-3" />
                                        <Zap v-else-if="task.priority === 'high'" class="w-3 h-3" />
                                        <Bookmark v-else-if="task.priority === 'medium'" class="w-3 h-3" />
                                        <CircleDot v-else class="w-3 h-3" />
                                        <span>{{ getPriorityLabelText(task.priority) }}</span>
                                    </span>
                                    <span v-if="task.estimated_cost" class="text-[11px] font-bold text-rose-900 bg-rose-50 px-2 py-0.5 rounded-md shrink-0">
                                        {{ formatCurrency(task.estimated_cost) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WorkspaceLayout>
</template>
