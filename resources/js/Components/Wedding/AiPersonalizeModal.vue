<script setup lang="ts">
import { ref } from 'vue';
import { Sparkles, X, Check, Heart, Calendar, MapPin, DollarSign, Users, ShieldCheck, Loader2 } from 'lucide-vue-next';

const props = defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'applied', data: any): void;
}>();

const currentStep = ref(1);
const isAnalyzing = ref(false);
const analysisProgress = ref(0);
const analysisStatusText = ref('');

const formData = ref({
    groom_name: 'Quốc Trung',
    bride_name: 'Hồng Vân',
    wedding_date: '2026-10-24',
    wedding_location: 'TP. Hồ Chí Minh',
    venue_type: 'Sảnh tiệc trung tâm',
    religion: 'Không theo đạo (Lễ cưới truyền thống Việt Nam)',
    style: 'Sang trọng & Modern Minimalist',
    budget_range: '300 - 500 triệu',
    estimated_guests: '200 - 300 khách',
    special_requirements: [
        'Chụp Pre-wedding ngoại cảnh xa (Đà Lạt / Phú Quốc)',
        'Thiết kế thiệp điện tử mở sáp 3D cá nhân hóa',
        'Tổ chức tiệc After-Party đêm cùng bạn bè'
    ]
});

const venueOptions = [
    'Sảnh tiệc trung tâm cao cấp',
    'Resort bãi biển ngoài trời',
    'Sân vườn Villa lãng mạn',
    'Nhà hàng tiệc cưới ấm cúng'
];

const religionOptions = [
    'Không theo đạo (Lễ cưới truyền thống Việt Nam)',
    'Đạo Công Giáo (Thánh Lễ Hôn Phối tại Nhà Thờ)',
    'Đạo Phật (Lễ Hằng Thuận tại Chùa)',
    'Đạo Tin Lành (Lễ cưới tại Thánh Thất / Hội Thánh)',
    'Phong tục truyền thống Khác'
];

const styleOptions = [
    'Sang trọng & Modern Minimalist',
    'Lãng mạn Châu Âu Pastel',
    'Truyền thống Á Đông ấm cúng',
    'Rustic Sân vườn / Boho Beach'
];

const budgetOptions = [
    'Dưới 150 triệu',
    '150 - 300 triệu',
    '300 - 500 triệu',
    'Trên 500 triệu (Luxury)'
];

const guestOptions = [
    'Dưới 100 khách (Ấm cúng)',
    '100 - 250 khách (Vừa phải)',
    '250 - 400 khách (Quy chuẩn)',
    'Trên 400 khách (Đông đúc)'
];

const requirementOptions = [
    'Chụp Pre-wedding ngoại cảnh xa (Đà Lạt / Phú Quốc)',
    'Thiết kế thiệp điện tử mở sáp 3D cá nhân hóa',
    'Trang trí hoa tươi 100% nhập khẩu',
    'Tổ chức tiệc After-Party đêm cùng bạn bè',
    'Thuê dịch vụ Wedding Planner trọn gói',
    'Thực đơn món ăn chay / kiêng đặc biệt'
];

const toggleRequirement = (req: string) => {
    const idx = formData.value.special_requirements.indexOf(req);
    if (idx > -1) {
        formData.value.special_requirements.splice(idx, 1);
    } else {
        formData.value.special_requirements.push(req);
    }
};

const handleStartAnalysis = async () => {
    isAnalyzing.value = true;
    analysisProgress.value = 15;
    analysisStatusText.value = 'Đang đọc thông tin cá nhân hóa của Dâu Rể...';

    setTimeout(() => {
        analysisProgress.value = 45;
        analysisStatusText.value = 'AI đang thiết kế 6 Giai đoạn & Subtask chi tiết...';
    }, 1200);

    setTimeout(() => {
        analysisProgress.value = 85;
        analysisStatusText.value = 'Tối ưu hóa ngân sách & phân bổ dòng tiền...';
    }, 2400);

    try {
        const response = await fetch('/wedding/ai-personalize-timeline', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''
            },
            body: JSON.stringify(formData.value)
        });

        const data = await response.json();
        analysisProgress.value = 100;
        analysisStatusText.value = 'Hoàn tất cá nhân hóa lộ trình!';

        setTimeout(() => {
            isAnalyzing.value = false;
            emit('applied', data);
            emit('close');
        }, 800);

    } catch (e) {
        console.error('Error generating AI timeline:', e);
        isAnalyzing.value = false;
    }
};
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" @click="emit('close')">
        <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden border border-rose-100 flex flex-col max-h-[90vh]" @click.stop>
            
            <!-- Modal Header Banner -->
            <div class="bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 p-6 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/20 border border-rose-400/40 flex items-center justify-center shrink-0">
                        <Sparkles class="w-5 h-5 text-rose-300 animate-pulse" />
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-rose-300 block">OPENAI GROUNDED AI AGENT</span>
                        <h3 class="font-serif font-bold text-lg text-white">Cá Nhân Hóa Kế Hoạch & Subtask Cưới</h3>
                    </div>
                </div>
                <button @click="emit('close')" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition cursor-pointer">
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Modal Content Body -->
            <div class="p-6 md:p-8 overflow-y-auto space-y-6 flex-1">
                
                <!-- Loading State during AI Analysis -->
                <div v-if="isAnalyzing" class="py-12 text-center space-y-6">
                    <div class="relative w-20 h-20 mx-auto flex items-center justify-center">
                        <div class="absolute inset-0 rounded-full border-4 border-rose-100 border-t-rose-600 animate-spin"></div>
                        <Sparkles class="w-8 h-8 text-rose-600 animate-pulse" />
                    </div>

                    <div class="space-y-2 max-w-md mx-auto">
                        <h4 class="font-serif font-bold text-lg text-slate-900">AI đang phân tích & lập lộ trình...</h4>
                        <p class="text-xs text-slate-500 font-medium">{{ analysisStatusText }}</p>
                    </div>

                    <div class="w-full max-w-md mx-auto bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                        <div class="bg-gradient-to-r from-rose-500 to-pink-500 h-2 rounded-full transition-all duration-500" :style="{ width: `${analysisProgress}%` }"></div>
                    </div>
                </div>

                <!-- Form Step 1: Couple & Venue Details -->
                <div v-else-if="currentStep === 1" class="space-y-6">
                    <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 text-rose-950 text-xs leading-relaxed flex items-start gap-3">
                        <Heart class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" />
                        <span>Hãy chia sẻ thông tin chi tiết về lễ cưới của hai bạn để Trợ lý AI thiết kế lộ trình & danh sách công việc phụ (subtasks) chuẩn xác nhất!</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Tên Chú Rể</label>
                            <input v-model="formData.groom_name" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Tên Cô Dâu</label>
                            <input v-model="formData.bride_name" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Ngày Tổ Chức Dự Kiến</label>
                            <input v-model="formData.wedding_date" type="date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Tỉnh / Thành Phố</label>
                            <input v-model="formData.wedding_location" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 block">Địa Điểm / Loại Hình Tiệc Cưới</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button 
                                v-for="opt in venueOptions" 
                                :key="opt" 
                                type="button"
                                @click="formData.venue_type = opt"
                                class="p-3 rounded-xl border text-left text-xs font-semibold transition cursor-pointer"
                                :class="formData.venue_type === opt ? 'border-rose-500 bg-rose-50 text-rose-950 font-bold shadow-2xs' : 'border-slate-200 hover:border-slate-300 text-slate-700'"
                            >
                                {{ opt }}
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 block">Nghi Lễ Tôn Giáo / Tín Ngưỡng (Việt Nam)</label>
                        <div class="space-y-2">
                            <button 
                                v-for="rel in religionOptions" 
                                :key="rel" 
                                type="button"
                                @click="formData.religion = rel"
                                class="w-full p-2.5 rounded-xl border text-left text-xs font-medium transition cursor-pointer flex items-center justify-between"
                                :class="formData.religion === rel ? 'border-rose-500 bg-rose-50/80 text-rose-950 font-bold shadow-2xs' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
                            >
                                <span>{{ rel }}</span>
                                <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0" :class="formData.religion === rel ? 'bg-rose-600 border-rose-600 text-white' : 'border-slate-300 bg-white'">
                                    <Check v-if="formData.religion === rel" class="w-3 h-3 stroke-[3]" />
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form Step 2: Style, Budget & Preferences -->
                <div v-else-if="currentStep === 2" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 block">Phong Cách Tiệc Cưới Chủ Đạo</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button 
                                v-for="opt in styleOptions" 
                                :key="opt" 
                                type="button"
                                @click="formData.style = opt"
                                class="p-3 rounded-xl border text-left text-xs font-semibold transition cursor-pointer"
                                :class="formData.style === opt ? 'border-rose-500 bg-rose-50 text-rose-950 font-bold shadow-2xs' : 'border-slate-200 hover:border-slate-300 text-slate-700'"
                            >
                                {{ opt }}
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-700 block">Ngân Sách Trần Dự Kiến</label>
                            <select v-model="formData.budget_range" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none bg-white font-medium">
                                <option v-for="b in budgetOptions" :key="b" :value="b">{{ b }}</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-700 block">Quy Mô Khách Mời Dự Kiến</label>
                            <select v-model="formData.estimated_guests" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none bg-white font-medium">
                                <option v-for="g in guestOptions" :key="g" :value="g">{{ g }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 block">Nhu Cầu Cá Nhân Hóa Đặc Biệt (Tùy chọn)</label>
                        <div class="space-y-2">
                            <div 
                                v-for="req in requirementOptions" 
                                :key="req" 
                                @click="toggleRequirement(req)"
                                class="p-2.5 rounded-xl border transition flex items-center justify-between text-xs cursor-pointer"
                                :class="formData.special_requirements.includes(req) ? 'border-rose-300 bg-rose-50/60 font-semibold text-rose-950' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
                            >
                                <span>{{ req }}</span>
                                <div class="w-4 h-4 rounded border flex items-center justify-center" :class="formData.special_requirements.includes(req) ? 'bg-rose-600 border-rose-600 text-white' : 'border-slate-300 bg-white'">
                                    <Check v-if="formData.special_requirements.includes(req)" class="w-3 h-3 stroke-[3]" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer Controls -->
            <div v-if="!isAnalyzing" class="p-6 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between shrink-0">
                <button 
                    v-if="currentStep > 1" 
                    @click="currentStep--" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200 transition cursor-pointer"
                >
                    Quay lại
                </button>
                <div v-else></div>

                <button 
                    v-if="currentStep === 1" 
                    @click="currentStep = 2" 
                    class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer shadow-sm"
                >
                    Tiếp theo: Phong cách & Ngân sách →
                </button>

                <button 
                    v-else 
                    @click="handleStartAnalysis" 
                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 via-pink-600 to-purple-600 hover:from-rose-500 hover:to-purple-500 text-white text-xs font-bold shadow-lg shadow-rose-600/20 transition cursor-pointer flex items-center gap-2"
                >
                    <Sparkles class="w-4 h-4 text-amber-300" /> AI Phân Tích & Tạo Lộ Trình Chi Tiết
                </button>
            </div>

        </div>
    </div>
</template>
