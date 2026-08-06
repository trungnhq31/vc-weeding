<script setup lang="ts">
import { ref } from 'vue';
import { 
  AlertTriangle, 
  ShieldCheck, 
  CloudRain, 
  Users, 
  VolumeX, 
  Car, 
  CheckCircle2, 
  Sparkles, 
  HelpCircle 
} from 'lucide-vue-next';

interface RiskScenario {
  id: string;
  scenarioTitle: string;
  riskLevel: 'high' | 'medium' | 'low';
  probability: string;
  impact: string;
  preventionPlan: string;
  contingencyAction: string;
  responsibleRole: string;
  icon: string;
}

const riskScenarios = ref<RiskScenario[]>([
  {
    id: 'r1',
    scenarioTitle: 'Thời Tiết Mưa Bất Chợt (Khu Vực Tiệc Ngoài Trời / Lounge)',
    riskLevel: 'high',
    probability: 'Trung Bình (Mùa mưa TP.HCM)',
    impact: 'Khách mời dính mưa, làm hỏng hoa tươi & thiết bị âm thanh',
    preventionPlan: 'Chuẩn bị sẵn dù che trong suốt 50 cái & bạt kéo định vị từ 12:00 trưa',
    contingencyAction: 'Kích hoạt phương án B: Di chuyển toàn bộ khách vào sảnh dự phòng Grand Hall trong 15 phút',
    responsibleRole: 'Quản Lý Sảnh White Palace & Wedding Planner',
    icon: '🌧️',
  },
  {
    id: 'r2',
    scenarioTitle: 'Phát Sinh Khách Vượt Số Lượng Bàn Dự Kiến (+2 - 3 Bàn)',
    riskLevel: 'medium',
    probability: 'Cao (Khách đưa thêm người thân)',
    impact: 'Thiếu bàn tiệc & cỗ tiệc cho khách mời VIP',
    preventionPlan: 'Chốt nhà hàng nạp sẵn 2 bàn dự phòng (Backup Tables) kê sẵn khung',
    contingencyAction: 'Mở ngay bàn dự phòng 21 & 22 trong vòng 10 phút, nhà bếp lên thực đơn phụ cấp tốc',
    responsibleRole: 'Trưởng Ban Khánh Tiết & Bếp Trưởng Nhà Hàng',
    icon: '🍽️',
  },
  {
    id: 'r3',
    scenarioTitle: 'Sự Cố Âm Thanh Ánh Sáng Hoặc Mất Điện Sảnh Tiệc',
    riskLevel: 'low',
    probability: 'Thấp',
    impact: 'Gián đoạn nghi thức cắt bánh & phát biểu hai họ',
    preventionPlan: 'Kiểm tra máy phát điện dự phòng sảnh tiệc & micro không dây từ 15:00',
    contingencyAction: 'Sử dụng loa kéo di động tích điện & đàn Acoustic hát mộc giao lưu',
    responsibleRole: 'Kỹ Thuật Viên Âm Thanh & Band Nhạc',
    icon: '⚡',
  },
  {
    id: 'r4',
    scenarioTitle: 'Xe Dâu Hoặc Thợ Phóng Sự Trễ Giờ Do Kẹt Xe TP.HCM',
    riskLevel: 'medium',
    probability: 'Trung Bình (Kẹt xe giờ cao điểm)',
    impact: 'Trễ mốc giờ đẹp gia tiên & đón khách',
    preventionPlan: 'Lên lộ trình di chuyển trừ hao 45 phút & di chuyển từ 06:30 sáng',
    contingencyAction: 'Điều hướng xe đi tuyến đường tránh & chụp ảnh cận cảnh tại sảnh trước',
    responsibleRole: 'Phụ Rể Trưởng & Tài Xế Xe Dâu',
    icon: '🚗',
  },
]);
</script>

<template>
  <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-sm space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-rose-100 pb-4">
      <div>
        <span class="text-[11px] font-bold uppercase tracking-widest text-rose-600">CONTINGENCY RISK MANAGEMENT MATRIX</span>
        <h2 class="text-xl font-serif font-bold text-slate-900 mt-0.5">Bảng Quản Lý Rủi Ro & Kế Hoạch Dự Phòng</h2>
        <p class="text-xs text-slate-500 mt-1">Đánh giá các kịch bản rủi ro thường gặp ngày cưới và phương án xử lý tức thì</p>
      </div>

      <span class="px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-800 font-bold text-xs border border-emerald-200 flex items-center gap-1.5">
        <ShieldCheck class="w-4 h-4 text-emerald-600" /> Phương Án B Sẵn Sàng
      </span>
    </div>

    <!-- Risk Matrix Cards Grid -->
    <div class="space-y-4">
      <div 
        v-for="risk in riskScenarios" 
        :key="risk.id"
        class="p-6 rounded-2xl border-2 transition-all space-y-3 bg-[#FAF8F5] border-rose-100 hover:border-rose-300"
      >
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-center gap-3">
            <span class="text-2xl p-2 rounded-xl bg-white border border-rose-100 shadow-2xs shrink-0">{{ risk.icon }}</span>
            <div>
              <h3 class="font-serif font-bold text-slate-900 text-sm">{ risk.scenarioTitle }</h3>
              <span class="text-[10px] text-slate-500 block font-medium">Xác xuất xảy ra: <strong class="text-slate-800">{{ risk.probability }}</strong></span>
            </div>
          </div>

          <span 
            class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border shrink-0"
            :class="risk.riskLevel === 'high' ? 'bg-rose-100 text-rose-800 border-rose-200' : 'bg-amber-100 text-amber-800 border-amber-200'"
          >
            {{ risk.riskLevel === 'high' ? '🔥 Rủi Ro Cao' : '⚠️ Vừa' }}
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-rose-200/50 text-xs">
          <div class="p-3.5 rounded-xl bg-white border border-slate-200 space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Kế Hoạch Phòng Ngừa (Prevention):</span>
            <p class="text-slate-700 leading-relaxed font-medium">{{ risk.preventionPlan }}</p>
          </div>

          <div class="p-3.5 rounded-xl bg-rose-50/70 border border-rose-200 space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-800 block">Phương Án B Xử Lý Tức Thì (Contingency Action):</span>
            <p class="text-rose-950 font-bold leading-relaxed">{{ risk.contingencyAction }}</p>
          </div>
        </div>

        <div class="text-[11px] text-slate-500 font-medium pt-1 flex items-center justify-between">
          <span>Người phụ trách chính: <strong class="text-slate-800 font-bold">{{ risk.responsibleRole }}</strong></span>
        </div>
      </div>
    </div>
  </div>
</template>
