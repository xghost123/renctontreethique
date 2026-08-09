<script setup>
import { ref, onMounted, computed } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
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
const hoveredCard = ref(null);

// Premium Statistics computed
const stats = computed(() => {
    const biodatas = page.props.all_biodatas || [];
    const proposals = page.props.all_proposals || [];
    
    return {
        total_members: Array.isArray(biodatas) ? biodatas.length : 0,
        pending_approvals: Array.isArray(biodatas) ? biodatas.filter(b => b.status === 'pending').length : 0,
        active_users: Array.isArray(biodatas) ? biodatas.filter(b => b.status === 'approved').length : 0,
        flagged_profiles: Math.floor(Array.isArray(biodatas) ? biodatas.length * 0.05 : 0),
    };
});

// Recent Activity mock data
const recentActivity = computed(() => [
    { id: 1, action: 'Profile Approved', user: 'Sarah Johnson', time: '2 minutes ago', icon: '✓', color: 'emerald' },
    { id: 2, action: 'Profile Flagged', user: 'Michael Smith', time: '15 minutes ago', icon: '⚠', color: 'amber' },
    { id: 3, action: 'New Registration', user: 'Emma Wilson', time: '1 hour ago', icon: '✨', color: 'blue' },
    { id: 4, action: 'Profile Rejected', user: 'James Brown', time: '3 hours ago', icon: '✕', color: 'rose' },
    { id: 5, action: 'Verification Complete', user: 'Lisa Anderson', time: '5 hours ago', icon: '🔒', color: 'cyan' },
]);

// Chart data for visualization
const chartData = computed(() => ({
    thisWeek: [45, 52, 48, 61, 55, 67, 72],
    lastWeek: [38, 42, 41, 53, 48, 62, 68],
    days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
}));

const maxValue = computed(() => Math.max(...chartData.value.thisWeek, ...chartData.value.lastWeek));
</script>

<template>
    <AdminLayout>
        <Head title="Admin Dashboard - Rencontre Éthique" />
        
        <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
            <Header :translations :locale :locales :canLogin :canRegister />
            
            <!-- Main Content -->
            <div class="container max-w-7xl mx-auto px-6 py-12">
                
                <!-- Page Header -->
                <div class="mb-12">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-[#0f3a7d] via-[#17a2b8] to-[#0f3a7d] bg-clip-text text-transparent mb-2">
                        Admin Dashboard
                    </h1>
                    <p class="text-slate-400 text-lg">Welcome back. Here's your performance overview.</p>
                </div>

                <!-- Premium Stats Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    
                    <!-- Total Members Card -->
                    <div 
                        class="group relative overflow-hidden rounded-2xl transition-all duration-500 cursor-pointer"
                        @mouseenter="hoveredCard = 'members'"
                        @mouseleave="hoveredCard = null"
                    >
                        <!-- Premium Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#0f3a7d]/10 via-slate-800/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Glassmorphism Card -->
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-[#0f3a7d]/50 transition-all duration-300 shadow-2xl">
                            <!-- Glow Effect -->
                            <div class="absolute -inset-px bg-gradient-to-r from-[#0f3a7d]/20 to-[#17a2b8]/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl -z-10"></div>
                            
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-3">Total Members</p>
                                    <p class="text-5xl font-bold text-white mb-2">{{ stats.total_members }}</p>
                                    <p class="text-emerald-400 text-sm font-medium">↑ 12% from last month</p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#0f3a7d]/30 to-[#17a2b8]/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-[#17a2b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Approvals Card -->
                    <div 
                        class="group relative overflow-hidden rounded-2xl transition-all duration-500 cursor-pointer"
                        @mouseenter="hoveredCard = 'pending'"
                        @mouseleave="hoveredCard = null"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 via-slate-800/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-amber-500/50 transition-all duration-300 shadow-2xl">
                            <div class="absolute -inset-px bg-gradient-to-r from-amber-500/20 to-orange-500/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl -z-10"></div>
                            
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-3">Pending Approvals</p>
                                    <p class="text-5xl font-bold text-white mb-2">{{ stats.pending_approvals }}</p>
                                    <p class="text-amber-400 text-sm font-medium">⏳ Awaiting review</p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Users Card -->
                    <div 
                        class="group relative overflow-hidden rounded-2xl transition-all duration-500 cursor-pointer"
                        @mouseenter="hoveredCard = 'active'"
                        @mouseleave="hoveredCard = null"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-slate-800/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-emerald-500/50 transition-all duration-300 shadow-2xl">
                            <div class="absolute -inset-px bg-gradient-to-r from-emerald-500/20 to-teal-500/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl -z-10"></div>
                            
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-3">Active Users</p>
                                    <p class="text-5xl font-bold text-white mb-2">{{ stats.active_users }}</p>
                                    <p class="text-emerald-400 text-sm font-medium">✓ Currently online</p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500/30 to-[#17a2b8]/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h-2m0 0h-2m2 0v2m0 -2v-2m-4 4a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flagged Profiles Card -->
                    <div 
                        class="group relative overflow-hidden rounded-2xl transition-all duration-500 cursor-pointer"
                        @mouseenter="hoveredCard = 'flagged'"
                        @mouseleave="hoveredCard = null"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-500/10 via-slate-800/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-rose-500/50 transition-all duration-300 shadow-2xl">
                            <div class="absolute -inset-px bg-gradient-to-r from-rose-500/20 to-red-500/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl -z-10"></div>
                            
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-3">Flagged Profiles</p>
                                    <p class="text-5xl font-bold text-white mb-2">{{ stats.flagged_profiles }}</p>
                                    <p class="text-rose-400 text-sm font-medium">⚠ Requires attention</p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-rose-500/30 to-red-500/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts & Analytics Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                    
                    <!-- Registration Trends Chart -->
                    <div class="lg:col-span-2 group relative overflow-hidden rounded-2xl">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-[#17a2b8]/50 transition-all duration-300 shadow-2xl">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">Registration Trends</h3>
                                    <p class="text-slate-400 text-sm">7-day performance metrics</p>
                                </div>
                                <div class="flex gap-2">
                                    <div class="w-3 h-3 rounded-full bg-[#17a2b8]"></div>
                                    <p class="text-slate-400 text-xs">This Week</p>
                                </div>
                            </div>
                            
                            <!-- Mini Chart -->
                            <div class="h-48 flex items-end justify-between gap-3 pb-4 border-b border-white/10">
                                <div v-for="(day, idx) in chartData.days" :key="day" class="flex-1 flex flex-col items-center">
                                    <div 
                                        class="w-full bg-gradient-to-t from-[#17a2b8] to-[#0f3a7d] rounded-lg transition-all duration-300 hover:opacity-80 cursor-pointer relative group/bar"
                                        :style="{ height: (chartData.thisWeek[idx] / maxValue) * 100 + '%', minHeight: '2px' }"
                                    >
                                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/bar:opacity-100 transition-opacity whitespace-nowrap">
                                            {{ chartData.thisWeek[idx] }}
                                        </div>
                                    </div>
                                    <p class="text-slate-500 text-xs mt-4">{{ day }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-between text-sm">
                                <div>
                                    <p class="text-slate-400">Avg. Daily</p>
                                    <p class="text-xl font-bold text-[#17a2b8]">{{ (chartData.thisWeek.reduce((a, b) => a + b, 0) / chartData.thisWeek.length).toFixed(0) }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-400">Peak Day</p>
                                    <p class="text-xl font-bold text-emerald-400">{{ Math.max(...chartData.thisWeek) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Health -->
                    <div class="group relative overflow-hidden rounded-2xl">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 h-full hover:border-[#0f3a7d]/50 transition-all duration-300 shadow-2xl flex flex-col">
                            <h3 class="text-xl font-bold text-white mb-8">Platform Health</h3>
                            
                            <!-- Health Metrics -->
                            <div class="space-y-6 flex-1">
                                <!-- System Status -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400 text-sm font-medium">System Status</span>
                                        <span class="text-emerald-400 text-xs font-bold">100%</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full"></div>
                                    </div>
                                </div>

                                <!-- Database Performance -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400 text-sm font-medium">DB Performance</span>
                                        <span class="text-cyan-400 text-xs font-bold">98%</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-11/12 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full"></div>
                                    </div>
                                </div>

                                <!-- API Uptime -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400 text-sm font-medium">API Uptime</span>
                                        <span class="text-blue-400 text-xs font-bold">99.8%</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-11/12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
                                    </div>
                                </div>

                                <!-- Storage -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400 text-sm font-medium">Storage Used</span>
                                        <span class="text-purple-400 text-xs font-bold">64%</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-8/12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>

                            <button class="mt-6 w-full py-2 px-4 bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] text-white rounded-lg font-medium hover:shadow-lg hover:shadow-[#0f3a7d]/50 transition-all duration-300 text-sm">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="group relative overflow-hidden rounded-2xl">
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-white/20 transition-all duration-300 shadow-2xl">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-xl font-bold text-white mb-1">Recent Activity</h3>
                                <p class="text-slate-400 text-sm">Latest events and actions</p>
                            </div>
                            <Link href="#" class="text-[#17a2b8] hover:text-[#0f3a7d] text-sm font-semibold transition-colors">View All →</Link>
                        </div>

                        <!-- Activity List -->
                        <div class="space-y-4">
                            <div 
                                v-for="activity in recentActivity"
                                :key="activity.id"
                                class="group/item flex items-center gap-4 p-4 rounded-xl border border-white/5 hover:border-white/20 hover:bg-white/5 transition-all duration-300 cursor-pointer"
                            >
                                <!-- Activity Icon -->
                                <div :class="`w-10 h-10 rounded-lg flex items-center justify-center font-bold text-white bg-${activity.color}-500/20`">
                                    {{ activity.icon }}
                                </div>

                                <!-- Activity Content -->
                                <div class="flex-1">
                                    <p class="text-white font-semibold">{{ activity.action }}</p>
                                    <p class="text-slate-400 text-sm">{{ activity.user }}</p>
                                </div>

                                <!-- Time -->
                                <div class="text-right">
                                    <p class="text-slate-400 text-sm">{{ activity.time }}</p>
                                </div>

                                <!-- Arrow Indicator -->
                                <svg class="w-5 h-5 text-slate-500 group-hover/item:text-[#17a2b8] group-hover/item:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/* Animated gradient background */
@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* Global smooth easing */
:deep(*) {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
