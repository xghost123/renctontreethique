<template>
  <GuestLayout>
    <Head title="Rencontre Éthique - Trouvez votre moitié" />

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-[#0D2218] to-[#1C4532] text-white py-24 relative overflow-hidden">
      <div class="absolute inset-0 opacity-20" style="background: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1440 320%22><path fill=%22%23C8A028%22 d=%22M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z%22></path></svg>')"></div>
      
      <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6">Rencontre Éthique</h1>
        <p class="text-xl md:text-2xl mb-8 text-gray-200">Rencontrez votre moitié au sein de votre communauté</p>
        <Link 
          href="/register" 
          class="inline-block bg-[#C8A028] text-[#0D2218] px-8 py-4 rounded-lg font-bold text-lg hover:bg-[#D4B44D] transition transform hover:scale-105"
        >
          Créer mon profil gratuitement
        </Link>
      </div>
    </section>

    <!-- Value Proposition -->
    <section class="py-16 px-4 bg-gray-50">
      <div class="container mx-auto">
        <div class="max-w-3xl mx-auto text-center">
          <h2 class="text-4xl font-bold text-[#0D2218] mb-6">Une plateforme pour la communauté</h2>
          <p class="text-lg text-gray-600 leading-relaxed mb-4">
            Rencontre Éthique est un service <strong>100% gratuit</strong> au service de la communauté musulmane.
          </p>
          <p class="text-lg text-gray-600 leading-relaxed">
            Ce projet a uniquement vocation à rendre accessible cette étape clé de mise en relation entre un prétendant et un tuteur en vu du mariage.
          </p>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4">
      <div class="container mx-auto">
        <h2 class="text-4xl font-bold text-[#0D2218] text-center mb-16">Pourquoi Rencontre Éthique?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <FeatureCard 
            icon="🕌"
            title="Profils locaux confirmés"
            description="Fini les profils à l'autre bout du monde. Chaque profil est rattaché à sa mosquée et est confirmé par deux fidèles de cette même mosquée. Un profil associé à une communauté locale est rassurant."
          />
          
          <FeatureCard 
            icon="⚖️"
            title="Cadre légiféré"
            description="Le mahram est impliqué. Pas d'échange direct sans modération. Vos coordonnées sont transmises entre le tuteur et le prétendant seulement après validation mutuelle."
          />
          
          <FeatureCard 
            icon="🔒"
            title="Vie privée protégée"
            description="Votre profil reste anonyme durant tout le processus. Vos informations personnelles sont rigoureusement protégées et ne seront jamais communiquées à des tiers."
          />
          
          <FeatureCard 
            icon="💰"
            title="100% Gratuit"
            description="Rencontre Éthique est un service entièrement gratuit. Le concept se veut à but non lucratif, porté par la communauté pour la communauté."
          />
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 px-4 bg-gray-50">
      <div class="container mx-auto">
        <h2 class="text-4xl font-bold text-[#0D2218] text-center mb-16">Comment ça fonctionne</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <StepCard 
            :step="1"
            title="Inscription"
            description="Complétez votre profil et engagez-vous dans une démarche respectueuse en vu du mariage uniquement."
          />
          
          <StepCard 
            :step="2"
            title="Confirmation"
            description="Deux témoins fidèles de votre mosquée doivent confirmer votre profil pour finaliser votre inscription."
          />
          
          <StepCard 
            :step="3"
            title="Recherche"
            description="Accédez à tous les profils anonymes rattachés à votre mosquée ou aux mosquées à proximité. Filtrez et faites une demande d'échange."
          />
          
          <StepCard 
            :step="4"
            title="Mise en relation"
            description="Une fois compatibles, nous levons l'anonymat entre le prétendant et le tuteur pour que vous puissiez poursuivre votre démarche."
          />
        </div>

        <div class="mt-12 p-6 bg-blue-50 border-l-4 border-[#C8A028] rounded">
          <p class="text-gray-700 italic">
            <strong>Important :</strong> Les profils consultés entre prétendants restent anonymes durant tout le processus.
          </p>
        </div>
      </div>
    </section>

    <!-- Cause Section -->
    <section class="py-20 px-4">
      <div class="container mx-auto max-w-3xl">
        <h2 class="text-4xl font-bold text-[#0D2218] mb-8 text-center">Notre cause</h2>
        
        <div class="space-y-6 text-gray-600 leading-relaxed">
          <p>
            Rencontre Éthique est une initiative lancée pour faciliter à notre communauté les rencontres en vue du mariage dans un cadre préservé et articulé autour d'un lieu au cœur : <strong>la mosquée</strong>.
          </p>
          
          <p>
            Force est de reconnaître l'attente et la demande importante sur cette question et l'abandon ou le désengagement des proches. Il est devenu impératif de proposer une solution saine dans une société où les tentations sont omniprésentes et souvent pernicieuses.
          </p>
          
          <p class="font-semibold">
            Rencontre Éthique a pour unique objectif de faciliter cette étape essentielle de mise en relation entre un prétendant et un tuteur.
          </p>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 px-4 bg-gray-50">
      <div class="container mx-auto max-w-3xl">
        <h2 class="text-4xl font-bold text-[#0D2218] text-center mb-16">Questions fréquentes</h2>
        
        <FAQItem 
          question="Rencontre Éthique est-il vraiment gratuit?"
          answer="Oui, Rencontre Éthique est 100% gratuit. Le concept se veut à but non lucratif et est porté par la communauté pour la communauté. Aucun frais caché n'existe."
        />
        
        <FAQItem 
          question="Comment fonctionne la confirmation du profil?"
          answer="Deux témoins fidèles de votre mosquée doivent confirmer que vous êtes bien la personne décrite dans votre profil. Cela assure l'authenticité et la sérieux de chaque inscription."
        />
        
        <FAQItem 
          question="Mes informations personnelles sont-elles protégées?"
          answer="Absolument. Votre profil reste anonyme durant tout le processus de matching. Vos informations personnelles ne sont partagées qu'avec la permission mutuelle et seulement après validation."
        />
        
        <FAQItem 
          question="Peut-on communiquer directement sans modération?"
          answer="Non, le mahram (tuteur) est impliqué dans le processus. Il n'y a pas d'échange direct sans modération jusqu'à la mise en relation finale validée par les deux parties."
        />
        
        <FAQItem 
          question="Comment puis-je supprimer mon profil?"
          answer="Vous gardez le contrôle total de vos données. Vous pouvez modifier votre profil et le supprimer à tout moment depuis les paramètres de votre compte."
        />
        
        <FAQItem 
          question="Y a-t-il des photos ou des vidéos?"
          answer="Non, pendant la phase de recherche et de matching, il n'y a pas d'échange de photos ou de vidéos. L'anonymat et la protection de la vie privée sont primordiaux."
        />
      </div>
    </section>

    <!-- Final CTA -->
    <CTASection 
      title="Retrouvez les profils sérieux près de chez vous"
      subtitle="Rejoignez notre communauté et trouvez votre moitié dans un cadre respectueux et sain."
      buttonText="Créer mon profil gratuitement"
      buttonLink="/register"
    />
  </GuestLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import FeatureCard from '../Components/Frontend/FeatureCard.vue';
import StepCard from '../Components/Frontend/StepCard.vue';
import FAQItem from '../Components/Frontend/FAQItem.vue';
import CTASection from '../Components/Frontend/CTASection.vue';
</script>
