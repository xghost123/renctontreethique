<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isMobileMenuOpen = ref(false);

const navItems = [
    { label: 'Dashboard', href: '/admin', icon: '📊' },
    { label: 'Users', href: '/admin/users', icon: '👥' },
    { label: 'Biodata', href: '/admin/biodata', icon: '📋' },
    { label: 'Reports', href: '/admin/reports', icon: '📈' },
    { label: 'Moderation', href: '/admin/moderation', icon: '⚖️' },
];

const isActive = (href) => {
    return page.url.startsWith(href);
};
</script>

<template>
    <aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-slate-900 to-slate-950 border-r border-white/10 shadow-2xl z-50 hidden lg:flex flex-col">
        <!-- Logo -->
        <div class="px-6 py-8 border-b border-white/10">
            <Link href="/admin" class="flex items-center gap-3 text-decoration-none">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#0f3a7d] to-[#17a2b8] flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">Rencontre</p>
                    <p class="text-[#C8A028] text-xs">Admin</p>
                </div>
            </Link>
        </div>

        <!-- Navigation Items -->
        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            <Link 
                v-for="item in navItems"
                :key="item.href"
                :href="item.href"
                class="flex items-center gap-3 px-4 py-3 rounded-lg text-decoration-none transition-all duration-300"
                :class="isActive(item.href)
                    ? 'bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] text-white shadow-lg shadow-[#0f3a7d]/30'
                    : 'text-slate-400 hover:bg-white/5 hover:text-white'"
            >
                <span class="text-xl">{{ item.icon }}</span>
                <span class="font-medium">{{ item.label }}</span>
                <svg v-if="isActive(item.href)" class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
            </Link>
        </nav>

        <!-- Footer -->
        <div class="px-4 py-6 border-t border-white/10 space-y-3">
            <div class="px-4 py-3 rounded-lg bg-white/5 border border-white/10">
                <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Version</p>
                <p class="text-white text-sm font-semibold">1.0.0</p>
            </div>
        </div>
    </aside>

    <!-- Mobile Menu Toggle -->
    <button 
        @click="isMobileMenuOpen = !isMobileMenuOpen"
        class="fixed bottom-6 right-6 lg:hidden w-14 h-14 rounded-full bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] text-white shadow-lg shadow-[#0f3a7d]/50 flex items-center justify-center z-40"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Mobile Menu -->
    <div 
        v-if="isMobileMenuOpen"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        @click="isMobileMenuOpen = false"
    >
        <aside class="absolute left-0 top-0 h-full w-64 bg-gradient-to-b from-slate-900 to-slate-950 border-r border-white/10 shadow-2xl flex flex-col">
            <!-- Logo -->
            <div class="px-6 py-8 border-b border-white/10">
                <Link href="/admin" class="flex items-center gap-3 text-decoration-none" @click="isMobileMenuOpen = false">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#0f3a7d] to-[#17a2b8] flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Rencontre</p>
                        <p class="text-[#C8A028] text-xs">Admin</p>
                    </div>
                </Link>
            </div>

            <!-- Navigation Items -->
            <nav class="flex-1 px-4 py-8 space-y-2">
                <Link 
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-decoration-none transition-all duration-300"
                    :class="isActive(item.href)
                        ? 'bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] text-white shadow-lg'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                    @click="isMobileMenuOpen = false"
                >
                    <span class="text-xl">{{ item.icon }}</span>
                    <span class="font-medium">{{ item.label }}</span>
                </Link>
            </nav>
        </aside>
    </div>
</template>

<style scoped>
/* Smooth scrolling for nav */
nav {
    scroll-behavior: smooth;
}

/* Remove link underline */
:deep(a) {
    text-decoration: none;
}
</style>
