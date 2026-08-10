<template>
  <div class="message-input-component">
    <div class="flex gap-3 items-end">
      <!-- Message textarea -->
      <div class="flex-1">
        <textarea
          v-model="messageText"
          @keydown.enter.exact.prevent="sendMessage"
          @input="handleInput"
          class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0f3a7d]/20 focus:border-transparent bg-white text-gray-900 placeholder-gray-400 resize-none"
          placeholder="Type a message..."
          rows="1"
          :disabled="disabled || sending"
        ></textarea>
      </div>

      <!-- Send button -->
      <button
        @click="sendMessage"
        :disabled="disabled || sending || !messageText.trim()"
        class="bg-[#0f3a7d] hover:bg-[#0f3a7d]/90 text-white px-4 py-3 rounded-lg transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center"
        title="Send message (Enter)"
      >
        <svg v-if="!sending" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
          <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
        </svg>
        <svg v-else class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </button>
    </div>

    <!-- Help text -->
    <p class="text-xs text-gray-500 mt-2">Press Enter to send, Shift+Enter for new line</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  disabled: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['send', 'typing'])

const messageText = ref('')
const sending = ref(false)
let typingTimeout = null

const sendMessage = async () => {
  if (!messageText.value.trim() || sending.value) {
    return
  }

  const message = messageText.value.trim()
  messageText.value = ''

  try {
    sending.value = true
    await emit('send', message)
  } finally {
    sending.value = false
  }
}

const handleInput = () => {
  emit('typing', true)

  // Clear existing timeout
  clearTimeout(typingTimeout)

  // Debounce typing indicator
  typingTimeout = setTimeout(() => {
    emit('typing', false)
  }, 1500)
}

// Cleanup on unmount
const cleanup = () => {
  clearTimeout(typingTimeout)
}

// Export for parent to call on unmount
defineExpose({
  cleanup,
  focus: () => {
    // Could be used to focus the textarea
  },
})
</script>

<style scoped>
.message-input-component {
  @apply w-full;
}

/* Auto-expand textarea */
textarea {
  overflow: hidden;
  max-height: 120px;
}

textarea:focus {
  height: auto;
}
</style>
