<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

defineProps({
    translations: { type: Object, default: () => ({}) },
    locale: { type: String, default: 'fr' },
});

const mosque = ref(null);
const members = ref([]);
const incoming = ref([]);
const sent = ref([]);
const joinRequests = ref([]);
const isModerator = ref(false);
const pending = ref(false);
const loading = ref(true);
const acting = ref({});
const toast = ref(null);
let toastTimer = null;

const csrf = () => {
    const m = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
    if (m) { try { return decodeURIComponent(m[1]); } catch (e) { return m[1]; } }
    return '';
};

function showToast(msg, type = 'success') {
    toast.value = { msg, type };
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => (toast.value = null), 3200);
}

async function api(path, opts = {}) {
    const res = await fetch(path, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-XSRF-TOKEN': csrf(),
            ...(opts.headers || {}),
        },
        ...opts,
    });
    const j = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(j.error || j.message || `Erreur ${res.status}`);
    return j;
}

async function load() {
    try {
        const res = await fetch('/api/mosque/my', { headers: { 'Accept': 'application/json' } });
        const d = await res.json();
        pending.value = !!d.pending;
        mosque.value = d.mosque;
        members.value = d.members || [];
        incoming.value = d.incoming_proposals || [];
        sent.value = d.sent_proposals || [];
        joinRequests.value = d.join_requests || [];
        isModerator.value = !!d.is_moderator;
    } catch (e) {
        showToast('Impossible de charger votre espace.', 'error');
    }
    loading.value = false;
}

async function sendProposal(userId) {
    if (acting.value['p' + userId]) return;
    acting.value['p' + userId] = true;
    try {
        await api('/api/mosque/propose', {
            method: 'POST',
            body: JSON.stringify({
                receiver_id: userId,
                message: 'Salam alaykoum, je souhaiterais faire votre connaissance dans le cadre du mariage.',
            }),
        });
        showToast('Demande envoyée ✓');
        await load();
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        acting.value['p' + userId] = false;
    }
}

async function respondProposal(id, action) {
    if (acting.value[id]) return;
    acting.value[id] = true;
    try {
        await api(`/api/mosque/proposals/${id}/${action}`, { method: 'POST', body: JSON.stringify({}) });
        showToast(action === 'accept' ? 'Demande acceptée ✓' : 'Demande déclinée');
        await load();
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        acting.value[id] = false;
    }
}

const firstName = (u) => (u && u.name ? u.name.split(' ')[0] : 'Membre');
const initials = (u) => (u && u.name ? u.name.split(' ').map((p) => p[0]).slice(0, 2).join('').toUpperCase() : '?');
const fmtDate = (d) => (d ? new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' }) : '');

const stats = computed(() => [
    { label: 'membres visibles', value: members.value.length },
    { label: 'demandes reçues', value: incoming.value.length },
    { label: 'demandes envoyées', value: sent.value.length },
]);

onMounted(load);
onBeforeUnmount(() => clearTimeout(toastTimer));
</script>

<template>
    <Head title="Mon espace — Rencontre Éthique" />

    <div class="min-h-screen pb-16" style="background: linear-gradient(180deg, #FBF7F0 0%, #F5EEDD 100%)">
        <div class="pattern-re absolute inset-0 pointer-events-none"></div>

        <!-- Toast -->
        <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-3" leave-active-class="transition duration-200" leave-to-class="opacity-0">
            <div v-if="toast" :class="['fixed top-5 right-5 z-50 px-5 py-3 rounded-2xl shadow-xl text-sm font-semibold text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-[#1C4532]']">
                {{ toast.msg }}
            </div>
        </Transition>

        <div class="relative max-w-6xl mx-auto px-5 pt-10">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
                <div>
                    <h1 class="font-display text-3xl md:text-4xl font-medium text-[#1C4532] leading-tight" style="font-family:'Cormorant Garamond',serif">Mon espace</h1>
                    <p class="text-sm text-[#8A9680] mt-1">Votre mosquée, vos demandes, vos rencontres</p>
                </div>
                <div class="flex gap-2.5">
                    <Link href="/app/members" class="btn-re btn-re-primary px-5 py-2.5 text-sm">Découvrir les membres</Link>
                    <Link href="/app/chat" class="btn-re btn-re-ghost px-5 py-2.5 text-sm">Messagerie</Link>
                </div>
            </div>

            <div v-if="loading" class="space-y-5">
                <div class="card-re h-36 animate-pulse"></div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div v-for="i in 3" :key="i" class="card-re h-52 animate-pulse"></div>
                </div>
            </div>

            <template v-else>
                <!-- PENDING: no approved mosque yet -->
                <div v-if="pending || !mosque" class="card-re p-12 text-center re-fade-up">
                    <div class="w-20 h-20 rounded-full bg-[#C8A028]/10 border-2 border-[#C8A028]/30 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-9 h-9 text-[#C8A028]" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10v5h2v2h-1v9h3v2H3v-2h3v-9H5V7h2V2z"/></svg>
                    </div>
                    <h2 class="font-display text-2xl font-medium text-[#1C4532] mb-2" style="font-family:'Cormorant Garamond',serif">Rejoignez votre mosquée</h2>
                    <p class="text-sm text-[#8A9680] max-w-md mx-auto mb-6 leading-relaxed">
                        Votre profil sera visible uniquement par les membres approuvés de votre mosquée.
                        L'imam valide chaque adhésion.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <Link href="/app/status" class="btn-re btn-re-primary px-7 py-3 text-sm">Voir mon statut</Link>
                    </div>
                </div>

                <template v-else>
                    <!-- ═══ MOSQUE BANNER ═══ -->
                    <div class="bg-[#1C4532] rounded-3xl p-8 mb-8 relative overflow-hidden shadow-xl" style="box-shadow: 0 12px 40px rgba(28,69,50,.25)">
                        <div class="pattern-re absolute inset-0 opacity-[.1]"></div>
                        <svg class="absolute -top-12 -right-12 w-48 h-48 text-[#C8A028] opacity-[.07]" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-60a20 20 0 1 0 20 20 20 20 0 0 0-20-20z"/></svg>
                        <div class="relative flex flex-wrap items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl bg-[#C8A028]/15 border border-[#C8A028]/30 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#E4B84A]" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10v5h2v2h-1v9h3v2H3v-2h3v-9H5V7h2V2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <div class="text-[11px] uppercase tracking-[.2em] text-[#C8A028] font-semibold mb-1">Ma mosquée</div>
                                <h2 class="font-display text-2xl md:text-3xl font-medium text-[#FBF7F0]" style="font-family:'Cormorant Garamond',serif">{{ mosque.name }}</h2>
                                <div class="text-sm text-[#C8A028]/70 mt-1">{{ mosque.city }}{{ mosque.country ? ', ' + mosque.country : '' }}</div>
                            </div>
                            <div class="flex gap-8 text-center">
                                <div v-for="s in stats" :key="s.label" class="min-w-[72px]">
                                    <div class="text-2xl md:text-3xl font-bold text-[#E4B84A] font-display" style="font-family:'Cormorant Garamond',serif">{{ s.value }}</div>
                                    <div class="text-[11px] text-[#C8A028]/60 mt-0.5">{{ s.label }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ INCOMING PROPOSALS ═══ -->
                    <div v-if="incoming.length" class="mb-8">
                        <h3 class="font-display text-xl font-semibold text-[#1C4532] mb-4" style="font-family:'Cormorant Garamond',serif">
                            Demandes reçues
                            <span class="ml-2 inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-[#C8A028] text-[#0D2218] text-xs font-bold align-middle">{{ incoming.length }}</span>
                        </h3>
                        <div class="space-y-3">
                            <div v-for="p in incoming" :key="p.id" class="card-re p-5 flex flex-wrap items-center gap-4 hover:shadow-md transition-all">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1C4532] to-[#2D6A4F] flex items-center justify-center text-[#E4B84A] font-display text-lg font-bold" style="font-family:'Cormorant Garamond',serif">{{ initials(p.sender) }}</div>
                                <div class="flex-1 min-w-[200px]">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-[#1C4532] text-sm">{{ firstName(p.sender) }}</span>
                                        <span class="text-[10px] text-[#8A9680] bg-[#F8F6F0] px-2 py-0.5 rounded-full">il y a {{ fmtDate(p.created_at) }}</span>
                                    </div>
                                    <p class="text-xs text-[#6B7280] mt-1 line-clamp-1">{{ p.message || 'Demande de mariage' }}</p>
                                </div>
                                <div class="flex gap-2.5">
                                    <button @click="respondProposal(p.id, 'accept')" :disabled="acting[p.id]"
                                        class="bg-[#1C4532] hover:bg-[#163828] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition-all active:scale-[.98] disabled:opacity-50">
                                        <span v-if="acting[p.id]">…</span><span v-else>Accepter</span>
                                    </button>
                                    <button @click="respondProposal(p.id, 'decline')" :disabled="acting[p.id]"
                                        class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-5 py-2.5 rounded-xl transition-all active:scale-[.98] disabled:opacity-50">
                                        Décliner
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ MEMBERS GRID ═══ -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-xl font-semibold text-[#1C4532]" style="font-family:'Cormorant Garamond',serif">Membres de ma mosquée</h3>
                            <Link href="/app/members" class="text-xs font-semibold text-[#C8A028] hover:text-[#B18E20] transition-colors">Tout voir →</Link>
                        </div>

                        <div v-if="members.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div v-for="m in members" :key="m.id" class="card-re overflow-hidden hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                                <div class="h-1.5 bg-gradient-to-r from-[#1C4532] via-[#2D6A4F] to-[#C8A028]"></div>
                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1C4532] to-[#2D6A4F] flex items-center justify-center text-[#E4B84A] font-display text-xl font-bold" style="font-family:'Cormorant Garamond',serif">{{ m.age || '?' }}</div>
                                            <div>
                                                <div class="font-semibold text-[#1C4532] text-sm">{{ m.age || '—' }} ans</div>
                                                <div class="text-xs text-[#8A9680] capitalize">{{ m.maritial_status || '—' }}</div>
                                            </div>
                                        </div>
                                        <span class="text-[10px] text-[#8A9680] bg-[#F8F6F0] px-2 py-1 rounded-lg">{{ m.permanent_country || '—' }}</span>
                                    </div>

                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        <span v-if="m.madhab" class="text-[10px] bg-[#C8A028]/10 text-[#8A6D12] px-2 py-0.5 rounded-full font-medium">🕌 {{ m.madhab }}</span>
                                        <span v-if="m.practice_religion" class="text-[10px] bg-[#1C4532]/[.06] text-[#1C4532] px-2 py-0.5 rounded-full font-medium">{{ m.practice_religion }}</span>
                                        <span v-if="m.height" class="text-[10px] bg-[#F0EDE6] text-[#4A5A4C] px-2 py-0.5 rounded-full font-medium">↕ {{ m.height }} cm</span>
                                    </div>

                                    <p v-if="m.bio" class="text-xs text-[#6B7280] leading-relaxed line-clamp-2 mb-4">{{ m.bio }}</p>

                                    <button v-if="!m.has_pending_proposal" @click="sendProposal(m.user_id)" :disabled="acting['p' + m.user_id]"
                                        class="w-full bg-[#1C4532] hover:bg-[#163828] text-white text-xs font-semibold py-2.5 rounded-xl transition-all active:scale-[.98] disabled:opacity-50">
                                        <span v-if="acting['p' + m.user_id]">Envoi…</span>
                                        <span v-else>Envoyer une demande</span>
                                    </button>
                                    <div v-else class="w-full bg-[#F8F6F0] text-[#8A9680] text-xs font-semibold py-2.5 rounded-xl text-center flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                        Demande envoyée
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="card-re p-10 text-center">
                            <div class="text-4xl mb-3">🕌</div>
                            <p class="text-sm text-[#8A9680]">Aucun membre visible pour le moment.</p>
                            <Link href="/app/members" class="btn-re btn-re-ghost px-6 py-2.5 text-sm mt-4">Parcourir les membres</Link>
                        </div>
                    </div>

                    <!-- ═══ MODERATOR PANEL ═══ -->
                    <div v-if="isModerator && joinRequests.length" class="mb-8">
                        <h3 class="font-display text-xl font-semibold text-[#1C4532] mb-4" style="font-family:'Cormorant Garamond',serif">
                            Adhésions en attente
                            <span class="ml-2 inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-[#C8A028] text-[#0D2218] text-xs font-bold align-middle">{{ joinRequests.length }}</span>
                        </h3>
                        <div class="card-re divide-y divide-[#1C4532]/[.04]">
                            <div v-for="r in joinRequests" :key="r.id" class="px-6 py-4 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#1C4532]/[.07] flex items-center justify-center text-[#1C4532] font-bold text-sm">{{ initials(r.user) }}</div>
                                <div class="flex-1 text-sm text-[#374151]">{{ firstName(r.user) }} <span class="text-[#8A9680]">souhaite rejoindre</span></div>
                                <Link href="/app/moderation" class="text-xs font-semibold text-[#C8A028] hover:text-[#B18E20]">Modérer →</Link>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>
