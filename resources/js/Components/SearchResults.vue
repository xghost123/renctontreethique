<template>
  <div class="search-results-container">
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-500 mx-auto"></div>
      <p class="text-gray-400 mt-4">Searching for matches...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="!results || results.length === 0" class="text-center py-12 bg-gray-900 rounded-lg">
      <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <p class="text-gray-400">No profiles found matching your criteria. Try adjusting your filters.</p>
    </div>

    <!-- Results Grid -->
    <div v-else>
      <!-- Results Header -->
      <div class="mb-6 flex items-center justify-between">
        <h3 class="text-white text-lg font-semibold">
          Found <span class="text-amber-500">{{ pagination.total }}</span> Profiles
        </h3>
        <div class="flex items-center gap-2">
          <select
            v-model="sortBy"
            @change="changeSorting"
            class="bg-gray-800 border border-gray-600 text-white px-3 py-1 rounded text-sm focus:outline-none focus:border-amber-500"
          >
            <option value="newest_profiles">Newest First</option>
            <option value="recently_active">Recently Active</option>
            <option value="age_asc">Age: Low to High</option>
            <option value="age_desc">Age: High to Low</option>
            <option value="compatibility">Most Compatible</option>
          </select>
        </div>
      </div>

      <!-- Results Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div
          v-for="biodata in results"
          :key="biodata.id"
          class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition border border-gray-700 hover:border-amber-500"
        >
          <!-- Photo Section -->
          <div class="relative h-64 bg-gray-700 overflow-hidden group">
            <img
              v-if="biodata.primary_photo"
              :src="biodata.primary_photo"
              :alt="biodata.job_title"
              class="w-full h-full object-cover group-hover:scale-105 transition"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>

            <!-- Photo Badge -->
            <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
              📸 {{ biodata.photo_count }}
            </div>

            <!-- Action Buttons -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-transparent opacity-0 group-hover:opacity-100 transition p-4 flex gap-2">
              <button class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded transition">
                View Profile
              </button>
              <button class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded transition">
                ❤️
              </button>
            </div>
          </div>

          <!-- Profile Info -->
          <div class="p-4">
            <!-- Name & Age -->
            <h3 class="text-white font-bold text-lg">
              {{ biodata.age }} • {{ capitalizeFirst(biodata.gender) }}
            </h3>

            <!-- Location -->
            <p class="text-gray-400 text-sm mb-3">
              📍 {{ biodata.district }}, {{ biodata.division }}
            </p>

            <!-- Job -->
            <p v-if="biodata.job_title" class="text-amber-300 text-sm mb-2">
              💼 {{ biodata.job_title }}
            </p>

            <!-- Bio Preview -->
            <p v-if="biodata.bio" class="text-gray-300 text-sm mb-3 line-clamp-2">
              {{ biodata.bio }}
            </p>

            <!-- Key Attributes -->
            <div class="space-y-2 text-xs text-gray-400">
              <div v-if="biodata.prayer_level" class="flex items-center gap-2">
                <span>🕌</span>
                <span>{{ formatLabel(biodata.prayer_level) }}</span>
              </div>
              <div v-if="biodata.maritial_status" class="flex items-center gap-2">
                <span>💍</span>
                <span>{{ capitalizeFirst(biodata.maritial_status) }}</span>
              </div>
              <div v-if="biodata.have_children" class="flex items-center gap-2">
                <span>👨‍👩‍👧</span>
                <span>{{ formatLabel(biodata.have_children) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 mt-8">
        <button
          @click="previousPage"
          :disabled="pagination.current_page === 1"
          class="px-4 py-2 bg-gray-700 hover:bg-gray-600 disabled:bg-gray-800 text-white rounded transition"
        >
          Previous
        </button>

        <div class="flex gap-1">
          <button
            v-for="page in visiblePages"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'px-3 py-2 rounded transition',
              page === pagination.current_page
                ? 'bg-amber-600 text-white'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600',
            ]"
          >
            {{ page }}
          </button>
        </div>

        <button
          @click="nextPage"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-4 py-2 bg-gray-700 hover:bg-gray-600 disabled:bg-gray-800 text-white rounded transition"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  results: Array,
  pagination: Object,
  loading: Boolean,
})

const emit = defineEmits(['changeSorting', 'changePage'])

const sortBy = ref('newest_profiles')

const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, props.pagination.current_page - 2)
  const end = Math.min(props.pagination.last_page, props.pagination.current_page + 2)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

const changeSorting = () => {
  emit('changeSorting', sortBy.value)
}

const previousPage = () => {
  if (props.pagination.current_page > 1) {
    emit('changePage', props.pagination.current_page - 1)
  }
}

const nextPage = () => {
  if (props.pagination.current_page < props.pagination.last_page) {
    emit('changePage', props.pagination.current_page + 1)
  }
}

const goToPage = (page) => {
  emit('changePage', page)
}

const formatLabel = (str) => {
  return str.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
}

const capitalizeFirst = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.search-results-container {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
