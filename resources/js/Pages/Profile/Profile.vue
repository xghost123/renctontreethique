<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

const biodata = ref(null);
const status = ref('loading');
const completion = ref(0);
const loading = ref(true);

onMounted(async () => {
    try {
        const res = await fetch('/api/profile/state', { headers: { 'Accept': 'application/json' } });
        const j = await res.json();
        biodata.value = j.biodata;
        completion.value = j.completion;
        status.value = j.status;
    } catch (e) {
        status.value = 'error';
    } finally {
        loading.value = false;
    }
});

const profileFields = computed(() => {
    if (!biodata.value) return [];
    return [
        { label: 'Identité', icon: '👤', fields: [
            { key: 'kounia', label: 'Kounia', value: biodata.value.kounia },
            { key: 'age', label: 'Âge', value: biodata.value.age },
            { key: 'gender', label: 'Sexe', value: biodata.value.gender },
            { key: 'city', label: 'Ville', value: biodata.value.city },
            { key: 'nationality', label: 'Nationalité', value: biodata.value.nationality },
        ]},
        { label: 'Résidence', icon: '🌍', fields: [
            { key: 'permanent_country', label: 'Pays de résidence', value: biodata.value.permanent_country },
            { key: 'origine', label: 'Pays d\'origine', value: biodata.value.origine },
            { key: 'spoken_langage', label: 'Langue', value: biodata.value.spoken_langage },
        ]},
        { label: 'Famille', icon: '👨‍👩‍👧', fields: [
            { key: 'maritial_status', label: 'Situation matrimoniale', value: biodata.value.maritial_status },
            { key: 'boys', label: 'Garçons', value: biodata.value.boys || 0 },
            { key: 'girls', label: 'Filles', value: biodata.value.girls || 0 },
            { key: 'dependentchildren', label: 'Enfants à charge', value: biodata.value.dependentchildren },
        ]},
        { label: 'Profession & Apparence', icon: '✨', fields: [
            { key: 'job', label: 'Métier', value: biodata.value.job },
            { key: 'tall', label: 'Taille', value: biodata.value.tall },
            { key: 'ethnicity', label: 'Ethnicité', value: biodata.value.ethnicity },
            { key: 'body_type', label: 'Morphologie', value: biodata.value.body_type },
        ]},
        { label: 'Pratique religieuse', icon: '☾', fields: [
            { key: 'salafy', label: 'Minhaj salafi', value: biodata.value.salafy },
            { key: 'hijra', label: 'Projet hijra', value: biodata.value.hijra },
            { key: 'practice_religion_years', label: 'Pratique sérieuse depuis', value: biodata.value.practice_religion_years ? biodata.value.practice_religion_years + ' ans' : '-' },
            { key: 'madhab', label: 'Madhab', value: biodata.value.madhab || '-' },
            { key: 'prayer_level', label: 'Niveau de prière', value: biodata.value.prayer_level || '-' },
        ]},
        { label: 'Biographie', icon: '💬', fields: [
            { key: 'bio', label: 'Qui êtes-vous', value: biodata.value.bio, multiline: true },
            { key: 'looking_for', label: 'Ce que vous cherchez', value: biodata.value.looking_for, multiline: true },
            { key: 'health', label: 'Santé', value: biodata.value.health, multiline: true },
        ]}
    ];
});
</script>

<template>
    <Head title="Mon profil — Rencontre Éthique" />

    <div class="min-h-screen" style="background: linear-gradient(160deg, #FBF7F0 0%, #F5EEDD 50%, #F0E6D0 100%)">
        <div class="pattern-re absolute inset-0 pointer-events-none"></div>

        <!-- Header -->
        <header class="sticky top-0 z-30 bg-[#FBF7F0]/95 backdrop-blur-sm border-b border-[#E8E4DA]">
            <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link href="/app/status" class="text-[#8A9680] hover:text-[#0f3a7d] transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </Link>
                    <div>
                        <h1 class="font-display text-xl font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">Mon profil</h1>
                        <p class="text-xs text-[#8A9680]">Consultez votre biographie</p>
                    </div>
                </div>
                <Link href="/profile/edit" class="btn-re btn-re-primary px-4 py-2.5 text-xs">
                    ✎ Modifier
                </Link>
            </div>
        </header>

        <div class="max-w-4xl mx-auto px-4 py-8 pb-20">
            <!-- Loading state -->
            <div v-if="loading" class="card-re p-8 text-center">
                <div class="animate-spin w-8 h-8 border-3 border-[#0f3a7d] border-t-transparent rounded-full mx-auto mb-4"></div>
                <p class="text-sm text-[#8A9680]">Chargement du profil...</p>
            </div>

            <!-- No profile -->
            <div v-else-if="!biodata" class="card-re p-8 text-center">
                <div class="text-5xl mb-4">👤</div>
                <h2 class="font-display text-2xl font-medium text-[#0f3a7d] mb-3" style="font-family:'Cormorant Garamond',serif">Aucun profil</h2>
                <p class="text-sm text-[#8A9680] mb-5">Créez votre profil pour commencer.</p>
                <Link href="/profile/edit" class="btn-re btn-re-primary inline-block px-6 py-3 text-sm">
                    Créer mon profil →
                </Link>
            </div>

            <!-- Profile display -->
            <div v-else class="space-y-6 re-fade-up">
                <!-- Completion bar -->
                <div class="card-re p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-[#0f3a7d]">Complétude du profil</span>
                        <span class="text-lg font-bold text-[#ff6b6b]">{{ completion }}%</span>
                    </div>
                    <div class="h-3 bg-[#E8E4DA] rounded-full overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-[#0f3a7d] to-[#ff6b6b] rounded-full transition-all duration-500"
                            :style="{ width: completion + '%' }"
                        ></div>
                    </div>
                    <p class="text-xs text-[#8A9680] mt-3">
                        {{ completion >= 100 ? '✓ Profil complet' : completion >= 80 ? '✓ Profil validable' : 'Complétez votre profil pour soumettre' }}
                    </p>
                </div>

                <!-- Profile sections -->
                <div v-for="section in profileFields" :key="section.label" class="card-re overflow-hidden">
                    <div class="px-6 py-4 bg-[#FEFCF7] border-b border-[#E8E4DA]">
                        <div class="flex items-center gap-2.5">
                            <span class="text-2xl">{{ section.icon }}</span>
                            <h3 class="font-display text-lg font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">{{ section.label }}</h3>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div v-for="field in section.fields" :key="field.key" class="min-h-16">
                                <div class="text-xs font-semibold text-[#ff6b6b] uppercase tracking-wide mb-1">{{ field.label }}</div>
                                <div v-if="field.multiline" class="text-sm text-[#374151] leading-relaxed whitespace-pre-wrap max-h-20 overflow-y-auto">
                                    {{ field.value || '—' }}
                                </div>
                                <div v-else class="text-sm font-medium text-[#0f3a7d]">
                                    {{ field.value || '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photos section (if Photo component exists) -->
                <div class="card-re overflow-hidden">
                    <div class="px-6 py-4 bg-[#FEFCF7] border-b border-[#E8E4DA]">
                        <div class="flex items-center gap-2.5">
                            <span class="text-2xl">📸</span>
                            <h3 class="font-display text-lg font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">Photos</h3>
                        </div>
                    </div>
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-[#8A9680] mb-4">Visitez la page d'édition pour gérer vos photos</p>
                        <Link href="/profile/edit" class="btn-re btn-re-ghost inline-block px-5 py-2.5 text-xs">
                            Gérer les photos
                        </Link>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 sticky bottom-0 bg-[#FBF7F0]/95 backdrop-blur-sm border-t border-[#E8E4DA] px-4 py-3 mt-6 -mx-4">
                    <Link href="/app/status" class="btn-re btn-re-ghost flex-1 py-3 text-sm">
                        ← Retour au statut
                    </Link>
                    <Link href="/profile/edit" class="btn-re btn-re-primary flex-1 py-3 text-sm">
                        Modifier le profil →
                    </Link>
                </div>
            </div>
        </div>
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

.card-re {
    background: white;
    border: 1px solid #E8E4DA;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.card-re:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.btn-re {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border-radius: 10px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    border: none;
    font-size: inherit;
}

.btn-re-primary {
    background: linear-gradient(135deg, #0f3a7d 0%, #17a2b8 100%);
    color: white;
}

.btn-re-primary:hover:not(:disabled) {
    box-shadow: 0 6px 20px rgba(15, 58, 125, 0.3);
    transform: translateY(-2px);
}

.btn-re-ghost {
    background: transparent;
    color: #0f3a7d;
    border: 1.5px solid #E8E4DA;
}

.btn-re-ghost:hover:not(:disabled) {
    border-color: #ff6b6b;
    color: #ff6b6b;
}

.re-fade-up {
    animation: fadeUp 0.4s ease-out;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
