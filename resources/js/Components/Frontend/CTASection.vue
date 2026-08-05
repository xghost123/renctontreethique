<template>
  <section class="py-12 px-4">
    <div class="container mx-auto">
      <div class="bg-gradient-to-r from-[#0D2218] to-[#0f3a7d] rounded-lg p-8 md:p-12 text-white relative overflow-hidden">
        <!-- Accent element -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-[#C8A028]/10 rounded-full -mr-20 -mt-20"></div>
        
        <div class="relative z-10">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ title }}</h2>
          <p class="text-gray-200 text-lg mb-8 max-w-2xl">{{ subtitle }}</p>
          <Link :href="buttonLink" class="inline-block bg-[#C8A028] text-[#0D2218] px-8 py-3 rounded-lg font-bold hover:bg-[#D4B44D] transition transform hover:scale-105">
            {{ buttonText }}
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  title: String,
  subtitle: String,
  buttonText: {
    type: String,
    default: 'Commencer'
  },
  buttonLink: {
    type: String,
    default: '/register'
  }
});
</script>
