<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const members = ref([]);
const options = ref({ marital_statuses: [], countries: [], madhab: [], prayer_levels: [], practices: [], children_prefs: [] });
const loading = ref(true);
const error = ref('');
const page = ref(1);
const lastPage = ref(1);
const total = ref(0);
const gender = ref('');
const showFilters = ref(false);
const toast = ref(null);
let toastTimer = null;

function showToast(msg, type = 'success') {
    toast.value = { msg, type };
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => (toast.value = null), 3200);
}

const filters = ref({
    min_age: '', max_age: '', marital_status: [], country: '', madhab: '',
    prayer_level: '', practice_religion: '', min_height: '', max_height: '',
    children_pref: '', q: '', sort: 'recent',
});

const csrf = () => {
    const m = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
    if (m) { try { return decodeURIComponent(m[1]); } catch (e) { return m[1]; } }
    return '';
};

async function load() {
    loading.value = true;
    error.value = '';
    const params = new URLSearchParams({ page: page.value });
    if (filters.value.min_age) params.set('min_age', filters.value.min_age);
    if (filters.value.max_age) params.set('max_age', filters.value.max_age);
    if (filters.value.marital_status.length) params.set('marital_status', filters.value.marital_status.join(','));
    if (filters.value.country) params.set('country', filters.value.country);
    if (filters.value.madhab) params.set('madhab', filters.value.madhab);
    if (filters.value.prayer_level) params.set('prayer_level', filters.value.prayer_level);
    if (filters.value.practice_religion) params.set('practice_religion', filters.value.practice_religion);
    if (filters.value.min_height) params.set('min_height', filters.value.min_height);
    if (filters.value.max_height) params.set('max_height', filters.value.max_height);
    if (filters.value.children_pref) params.set('children_pref', filters.value.children_pref);
    if (filters.value.q) params.set('q', filters.value.q);
    if (filters.value.sort !== 'recent') params.set('sort', filters.value.sort);

    try {
        const res = await fetch('/api/members?' + params, { headers: { 'Accept': 'application/json' } });
        const j = await res.json();
        if (!res.ok) { error.value = j.error || 'Erreur'; loading.value = false; return; }
        members.value = j.members.data || [];
        page.value = j.members.current_page;
        lastPage.value = j.members.last_page;
        total.value = j.members.total;
        gender.value = j.gender;
    } catch (e) { error.value = e.message; }
    loading.value = false;
}

function resetFilters() {
    filters.value = { min_age: '', max_age: '', marital_status: [], country: '', madhab: '', prayer_level: '', practice_religion: '', min_height: '', max_height: '', children_pref: '', q: '', sort: 'recent' };
    page.value = 1;
    load();
}

async function sendProposal(userId) {
    try {
        const res = await fetch('/api/mosque/propose', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ receiver_id: userId, message: 'Salam, je souhaiterais faire votre connaissance dans le cadre du mariage.' }),
        });
        const j = await res.json();
        if (j.success) {
            showToast('Demande envoyée ✓');
            load();
        } else {
            showToast(j.error || 'Erreur', 'error');
        }
    } catch (e) { showToast('Erreur: ' + e.message, 'error'); }
}

onMounted(async () => {
    // Load options first
    try {
        const res = await fetch('/api/members/options', { headers: { 'Accept': 'application/json' } });
        options.value = await res.json();
    } catch (e) {}
    await load();
});
</script>

<template>
    <Head title="Membres de ma mosquée — Rencontre Éthique" />

    <div class="min-h-screen pb-16" style="background: linear-gradient(180deg, #FBF7F0 0%, #F5EEDD 100%)">
        <div class="pattern-re absolute inset-0 pointer-events-none"></div>

        <div class="relative max-w-6xl mx-auto px-5 pt-8">
            <!-- Toast -->
            <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-3" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                <div v-if="toast" :class="['fixed top-5 right-5 z-50 px-5 py-3 rounded-2xl shadow-xl text-sm font-semibold text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-[#0f3a7d]']">
                    {{ toast.msg }}
                </div>
            </Transition>

            <!-- Header -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="font-display text-3xl font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">
                        {{ gender === 'female' ? 'Sœurs de ma mosquée' : gender === 'male' ? 'Frères de ma mosquée' : 'Membres de ma mosquée' }}
                    </h1>
                    <p class="text-sm text-[#8A9680] mt-1">{{ total }} membre{{ total > 1 ? 's' : '' }} — uniquement votre mosquée</p>
                </div>
                <div class="flex gap-2.5">
                    <button @click="showFilters = !showFilters" :class="['btn-re px-5 py-2.5 text-sm', showFilters ? 'btn-re-primary' : 'btn-re-ghost']">
                        <svg class="w-4 h-4 mr-1.5 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/></svg>
                        Filtres
                    </button>
                    <select v-model="filters.sort" @change="load" class="field-input-re !w-40">
                        <option value="recent">Plus récents</option>
                        <option value="age_asc">Âge croissant</option>
                        <option value="age_desc">Âge décroissant</option>
                        <option value="completion">Profil complet</option>
                    </select>
                </div>
            </div>

            <!-- Search bar -->
            <div class="relative mb-5">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#8A9680]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input v-model="filters.q" @keyup.enter="page = 1; load()" placeholder="Rechercher dans les présentations..." class="field-input-re !pl-11 !h-12" />
            </div>

            <!-- Filters panel -->
            <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-2" leave-active-class="transition duration-200" leave-to-class="opacity-0 -translate-y-2">
                <div v-if="showFilters" class="card-re p-5 mb-6">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="field-label-re block mb-1.5">Âge minimum</label>
                            <input type="number" v-model="filters.min_age" class="field-input-re" placeholder="18" />
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Âge maximum</label>
                            <input type="number" v-model="filters.max_age" class="field-input-re" placeholder="45" />
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Situation familiale</label>
                            <div class="flex flex-wrap gap-1.5">
                                <button v-for="m in options.marital_statuses" :key="m" @click="filters.marital_status.includes(m) ? filters.marital_status = filters.marital_status.filter(x => x !== m) : filters.marital_status.push(m)" :class="['px-2.5 py-1.5 rounded-lg border text-[11px] font-medium transition-all', filters.marital_status.includes(m) ? 'bg-[#0f3a7d] border-[#0f3a7d] text-white' : 'bg-white border-[#E2DDD5] text-[#374151]']">{{ m }}</button>
                            </div>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Pays</label>
                            <select v-model="filters.country" class="field-input-re">
                                <option value="">Tous</option>
                                <option v-for="c in options.countries" :key="c">{{ c }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Madhab</label>
                            <select v-model="filters.madhab" class="field-input-re">
                                <option value="">Tous</option>
                                <option v-for="m in options.madhab" :key="m">{{ m }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Pratique religieuse</label>
                            <select v-model="filters.practice_religion" class="field-input-re">
                                <option value="">Tous</option>
                                <option v-for="p in options.practices" :key="p">{{ p }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Prières</label>
                            <select v-model="filters.prayer_level" class="field-input-re">
                                <option value="">Tous</option>
                                <option v-for="p in options.prayer_levels" :key="p">{{ p }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Taille min (cm)</label>
                            <input type="number" v-model="filters.min_height" class="field-input-re" placeholder="160" />
                        </div>
                        <div class="flex items-end gap-2.5">
                            <button @click="page = 1; load()" class="btn-re btn-re-primary flex-1 py-2.5 text-sm">Appliquer</button>
                            <button @click="resetFilters" class="btn-re btn-re-ghost py-2.5 text-sm">Réinitialiser</button>
                        </div>
                    </div>
                </div>
            </Transition>

            <!-- Error -->
            <div v-if="error" class="card-re p-8 text-center mb-6">
                <div class="text-4xl mb-3">🔒</div>
                <h2 class="font-display text-xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Accès limité</h2>
                <p class="text-sm text-[#8A9680] max-w-sm mx-auto">{{ error }}</p>
                <div class="flex gap-3 justify-center mt-5">
                    <Link href="/app/status" class="btn-re btn-re-primary px-6 py-2.5 text-sm">Voir mon statut</Link>
                </div>
            </div>

            <!-- Members grid -->
            <div v-else>
                <div v-if="loading" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div v-for="i in 6" :key="i" class="card-re p-5 animate-pulse">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-[#F0EDE6]"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-3 bg-[#F0EDE6] rounded w-2/3"></div>
                                <div class="h-2.5 bg-[#F0EDE6] rounded w-1/2"></div>
                            </div>
                        </div>
                        <div class="h-3 bg-[#F0EDE6] rounded mb-2"></div>
                        <div class="h-3 bg-[#F0EDE6] rounded w-3/4 mb-4"></div>
                        <div class="h-9 bg-[#F0EDE6] rounded"></div>
                    </div>
                </div>

                <div v-else-if="members.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div v-for="m in members" :key="m.id" class="card-re overflow-hidden hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                        <!-- Card top -->
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#0f3a7d] to-[#17a2b8] flex items-center justify-center text-[#E4B84A] font-display text-xl font-bold" style="font-family:'Cormorant Garamond',serif">{{ m.age || '?' }}</div>
                                        <span v-if="m.is_verified" class="absolute -bottom-1 -right-1 w-4.5 h-4.5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-[#0f3a7d]">{{ m.age }} ans</div>
                                        <div class="text-xs text-[#8A9680] capitalize">{{ m.maritial_status || '—' }}<template v-if="m.have_children && m.have_children !== 'Aucun'"> · {{ m.have_children }}</template></div>
                                    </div>
                                </div>
                                <span class="text-[10px] text-[#8A9680] bg-[#F8F6F0] px-2 py-1 rounded-lg">{{ m.permanent_country || m.permanent_city || '—' }}</span>
                            </div>

                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <span v-if="m.madhab" class="text-[10px] bg-[#ff6b6b]/10 text-[#8A6D12] px-2 py-0.5 rounded-full font-medium">🕌 {{ m.madhab }}</span>
                                <span v-if="m.practice_religion" class="text-[10px] bg-[#0f3a7d]/[.06] text-[#0f3a7d] px-2 py-0.5 rounded-full font-medium">{{ m.practice_religion }}</span>
                                <span v-if="m.height" class="text-[10px] bg-[#F0EDE6] text-[#4A5A4C] px-2 py-0.5 rounded-full font-medium">↕ {{ m.height }} cm</span>
                            </div>

                            <p v-if="m.bio" class="text-xs text-[#6B7280] leading-relaxed line-clamp-2 mb-3">{{ m.bio }}</p>
                            <p v-else-if="m.looking_for" class="text-xs text-[#6B7280] leading-relaxed line-clamp-2 mb-3 italic">« {{ m.looking_for }} »</p>

                            <div class="flex items-center justify-between text-[10px] text-[#8A9680] mb-4">
                                <span>Profil {{ m.completeness || 0 }}%</span>
                                <span v-if="m.photo_blurred" class="flex items-center gap-1"><svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3m7.53 6.53a1 1 0 0 1 0 1.414l-1.414 1.414a1 1 0 0 1-1.414 0l-1.06-1.06A9.96 9.96 0 0 1 12 18a9.96 9.96 0 0 1-3.642-.699l-1.06 1.06a1 1 0 0 1-1.414 0L4.47 16.944a1 1 0 0 1 0-1.414L5.53 14.47A9.96 9.96 0 0 1 4.83 12a9.96 9.96 0 0 1 .7-2.47L4.47 8.47a1 1 0 0 1 0-1.414l1.414-1.414a1 1 0 0 1 1.414 0l1.06 1.06A9.96 9.96 0 0 1 12 6a9.96 9.96 0 0 1 3.642.699l1.06-1.06a1 1 0 0 1 1.414 0l1.414 1.414a1 1 0 0 1 0 1.414L17.47 9.53c.469.756.7 1.605.7 2.47s-.231 1.714-.7 2.47l1.06 1.06Z"/></svg> Photo protégée</span>
                            </div>

                            <button @click="sendProposal(m.user_id)" class="w-full bg-[#0f3a7d] hover:bg-[#163828] text-white text-xs font-semibold py-2.5 rounded-xl transition-all active:scale-[.98]">
                                Envoyer une demande
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="card-re p-14 text-center">
                    <div class="text-5xl mb-4">🕌</div>
                    <h2 class="font-display text-xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Aucun membre trouvé</h2>
                    <p class="text-sm text-[#8A9680]">Essayez d'élargir vos filtres ou revenez plus tard.</p>
                    <button @click="resetFilters" class="btn-re btn-re-ghost px-6 py-2.5 text-sm mt-5">Réinitialiser les filtres</button>
                </div>

                <!-- Pagination -->
                <div v-if="lastPage > 1" class="flex items-center justify-center gap-3 mt-8">
                    <button @click="page--; load()" :disabled="page <= 1" class="btn-re btn-re-ghost px-4 py-2 text-xs" :class="{ 'opacity-40': page <= 1 }">←</button>
                    <span class="text-sm text-[#8A9680]">Page {{ page }} / {{ lastPage }}</span>
                    <button @click="page++; load()" :disabled="page >= lastPage" class="btn-re btn-re-ghost px-4 py-2 text-xs" :class="{ 'opacity-40': page >= lastPage }">→</button>
                </div>
            </div>
        </div>
    </div>
</template>
