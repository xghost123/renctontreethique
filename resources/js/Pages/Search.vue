<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 border-b border-slate-700 py-8">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Find Your Perfect Match</h1>
        <p class="text-gray-400">Advanced search and filtering to discover compatible profiles</p>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
      <!-- Search Filters Component -->
      <SearchFilters
        @search="handleSearch"
        @update:filters="updateFilters"
      />

      <!-- Results Section -->
      <div v-if="hasSearched" class="mt-8">
        <SearchResults
          :results="searchResults"
          :pagination="pagination"
          :loading="isLoading"
          @changeSorting="handleSortChange"
          @changePage="handlePageChange"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="mt-12 text-center">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-12 border border-gray-700">
          <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <h2 class="text-2xl font-bold text-white mb-2">Ready to Search?</h2>
          <p class="text-gray-400 mb-6">Use the filters above to find profiles that match your preferences</p>
          <div class="space-y-2 text-sm text-gray-500">
            <p>✓ Filter by age, location, education level, and more</p>
            <p>✓ Save your favorite search combinations for quick access</p>
            <p>✓ Get smart recommendations based on your profile</p>
            <p>✓ Unlimited searches with no restrictions</p>
          </div>
        </div>
      </div>

      <!-- Tips Section -->
      <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700">
          <div class="text-2xl mb-3">🎯</div>
          <h3 class="text-white font-semibold mb-2">Use Specific Filters</h3>
          <p class="text-gray-400 text-sm">
            Narrow down your search with specific criteria like age, location, and religious practice level for better matches.
          </p>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700">
          <div class="text-2xl mb-3">💾</div>
          <h3 class="text-white font-semibold mb-2">Save Your Searches</h3>
          <p class="text-gray-400 text-sm">
            Create saved searches to quickly return to your favorite filter combinations without having to set them up again.
          </p>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700">
          <div class="text-2xl mb-3">✨</div>
          <h3 class="text-white font-semibold mb-2">Get Recommendations</h3>
          <p class="text-gray-400 text-sm">
            Let our smart algorithm suggest compatible profiles based on your profile information and preferences.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import SearchFilters from '@/Components/SearchFilters.vue'
import SearchResults from '@/Components/SearchResults.vue'

const hasSearched = ref(false)
const isLoading = ref(false)
const searchResults = ref([])
const currentFilters = ref({})
const pagination = ref({
  current_page: 1,
  total: 0,
  per_page: 20,
  last_page: 1,
  from: 0,
  to: 0,
  total_count: 0,
})

const handleSearch = (results) => {
  hasSearched.value = true
  isLoading.value = true

  // Simulate a small delay for better UX
  setTimeout(() => {
    searchResults.value = results.data || []
    pagination.value = results.pagination || {}
    isLoading.value = false
  }, 300)
}

const updateFilters = (newFilters) => {
  currentFilters.value = newFilters
}

const handleSortChange = (sortBy) => {
  // Re-run search with new sorting
  isLoading.value = true
  currentFilters.value.sort_by = sortBy

  // API call would go here
  setTimeout(() => {
    isLoading.value = false
  }, 500)
}

const handlePageChange = (page) => {
  isLoading.value = true
  currentFilters.value.page = page

  // API call would go here
  setTimeout(() => {
    isLoading.value = false
  }, 500)
}
</script>

<style scoped>
/* Animations */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-in {
  animation: slideInUp 0.5s ease-out;
}
</style>
