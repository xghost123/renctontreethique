<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>

<template>
    <Head title="Conditions d'utilisation" />
    
    <GuestLayout>
        <div class="max-w-3xl mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold mb-8 text-[#0f3a7d]">Conditions d'utilisation</h1>
            
            <div class="prose prose-sm max-w-none text-gray-700 space-y-6">
                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">1. Acceptation des conditions</h2>
                    <p>En utilisant Rencontre Éthique, vous acceptez de vous conformer à ces conditions d'utilisation et à toutes les lois et réglementations applicables.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">2. Éligibilité</h2>
                    <p>Vous devez être âgé(e) d'au moins 18 ans pour utiliser cette plateforme. Vous garantissez que toutes les informations que vous fournissez sont exactes, actuelles et complètes.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">3. Comportement des utilisateurs</h2>
                    <p>Vous vous engagez à:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Utiliser la plateforme de manière respectueuse et éthique</li>
                        <li>Ne pas harceler ou abuser d'autres utilisateurs</li>
                        <li>Ne pas poster de contenu offensant, discriminatoire ou illégal</li>
                        <li>Ne pas frauder ou tromper d'autres utilisateurs</li>
                        <li>Respecter les valeurs islamiques et éthiques de la plateforme</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">4. Responsabilité de l'utilisateur</h2>
                    <p>Vous êtes responsable de l'utilisation de votre compte et de la confidentialité de vos identifiants. Rencontre Éthique n'est pas responsable des pertes ou dommages résultant d'une utilisation non autorisée de votre compte.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">5. Limitation de responsabilité</h2>
                    <p>Rencontre Éthique est fournie « telle quelle » sans garanties. Nous ne sommes pas responsables des dommages directs, indirects ou consécutifs résultant de l'utilisation de la plateforme.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">6. Résiliation</h2>
                    <p>Nous nous réservons le droit de suspendre ou de fermer votre compte à tout moment si vous violez ces conditions ou les lois applicables.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">7. Modifications</h2>
                    <p>Nous pouvons modifier ces conditions à tout moment. Les modifications seront effectifs dès la publication. Votre utilisation continue de la plateforme constitue votre acceptation des conditions modifiées.</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-[#0f3a7d] mt-6 mb-3">8. Contact</h2>
                    <p>Pour toute question concernant ces conditions, veuillez nous contacter à: contact@rencontre-ethique.fr</p>
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
