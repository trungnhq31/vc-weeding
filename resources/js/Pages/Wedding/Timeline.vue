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
import GroundedAiDrawer from '@/Components/Wedding/GroundedAiDrawer.vue';
import AiPersonalizeModal from '@/Components/Wedding/AiPersonalizeModal.vue';
import QuickTaskExecuteModal from '@/Components/Wedding/QuickTaskExecuteModal.vue';
import { Zap, Sparkles } from 'lucide-vue-next';

const isAiDrawerOpen = ref(false);
const showAiPersonalizeModal = ref(false);
const showQuickExecuteModal = ref(false);
const taskForQuickExecute = ref<any>(null);
const aiDataForModal = ref<any>(null);

const openQuickExecuteModal = async (task: any) => {
    taskForQuickExecute.value = task;
    aiDataForModal.value = null;
    showQuickExecuteModal.value = true;
    try {
        const res = await fetch(`/wedding/tasks/${task.id}/ai-recommendation`);
        const data = await res.json();
        if (data.success) {
            aiDataForModal.value = data;
        }
    } catch (e) {
        console.error('Error fetching AI recommendation:', e);
    }
};

const handleExecuteTaskAction = async ({ taskId, input }: { taskId: string; input: Record<string, any> }) => {
    try {
        const res = await fetch(`/wedding/tasks/${taskId}/execute-action`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(input),
        });
        const data = await res.json();
        if (data.success) {
            showQuickExecuteModal.value = false;
            const updatedTask = data.task;
            props.milestones.forEach(m => {
                const foundIdx = m.tasks.findIndex(t => t.id === taskId);
                if (foundIdx !== -1) {
                    m.tasks[foundIdx] = updatedTask;
                    m.progress_percentage = data.milestoneProgress;
                }
            });
        }
    } catch (error) {
        console.error('Error executing task 1-click:', error);
    }
};

const handleAutoCompleteMilestoneAi = async (milestone: any) => {
    try {
        const res = await fetch(`/wedding/milestones/${milestone.id}/auto-complete-ai`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        const data = await res.json();
        if (data.success) {
            if (data.milestone) {
                const foundIdx = props.milestones.findIndex(m => m.id === milestone.id);
                if (foundIdx !== -1) {
                    props.milestones[foundIdx] = data.milestone;
                }
            }
        }
    } catch (error) {
        console.error('Error completing milestone with AI:', error);
    }
};

const handleAiApplied = (data: any) => {
    if (data.milestones) {
        props.milestones.splice(0, props.milestones.length, ...data.milestones);
    }
    if (data.workspace && props.workspace) {
        Object.assign(props.workspace, data.workspace);
    }
};


interface Subtask {
    id: string;
    title: string;
    is_completed: boolean;
}

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
    subtasks?: Subtask[];
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
    wedding_date?: string;
    budget_cap?: number;
    estimated_guests?: number;
    venue_name?: string;
}

const props = defineProps<{
    milestones: Milestone[];
    stats: Stats;
    workspace?: WorkspaceInfo;
}>();

const selectedMilestone = ref<Milestone | null>(null);
const expandedTaskId = ref<string | null>(null);
const savingTaskId = ref<string | null>(null);
const uploadingTaskId = ref<string | null>(null);

// Active modal preview for images
const previewImageUrl = ref<string | null>(null);

// Collapsible milestone state (Default COLLAPSED for all milestones)
const expandedMilestoneIds = ref<Set<string>>(new Set());

const toggleMilestoneExpand = (id: string, e?: Event) => {
    if (e) e.stopPropagation();
    const next = new Set(expandedMilestoneIds.value);
    if (next.has(id)) {
        next.delete(id);
    } else {
        next.add(id);
    }
    expandedMilestoneIds.value = next;
};

// Subtask management
const activeSubtaskInputTaskId = ref<string | null>(null);
const newSubtaskTitleMap = ref<Record<string, string>>({});

const toggleSubtask = (task: Task, subtask: Subtask, e?: Event) => {
    if (e) e.stopPropagation();
    subtask.is_completed = !subtask.is_completed;
};

const handleAddSubtask = (task: Task, e?: Event) => {
    if (e) e.stopPropagation();
    const title = newSubtaskTitleMap.value[task.id]?.trim();
    if (!title) return;
    if (!task.subtasks) task.subtasks = [];
    task.subtasks.push({
        id: `sub-${Date.now()}-${Math.floor(Math.random() * 1000)}`,
        title,
        is_completed: false
    });
    newSubtaskTitleMap.value[task.id] = '';
    activeSubtaskInputTaskId.value = null;
};

const openMilestoneModal = (milestone: Milestone) => {
    selectedMilestone.value = milestone;
    expandedTaskId.value = null;
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

const saveTaskDetails = async (task: Task) => {
    savingTaskId.value = task.id;
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
                actual_cost: task.actual_cost
            })
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
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
            },
            body: formData
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

const deleteTaskImage = async (task: Task, url: string) => {
    try {
        const response = await fetch(`/wedding/tasks/${task.id}/delete-image`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
            },
            body: JSON.stringify({ url })
        });

        const data = await response.json();
        if (data.success) {
            task.attachments = data.attachments;
        }
    } catch (e) {
        console.error('Error deleting image:', e);
    }
};

import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { computed } from 'vue';

const viewMode = ref<'milestones' | 'kanban'>('milestones');
const showAddTaskModal = ref(false);
const newTaskTitle = ref('');
const selectedMilestoneForAdd = ref<Milestone | null>(null);
const newTaskEstimatedCost = ref<number | null>(null);
const selectedCategoryFilter = ref('all');

const allTasksWithMilestone = computed(() => {
    const list: Array<{ task: Task; milestone: Milestone }> = [];
    props.milestones.forEach(m => {
        m.tasks.forEach(t => {
            list.push({ task: t, milestone: m });
        });
    });
    return list;
});

const pendingTasksList = computed(() => allTasksWithMilestone.value.filter(item => !item.task.is_completed));
const completedTasksList = computed(() => allTasksWithMilestone.value.filter(item => item.task.is_completed));

const handleAddTask = () => {
    if (!newTaskTitle.value || !selectedMilestoneForAdd.value) return;
    const newTask: Task = {
        id: `task-${Date.now()}`,
        title: newTaskTitle.value,
        is_completed: false,
        estimated_cost: newTaskEstimatedCost.value || 0,
        actual_cost: 0,
        notes: 'Công việc mới khởi tạo'
    };
    selectedMilestoneForAdd.value.tasks.push(newTask);
    newTaskTitle.value = '';
    newTaskEstimatedCost.value = null;
    showAddTaskModal.value = false;
};
</script>

<template>
    <WorkspaceLayout title="Lộ Trình & Task Cưới" active-nav="timeline">
        <!-- Sleek Minimalist Linear Header -->
        <div class="max-w-6xl mx-auto px-6 pt-8 pb-6 space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-6 border-b border-slate-200/80">
                <div class="space-y-1">
                    <h2 class="text-xl md:text-2xl font-serif font-bold text-slate-900 tracking-tight">
                        Lộ Trình & Tiến Độ Cưới
                    </h2>
                    <p class="text-xs text-slate-500 flex items-center gap-2 flex-wrap">
                        <span class="font-medium text-slate-800">{{ workspace?.groom_name }} & {{ workspace?.bride_name }}</span>
                        <span class="text-slate-300">·</span>
                        <span>📅 {{ workspace?.wedding_date || '2026-10-24' }}</span>
                        <span class="text-slate-300">·</span>
                        <span>📍 {{ workspace?.wedding_location || 'TP. Hồ Chí Minh' }}</span>
                        <span class="text-slate-300">·</span>
                        <span>👥 ~{{ workspace?.estimated_guests || 200 }} khách</span>
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <!-- View Mode Toggle -->
                    <div class="p-1 rounded-xl bg-slate-100 border border-slate-200 flex items-center gap-1 shadow-2xs">
                        <button 
                            @click="viewMode = 'milestones'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                            :class="viewMode === 'milestones' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900'"
                        >
                            Milestones
                        </button>
                        <button 
                            @click="viewMode = 'kanban'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                            :class="viewMode === 'kanban' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900'"
                        >
                            Kanban Board
                        </button>
                    </div>

                    <button 
                        @click="showAiPersonalizeModal = true" 
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 hover:from-rose-800 hover:to-slate-800 text-white text-xs font-bold shadow-md transition flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5"
                    >
                        <Sparkles class="w-4 h-4 text-rose-300 animate-pulse" /> AI Cá Nhân Hóa Kế Hoạch
                    </button>

                    <button @click="showAddTaskModal = true; selectedMilestoneForAdd = milestones[0] || null" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <Plus class="w-4 h-4 text-rose-400" /> Thêm Công Việc
                    </button>
                </div>
            </div>

            <!-- Minimalist Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Overall Progress -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tiến độ tổng thể</span>
                        <span class="text-xl font-bold text-slate-900">{{ stats.overallProgress }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-rose-500 h-1.5 rounded-full transition-all duration-500" :style="{ width: `${stats.overallProgress}%` }"></div>
                    </div>
                    <div class="text-[11px] text-slate-400">
                        Hoàn thành {{ stats.completedTasks }}/{{ stats.totalTasks }} công việc
                    </div>
                </div>

                <!-- Budget Allocated -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-1">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Ngân sách trần</span>
                    <div class="text-xl font-bold text-slate-900">{{ formatCurrency(stats.totalBudgetAllocated) }}</div>
                </div>

                <!-- Budget Spent -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-1">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Chi phí đã dùng</span>
                    <div class="text-xl font-bold text-emerald-700">{{ formatCurrency(stats.totalBudgetSpent) }}</div>
                </div>
            </div>
        </div>

        <!-- Main Content Area: Milestones Stream or Kanban Board -->
        <main class="max-w-6xl mx-auto px-6 pb-20">
            <!-- Mode 1: Milestones Timeline Stream -->
            <div v-if="viewMode === 'milestones'" class="relative border-l-2 border-rose-200/80 ml-3 md:ml-36 space-y-12">
                <div 
                    v-for="milestone in milestones" 
                    :key="milestone.id" 
                    @click="openMilestoneModal(milestone)"
                    class="relative pl-6 md:pl-10 group cursor-pointer"
                >
                    <!-- Timeframe Badge (Left side on desktop) -->
                    <div class="hidden md:flex absolute -left-44 top-4 text-right w-36 justify-end">
                        <span class="px-3.5 py-1 rounded-full bg-rose-100/80 text-rose-950 font-bold text-xs border border-rose-200/60 shadow-xs whitespace-nowrap inline-block">
                            {{ milestone.timeframe }}
                        </span>
                    </div>

                    <!-- Timeline Point Dot -->
                    <div 
                        class="absolute -left-[14px] top-4 w-7 h-7 rounded-full border-2 bg-white flex items-center justify-center transition-all group-hover:scale-125 shadow-sm"
                        :class="{
                            'border-emerald-500 text-emerald-600 bg-emerald-50 shadow-emerald-200': milestone.status === 'completed',
                            'border-rose-500 text-rose-600 bg-rose-50 shadow-rose-200': milestone.status === 'in_progress',
                            'border-slate-300 text-slate-400 bg-white': milestone.status === 'pending'
                        }"
                    >
                        <Check v-if="milestone.status === 'completed'" class="w-3.5 h-3.5 stroke-[3]" />
                        <span v-else class="w-2 h-2 rounded-full" :class="milestone.status === 'in_progress' ? 'bg-rose-500' : 'bg-slate-300'"></span>
                    </div>

                    <!-- Stitch Glassmorphic Card -->
                    <div class="p-6 md:p-7 rounded-3xl bg-white/90 backdrop-blur-md border border-rose-100/90 shadow-md shadow-rose-900/5 group-hover:shadow-xl group-hover:border-rose-200 transition-all duration-300 space-y-4">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span 
                                    class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider border shadow-2xs"
                                    :class="{
                                        'bg-emerald-100/80 text-emerald-900 border-emerald-200': milestone.status === 'completed',
                                        'bg-rose-100/80 text-rose-900 border-rose-200': milestone.status === 'in_progress',
                                        'bg-amber-50 text-amber-900 border-amber-200': milestone.status === 'pending'
                                    }"
                                >
                                    {{ milestone.status === 'completed' ? '✨ Hoàn thành' : (milestone.status === 'in_progress' ? '🔥 Đang thực hiện' : '⏳ Chờ chuẩn bị') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button 
                                    @click.stop="handleAutoCompleteMilestoneAi(milestone)"
                                    class="text-xs font-bold text-[#881337] bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-xl hidden sm:flex items-center gap-1.5 transition cursor-pointer shadow-2xs"
                                >
                                    <Sparkles class="w-3.5 h-3.5 text-[#881337]" /> AI Tự Động Hoàn Thành
                                </button>

                                <button 
                                    @click.stop="openMilestoneModal(milestone)"
                                    class="text-xs font-semibold text-rose-700 hover:text-rose-900 hidden sm:flex items-center gap-1 transition-colors pr-2"
                                >
                                    Chi tiết <ChevronRight class="w-3.5 h-3.5" />
                                </button>

                                <button 
                                    @click.stop="toggleMilestoneExpand(milestone.id)" 
                                    class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-900 text-xs font-bold flex items-center gap-1.5 transition cursor-pointer border border-slate-200 shadow-2xs"
                                >
                                    <span>{{ expandedMilestoneIds.has(milestone.id) ? 'Thu gọn' : `Công việc (${milestone.tasks?.length || 0})` }}</span>
                                    <ChevronDown class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': expandedMilestoneIds.has(milestone.id) }" />
                                </button>
                            </div>
                        </div>

                        <div @click.stop="toggleMilestoneExpand(milestone.id)" class="cursor-pointer space-y-1">
                            <h3 class="text-lg font-serif font-bold text-slate-900 group-hover:text-rose-700 transition-colors">
                                {{ milestone.title }}
                            </h3>

                            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                                {{ milestone.summary }}
                            </p>
                        </div>

                        <!-- Expanded Task & Subtask Checklist (Collapsible) -->
                        <div v-if="expandedMilestoneIds.has(milestone.id)" class="mt-4 pt-4 border-t border-rose-100/80 space-y-3" @click.stop>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    DANH SÁCH CÔNG VIỆC ({{ milestone.tasks?.filter(t => t.is_completed).length || 0 }}/{{ milestone.tasks?.length || 0 }})
                                </span>
                            </div>
                            
                            <div v-if="milestone.tasks && milestone.tasks.length > 0" class="space-y-3">
                                <div 
                                    v-for="task in milestone.tasks" 
                                    :key="task.id" 
                                    class="p-3 rounded-2xl border transition space-y-2 bg-white shadow-2xs"
                                    :class="task.is_completed ? 'border-emerald-200 bg-emerald-50/30' : 'border-rose-100 hover:border-rose-200'"
                                >
                                    <!-- Main Task Row -->
                                    <div class="flex items-center justify-between gap-3 text-xs">
                                        <div @click="toggleTask(task)" class="flex items-center gap-2.5 min-w-0 flex-1 cursor-pointer">
                                            <div 
                                                class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0 transition"
                                                :class="task.is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 bg-white'"
                                            >
                                                <Check v-if="task.is_completed" class="w-3 h-3 stroke-[3]" />
                                            </div>
                                            <span class="font-semibold leading-normal break-words" :class="task.is_completed ? 'text-slate-400 line-through' : 'text-slate-900'">
                                                {{ task.title }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <span v-if="task.estimated_cost" class="text-[11px] font-bold text-rose-900 bg-rose-50 px-2 py-0.5 rounded-md whitespace-nowrap">
                                                {{ formatCurrency(task.estimated_cost) }}
                                            </span>
                                            <button 
                                                v-if="!task.is_completed"
                                                @click.stop="openQuickExecuteModal(task)"
                                                class="px-3.5 py-1 rounded-full bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 hover:from-rose-800 hover:to-slate-800 text-white font-extrabold text-[10px] shadow-2xs flex items-center gap-1 transition-all cursor-pointer uppercase tracking-wider border border-rose-800/40 transform hover:-translate-y-0.5"
                                            >
                                                <Sparkles class="w-3 h-3 text-amber-300 animate-pulse" />
                                                <span>✨ AI Smart Suggest</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Subtasks List (Nội dung từng task có subtask) -->
                                    <div v-if="task.subtasks && task.subtasks.length > 0" class="pl-6 space-y-1.5 border-l-2 border-slate-200/80 ml-2 my-1">
                                        <div 
                                            v-for="sub in task.subtasks" 
                                            :key="sub.id" 
                                            @click.stop="toggleSubtask(task, sub)"
                                            class="flex items-center justify-between text-xs py-1 px-2.5 rounded-lg hover:bg-slate-100/80 cursor-pointer group/sub transition"
                                            :class="sub.is_completed ? 'line-through text-slate-400' : 'text-slate-700'"
                                        >
                                            <div class="flex items-center gap-2">
                                                <div 
                                                    class="w-3.5 h-3.5 rounded border flex items-center justify-center transition shrink-0" 
                                                    :class="sub.is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 bg-white'"
                                                >
                                                    <Check v-if="sub.is_completed" class="w-2.5 h-2.5 stroke-[3]" />
                                                </div>
                                                <span class="font-medium">{{ sub.title }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Inline Subtask Creator -->
                                    <div class="pl-6 pt-1 flex items-center gap-2 text-xs" @click.stop>
                                        <button 
                                            v-if="activeSubtaskInputTaskId !== task.id" 
                                            @click="activeSubtaskInputTaskId = task.id"
                                            class="text-[11px] font-semibold text-rose-700 hover:text-rose-900 flex items-center gap-1 py-0.5 px-2 rounded-md hover:bg-rose-50 transition cursor-pointer"
                                        >
                                            <Plus class="w-3 h-3" /> Thêm công việc phụ (subtask)
                                        </button>

                                        <div v-else class="flex items-center gap-2 flex-1 max-w-md pt-1">
                                            <input 
                                                v-model="newSubtaskTitleMap[task.id]" 
                                                @keyup.enter="handleAddSubtask(task)"
                                                placeholder="Nhập tên subtask & bấm Enter..." 
                                                class="px-3 py-1.5 text-xs rounded-xl border border-rose-300 bg-white focus:outline-none focus:ring-2 focus:ring-rose-500 flex-1 shadow-2xs"
                                                autoFocus
                                            />
                                            <button @click="handleAddSubtask(task)" class="px-3 py-1.5 text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white rounded-xl cursor-pointer shadow-2xs">Lưu</button>
                                            <button @click="activeSubtaskInputTaskId = null" class="p-1.5 text-slate-400 hover:text-slate-600"><X class="w-4 h-4" /></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar & Budget (Always visible summary bar) -->
                        <div class="mt-4 pt-4 border-t border-rose-100/80 flex items-center justify-between text-xs text-slate-600 gap-3">
                            <div class="flex items-center gap-3 flex-1 max-w-[240px]">
                                <div class="flex-1 bg-rose-100/60 rounded-full h-2 overflow-hidden border border-rose-200/40">
                                    <div class="bg-gradient-to-r from-rose-500 to-pink-500 h-2 rounded-full transition-all duration-500" :style="{ width: `${milestone.progress_percentage || 0}%` }"></div>
                                </div>
                                <span class="font-bold text-rose-950 text-xs shrink-0 whitespace-nowrap">{{ Math.round(milestone.progress_percentage || 0) }}%</span>
                            </div>

                            <div class="text-xs font-medium bg-rose-50/80 px-3 py-1 rounded-xl border border-rose-100 text-rose-900 shrink-0 whitespace-nowrap">
                                Ngân sách: <span class="font-bold text-rose-950">{{ formatCurrency(Number(milestone.budget_allocated)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mode 2: Kanban Board View -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Column 1: Tasks Cần Làm -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <h3 class="font-serif font-bold text-slate-900 text-base">Cần Làm / Đang Thực Hiện</h3>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">{{ pendingTasksList.length }} task</span>
                    </div>

                    <div class="space-y-3">
                        <div v-if="pendingTasksList.length === 0" class="p-8 text-center text-slate-400 text-xs bg-white rounded-2xl border border-dashed border-slate-200">
                            Không có công việc nào đang chờ.
                        </div>

                        <div 
                            v-for="item in pendingTasksList" 
                            :key="item.task.id"
                            @click="toggleTask(item.task)"
                            class="p-4 bg-white rounded-2xl border border-slate-200 shadow-2xs hover:border-rose-300 hover:shadow-xs transition cursor-pointer space-y-2 group"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100 uppercase tracking-widest truncate max-w-[200px]">
                                    {{ item.milestone.title }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ item.milestone.timeframe }}</span>
                            </div>

                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-rose-700 transition">{{ item.task.title }}</h4>

                            <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-[11px]">
                                <span class="text-slate-500">{{ item.task.notes || 'Chưa có ghi chú' }}</span>
                                <span v-if="item.task.estimated_cost" class="font-bold text-rose-950">
                                    {{ formatCurrency(item.task.estimated_cost) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Tasks Đã Hoàn Thành -->
                <div class="bg-emerald-50/50 border border-emerald-200/80 rounded-3xl p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-emerald-200 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <h3 class="font-serif font-bold text-slate-900 text-base">Đã Hoàn Thành</h3>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">{{ completedTasksList.length }} task</span>
                    </div>

                    <div class="space-y-3">
                        <div v-if="completedTasksList.length === 0" class="p-8 text-center text-slate-400 text-xs bg-white rounded-2xl border border-dashed border-slate-200">
                            Chưa có công việc nào hoàn thành.
                        </div>

                        <div 
                            v-for="item in completedTasksList" 
                            :key="item.task.id"
                            @click="toggleTask(item.task)"
                            class="p-4 bg-white rounded-2xl border border-emerald-200/80 shadow-2xs transition cursor-pointer space-y-2 opacity-85 hover:opacity-100"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 uppercase tracking-widest truncate max-w-[200px]">
                                    {{ item.milestone.title }}
                                </span>
                                <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                                    <Check class="w-3 h-3" /> Hoàn tất
                                </span>
                            </div>

                            <h4 class="text-xs font-bold text-slate-700 line-through">{{ item.task.title }}</h4>
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
                                                    @click="deleteTaskImage(task, imgUrl)"
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
        <!-- Add Custom Task Modal (The Knot Style) -->
        <div v-if="showAddTaskModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl p-6 max-w-md w-full border border-rose-100 shadow-xl">
                <h3 class="text-lg font-serif font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <Plus class="w-5 h-5 text-rose-600" /> Thêm Công Việc Mới Vào Lộ Trình
                </h3>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Giai đoạn thực hiện</label>
                        <select v-model="selectedMilestoneForAdd" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-hidden focus:border-rose-300">
                            <option v-for="m in milestones" :key="m.id" :value="m">{{ m.title }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Tên mục công việc</label>
                        <input v-model="newTaskTitle" type="text" placeholder="VD: Thử món thực đơn 6 món tiệc cưới" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-hidden focus:border-rose-300" />
                    </div>

                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Dự toán chi phí (VND)</label>
                        <input v-model.number="newTaskEstimatedCost" type="number" placeholder="5000000" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-hidden focus:border-rose-300" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="showAddTaskModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold cursor-pointer">Hủy</button>
                    <button @click="handleAddTask" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-semibold shadow-md hover:bg-rose-700 transition cursor-pointer">Lưu Công Việc</button>
                </div>
            </div>
        </div>

        <!-- AI Personalization Modal -->
        <AiPersonalizeModal 
            :is-open="showAiPersonalizeModal" 
            @close="showAiPersonalizeModal = false" 
            @applied="handleAiApplied" 
        />

        <!-- Grounded AI Drawer -->
        <GroundedAiDrawer :is-open="isAiDrawerOpen" @close="isAiDrawerOpen = false" />

        <!-- Quick Task Execute Modal (AI Smart Suggest Execution) -->
        <QuickTaskExecuteModal 
            :show="showQuickExecuteModal"
            :task="taskForQuickExecute"
            :ai-data="aiDataForModal"
            :workspace-budget-cap="workspace?.budget_cap"
            :estimated-guests="workspace?.estimated_guests"
            @close="showQuickExecuteModal = false"
            @execute="handleExecuteTaskAction"
        />
    </WorkspaceLayout>
</template>
