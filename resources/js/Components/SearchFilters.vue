<template>
  <div class="search-filters-container bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-lg shadow-xl p-6 mb-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 border-b border-gray-700 pb-4">
      <div class="flex items-center gap-3">
        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        <h2 class="text-2xl font-bold text-white">Advanced Filters</h2>
      </div>
      <button
        @click="toggleFilters"
        class="text-gray-400 hover:text-amber-500 transition"
      >
        <svg class="w-6 h-6" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      </button>
    </div>

    <!-- Applied Filters Pills -->
    <div v-if="appliedFilters.length > 0" class="mb-6 flex flex-wrap gap-2">
      <div
        v-for="(filter, idx) in appliedFilters"
        :key="idx"
        class="inline-flex items-center gap-2 bg-amber-900 bg-opacity-50 text-amber-200 px-3 py-1 rounded-full text-sm border border-amber-600"
      >
        <span>{{ formatFilterLabel(filter) }}</span>
        <button
          @click="removeFilter(filter)"
          class="hover:text-amber-300 transition"
        >
          ✕
        </button>
      </div>
      <button
        v-if="appliedFilters.length > 0"
        @click="clearAllFilters"
        class="text-red-400 hover:text-red-300 text-sm underline"
      >
        Clear All
      </button>
    </div>

    <!-- Filters Grid -->
    <transition-expand v-if="showFilters">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Gender Filter -->
        <FilterGroup label="Gender" icon="👤">
          <div class="flex gap-2">
            <FilterButton
              v-for="gender in ['male', 'female']"
              :key="gender"
              :label="capitalizeFirst(gender)"
              :active="filters.gender === gender"
              @click="filters.gender = filters.gender === gender ? null : gender"
            />
          </div>
        </FilterGroup>

        <!-- Age Range Filter -->
        <FilterGroup label="Age Range" icon="📅">
          <div class="flex gap-3">
            <input
              v-model.number="filters.age_min"
              type="number"
              placeholder="Min"
              class="w-20 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-amber-500"
            />
            <span class="text-gray-400">-</span>
            <input
              v-model.number="filters.age_max"
              type="number"
              placeholder="Max"
              class="w-20 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-amber-500"
            />
          </div>
        </FilterGroup>

        <!-- Location: Country -->
        <FilterGroup label="Country" icon="🌍">
          <select
            v-model="filters.country"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Country</option>
            <option v-for="country in filterOptions.countries" :key="country" :value="country">
              {{ country }}
            </option>
          </select>
        </FilterGroup>

        <!-- Location: Division -->
        <FilterGroup label="Division" icon="🗺️">
          <select
            v-model="filters.division"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Division</option>
            <option v-for="division in filterOptions.divisions" :key="division" :value="division">
              {{ division }}
            </option>
          </select>
        </FilterGroup>

        <!-- Education Level -->
        <FilterGroup label="Education" icon="🎓">
          <select
            v-model="filters.education_level"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Education</option>
            <option v-for="level in ['general', 'aliya', 'kowmi', 'other']" :key="level" :value="level">
              {{ capitalizeFirst(level) }}
            </option>
          </select>
        </FilterGroup>

        <!-- Prayer Level -->
        <FilterGroup label="Religious Practice" icon="🕌">
          <select
            v-model="filters.prayer_level"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Practice Level</option>
            <option v-for="level in filterOptions.prayer_levels" :key="level" :value="level">
              {{ formatLabel(level) }}
            </option>
          </select>
        </FilterGroup>

        <!-- Practice Religion Years -->
        <FilterGroup label="Religious Journey" icon="📜">
          <select
            v-model="filters.practice_religion_years"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Duration</option>
            <option v-for="duration in filterOptions.practice_religion_years" :key="duration" :value="duration">
              {{ formatLabel(duration) }}
            </option>
          </select>
        </FilterGroup>

        <!-- Family Goals -->
        <FilterGroup label="Family Goals" icon="👨‍👩‍👧‍👦">
          <select
            v-model="filters.family_goals"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Preference</option>
            <option v-for="goal in filterOptions.family_goals" :key="goal" :value="goal">
              {{ formatLabel(goal) }}
            </option>
          </select>
        </FilterGroup>

        <!-- Have Children -->
        <FilterGroup label="Children" icon="👶">
          <select
            v-model="filters.have_children"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Status</option>
            <option v-for="status in filterOptions.have_children" :key="status" :value="status">
              {{ formatLabel(status) }}
            </option>
          </select>
        </FilterGroup>

        <!-- Skin Color (Multi-select) -->
        <FilterGroup label="Skin Tone" icon="🎨">
          <div class="flex flex-wrap gap-2">
            <FilterButton
              v-for="color in filterOptions.skin_colors"
              :key="color"
              :label="capitalizeFirst(color)"
              :active="filters.skin_color?.includes(color)"
              @click="toggleSkinColor(color)"
            />
          </div>
        </FilterGroup>

        <!-- Height Range -->
        <FilterGroup label="Height Range (cm)" icon="📏">
          <div class="flex gap-3">
            <input
              v-model.number="filters.height_min"
              type="number"
              placeholder="Min"
              class="w-20 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-amber-500"
            />
            <span class="text-gray-400">-</span>
            <input
              v-model.number="filters.height_max"
              type="number"
              placeholder="Max"
              class="w-20 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-amber-500"
            />
          </div>
        </FilterGroup>

        <!-- Marital Status -->
        <FilterGroup label="Marital Status" icon="💍">
          <select
            v-model="filters.maritial_status"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Status</option>
            <option v-for="status in filterOptions.maritial_statuses" :key="status" :value="status">
              {{ capitalizeFirst(status) }}
            </option>
          </select>
        </FilterGroup>

        <!-- Madhab -->
        <FilterGroup label="Madhab" icon="📖">
          <select
            v-model="filters.madhab"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-amber-500"
          >
            <option value="">Select Madhab</option>
            <option v-for="madhab in filterOptions.madhabs" :key="madhab" :value="madhab">
              {{ capitalizeFirst(madhab) }}
            </option>
          </select>
        </FilterGroup>

        <!-- Has Photos -->
        <FilterGroup label="Photos" icon="📸">
          <label class="flex items-center gap-2 cursor-pointer">
            <input
              v-model="filters.has_photos"
              type="checkbox"
              class="w-4 h-4 rounded bg-gray-700 border-gray-600 text-amber-500 focus:ring-amber-500"
            />
            <span class="text-gray-300">Profiles with photos only</span>
          </label>
        </FilterGroup>

        <!-- Text Search -->
        <FilterGroup label="Search Keywords" icon="🔍" class="lg:col-span-3">
          <input
            v-model="filters.q"
            type="text"
            placeholder="Search by job, interests, or bio..."
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-amber-500"
          />
        </FilterGroup>
      </div>
    </transition-expand>

    <!-- Action Buttons -->
    <div class="flex gap-3 mt-6 pt-6 border-t border-gray-700">
      <button
        @click="searchWithFilters"
        :disabled="loading"
        class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-700 hover:to-amber-600 disabled:from-gray-600 disabled:to-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition"
      >
        <svg v-if="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <span v-if="loading">Searching...</span>
        <span v-else>Search</span>
      </button>

      <button
        @click="saveSearch"
        class="flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition border border-gray-600"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" />
        </svg>
        Save
      </button>

      <button
        @click="toggleSavedSearches"
        class="flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition border border-gray-600"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" />
        </svg>
        Saved ({{ savedSearches.length }})
      </button>
    </div>

    <!-- Saved Searches Modal -->
    <SavedSearchesModal
      v-if="showSavedSearchesModal"
      :searches="savedSearches"
      @close="showSavedSearchesModal = false"
      @load="loadSavedSearch"
      @delete="deleteSavedSearch"
    />

    <!-- Save Search Modal -->
    <SaveSearchModal
      v-if="showSaveModal"
      @close="showSaveModal = false"
      @save="confirmSaveSearch"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import FilterGroup from './FilterGroup.vue'
import FilterButton from './FilterButton.vue'
import SavedSearchesModal from './SavedSearchesModal.vue'
import SaveSearchModal from './SaveSearchModal.vue'
import { useLocalStorage } from '../composables/useLocalStorage'
import { useSearch } from '../composables/useSearch'

const props = defineProps({
  onSearchResults: Function,
})

const emit = defineEmits(['search', 'update:filters'])

const showFilters = ref(false)
const loading = ref(false)
const showSaveModal = ref(false)
const showSavedSearchesModal = ref(false)
const savedSearches = ref([])
const filterOptions = ref({
  genders: ['male', 'female'],
  education_levels: [],
  prayer_levels: [],
  practice_religion_years: [],
  family_goals: [],
  have_children: [],
  skin_colors: [],
  maritial_statuses: [],
  madhabs: [],
  countries: [],
  divisions: [],
})

const filters = ref({
  gender: null,
  age_min: null,
  age_max: null,
  country: null,
  division: null,
  education_level: null,
  prayer_level: null,
  practice_religion_years: null,
  family_goals: null,
  have_children: null,
  skin_color: [],
  height_min: null,
  height_max: null,
  maritial_status: null,
  madhab: null,
  has_photos: false,
  q: null,
})

const { getStoredFilters, storeFilters } = useLocalStorage()
const { fetchFilterOptions, performSearch, fetchSavedSearches } = useSearch()

const appliedFilters = computed(() => {
  const applied = []
  for (const [key, value] of Object.entries(filters.value)) {
    if (value && value !== '' && (!Array.isArray(value) || value.length > 0)) {
      applied.push({ key, value })
    }
  }
  return applied
})

const toggleFilters = () => {
  showFilters.value = !showFilters.value
}

const toggleSkinColor = (color) => {
  const idx = filters.value.skin_color.indexOf(color)
  if (idx > -1) {
    filters.value.skin_color.splice(idx, 1)
  } else {
    filters.value.skin_color.push(color)
  }
}

const removeFilter = (filter) => {
  if (Array.isArray(filters.value[filter.key])) {
    filters.value[filter.key] = []
  } else {
    filters.value[filter.key] = null
  }
}

const clearAllFilters = () => {
  filters.value = {
    gender: null,
    age_min: null,
    age_max: null,
    country: null,
    division: null,
    education_level: null,
    prayer_level: null,
    practice_religion_years: null,
    family_goals: null,
    have_children: null,
    skin_color: [],
    height_min: null,
    height_max: null,
    maritial_status: null,
    madhab: null,
    has_photos: false,
    q: null,
  }
  storeFilters(filters.value)
}

const searchWithFilters = async () => {
  loading.value = true
  try {
    storeFilters(filters.value)
    const results = await performSearch(filters.value)
    emit('search', results)
  } finally {
    loading.value = false
  }
}

const saveSearch = () => {
  showSaveModal.value = true
}

const confirmSaveSearch = async (name, description) => {
  try {
    // Save via API
    const response = await fetch('/api/saved-searches', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      body: JSON.stringify({
        name,
        description,
        filters: filters.value,
      }),
    })

    if (response.ok) {
      showSaveModal.value = false
      await loadSavedSearches()
    }
  } catch (error) {
    console.error('Error saving search:', error)
  }
}

const toggleSavedSearches = async () => {
  if (!showSavedSearchesModal.value) {
    await loadSavedSearches()
  }
  showSavedSearchesModal.value = !showSavedSearchesModal.value
}

const loadSavedSearches = async () => {
  try {
    const response = await fetch('/api/saved-searches')
    const data = await response.json()
    if (data.success) {
      savedSearches.value = data.data
    }
  } catch (error) {
    console.error('Error loading saved searches:', error)
  }
}

const loadSavedSearch = (search) => {
  filters.value = { ...filters.value, ...search.filters }
  showSavedSearchesModal.value = false
  searchWithFilters()
}

const deleteSavedSearch = async (id) => {
  try {
    const response = await fetch(`/api/saved-searches/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
    })

    if (response.ok) {
      await loadSavedSearches()
    }
  } catch (error) {
    console.error('Error deleting search:', error)
  }
}

const formatLabel = (str) => {
  return str.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
}

const capitalizeFirst = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1)
}

const formatFilterLabel = (filter) => {
  if (Array.isArray(filter.value)) {
    return `${formatLabel(filter.key)}: ${filter.value.join(', ')}`
  }
  return `${formatLabel(filter.key)}: ${filter.value}`
}

onMounted(async () => {
  // Load stored filters
  const stored = getStoredFilters()
  if (stored) {
    filters.value = { ...filters.value, ...stored }
  }

  // Load filter options
  try {
    const response = await fetch('/api/search/filters')
    const data = await response.json()
    if (data.success) {
      filterOptions.value = data.data
    }
  } catch (error) {
    console.error('Error loading filter options:', error)
  }

  // Load saved searches
  await loadSavedSearches()
})

// Watch filters for changes
watch(
  () => filters.value,
  (newFilters) => {
    emit('update:filters', newFilters)
  },
  { deep: true }
)
</script>

<style scoped>
.search-filters-container {
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
