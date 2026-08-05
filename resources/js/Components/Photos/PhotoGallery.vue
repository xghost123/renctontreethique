<template>
  <div class="photo-gallery-component">
    <h2 class="text-2xl font-bold text-white mb-6">Profile Photos</h2>

    <div v-if="loading" class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div
        v-for="i in 4"
        :key="i"
        class="aspect-square bg-gray-700 rounded-xl animate-pulse"
      ></div>
    </div>

    <div v-else-if="photos.length === 0" class="text-center py-12">
      <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <p class="text-gray-400 text-lg">No photos yet</p>
      <p class="text-gray-500 text-sm mt-2">Upload photos to showcase your profile</p>
    </div>

    <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div
        v-for="photo in sortedPhotos"
        :key="photo.id"
        class="group relative rounded-xl overflow-hidden bg-black/20 aspect-square cursor-pointer hover:shadow-lg transition-all"
        @click="selectedPhoto = photo"
      >
        <!-- Image -->
        <img
          :src="photo.path"
          :alt="photo.created_at"
          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
        />

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors"></div>

        <!-- Status Badge -->
        <div
          :class="[
            'absolute top-2 left-2 px-2 py-1 rounded text-xs font-semibold',
            photo.approved
              ? 'bg-green-500/90 text-white'
              : 'bg-yellow-500/90 text-white'
          ]"
        >
          {{ photo.approved ? '✓ Approved' : '⏳ Pending' }}
        </div>

        <!-- Primary Badge -->
        <div
          v-if="isFirst(photo)"
          class="absolute top-2 right-2 px-2 py-1 bg-blue-500/90 text-white rounded text-xs font-semibold"
        >
          Primary
        </div>

        <!-- Actions (on hover) -->
        <div
          v-if="isOwner"
          class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2"
        >
          <button
            @click.stop="setAsPrimary(photo)"
            v-if="!isFirst(photo)"
            title="Set as primary photo"
            class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition-colors"
          >
            Set as Primary
          </button>
          <button
            @click.stop="deletePhoto(photo)"
            title="Delete this photo"
            class="flex-1 py-2 px-3 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded transition-colors"
          >
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Lightbox Modal -->
    <div
      v-if="selectedPhoto"
      @click="selectedPhoto = null"
      class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4"
    >
      <div
        @click.stop
        class="max-w-2xl w-full bg-[#0D2218] rounded-xl overflow-hidden"
      >
        <div class="relative aspect-video bg-black flex items-center justify-center">
          <img
            :src="selectedPhoto.path"
            :alt="selectedPhoto.created_at"
            class="w-full h-full object-contain"
          />

          <!-- Close Button -->
          <button
            @click="selectedPhoto = null"
            class="absolute top-4 right-4 bg-black/50 hover:bg-black/80 text-white p-2 rounded-full transition-colors"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>

        <!-- Photo Info -->
        <div class="p-4 border-t border-white/10">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-400">Uploaded {{ formatDate(selectedPhoto.created_at) }}</p>
              <div class="flex items-center gap-2 mt-2">
                <span
                  :class="[
                    'px-3 py-1 rounded text-sm font-semibold',
                    selectedPhoto.approved
                      ? 'bg-green-500/20 text-green-400'
                      : 'bg-yellow-500/20 text-yellow-400'
                  ]"
                >
                  {{ selectedPhoto.approved ? '✓ Approved' : '⏳ Pending Approval' }}
                </span>
              </div>
            </div>
            <div v-if="isOwner" class="flex gap-2">
              <button
                @click="deletePhoto(selectedPhoto)"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition-colors"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  biodataId: {
    type: Number,
    required: true,
  },
  isOwner: {
    type: Boolean,
    default: false,
  },
})

const photos = ref([])
const loading = ref(true)
const selectedPhoto = ref(null)

const sortedPhotos = computed(() => {
  return [...photos.value].sort((a, b) => {
    // Approved first
    if (a.approved && !b.approved) return -1
    if (!a.approved && b.approved) return 1
    // Then by date (newest first)
    return new Date(b.created_at) - new Date(a.created_at)
  })
})

const isFirst = (photo) => {
  const approved = sortedPhotos.value.filter(p => p.approved)
  return approved.length > 0 && approved[0].id === photo.id
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const setAsPrimary = async (photo) => {
  // This would require an API endpoint to reorder photos
  // For now, we'll show a message
  alert('Set as primary photo - feature coming soon')
}

const deletePhoto = async (photo) => {
  if (!confirm('Delete this photo?')) return

  try {
    await axios.delete(`/api/photos/${photo.id}`)
    photos.value = photos.value.filter(p => p.id !== photo.id)
    selectedPhoto.value = null
  } catch (error) {
    alert('Error deleting photo')
    console.error(error)
  }
}

onMounted(async () => {
  try {
    const response = await axios.get(`/api/biodata/${props.biodataId}/photos`)
    photos.value = response.data.photos || []
  } catch (error) {
    console.error('Error loading photos:', error)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.photo-gallery-component {
  @apply w-full;
}
</style>
