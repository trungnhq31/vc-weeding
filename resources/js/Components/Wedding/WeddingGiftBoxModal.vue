<script setup lang="ts">
import { ref } from 'vue';
import { Gift, Copy, Check, X } from 'lucide-vue-next';

const isOpen = ref(false);
const copiedGroom = ref(false);
const copiedBride = ref(false);

const openModal = () => { isOpen.value = true; };
const closeModal = () => { isOpen.value = false; };

defineExpose({ openModal });

const copyStkGroom = () => {
    navigator.clipboard.writeText('19036688999');
    copiedGroom.value = true;
    setTimeout(() => { copiedGroom.value = false; }, 3000);
};

const copyStkBride = () => {
    navigator.clipboard.writeText('9903887766');
    copiedBride.value = true;
    setTimeout(() => { copiedBride.value = false; }, 3000);
};
</script>

<template>
    <div>
        <!-- Trigger Button -->
        <button 
            @click="openModal"
            class="px-8 py-3.5 rounded-full bg-gradient-to-r from-rose-600 to-amber-600 hover:from-rose-700 hover:to-amber-700 text-white font-bold text-sm shadow-xl shadow-rose-300 transition-all flex items-center gap-2 cursor-pointer"
        >
            <Gift class="w-4 h-4 fill-white" /> Gửi Hộp Mừng Cưới (VietQR)
        </button>

        <!-- VietQR Gift Box Modal -->
        <div 
            v-if="isOpen"
            @click.self="closeModal"
            class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4"
        >
            <div class="relative w-full max-w-2xl bg-[#FAF8F5] rounded-3xl border border-rose-200 shadow-2xl p-6 md:p-10 space-y-6 max-h-[90vh] overflow-y-auto">
                
                <button 
                    @click="closeModal"
                    class="absolute top-4 right-4 p-2 rounded-full bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors"
                >
                    <X class="w-5 h-5" />
                </button>

                <!-- Header -->
                <div class="text-center space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-100 text-rose-900 text-xs font-bold uppercase tracking-wider">
                        <Gift class="w-3.5 h-3.5 text-rose-600 fill-rose-400" /> Hộp Mừng Cưới Online
                    </div>
                    <h3 class="text-3xl font-serif font-bold text-rose-950">Mừng Cưới Quốc Trung & Hồng Vân</h3>
                    <p class="text-xs font-serif text-slate-600 italic">Sự có mặt và lời chúc của bạn là món quà vô giá nhất đối với chúng mình!</p>
                </div>

                <!-- QR Code Cards Grid -->
                <div class="grid md:grid-cols-2 gap-6 pt-2">
                    
                    <!-- Groom QR Card -->
                    <div class="p-6 rounded-2xl bg-white border border-rose-200 shadow-md text-center space-y-4">
                        <span class="text-xs font-mono font-bold text-rose-700 uppercase tracking-wider block">Mừng Cưới Chú Rể</span>
                        
                        <!-- QR Code Image -->
                        <div class="w-40 h-40 mx-auto p-2 bg-white rounded-xl border border-rose-200 shadow-inner flex items-center justify-center">
                            <img 
                                src="https://api.vietqr.io/image/970407-19036688999-compact2.png?amount=0&accountName=NGUYEN%20HOANG%20QUOC%20TRUNG" 
                                alt="Mã QR Chú Rể Nguyễn Hoàng Quốc Trung" 
                                class="w-full h-full object-contain"
                            />
                        </div>

                        <div class="text-xs space-y-1 font-serif text-slate-700">
                            <p class="font-bold text-rose-950 text-sm">Nguyễn Hoàng Quốc Trung</p>
                            <p>Techcombank: <span class="font-mono font-bold">19036688999</span></p>
                        </div>

                        <button 
                            @click="copyStkGroom"
                            class="w-full py-2 rounded-xl bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                        >
                            <component :is="copiedGroom ? Check : Copy" class="w-3.5 h-3.5 text-rose-700" />
                            <span>{{ copiedGroom ? 'Đã Sao Chép STK!' : 'Sao Chép Số Tài Khoản' }}</span>
                        </button>
                    </div>

                    <!-- Bride QR Card -->
                    <div class="p-6 rounded-2xl bg-white border border-rose-200 shadow-md text-center space-y-4">
                        <span class="text-xs font-mono font-bold text-rose-700 uppercase tracking-wider block">Mừng Cưới Cô Dâu</span>
                        
                        <!-- QR Code Image -->
                        <div class="w-40 h-40 mx-auto p-2 bg-white rounded-xl border border-rose-200 shadow-inner flex items-center justify-center">
                            <img 
                                src="https://api.vietqr.io/image/970415-9903887766-compact2.png?amount=0&accountName=LE%20THI%20HONG%20VAN" 
                                alt="Mã QR Cô Dâu Lê Thị Hồng Vân" 
                                class="w-full h-full object-contain"
                            />
                        </div>

                        <div class="text-xs space-y-1 font-serif text-slate-700">
                            <p class="font-bold text-rose-950 text-sm">Lê Thị Hồng Vân</p>
                            <p>VietinBank: <span class="font-mono font-bold">9903887766</span></p>
                        </div>

                        <button 
                            @click="copyStkBride"
                            class="w-full py-2 rounded-xl bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                        >
                            <component :is="copiedBride ? Check : Copy" class="w-3.5 h-3.5 text-rose-700" />
                            <span>{{ copiedBride ? 'Đã Sao Chép STK!' : 'Sao Chép Số Tài Khoản' }}</span>
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>
