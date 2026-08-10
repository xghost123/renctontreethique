<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Header from '../../Components/Admin/Header.vue';

defineProps({
    translations: Object,
    front_end_translations: Object,
    locale: String,
    locales: Array,
    canLogin: Boolean,
    canRegister: Boolean,
});

const page = usePage();
const searchQuery = ref('');
const selectedFilter = ref('all');
const hoveredRow = ref(null);

// Sample biodata
const biodatas = ref([
    { id: 1, user: 'Ahmed Hassan', gender: 'male', status: 'pending', submitted: '2024-08-05', completeness: 85 },
    { id: 2, user: 'Fatima Khan', gender: 'female', status: 'approved', submitted: '2024-07-20', completeness: 100 },
    { id: 3, user: 'Mohammed Ali', gender: 'male', status: 'flagged', submitted: '2024-08-01', completeness: 70 },
    { id: 4, user: 'Aisha Ahmed', gender: 'female', status: 'pending', submitted: '2024-08-03', completeness: 90 },
    { id: 5, user: 'Omar Ibrahim', gender: 'male', status: 'approved', submitted: '2024-07-15', completeness: 100 },
]);

const filteredBiodatas = computed(() => {
    return biodatas.value.filter(biodata => {
        const matchesSearch = biodata.user.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesFilter = selectedFilter.value === 'all' || biodata.status === selectedFilter.value;
        return matchesSearch && matchesFilter;
    });
});

const stats = computed(() => ({
    total: biodatas.value.length,
    pending: biodatas.value.filter(b => b.status === 'pending').length,
    approved: biodatas.value.filter(b => b.status === 'approved').length,
    flagged: biodatas.value.filter(b => b.status === 'flagged').length,
}));

const getStatusColor = (status) => {
    switch(status) {
        case 'approved': return 'emerald';
        case 'pending': return 'amber';
        case 'flagged': return 'rose';
        default: return 'slate';
    }
};

const getCompletenessColor = (percent) => {
    if (percent >= 90) return 'emerald';
    if (percent >= 70) return 'amber';
    return 'rose';
};

const approveAction = (id) => {
    const biodata = biodatas.value.find(b => b.id === id);
    if (biodata) {
        biodata.status = 'approved';
    }
};

const rejectAction = (id) => {
    const biodata = biodatas.value.find(b => b.id === id);
    if (biodata) {
        biodata.status = 'rejected';
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Admin Biodata Management - Rencontre Éthique" />
        
        <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
            <Navigation />
            <Header :translations :locale :locales :canLogin :canRegister />
            
            <!-- Main Content -->
            <div class="lg:ml-64 container max-w-7xl mx-auto px-6 py-12">
                
                <!-- Page Header -->
                <div class="mb-12">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-[#0f3a7d] via-[#17a2b8] to-[#0f3a7d] bg-clip-text text-transparent mb-2">
                        Biodata Management
                    </h1>
                    <p class="text-slate-400 text-lg">Review and approve member profiles</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-[#0f3a7d]/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Total Biodatas</p>
                            <p class="text-4xl font-bold text-white">{{ stats.total }}</p>
                        </div>
                    </div>

                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-amber-500/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Pending Review</p>
                            <p class="text-4xl font-bold text-amber-400">{{ stats.pending }}</p>
                        </div>
                    </div>

                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-emerald-500/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Approved</p>
                            <p class="text-4xl font-bold text-emerald-400">{{ stats.approved }}</p>
                        </div>
                    </div>

                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-rose-500/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Flagged</p>
                            <p class="text-4xl font-bold text-rose-400">{{ stats.flagged }}</p>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter -->
                <div class="mb-8">
                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-[#17a2b8]/50 transition-all duration-300 shadow-2xl">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Search -->
                                <div class="md:col-span-2">
                                    <label class="block text-slate-400 text-sm font-semibold mb-3">Search Biodata</label>
                                    <input 
                                        v-model="searchQuery"
                                        type="text" 
                                        placeholder="Search by user name..."
                                        class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-[#17a2b8]/50 transition-all"
                                    />
                                </div>

                                <!-- Filter -->
                                <div>
                                    <label class="block text-slate-400 text-sm font-semibold mb-3">Filter by Status</label>
                                    <select 
                                        v-model="selectedFilter"
                                        class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-white focus:outline-none focus:border-[#17a2b8]/50 transition-all"
                                    >
                                        <option value="all" class="bg-slate-900">All Biodatas</option>
                                        <option value="pending" class="bg-slate-900">Pending</option>
                                        <option value="approved" class="bg-slate-900">Approved</option>
                                        <option value="flagged" class="bg-slate-900">Flagged</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biodatas Table -->
                <div class="group relative rounded-2xl overflow-hidden">
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 shadow-2xl overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">User</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Gender</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Completeness</th>
                                    <th class="px-6 py-4 text-center text-slate-400 font-semibold text-sm uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr 
                                    v-for="biodata in filteredBiodatas"
                                    :key="biodata.id"
                                    class="border-b border-white/5 hover:bg-white/5 transition-colors"
                                    @mouseenter="hoveredRow = biodata.id"
                                    @mouseleave="hoveredRow = null"
                                >
                                    <td class="px-6 py-4 text-white font-medium">{{ biodata.user }}</td>
                                    <td class="px-6 py-4 text-slate-400">{{ biodata.gender }}</td>
                                    <td class="px-6 py-4">
                                        <span v-if="biodata.status === 'approved'" class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-emerald-500/20 text-emerald-400">
                                            {{ biodata.status }}
                                        </span>
                                        <span v-else-if="biodata.status === 'pending'" class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-amber-500/20 text-amber-400">
                                            {{ biodata.status }}
                                        </span>
                                        <span v-else class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-rose-500/20 text-rose-400">
                                            {{ biodata.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ biodata.submitted }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-12 bg-slate-700 rounded-full overflow-hidden">
                                                <div 
                                                    :style="{ width: biodata.completeness + '%' }"
                                                    :class="biodata.completeness >= 90 
                                                        ? 'h-full bg-gradient-to-r from-emerald-500 to-emerald-600'
                                                        : biodata.completeness >= 70
                                                        ? 'h-full bg-gradient-to-r from-amber-500 to-amber-600'
                                                        : 'h-full bg-gradient-to-r from-rose-500 to-rose-600'"
                                                ></div>
                                            </div>
                                            <span :class="biodata.completeness >= 90 
                                                ? 'text-xs font-medium text-emerald-400'
                                                : biodata.completeness >= 70
                                                ? 'text-xs font-medium text-amber-400'
                                                : 'text-xs font-medium text-rose-400'">{{ biodata.completeness }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div v-if="hoveredRow === biodata.id" class="flex gap-2 justify-center">
                                            <button class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-medium hover:bg-blue-500/30 transition-colors">View</button>
                                            <button v-if="biodata.status === 'pending'" @click="approveAction(biodata.id)" class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded text-xs font-medium hover:bg-emerald-500/30 transition-colors">Approve</button>
                                            <button v-if="biodata.status !== 'approved'" @click="rejectAction(biodata.id)" class="px-3 py-1 bg-rose-500/20 text-rose-400 rounded text-xs font-medium hover:bg-rose-500/30 transition-colors">Reject</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="filteredBiodatas.length === 0" class="px-6 py-12 text-center text-slate-400">
                            No biodatas found matching your criteria
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
