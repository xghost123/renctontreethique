<script setup>
import { ref, onMounted, computed } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Header from '../../Components/Admin/Header.vue';

defineProps({
    translations: Object,
    front_end_translations: Object,
    districts: Object,
    locale: String,
    locales: Array,
    canLogin: Boolean,
    canRegister: Boolean,
    all_biodatas: Object,
    biodata_updates: Object,
    all_proposals: Object,
    all_terms: Object,
});

const page = usePage();
const selectedTab = ref(0);

// Statistics computed
const stats = computed(() => {
    const biodatas = page.props.all_biodatas || [];
    const proposals = page.props.all_proposals || [];
    
    return {
        total_profiles: Array.isArray(biodatas) ? biodatas.length : 0,
        pending_profiles: Array.isArray(biodatas) ? biodatas.filter(b => b.status === 'pending').length : 0,
        approved_profiles: Array.isArray(biodatas) ? biodatas.filter(b => b.status === 'approved').length : 0,
        pending_proposals: Array.isArray(proposals) ? proposals.filter(p => p.status === 'pending').length : 0,
    };
});

function changeTab(index) {
    selectedTab.value = index;
}
</script>

<template>
    <AdminLayout>
        <Head title="Admin Dashboard - Rencontre Éthique" />
        
        <div class="min-h-screen bg-gradient-to-br from-[#0D2218] via-[#1a3a28] to-[#0D2218]">
            <Header :translations :locale :locales :canLogin :canRegister />
            
            <!-- Main Content -->
            <div class="container max-w-7xl mx-auto px-4 py-8">
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Total Profiles -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:border-[#C8A028]/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[#C8A028]/70 text-sm font-medium">Total Profiles</p>
                                <p class="text-white text-3xl font-bold mt-2">{{ stats.total_profiles }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-[#C8A028]/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#C8A028]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Profiles -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:border-yellow-400/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-400/70 text-sm font-medium">Pending Approval</p>
                                <p class="text-white text-3xl font-bold mt-2">{{ stats.pending_profiles }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-yellow-400/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Profiles -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:border-green-400/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-400/70 text-sm font-medium">Approved</p>
                                <p class="text-white text-3xl font-bold mt-2">{{ stats.approved_profiles }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-green-400/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Proposals -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:border-purple-400/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-400/70 text-sm font-medium">Proposals</p>
                                <p class="text-white text-3xl font-bold mt-2">{{ stats.pending_proposals }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-purple-400/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.646 7.23a2 2 0 01-1.789 1.106H2a2 2 0 01-2-2V8a2 2 0 012-2h15.25a2 2 0 012 2v1" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/20 p-6">
                    <TabGroup :selectedIndex="selectedTab" @change="changeTab">
                        <!-- Tab Navigation -->
                        <TabList class="flex gap-2 border-b border-white/10 pb-4 overflow-x-auto">
                            <Tab v-slot="{ selected }" as="template">
                                <button :class="[
                                    'px-4 py-2 font-medium text-sm rounded-lg transition-all whitespace-nowrap',
                                    selected 
                                        ? 'bg-[#C8A028] text-[#0D2218] shadow-lg'
                                        : 'text-[#C8A028]/60 hover:text-[#C8A028] hover:bg-white/5'
                                ]">
                                    👥 Biodatas
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <button :class="[
                                    'px-4 py-2 font-medium text-sm rounded-lg transition-all whitespace-nowrap',
                                    selected 
                                        ? 'bg-[#C8A028] text-[#0D2218] shadow-lg'
                                        : 'text-[#C8A028]/60 hover:text-[#C8A028] hover:bg-white/5'
                                ]">
                                    💍 Proposals
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <button :class="[
                                    'px-4 py-2 font-medium text-sm rounded-lg transition-all whitespace-nowrap',
                                    selected 
                                        ? 'bg-[#C8A028] text-[#0D2218] shadow-lg'
                                        : 'text-[#C8A028]/60 hover:text-[#C8A028] hover:bg-white/5'
                                ]">
                                    ✅ Approved
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <button :class="[
                                    'px-4 py-2 font-medium text-sm rounded-lg transition-all whitespace-nowrap',
                                    selected 
                                        ? 'bg-[#C8A028] text-[#0D2218] shadow-lg'
                                        : 'text-[#C8A028]/60 hover:text-[#C8A028] hover:bg-white/5'
                                ]">
                                    ⚙️ Settings
                                </button>
                            </Tab>
                        </TabList>

                        <!-- Tab Content -->
                        <TabPanels class="mt-6">
                            <TabPanel class="text-white">
                                <div class="bg-white/5 rounded-xl p-6">
                                    <h3 class="text-xl font-bold text-[#C8A028] mb-4">Biodata Management</h3>
                                    <p class="text-white/60">Manage user profiles and approve/reject submissions.</p>
                                </div>
                            </TabPanel>

                            <TabPanel class="text-white">
                                <div class="bg-white/5 rounded-xl p-6">
                                    <h3 class="text-xl font-bold text-[#C8A028] mb-4">Proposal Management</h3>
                                    <p class="text-white/60">Review and manage marriage proposals between members.</p>
                                </div>
                            </TabPanel>

                            <TabPanel class="text-white">
                                <div class="bg-white/5 rounded-xl p-6">
                                    <h3 class="text-xl font-bold text-[#C8A028] mb-4">Approved Profiles</h3>
                                    <p class="text-white/60">View and manage approved member profiles.</p>
                                </div>
                            </TabPanel>

                            <TabPanel class="text-white">
                                <div class="bg-white/5 rounded-xl p-6">
                                    <h3 class="text-xl font-bold text-[#C8A028] mb-4">Settings & Configuration</h3>
                                    <p class="text-white/60">Configure system settings and manage content.</p>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </TabGroup>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/* Smooth transitions */
:deep(*) {
    @apply transition-all duration-200;
}

/* Better hover effects */
:deep(button:hover) {
    transform: translateY(-2px);
}

/* Glassmorphism effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}
</style>
