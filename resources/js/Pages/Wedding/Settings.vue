<script setup lang="ts">
import { ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { 
  Settings, 
  Users, 
  Save, 
  Check, 
  Globe, 
  Heart,
  Sparkles,
  MapPin,
  Building2,
  Tag
} from 'lucide-vue-next';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';

const page = usePage();
const workspace = (page.props as any).workspace || {};

const groomName = ref(workspace.groom_name || 'Nguyễn Hoàng Quốc Trung');
const brideName = ref(workspace.bride_name || 'Lê Thị Hồng Vân');
const weddingDate = ref(workspace.wedding_date || '2026-10-24');
const weddingLocation = ref(workspace.wedding_location || 'TP. Hồ Chí Minh');
const venueName = ref(workspace.venue_name || 'Trung tâm Tiệc cưới White Palace Event Center');
const estimatedGuests = ref(workspace.estimated_guests || 200);
const budgetCap = ref(workspace.budget_cap || 350000000);
const weddingHashtag = ref(workspace.wedding_hashtag || '#TrungVanWedding2026');
const coupleStory = ref(workspace.couple_story || 'Hành trình 6 năm tình yêu từ mái trường đại học đến ngày chung đôi hạnh phúc.');

const isSaved = ref(false);

const members = ref([
  { id: '1', name: groomName.value, role: 'Chú rể (Owner)', email: 'quoctrung@gmail.com' },
  { id: '2', name: brideName.value, role: 'Cô dâu (Co-Owner)', email: 'hongvan@gmail.com' },
  { id: '3', name: 'Eloria Planner Team', role: 'Wedding Planner', email: 'planner@eloria.app' },
]);

const saveSettings = () => {
  isSaved.value = true;
  setTimeout(() => { isSaved.value = false; }, 2500);
};
</script>

<template>
  <WorkspaceLayout title="Cài Đặt Workspace & Phân Quyền" active-nav="settings">
    <main class="max-w-5xl mx-auto px-6 py-8 space-y-8">
      <!-- Header Title -->
      <div class="flex items-center justify-between">
        <div>
          <span class="text-[11px] font-bold text-rose-600 uppercase tracking-widest block">PERSONALIZATION ENGINE</span>
          <h1 class="text-2xl font-serif font-bold text-rose-950">Thông Tin Cá Nhân Hóa Dâu Rể</h1>
        </div>
        <button @click="saveSettings" class="px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-md transition flex items-center gap-2 cursor-pointer">
          <Check v-if="isSaved" class="w-4 h-4 text-emerald-300" />
          <Save v-else class="w-4 h-4" />
          {{ isSaved ? 'Đã lưu cài đặt!' : 'Lưu Thay Đổi Cài Đặt' }}
        </button>
      </div>

      <!-- Couple Personalization Card -->
      <div class="p-8 rounded-3xl bg-white/90 backdrop-blur-xl border border-rose-100/90 shadow-lg shadow-rose-900/5 space-y-6">
        <h2 class="text-lg font-serif font-bold text-rose-950 flex items-center gap-2 border-b border-rose-100 pb-3">
          <Heart class="w-5 h-5 text-rose-500 fill-rose-500" />
          Thông Tin Chú Rể & Cô Dâu
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Họ & Tên Chú Rể</label>
            <input v-model="groomName" type="text" placeholder="Nguyễn Hoàng Quốc Trung" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Họ & Tên Cô Dâu</label>
            <input v-model="brideName" type="text" placeholder="Lê Thị Hồng Vân" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Ngày Tổ Chức Đám Cưới</label>
            <input v-model="weddingDate" type="date" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Tỉnh / Thành Phố Organise</label>
            <input v-model="weddingLocation" type="text" placeholder="TP. Hồ Chí Minh" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Địa Điểm / Nhà Hàng Tiệc Cưới</label>
            <input v-model="venueName" type="text" placeholder="White Palace Event Center" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Quy Mô Khách Mời Dự Kiến (Số khách)</label>
            <input v-model.number="estimatedGuests" type="number" placeholder="200" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Trần Ngân Sách Dự Kiến (VND)</label>
            <input v-model.number="budgetCap" type="number" placeholder="350000000" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Hashtag Đám Cưới</label>
            <input v-model="weddingHashtag" type="text" placeholder="#TrungVanWedding2026" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-rose-400" />
          </div>
        </div>

        <div>
          <label class="text-xs font-bold text-slate-700 block mb-1">Câu Chuyện Tình Yêu & Lời Ngỏ Gửi Khách Mời</label>
          <textarea v-model="coupleStory" rows="3" class="w-full px-4 py-2.5 bg-rose-50/40 border border-rose-200/80 rounded-xl text-xs font-medium focus:outline-hidden focus:border-rose-400"></textarea>
        </div>
      </div>

      <!-- Multi-Tenant RBAC Members -->
      <div class="p-8 rounded-3xl bg-white/90 backdrop-blur-xl border border-rose-100/90 shadow-lg shadow-rose-900/5 space-y-4">
        <div class="flex items-center justify-between border-b border-rose-100 pb-3">
          <div>
            <h2 class="text-lg font-serif font-bold text-rose-950 flex items-center gap-2">
              <Users class="w-5 h-5 text-indigo-600" />
              Thành Viên Hợp Tác Workspace (Multi-tenant RBAC)
            </h2>
            <p class="text-xs text-slate-500">Phân quyền vai trò Groom, Bride, Planner & Vendor</p>
          </div>
          <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-semibold text-xs hover:bg-slate-800 transition cursor-pointer">
            + Mời Thành Viên
          </button>
        </div>

        <div class="divide-y divide-rose-50">
          <div v-for="member in members" :key="member.id" class="py-3 flex items-center justify-between text-xs">
            <div>
              <div class="font-bold text-slate-900">{{ member.name }}</div>
              <div class="text-slate-400 font-mono text-[11px]">{{ member.email }}</div>
            </div>
            <span class="px-3 py-1 rounded-full bg-rose-100/70 text-rose-900 font-bold border border-rose-200">
              {{ member.role }}
            </span>
          </div>
        </div>
      </div>
    </main>
  </WorkspaceLayout>
</template>
