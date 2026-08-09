<template>
  <div class="luxury-homepage">
    <!-- Hero Section -->
    <section class="hero-section relative w-full min-h-screen flex items-center justify-center overflow-hidden">
      <!-- Animated Gradient Background -->
      <div class="hero-background absolute inset-0 w-full h-full">
        <div class="gradient-1"></div>
        <div class="gradient-2"></div>
        <div class="gradient-3"></div>
        <!-- Animated mosque dome and crescent moon -->
        <svg class="mosque-dome" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <radialGradient id="domeGradient" cx="50%" cy="30%">
              <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:0.8" />
              <stop offset="100%" style="stop-color:#0f3a7d;stop-opacity:0.4" />
            </radialGradient>
          </defs>
          <!-- Dome -->
          <circle cx="100" cy="80" r="60" fill="url(#domeGradient)" opacity="0.7"/>
          <!-- Minaret -->
          <rect x="95" y="120" width="10" height="60" fill="#ff6b6b" opacity="0.6"/>
          <!-- Crescent moon -->
          <circle cx="150" cy="40" r="25" fill="#ffffff" opacity="0.8"/>
          <circle cx="160" cy="40" r="25" fill="#0f3a7d" opacity="0.9"/>
        </svg>
        
        <!-- Crescent moon accent -->
        <div class="crescent-moon absolute top-20 right-10"></div>
        
        <!-- Floating geometric patterns -->
        <div class="geometric-pattern pattern-1"></div>
        <div class="geometric-pattern pattern-2"></div>
        <div class="geometric-pattern pattern-3"></div>
      </div>

      <!-- Hero Content -->
      <div class="hero-content relative z-10 text-center px-4 sm:px-6 max-w-4xl mx-auto">
        <div class="mb-8 animate-fade-in">
          <h1 class="hero-title text-5xl sm:text-7xl font-bold mb-6 bg-gradient-to-r from-white via-pink-200 to-teal-200 bg-clip-text text-transparent leading-tight">
            Rencontre Éthique
          </h1>
          <p class="hero-subtitle text-xl sm:text-2xl text-gray-100 font-light mb-8 max-w-2xl mx-auto">
            Rencontrez votre moitié au sein de votre communauté
          </p>
        </div>

        <!-- Primary CTA -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 animate-fade-in-delay">
          <button class="premium-btn btn-primary group relative px-10 py-4 text-lg font-semibold text-white bg-gradient-to-r from-pink-500 to-red-600 rounded-full overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105">
            <span class="relative z-10 flex items-center gap-2">
              Créer mon profil gratuitement
              <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </button>
          <button class="premium-btn btn-secondary px-10 py-4 text-lg font-semibold text-sapphire-blue border-2 border-white rounded-full hover:bg-white/10 transition-all duration-300 backdrop-blur-md bg-white/5">
            En Savoir Plus
          </button>
        </div>

        <!-- Scroll Indicator -->
        <div class="animate-bounce mt-16">
          <svg class="w-8 h-8 mx-auto text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
          </svg>
        </div>
      </div>
    </section>

    <!-- Why Rencontre Éthique Section -->
    <section class="why-section py-24 px-4 sm:px-6 lg:px-8 relative z-20 bg-gradient-to-b from-dark-slate-900 via-dark-slate-800 to-dark-slate-900">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-20">
          <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">Pourquoi Rencontre Éthique?</h2>
          <div class="w-24 h-1 bg-gradient-to-r from-coral-pink to-teal mx-auto rounded-full"></div>
        </div>

        <!-- Value Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Card 1: Profils Locaux Confirmés -->
          <div class="value-card group cursor-pointer">
            <div class="card-glass relative p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl hover:bg-white/10 transition-all duration-500 h-full transform hover:scale-105 hover:-translate-y-2">
              <div class="card-icon mb-6 w-16 h-16 rounded-xl bg-gradient-to-br from-pink-500 to-red-600 flex items-center justify-center group-hover:shadow-2xl transition-shadow">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Profils Locaux Confirmés</h3>
              <p class="text-gray-300 leading-relaxed">Chaque profil est lié à une mosquée et validé par 2 témoins de la communauté pour garantir l'authenticité et la confiance.</p>
              <div class="mt-6 pt-6 border-t border-white/10">
                <span class="text-teal text-sm font-semibold">Vérification garantie →</span>
              </div>
            </div>
          </div>

          <!-- Card 2: Cadre Légifié -->
          <div class="value-card group cursor-pointer">
            <div class="card-glass relative p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl hover:bg-white/10 transition-all duration-500 h-full transform hover:scale-105 hover:-translate-y-2">
              <div class="card-icon mb-6 w-16 h-16 rounded-xl bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center group-hover:shadow-2xl transition-shadow">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H7a1 1 0 01-1-1v-6z" clip-rule="evenodd" />
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Cadre Légifié</h3>
              <p class="text-gray-300 leading-relaxed">Un Mahram est impliqué, sans échanges directs sans médiation pour respecter les principes islamiques.</p>
              <div class="mt-6 pt-6 border-t border-white/10">
                <span class="text-teal text-sm font-semibold">Éthique garantie →</span>
              </div>
            </div>
          </div>

          <!-- Card 3: Vie Privée Protégée -->
          <div class="value-card group cursor-pointer">
            <div class="card-glass relative p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl hover:bg-white/10 transition-all duration-500 h-full transform hover:scale-105 hover:-translate-y-2">
              <div class="card-icon mb-6 w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center group-hover:shadow-2xl transition-shadow">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Vie Privée Protégée</h3>
              <p class="text-gray-300 leading-relaxed">Anonymat total, données protégées, contrôle complet de vos informations personnelles.</p>
              <div class="mt-6 pt-6 border-t border-white/10">
                <span class="text-teal text-sm font-semibold">Confidentialité →</span>
              </div>
            </div>
          </div>

          <!-- Card 4: Gratuit -->
          <div class="value-card group cursor-pointer">
            <div class="card-glass relative p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl hover:bg-white/10 transition-all duration-500 h-full transform hover:scale-105 hover:-translate-y-2">
              <div class="card-icon mb-6 w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center group-hover:shadow-2xl transition-shadow">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Gratuit</h3>
              <p class="text-gray-300 leading-relaxed">100% gratuit, à but non lucratif, piloté par la communauté pour le bien de tous.</p>
              <div class="mt-6 pt-6 border-t border-white/10">
                <span class="text-teal text-sm font-semibold">Sans frais →</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Comment Ça Fonctionne Section -->
    <section class="how-it-works-section py-24 px-4 sm:px-6 lg:px-8 relative z-20">
      <div class="max-w-6xl mx-auto">
        <div class="text-center mb-20">
          <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">Comment Ça Fonctionne</h2>
          <div class="w-24 h-1 bg-gradient-to-r from-coral-pink to-teal mx-auto rounded-full"></div>
        </div>

        <!-- Steps Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
          <!-- Connection lines for desktop -->
          <div class="hidden lg:block absolute top-1/3 left-0 w-full h-1 bg-gradient-to-r from-transparent via-teal to-transparent opacity-30"></div>

          <!-- Step 1: Inscription -->
          <div class="step-card group relative">
            <div class="flex flex-col items-center">
              <div class="step-number w-20 h-20 rounded-full bg-gradient-to-br from-pink-500 to-red-600 flex items-center justify-center text-white text-2xl font-bold mb-6 relative z-10 shadow-lg group-hover:shadow-2xl group-hover:scale-110 transition-all duration-300">
                1
              </div>
              <div class="step-content text-center">
                <h3 class="text-xl font-bold text-white mb-3">Étape 1: Inscription</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Complétez votre profil avec engagement respectueux focalisé sur le mariage.</p>
              </div>
            </div>
          </div>

          <!-- Step 2: Confirmation -->
          <div class="step-card group relative">
            <div class="flex flex-col items-center">
              <div class="step-number w-20 h-20 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white text-2xl font-bold mb-6 relative z-10 shadow-lg group-hover:shadow-2xl group-hover:scale-110 transition-all duration-300">
                2
              </div>
              <div class="step-content text-center">
                <h3 class="text-xl font-bold text-white mb-3">Étape 2: Confirmation</h3>
                <p class="text-gray-400 text-sm leading-relaxed">2 témoins de la mosquée valident votre profil pour garantir l'authenticité.</p>
              </div>
            </div>
          </div>

          <!-- Step 3: Recherche -->
          <div class="step-card group relative">
            <div class="flex flex-col items-center">
              <div class="step-number w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold mb-6 relative z-10 shadow-lg group-hover:shadow-2xl group-hover:scale-110 transition-all duration-300">
                3
              </div>
              <div class="step-content text-center">
                <h3 class="text-xl font-bold text-white mb-3">Étape 3: Recherche</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Accédez à des profils anonymes de votre mosquée et alentours, filtrez par critères, demandez des échanges.</p>
              </div>
            </div>
          </div>

          <!-- Step 4: Mise en Relation -->
          <div class="step-card group relative">
            <div class="flex flex-col items-center">
              <div class="step-number w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-2xl font-bold mb-6 relative z-10 shadow-lg group-hover:shadow-2xl group-hover:scale-110 transition-all duration-300">
                4
              </div>
              <div class="step-content text-center">
                <h3 class="text-xl font-bold text-white mb-3">Étape 4: Mise en Relation</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Une fois compatible, révélez les identités au Mahram et à l'autre partie uniquement.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Une Cause Section -->
    <section class="cause-section py-24 px-4 sm:px-6 lg:px-8 relative z-20 bg-gradient-to-b from-dark-slate-900 via-dark-slate-800 to-dark-slate-900">
      <div class="max-w-4xl mx-auto">
        <div class="cause-glass relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl p-12 sm:p-16">
          <!-- Animated background elements -->
          <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-teal-500 to-transparent rounded-full blur-3xl transform translate-x-1/3 -translate-y-1/3"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-br from-pink-500 to-transparent rounded-full blur-3xl transform -translate-x-1/3 translate-y-1/3"></div>
          </div>

          <!-- Content -->
          <div class="relative z-10">
            <div class="text-center mb-12">
              <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">Une Cause</h2>
              <div class="w-24 h-1 bg-gradient-to-r from-coral-pink to-teal mx-auto rounded-full"></div>
            </div>

            <div class="space-y-6">
              <p class="text-xl text-gray-100 leading-relaxed">
                <span class="font-semibold text-transparent bg-gradient-to-r from-pink-400 to-teal-400 bg-clip-text">Rencontre Éthique</span> est une initiative lancée pour faciliter à notre communauté les rencontres en vue du mariage dans un cadre préservé et articulé autour d'un lieu au cœur, <span class="font-semibold text-transparent bg-gradient-to-r from-teal-400 to-pink-400 bg-clip-text">la mosquée</span>.
              </p>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <!-- Besoin Communautaire -->
                <div class="cause-card p-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-lg hover:bg-white/10 transition-all duration-300">
                  <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-pink-500 to-red-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM16 15v2h2v-2zM2 8a2 2 0 11-4 0 2 2 0 014 0zM6 15v2H4v-2z" />
                    </svg>
                  </div>
                  <h3 class="text-lg font-bold text-white mb-2">Besoin Communautaire</h3>
                  <p class="text-sm text-gray-300">Répondre aux besoins réels de notre communauté pour des rencontres dignes et respectueuses.</p>
                </div>

                <!-- Alternative Saine -->
                <div class="cause-card p-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-lg hover:bg-white/10 transition-all duration-300">
                  <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 0zm0-6a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 11-1.414 1.414L10 5.414 5.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <h3 class="text-lg font-bold text-white mb-2">Alternative Saine</h3>
                  <p class="text-sm text-gray-300">Offrir une alternative vertueuse aux plateformes conventionnelles et respectueuse des valeurs islamiques.</p>
                </div>

                <!-- Intégration Mahram -->
                <div class="cause-card p-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-lg hover:bg-white/10 transition-all duration-300">
                  <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <h3 class="text-lg font-bold text-white mb-2">Rôle du Mahram</h3>
                  <p class="text-sm text-gray-300">Intégrer le Mahram au cœur du processus pour garantir l'éthique et la protection mutuelle.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Témoignages Section -->
    <section class="testimonials-section py-24 px-4 sm:px-6 lg:px-8 relative z-20 bg-gradient-to-b from-dark-slate-800 to-dark-slate-900">
      <div class="max-w-6xl mx-auto">
        <div class="text-center mb-20">
          <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">Histoires de Succès</h2>
          <p class="text-xl text-gray-300 max-w-2xl mx-auto">Connexions authentiques, amour sincère, parcours réels de notre communauté</p>
          <div class="w-24 h-1 bg-gradient-to-r from-coral-pink to-teal mx-auto rounded-full mt-6"></div>
        </div>

        <!-- Testimonial Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Testimonial 1 -->
          <div class="testimonial-card group">
            <div class="testimonial-glass relative p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl h-full">
              <!-- Quote mark -->
              <div class="text-4xl text-teal opacity-50 mb-4">"</div>
              
              <!-- Stars -->
              <div class="flex gap-1 mb-4">
                <span v-for="i in 5" :key="`star-1-${i}`" class="text-yellow-400 text-lg">★</span>
              </div>

              <p class="text-gray-200 italic mb-6 leading-relaxed">
                Rencontre Éthique m'a aidé à trouver quelqu'un qui partage vraiment mes valeurs. La plateforme respecte notre foi et rend les connexions significatives possibles.
              </p>

              <!-- Author -->
              <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-500 to-red-600 flex items-center justify-center text-white font-bold text-lg">
                  A
                </div>
                <div>
                  <p class="font-bold text-white">Aisha & Ahmad</p>
                  <p class="text-sm text-gray-400">Mariés il y a 6 mois</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="testimonial-card group">
            <div class="testimonial-glass relative p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl h-full">
              <!-- Quote mark -->
              <div class="text-4xl text-teal opacity-50 mb-4">"</div>
              
              <!-- Stars -->
              <div class="flex gap-1 mb-4">
                <span v-for="i in 5" :key="`star-2-${i}`" class="text-yellow-400 text-lg">★</span>
              </div>

              <p class="text-gray-200 italic mb-6 leading-relaxed">
                Je me suis sentie en sécurité et respectée tout au long de mon expérience. Le processus de vérification assure des correspondances de qualité. Hautement recommandé pour les chercheurs sérieux!
              </p>

              <!-- Author -->
              <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white font-bold text-lg">
                  F
                </div>
                <div>
                  <p class="font-bold text-white">Fatima & Karim</p>
                  <p class="text-sm text-gray-400">Fiancés récemment</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="testimonial-card group">
            <div class="testimonial-glass relative p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl h-full">
              <!-- Quote mark -->
              <div class="text-4xl text-teal opacity-50 mb-4">"</div>
              
              <!-- Stars -->
              <div class="flex gap-1 mb-4">
                <span v-for="i in 5" :key="`star-3-${i}`" class="text-yellow-400 text-lg">★</span>
              </div>

              <p class="text-gray-200 italic mb-6 leading-relaxed">
                La communauté ici est vraiment soutenante et la mise en correspondance basée sur les valeurs est incroyable. J'ai trouvé mon âme sœur sans compromettre mes principes!
              </p>

              <!-- Author -->
              <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-lg">
                  N
                </div>
                <div>
                  <p class="font-bold text-white">Noor & Hassan</p>
                  <p class="text-sm text-gray-400">Mariage l'année prochaine</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-24 px-4 sm:px-6 lg:px-8 relative z-20">
      <div class="max-w-4xl mx-auto">
        <div class="cta-glass relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl p-12 sm:p-16 md:p-20">
          <!-- Animated background elements -->
          <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-pink-500 to-transparent rounded-full blur-3xl transform translate-x-1/3 -translate-y-1/3"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-br from-teal-500 to-transparent rounded-full blur-3xl transform -translate-x-1/3 translate-y-1/3"></div>
          </div>

          <!-- Content -->
          <div class="relative z-10 text-center">
            <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">
              Retrouvez les Profils Sérieux Près de Chez Vous
            </h2>
            <p class="text-xl text-gray-200 mb-12 max-w-2xl mx-auto">
              Rejoignez des milliers de chercheurs sincères trouvant l'amour et la connexion de la manière islamique. Votre voyage vers trouver votre âme sœur commence ici.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
              <button class="premium-btn btn-primary-large group relative px-12 py-5 text-xl font-bold text-white bg-gradient-to-r from-pink-500 to-red-600 rounded-full overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105">
                <span class="relative z-10 flex items-center justify-center gap-2">
                  Créer mon profil gratuitement
                  <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </button>
              <button class="premium-btn btn-secondary-large px-12 py-5 text-xl font-bold text-sapphire-blue border-2 border-white rounded-full hover:bg-white/10 transition-all duration-300 backdrop-blur-md bg-white/5">
                En Savoir Plus
              </button>
            </div>

            <!-- Trust badges -->
            <div class="mt-12 flex flex-wrap justify-center gap-6 text-sm text-gray-300">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-teal" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span>Profils Vérifiés</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-teal" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <span>100% Sécurisé & Privé</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-teal" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                  <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2h-.5A1.5 1.5 0 006 6.5v9a1.5 1.5 0 01-1.5 1.5H4a2 2 0 01-2-2v-4a1 1 0 112 0v4h.5a.5.5 0 00.5-.5v-9a.5.5 0 00-.5-.5H6a1 1 0 100-2H6a2 2 0 00-2 2v4a1 1 0 11-2 0v-4a2 2 0 012-2z"/>
                </svg>
                <span>Rencontres Sérieuses Uniquement</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer relative z-30 border-t border-white/10 bg-dark-slate-950 py-16 px-4 sm:px-6 lg:px-8">
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
          <!-- Brand -->
          <div>
            <h3 class="text-2xl font-bold text-white mb-4">Rencontre Éthique</h3>
            <p class="text-gray-400 text-sm leading-relaxed">Plateforme matrimoniale islamique premium pour des rencontres sérieuses et éthiques.</p>
          </div>

          <!-- Quick Links -->
          <div>
            <h4 class="text-white font-bold mb-4">Plateforme</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-teal transition-colors">Comment Ça Marche</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Parcourir les Profils</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Histoires de Succès</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Tarification</a></li>
            </ul>
          </div>

          <!-- Company -->
          <div>
            <h4 class="text-white font-bold mb-4">Entreprise</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-teal transition-colors">À Propos de Nous</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Blog</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Carrières</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Contact</a></li>
            </ul>
          </div>

          <!-- Legal -->
          <div>
            <h4 class="text-white font-bold mb-4">Légal</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-teal transition-colors">Politique de Confidentialité</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Conditions d'Utilisation</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Directives Communautaires</a></li>
              <li><a href="#" class="hover:text-teal transition-colors">Politique des Cookies</a></li>
            </ul>
          </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
          <p class="text-sm text-gray-500">© 2026 Rencontre Éthique. Tous droits réservés. | Engagés aux valeurs islamiques.</p>
          <div class="flex gap-6">
            <a href="#" class="text-gray-400 hover:text-teal transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.29 20v-7.21H5.67V9.6h2.62V7.26c0-2.59 1.58-4 3.88-4 1.1 0 2.08.08 2.36.12v2.74h-1.62c-1.27 0-1.52.6-1.52 1.48v1.94h3.04l-.4 3.19h-2.64V20"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-teal transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M19 3.795a6.738 6.738 0 01-1.885.516 3.262 3.262 0 001.431-1.795 6.541 6.541 0 01-2.080.794 3.28 3.28 0 00-5.674 2.243c0 .26.03.51.09.75a9.318 9.318 0 01-6.767-3.429 3.29 3.29 0 001.015 4.382 3.281 3.281 0 01-1.487-.411v.041a3.288 3.288 0 002.633 3.22 3.28 3.28 0 01-.148.627 3.268 3.268 0 002.991 2.207 6.579 6.579 0 01-4.076 1.404c-.264 0-.528-.015-.788-.046a9.364 9.364 0 005.064 1.478c6.075 0 9.388-5.582 9.388-10.422 0-.159-.003-.318-.01-.476a6.727 6.727 0 001.65-1.72z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-teal transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'Homepage',
  data() {
    return {
      scrollY: 0,
    };
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll);
    this.initAnimations();
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  methods: {
    handleScroll() {
      this.scrollY = window.scrollY;
    },
    initAnimations() {
      // Observe elements for scroll-triggered animations
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1 });

      document.querySelectorAll('.value-card, .testimonial-card, .step-card, .cause-card').forEach((el) => {
        observer.observe(el);
      });
    },
  },
};
</script>

<style scoped>
/* ===== Color Variables ===== */
:root {
  --sapphire-blue: #0f3a7d;
  --coral-pink: #ff6b6b;
  --teal: #17a2b8;
  --white: #ffffff;
  --dark-slate-900: #0f172a;
  --dark-slate-800: #1e293b;
  --dark-slate-950: #020617;
}

.luxury-homepage {
  background: linear-gradient(to bottom, #0f172a, #020617);
  color: white;
  overflow-x: hidden;
}

/* ===== HERO SECTION ===== */
.hero-section {
  background: linear-gradient(135deg, #0f172a 0%, #1a2847 50%, #0f172a 100%);
}

.hero-background {
  background: 
    radial-gradient(ellipse 80% 60% at 50% 0%, rgba(255, 107, 107, 0.15) 0%, transparent 60%),
    radial-gradient(ellipse 80% 60% at 0% 100%, rgba(23, 162, 184, 0.1) 0%, transparent 70%),
    linear-gradient(135deg, #0f3a7d 0%, #1a4d6d 50%, #020617 100%);
}

.gradient-1,
.gradient-2,
.gradient-3 {
  position: absolute;
  border-radius: 50%;
  filter: blur(100px);
  opacity: 0.3;
  animation: float 20s ease-in-out infinite;
}

.gradient-1 {
  width: 600px;
  height: 600px;
  background: linear-gradient(135deg, #ff6b6b 0%, #ff8787 100%);
  top: -200px;
  right: -100px;
  animation-duration: 15s;
}

.gradient-2 {
  width: 500px;
  height: 500px;
  background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
  bottom: -150px;
  left: -100px;
  animation-duration: 18s;
  animation-delay: 2s;
}

.gradient-3 {
  width: 400px;
  height: 400px;
  background: linear-gradient(135deg, #0f3a7d 0%, #1a5490 100%);
  top: 50%;
  left: 50%;
  animation-duration: 22s;
  animation-delay: 4s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) translateX(0px); }
  50% { transform: translateY(30px) translateX(20px); }
}

.mosque-dome {
  position: absolute;
  width: 300px;
  height: 300px;
  top: 10%;
  left: 10%;
  opacity: 0.4;
  animation: float 25s ease-in-out infinite;
  filter: drop-shadow(0 0 60px rgba(255, 107, 107, 0.3));
}

.crescent-moon {
  position: absolute;
  width: 120px;
  height: 120px;
  border: 8px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  box-shadow: inset -8px 0 20px rgba(0, 0, 0, 0.5), 0 0 40px rgba(255, 107, 107, 0.2);
  animation: moonOscillate 8s ease-in-out infinite;
}

@keyframes moonOscillate {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(20px) rotate(5deg); }
}

.geometric-pattern {
  position: absolute;
  opacity: 0.1;
  animation: rotate 30s linear infinite;
}

.pattern-1 {
  width: 200px;
  height: 200px;
  top: 20%;
  right: 15%;
  border: 2px solid #ff6b6b;
  clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
}

.pattern-2 {
  width: 150px;
  height: 150px;
  bottom: 20%;
  left: 10%;
  border: 2px solid #17a2b8;
  clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%);
  transform: rotate(45deg);
}

.pattern-3 {
  width: 180px;
  height: 180px;
  top: 60%;
  right: 5%;
  border: 2px solid #0f3a7d;
  border-radius: 50%;
}

@keyframes rotate {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.hero-content {
  animation: slideUp 1s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hero-title {
  font-size: clamp(2rem, 8vw, 7rem);
  letter-spacing: -2px;
  text-shadow: 0 20px 60px rgba(255, 107, 107, 0.3);
}

.hero-subtitle {
  text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.animate-fade-in {
  animation: fadeInUp 0.8s ease-out 0s forwards;
}

.animate-fade-in-delay {
  animation: fadeInUp 0.8s ease-out 0.3s forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-bounce {
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

/* ===== PREMIUM BUTTONS ===== */
.premium-btn {
  position: relative;
  font-weight: 600;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
}

.btn-primary {
  min-width: 200px;
}

.btn-secondary {
  min-width: 160px;
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 20px 60px rgba(255, 107, 107, 0.4) !important;
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.15) !important;
  transform: translateY(-3px);
}

.btn-primary-large {
  min-width: 240px;
}

.btn-secondary-large {
  min-width: 200px;
}

/* ===== WHY SECTION ===== */
.why-section {
  position: relative;
}

.value-card {
  perspective: 1000px;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-glass {
  border-radius: 24px;
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.value-card:hover .card-glass {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 20px 60px rgba(255, 107, 107, 0.2), 0 0 40px rgba(23, 162, 184, 0.1);
}

.card-icon {
  transition: all 0.3s ease;
}

.value-card:hover .card-icon {
  box-shadow: 0 10px 40px rgba(255, 107, 107, 0.4);
  transform: scale(1.1);
}

.value-card.animate-in {
  animation: slideInCard 0.6s ease-out forwards;
}

@keyframes slideInCard {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===== HOW IT WORKS SECTION ===== */
.step-card {
  position: relative;
}

.step-number {
  background: linear-gradient(135deg, #ff6b6b 0%, #ff8787 100%);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.step-card:nth-child(2) .step-number {
  background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
}

.step-card:nth-child(3) .step-number {
  background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
}

.step-card:nth-child(4) .step-number {
  background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
}

.step-card.animate-in .step-number {
  animation: scaleUp 0.6s ease-out;
}

@keyframes scaleUp {
  from {
    opacity: 0;
    transform: scale(0.5);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.step-card.animate-in .step-content {
  animation: fadeInUp 0.6s ease-out 0.2s forwards;
  opacity: 0;
}

/* ===== CAUSE SECTION ===== */
.cause-section {
  position: relative;
}

.cause-glass {
  backdrop-filter: blur(30px);
  -webkit-backdrop-filter: blur(30px);
  position: relative;
  overflow: hidden;
}

.cause-glass::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
  pointer-events: none;
}

.cause-card {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.cause-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(255, 107, 107, 0.1);
}

.cause-card.animate-in {
  animation: slideInCard 0.6s ease-out forwards;
}

/* ===== TESTIMONIALS SECTION ===== */
.testimonial-card {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.testimonial-glass {
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.testimonial-card:hover .testimonial-glass {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 20px 60px rgba(255, 107, 107, 0.2), 0 0 40px rgba(23, 162, 184, 0.1);
  transform: translateY(-8px);
}

.testimonial-card.animate-in {
  animation: slideInCard 0.6s ease-out forwards;
}

/* ===== CTA SECTION ===== */
.cta-glass {
  backdrop-filter: blur(30px);
  -webkit-backdrop-filter: blur(30px);
  position: relative;
  overflow: hidden;
}

.cta-glass::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
  pointer-events: none;
}

/* ===== FOOTER ===== */
.footer {
  background: linear-gradient(135deg, #020617 0%, #0f172a 100%);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .hero-title {
    font-size: clamp(1.5rem, 6vw, 3rem);
  }

  .mosque-dome {
    width: 200px;
    height: 200px;
    top: 15%;
    left: 50%;
    transform: translateX(-50%);
  }

  .geometric-pattern {
    opacity: 0.05;
  }

  .value-card {
    width: 100%;
  }

  .step-card {
    margin-bottom: 2rem;
  }

  .cta-glass {
    padding: 2rem;
  }

  .cta-glass h2 {
    font-size: 2rem;
  }

  .cause-glass {
    padding: 2rem;
  }
}

/* ===== UTILITY CLASSES ===== */
.text-sapphire-blue {
  color: #0f3a7d;
}

.text-coral-pink {
  color: #ff6b6b;
}

.text-teal {
  color: #17a2b8;
}

.bg-dark-slate-900 {
  background-color: #0f172a;
}

.bg-dark-slate-800 {
  background-color: #1e293b;
}

.bg-dark-slate-950 {
  background-color: #020617;
}
</style>
