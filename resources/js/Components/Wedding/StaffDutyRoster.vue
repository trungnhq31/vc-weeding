<script setup lang="ts">
import { ref } from 'vue';
import { 
  Users, 
  UserCheck, 
  ShieldCheck, 
  Phone, 
  Gift, 
  CheckCircle2, 
  Plus, 
  Briefcase, 
  Heart,
  Clock
} from 'lucide-vue-next';

interface StaffMember {
  id: string;
  roleTitle: string;
  assignedPerson: string;
  relation: string;
  phone: string;
  responsibilities: string[];
  status: 'assigned' | 'pending';
  icon: string;
}

const staffMembers = ref<StaffMember[]>([
  {
    id: 's1',
    roleTitle: 'Trưởng Ban Khánh Tiết (Điều Phối Hai Họ)',
    assignedPerson: 'Chú Nguyễn Văn Nam',
    relation: 'Chú Chú Rể (Họ Hàng Nhà Trai)',
    phone: '0908 123 456',
    responsibilities: [
      'Đại diện hai họ phát biểu cảm ơn tại sân khấu chính',
      'Điều phối thứ tự thắp hương gia tiên sáng ngày cưới',
      'Kiểm tra và ký chốt số lượng bàn tiệc thực tế với nhà hàng'
    ],
    status: 'assigned',
    icon: '👑',
  },
  {
    id: 's2',
    roleTitle: 'Quản Lý Thùng Tiền Mừng & Sổ Ký Tên',
    assignedPerson: 'Chị Lê Thị Thanh Thảo',
    relation: 'Chị Ruột Cô Dâu (Nhà Gái)',
    phone: '0912 987 654',
    responsibilities: [
      'Trực bàn lễ tân niêm phong thùng tiền mừng trước 17:30',
      'Hướng dẫn khách mời ký tên sổ lưu niệm & chụp ảnh Photo Booth',
      'Di chuyển thùng tiền về phòng cô dâu sau nghi thức tiệc cưới'
    ],
    status: 'assigned',
    icon: '🎁',
  },
  {
    id: 's3',
    roleTitle: 'Đội Đón Khách & Hướng Dẫn Vị Trí Bàn Tiệc',
    assignedPerson: 'Nhóm Bạn Thân Cấp 3 & Đại Học (6 Người)',
    relation: 'Bạn Thân Dâu Rể',
    phone: '0938 112 233',
    responsibilities: [
      'Cầm sơ đồ phân bàn tiệc hỗ trợ khách tìm đúng vị trí bàn',
      'Hướng dẫn khách VIP vào các bàn 01 - 04 khu vực đầu sân khấu',
      'Báo cáo ngay ban khánh tiết nếu phát sinh khách ngoài dự kiến'
    ],
    status: 'assigned',
    icon: '🥂',
  },
  {
    id: 's4',
    roleTitle: 'Phụ Trách Quà Đáp Lễ & Xe Dâu Rể',
    assignedPerson: 'Anh Trần Quốc Bảo',
    relation: 'Phụ Rể Trưởng',
    phone: '0977 445 566',
    responsibilities: [
      'Bảo quản nến thơm quà đáp lễ tại bàn tiễn khách',
      'Kiểm tra hoa xe dâu & xe đưa đón hai họ đúng 07:00 sáng',
      'Trao phần quà cảm ơn tận tay khách mời trước khi ra về'
    ],
    status: 'assigned',
    icon: '🚗',
  },
]);
</script>

<template>
  <div class="p-8 rounded-3xl bg-white border border-rose-100 shadow-sm space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-rose-100 pb-4">
      <div>
        <span class="text-[11px] font-bold uppercase tracking-widest text-rose-600">WEDDING DAY LOGISTICS & DUTY ROSTER</span>
        <h2 class="text-xl font-serif font-bold text-slate-900 mt-0.5">Bảng Phân Công Ban Khánh Tiết & Nhân Sự</h2>
        <p class="text-xs text-slate-500 mt-1">Phân công nhiệm vụ cụ thể cho người nhà & đội ngũ điều phối trong ngày cưới</p>
      </div>

      <span class="px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-900 font-bold text-xs border border-rose-200 flex items-center gap-1.5">
        <UserCheck class="w-4 h-4 text-rose-600" /> Đã Phân Công 4/4 Vị Trí
      </span>
    </div>

    <!-- Duty Roster Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="staff in staffMembers" 
        :key="staff.id"
        class="p-6 rounded-2xl bg-[#FAF8F5] border border-rose-200/80 shadow-2xs hover:border-rose-300 hover:shadow-md transition-all space-y-4 flex flex-col justify-between"
      >
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-2xl p-2.5 rounded-2xl bg-white border border-rose-100 shadow-2xs">{{ staff.icon }}</span>
            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
              <CheckCircle2 class="w-3 h-3 text-emerald-600" /> Đã Chốt Nhân Sự
            </span>
          </div>

          <div>
            <h3 class="font-serif font-bold text-slate-900 text-sm mb-0.5">{{ staff.roleTitle }}</h3>
            <p class="text-xs text-rose-900 font-bold">{{ staff.assignedPerson }} <span class="text-slate-500 font-normal">({{ staff.relation }})</span></p>
          </div>

          <div class="space-y-1.5 pt-2 border-t border-rose-200/50">
            <span class="text-[11px] font-bold text-slate-700 block">Nhiệm Vụ Trọng Tâm:</span>
            <ul class="space-y-1">
              <li v-for="(task, tIdx) in staff.responsibilities" :key="tIdx" class="text-xs text-slate-600 flex items-start gap-1.5 leading-relaxed">
                <span class="text-rose-500 font-bold mt-0.5">•</span>
                <span>{{ task }}</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="pt-3 border-t border-rose-200/50 flex items-center justify-between text-xs">
          <span class="flex items-center gap-1 text-slate-600 font-mono font-semibold">
            <Phone class="w-3.5 h-3.5 text-rose-500" /> {{ staff.phone }}
          </span>
          <a :href="`tel:${staff.phone}`" class="px-3 py-1 rounded-xl bg-white border border-rose-200 text-rose-800 font-bold text-[11px] hover:bg-rose-50 transition">
            Gọi Điện Trực Tiếp
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
