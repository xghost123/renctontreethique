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

const biodatas = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const acting = ref({});
const toast = ref(null);
let toastTimer = null;

const filters = ref({
  gender: '',
  ageMin: 18,
  ageMax: 65,
  location: '',
  education: '',
  practice: '',
});

const showFilters = ref(false);

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

async function loadBiodatas(page = 1) {
  loading.value = true;
  try {
    const params = new URLSearchParams();
    params.set('page', page);
    if (filters.value.gender) params.set('gender', filters.value.gender);
    if (filters.value.ageMin) params.set('age_min', filters.value.ageMin);
    if (filters.value.ageMax) params.set('age_max', filters.value.ageMax);
    if (filters.value.location) params.set('location', filters.value.location);

    const res = await fetch(`/api/biodatas/search?${params}`, {
      headers: { 'Accept': 'application/json' }
    });
    const d = await res.json();
    biodatas.value = d.data || [];
    currentPage.value = d.current_page || 1;
    totalPages.value = d.last_page || 1;
  } catch (e) {
    showToast('Erreur lors du chargement des profils.', 'error');
  }
  loading.value = false;
}

async function sendLike(biodataId) {
  if (acting.value['like' + biodataId]) return;
  acting.value['like' + biodataId] = true;
  try {
    const res = await fetch('/api/likes', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-XSRF-TOKEN': csrf(),
      },
      body: JSON.stringify({ biodata_id: biodataId }),
    });
    if (!res.ok) throw new Error('Erreur lors du like');
    showToast('J\'aime ajouté ! ❤️');
    await loadBiodatas(currentPage.value);
  } catch (e) {
    showToast(e.message, 'error');
  } finally {
    acting.value['like' + biodataId] = false;
  }
}

async function sendProposal(biodataId) {
  if (acting.value['proposal' + biodataId]) return;
  acting.value['proposal' + biodataId] = true;
  try {
    const res = await fetch('/api/proposals', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-XSRF-TOKEN': csrf(),
      },
      body: JSON.stringify({ 
        biodata_id: biodataId,
        message: 'Salam alaykoum, je souhaiterais faire votre connaissance.'
      }),
    });
    if (!res.ok) throw new Error('Erreur lors de l\'envoi');
    showToast('Demande envoyée ! ✓');
    await loadBiodatas(currentPage.value);
  } catch (e) {
    showToast(e.message, 'error');
  } finally {
    acting.value['proposal' + biodataId] = false;
  }
}

const filtersActive = computed(() => {
  return filters.value.gender || filters.value.location || 
         filters.value.ageMin !== 18 || filters.value.ageMax !== 65;
});

const resetFilters = () => {
  filters.value = {
    gender: '',
    ageMin: 18,
    ageMax: 65,
    location: '',
    education: '',
    practice: '',
  };
  loadBiodatas(1);
};

onMounted(() => loadBiodatas(1));
</script>

<template>
  <Head title="Parcourir les profils — Rencontre Éthique" />

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
        Parcourir les Profils
      </h1>
      <p class="text-sm text-[#8A9680]">
        Découvrez les profils de votre communauté et connectez-vous avec ceux qui partagent vos valeurs
      </p>
    </div>

    <!-- Main Content -->
    <div class="grid lg:grid-cols-4 gap-6">
      <!-- Filter Sidebar (Desktop) -->
      <aside class="hidden lg:block">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-24 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-[#0f3a7d]">Filtres</h2>
            <button v-if="filtersActive" @click="resetFilters" class="text-xs text-[#ff6b6b] hover:text-[#ff6b6b]/80 font-semibold">
              Réinitialiser
            </button>
          </div>

          <div class="space-y-5">
            <!-- Gender -->
            <div>
              <label class="block text-xs font-semibold text-[#0f3a7d] uppercase tracking-wide mb-2">Genre</label>
              <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input v-model="filters.gender" type="radio" value="" class="w-4 h-4 accent-[#0f3a7d]">
                  <span class="text-[#374151]">Tous</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input v-model="filters.gender" type="radio" value="femme" class="w-4 h-4 accent-[#0f3a7d]">
                  <span class="text-[#374151]">Femmes</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input v-model="filters.gender" type="radio" value="homme" class="w-4 h-4 accent-[#0f3a7d]">
                  <span class="text-[#374151]">Hommes</span>
                </label>
              </div>
            </div>

            <!-- Age Range -->
            <div class="pt-3 border-t border-gray-100">
              <label class="block text-xs font-semibold text-[#0f3a7d] uppercase tracking-wide mb-3">Âge</label>
              <div class="flex gap-2 mb-3">
                <input v-model.number="filters.ageMin" type="number" min="18" max="80" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0f3a7d]/20">
                <input v-model.number="filters.ageMax" type="number" min="18" max="80" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0f3a7d]/20">
              </div>
              <div class="text-xs text-[#8A9680]">{{ filters.ageMin }} - {{ filters.ageMax }} ans</div>
            </div>

            <!-- Location -->
            <div class="pt-3 border-t border-gray-100">
              <label class="block text-xs font-semibold text-[#0f3a7d] uppercase tracking-wide mb-2">Localisation</label>
              <input v-model="filters.location" type="text" placeholder="Ville ou pays" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0f3a7d]/20">
            </div>

            <!-- Apply Filters -->
            <button @click="loadBiodatas(1)" class="w-full mt-4 bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white font-semibold py-2 rounded-xl transition-all text-sm">
              Appliquer les filtres
            </button>
          </div>
        </div>
      </aside>

      <!-- Mobile Filter Toggle -->
      <button @click="showFilters = !showFilters" class="lg:hidden mb-4 flex items-center gap-2 px-4 py-3 bg-white border border-gray-100 rounded-xl font-semibold text-[#0f3a7d] w-full justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        Filtres
      </button>

      <!-- Mobile Filter Panel -->
      <Transition
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        enter-from-class="opacity-0 scale-95"
        leave-to-class="opacity-0 scale-95"
      >
        <div v-if="showFilters" class="lg:hidden col-span-full bg-white rounded-2xl border border-gray-100 p-6 mb-6 shadow-sm">
          <div class="space-y-4">
            <!-- Gender -->
            <div>
              <label class="block text-xs font-semibold text-[#0f3a7d] uppercase tracking-wide mb-2">Genre</label>
              <div class="flex gap-2">
                <label class="flex-1 flex items-center gap-2 text-sm cursor-pointer">
                  <input v-model="filters.gender" type="radio" value="" class="w-4 h-4 accent-[#0f3a7d]">
                  <span>Tous</span>
                </label>
                <label class="flex-1 flex items-center gap-2 text-sm cursor-pointer">
                  <input v-model="filters.gender" type="radio" value="femme" class="w-4 h-4 accent-[#0f3a7d]">
                  <span>Femmes</span>
                </label>
                <label class="flex-1 flex items-center gap-2 text-sm cursor-pointer">
                  <input v-model="filters.gender" type="radio" value="homme" class="w-4 h-4 accent-[#0f3a7d]">
                  <span>Hommes</span>
                </label>
              </div>
            </div>

            <button @click="showFilters = false; loadBiodatas(1)" class="w-full mt-4 bg-[#0f3a7d] text-white font-semibold py-2 rounded-xl text-sm">
              Appliquer
            </button>
          </div>
        </div>
      </Transition>

      <!-- Profiles Grid -->
      <div class="lg:col-span-3">
        <!-- Loading -->
        <div v-if="loading" class="grid sm:grid-cols-2 gap-6">
          <div v-for="i in 6" :key="i" class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-pulse h-96"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="!biodatas.length" class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
          <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-[#8A9680]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <p class="text-[#8A9680] font-medium mb-1">Aucun profil trouvé</p>
          <p class="text-xs text-[#8A9680] mb-6">Essayez d'ajuster vos filtres</p>
          <button @click="resetFilters" class="inline-block bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white px-6 py-2 rounded-xl text-sm font-semibold transition-all">
            Réinitialiser les filtres
          </button>
        </div>

        <!-- Profiles Grid -->
        <div v-else class="grid sm:grid-cols-2 gap-6 mb-8">
          <div v-for="biodata in biodatas" :key="biodata.id" class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all">
            <!-- Header Gradient -->
            <div class="h-1 bg-gradient-to-r from-[#0f3a7d] via-[#17a2b8] to-[#ff6b6b]"></div>

            <!-- Profile Content -->
            <div class="p-6">
              <!-- Name & Age -->
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h3 class="font-semibold text-[#0f3a7d] text-lg group-hover:text-[#17a2b8] transition-colors">
                    {{ biodata.name || 'Profil' }} <span class="text-[#8A9680]">{{ biodata.age }}</span>
                  </h3>
                  <p class="text-xs text-[#8A9680] mt-0.5">📍 {{ biodata.permanent_country || biodata.city || 'Non spécifié' }}</p>
                </div>
              </div>

              <!-- Tags -->
              <div class="flex flex-wrap gap-1.5 mb-4 text-xs">
                <span v-if="biodata.madhab" class="bg-[#ff6b6b]/10 text-[#8A6D12] px-2.5 py-1 rounded-full font-medium">{{ biodata.madhab }}</span>
                <span v-if="biodata.practice_religion" class="bg-[#0f3a7d]/10 text-[#0f3a7d] px-2.5 py-1 rounded-full font-medium">{{ biodata.practice_religion }}</span>
                <span v-if="biodata.education" class="bg-[#17a2b8]/10 text-[#17a2b8] px-2.5 py-1 rounded-full font-medium">{{ biodata.education }}</span>
              </div>

              <!-- Bio -->
              <p v-if="biodata.bio" class="text-sm text-[#6B7280] mb-4 line-clamp-2 leading-relaxed">
                {{ biodata.bio }}
              </p>

              <!-- Action Buttons -->
              <div class="flex gap-2">
                <button
                  @click="sendLike(biodata.id)"
                  :disabled="acting['like' + biodata.id]"
                  class="flex-1 flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-[#ff6b6b] font-semibold py-2.5 rounded-xl transition-all disabled:opacity-50"
                >
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                  {{ acting['like' + biodata.id] ? '...' : 'J\'aime' }}
                </button>
                <button
                  @click="sendProposal(biodata.id)"
                  :disabled="acting['proposal' + biodata.id]"
                  class="flex-1 bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white font-semibold py-2.5 rounded-xl transition-all disabled:opacity-50"
                >
                  {{ acting['proposal' + biodata.id] ? '...' : 'Demande' }}
                </button>
              </div>

              <!-- View Profile Link -->
              <Link
                :href="route('biodata.show', biodata.id)"
                class="block mt-3 text-center text-sm text-[#8A9680] hover:text-[#0f3a7d] font-medium transition-colors"
              >
                Voir le profil complet →
              </Link>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex items-center justify-center gap-2">
          <button
            @click="currentPage > 1 && loadBiodatas(currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-4 py-2 rounded-lg border border-gray-200 text-[#374151] hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
          >
            ← Précédent
          </button>

          <div class="flex gap-1">
            <button
              v-for="page in totalPages"
              :key="page"
              @click="loadBiodatas(page)"
              :class="[
                'w-10 h-10 rounded-lg font-semibold transition-all',
                page === currentPage
                  ? 'bg-[#0f3a7d] text-white'
                  : 'border border-gray-200 text-[#374151] hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
          </div>

          <button
            @click="currentPage < totalPages && loadBiodatas(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 rounded-lg border border-gray-200 text-[#374151] hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
          >
            Suivant →
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
