<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-900 rounded-lg shadow-xl p-6 w-96 border border-gray-700">
      <h2 class="text-2xl font-bold text-white mb-4">Save Search</h2>

      <div class="mb-4">
        <label class="block text-gray-300 text-sm font-medium mb-2">Search Name *</label>
        <input
          v-model="name"
          type="text"
          placeholder="e.g., Religious Women in Dubai"
          class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-amber-500"
        />
      </div>

      <div class="mb-6">
        <label class="block text-gray-300 text-sm font-medium mb-2">Description (Optional)</label>
        <textarea
          v-model="description"
          placeholder="What makes this search special?"
          class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-amber-500 h-24 resize-none"
        />
      </div>

      <div class="flex gap-3">
        <button
          @click="$emit('close')"
          class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded transition"
        >
          Cancel
        </button>
        <button
          @click="saveSearch"
          :disabled="!name.trim()"
          class="flex-1 bg-amber-600 hover:bg-amber-700 disabled:bg-gray-600 text-white font-semibold py-2 px-4 rounded transition"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const emit = defineEmits(['close', 'save'])

const name = ref('')
const description = ref('')

const saveSearch = () => {
  if (name.value.trim()) {
    emit('save', name.value, description.value)
    name.value = ''
    description.value = ''
  }
}
</script>
