<template>
  <header class="sticky top-0 z-50 bg-[#0f3a7d] shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <!-- Logo -->
      <Link href="/" class="flex items-center gap-3">
        <img src="/logo-new.svg" alt="Rencontre Éthique" class="w-12 h-12">
        <div>
          <div class="font-display text-xl font-semibold text-white" style="font-family:'Cormorant Garamond',serif">Rencontre Éthique</div>
          <div class="text-[10px] uppercase tracking-[.18em] text-[#ff6b6b]">Halal · Mosquée · Sérieux</div>
        </div>
      </Link>

      <!-- Navigation Links (Desktop) -->
      <nav class="hidden md:flex gap-8">
        <a href="#concept" class="text-[#ff6b6b] hover:text-white transition font-medium">Concept</a>
        <a href="#fonctionnement" class="text-[#ff6b6b] hover:text-white transition font-medium">Fonctionnement</a>
        <a href="#temoignages" class="text-[#ff6b6b] hover:text-white transition font-medium">Témoignages</a>
        <a href="/contact" class="text-[#ff6b6b] hover:text-white transition font-medium">Contact</a>
      </nav>

      <!-- Auth Buttons -->
      <div class="flex gap-3 items-center">
        <Link 
          href="/login" 
          class="hidden sm:block text-[#ff6b6b] hover:text-white transition font-medium px-4 py-2"
        >
          Connexion
        </Link>
        <Link 
          href="/register" 
          class="btn-re-gold px-6 py-2 text-sm font-semibold bg-[#ff6b6b] text-[#0f3a7d] rounded-lg hover:bg-[#D4B44D] transition"
        >
          S'inscrire
        </Link>
      </div>
    </div>
  </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.btn-re-gold {
  box-shadow: 0 4px 12px rgba(200, 160, 40, 0.2);
  transition: all 0.3s ease;
}

.btn-re-gold:hover {
  box-shadow: 0 6px 20px rgba(200, 160, 40, 0.3);
  transform: translateY(-2px);
}
</style>
