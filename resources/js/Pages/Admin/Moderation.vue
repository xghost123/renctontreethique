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
const selectedFilter = ref('all');
const hoveredProfileId = ref(null);
const expandedProfileId = ref(null);
const actionInProgress = ref(null);

// Mock pending profiles data
const pendingProfiles = computed(() => {
    const biodatas = page.props.all_biodatas || [];
    return Array.isArray(biodatas)
        ? biodatas.filter(b => b.status === 'pending').map((b, idx) => ({
            id: b.id || idx,
            name: b.name || 'Profile ' + (idx + 1),
            age: 28 + idx,
            photo: null,
            location: b.location || 'New York',
            submittedAt: new Date(Date.now() - idx * 86400000).toLocaleDateString(),
            status: 'pending',
            reviewScore: 85 + Math.random() * 10,
            flagReason: idx % 5 === 0 ? 'Incomplete Photos' : null,
            bio: 'Professional looking for meaningful connection.',
            religion: 'Christian',
            occupation: 'Software Engineer',
            education: 'Bachelor\'s Degree',
            height: '5\'10"',
            verified: idx % 3 === 0,
        }))
        : [];
});

// Filter options
const filterOptions = [
    { value: 'all', label: 'All Pending', icon: '📋' },
    { value: 'flagged', label: 'Flagged', icon: '⚠️' },
    { value: 'verified', label: 'Verified', icon: '✓' },
    { value: 'incomplete', label: 'Incomplete', icon: '❌' },
];

// Filtered profiles
const filteredProfiles = computed(() => {
    if (selectedFilter.value === 'all') return pendingProfiles.value;
    if (selectedFilter.value === 'flagged') return pendingProfiles.value.filter(p => p.flagReason);
    if (selectedFilter.value === 'verified') return pendingProfiles.value.filter(p => p.verified);
    if (selectedFilter.value === 'incomplete') return pendingProfiles.value.filter(p => !p.verified);
    return pendingProfiles.value;
});

// Actions
async function approveProfile(profileId) {
    actionInProgress.value = profileId;
    setTimeout(() => {
        actionInProgress.value = null;
        // Simulate API call
    }, 800);
}

async function rejectProfile(profileId) {
    actionInProgress.value = profileId;
    setTimeout(() => {
        actionInProgress.value = null;
    }, 800);
}

async function flagProfile(profileId) {
    actionInProgress.value = profileId;
    setTimeout(() => {
        actionInProgress.value = null;
    }, 800);
}

function toggleExpandProfile(profileId) {
    expandedProfileId.value = expandedProfileId.value === profileId ? null : profileId;
}
</script>

<template>
    <AdminLayout>
        <Head title="Moderation - Rencontre Éthique" />
        
        <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
            <Header :translations :locale :locales :canLogin :canRegister />
            
            <!-- Main Content -->
            <div class="container max-w-7xl mx-auto px-6 py-12">
                
                <!-- Page Header -->
                <div class="mb-12">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-[#0f3a7d] via-[#17a2b8] to-[#0f3a7d] bg-clip-text text-transparent mb-2">
                        Profile Moderation
                    </h1>
                    <p class="text-slate-400 text-lg">Review and manage pending profile submissions</p>
                </div>

                <!-- Filter Tabs -->
                <div class="mb-8 flex gap-3 overflow-x-auto pb-4">
                    <button 
                        v-for="filter in filterOptions"
                        :key="filter.value"
                        @click="selectedFilter = filter.value"
                        :class="{
                            'bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] border-[#0f3a7d]/50 text-white shadow-lg shadow-[#0f3a7d]/50': selectedFilter === filter.value,
                            'bg-white/5 border-white/10 text-slate-300 hover:bg-white/10 hover:border-white/20': selectedFilter !== filter.value
                        }"
                        class="px-6 py-3 rounded-xl border transition-all duration-300 font-semibold text-sm whitespace-nowrap backdrop-blur-xl"
                    >
                        <span class="mr-2">{{ filter.icon }}</span>{{ filter.label }}
                        <span class="ml-2 text-xs opacity-70">({{ filter.value === 'all' ? filteredProfiles.length : (filter.value === 'flagged' ? pendingProfiles.filter(p => p.flagReason).length : filter.value === 'verified' ? pendingProfiles.filter(p => p.verified).length : pendingProfiles.filter(p => !p.verified).length) }})</span>
                    </button>
                </div>

                <!-- Profiles Grid -->
                <div v-if="filteredProfiles.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div 
                        v-for="profile in filteredProfiles"
                        :key="profile.id"
                        class="group relative overflow-hidden rounded-2xl transition-all duration-500"
                        @mouseenter="hoveredProfileId = profile.id"
                        @mouseleave="hoveredProfileId = null"
                    >
                        <!-- Premium Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#0f3a7d]/10 via-slate-800/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Glassmorphism Card -->
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 overflow-hidden hover:border-[#17a2b8]/50 transition-all duration-300 shadow-2xl">
                            <!-- Glow Effect -->
                            <div class="absolute -inset-px bg-gradient-to-r from-[#0f3a7d]/20 to-[#17a2b8]/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl -z-10"></div>
                            
                            <!-- Profile Header with Photo Background -->
                            <div class="relative h-48 bg-gradient-to-br from-[#0f3a7d] to-[#17a2b8] overflow-hidden">
                                <!-- Photo Placeholder with Gradient -->
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#0f3a7d] via-slate-800 to-slate-900">
                                    <div class="text-center">
                                        <div class="text-6xl mb-4">👤</div>
                                        <p class="text-white/40 text-sm">Profile Photo</p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4">
                                    <div v-if="profile.verified" class="px-4 py-2 bg-emerald-500/90 backdrop-blur-sm rounded-lg flex items-center gap-2 text-white text-xs font-bold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Verified
                                    </div>
                                </div>

                                <!-- Flag Badge -->
                                <div v-if="profile.flagReason" class="absolute top-4 left-4">
                                    <div class="px-4 py-2 bg-rose-500/90 backdrop-blur-sm rounded-lg flex items-center gap-2 text-white text-xs font-bold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3.5 2.75a.75.75 0 00-1.5 0v14.5a.75.75 0 001.5 0V2.75zM8 5a.75.75 0 01.75.75v10.5a.75.75 0 01-1.5 0V5.75A.75.75 0 018 5zm4 0a.75.75 0 01.75.75v10.5a.75.75 0 01-1.5 0V5.75A.75.75 0 0112 5z" />
                                        </svg>
                                        {{ profile.flagReason }}
                                    </div>
                                </div>

                                <!-- Hover Overlay -->
                                <div v-if="hoveredProfileId === profile.id" class="absolute inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300">
                                    <p class="text-white text-center text-sm font-semibold">View Details</p>
                                </div>
                            </div>

                            <!-- Profile Content -->
                            <div class="p-6">
                                <!-- Name & Basic Info -->
                                <div class="mb-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-2xl font-bold text-white">{{ profile.name }}</h3>
                                            <p class="text-[#17a2b8] font-semibold text-sm">{{ profile.age }}, {{ profile.location }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-800/50 border border-white/10">
                                            <div class="w-2 h-2 rounded-full" :class="profile.reviewScore >= 90 ? 'bg-emerald-400' : 'bg-amber-400'"></div>
                                            <span class="text-white text-sm font-semibold">{{ profile.reviewScore.toFixed(0) }}%</span>
                                        </div>
                                    </div>
                                    <p class="text-slate-400 text-sm">Submitted {{ profile.submittedAt }}</p>
                                </div>

                                <!-- Bio Preview -->
                                <div class="mb-6 p-4 rounded-lg bg-white/5 border border-white/10">
                                    <p class="text-slate-300 text-sm leading-relaxed">{{ profile.bio }}</p>
                                </div>

                                <!-- Profile Details Grid -->
                                <div v-if="expandedProfileId === profile.id" class="mb-6 p-4 rounded-lg bg-gradient-to-br from-[#0f3a7d]/10 to-[#17a2b8]/10 border border-white/10 animate-in fade-in slide-in-from-top-2 duration-300">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold mb-1">Religion</p>
                                            <p class="text-white font-medium">{{ profile.religion }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold mb-1">Occupation</p>
                                            <p class="text-white font-medium">{{ profile.occupation }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold mb-1">Education</p>
                                            <p class="text-white font-medium">{{ profile.education }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold mb-1">Height</p>
                                            <p class="text-white font-medium">{{ profile.height }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expand Details Button -->
                                <button 
                                    @click="toggleExpandProfile(profile.id)"
                                    class="mb-6 w-full py-2 px-4 text-center text-[#17a2b8] hover:text-white text-xs font-semibold uppercase tracking-wider border border-[#17a2b8]/30 rounded-lg hover:bg-[#17a2b8]/10 transition-all duration-300"
                                >
                                    {{ expandedProfileId === profile.id ? '▲ Hide Details' : '▼ Show Details' }}
                                </button>

                                <!-- Action Buttons -->
                                <div class="grid grid-cols-3 gap-3">
                                    <!-- Approve Button -->
                                    <button 
                                        @click="approveProfile(profile.id)"
                                        :disabled="actionInProgress === profile.id"
                                        class="relative overflow-hidden px-4 py-3 rounded-xl font-bold text-sm uppercase tracking-wider transition-all duration-300 group/btn border-2 border-emerald-500/50 hover:border-emerald-500 text-emerald-400 hover:text-white bg-emerald-500/10 hover:bg-emerald-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <div v-if="actionInProgress === profile.id" class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
                                        <span class="relative flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            {{ actionInProgress === profile.id ? '...' : 'Approve' }}
                                        </span>
                                    </button>

                                    <!-- Flag Button -->
                                    <button 
                                        @click="flagProfile(profile.id)"
                                        :disabled="actionInProgress === profile.id"
                                        class="relative overflow-hidden px-4 py-3 rounded-xl font-bold text-sm uppercase tracking-wider transition-all duration-300 group/btn border-2 border-amber-500/50 hover:border-amber-500 text-amber-400 hover:text-white bg-amber-500/10 hover:bg-amber-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <div v-if="actionInProgress === profile.id" class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
                                        <span class="relative flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a1 1 0 011-1h14a1 1 0 011 1v8a1 1 0 01-1 1H3a1 1 0 01-1-1V6zM3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm7 6a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            {{ actionInProgress === profile.id ? '...' : 'Flag' }}
                                        </span>
                                    </button>

                                    <!-- Reject Button -->
                                    <button 
                                        @click="rejectProfile(profile.id)"
                                        :disabled="actionInProgress === profile.id"
                                        class="relative overflow-hidden px-4 py-3 rounded-xl font-bold text-sm uppercase tracking-wider transition-all duration-300 group/btn border-2 border-rose-500/50 hover:border-rose-500 text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <div v-if="actionInProgress === profile.id" class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
                                        <span class="relative flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            {{ actionInProgress === profile.id ? '...' : 'Reject' }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="flex items-center justify-center py-24">
                    <div class="text-center">
                        <div class="text-6xl mb-4">🎉</div>
                        <h3 class="text-2xl font-bold text-white mb-2">All Caught Up!</h3>
                        <p class="text-slate-400 mb-6">There are no pending profiles in this category</p>
                        <button 
                            @click="selectedFilter = 'all'"
                            class="px-8 py-3 bg-gradient-to-r from-[#0f3a7d] to-[#17a2b8] text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-[#0f3a7d]/50 transition-all duration-300"
                        >
                            View All Profiles
                        </button>
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

/* Animate in effect */
@keyframes slide-in-from-top {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation: slide-in-from-top 0.3s ease-out;
}

.fade-in {
    animation: fade-in 0.3s ease-out;
}

@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Pulse animation for action buttons */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
