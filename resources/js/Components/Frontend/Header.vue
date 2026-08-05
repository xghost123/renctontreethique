<template>
  <header class="sticky top-0 z-50 bg-[#0D2218] shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <!-- Logo -->
      <Link href="/" class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-[#C8A028] flex items-center justify-center">
          <svg class="w-6 h-6 text-[#0D2218]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C7.03 2 3 6.03 3 11c0 6.5 9 11 9 11s9-4.5 9-11c0-4.97-4.03-9-9-9zm0 4.5c2.76 0 5 2.24 5 5 0 .55-.45 1-1 1s-1-.45-1-1c0-1.66-1.34-3-3-3-.55 0-1-.45-1-1s.45-1 1-1z"/>
          </svg>
        </div>
        <div>
          <div class="font-display text-xl font-semibold text-white" style="font-family:'Cormorant Garamond',serif">Rencontre Éthique</div>
          <div class="text-[10px] uppercase tracking-[.18em] text-[#C8A028]">Halal · Mosquée · Sérieux</div>
        </div>
      </Link>

      <!-- Navigation Links (Desktop) -->
      <nav class="hidden md:flex gap-8">
        <a href="#concept" class="text-[#C8A028] hover:text-white transition font-medium">Concept</a>
        <a href="#fonctionnement" class="text-[#C8A028] hover:text-white transition font-medium">Fonctionnement</a>
        <a href="#temoignages" class="text-[#C8A028] hover:text-white transition font-medium">Témoignages</a>
        <a href="/contact" class="text-[#C8A028] hover:text-white transition font-medium">Contact</a>
      </nav>

      <!-- Auth Buttons -->
      <div class="flex gap-3 items-center">
        <Link 
          href="/login" 
          class="hidden sm:block text-[#C8A028] hover:text-white transition font-medium px-4 py-2"
        >
          Connexion
        </Link>
        <Link 
          href="/register" 
          class="btn-re-gold px-6 py-2 text-sm font-semibold bg-[#C8A028] text-[#0D2218] rounded-lg hover:bg-[#D4B44D] transition"
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
