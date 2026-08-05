<template>
  <div v-if="searches.length === 0" class="text-center py-8 text-gray-400">
    <p>No saved searches yet. Create one to quickly access your favorite filter combinations!</p>
  </div>

  <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
    <div
      v-for="search in searches"
      :key="search.id"
      class="bg-gray-700 rounded-lg p-4 border border-gray-600 hover:border-amber-500 transition"
    >
      <div class="flex justify-between items-start mb-2">
        <div>
          <h3 class="text-white font-semibold">{{ search.name }}</h3>
          <p v-if="search.description" class="text-gray-400 text-sm mt-1">{{ search.description }}</p>
        </div>
        <button
          @click="$emit('delete', search.id)"
          class="text-red-400 hover:text-red-300 transition"
        >
          ✕
        </button>
      </div>

      <div class="text-gray-400 text-xs mb-3">
        <p>{{ formatDate(search.updated_at) }}</p>
      </div>

      <div class="flex gap-2">
        <button
          @click="$emit('load', search)"
          class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-2 px-3 rounded text-sm font-medium transition"
        >
          Load
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  searches: Array,
})

defineEmits(['close', 'load', 'delete'])

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}
</script>
