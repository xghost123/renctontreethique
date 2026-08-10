<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  user: Object,
  unreadCount: Number,
});

defineEmits(['toggle-sidebar']);

const greetingTime = computed(() => {
  const hour = new Date().getHours();
  if (hour < 12) return 'Bonjour';
  if (hour < 18) return 'Bon après-midi';
  return 'Bonsoir';
});

const firstName = computed(() => {
  return props.user?.name?.split(' ')[0] || 'Utilisateur';
});

const props = {
  user: Object,
  unreadCount: Number,
};
</script>

<template>
  <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="flex items-center justify-between px-4 md:px-8 py-4">
      <!-- Mobile Menu Button + Greeting -->
      <div class="flex items-center gap-4 flex-1">
        <button
          @click="$emit('toggle-sidebar')"
          class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl hover:bg-gray-100 transition-colors text-[#374151]"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>

        <div class="hidden sm:block">
          <h1 class="text-lg md:text-xl font-display font-semibold text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">
            {{ greetingTime }}, {{ firstName }}
          </h1>
          <p class="text-xs text-[#8A9680] mt-0.5">{{ new Date().toLocaleDateString('fr-FR', { weekday: 'long', month: 'long', day: 'numeric' }) }}</p>
        </div>
      </div>

      <!-- Right Actions -->
      <div class="flex items-center gap-3">
        <!-- Notifications -->
        <Link
          :href="route('notifications.index')"
          class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl hover:bg-gray-100 transition-colors text-[#374151]"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          <span v-if="unreadCount" class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-[#ff6b6b] rounded-full min-w-5 h-5">
            {{ unreadCount }}
          </span>
        </Link>

        <!-- Messages -->
        <Link
          :href="route('user.messages')"
          class="inline-flex items-center justify-center w-10 h-10 rounded-xl hover:bg-gray-100 transition-colors text-[#374151]"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </Link>

        <!-- User Menu -->
        <Link
          :href="route('user.profile')"
          class="inline-flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition-colors group"
        >
          <div class="hidden md:block text-right">
            <div class="text-sm font-semibold text-[#0f3a7d]">{{ user.name }}</div>
            <div class="text-xs text-[#8A9680]">Profil</div>
          </div>
          <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#0f3a7d] to-[#ff6b6b] flex items-center justify-center text-white font-semibold text-sm group-hover:shadow-md transition-shadow">
            {{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}
          </div>
        </Link>
      </div>
    </div>
  </header>
</template>

<style scoped>
header {
  background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(251,247,240,0.95) 100%);
}
</style>
