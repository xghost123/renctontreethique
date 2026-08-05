<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';

const conversations = ref([]);
const activeConv = ref(null);
const messages = ref([]);
const newMessage = ref('');
const loading = ref(false);
const sending = ref(false);
const notice = ref('');
const selectedOtherId = ref(null);
const toast = ref(null);
let toastTimer = null;

function showToast(msg, type = 'error') {
    toast.value = { msg, type };
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => (toast.value = null), 3200);
}

const csrf = () => {
    const m = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
    if (m) { try { return decodeURIComponent(m[1]); } catch (e) { return m[1]; } }
    return '';
};

async function api(path, method = 'GET', body = null) {
    const headers = { 'Accept': 'application/json' };
    let data = null;
    if (body !== null) {
        data = JSON.stringify(body);
        headers['Content-Type'] = 'application/json';
        headers['X-XSRF-TOKEN'] = csrf();
    }
    const res = await fetch(path, { method, headers, body: data });
    if (!res.ok) {
        const j = await res.json().catch(() => ({}));
        throw new Error(j.error || j.message || 'Erreur');
    }
    return res.json();
}

async function loadConversations() {
    try {
        const d = await api('/api/chat/conversations');
        conversations.value = d.conversations || [];
    } catch (e) { notice.value = e.message; }
}

async function openConversation(otherId) {
    selectedOtherId.value = otherId;
    try {
        const d = await api('/api/chat/open', 'POST', { other_id: otherId });
        activeConv.value = d.conversation;
        await loadMessages(d.conversation.id);
    } catch (e) {
        notice.value = e.message;
        showToast(e.message);
    }
}

async function loadMessages(convId) {
    loading.value = true;
    try {
        const d = await api('/api/chat/messages/' + convId);
        messages.value = d.messages || [];
        await nextTick();
        const el = document.querySelector('.chat-messages');
        if (el) el.scrollTop = el.scrollHeight;
    } catch (e) { notice.value = e.message; }
    loading.value = false;
}

async function sendMessage() {
    if (!newMessage.value.trim() || !activeConv.value) return;
    sending.value = true;
    notice.value = '';
    const text = newMessage.value.trim();
    newMessage.value = '';
    try {
        const d = await api('/api/chat/send', 'POST', { conversation_id: activeConv.value.id, message: text });
        messages.value.push({
            id: d.message.id,
            message: text,
            from_me: true,
            status: 'pending',
            rejected_reason: null,
            created_at: new Date().toISOString(),
        });
        notice.value = d.notice || 'Message en attente de validation';
        showToast(notice.value, 'success');
        await loadConversations();
    } catch (e) {
        newMessage.value = text;
        notice.value = e.message;
        showToast(e.message);
    }
    sending.value = false;
}

function statusBadge(s) {
    if (s === 'pending') return { text: '⏳ En attente de modération', cls: 'bg-amber-50 text-amber-700 border-amber-200' };
    if (s === 'delivered') return { text: '✓ Remis', cls: 'bg-emerald-50 text-emerald-700 border-emerald-200' };
    if (s === 'rejected') return { text: '✗ Refusé', cls: 'bg-red-50 text-red-700 border-red-200' };
    return { text: s, cls: 'bg-gray-50 text-gray-600 border-gray-200' };
}

function formatTime(d) {
    if (!d) return '';
    return new Date(d).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
}

onMounted(loadConversations);
</script>

<template>
    <Head title="Conversations — Rencontre Éthique" />

    <div class="min-h-screen pb-16" style="background: linear-gradient(180deg, #FBF7F0 0%, #F5EEDD 100%)">
        <div class="pattern-re absolute inset-0 pointer-events-none"></div>

        <div class="relative max-w-6xl mx-auto px-5 pt-8">
            <!-- Toast -->
            <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-3" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                <div v-if="toast" :class="['fixed top-5 right-5 z-50 px-5 py-3 rounded-2xl shadow-xl text-sm font-semibold text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-[#1C4532]']">
                    {{ toast.msg }}
                </div>
            </Transition>

            <h1 class="font-display text-3xl font-medium text-[#1C4532] mb-2" style="font-family:'Cormorant Garamond',serif">Conversations</h1>
            <p class="text-sm text-[#8A9680] mb-6">Chaque message est validé par un modérateur avant d'être remis</p>

            <div v-if="notice" class="mb-4 bg-[#FEFCF0] border border-[#C8A028]/40 text-[#8A6D12] px-4 py-3 rounded-xl text-sm">{{ notice }}</div>

            <div class="grid md:grid-cols-3 gap-5">
                <!-- Conversation list -->
                <div class="bg-white rounded-2xl border border-[#1C4532]/[.06] shadow-sm overflow-hidden md:col-span-1">
                    <div class="px-4 py-3.5 border-b border-[#1C4532]/[.06]">
                        <div class="text-[11px] uppercase tracking-widest text-[#C8A028] font-semibold">Mes conversations</div>
                    </div>
                    <div class="divide-y divide-[#1C4532]/[.04] max-h-[560px] overflow-y-auto">
                        <button v-for="c in conversations" :key="c.id" @click="openConversation(c.other_id)"
                            :class="['w-full px-4 py-3.5 text-left hover:bg-[#F8F6F0] transition-colors', activeConv?.id === c.id ? 'bg-[#F8F6F0] border-l-2 border-[#1C4532]' : 'border-l-2 border-transparent']">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#1C4532]/[.07] flex items-center justify-center text-[#1C4532] font-bold text-sm flex-shrink-0">
                                    {{ (c.other_name || '?').charAt(0) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-[#1C4532] text-sm truncate">{{ c.other_name }}</span>
                                        <span v-if="c.unread > 0" class="bg-[#C8A028] text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">{{ c.unread }}</span>
                                    </div>
                                    <div class="text-xs text-[#8A9680] truncate mt-0.5">{{ c.last_message || 'Nouvelle conversation' }}</div>
                                </div>
                            </div>
                        </button>
                        <div v-if="!conversations.length" class="px-4 py-10 text-center text-sm text-[#8A9680]">
                            Aucune conversation<br />
                            <span class="text-xs">Découvrez les membres de votre mosquée pour commencer</span>
                        </div>
                    </div>
                </div>

                <!-- Chat window -->
                <div class="bg-white rounded-2xl border border-[#1C4532]/[.06] shadow-sm overflow-hidden md:col-span-2 flex flex-col" style="min-height: 600px">
                    <template v-if="activeConv">
                        <!-- Header -->
                        <div class="px-5 py-3.5 border-b border-[#1C4532]/[.06] flex items-center gap-3 bg-[#F8F6F0]">
                            <div class="w-9 h-9 rounded-full bg-[#1C4532] text-white flex items-center justify-center text-xs font-bold">
                                {{ (conversations.find(c => c.id === activeConv.id)?.other_name || '?').charAt(0) }}
                            </div>
                            <div>
                                <div class="font-semibold text-[#1C4532] text-sm">{{ conversations.find(c => c.id === activeConv.id)?.other_name }}</div>
                                <div class="text-[10px] text-[#8A9680]">🕌 Membre de votre mosquée</div>
                            </div>
                            <div class="ml-auto text-[10px] bg-[#C8A028]/10 text-[#8A6D12] px-2.5 py-1 rounded-full font-medium">Messages modérés</div>
                        </div>

                        <!-- Messages -->
                        <div class="chat-messages flex-1 overflow-y-auto px-5 py-5 space-y-3" style="max-height: 440px">
                            <div v-if="loading" class="text-center text-sm text-[#8A9680] py-8">Chargement...</div>
                            <div v-for="m in messages" :key="m.id" :class="['flex', m.from_me ? 'justify-end' : 'justify-start']">
                                <div :class="['max-w-[75%] rounded-2xl px-4 py-2.5', m.from_me ? 'bg-[#1C4532] text-white rounded-br-sm' : 'bg-[#F0EDE6] text-[#374151] rounded-bl-sm']">
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ m.message }}</p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span :class="['text-[10px]', m.from_me ? 'text-white/60' : 'text-[#8A9680]']">{{ formatTime(m.created_at) }}</span>
                                        <span v-if="m.from_me" :class="['inline-flex px-1.5 py-0.5 rounded text-[9px] font-semibold border', statusBadge(m.status).cls]">{{ statusBadge(m.status).text }}</span>
                                    </div>
                                    <p v-if="m.from_me && m.status === 'rejected' && m.rejected_reason" class="text-[10px] text-red-300 mt-1">Motif : {{ m.rejected_reason }}</p>
                                </div>
                            </div>
                            <div v-if="!loading && !messages.length" class="text-center text-sm text-[#8A9680] py-10">
                                Démarrez la conversation — votre premier message sera validé par un modérateur.
                            </div>
                        </div>

                        <!-- Input -->
                        <div class="px-5 py-4 border-t border-[#1C4532]/[.06]">
                            <div class="flex gap-3">
                                <textarea v-model="newMessage" @keydown.enter.exact.prevent="sendMessage" rows="1"
                                    class="field-input-re !h-auto !py-3 resize-none flex-1" placeholder="Écrivez votre message..." ></textarea>
                                <button @click="sendMessage" :disabled="sending || !newMessage.trim()"
                                    class="bg-[#1C4532] hover:bg-[#163828] text-white px-5 rounded-xl transition-all disabled:opacity-40 flex items-center gap-1.5 text-sm font-semibold">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                    Envoyer
                                </button>
                            </div>
                            <div class="text-[10px] text-[#8A9680] mt-2 flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-[#C8A028]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                Votre message sera validé par un modérateur avant d'être remis
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <div class="flex-1 flex flex-col items-center justify-center p-10 text-center">
                            <div class="w-16 h-16 rounded-full bg-[#1C4532]/[.06] flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-[#1C4532]" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm0 14H5.17L4 17.17V4h16v12zM7 9h10v2H7V9zm0 4h7v2H7v-2z"/></svg>
                            </div>
                            <h3 class="font-display text-xl font-medium text-[#1C4532] mb-2" style="font-family:'Cormorant Garamond',serif">Sélectionnez une conversation</h3>
                            <p class="text-sm text-[#8A9680] max-w-xs">Choisissez un membre de votre mosquée ou envoyez une demande depuis la recherche.</p>
                            <Link href="/app/members" class="btn-re btn-re-primary px-6 py-2.5 text-sm mt-5">Découvrir les membres →</Link>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
