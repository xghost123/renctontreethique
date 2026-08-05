<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    translations: { type: Object, default: () => ({}) },
    locale: { type: String, default: 'fr' },
});

const cols = [
    { title: 'Plateforme', links: [{ href: '/#concept', label: 'Le concept' }, { href: '/#fonctionnement', label: 'Comment ça marche' }, { href: '/#temoignages', label: 'Témoignages' }] },
    { title: 'Informations', links: [{ href: '/contact', label: 'Contact' }, { href: '/terms', label: 'Conditions d\'utilisation' }, { href: '/opinions', label: 'Avis' }] },
];
</script>

<template>
    <footer class="bg-[#0D2218] text-[#C8A028]/70 relative overflow-hidden">
        <div class="pattern-re absolute inset-0 opacity-[.06]"></div>
        <div class="relative max-w-7xl mx-auto px-6 py-14">
            <div class="grid md:grid-cols-4 gap-10 mb-12">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-[#0f3a7d] border border-[#C8A028]/25 flex items-center justify-center">
                            <svg class="w-[18px] h-[18px] text-[#E4B84A]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 1.5a7.5 7.5 0 0 0-7.5 7.5c0 5.2 6.2 10.6 7.5 10.6s7.5-5.4 7.5-10.6A7.5 7.5 0 0 0 12 1.5Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-display text-lg font-semibold text-[#FBF7F0]" style="font-family:'Cormorant Garamond',Georgia,serif">Rencontre Éthique</div>
                            <div class="text-[9px] uppercase tracking-[.2em] text-[#C8A028] mt-0.5">Halal · Mosquée</div>
                        </div>
                    </div>
                    <p class="text-[#C8A028]/50 text-sm leading-relaxed max-w-sm" style="font-family:'Plus Jakarta Sans',sans-serif">
                        La rencontre matrimoniale éthique, basée sur votre mosquée.
                        Des profils sincères, validés par votre imam. Gratuit et sans compromis.
                    </p>
                </div>

                <!-- Link columns -->
                <div v-for="col in cols" :key="col.title">
                    <div class="text-[11px] uppercase tracking-[.18em] text-[#C8A028] font-semibold mb-4">{{ col.title }}</div>
                    <ul class="space-y-2.5">
                        <li v-for="l in col.links" :key="l.label">
                            <a :href="l.href" class="text-sm text-[#C8A028]/60 hover:text-[#C8A028] transition-colors" style="font-family:'Plus Jakarta Sans',sans-serif">{{ l.label }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="divider-re !text-[#C8A028]/30">
                <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-3 mt-6">
                <div class="text-xs text-[#C8A028]/35" style="font-family:'Plus Jakarta Sans',sans-serif">
                    © {{ new Date().getFullYear() }} Rencontre Éthique — Tous droits réservés.
                </div>
                <div class="flex items-center gap-5 text-[11px] text-[#C8A028]/40" style="font-family:'Plus Jakarta Sans',sans-serif">
                    <span>Fait avec sincérité</span>
                    <span class="w-1 h-1 rounded-full bg-[#C8A028]/40"></span>
                    <span>Plateforme 100% gratuite</span>
                </div>
            </div>
        </div>
    </footer>
</template>
