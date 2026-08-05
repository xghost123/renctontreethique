<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const pending = ref([]);
const count = ref(0);
const loading = ref(true);
const error = ref('');
const toast = ref(null);
const reason = ref('');
const rejectingId = ref(null);

const csrf = () => {
    const m = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
    if (m) { try { return decodeURIComponent(m[1]); } catch (e) { return m[1]; } }
    return '';
};

function showToast(msg, type = 'success') {
    toast.value = { msg, type };
    setTimeout(() => (toast.value = null), 2500);
}

async function load() {
    loading.value = true;
    try {
        const res = await fetch('/api/moderation/queue', { headers: { 'Accept': 'application/json' } });
        const j = await res.json();
        if (!res.ok) { error.value = j.error || 'Erreur'; return; }
        pending.value = j.pending.data || [];
        count.value = j.count || 0;
    } catch (e) { error.value = e.message; }
    loading.value = false;
}

async function approve(id) {
    try {
        const res = await fetch('/api/moderation/approve', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-XSRF-TOKEN': csrf() },
            body: JSON.stringify({ message_id: id }),
        });
        const j = await res.json();
        if (!res.ok) throw new Error(j.error || 'Erreur');
        showToast('✓ Message approuvé et remis');
        await load();
    } catch (e) { showToast(e.message, 'error'); }
}

async function reject(id) {
    const r = reason.value.trim() || 'Message refusé par un modérateur.';
    try {
        const res = await fetch('/api/moderation/reject', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-XSRF-TOKEN': csrf() },
            body: JSON.stringify({ message_id: id, reason: r }),
        });
        const j = await res.json();
        if (!res.ok) throw new Error(j.error || 'Erreur');
        showToast('✗ Message refusé');
        reason.value = '';
        rejectingId.value = null;
        await load();
    } catch (e) { showToast(e.message, 'error'); }
}

function formatTime(d) {
    if (!d) return '';
    return new Date(d).toLocaleString('fr-FR', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
}

onMounted(load);
</script>

<template>
    <Head title="Modération — Rencontre Éthique" />

    <div class="min-h-screen pb-16" style="background: linear-gradient(180deg, #FBF7F0 0%, #F5EEDD 100%)">
        <div class="pattern-re absolute inset-0 pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto px-5 pt-8">
            <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-4" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                <div v-if="toast" :class="['fixed top-5 right-5 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-semibold text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-[#0f3a7d]']">{{ toast.msg }}</div>
            </Transition>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="font-display text-3xl font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">Modération des messages</h1>
                    <p class="text-sm text-[#8A9680] mt-1">Aucun message n'est remis sans votre validation</p>
                </div>
                <div class="bg-[#0f3a7d] text-white rounded-xl px-4 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#ff6b6b] animate-pulse"></span>
                    {{ count }} en attente
                </div>
            </div>

            <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5">{{ error }}</div>

            <!-- Pending list -->
            <div v-if="loading" class="space-y-4">
                <div v-for="i in 3" :key="i" class="bg-white rounded-2xl border border-[#0f3a7d]/[.06] p-5 animate-pulse">
                    <div class="h-3 bg-[#F0EDE6] rounded w-1/3 mb-3"></div>
                    <div class="h-4 bg-[#F0EDE6] rounded w-2/3 mb-2"></div>
                    <div class="h-3 bg-[#F0EDE6] rounded w-1/2"></div>
                </div>
            </div>

            <div v-else class="space-y-4">
                <div v-for="m in pending" :key="m.id" class="bg-white rounded-2xl border border-[#0f3a7d]/[.06] shadow-sm p-5">
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-[#0f3a7d]">{{ m.sender?.name }}</span>
                            <svg class="w-3.5 h-3.5 text-[#ff6b6b]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span class="font-bold text-[#374151]">{{ m.recipient?.name }}</span>
                        </div>
                        <span v-if="m.mosque" class="text-[10px] bg-[#ff6b6b]/10 text-[#8A6D12] px-2 py-0.5 rounded-full font-medium">🕌 {{ m.mosque }}</span>
                        <span class="ml-auto text-[10px] text-[#8A9680]">{{ formatTime(m.created_at) }}</span>
                    </div>

                    <div class="bg-[#F8F6F0] rounded-xl px-4 py-3 mb-4">
                        <p class="text-sm text-[#374151] leading-relaxed">« {{ m.message }} »</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <button @click="approve(m.id)" class="inline-flex items-center gap-1.5 bg-[#0f3a7d] hover:bg-[#163828] text-white text-xs font-semibold px-4 py-2 rounded-xl transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Approuver & remettre
                        </button>
                        <button @click="rejectingId = rejectingId === m.id ? null : m.id" :class="['inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2 rounded-xl transition-all', rejectingId === m.id ? 'bg-red-600 text-white' : 'bg-red-50 hover:bg-red-100 text-red-600']">
                            ✗ Refuser
                        </button>
                        <template v-if="rejectingId === m.id">
                            <input v-model="reason" placeholder="Motif du refus (optionnel)" class="field-input-re !h-9 !text-xs flex-1 min-w-[180px]" />
                            <button @click="reject(m.id)" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-4 py-2 rounded-xl">Confirmer</button>
                        </template>
                    </div>
                </div>

                <div v-if="!pending.length" class="bg-white rounded-2xl border border-[#0f3a7d]/[.06] p-14 text-center">
                    <div class="text-5xl mb-4">✅</div>
                    <h3 class="font-display text-xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Aucun message en attente</h3>
                    <p class="text-sm text-[#8A9680]">Tous les messages ont été modérés.</p>
                </div>
            </div>
        </div>
    </div>
</template>
