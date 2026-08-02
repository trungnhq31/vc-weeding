<script setup lang="ts">
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Bot, User, Send, Sparkles, X, ArrowRight, DollarSign, Calendar, Users, Briefcase } from 'lucide-vue-next';

interface GroundedMetrics {
  workspace: { id: string; name: string; budget_cap: number; wedding_date?: string };
  budget: { total_actual: number; budget_cap: number; remaining_balance: number; is_overrun_alert: boolean; upcoming_payments_count: number };
  tasks: { total_tasks: number; completed_tasks: number; pending_tasks: number; progress_percentage: number; overdue_count: number; overdue_tasks: Array<any> };
  vendors: { total_contracts: number; total_paid: number; remaining_unpaid: number; vendors_count: number; unpaid_vendors_count: number; upcoming_due_vendors: Array<any> };
  guests: { total_guests: number; attending_guests: number; unseated_guests: number; total_tables: number; over_capacity_tables_count: number };
}

interface ChatMessage {
  id: string;
  role: 'user' | 'assistant';
  content: string;
  openaiReply?: string | null;
  metrics?: GroundedMetrics;
  insights?: string[];
  recommendations?: string[];
  timestamp: string;
}

const props = defineProps<{
  isOpen: boolean;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
}>();

const queryInput = ref('');
const isLoading = ref(false);
const chatMessages = ref<ChatMessage[]>([]);
const chatContainer = ref<HTMLElement | null>(null);

const formatVnd = (num: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num || 0);
};

const scrollToBottom = async () => {
  await nextTick();
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
};

const executeQuery = async (queryText?: string) => {
  const textToQuery = queryText || queryInput.value.trim();
  if (!textToQuery) return;

  const userMsgId = 'msg-' + Date.now();
  const timeNow = new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

  // Add User Message to History
  chatMessages.value.push({
    id: userMsgId,
    role: 'user',
    content: textToQuery,
    timestamp: timeNow,
  });

  queryInput.value = '';
  isLoading.value = true;
  await scrollToBottom();

  try {
    const historyPayload = chatMessages.value.map(msg => ({
      role: msg.role,
      content: msg.openaiReply || msg.content,
    }));

    const res = await fetch('/wedding/ai-query', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
      },
      body: JSON.stringify({
        query: textToQuery,
        history: historyPayload,
      }),
    });

    const json = await res.json();

    if (json.success && json.data) {
      const data = json.data;
      chatMessages.value.push({
        id: 'bot-' + Date.now(),
        role: 'assistant',
        content: data.summary_text,
        openaiReply: data.openai_reply,
        metrics: data.metrics,
        insights: data.insights,
        recommendations: data.recommendations,
        timestamp: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
      });
    }
  } catch (err) {
    console.error('Grounded AI Agent query error:', err);
    chatMessages.value.push({
      id: 'err-' + Date.now(),
      role: 'assistant',
      content: 'Xin lỗi, không thể kết nối tới AI Agent lúc này. Vui lòng thử lại sau!',
      timestamp: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
    });
  } finally {
    isLoading.value = false;
    await scrollToBottom();
  }
};

const handleKeyDown = (e: KeyboardEvent) => {
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault();
    if (props.isOpen) {
      emit('close');
    }
  }
  if (e.key === 'Escape' && props.isOpen) {
    emit('close');
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
  if (chatMessages.value.length === 0) {
    // Add Welcome Message
    chatMessages.value.push({
      id: 'welcome-msg',
      role: 'assistant',
      content: 'Xin chào! Tôi là Trợ lý AI Chuyên gia Lập Kế hoạch Đám Cưới của Eloria OS.',
      openaiReply: 'Xin chào Dâu Rể & Planner! Tôi là Trợ lý AI Agent trực tuyến kết nối trực tiếp với Database đám cưới của bạn. Hãy đặt bất kỳ câu hỏi nào về Ngân sách, Tiến độ Task, Nhà cung cấp hoặc Sơ đồ bàn tiệc!',
      timestamp: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
    });
  }
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex justify-end bg-slate-950/50 backdrop-blur-xs transition-opacity">
      <!-- Backdrop Click to Close -->
      <div class="fixed inset-0" @click="emit('close')"></div>

      <!-- Drawer Content -->
      <div class="relative z-10 w-full max-w-xl bg-slate-50 h-full shadow-2xl flex flex-col border-l border-slate-200">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 bg-white flex items-center justify-between shadow-2xs">
          <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 text-white flex items-center justify-center font-bold text-xs shadow-sm">
              <Bot class="w-5 h-5 text-white" />
            </div>
            <div>
              <h2 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                <span>Eloria Grounded AI Agent</span>
                <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold border border-emerald-200">OpenAI Integrated</span>
              </h2>
              <p class="text-[11px] text-slate-500">Trò chuyện trực tiếp • Data-Grounded Zero Hallucination</p>
            </div>
          </div>

          <button @click="emit('close')" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
            <X class="w-4 h-4" />
          </button>
        </div>

        <!-- Chat Container -->
        <div ref="chatContainer" class="p-6 flex-1 overflow-y-auto space-y-4">
          <!-- Preset Quick Chips -->
          <div class="p-3 bg-white rounded-2xl border border-slate-200/80 shadow-2xs space-y-2">
            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-1">
              <Sparkles class="w-3.5 h-3.5 text-rose-500" />
              Câu hỏi gợi ý nhanh cho AI Agent
            </div>
            <div class="flex flex-wrap gap-1.5">
              <button
                @click="executeQuery('Phân tích chi tiết dòng tiền ngân sách có bị vượt trần không?')"
                class="px-2.5 py-1 text-xs font-medium bg-slate-50 text-slate-700 border border-slate-200 rounded-lg hover:bg-rose-50 hover:border-rose-300 hover:text-rose-900 transition cursor-pointer"
              >
                💰 Phân tích dòng tiền Ngân sách
              </button>
              <button
                @click="executeQuery('Kiểm tra những công việc nào đã bị quá hạn?')"
                class="px-2.5 py-1 text-xs font-medium bg-slate-50 text-slate-700 border border-slate-200 rounded-lg hover:bg-rose-50 hover:border-rose-300 hover:text-rose-900 transition cursor-pointer"
              >
                ⏱️ Kiểm tra Task Quá Hạn
              </button>
              <button
                @click="executeQuery('Nhà cung cấp nào cần thanh toán tiền cọc đợt tiếp theo?')"
                class="px-2.5 py-1 text-xs font-medium bg-slate-50 text-slate-700 border border-slate-200 rounded-lg hover:bg-rose-50 hover:border-rose-300 hover:text-rose-900 transition cursor-pointer"
              >
                🤝 Dư nợ Hợp đồng Vendors
              </button>
              <button
                @click="executeQuery('Còn bao nhiêu khách mời chưa được xếp vào sơ đồ bàn tiệc?')"
                class="px-2.5 py-1 text-xs font-medium bg-slate-50 text-slate-700 border border-slate-200 rounded-lg hover:bg-rose-50 hover:border-rose-300 hover:text-rose-900 transition cursor-pointer"
              >
                🪑 Khách mời chưa xếp bàn
              </button>
            </div>
          </div>

          <!-- Message History Stream -->
          <div v-for="msg in chatMessages" :key="msg.id" class="space-y-2">
            <!-- USER BUBBLE -->
            <div v-if="msg.role === 'user'" class="flex justify-end items-start gap-2">
              <div class="bg-slate-900 text-white p-3.5 rounded-2xl rounded-tr-xs text-xs max-w-[85%] leading-relaxed shadow-sm">
                {{ msg.content }}
                <div class="text-[9px] text-slate-400 text-right mt-1 font-mono">{{ msg.timestamp }}</div>
              </div>
              <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 text-xs font-bold shrink-0">
                <User class="w-3.5 h-3.5" />
              </div>
            </div>

            <!-- AI AGENT BUBBLE -->
            <div v-else class="flex justify-start items-start gap-2.5">
              <div class="w-8 h-8 rounded-2xl bg-gradient-to-br from-rose-600 to-rose-800 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm">
                <Bot class="w-4 h-4" />
              </div>

              <div class="space-y-3 max-w-[90%]">
                <!-- Main Speech Bubble -->
                <div class="bg-white border border-slate-200 p-4 rounded-2xl rounded-tl-xs text-xs text-slate-800 leading-relaxed shadow-2xs space-y-2">
                  <div v-if="msg.openaiReply" class="whitespace-pre-line font-medium text-slate-900">
                    {{ msg.openaiReply }}
                  </div>
                  <div v-else class="whitespace-pre-line font-medium text-slate-800">
                    {{ msg.content }}
                  </div>
                  <div class="text-[9px] text-slate-400 font-mono pt-1 border-t border-slate-100 flex items-center justify-between">
                    <span>Eloria AI Agent • Zero Hallucination</span>
                    <span>{{ msg.timestamp }}</span>
                  </div>
                </div>

                <!-- Grounded Metrics Card Snapshot if available -->
                <div v-if="msg.metrics" class="grid grid-cols-2 gap-2 text-xs">
                  <div class="p-2.5 bg-white rounded-xl border border-slate-200">
                    <div class="text-[10px] text-slate-400 uppercase font-semibold">Đã chi thực tế</div>
                    <div class="font-bold text-slate-900 mt-0.5">{{ formatVnd(msg.metrics.budget.total_actual) }}</div>
                  </div>

                  <div class="p-2.5 bg-white rounded-xl border border-slate-200">
                    <div class="text-[10px] text-slate-400 uppercase font-semibold">Tiến độ Tasks</div>
                    <div class="font-bold text-slate-900 mt-0.5">{{ msg.metrics.tasks.progress_percentage }}% ({{ msg.metrics.tasks.completed_tasks }}/{{ msg.metrics.tasks.total_tasks }})</div>
                  </div>
                </div>

                <!-- Direct Page Action Shortcuts -->
                <div v-if="msg.metrics" class="p-3 bg-slate-100 rounded-xl border border-slate-200/80 space-y-1.5">
                  <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">CHUYỂN TRANG THAO TÁC WORKSPACE</div>
                  <div class="grid grid-cols-2 gap-1.5">
                    <Link href="/wedding/budget" @click="emit('close')" class="p-1.5 bg-white rounded-lg border border-slate-200 text-[11px] font-bold text-slate-800 hover:bg-rose-50 text-center transition">
                      💰 Ngân Sách
                    </Link>
                    <Link href="/wedding/timeline" @click="emit('close')" class="p-1.5 bg-white rounded-lg border border-slate-200 text-[11px] font-bold text-slate-800 hover:bg-rose-50 text-center transition">
                      📅 Lộ Trình
                    </Link>
                    <Link href="/wedding/guests" @click="emit('close')" class="p-1.5 bg-white rounded-lg border border-slate-200 text-[11px] font-bold text-slate-800 hover:bg-rose-50 text-center transition">
                      🪑 Bàn Tiệc
                    </Link>
                    <Link href="/wedding/vendors" @click="emit('close')" class="p-1.5 bg-white rounded-lg border border-slate-200 text-[11px] font-bold text-slate-800 hover:bg-rose-50 text-center transition">
                      🤝 Vendor CRM
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Loading Indicator Bubble -->
          <div v-if="isLoading" class="flex justify-start items-center gap-2 pt-2">
            <div class="w-8 h-8 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-xs font-bold">
              <Bot class="w-4 h-4" />
            </div>
            <div class="bg-white border border-slate-200 px-4 py-2.5 rounded-2xl text-xs text-slate-500 flex items-center gap-2 shadow-2xs">
              <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
              <span>AI Agent đang phân tích dữ liệu thực từ Workspace...</span>
            </div>
          </div>
        </div>

        <!-- Chat Input Field Bar -->
        <div class="p-4 border-t border-slate-200 bg-white">
          <form @submit.prevent="executeQuery()" class="flex items-center gap-2">
            <input
              v-model="queryInput"
              type="text"
              placeholder="Hỏi AI Agent (ví dụ: dòng tiền, task quá hạn, nợ vendor)..."
              class="flex-1 px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-hidden focus:border-rose-400 transition"
            />
            <button
              type="submit"
              :disabled="isLoading || !queryInput.trim()"
              class="px-4 py-2.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
            >
              <span>Gửi</span>
              <Send class="w-3.5 h-3.5 text-rose-400" />
            </button>
          </form>
        </div>
      </div>
    </div>
  </Teleport>
</template>
