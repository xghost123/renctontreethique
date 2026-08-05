<template>
  <div class="upload-photo-component">
    <!-- Upload Area -->
    <div
      @click="triggerFileInput"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="handleDrop"
      :class="[
        'relative border-2 border-dashed rounded-xl p-12 text-center cursor-pointer transition-all',
        isDragging
          ? 'border-[#C8A028] bg-[#C8A028]/10'
          : 'border-[#C8A028]/30 hover:border-[#C8A028]/50 bg-white/5 hover:bg-white/10'
      ]"
    >
      <input
        ref="fileInput"
        type="file"
        multiple
        accept="image/*"
        @change="handleFileSelect"
        class="hidden"
        :disabled="isUploading"
      />

      <div class="space-y-4">
        <div class="flex justify-center">
          <svg class="w-16 h-16 text-[#C8A028]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </div>

        <div>
          <p class="text-lg font-semibold text-white">Drop photos here or click to upload</p>
          <p class="text-sm text-gray-400 mt-2">Maximum 5 photos per profile</p>
          <p class="text-xs text-gray-500 mt-1">JPG, PNG, WebP • Maximum 5MB each</p>
        </div>
      </div>
    </div>

    <!-- File List -->
    <div v-if="filesToUpload.length > 0" class="mt-6 space-y-3">
      <h3 class="text-sm font-semibold text-white">Selected Files ({{ filesToUpload.length }})</h3>
      <div class="space-y-2">
        <div
          v-for="(file, idx) in filesToUpload"
          :key="idx"
          class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-lg"
        >
          <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-10 h-10 bg-[#C8A028]/20 rounded flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-[#C8A028]" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-white truncate">{{ file.name }}</p>
              <p class="text-xs text-gray-400">{{ formatSize(file.size) }}</p>
            </div>
          </div>
          <button
            type="button"
            @click="removeFile(idx)"
            :disabled="isUploading"
            class="ml-2 text-gray-400 hover:text-red-400 disabled:opacity-50"
          >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Upload Button -->
    <div v-if="filesToUpload.length > 0" class="mt-6">
      <button
        @click="uploadPhotos"
        :disabled="isUploading"
        :class="[
          'w-full py-3 px-4 rounded-lg font-semibold transition-all',
          isUploading
            ? 'bg-gray-600 text-gray-300 cursor-not-allowed'
            : 'bg-[#C8A028] text-[#0D2218] hover:bg-[#D4B548]'
        ]"
      >
        <span v-if="!isUploading">Upload {{ filesToUpload.length }} Photo(s)</span>
        <span v-else>Uploading... {{ uploadProgress }}%</span>
      </button>
    </div>

    <!-- Upload Progress -->
    <div v-if="isUploading" class="mt-6">
      <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
        <div
          class="h-full bg-[#C8A028] transition-all duration-300"
          :style="{ width: uploadProgress + '%' }"
        ></div>
      </div>
    </div>

    <!-- Uploaded Photos -->
    <div v-if="uploadedPhotos.length > 0" class="mt-8">
      <h3 class="text-lg font-semibold text-white mb-4">Uploaded Photos</h3>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div
          v-for="photo in uploadedPhotos"
          :key="photo.id"
          class="relative group rounded-xl overflow-hidden bg-black/20"
        >
          <img
            :src="photo.path"
            :alt="photo.original_filename"
            class="w-full h-40 object-cover"
          />

          <!-- Status Badge -->
          <div
            :class="[
              'absolute top-2 right-2 px-2 py-1 rounded text-xs font-semibold',
              photo.approved
                ? 'bg-green-500/80 text-white'
                : 'bg-yellow-500/80 text-white'
            ]"
          >
            {{ photo.approved ? 'Approved' : 'Pending' }}
          </div>

          <!-- Delete Button -->
          <button
            @click="deletePhoto(photo.id)"
            class="absolute bottom-0 left-0 right-0 bg-red-600/90 text-white py-2 text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity"
          >
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Messages -->
    <div v-if="successMessage" class="mt-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
      {{ successMessage }}
    </div>

    <div v-if="errorMessage" class="mt-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  biodataId: {
    type: Number,
    required: true,
  },
})

const fileInput = ref(null)
const filesToUpload = ref([])
const uploadedPhotos = ref([])
const isUploading = ref(false)
const uploadProgress = ref(0)
const isDragging = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const triggerFileInput = () => {
  fileInput.value.click()
}

const handleFileSelect = (e) => {
  const files = Array.from(e.target.files)
  addFilesToUpload(files)
}

const handleDrop = (e) => {
  isDragging.value = false
  const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'))
  addFilesToUpload(files)
}

const addFilesToUpload = (files) => {
  const totalWillBe = filesToUpload.value.length + files.length
  if (totalWillBe > 5) {
    errorMessage.value = `Maximum 5 photos allowed. You already have ${filesToUpload.value.length}.`
    return
  }

  filesToUpload.value = [...filesToUpload.value, ...files]
  errorMessage.value = ''
}

const removeFile = (index) => {
  filesToUpload.value.splice(index, 1)
}

const formatSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

const uploadPhotos = async () => {
  if (filesToUpload.value.length === 0) return

  isUploading.value = true
  uploadProgress.value = 0
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const totalFiles = filesToUpload.value.length
    let completed = 0

    for (const file of filesToUpload.value) {
      const formData = new FormData()
      formData.append('photo', file)
      formData.append('biodata_id', props.biodataId)

      try {
        const response = await axios.post('/api/photos/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })

        uploadedPhotos.value.push(response.data.photo)
        completed++
        uploadProgress.value = Math.round((completed / totalFiles) * 100)
      } catch (error) {
        console.error('Error uploading file:', error)
        errorMessage.value = error.response?.data?.message || 'Error uploading photo'
      }
    }

    filesToUpload.value = []
    fileInput.value.value = ''
    successMessage.value = `Successfully uploaded ${completed} photo(s). Awaiting admin approval.`

    // Clear messages after 5 seconds
    setTimeout(() => {
      successMessage.value = ''
      errorMessage.value = ''
    }, 5000)

  } catch (error) {
    console.error('Upload error:', error)
    errorMessage.value = 'Error uploading photos'
  } finally {
    isUploading.value = false
    uploadProgress.value = 0
  }
}

const deletePhoto = async (photoId) => {
  if (!confirm('Are you sure you want to delete this photo?')) return

  try {
    await axios.delete(`/api/photos/${photoId}`)
    uploadedPhotos.value = uploadedPhotos.value.filter(p => p.id !== photoId)
    successMessage.value = 'Photo deleted successfully'

    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error deleting photo'
  }
}

// Load existing photos on mount
onMounted(async () => {
  try {
    const response = await axios.get(`/api/biodata/${props.biodataId}/photos`)
    uploadedPhotos.value = response.data.photos
  } catch (error) {
    console.error('Error loading photos:', error)
  }
})
</script>

<style scoped>
.upload-photo-component {
  @apply space-y-4;
}
</style>
