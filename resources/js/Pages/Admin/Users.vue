<script setup>
import { ref, onMounted, computed } from 'vue';
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
const searchQuery = ref('');
const selectedFilter = ref('all');
const hoveredRow = ref(null);

// Sample user data - would normally come from Laravel backend
const users = ref([
    { id: 1, name: 'Ahmed Hassan', email: 'ahmed@example.com', status: 'approved', role: 'member', joined: '2024-07-15' },
    { id: 2, name: 'Fatima Khan', email: 'fatima@example.com', status: 'pending', role: 'member', joined: '2024-08-01' },
    { id: 3, name: 'Mohammed Ali', email: 'mohammed@example.com', status: 'approved', role: 'member', joined: '2024-07-20' },
    { id: 4, name: 'Aisha Ahmed', email: 'aisha@example.com', status: 'rejected', role: 'member', joined: '2024-07-10' },
    { id: 5, name: 'Omar Ibrahim', email: 'omar@example.com', status: 'pending', role: 'member', joined: '2024-08-05' },
]);

const filteredUsers = computed(() => {
    return users.value.filter(user => {
        const matchesSearch = 
            user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesFilter = selectedFilter.value === 'all' || user.status === selectedFilter.value;
        return matchesSearch && matchesFilter;
    });
});

const stats = computed(() => ({
    total: users.value.length,
    approved: users.value.filter(u => u.status === 'approved').length,
    pending: users.value.filter(u => u.status === 'pending').length,
    rejected: users.value.filter(u => u.status === 'rejected').length,
}));

const getStatusColor = (status) => {
    switch(status) {
        case 'approved': return 'emerald';
        case 'pending': return 'amber';
        case 'rejected': return 'rose';
        default: return 'slate';
    }
};

const getStatusIcon = (status) => {
    switch(status) {
        case 'approved': return '✓';
        case 'pending': return '⏳';
        case 'rejected': return '✕';
        default: return '•';
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Admin Users - Rencontre Éthique" />
        
        <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
            <Navigation />
            <Header :translations :locale :locales :canLogin :canRegister />
            
            <!-- Main Content -->
            <div class="lg:ml-64 container max-w-7xl mx-auto px-6 py-12">
                
                <!-- Page Header -->
                <div class="mb-12">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-[#0f3a7d] via-[#17a2b8] to-[#0f3a7d] bg-clip-text text-transparent mb-2">
                        User Management
                    </h1>
                    <p class="text-slate-400 text-lg">Manage and review user profiles and approvals</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-[#0f3a7d]/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Total Users</p>
                            <p class="text-4xl font-bold text-white">{{ stats.total }}</p>
                        </div>
                    </div>

                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-emerald-500/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Approved</p>
                            <p class="text-4xl font-bold text-emerald-400">{{ stats.approved }}</p>
                        </div>
                    </div>

                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-amber-500/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Pending</p>
                            <p class="text-4xl font-bold text-amber-400">{{ stats.pending }}</p>
                        </div>
                    </div>

                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-rose-500/50 transition-all duration-300 shadow-2xl">
                            <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Rejected</p>
                            <p class="text-4xl font-bold text-rose-400">{{ stats.rejected }}</p>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter -->
                <div class="mb-8 space-y-4">
                    <div class="group relative rounded-2xl overflow-hidden">
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 hover:border-[#17a2b8]/50 transition-all duration-300 shadow-2xl">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Search -->
                                <div class="md:col-span-2">
                                    <label class="block text-slate-400 text-sm font-semibold mb-3">Search Users</label>
                                    <input 
                                        v-model="searchQuery"
                                        type="text" 
                                        placeholder="Search by name or email..."
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
                                        <option value="all" class="bg-slate-900">All Users</option>
                                        <option value="approved" class="bg-slate-900">Approved</option>
                                        <option value="pending" class="bg-slate-900">Pending</option>
                                        <option value="rejected" class="bg-slate-900">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="group relative rounded-2xl overflow-hidden">
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 shadow-2xl overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-4 text-left text-slate-400 font-semibold text-sm uppercase tracking-wider">Joined</th>
                                    <th class="px-6 py-4 text-center text-slate-400 font-semibold text-sm uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr 
                                    v-for="user in filteredUsers"
                                    :key="user.id"
                                    class="border-b border-white/5 hover:bg-white/5 transition-colors"
                                    @mouseenter="hoveredRow = user.id"
                                    @mouseleave="hoveredRow = null"
                                >
                                    <td class="px-6 py-4 text-white font-medium">{{ user.name }}</td>
                                    <td class="px-6 py-4 text-slate-400">{{ user.email }}</td>
                                    <td class="px-6 py-4">
                                        <span v-if="user.status === 'approved'" class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-sm font-medium bg-emerald-500/20 text-emerald-400">
                                            {{ getStatusIcon(user.status) }} {{ user.status }}
                                        </span>
                                        <span v-else-if="user.status === 'pending'" class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-sm font-medium bg-amber-500/20 text-amber-400">
                                            {{ getStatusIcon(user.status) }} {{ user.status }}
                                        </span>
                                        <span v-else class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-sm font-medium bg-rose-500/20 text-rose-400">
                                            {{ getStatusIcon(user.status) }} {{ user.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ user.role }}</td>
                                    <td class="px-6 py-4 text-slate-400">{{ user.joined }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div v-if="hoveredRow === user.id" class="flex gap-2 justify-center">
                                            <button class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded text-xs font-medium hover:bg-emerald-500/30 transition-colors">View</button>
                                            <button v-if="user.status === 'pending'" class="px-3 py-1 bg-amber-500/20 text-amber-400 rounded text-xs font-medium hover:bg-amber-500/30 transition-colors">Approve</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="filteredUsers.length === 0" class="px-6 py-12 text-center text-slate-400">
                            No users found matching your criteria
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
