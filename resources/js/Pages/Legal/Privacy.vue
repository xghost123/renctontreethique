<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>

<template>
    <Head title="Politique de confidentialité" />
    
    <GuestLayout>
        <div class="max-w-3xl mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold mb-8 text-[#0f3a7d]">Politique de confidentialité</h1>
            
            <div class="prose prose-sm max-w-none text-gray-700 space-y-6">
                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">1. Introduction</h2>
                    <p>Rencontre Éthique s'engage à protéger votre vie privée. Cette politique décrit comment nous collectons, utilisons et protégeons vos données personnelles.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">2. Données collectées</h2>
                    <p>Nous collectons les données suivantes lors de l'inscription:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Nom et prénom</li>
                        <li>Adresse email</li>
                        <li>Numéro de téléphone</li>
                        <li>Sexe / Genre</li>
                        <li>Âge et date de naissance</li>
                        <li>Localisation (ville, pays)</li>
                        <li>Informations religieuses et culturelles</li>
                        <li>Photos de profil</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">3. Utilisation des données</h2>
                    <p>Vos données sont utilisées pour:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Créer et gérer votre profil</li>
                        <li>Vous permettre de rechercher et de naviguer les profils</li>
                        <li>Faciliter la communication entre utilisateurs</li>
                        <li>Vérifier votre identité et votre admissibilité</li>
                        <li>Envoyer des notifications et des mises à jour</li>
                        <li>Améliorer nos services</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">4. Partage des données</h2>
                    <p>Vos données ne sont partagées que avec:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Les autres utilisateurs de votre mosquée (profil visible uniquement)</li>
                        <li>Les modérateurs et l'équipe administrative (vérification)</li>
                        <li>Les prestataires de services (hébergement, email)</li>
                    </ul>
                    <p>Nous ne vendons jamais vos données à des tiers.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">5. Sécurité des données</h2>
                    <p>Nous utilisons le chiffrement SSL/TLS pour protéger vos données en transit. Les mots de passe sont hachés avec bcrypt. Cependant, aucune transmission n'est 100% sécurisée.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">6. Droits des utilisateurs (RGPD)</h2>
                    <p>Vous avez le droit de:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Accéder à vos données personnelles</li>
                        <li>Rectifier vos données inexactes</li>
                        <li>Supprimer votre compte et vos données</li>
                        <li>Porter vos données vers un autre service</li>
                        <li>Retirer votre consentement</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">7. Cookies</h2>
                    <p>Nous utilisons des cookies pour mémoriser vos préférences et améliorer votre expérience. Vous pouvez désactiver les cookies dans les paramètres de votre navigateur.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">8. Durée de conservation</h2>
                    <p>Vos données sont conservées aussi longtemps que votre compte est actif. Vous pouvez demander la suppression de votre compte à tout moment.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">9. Modifications</h2>
                    <p>Nous pouvons modifier cette politique à tout moment. Les modifications seront publiées sur cette page.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">10. Contact</h2>
                    <p>Pour toute question concernant votre vie privée, contactez-nous à: privacy@rencontre-ethique.fr</p>
                </section>

                <div class="bg-[#f0f4ff] border border-[#0f3a7d] rounded-lg p-4 mt-8">
                    <p class="text-sm text-gray-700">Dernière mise à jour: {{ new Date().toLocaleDateString('fr-FR') }}</p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
h2 {
    color: #0f3a7d;
}
</style>
