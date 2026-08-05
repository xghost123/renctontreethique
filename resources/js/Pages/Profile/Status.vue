<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const status = ref('loading');
const biodata = ref(null);
const mosque = ref(null);
const pendingJoin = ref(null);
const completion = ref(0);

onMounted(async () => {
    try {
        const res = await fetch('/api/profile/state', { headers: { 'Accept': 'application/json' } });
        const j = await res.json();
        biodata.value = j.biodata;
        mosque.value = j.mosque;
        pendingJoin.value = j.pending_join;
        completion.value = j.completion;
        status.value = j.status;
    } catch (e) {
        status.value = 'error';
    }
});
</script>

<template>
    <Head title="Statut du profil — Rencontre Éthique" />

    <div class="min-h-screen flex items-center justify-center" style="background: linear-gradient(160deg, #FBF7F0 0%, #F5EEDD 50%, #F0E6D0 100%)">
        <div class="pattern-re absolute inset-0 pointer-events-none"></div>

        <div class="relative w-full max-w-md mx-5">
            <div class="text-center mb-6">
                <div class="inline-flex w-14 h-14 rounded-2xl bg-[#0f3a7d] shadow-lg items-center justify-center mb-4" style="box-shadow: 0 6px 20px rgba(28,69,50,.3)">
                    <svg class="w-7 h-7 text-[#E4B84A]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5a7.5 7.5 0 0 0-7.5 7.5c0 5.2 6.2 10.6 7.5 10.6s7.5-5.4 7.5-10.6A7.5 7.5 0 0 0 12 1.5Z"/></svg>
                </div>
                <h1 class="font-display text-2xl font-medium text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">Rencontre Éthique</h1>
            </div>

            <div v-if="status === 'loading'" class="card-re p-8 text-center">
                <div class="animate-spin w-8 h-8 border-3 border-[#0f3a7d] border-t-transparent rounded-full mx-auto mb-4"></div>
                <div class="text-sm text-[#8A9680]">Chargement...</div>
            </div>

            <!-- PENDING APPROVAL -->
            <div v-else-if="status === 'pending_approval'" class="card-re p-8 text-center re-fade-up">
                <div class="w-20 h-20 rounded-full bg-amber-50 border-2 border-amber-300 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-9 h-9 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 6h2v6h-2V8zm0 8h2v2h-2v-2z"/></svg>
                </div>
                <h2 class="font-display text-2xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Profil en cours de validation</h2>
                <p class="text-sm text-[#8A9680] leading-relaxed mb-5">
                    Votre profil a été soumis. L'imam de votre mosquée va l'examiner.
                    Cette étape garantit le sérieux de chaque profil.
                </p>
                <div class="bg-[#F8F6F0] rounded-xl px-4 py-3 mb-5 text-sm text-[#374151]">
                    <span class="font-semibold text-[#0f3a7d]">Profil complété à {{ completion }}%</span>
                    <span class="text-[#8A9680]"> · Soumis</span>
                </div>
                <div class="flex flex-col gap-2.5">
                    <Link href="/profile/edit" class="btn-re btn-re-ghost w-full py-3 text-sm">Modifier mon profil</Link>
                    <Link href="/" class="text-xs text-[#8A9680] hover:text-[#0f3a7d] transition-colors">Retour à l'accueil</Link>
                </div>
            </div>

            <!-- NO PROFILE -->
            <div v-else-if="status === 'no_profile'" class="card-re p-8 text-center re-fade-up">
                <div class="text-5xl mb-4">👤</div>
                <h2 class="font-display text-2xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Votre profil n'est pas encore créé</h2>
                <p class="text-sm text-[#8A9680] mb-5">Complétez votre profil en quelques minutes pour commencer votre rencontre éthique.</p>
                <Link href="/profile/edit" class="btn-re btn-re-primary w-full py-3.5 text-sm">Créer mon profil →</Link>
            </div>

            <!-- NO MOSQUE -->
            <div v-else-if="status === 'no_mosque'" class="card-re p-8 text-center re-fade-up">
                <div class="text-5xl mb-4">🕌</div>
                <h2 class="font-display text-2xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Rejoignez votre mosquée</h2>
                <p class="text-sm text-[#8A9680] mb-5">Votre profil est validé ! Il ne manque plus que votre mosquée pour découvrir les membres.</p>
                <Link href="/mosque" class="btn-re btn-re-primary w-full py-3.5 text-sm">Trouver ma mosquée →</Link>
            </div>

            <!-- ACTIVE -->
            <div v-else-if="status === 'active'" class="card-re p-8 text-center re-fade-up">
                <div class="w-20 h-20 rounded-full bg-emerald-50 border-2 border-emerald-300 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-9 h-9 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                </div>
                <h2 class="font-display text-2xl font-medium text-[#0f3a7d] mb-2" style="font-family:'Cormorant Garamond',serif">Votre profil est actif !</h2>
                <p v-if="mosque" class="text-sm text-[#8A9680] mb-5">Vous faites partie de <strong class="text-[#0f3a7d]">{{ mosque.name }}</strong> — découvrez les membres.</p>
                <Link href="/app/members" class="btn-re btn-re-primary w-full py-3.5 text-sm">Découvrir les membres →</Link>
            </div>

            <div v-else class="card-re p-8 text-center">
                <div class="text-5xl mb-4">⚠️</div>
                <h2 class="font-display text-xl font-medium text-[#0f3a7d] mb-3">Une erreur est survenue</h2>
                <Link href="/login" class="text-xs text-[#ff6b6b] font-semibold">Se reconnecter</Link>
            </div>
        </div>
    </div>
</template>
