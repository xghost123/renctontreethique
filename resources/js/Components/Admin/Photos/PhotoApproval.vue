<template>
  <div class="photo-approval-container space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-white">Photo Approvals</h2>
        <p class="text-gray-400 mt-1">{{ pendingCount }} photos awaiting approval</p>
      </div>
      <button
        @click="loadPhotos"
        class="px-4 py-2 bg-[#C8A028] hover:bg-[#D4B548] text-[#0D2218] rounded-lg font-semibold transition-colors"
      >
        Refresh
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="i in 6"
        :key="i"
        class="bg-gray-700/50 rounded-xl aspect-square animate-pulse"
      ></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="photos.length === 0" class="text-center py-16">
      <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <p class="text-gray-400 text-lg">All photos approved!</p>
      <p class="text-gray-500 text-sm mt-2">No pending photos to review</p>
    </div>

    <!-- Photos Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="photo in photos"
        :key="photo.id"
        class="bg-white/5 border border-white/10 rounded-xl overflow-hidden hover:border-white/20 transition-all"
      >
        <!-- Image -->
        <div
          class="relative aspect-square bg-black/20 cursor-pointer"
          @click="selectedPhoto = photo"
        >
          <img
            :src="photo.path"
            :alt="photo.user_name"
            class="w-full h-full object-cover hover:scale-105 transition-transform"
          />

          <!-- User Badge -->
          <div class="absolute top-3 left-3 px-3 py-1 bg-[#0D2218]/90 rounded-full text-xs text-white font-semibold">
            {{ photo.user_name }}
          </div>

          <!-- Upload Date -->
          <div class="absolute top-3 right-3 px-3 py-1 bg-[#C8A028]/80 rounded-full text-xs text-[#0D2218] font-semibold">
            {{ formatDate(photo.created_at) }}
          </div>
        </div>

        <!-- Actions -->
        <div class="p-4 space-y-3">
          <div class="text-sm text-gray-400">
            <p class="font-semibold text-white">{{ photo.user_name }}</p>
            <p class="text-xs mt-1">{{ photo.original_filename }}</p>
          </div>

          <div class="flex gap-2">
            <button
              @click="approvePhoto(photo)"
              :disabled="approvingId === photo.id || rejectingId === photo.id"
              class="flex-1 py-2 px-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-600 text-white text-sm font-semibold rounded transition-colors flex items-center justify-center gap-2"
            >
              <span v-if="approvingId !== photo.id">✓ Approve</span>
              <span v-else class="animate-spin">⏳</span>
            </button>

            <button
              @click="rejectPhoto(photo)"
              :disabled="approvingId === photo.id || rejectingId === photo.id"
              class="flex-1 py-2 px-3 bg-red-600 hover:bg-red-700 disabled:bg-gray-600 text-white text-sm font-semibold rounded transition-colors flex items-center justify-center gap-2"
            >
              <span v-if="rejectingId !== photo.id">✗ Reject</span>
              <span v-else class="animate-spin">⏳</span>
            </button>
          </div>

          <button
            @click="selectedPhoto = photo"
            class="w-full py-2 px-3 bg-white/5 hover:bg-white/10 text-gray-300 text-sm font-semibold rounded transition-colors"
          >
            Preview
          </button>
        </div>
      </div>
    </div>

    <!-- Lightbox Modal -->
    <div
      v-if="selectedPhoto"
      @click="selectedPhoto = null"
      class="fixed inset-0 bg-black/95 flex items-center justify-center z-50 p-4"
    >
      <div
        @click.stop
        class="max-w-4xl w-full bg-[#0D2218] rounded-xl overflow-hidden shadow-2xl"
      >
        <!-- Image Container -->
        <div class="relative bg-black aspect-video flex items-center justify-center">
          <img
            :src="selectedPhoto.path"
            :alt="selectedPhoto.user_name"
            class="w-full h-full object-contain"
          />

          <!-- Close Button -->
          <button
            @click="selectedPhoto = null"
            class="absolute top-4 right-4 bg-black/50 hover:bg-black/80 text-white p-2 rounded-full transition-colors z-10"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>

        <!-- Info & Actions -->
        <div class="p-6 border-t border-white/10 space-y-4">
          <div>
            <p class="text-white font-semibold text-lg">{{ selectedPhoto.user_name }}</p>
            <p class="text-gray-400 text-sm mt-1">{{ selectedPhoto.original_filename }}</p>
            <p class="text-gray-500 text-xs mt-2">Uploaded {{ formatDateTime(selectedPhoto.created_at) }}</p>
          </div>

          <!-- Decision Buttons -->
          <div class="flex gap-3">
            <button
              @click="approvePhotoAndClose(selectedPhoto)"
              :disabled="approvingId === selectedPhoto.id || rejectingId === selectedPhoto.id"
              class="flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 disabled:bg-gray-600 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
            >
              <svg v-if="approvingId !== selectedPhoto.id" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <span v-if="approvingId !== selectedPhoto.id">Approve Photo</span>
              <span v-else class="animate-spin">⏳ Approving...</span>
            </button>

            <button
              @click="rejectPhotoAndClose(selectedPhoto)"
              :disabled="approvingId === selectedPhoto.id || rejectingId === selectedPhoto.id"
              class="flex-1 py-3 px-4 bg-red-600 hover:bg-red-700 disabled:bg-gray-600 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
            >
              <svg v-if="rejectingId !== selectedPhoto.id" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              <span v-if="rejectingId !== selectedPhoto.id">Reject Photo</span>
              <span v-else class="animate-spin">⏳ Rejecting...</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notifications -->
    <div v-if="successMessage" class="fixed bottom-4 right-4 px-6 py-3 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 font-semibold">
      {{ successMessage }}
    </div>

    <div v-if="errorMessage" class="fixed bottom-4 right-4 px-6 py-3 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 font-semibold">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const photos = ref([])
const loading = ref(false)
const selectedPhoto = ref(null)
const approvingId = ref(null)
const rejectingId = ref(null)
const successMessage = ref('')
const errorMessage = ref('')

const pendingCount = computed(() => photos.value.length)

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
  })
}

const formatDateTime = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const loadPhotos = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/admin/photos/pending')
    photos.value = response.data.data || []
  } catch (error) {
    errorMessage.value = 'Error loading photos'
    console.error(error)
  } finally {
    loading.value = false
  }
}

const approvePhoto = async (photo) => {
  approvingId.value = photo.id
  try {
    await axios.post(`/api/admin/photos/${photo.id}/approve`)
    photos.value = photos.value.filter(p => p.id !== photo.id)
    successMessage.value = `Approved photo from ${photo.user_name}`
    setTimeout(() => (successMessage.value = ''), 3000)
  } catch (error) {
    errorMessage.value = 'Error approving photo'
    console.error(error)
  } finally {
    approvingId.value = null
  }
}

const approvePhotoAndClose = async (photo) => {
  await approvePhoto(photo)
  selectedPhoto.value = null
}

const rejectPhoto = async (photo) => {
  rejectingId.value = photo.id
  try {
    await axios.post(`/api/admin/photos/${photo.id}/reject`)
    photos.value = photos.value.filter(p => p.id !== photo.id)
    successMessage.value = `Rejected photo from ${photo.user_name}`
    setTimeout(() => (successMessage.value = ''), 3000)
  } catch (error) {
    errorMessage.value = 'Error rejecting photo'
    console.error(error)
  } finally {
    rejectingId.value = null
  }
}

const rejectPhotoAndClose = async (photo) => {
  await rejectPhoto(photo)
  selectedPhoto.value = null
}

onMounted(loadPhotos)
</script>

<style scoped>
.photo-approval-container {
  @apply w-full;
}
</style>
