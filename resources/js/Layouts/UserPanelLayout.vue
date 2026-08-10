<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import UserSidebar from '../Components/User/Sidebar.vue';
import UserHeader from '../Components/User/Header.vue';

const page = usePage();
const sidebarOpen = ref(false);

const user = computed(() => page.props.auth?.user || {});
const notifications = computed(() => page.props.notifications || []);
const unreadCount = computed(() => notifications.value.filter(n => !n.read).length);

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
  sidebarOpen.value = false;
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-[#FBF7F0] via-white to-[#F5EEDD]">
    <!-- Sidebar -->
    <UserSidebar 
      :open="sidebarOpen" 
      :user="user"
      @close="closeSidebar"
    />

    <!-- Main Content -->
    <div class="lg:ml-72 min-h-screen flex flex-col">
      <!-- Header -->
      <UserHeader 
        :user="user" 
        :unread-count="unreadCount"
        @toggle-sidebar="toggleSidebar"
      />

      <!-- Page Content -->
      <main class="flex-1 overflow-auto">
        <div class="p-4 md:p-8">
          <slot />
        </div>
      </main>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <Transition 
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      enter-from-class="opacity-0"
      leave-to-class="opacity-0"
    >
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        @click="closeSidebar"
      />
    </Transition>
  </div>
</template>

<style scoped>
</style>
