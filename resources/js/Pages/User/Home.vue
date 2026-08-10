<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import UserPanelLayout from '../../Layouts/UserPanelLayout.vue';

defineProps({
  translations: { type: Object, default: () => ({}) },
  locale: { type: String, default: 'fr' },
});

defineOptions({
  layout: UserPanelLayout,
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
  const m = document.cookie.match(/(?:^|;\\s*)XSRF-TOKEN=([^;]+)/);
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
  <Head title="Dashboard — Rencontre Éthique" />

  <div class="min-h-[calc(100vh-5rem)]">
    <!-- Toast Notifications -->
    <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-3" leave-active-class="transition duration-200" leave-to-class="opacity-0">
      <div v-if="toast" :class="['fixed bottom-5 right-5 z-50 px-6 py-3 rounded-2xl shadow-xl text-sm font-semibold text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-[#0f3a7d]']">
        {{ toast.msg }}
      </div>
    </Transition>

    <!-- Page Content -->
    <div class="max-w-6xl mx-auto">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="font-display text-3xl md:text-4xl font-medium text-[#0f3a7d] leading-tight mb-2" style="font-family:'Cormorant Garamond',serif">
          Mon Espace
        </h1>
        <p class="text-sm text-[#8A9680] max-w-2xl">
          Bienvenue dans votre tableau de bord personnalisé. Gérez vos demandes, découvrez les membres et communiquez avec vos matches.
        </p>
      </div>

      <!-- Quick Stats -->
      <div v-if="!loading && mosque" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-gradient-to-br from-[#0f3a7d]/10 to-[#0f3a7d]/5 rounded-2xl p-6 border border-[#0f3a7d]/10 backdrop-blur-sm hover:shadow-md transition-all">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs text-[#8A9680] font-medium uppercase tracking-wider mb-1">Profil</p>
              <p class="text-3xl font-bold text-[#0f3a7d] font-display" style="font-family:'Cormorant Garamond',serif">85%</p>
              <p class="text-xs text-[#8A9680] mt-2">Complété</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#0f3a7d]/20 flex items-center justify-center text-[#0f3a7d]">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-[#ff6b6b]/10 to-[#ff6b6b]/5 rounded-2xl p-6 border border-[#ff6b6b]/10 backdrop-blur-sm hover:shadow-md transition-all">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs text-[#8A9680] font-medium uppercase tracking-wider mb-1">Demandes Reçues</p>
              <p class="text-3xl font-bold text-[#ff6b6b] font-display" style="font-family:'Cormorant Garamond',serif">{{ incoming.length }}</p>
              <p class="text-xs text-[#8A9680] mt-2">En attente</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#ff6b6b]/20 flex items-center justify-center text-[#ff6b6b]">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-[#17a2b8]/10 to-[#17a2b8]/5 rounded-2xl p-6 border border-[#17a2b8]/10 backdrop-blur-sm hover:shadow-md transition-all">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs text-[#8A9680] font-medium uppercase tracking-wider mb-1">Membres</p>
              <p class="text-3xl font-bold text-[#17a2b8] font-display" style="font-family:'Cormorant Garamond',serif">{{ members.length }}</p>
              <p class="text-xs text-[#8A9680] mt-2">De votre mosquée</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#17a2b8]/20 flex items-center justify-center text-[#17a2b8]">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/></svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading Skeleton -->
      <div v-if="loading" class="space-y-6">
        <div class="bg-white rounded-2xl p-6 animate-pulse h-64"></div>
      </div>

      <!-- No Mosque Alert -->
      <div v-else-if="pending || !mosque" class="bg-gradient-to-br from-[#ff6b6b]/10 to-[#ff6b6b]/5 rounded-2xl p-8 border border-[#ff6b6b]/20 backdrop-blur-sm text-center">
        <div class="w-16 h-16 rounded-full bg-[#ff6b6b]/20 border-2 border-[#ff6b6b]/30 flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-[#ff6b6b]" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10v5h2v2h-1v9h3v2H3v-2h3v-9H5V7h2V2z"/></svg>
        </div>
        <h2 class="font-display text-2xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Rejoignez votre mosquée</h2>
        <p class="text-sm text-[#8A9680] max-w-md mx-auto mb-6 leading-relaxed">
          Votre profil sera visible uniquement par les membres approuvés de votre mosquée.
        </p>
        <Link href="/app/status" class="inline-block bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white px-8 py-3 rounded-xl font-semibold transition-all">
          Voir mon statut
        </Link>
      </div>

      <template v-else>
        <!-- Mosque Banner -->
        <div class="bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] rounded-2xl p-8 mb-8 relative overflow-hidden shadow-lg" style="box-shadow: 0 12px 40px rgba(15,58,125,.2)">
          <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40z"/></svg>
          </div>
          <div class="relative flex flex-wrap items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center">
              <svg class="w-8 h-8 text-[#E4B84A]" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10v5h2v2h-1v9h3v2H3v-2h3v-9H5V7h2V2z"/></svg>
            </div>
            <div class="flex-1">
              <div class="text-xs uppercase tracking-wider text-white/70 font-semibold mb-1">Ma mosquée</div>
              <h2 class="font-display text-2xl md:text-3xl font-medium text-white" style="font-family:'Cormorant Garamond',serif">{{ mosque.name }}</h2>
              <div class="text-sm text-white/70 mt-1">📍 {{ mosque.city }}{{ mosque.country ? ', ' + mosque.country : '' }}</div>
            </div>
          </div>
        </div>

        <!-- Incoming Proposals Section -->
        <div v-if="incoming.length" class="mb-8">
          <div class="flex items-center gap-2 mb-4">
            <h3 class="font-display text-xl font-semibold text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">Demandes Reçues</h3>
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#ff6b6b] text-white text-xs font-bold">{{ incoming.length }}</span>
          </div>
          <div class="space-y-3">
            <div v-for="p in incoming" :key="p.id" class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-all flex flex-wrap items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#0f3a7d] to-[#17a2b8] flex items-center justify-center text-[#E4B84A] font-bold">{{ initials(p.sender) }}</div>
              <div class="flex-1 min-w-[200px]">
                <div class="flex items-center gap-2">
                  <span class="font-semibold text-[#0f3a7d] text-sm">{{ firstName(p.sender) }}</span>
                  <span class="text-[10px] text-[#8A9680] bg-[#F8F6F0] px-2 py-0.5 rounded-full">il y a {{ fmtDate(p.created_at) }}</span>
                </div>
                <p class="text-xs text-[#6B7280] mt-1 line-clamp-1">{{ p.message || 'Demande de mariage' }}</p>
              </div>
              <div class="flex gap-2.5">
                <button @click="respondProposal(p.id, 'accept')" :disabled="acting[p.id]"
                  class="bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition-all disabled:opacity-50">
                  <span v-if="acting[p.id]">…</span><span v-else>Accepter</span>
                </button>
                <button @click="respondProposal(p.id, 'decline')" :disabled="acting[p.id]"
                  class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-5 py-2.5 rounded-xl transition-all disabled:opacity-50">
                  Décliner
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity & Members Grid -->
        <div class="grid lg:grid-cols-3 gap-6 mb-8">
          <!-- Activity Sidebar -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
              <h3 class="font-semibold text-[#0f3a7d] mb-4">Activité Récente</h3>
              <div class="space-y-3 text-sm">
                <div class="flex gap-3 pb-3 border-b border-gray-100">
                  <div class="w-2 h-2 rounded-full bg-[#0f3a7d] mt-1.5 flex-shrink-0"></div>
                  <div>
                    <p class="text-[#374151] font-medium">Nouvelle demande</p>
                    <p class="text-xs text-[#8A9680] mt-0.5">Il y a 2 heures</p>
                  </div>
                </div>
                <div class="flex gap-3 pb-3 border-b border-gray-100">
                  <div class="w-2 h-2 rounded-full bg-[#17a2b8] mt-1.5 flex-shrink-0"></div>
                  <div>
                    <p class="text-[#374151] font-medium">Profil visité</p>
                    <p class="text-xs text-[#8A9680] mt-0.5">Il y a 4 heures</p>
                  </div>
                </div>
                <div class="flex gap-3">
                  <div class="w-2 h-2 rounded-full bg-[#ff6b6b] mt-1.5 flex-shrink-0"></div>
                  <div>
                    <p class="text-[#374151] font-medium">Message reçu</p>
                    <p class="text-xs text-[#8A9680] mt-0.5">Il y a 1 jour</p>
                  </div>
                </div>
              </div>
              <Link href="/app/search" class="block mt-6 text-center bg-[#0f3a7d]/10 hover:bg-[#0f3a7d]/20 text-[#0f3a7d] py-2 rounded-xl text-sm font-semibold transition-colors">
                Parcourir les membres
              </Link>
            </div>
          </div>

          <!-- Members Grid -->
          <div class="lg:col-span-2">
            <h3 class="font-display text-lg font-semibold text-[#0f3a7d] mb-4" style="font-family:'Cormorant Garamond',serif">
              Membres de ma mosquée
            </h3>
            <div v-if="members.length" class="grid sm:grid-cols-2 gap-4">
              <div v-for="m in members.slice(0, 4)" :key="m.id" class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all group">
                <div class="h-1 bg-gradient-to-r from-[#0f3a7d] via-[#17a2b8] to-[#ff6b6b]"></div>
                <div class="p-4">
                  <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                      <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-[#0f3a7d] to-[#17a2b8] flex items-center justify-center text-[#E4B84A] font-bold text-sm">{{ m.age || '?' }}</div>
                      <div>
                        <div class="font-semibold text-[#0f3a7d] text-sm">{{ m.age || '—' }} ans</div>
                        <div class="text-xs text-[#8A9680]">{{ m.maritial_status || '—' }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-wrap gap-1 mb-3 text-xs">
                    <span v-if="m.madhab" class="bg-[#ff6b6b]/10 text-[#8A6D12] px-2 py-0.5 rounded-full font-medium">{{ m.madhab }}</span>
                  </div>
                  <button v-if="!m.has_pending_proposal" @click="sendProposal(m.user_id)" :disabled="acting['p' + m.user_id]"
                    class="w-full bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white text-xs font-semibold py-2 rounded-xl transition-all disabled:opacity-50">
                    <span v-if="acting['p' + m.user_id]">Envoi…</span>
                    <span v-else>Envoyer demande</span>
                  </button>
                  <div v-else class="w-full bg-[#F8F6F0] text-[#8A9680] text-xs font-semibold py-2 rounded-xl text-center">
                    ✓ Demande envoyée
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="bg-white rounded-2xl p-8 text-center border border-gray-100">
              <p class="text-sm text-[#8A9680]">Aucun membre visible pour le moment.</p>
            </div>
          </div>
        </div>

        <!-- CTA -->
        <div class="bg-gradient-to-r from-[#0f3a7d]/5 via-white to-[#17a2b8]/5 rounded-2xl p-8 border border-gray-100 text-center">
          <h2 class="font-display text-2xl font-semibold text-[#0f3a7d] mb-3" style="font-family:'Cormorant Garamond',serif">Prêt(e) à découvrir ?</h2>
          <p class="text-sm text-[#8A9680] mb-6 max-w-md mx-auto">Explorez les profils de membres de votre mosquée et connectez-vous avec ceux qui partagent vos valeurs.</p>
          <Link href="/app/search" class="inline-block bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white px-8 py-3 rounded-xl font-semibold transition-all">
            Voir tous les profils
          </Link>
        </div>
      </template>
    </div>
  </div>
</template>

<style scoped>
:deep(.pattern-re) {
  background-image: repeating-linear-gradient(
    45deg,
    rgba(15, 58, 125, 0.03),
    rgba(15, 58, 125, 0.03) 10px,
    transparent 10px,
    transparent 20px
  );
}
</style>
