<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Header from '@/Components/Frontend/Header.vue';

const props = defineProps({
    mosqueCount: { type: Number, default: 0 },
    memberCount: { type: Number, default: 0 },
    translations: { type: Object, default: () => ({}) },
    locale: { type: String, default: 'fr' },
    canLogin: { type: Boolean, default: true },
    canRegister: { type: Boolean, default: true },
});

document.body.classList.remove(...document.body.classList);
document.body.classList.add("frontend.home");

const scrolled = ref(false);
onMounted(() => {
    window.addEventListener('scroll', () => { scrolled.value = window.scrollY > 20; });
});



const features = [
    { icon: 'mosque', title: 'Profils locaux confirmés', desc: 'Fini les profils à l\u2019autre bout du monde. Chaque profil est rattaché à sa mosquée et est confirmé par deux fidèles de cette même mosquée. Un profil associé à une communauté local est rassurant, il permet de plus aisément créer le lien entre les proches (tuteur, frère,...) et ainsi faciliter la suite de votre démarche.' },
    { icon: 'shield', title: 'Cadre légiféré', desc: 'Le mahram est impliqué. Pas d\u2019échange direct sans modération. Vos coordonnées sont transmises entre le tuteur et le prétendant seulement après validation mutuelle. Notre accompagnement s\u2019arrête ici.' },
    { icon: 'hands', title: 'Vie privée protégée', desc: 'Votre profil reste anonyme durant tout le processus. Vos informations personnelles sont rigoureusement protégées et seront exclusivement employées dans le cadre de votre projet de mariage. Elles ne seront en aucun cas communiquées à des tiers.' },
    { icon: 'family', title: '100% Gratuit', desc: 'Rencontre Éthique est un service 100% gratuit. Le concept se veut à but non lucratif, pour la communauté et porté par la communauté. Chacun de nous peut participer à son développement.' },
    { icon: 'search', title: 'Rencontres sérieuses', desc: 'Pratique religieuse, madhab, situation familiale, attentes : des profils complets pour des projets durables et sincères.' },
    { icon: 'lock', title: 'Processus transparent', desc: 'Chaque étape est claire et encadrée. De l\u2019inscription à la mise en relation, tout est pensé pour la sérieux et le respect mutuel.' },
];

const steps = [
    { n: '01', title: 'Créez votre profil', desc: 'Indiquez vos informations, votre pratique religieuse et vos attentes, en toute sincérité.' },
    { n: '02', title: 'Rejoignez votre mosquée', desc: 'Votre demande d\u2019adhésion est examinée et validée par l\u2019imam de votre mosquée.' },
    { n: '03', title: 'Découvrez les membres', desc: 'Les profils des membres de votre mosquée vous sont proposés, dans le respect mutuel.' },
    { n: '04', title: 'Échangez dans le cadre', desc: 'Les frères initient, les sœurs acceptent. Le premier contact se fait dans les règles.' },
];

const testimonials = [
    { quote: 'Une approche enfin sérieuse et respectueuse. J\u2019ai rencontré mon mari à la mosquée de notre quartier, sans stress ni faux profils.', name: 'Amina, 29', city: 'Paris' },
    { quote: 'Le fait que l\u2019imam valide les profils change tout. On sait qu\u2019on parle à de vraies personnes sérieuses.', name: 'Youssef, 34', city: 'Lyon' },
    { quote: 'Enfin une plateforme où la famille est respectée. Mon wali a pu suivre les échanges dès le début.', name: 'Salma, 26', city: 'Bruxelles' },
];


</script>

<template>
    <Head title="Rencontre Éthique — Le mariage halal, dans votre mosquée" />

    <!-- Header -->
    <Header />


    <!-- ═══ HERO ═══ -->
    <section class="relative overflow-hidden" style="background: linear-gradient(160deg, #faf8f3 0%, #f3f0ed 45%, #F0E6D0 100%)">
        <div class="pattern-re absolute inset-0 opacity-70"></div>
        <!-- Ornament corners -->
        <svg class="absolute -top-10 -left-10 w-44 h-44 text-[#1e3a8a] opacity-[.07]" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-60a20 20 0 1 0 20 20 20 20 0 0 0-20-20z"/></svg>
        <svg class="absolute -bottom-10 -right-10 w-44 h-44 text-[#1e3a8a] opacity-[.07] rotate-180" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-60a20 20 0 1 0 20 20 20 20 0 0 0-20-20z"/></svg>

        <div class="relative max-w-7xl mx-auto px-6 pt-16 md:pt-24 pb-20 md:pb-28 text-center">
            <div class="re-fade-up inline-flex items-center gap-2 bg-white/70 backdrop-blur border border-[#b76e79]/30 text-[#1e3a8a] px-4 py-1.5 rounded-full text-sm font-medium mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#b76e79]"></span>
                Rencontres basées sur la mosquée · Profils validés par la communauté
            </div>

            <h1 class="re-fade-up-1 font-display text-5xl md:text-7xl font-medium text-[#1e3a8a] mb-6 leading-[1.05]" style="font-family:'Cormorant Garamond',Georgia,serif;letter-spacing:-.015em">
                Le mariage halal,<br />
                <span class="italic text-[#059669]">dans votre mosquée</span>
            </h1>

            <div class="re-fade-up-2 divider-re max-w-xs mx-auto mb-6">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
            </div>

            <p class="re-fade-up-2 text-lg md:text-xl text-[#6B7280] max-w-2xl mx-auto mb-10 leading-relaxed" style="font-family:'Plus Jakarta Sans',sans-serif">
                Rencontre Éthique réunit les membres d'une même mosquée, confirmés par la communauté.
                Une rencontre respectueuse, encadrée, et pensée pour durer.
            </p>

            <div class="re-fade-up-3 flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <Link href="/register" class="btn-re btn-re-primary px-9 py-4 text-base">Créer mon profil gratuitement</Link>
                <a href="#concept" class="btn-re btn-re-ghost px-9 py-4 text-base">Découvrir le concept</a>
            </div>

            <!-- Stats -->
            <div class="re-fade-up-4 grid grid-cols-3 gap-4 max-w-2xl mx-auto">
                <div class="card-re px-4 py-6">
                    <div class="font-display text-3xl md:text-4xl font-medium text-[#1e3a8a]" style="font-family:'Cormorant Garamond',serif">{{ mosqueCount || '50+' }}</div>
                    <div class="text-xs md:text-sm text-[#8A9680] mt-1">mosquées partenaires</div>
                </div>
                <div class="card-re px-4 py-6">
                    <div class="font-display text-3xl md:text-4xl font-medium text-[#1e3a8a]" style="font-family:'Cormorant Garamond',serif">{{ memberCount || '1000+' }}</div>
                    <div class="text-xs md:text-sm text-[#8A9680] mt-1">membres vérifiés</div>
                </div>
                <div class="card-re px-4 py-6">
                    <div class="font-display text-3xl md:text-4xl font-medium text-[#b76e79]" style="font-family:'Cormorant Garamond',serif">100%</div>
                    <div class="text-xs md:text-sm text-[#8A9680] mt-1">profils confirmés par la communauté</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CONCEPT ═══ -->
    <section id="concept" class="py-24 bg-[#faf8f3]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="text-xs uppercase tracking-[.2em] text-[#b76e79] font-semibold mb-3">Le concept</div>
                <h2 class="section-title-re mb-4">Pourquoi Rencontre Éthique ?</h2>
                <div class="divider-re max-w-[160px] mx-auto mb-5">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
                </div>
                <p class="section-sub-re max-w-2xl mx-auto">Une approche différente des sites de rencontre : la mosquée comme gage de confiance, l'imam comme garant du sérieux.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="(f, i) in features" :key="f.title" :class="['card-re p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl', 're-fade-up-' + (i % 3 + 1)]">
                    <div class="w-12 h-12 rounded-xl bg-[#1e3a8a]/[.06] border border-[#1e3a8a]/10 flex items-center justify-center mb-5">
                        <svg v-if="f.icon === 'mosque'" class="w-6 h-6 text-[#1e3a8a]" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10v5h2v2h-1v9h3v2H3v-2h3v-9H5V7h2V2zm2 5h6V4H9v3zm2 5v7h2v-7h-2z"/></svg>
                        <svg v-else-if="f.icon === 'shield'" class="w-6 h-6 text-[#1e3a8a]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.11v5.7c0 4.5-2.92 8.7-7 10.02-4.08-1.32-7-5.52-7-10.02v-5.7l7-3.11zM11 7h2v5h-2V7zm0 7h2v2h-2v-2z"/></svg>
                        <svg v-else-if="f.icon === 'hands'" class="w-6 h-6 text-[#1e3a8a]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        <svg v-else-if="f.icon === 'family'" class="w-6 h-6 text-[#1e3a8a]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-9 10v-1c0-1.66 1.34-3 3-3h12c1.66 0 3 1.34 3 3v1H3z" transform="scale(.8) translate(3,3)"/></svg>
                        <svg v-else-if="f.icon === 'search'" class="w-6 h-6 text-[#1e3a8a]" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 001.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 00-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 005.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <svg v-else class="w-6 h-6 text-[#1e3a8a]" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM9 8V6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9z"/></svg>
                    </div>
                    <h3 class="font-display text-xl font-semibold text-[#1e3a8a] mb-2" style="font-family:'Cormorant Garamond',serif">{{ f.title }}</h3>
                    <p class="text-[#6B7280] leading-relaxed text-[.92rem]" style="font-family:'Plus Jakarta Sans',sans-serif">{{ f.desc }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ HOW IT WORKS ═══ -->
    <section id="fonctionnement" class="py-24" style="background: linear-gradient(180deg, #faf8f3 0%, #F0E6D0 100%)">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="text-xs uppercase tracking-[.2em] text-[#b76e79] font-semibold mb-3">Comment ça marche</div>
                <h2 class="section-title-re mb-4">Quatre étapes vers un projet sérieux</h2>
                <div class="divider-re max-w-[160px] mx-auto mb-5">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
                </div>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <div v-for="(s, i) in steps" :key="s.n" :class="['card-re p-7 relative overflow-hidden', 're-fade-up-' + (i + 1)]">
                    <div class="absolute -right-4 -top-6 font-display text-[5rem] font-medium text-[#1e3a8a]/[.05] leading-none" style="font-family:'Cormorant Garamond',serif">{{ s.n }}</div>
                    <div class="w-11 h-11 rounded-full bg-[#1e3a8a] text-white font-semibold flex items-center justify-center mb-5 shadow-md" style="box-shadow: 0 4px 12px rgba(28,69,50,.3)">{{ s.n }}</div>
                    <h3 class="font-display text-lg font-semibold text-[#1e3a8a] mb-2" style="font-family:'Cormorant Garamond',serif">{{ s.title }}</h3>
                    <p class="text-[#6B7280] text-sm leading-relaxed" style="font-family:'Plus Jakarta Sans',sans-serif">{{ s.desc }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ TESTIMONIALS ═══ -->
    <section id="temoignages" class="py-24 bg-[#faf8f3]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="text-xs uppercase tracking-[.2em] text-[#b76e79] font-semibold mb-3">Témoignages</div>
                <h2 class="section-title-re mb-4">Ils ont trouvé leur moitié</h2>
                <div class="divider-re max-w-[160px] mx-auto mb-5">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div v-for="(t, i) in testimonials" :key="t.name" :class="['card-re p-8', 're-fade-up-' + (i + 1)]">
                    <div class="text-[#b76e79] text-2xl mb-3" style="font-family:Georgia,serif">"</div>
                    <p class="text-[#374151] italic leading-relaxed mb-6" style="font-family:'Plus Jakarta Sans',sans-serif">{{ t.quote }}</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#1e3a8a]/[.08] border border-[#1e3a8a]/15 flex items-center justify-center text-[#1e3a8a] font-semibold text-sm">{{ t.name.charAt(0) }}</div>
                        <div>
                            <div class="text-sm font-semibold text-[#1e3a8a]">{{ t.name }}</div>
                            <div class="text-xs text-[#8A9680]">{{ t.city }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ FREE PLATFORM ═══ -->
    <section class="py-20 bg-[#1e3a8a] relative overflow-hidden">
        <div class="pattern-re absolute inset-0 opacity-[.12]"></div>
        <svg class="absolute -top-20 -left-20 w-72 h-72 text-[#b76e79] opacity-[.08]" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-60a20 20 0 1 0 20 20 20 20 0 0 0-20-20z"/></svg>
        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 bg-[#b76e79]/15 border border-[#b76e79]/30 text-[#E4B84A] px-5 py-2 rounded-full text-sm font-semibold mb-7">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
                100% gratuit
            </div>
            <h2 class="font-display text-3xl md:text-5xl font-medium text-[#faf8f3] mb-5 leading-tight" style="font-family:'Cormorant Garamond',Georgia,serif">
                Une plateforme gratuite,<br /><span class="italic text-[#E4B84A]">sans compromis</span>
            </h2>
            <div class="divider-re max-w-[160px] mx-auto mb-6 !text-[#b76e79]">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
            </div>
            <p class="text-[#b76e79]/85 text-lg mb-9 leading-relaxed max-w-2xl mx-auto" style="font-family:'Plus Jakarta Sans',sans-serif">
                Pas d'abonnement, pas de frais cachés. Rencontre Éthique est entièrement gratuit :
                votre profil, vos recherches, vos échanges — tout est accessible, pour les frères comme pour les sœurs.
            </p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto mb-10">
                <div class="bg-white/[.06] border border-white/10 rounded-2xl px-4 py-5">
                    <div class="font-display text-2xl text-[#E4B84A]" style="font-family:'Cormorant Garamond',serif">0 €</div>
                    <div class="text-xs text-[#b76e79]/60 mt-1">création de profil</div>
                </div>
                <div class="bg-white/[.06] border border-white/10 rounded-2xl px-4 py-5">
                    <div class="font-display text-2xl text-[#E4B84A]" style="font-family:'Cormorant Garamond',serif">0 €</div>
                    <div class="text-xs text-[#b76e79]/60 mt-1">recherche illimitée</div>
                </div>
                <div class="bg-white/[.06] border border-white/10 rounded-2xl px-4 py-5">
                    <div class="font-display text-2xl text-[#E4B84A]" style="font-family:'Cormorant Garamond',serif">0 €</div>
                    <div class="text-xs text-[#b76e79]/60 mt-1">messagerie</div>
                </div>
                <div class="bg-white/[.06] border border-white/10 rounded-2xl px-4 py-5">
                    <div class="font-display text-2xl text-[#E4B84A]" style="font-family:'Cormorant Garamond',serif">∞</div>
                    <div class="text-xs text-[#b76e79]/60 mt-1">demandes</div>
                </div>
            </div>
            <Link href="/register" class="btn-re btn-re-gold px-10 py-4 text-base">Commencer gratuitement</Link>
        </div>
    </section>

    <!-- ═══ FINAL CTA ═══ -->
    <section class="py-20 bg-[#1e3a8a] relative overflow-hidden">
        <div class="pattern-re absolute inset-0 opacity-[.15]"></div>
        <svg class="absolute -top-16 -right-16 w-64 h-64 text-[#b76e79] opacity-[.08] rotate-180" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-60a20 20 0 1 0 20 20 20 20 0 0 0-20-20z"/></svg>
        <div class="relative max-w-3xl mx-auto px-6 text-center">
            <h2 class="font-display text-3xl md:text-5xl font-medium text-[#faf8f3] mb-5 leading-tight" style="font-family:'Cormorant Garamond',serif">
                Prêt à faire une rencontre éthique ?
            </h2>
            <div class="divider-re max-w-[160px] mx-auto mb-6 !text-[#b76e79]">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
            </div>
            <p class="text-[#b76e79]/90 text-lg mb-10 leading-relaxed" style="font-family:'Plus Jakarta Sans',sans-serif">
                Rejoignez votre mosquée, créez votre profil, et laissez la sincérité faire le reste.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <Link href="/register" class="btn-re btn-re-gold px-9 py-4 text-base">Créer mon profil — gratuit</Link>
                <Link href="/login" class="btn-re px-9 py-4 text-base text-[#faf8f3] border border-[#faf8f3]/30 hover:bg-white/10 rounded-xl transition-all">J'ai déjà un compte</Link>
            </div>
        </div>
    </section>

    <!-- ═══ FOOTER ═══ -->
    <footer class="bg-[#0D2218] py-14">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#1e3a8a] border border-[#b76e79]/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#b76e79]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C7.03 2 3 6.03 3 11c0 6.5 9 11 9 11s9-4.5 9-11c0-4.97-4.03-9-9-9zm0 4.5c2.76 0 5 2.24 5 5 0 .55-.45 1-1 1s-1-.45-1-1c0-1.66-1.34-3-3-3-.55 0-1-.45-1-1s.45-1 1-1z"/></svg>
                    </div>
                    <div>
                        <div class="font-display text-lg text-[#faf8f3] leading-none" style="font-family:'Cormorant Garamond',serif">Rencontre Éthique</div>
                        <div class="text-[10px] uppercase tracking-[.18em] text-[#b76e79] mt-1">Halal · Mosquée · Sérieux</div>
                    </div>
                </div>
                <div class="flex gap-8 text-sm text-[#b76e79]/70">
                    <a href="#concept" class="hover:text-[#b76e79] transition-colors">Concept</a>
                    <a href="#fonctionnement" class="hover:text-[#b76e79] transition-colors">Fonctionnement</a>
                    <a href="/contact" class="hover:text-[#b76e79] transition-colors">Contact</a>
                    <a href="/terms" class="hover:text-[#b76e79] transition-colors">Conditions</a>
                </div>
            </div>
            <div class="divider-re !text-[#b76e79]/40">
                <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
            </div>
            <div class="text-center text-xs text-[#b76e79]/40 mt-6" style="font-family:'Plus Jakarta Sans',sans-serif">
                © {{ new Date().getFullYear() }} Rencontre Éthique — Une plateforme de rencontre matrimoniale éthique. Tous droits réservés.
            </div>
        </div>
    </footer>
</template>
