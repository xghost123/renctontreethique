<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Header from '../../Components/Admin/Header.vue';
import Navigation from '../../Components/Admin/Navigation.vue';

defineProps({
    translations: Object,
    front_end_translations: Object,
    locale: String,
    locales: Array,
    canLogin: Boolean,
    canRegister: Boolean,
});

const page = usePage();
const selectedPeriod = ref('month');

// Chart data
const registrationData = computed(() => ({
    days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    thisWeek: [45, 52, 48, 61, 55, 67, 72],
    lastWeek: [38, 42, 41, 53, 48, 62, 68],
}));

const maxValue = computed(() => Math.max(...registrationData.value.thisWeek, ...registrationData.value.lastWeek));

const genderDistribution = computed(() => ({
    male: 62,
    female: 58,
}));

const statusDistribution = computed(() => ({
    approved: 85,
    pending: 28,
    flagged: 7,
    rejected: 5,
}));

const keyMetrics = computed(() => [
    { label: 'Total Members', value: 185, change: '+12%', color: 'emerald' },
    { label: 'New This Month', value: 42, change: '+8%', color: 'blue' },
    { label: 'Active Today', value: 73, change: '+15%', color: 'cyan' },
    { label: 'Pending Review', value: 28, change: '-5%', color: 'amber' },
]);

const verificationMetrics = computed(() => [
    { label: 'Email Verified', value: 156, percent: 84 },
    { label: 'Phone Verified', value: 142, percent: 77 },
    { label: 'Document Verified', value: 118, percent: 64 },
    { label: 'Photo Verified', value: 165, percent: 89 },
]);

const countryStats = computed(() => [
    { country: 'Bangladesh', users: 45, percent: 24 },
    { country: 'Saudi Arabia', users: 38, percent: 21 },
    { country: 'UAE', users: 32, percent: 17 },
    { country: 'Egypt', users: 28, percent: 15 },
    { country: 'Turkey', users: 25, percent: 14 },
    { country: 'Others', users: 17, percent: 9 },
]);
</script>

<template>
    <AdminLayout>
        <Head title="Admin Reports & Analytics - Rencontre Éthique" />
        
        <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
            <Navigation />
            <Header :translations :locale :locales :canLogin :canRegister />
            
            <!-- Main Content -->
            <div class="lg:ml-64 container max-w-7xl mx-auto px-6 py-12">
                
                <!-- Page Header -->
                <div class="mb-12">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-[#0f3a7d] via-[#17a2b8] to-[#0f3a7d] bg-clip-text text-transparent mb-2">
                        Reports & Analytics
                    </h1>
                    <p class="text-slate-400 text-lg">Platform statistics and insights</p>
                </div>

                <!-- Period Selector -->
                <div class="mb-8 flex gap-3">
                    <button 
                        v-for="period in ['week', 'month', 'quarter', 'year']"
                        :key="period"
                        @click="selectedPeriod = period"
                        :class="[
                            'px-6 py-2 rounded-lg font-medium transition-all duration-300',
                            selectedPeriod === period 
                                ? 'bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] text-white shadow-lg shadow-[#0f3a7d]/50'
                                : 'bg-white/5 border border-white/10 text-slate-400 hover:border-white/20'
                        ]"
                    >
                        {{ period.charAt(0).toUpperCase() + period.slice(1) }}
                    </button>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <div v-for="metric in keyMetrics" :key="metric.label" class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-white/20 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">{{ metric.label }}</p>
                            <div class="flex items-end gap-3">
                                <p class="text-4xl font-bold text-white">{{ metric.value }}</p>
                                <p :class="`text-sm font-medium text-${metric.color}-400`">{{ metric.change }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                    
                    <!-- Registration Trends -->
                    <div class="lg:col-span-2 group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-[#17a2b8]/50 transition-all duration-300 shadow-2xl">
                            <h3 class="text-xl font-bold text-white mb-8">Registration Trends (7-day)</h3>
                            
                            <div class="h-48 flex items-end justify-between gap-3 pb-4 border-b border-white/10">
                                <div v-for="(day, idx) in registrationData.days" :key="day" class="flex-1 flex flex-col items-center">
                                    <div 
                                        class="w-full bg-gradient-to-t from-[#17a2b8] to-[#0f3a7d] rounded-lg transition-all duration-300 hover:opacity-80 cursor-pointer group/bar"
                                        :style="{ height: (registrationData.thisWeek[idx] / maxValue) * 100 + '%', minHeight: '2px' }"
                                    >
                                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/bar:opacity-100 transition-opacity whitespace-nowrap">
                                            {{ registrationData.thisWeek[idx] }}
                                        </div>
                                    </div>
                                    <p class="text-slate-500 text-xs mt-4">{{ day }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-between text-sm">
                                <div>
                                    <p class="text-slate-400">Avg. Daily</p>
                                    <p class="text-xl font-bold text-[#17a2b8]">{{ (registrationData.thisWeek.reduce((a, b) => a + b, 0) / registrationData.thisWeek.length).toFixed(0) }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-400">Peak Day</p>
                                    <p class="text-xl font-bold text-emerald-400">{{ Math.max(...registrationData.thisWeek) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gender Distribution Pie Chart -->
                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-[#0f3a7d]/50 transition-all duration-300 shadow-2xl h-full flex flex-col">
                            <h3 class="text-xl font-bold text-white mb-8">Gender Distribution</h3>
                            
                            <div class="flex-1 flex items-center justify-center">
                                <div class="relative w-32 h-32">
                                    <svg viewBox="0 0 100 100" class="w-full h-full transform -rotate-90">
                                        <!-- Male segment -->
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="url(#gradient1)" stroke-width="8"
                                            stroke-dasharray="88 160" stroke-dashoffset="0" />
                                        <!-- Female segment -->
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="url(#gradient2)" stroke-width="8"
                                            stroke-dasharray="72 160" stroke-dashoffset="-88" />
                                        <defs>
                                            <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" style="stop-color:#0f3a7d;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#17a2b8;stop-opacity:1" />
                                            </linearGradient>
                                            <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#ff8787;stop-opacity:1" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center text-center">
                                        <div>
                                            <p class="text-3xl font-bold text-white">{{ genderDistribution.male + genderDistribution.female }}</p>
                                            <p class="text-xs text-slate-400">members</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Male: {{ genderDistribution.male }}</span>
                                    <span class="text-[#0f3a7d] font-semibold">{{ ((genderDistribution.male / (genderDistribution.male + genderDistribution.female)) * 100).toFixed(0) }}%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Female: {{ genderDistribution.female }}</span>
                                    <span class="text-[#ff6b6b] font-semibold">{{ ((genderDistribution.female / (genderDistribution.male + genderDistribution.female)) * 100).toFixed(0) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Verification Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
                    
                    <!-- Status Distribution -->
                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-[#17a2b8]/50 transition-all duration-300 shadow-2xl">
                            <h3 class="text-xl font-bold text-white mb-6">Profile Status Distribution</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400">Approved</span>
                                        <span class="text-emerald-400 font-semibold">{{ statusDistribution.approved }}</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-[85%] bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400">Pending</span>
                                        <span class="text-amber-400 font-semibold">{{ statusDistribution.pending }}</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-[28%] bg-gradient-to-r from-amber-500 to-orange-500"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400">Flagged</span>
                                        <span class="text-rose-400 font-semibold">{{ statusDistribution.flagged }}</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-[7%] bg-gradient-to-r from-rose-500 to-red-500"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400">Rejected</span>
                                        <span class="text-slate-400 font-semibold">{{ statusDistribution.rejected }}</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full w-[5%] bg-gradient-to-r from-slate-500 to-slate-600"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Metrics -->
                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-[#0f3a7d]/50 transition-all duration-300 shadow-2xl">
                            <h3 class="text-xl font-bold text-white mb-6">Verification Status</h3>
                            
                            <div class="space-y-6">
                                <div v-for="metric in verificationMetrics" :key="metric.label">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-slate-400">{{ metric.label }}</span>
                                        <span class="text-cyan-400 font-semibold">{{ metric.value }}</span>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full bg-gradient-to-r from-cyan-500 to-blue-500"
                                            :style="{ width: metric.percent + '%' }"
                                        ></div>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">{{ metric.percent }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Country Distribution -->
                <div class="group relative rounded-2xl overflow-hidden">
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 hover:border-white/20 transition-all duration-300 shadow-2xl">
                        <h3 class="text-xl font-bold text-white mb-6">Members by Country</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="country in countryStats" :key="country.country" class="p-4 rounded-lg bg-white/5 border border-white/10">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-white font-medium">{{ country.country }}</span>
                                    <span class="text-[#17a2b8] font-bold">{{ country.users }}</span>
                                </div>
                                <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                    <div 
                                        class="h-full bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8]"
                                        :style="{ width: (country.users / 45) * 100 + '%' }"
                                    ></div>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">{{ country.percent }}% of total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/* Smooth transitions */
:deep(*) {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
