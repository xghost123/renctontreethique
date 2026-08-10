<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import UserPanelLayout from '../../Layouts/UserPanelLayout.vue';

defineProps({
  translations: { type: Object, default: () => ({}) },
  locale: { type: String, default: 'fr' },
});

defineOptions({
  layout: UserPanelLayout,
});

const proposals = ref({ sent: [], received: [] });
const loading = ref(true);
const acting = ref({});
const toast = ref(null);
let toastTimer = null;

const csrf = () => {
  const m = document.cookie.match(/(?:^|;\\s*)XSRF-TOKEN=([^;]+)/);
  if (m) { try { return decodeURIComponent(m[1]); } catch (e) { return m[1]; } }
  return '';
};

function showToast(msg, type = 'success') {
  toast.value = { msg, type };
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => (toast.value = null), 3200);
}

async function loadProposals() {
  try {
    const res = await fetch('/api/proposals', { headers: { 'Accept': 'application/json' } });
    const d = await res.json();
    proposals.value = d;
  } catch (e) {
    showToast('Impossible de charger les demandes.', 'error');
  }
  loading.value = false;
}

async function respondProposal(id, action) {
  if (acting.value[id]) return;
  acting.value[id] = true;
  try {
    const res = await fetch(`/api/proposals/${id}/${action}`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-XSRF-TOKEN': csrf(),
      },
      body: JSON.stringify({}),
    });
    if (!res.ok) throw new Error('Erreur lors de la réponse');
    showToast(action === 'accept' ? 'Demande acceptée ✓' : 'Demande déclinée');
    await loadProposals();
  } catch (e) {
    showToast(e.message, 'error');
  } finally {
    acting.value[id] = false;
  }
}

const firstName = (u) => (u && u.name ? u.name.split(' ')[0] : 'Membre');
const initials = (u) => (u && u.name ? u.name.split(' ').map((p) => p[0]).slice(0, 2).join('').toUpperCase() : '?');
const fmtDate = (d) => d ? new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '';
const getStatusLabel = (status) => {
  const labels = { pending: 'En attente', accepted: 'Acceptée', declined: 'Déclinée' };
  return labels[status] || status;
};
const getStatusColor = (status) => {
  const colors = { pending: 'text-amber-600 bg-amber-50', accepted: 'text-green-600 bg-green-50', declined: 'text-red-600 bg-red-50' };
  return colors[status] || 'text-gray-600 bg-gray-50';
};

onMounted(loadProposals);
</script>

<template>
  <Head title="Demandes de Mariage — Rencontre Éthique" />

  <div class="min-h-[calc(100vh-5rem)]">
    <!-- Toast -->
    <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-3" leave-active-class="transition duration-200" leave-to-class="opacity-0">
      <div v-if="toast" :class="['fixed bottom-5 right-5 z-50 px-6 py-3 rounded-2xl shadow-xl text-sm font-semibold text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-[#0f3a7d]']">
        {{ toast.msg }}
      </div>
    </Transition>

    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="font-display text-3xl md:text-4xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">
        Demandes de Mariage
      </h1>
      <p class="text-sm text-[#8A9680]">
        Gérez vos demandes envoyées et reçues
      </p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="space-y-6">
      <div class="bg-white rounded-2xl p-8 animate-pulse h-96"></div>
    </div>

    <template v-else>
      <!-- Two Column Layout -->
      <div class="grid lg:grid-cols-2 gap-8">
        <!-- Demandes Reçues -->
        <div>
          <h2 class="font-display text-xl font-semibold text-[#0f3a7d] mb-4" style="font-family:'Cormorant Garamond',serif">
            Demandes Reçues
            <span class="ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#ff6b6b] text-white text-xs font-bold">{{ proposals.received.length }}</span>
          </h2>

          <div v-if="proposals.received.length" class="space-y-4">
            <div v-for="p in proposals.received" :key="p.id" class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition-all">
              <div class="h-1 bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8]"></div>
              <div class="p-6">
                <div class="flex items-start gap-4 mb-4">
                  <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#0f3a7d] to-[#ff6b6b] flex items-center justify-center text-white font-bold text-lg">
                    {{ initials(p.sender) }}
                  </div>
                  <div class="flex-1">
                    <h3 class="font-semibold text-[#0f3a7d] text-lg">{{ firstName(p.sender) }}</h3>
                    <p class="text-xs text-[#8A9680] mt-0.5">
                      {{ p.sender?.age }} ans • {{ p.sender?.permanent_country }}
                    </p>
                    <p class="text-xs text-[#6B7280] mt-1">
                      Demande envoyée le {{ fmtDate(p.created_at) }}
                    </p>
                  </div>
                </div>

                <div v-if="p.message" class="bg-gray-50 rounded-xl p-4 mb-4 text-sm text-[#374151] italic border-l-4 border-[#17a2b8]">
                  "{{ p.message }}"
                </div>

                <div class="flex gap-3">
                  <button
                    @click="respondProposal(p.id, 'accept')"
                    :disabled="acting[p.id]"
                    class="flex-1 bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white font-semibold py-2.5 rounded-xl transition-all disabled:opacity-50"
                  >
                    {{ acting[p.id] ? '…' : 'Accepter' }}
                  </button>
                  <button
                    @click="respondProposal(p.id, 'decline')"
                    :disabled="acting[p.id]"
                    class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 rounded-xl transition-all disabled:opacity-50"
                  >
                    Décliner
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-[#8A9680]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8"/>
              </svg>
            </div>
            <p class="text-[#8A9680] font-medium">Aucune demande reçue</p>
            <p class="text-xs text-[#8A9680] mt-1">Les demandes s'afficheront ici</p>
          </div>
        </div>

        <!-- Demandes Envoyées -->
        <div>
          <h2 class="font-display text-xl font-semibold text-[#0f3a7d] mb-4" style="font-family:'Cormorant Garamond',serif">
            Demandes Envoyées
            <span class="ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#17a2b8] text-white text-xs font-bold">{{ proposals.sent.length }}</span>
          </h2>

          <div v-if="proposals.sent.length" class="space-y-4">
            <div v-for="p in proposals.sent" :key="p.id" class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition-all">
              <div class="h-1 bg-gradient-to-r from-[#17a2b8] to-[#0f3a7d]"></div>
              <div class="p-6">
                <div class="flex items-start gap-4 mb-4">
                  <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#17a2b8] to-[#0f3a7d] flex items-center justify-center text-white font-bold text-lg">
                    {{ initials(p.receiver) }}
                  </div>
                  <div class="flex-1">
                    <div class="flex items-start justify-between">
                      <div>
                        <h3 class="font-semibold text-[#0f3a7d] text-lg">{{ firstName(p.receiver) }}</h3>
                        <p class="text-xs text-[#8A9680] mt-0.5">
                          {{ p.receiver?.age }} ans • {{ p.receiver?.permanent_country }}
                        </p>
                      </div>
                      <span :class="['text-xs font-semibold px-3 py-1 rounded-full', getStatusColor(p.status)]">
                        {{ getStatusLabel(p.status) }}
                      </span>
                    </div>
                    <p class="text-xs text-[#6B7280] mt-2">
                      Envoyée le {{ fmtDate(p.created_at) }}
                    </p>
                  </div>
                </div>

                <div v-if="p.message" class="bg-gray-50 rounded-xl p-4 text-sm text-[#374151] italic border-l-4 border-[#17a2b8]">
                  "{{ p.message }}"
                </div>

                <div v-if="p.status === 'accepted'" class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                  <p class="text-sm font-semibold text-green-600">✓ Demande acceptée !</p>
                  <p class="text-xs text-green-600 mt-1">Vous pouvez à présent communiquer</p>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-[#8A9680]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l-6-2m6 2l6-2"/>
              </svg>
            </div>
            <p class="text-[#8A9680] font-medium">Aucune demande envoyée</p>
            <p class="text-xs text-[#8A9680] mt-1">Commencez à parcourir les profils</p>
            <Link href="/app/search" class="inline-block mt-4 bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white px-6 py-2 rounded-xl text-sm font-semibold transition-all">
              Parcourir →
            </Link>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
