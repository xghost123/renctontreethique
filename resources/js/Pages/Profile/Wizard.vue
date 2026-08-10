<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

defineProps({ locale: { type: String, default: 'fr' } });

document.body.classList.remove(...document.body.classList);
document.body.classList.add("frontend.wizard");

const step = ref(1);
const saving = ref(false);
const error = ref('');
const showCgu = ref(false);
const cguAccepted = ref(false);
const cguAttest = ref(false);
const toast = ref(null);
const gender = ref('male');

const data = ref({
    kounia: '', age: '', city: '', mosque_name: '', whatsapp: '', whatsappCode: '+33', nationality: '',
    permanent_country: '', relocation_acceptance: '', origine: '', spoken_langage: 'Français',
    maritial_status: '', polygamy: '', boys: 0, girls: 0, dependentchildren: '', divorce_count: '', children_details: '',
    has_tutor: '', tutorname: '', tutorphone: '', tutorphoneCode: '+33', tutoraffiliation: '',
    job: '', tall: '', ethnicity: '', body_type: '',
    salafy: '', hijra: '', practice_religion_years: 0, dress_code_text: '', scholars: '', madhab: '', prayer_level: '',
    health: '', occult: '', bio: '', looking_for: '', prohibitive_criteria: '',
});

const steps = [
    { n: 1, title: 'Identité', icon: '👤' },
    { n: 2, title: 'Famille', icon: '👨‍👩‍👧' },
    { n: 3, title: 'Apparence', icon: '✨' },
    { n: 4, title: 'Pratique', icon: '☾' },
    { n: 5, title: 'À propos', icon: '💬' },
];

const countries = [
    { name: 'France', code: '+33' }, { name: 'Belgique', code: '+32' }, { name: 'Suisse', code: '+41' },
    { name: 'Royaume-Uni', code: '+44' }, { name: 'Allemagne', code: '+49' }, { name: 'Pays-Bas', code: '+31' },
    { name: 'Espagne', code: '+34' }, { name: 'Italie', code: '+39' }, { name: 'Canada', code: '+1' },
    { name: 'États-Unis', code: '+1' }, { name: 'Maroc', code: '+212' }, { name: 'Algérie', code: '+213' },
    { name: 'Tunisie', code: '+216' }, { name: 'Libye', code: '+218' }, { name: 'Égypte', code: '+20' },
    { name: 'Arabie Saoudite', code: '+966' }, { name: 'Émirats Arabes Unis', code: '+971' }, { name: 'Qatar', code: '+974' },
    { name: 'Jordanie', code: '+962' }, { name: 'Yémen', code: '+967' }, { name: 'Sénégal', code: '+221' },
    { name: 'Mali', code: '+223' }, { name: 'Burkina Faso', code: '+226' }, { name: 'Cameroun', code: '+237' },
    { name: 'Nigéria', code: '+234' }, { name: 'Indonésie', code: '+62' }, { name: 'Pakistan', code: '+92' },
    { name: 'Turquie', code: '+90' },
];

const ethnicities = ['Caucasien(ne)', 'Arabe', 'Berbère', 'Asiatique', 'Hispanique', 'Africain(e)', 'Métis(se)'];
const bodyTypes = ['Normal', 'Mince', 'Maigre', 'Surpoids'];
const salafyOptions = ['Oui', 'Non', 'Pas encore décidé(e)'];
const hijraOptions = ['Court terme', 'Long terme', 'Déjà dans un pays musulman', 'Non planifié'];
const madhabs = ['Hanafi', 'Maliki', "Shafi'i", 'Hanbali', 'Sans préférence'];
const prayerLevels = ['Pratiquant', 'Assidu aux 5 prières', 'Occasionnel', 'En chemin'];

function pill(active) {
    return active
        ? 'border-2 border-[#0f3a7d] bg-[#0f3a7d] text-white px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all'
        : 'border-2 border-[#E5E7EB] bg-white text-[#6B7280] px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all hover:border-[#ff6b6b]';
}

const completion = computed(() => {
    const total = Object.keys(data.value).length;
    let done = 0;
    for (const [k, v] of Object.entries(data.value)) {
        if (Array.isArray(v) ? v.length : (v !== '' && v !== null && v !== undefined && v !== 0 && v !== '0')) done++;
    }
    return Math.round((done / total) * 100);
});

const maleMarital = ['Célibataire', 'Marié', 'Divorcé', 'Veuf'];
const femaleMarital = ['Célibataire', 'Divorcée', 'Veuve'];

const maritalOptions = computed(() => gender.value === 'male' ? maleMarital : femaleMarital);

function showToast(msg, type = 'success') {
    toast.value = { msg, type };
    setTimeout(() => (toast.value = null), 3000);
}

async function saveCurrentStep() {
    saving.value = true;
    error.value = '';
    // Build the payload (strip code prefixes for composite phone fields)
    const payload = { ...data.value };
    if (payload.whatsapp) payload.whatsapp = payload.whatsappCode + ' ' + payload.whatsapp;
    if (payload.tutorphone) payload.tutorphone = payload.tutorphoneCode + ' ' + payload.tutorphone;
    try {
        const res = await fetch('/api/profile/save-step', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ step: step.value, data: payload }),
        });
        if (!res.ok) {
            const j = await res.json().catch(() => ({}));
            throw new Error(j.error || j.message || 'Erreur');
        }
        return await res.json();
    } catch (e) {
        error.value = e.message;
        return null;
    } finally {
        saving.value = false;
    }
}

async function next() {
    // Step 1 validation: age >= 18
    if (step.value === 1 && data.value.age && parseInt(data.value.age) < 18) {
        error.value = 'Vous devez avoir au moins 18 ans';
        return;
    }
    // Step 1: WhatsApp required for men
    if (step.value === 1 && gender.value === 'male' && !data.value.whatsapp) {
        error.value = 'Le numéro WhatsApp est obligatoire';
        return;
    }
    const r = await saveCurrentStep();
    if (!r) return;
    if (step.value < 5) { step.value++; window.scrollTo(0, 0); }
    else showCgu.value = true;
}

function prev() { if (step.value > 1) { step.value--; window.scrollTo(0, 0); } }

async function submitProfile() {
    saving.value = true;
    error.value = '';
    try {
        const res = await fetch('/api/profile/submit', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ cgu_accepted: cguAccepted.value, attest: cguAttest.value }),
        });
        const j = await res.json();
        if (!res.ok) { error.value = j.error || 'Erreur lors de la soumission'; return; }
        showToast('Profil soumis ✓');
        setTimeout(() => (window.location.href = '/app/status'), 1200);
    } catch (e) { error.value = e.message; }
    finally { saving.value = false; }
}

function goToStep(n) {
    if (n < step.value) { step.value = n; window.scrollTo(0, 0); }
}

onMounted(async () => {
    try {
        const res = await fetch('/api/profile/state', { headers: { 'Accept': 'application/json' } });
        const j = await res.json();
        if (j.user?.email) {
            const userRes = await fetch('/api/me', { headers: { 'Accept': 'application/json' } }).catch(() => null);
        }
        if (j.biodata) {
            Object.assign(data.value, { ...j.biodata, whatsapp: '', tutorphone: '' });
            if (j.biodata.whatsapp) {
                const m = String(j.biodata.whatsapp).match(/^(\+\d+)\s*(.*)$/);
                if (m) { data.value.whatsappCode = m[1]; data.value.whatsapp = m[2]; }
            }
            if (j.biodata.tutorphone) {
                const m = String(j.biodata.tutorphone).match(/^(\+\d+)\s*(.*)$/);
                if (m) { data.value.tutorphoneCode = m[1]; data.value.tutorphone = m[2]; }
            }
            if (j.biodata.gender) gender.value = j.biodata.gender;
            if (j.status === 'pending_approval' || j.status === 'active' || j.status === 'rejected') {
                // still allow edit via /app/profile/edit; status page shows the state
            }
        }
    } catch (e) {}
});
</script>

<template>
    <Head title="Créer mon profil — Rencontre Éthique" />

    <div class="min-h-screen" style="background: #FBF7F0">
        <div class="pattern-re absolute inset-0 pointer-events-none"></div>

        <!-- Sticky header -->
        <header class="sticky top-0 z-30 bg-[#FBF7F0]/95 backdrop-blur-sm border-b border-[#E8E4DA]">
            <div class="max-w-lg mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#0f3a7d] flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#E4B84A]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5a7.5 7.5 0 0 0-7.5 7.5c0 5.2 6.2 10.6 7.5 10.6s7.5-5.4 7.5-10.6A7.5 7.5 0 0 0 12 1.5Z"/></svg>
                    </div>
                    <h1 class="font-display text-xl font-medium text-[#0f3a7d] tracking-wide" style="font-family:'Cormorant Garamond',serif">Créer mon profil</h1>
                </div>
                <form method="POST" action="/logout">
                    <input type="hidden" name="_token" :value="document.querySelector('meta[name=csrf-token]')?.content || ''" />
                    <button class="text-xs text-[#8A9680] hover:text-[#0f3a7d] flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
            <!-- Progress bar -->
            <div class="max-w-lg mx-auto px-4 pb-3">
                <div class="h-1 w-full bg-[#E8E4DA] rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#0f3a7d] to-[#ff6b6b] rounded-full transition-all duration-500" :style="{ width: Math.round(step / 5 * 100) + '%' }"></div>
                </div>
                <!-- Step dots -->
                <div class="flex justify-between mt-2.5">
                    <div v-for="s in steps" :key="s.n" class="flex flex-col items-center gap-1 cursor-pointer" @click="goToStep(s.n)">
                        <div :class="['w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold transition-all', step > s.n ? 'bg-[#ff6b6b] text-white' : step === s.n ? 'bg-[#0f3a7d] text-white shadow-md' : 'bg-[#E8E4DA] text-[#9CA3AF]']">
                            <svg v-if="step > s.n" class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            <span v-else>{{ s.n }}</span>
                        </div>
                        <span :class="['text-[9px] font-medium', step === s.n ? 'text-[#0f3a7d]' : 'text-[#9CA3AF]']">{{ s.title }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Toast -->
        <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-4" leave-active-class="transition duration-200" leave-to-class="opacity-0">
            <div v-if="toast" :class="['fixed top-4 right-4 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-semibold text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-[#0f3a7d]']">{{ toast.msg }}</div>
        </Transition>

        <!-- Error -->
        <div v-if="error" class="max-w-lg mx-auto px-4 mt-4">
            <div class="flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                {{ error }}
            </div>
        </div>

        <!-- Wizard card -->
        <div class="max-w-lg mx-auto px-4 py-6 pb-36">
            <div class="bg-white rounded-2xl shadow-sm border border-[#E8E4DA] overflow-hidden">
                <div class="px-5 pt-5 pb-2">
                    <div class="text-[10px] text-[#ff6b6b] font-semibold uppercase tracking-widest mb-1">Étape {{ step }} sur 5</div>
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-2xl font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">{{ steps[step - 1].title }}</h2>
                        <div class="text-3xl">{{ steps[step - 1].icon }}</div>
                    </div>
                </div>

                <div class="px-5 py-4 space-y-5">
                    <!-- ═══ STEP 1 : IDENTITÉ ═══ -->
                    <div v-if="step === 1" class="space-y-5">
                        <div>
                            <div class="section-label mb-2.5">Contact & localisation</div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="field-label-re block mb-1.5">Âge <span class="text-[#ff6b6b]">*</span></label>
                                    <input type="number" v-model="data.age" min="18" max="99" class="field-input-re !w-24 text-center" placeholder="25" />
                                    <span class="text-[11px] text-[#ff6b6b] block mt-1">Vous devez avoir 18 ans ou plus.</span>
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Kounia / Pseudo <span class="text-[#9CA3AF] font-normal">(optionnel)</span></label>
                                    <input type="text" v-model="data.kounia" class="field-input-re" placeholder="Ex : Abou Ibrahim, Oum Salma…" />
                                </div>
                                <div class="col-span-2">
                                    <label class="field-label-re block mb-1.5">Ville <span class="text-[#ff6b6b]">*</span></label>
                                    <input type="text" v-model="data.city" class="field-input-re" placeholder="Ex : Paris, Lyon, Bruxelles..." />
                                </div>
                                <div class="col-span-2">
                                    <label class="field-label-re block mb-1.5">Mosquée fréquentée <span class="text-[#ff6b6b]">*</span></label>
                                    <input type="text" v-model="data.mosque_name" class="field-input-re" placeholder="Ex : Mosquée de Paris, Mosquée Al-Fath..." />
                                </div>
                                <div class="col-span-2">
                                    <label class="field-label-re block mb-1.5">WhatsApp <span class="text-[#ff6b6b]">*</span> <span v-if="gender === 'female'" class="text-[#9CA3AF] font-normal">(optionnel)</span></label>
                                    <div class="flex gap-2">
                                        <select v-model="data.whatsappCode" class="field-input-re !w-32">
                                            <option v-for="c in countries" :key="c.name" :value="c.code">{{ c.name }} {{ c.code }}</option>
                                        </select>
                                        <input type="tel" v-model="data.whatsapp" class="field-input-re" placeholder="6 12 34 56 78" />
                                    </div>
                                    <span class="text-[11px] text-[#9CA3AF] block mt-1">Visible uniquement par l'administration</span>
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Nationalité <span class="text-[#ff6b6b]">*</span></label>
                                    <input type="text" v-model="data.nationality" class="field-input-re" placeholder="Ex : Française, Marocaine..." />
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Pays de résidence <span class="text-[#ff6b6b]">*</span></label>
                                    <select v-model="data.permanent_country" class="field-input-re">
                                        <option value="">— Sélectionnez votre pays —</option>
                                        <option v-for="c in countries" :key="c.name">{{ c.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Pays d'origine <span class="text-[#ff6b6b]">*</span></label>
                                    <select v-model="data.origine" class="field-input-re">
                                        <option value="">— Sélectionnez votre pays d'origine —</option>
                                        <option v-for="c in countries" :key="c.name">{{ c.name }}</option>
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="field-label-re block mb-1.5">Accepte de domicilier <span class="text-[#ff6b6b]">*</span></label>
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <button @click="data.relocation_acceptance = 'Oui'" :class="pill(data.relocation_acceptance === 'Oui')">Oui</button>
                                        <button @click="data.relocation_acceptance = 'Non'" :class="pill(data.relocation_acceptance === 'Non')">Non</button>
                                        <button @click="data.relocation_acceptance = 'A proximite'" :class="pill(data.relocation_acceptance === 'A proximite')">À proximité</button>
                                        <button @click="data.relocation_acceptance = 'A discuter'" :class="pill(data.relocation_acceptance === 'A discuter')">À discuter</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Langue principale <span class="text-[#ff6b6b]">*</span></label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button @click="data.spoken_langage = 'Français'" :class="pill(data.spoken_langage === 'Français')">Français</button>
                                        <button @click="data.spoken_langage = 'Anglais'" :class="pill(data.spoken_langage === 'Anglais')">Anglais</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ STEP 2 : FAMILLE ═══ -->
                    <div v-else-if="step === 2" class="space-y-5">
                        <div>
                            <div class="section-label mb-2.5">Situation matrimoniale</div>
                            <label class="field-label-re block mb-1.5">Situation matrimoniale <span class="text-[#ff6b6b]">*</span></label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button v-for="m in maritalOptions" :key="m" @click="data.maritial_status = m.toLowerCase()" :class="pill(data.maritial_status === m.toLowerCase())">{{ m }}</button>
                            </div>
                        </div>

                        <!-- Divorce Count (if divorced) -->
                        <div v-if="data.maritial_status === 'divorcé' || data.maritial_status === 'divorced'">
                            <label class="field-label-re block mb-1.5">Si divorcé(e), combien de fois ? <span class="text-[#9CA3AF] font-normal">(optionnel)</span></label>
                            <input type="number" v-model="data.divorce_count" min="1" max="10" class="field-input-re text-center" placeholder="Ex : 1, 2, 3..." />
                        </div>

                        <div v-if="gender === 'female'">
                            <label class="field-label-re block mb-1.5">Envisagez-vous la polygamie ? <span class="text-[#ff6b6b]">*</span></label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button @click="data.polygamy = 'yes'" :class="pill(data.polygamy === 'yes')">Oui</button>
                                <button @click="data.polygamy = 'no'" :class="pill(data.polygamy === 'no')">Non</button>
                            </div>
                        </div>

                        <div>
                            <div class="section-label mb-2.5">Enfants</div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="field-label-re block mb-1.5">Nombre de garçons</label>
                                    <input type="number" v-model="data.boys" min="0" max="20" class="field-input-re text-center" />
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Nombre de filles</label>
                                    <input type="number" v-model="data.girls" min="0" max="20" class="field-input-re text-center" />
                                </div>
                            </div>
                            <div v-if="(data.boys > 0 || data.girls > 0)" class="mt-3 bg-[#FEFCF7] border border-[#E8E4DA] rounded-xl p-4 space-y-3">
                                <div>
                                    <label class="field-label-re block mb-1.5">Avez-vous des enfants à charge ? <span class="text-[#ff6b6b]">*</span></label>
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <button @click="data.dependentchildren = 'Oui'" :class="pill(data.dependentchildren === 'Oui')">Oui</button>
                                        <button @click="data.dependentchildren = 'Non'" :class="pill(data.dependentchildren === 'Non')">Non</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Précisez leurs âges <span class="text-[#ff6b6b]">*</span></label>
                                    <input type="text" v-model="data.children_details" class="field-input-re" placeholder="Ex : 3 ans, 7 ans, 12 ans" />
                                </div>
                            </div>
                        </div>

                        <div v-if="gender === 'female'">
                            <div class="section-label mb-2.5">Tuteur (Wali)</div>
                            <label class="field-label-re block mb-1.5">Avez-vous un tuteur (wali) ? <span class="text-[#ff6b6b]">*</span></label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button @click="data.has_tutor = true" :class="pill(data.has_tutor === true)">Oui</button>
                                <button @click="data.has_tutor = false" :class="pill(data.has_tutor === false)">Non</button>
                            </div>
                            <div v-if="data.has_tutor === true" class="mt-3 bg-[#FEFCF7] rounded-xl p-4 border border-[#E8E4DA] space-y-3">
                                <div>
                                    <label class="field-label-re block mb-1.5">Nom du tuteur <span class="text-[#ff6b6b]">*</span></label>
                                    <input type="text" v-model="data.tutorname" class="field-input-re" placeholder="Nom complet du tuteur" />
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Téléphone du tuteur <span class="text-[#ff6b6b]">*</span></label>
                                    <div class="flex gap-2">
                                        <select v-model="data.tutorphoneCode" class="field-input-re !w-32">
                                            <option v-for="c in countries" :key="c.name" :value="c.code">{{ c.name }} {{ c.code }}</option>
                                        </select>
                                        <input type="tel" v-model="data.tutorphone" class="field-input-re" placeholder="Numéro de téléphone du tuteur" />
                                    </div>
                                </div>
                                <div>
                                    <label class="field-label-re block mb-1.5">Affiliation du tuteur <span class="text-[#ff6b6b]">*</span></label>
                                    <input type="text" v-model="data.tutoraffiliation" class="field-input-re" placeholder="Ex : Mosquée, famille, association..." />
                                </div>
                            </div>
                            <div v-else-if="data.has_tutor === false" class="mt-3 rounded-xl border border-[#ff6b6b]/40 bg-[#FEFCF0] p-4">
                                <div class="text-[#8A6D12] text-xs leading-relaxed">
                                    ☾ Un tuteur est requis pour un mariage islamique. Sans tuteur, nous ne pouvons vous mettre en relation avec un prétendant avec qui vous souhaitez aller plus loin dans les démarches. Discutez-en avec votre prétendant pour trouver une solution.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ STEP 3 : APPARE NCE ═══ -->
                    <div v-else-if="step === 3" class="space-y-5">
                        <div>
                            <div class="section-label mb-2.5">Profession</div>
                            <label class="field-label-re block mb-1.5">Votre métier <span class="text-[#ff6b6b]">*</span></label>
                            <input type="text" v-model="data.job" class="field-input-re" placeholder="Ex : Infirmière, Ingénieur..." />
                        </div>
                        <div>
                            <div class="section-label mb-2.5">Apparence physique</div>
                            <label class="field-label-re block mb-1.5">Votre taille <span class="text-[#ff6b6b]">*</span></label>
                            <input type="text" v-model="data.tall" class="field-input-re" placeholder="Ex : 175 cm, 5'9&quot;, 5 ft 9 in…" />
                            <span class="text-[11px] text-[#9CA3AF] block mt-1">Nous vous demandons votre taille, pas votre poids</span>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Ethnicité <span class="text-[#ff6b6b]">*</span></label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button v-for="e in ethnicities" :key="e" @click="data.ethnicity = e" :class="pill(data.ethnicity === e)">{{ e }}</button>
                            </div>
                        </div>
                        <div v-if="gender === 'male'">
                            <label class="field-label-re block mb-1.5">Morphologie <span class="text-[#ff6b6b]">*</span></label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button v-for="b in bodyTypes" :key="b" @click="data.body_type = b" :class="pill(data.body_type === b)">{{ b }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ STEP 4 : PRATIQUE ═══ -->
                    <div v-else-if="step === 4" class="space-y-5">
                        <div>
                            <div class="section-label mb-2.5">Pratique religieuse</div>
                            <label class="field-label-re block mb-1.5">Suivez-vous le minhaj salafi ? <span class="text-[#ff6b6b]">*</span></label>
                            <div class="space-y-2.5">
                                <button v-for="s in salafyOptions" :key="s" @click="data.salafy = s.toLowerCase()" :class="['w-full text-left px-4 py-3 rounded-xl border-2 text-sm font-medium transition-all', data.salafy === s.toLowerCase() ? 'border-[#0f3a7d] bg-[#0f3a7d] text-white' : 'border-[#E5E7EB] bg-white text-[#6B7280] hover:border-[#ff6b6b]']">{{ s }}</button>
                            </div>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Projet Hijra <span class="text-[#ff6b6b]">*</span></label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button v-for="h in hijraOptions" :key="h" @click="data.hijra = h" :class="pill(data.hijra === h)">{{ h }}</button>
                            </div>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Pratique religieuse sérieuse depuis (en années) <span class="text-[#ff6b6b]">*</span></label>
                            <input type="number" v-model="data.practice_religion_years" min="0" max="80" class="field-input-re !w-24 text-center" placeholder="0" />
                            <span class="text-[11px] text-[#9CA3AF] ml-1">ans</span>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Madhab <span class="text-[#9CA3AF] font-normal">(optionnel)</span></label>
                            <select v-model="data.madhab" class="field-input-re">
                                <option value="">—</option>
                                <option v-for="m in madhabs" :key="m">{{ m }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Votre tenue vestimentaire <span class="text-[#ff6b6b]">*</span></label>
                            <textarea v-model="data.dress_code_text" rows="3" class="field-input-re !h-auto !py-3 resize-none" placeholder="Décrivez votre manière de vous habiller..."></textarea>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Savants / imams que vous suivez <span class="text-[#ff6b6b]">*</span></label>
                            <textarea v-model="data.scholars" rows="2" class="field-input-re !h-auto !py-3 resize-none" placeholder="Ex : Ibn Baz, Al-Uthaymin..."></textarea>
                        </div>
                    </div>

                    <!-- ═══ STEP 5 : À PROPOS ═══ -->
                    <div v-else class="space-y-5">
                        <div>
                            <div class="section-label mb-2.5">Santé</div>
                            <label class="field-label-re block mb-1.5">Votre santé physique et morale <span class="text-[#ff6b6b]">*</span></label>
                            <textarea v-model="data.health" rows="3" class="field-input-re !h-auto !py-3 resize-none" placeholder="Décrivez votre état de santé physique et moral..."></textarea>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Maladie occulte <span class="text-[#ff6b6b]">*</span></label>
                            <textarea v-model="data.occult" rows="2" class="field-input-re !h-auto !py-3 resize-none" placeholder="Sihr, envoutement, djinn... Mentionnez si applicable."></textarea>
                        </div>
                        <div>
                            <div class="section-label mb-2.5">Présentation</div>
                            <label class="field-label-re block mb-1.5">Qui êtes-vous ? <span class="text-[#ff6b6b]">*</span></label>
                            <textarea v-model="data.bio" rows="5" class="field-input-re !h-auto !py-3 resize-none" placeholder="Parlez de vous, de votre personnalité, de vos valeurs..."></textarea>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Ce que vous cherchez chez le/la prétendant(e) <span class="text-[#ff6b6b]">*</span></label>
                            <textarea v-model="data.looking_for" rows="4" class="field-input-re !h-auto !py-3 resize-none" placeholder="Décrivez le profil que vous recherchez..."></textarea>
                        </div>
                        <div>
                            <label class="field-label-re block mb-1.5">Vos critères rédhibitoires <span class="text-[#ff6b6b]">*</span></label>
                            <textarea v-model="data.prohibitive_criteria" rows="3" class="field-input-re !h-auto !py-3 resize-none" placeholder="Ce qui est absolument rédhibitoire pour vous..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-[11px] text-[#9CA3AF] mt-3">✓ Brouillon sauvegardé automatiquement</div>
        </div>

        <!-- Sticky bottom actions -->
        <div class="fixed bottom-0 left-0 right-0 z-30 bg-[#FBF7F0]/95 backdrop-blur-sm border-t border-[#E8E4DA] px-4 py-3" style="padding-bottom: max(12px, env(safe-area-inset-bottom))">
            <div class="max-w-lg mx-auto flex gap-3">
                <button v-if="step > 1" @click="prev" class="btn-re btn-re-ghost px-6 py-3.5 text-sm">← Précédent</button>
                <button @click="next" :disabled="saving" class="btn-re btn-re-primary flex-1 py-3.5 text-sm" :class="{ 'opacity-60': saving }">
                    {{ saving ? 'Enregistrement...' : (step < 5 ? 'Suivant →' : 'Valider & Continuer →') }}
                </button>
            </div>
        </div>

        <!-- ═══ CGU OVERLAY ═══ -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                <div v-if="showCgu" class="fixed inset-0 z-50 bg-[#FBF7F0] flex flex-col">
                    <div class="max-w-lg mx-auto w-full px-4 pt-5 flex items-center gap-3">
                        <button @click="showCgu = false" class="btn-re btn-re-ghost px-4 py-2 text-sm">← Retour</button>
                        <div>
                            <h2 class="font-display text-2xl font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">Conditions Générales d'Utilisation</h2>
                            <p class="text-xs text-[#8A9680]">Lisez attentivement avant de valider votre profil</p>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto max-w-lg mx-auto w-full px-4 py-5">
                        <div class="bg-white rounded-2xl border border-[#E8E4DA] p-6 text-sm text-[#374151] space-y-4">
                            <div>
                                <div class="font-semibold text-[#0f3a7d] mb-2">1. Objet de la plateforme</div>
                                <p class="text-xs leading-relaxed text-[#6B7280]">Rencontre Éthique est une plateforme de rencontre matrimoniale islamique. Les profils sont validés par l'imam ou un modérateur de la mosquée du membre. L'utilisation de la plateforme est strictement réservée aux personnes majeures (18 ans et plus) cherchant le mariage.</p>
                            </div>
                            <div>
                                <div class="font-semibold text-[#0f3a7d] mb-2">2. Sincérité des informations</div>
                                <p class="text-xs leading-relaxed text-[#6B7280]">Chaque membre atteste devant Allah que les informations fournies sont sincères et exactes. Toute fausse déclaration peut entraîner la suspension définitive du profil.</p>
                            </div>
                            <div>
                                <div class="font-semibold text-[#0f3a7d] mb-2">3. Respect et bienséance</div>
                                <p class="text-xs leading-relaxed text-[#6B7280]">Les échanges doivent se dérouler dans le respect des règles de bienséance islamique. Tout comportement inapproprié entraîne la modération du profil et des échanges.</p>
                            </div>
                            <div>
                                <div class="font-semibold text-[#0f3a7d] mb-2">4. Confidentialité</div>
                                <p class="text-xs leading-relaxed text-[#6B7280]">Vos informations ne sont visibles que par les membres approuvés de votre mosquée et par l'administration. Les coordonnées (WhatsApp, téléphone) ne sont visibles que par l'administration.</p>
                            </div>
                        </div>
                        <div class="mt-5 space-y-3.5">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" v-model="cguAccepted" class="w-5 h-5 rounded accent-[#0f3a7d] mt-0.5" />
                                <span class="text-sm text-[#374151]">J'ai lu et j'accepte les Conditions Générales d'Utilisation</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" v-model="cguAttest" class="w-5 h-5 rounded accent-[#0f3a7d] mt-0.5" />
                                <span class="text-sm text-[#374151]">J'atteste devant Allah que les informations transmises sont sincères et exactes</span>
                            </label>
                        </div>
                        <div v-if="error" class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ error }}</div>
                        <button @click="submitProfile" :disabled="saving || !cguAccepted || !cguAttest" class="btn-re btn-re-primary w-full py-4 mt-5 text-sm" :class="{ 'opacity-50': !cguAccepted || !cguAttest }">
                            {{ saving ? 'Chargement...' : 'Soumettre mon profil' }}
                        </button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.pattern-re {
    background-image:
        radial-gradient(circle at 25% 25%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px),
        radial-gradient(circle at 75% 75%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px),
        radial-gradient(circle at 75% 25%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px),
        radial-gradient(circle at 25% 75%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px);
    background-size: 60px 60px;
}
.section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #0f3a7d;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .12em;
}
.section-label::before {
    content: '';
    width: 2px;
    height: 18px;
    background: #ff6b6b;
    border-radius: 999px;
}
.pill-class {
    border: 2px solid #E5E7EB;
    background: #fff;
    color: #6B7280;
    padding: 8px 0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    transition: all .15s;
}
.pill-class:hover { border-color: #ff6b6b; }
.pill-active {
    border-color: #0f3a7d;
    background: #0f3a7d;
    color: #fff;
}
</style>
