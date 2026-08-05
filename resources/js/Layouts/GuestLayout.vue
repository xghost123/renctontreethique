<template>
  <div class="min-h-screen bg-white flex flex-col">
    <!-- Navigation -->
    <nav class="bg-[#0D2218] text-white py-4 sticky top-0 z-50 shadow-lg">
      <div class="container mx-auto px-4 flex justify-between items-center">
        <Link href="/" class="text-2xl font-bold text-[#C8A028]">
          Rencontre Éthique
        </Link>
        
        <div class="hidden md:flex gap-8">
          <Link href="/about" class="hover:text-[#C8A028] transition">À propos</Link>
          <Link href="/how-it-works" class="hover:text-[#C8A028] transition">Comment ça fonctionne</Link>
          <Link href="/faq" class="hover:text-[#C8A028] transition">FAQ</Link>
          <Link href="/contact" class="hover:text-[#C8A028] transition">Contact</Link>
        </div>
        
        <div class="flex gap-4">
          <Link href="/login" class="text-[#C8A028] hover:underline">Connexion</Link>
          <Link href="/register" class="bg-[#C8A028] text-[#0D2218] px-4 py-2 rounded font-semibold hover:bg-[#D4B44D] transition">
            S'inscrire
          </Link>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-[#0D2218] text-white py-12">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <div>
            <h3 class="text-[#C8A028] font-bold mb-4">Rencontre Éthique</h3>
            <p class="text-gray-300">Une plateforme communautaire pour les rencontres sérieuses en vue du mariage.</p>
          </div>
          <div>
            <h4 class="text-[#C8A028] font-semibold mb-4">Liens</h4>
            <ul class="space-y-2 text-gray-300">
              <li><Link href="/about" class="hover:text-[#C8A028]">À propos</Link></li>
              <li><Link href="/how-it-works" class="hover:text-[#C8A028]">Comment ça fonctionne</Link></li>
              <li><Link href="/faq" class="hover:text-[#C8A028]">FAQ</Link></li>
              <li><Link href="/contact" class="hover:text-[#C8A028]">Contact</Link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-[#C8A028] font-semibold mb-4">Légal</h4>
            <ul class="space-y-2 text-gray-300">
              <li><Link href="/privacy" class="hover:text-[#C8A028]">Confidentialité</Link></li>
              <li><Link href="/terms" class="hover:text-[#C8A028]">Conditions</Link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-[#C8A028] font-semibold mb-4">Nous Suivre</h4>
            <div class="flex gap-4">
              <a href="#" class="hover:text-[#C8A028]">Facebook</a>
              <a href="#" class="hover:text-[#C8A028]">Twitter</a>
              <a href="#" class="hover:text-[#C8A028]">Instagram</a>
            </div>
          </div>
        </div>
        <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
          <p>&copy; 2026 Rencontre Éthique. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
