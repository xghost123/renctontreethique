<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineProps({
  open: Boolean,
  user: Object,
});

defineEmits(['close']);

const isOpen = computed(() => this.open);

const navItems = [
  { label: 'Dashboard', route: 'user.dashboard', icon: 'home' },
  { label: 'Mon Profil', route: 'user.profile', icon: 'user' },
  { label: 'Parcourir', route: 'search.browse', icon: 'search' },
  { label: 'Demandes', route: 'user.proposals', icon: 'mail' },
  { label: 'Mes Likes', route: 'user.likes', icon: 'heart' },
  { label: 'Messages', route: 'user.messages', icon: 'chat' },
  { label: 'Paramètres', route: 'user.settings', icon: 'settings' },
];

const form = useForm({});

const logout = () => {
  form.post(route('logout'));
};

const getIcon = (name) => {
  const icons = {
    home: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4m-11-9l2-3m0 0l7-4 7 4"/></svg>',
    user: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
    search: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
    mail: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
    heart: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
    chat: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>',
    settings: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>',
    logout: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>',
  };
  return icons[name] || '';
};
</script>

<template>
  <!-- Sidebar -->
  <aside 
    :class="[
      'fixed left-0 top-0 h-screen w-72 bg-white border-r border-gray-100 z-40 transform transition-transform duration-300 flex flex-col',
      open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    style="box-shadow: 0 0 30px rgba(15, 58, 125, 0.08)"
  >
    <!-- Logo/Branding -->
    <div class="p-6 border-b border-gray-100">
      <Link href="/" class="flex items-center gap-3 group">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0f3a7d] to-[#17a2b8] flex items-center justify-center text-white font-bold text-lg group-hover:shadow-lg transition-shadow">
          RE
        </div>
        <div>
          <div class="font-display font-semibold text-[#0f3a7d]" style="font-family:'Cormorant Garamond',serif">Rencontre</div>
          <div class="text-xs text-[#8A9680] font-medium">Éthique</div>
        </div>
      </Link>
    </div>

    <!-- User Profile Card -->
    <div class="px-4 py-4 border-b border-gray-100">
      <Link :href="route('user.profile')" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#0f3a7d] to-[#ff6b6b] flex items-center justify-center text-white font-semibold text-sm">
          {{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-[#0f3a7d] truncate">{{ user.name || 'Utilisateur' }}</div>
          <div class="text-xs text-[#8A9680] truncate">{{ user.email }}</div>
        </div>
      </Link>
    </div>

    <!-- Navigation Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
      <div v-for="item in navItems" :key="item.route">
        <Link
          :href="route(item.route)"
          class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#374151] hover:bg-gray-50 transition-all group"
          :class="$page.component.startsWith('User/') || $page.component.startsWith('Search/') ? 'text-[#0f3a7d] bg-[#0f3a7d]/5 font-semibold' : ''"
        >
          <div class="w-5 h-5 flex-shrink-0 text-[#8A9680] group-hover:text-[#0f3a7d] transition-colors" v-html="getIcon(item.icon)"></div>
          <span class="text-sm font-medium flex-1">{{ item.label }}</span>
        </Link>
      </div>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-gray-100">
      <button
        @click="logout"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-[#374151] hover:bg-red-50 hover:text-red-600 transition-all font-medium text-sm group"
      >
        <div class="w-5 h-5 flex-shrink-0 text-[#8A9680] group-hover:text-red-600 transition-colors" v-html="getIcon('logout')"></div>
        <span>Déconnexion</span>
      </button>
    </div>
  </aside>
</template>

<style scoped>
aside {
  backdrop-filter: blur(10px);
}
</style>
