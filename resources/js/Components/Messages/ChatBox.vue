<template>
  <div class="chatbox-component">
    <!-- Chat Container -->
    <div class="flex flex-col h-full bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <!-- Header -->
      <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-[#1C4532]/5 to-transparent">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#1C4532]/10 flex items-center justify-center text-[#1C4532] font-bold text-sm">
              {{ (recipientName || '?').charAt(0).toUpperCase() }}
            </div>
            <div>
              <h3 class="font-semibold text-[#1C4532]">{{ recipientName || 'Loading...' }}</h3>
              <p class="text-xs text-gray-500">
                <span v-if="isOnline" class="inline-flex items-center gap-1">
                  <span class="w-2 h-2 bg-green-500 rounded-full"></span> Online
                </span>
                <span v-else class="text-gray-400">Offline</span>
              </p>
            </div>
          </div>
          <!-- Unread badge -->
          <div v-if="unreadCount > 0" class="bg-[#C8A028] text-white text-xs font-bold px-2.5 py-1 rounded-full">
            {{ unreadCount }} new
          </div>
        </div>
      </div>

      <!-- Messages Area -->
      <div ref="messagesContainer" class="flex-1 overflow-y-auto px-5 py-4 space-y-3 bg-gray-50">
        <!-- Loading skeleton -->
        <div v-if="loading && messages.length === 0" class="space-y-3">
          <div v-for="i in 3" :key="i" class="flex gap-3 items-start">
            <div class="w-8 h-8 rounded-full bg-gray-200 animate-pulse"></div>
            <div class="flex-1 space-y-2">
              <div class="h-4 bg-gray-200 rounded w-2/3 animate-pulse"></div>
              <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else-if="!loading && messages.length === 0" class="flex flex-col items-center justify-center h-full text-center py-12">
          <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-gray-500 text-sm">No messages yet</p>
          <p class="text-gray-400 text-xs mt-1">Start the conversation below</p>
        </div>

        <!-- Messages -->
        <div v-for="msg in messages" :key="msg.id" :class="['flex gap-2', msg.is_from_me ? 'justify-end' : 'justify-start']">
          <!-- Received message -->
          <template v-if="!msg.is_from_me">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 rounded-full bg-[#1C4532]/10 flex items-center justify-center text-[#1C4532] font-bold text-xs">
                {{ (recipientName || '?').charAt(0).toUpperCase() }}
              </div>
            </div>
            <div class="max-w-xs">
              <div class="bg-gray-200 text-gray-900 px-4 py-2.5 rounded-2xl rounded-tl-sm">
                <p class="text-sm leading-relaxed whitespace-pre-wrap break-words">{{ msg.body }}</p>
              </div>
              <p class="text-xs text-gray-500 mt-1 px-2">{{ formatTime(msg.created_at) }}</p>
            </div>
          </template>

          <!-- Sent message -->
          <template v-else>
            <div class="max-w-xs">
              <div class="bg-[#1C4532] text-white px-4 py-2.5 rounded-2xl rounded-tr-sm">
                <p class="text-sm leading-relaxed whitespace-pre-wrap break-words">{{ msg.body }}</p>
              </div>
              <div class="flex items-center justify-end gap-2 mt-1 px-2">
                <p class="text-xs text-gray-500">{{ formatTime(msg.created_at) }}</p>
                <!-- Status indicator -->
                <span v-if="msg.status === 'sent'" class="text-xs text-gray-400" title="Sending...">
                  ✓
                </span>
                <span v-else-if="msg.status === 'delivered'" class="text-xs text-gray-500" title="Delivered">
                  ✓ ✓
                </span>
                <span v-else-if="msg.status === 'read'" class="text-xs text-blue-500" title="Read">
                  ✓ ✓ ✓
                </span>
              </div>
            </div>
          </template>
        </div>

        <!-- Typing indicator -->
        <div v-if="isTyping" class="flex gap-2 items-start">
          <div class="w-8 h-8 rounded-full bg-[#1C4532]/10 flex items-center justify-center text-[#1C4532] font-bold text-xs flex-shrink-0">
            {{ (recipientName || '?').charAt(0).toUpperCase() }}
          </div>
          <div class="bg-gray-200 px-4 py-2 rounded-2xl rounded-tl-sm">
            <div class="flex gap-1">
              <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></span>
              <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
              <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Input Area -->
      <div class="px-5 py-4 border-t border-gray-100 bg-white">
        <div class="flex gap-3">
          <textarea
            v-model="messageText"
            @keydown.enter.exact.prevent="sendMessage"
            @input="handleInput"
            class="field-input-re !h-auto !py-3 resize-none flex-1"
            placeholder="Type a message..."
            rows="1"
          ></textarea>
          <button
            @click="sendMessage"
            :disabled="sending || !messageText.trim()"
            class="bg-[#1C4532] hover:bg-[#163828] text-white px-5 rounded-xl transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5 text-sm font-semibold"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
            </svg>
            Send
          </button>
        </div>
        <p class="text-xs text-gray-500 mt-2">Shift+Enter for new line</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  conversationId: {
    type: Number,
    required: true,
  },
  recipientId: {
    type: Number,
    required: true,
  },
  recipientName: {
    type: String,
    default: 'User',
  },
})

const emit = defineEmits(['message-sent', 'unread-changed'])

// State
const messages = ref([])
const messageText = ref('')
const loading = ref(true)
const sending = ref(false)
const unreadCount = ref(0)
const isOnline = ref(false)
const isTyping = ref(false)
const messagesContainer = ref(null)
const lastMessageId = ref(null)
const lastPollTime = ref(null)

// Polling
let pollInterval = null
const POLL_INTERVAL = 2000 // Poll every 2 seconds for MVP

/**
 * Initialize chat - load initial messages
 */
const loadMessages = async () => {
  try {
    loading.value = true
    const response = await axios.get(`/api/messages/conversation/${props.conversationId}`, {
      params: { limit: 50 },
    })
    messages.value = response.data.messages || []
    
    if (messages.value.length > 0) {
      lastMessageId.value = messages.value[messages.value.length - 1].id
    }
    
    await nextTick()
    scrollToBottom()
  } catch (error) {
    console.error('Error loading messages:', error)
  } finally {
    loading.value = false
  }
}

/**
 * Poll for new messages (MVP real-time solution)
 */
const pollForMessages = async () => {
  try {
    const sinceParam = lastPollTime.value 
      ? new Date(lastPollTime.value).toISOString()
      : null

    const response = await axios.get(`/api/messages/poll/${props.conversationId}`, {
      params: { since: sinceParam },
    })

    const newMessages = response.data.messages || []
    
    if (newMessages.length > 0) {
      messages.value.push(...newMessages)
      lastMessageId.value = newMessages[newMessages.length - 1].id
      
      // Count unread messages from recipient
      unreadCount.value = newMessages.filter(m => !m.is_from_me && m.status !== 'read').length
      
      if (unreadCount.value > 0) {
        emit('unread-changed', unreadCount.value)
      }
      
      await nextTick()
      scrollToBottom()
    }

    lastPollTime.value = response.data.timestamp
  } catch (error) {
    console.error('Error polling for messages:', error)
  }
}

/**
 * Send a message
 */
const sendMessage = async () => {
  if (!messageText.value.trim() || sending.value) {
    return
  }

  const body = messageText.value.trim()
  messageText.value = ''
  sending.value = true

  try {
    const response = await axios.post('/api/messages/send', {
      conversation_id: props.conversationId,
      body: body,
      language: 'fr',
    })

    const sentMessage = response.data.data
    messages.value.push(sentMessage)
    lastMessageId.value = sentMessage.id

    emit('message-sent', sentMessage)

    await nextTick()
    scrollToBottom()
  } catch (error) {
    console.error('Error sending message:', error)
    messageText.value = body // Restore message on error
  } finally {
    sending.value = false
  }
}

/**
 * Mark messages as read
 */
const markMessagesAsRead = async () => {
  const unreadMessages = messages.value.filter(
    m => !m.is_from_me && m.status !== 'read'
  )

  if (unreadMessages.length === 0) {
    return
  }

  try {
    await axios.post('/api/messages/mark-read', {
      message_ids: unreadMessages.map(m => m.id),
    })

    // Update local state
    unreadMessages.forEach(m => {
      m.status = 'read'
    })

    unreadCount.value = 0
    emit('unread-changed', 0)
  } catch (error) {
    console.error('Error marking messages as read:', error)
  }
}

/**
 * Scroll to bottom of messages
 */
const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

/**
 * Format time for display
 */
const formatTime = (timestamp) => {
  const date = new Date(timestamp)
  const now = new Date()
  const diffMs = now - date
  const diffMins = Math.floor(diffMs / 60000)

  if (diffMins < 1) {
    return 'now'
  } else if (diffMins < 60) {
    return `${diffMins}m ago`
  } else if (diffMins < 1440) {
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })
  } else {
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
  }
}

/**
 * Handle input - detect typing
 */
const handleInput = () => {
  isTyping.value = true
  // Debounce typing indicator
  clearTimeout(typingTimeout)
  typingTimeout = setTimeout(() => {
    isTyping.value = false
  }, 1000)
}

let typingTimeout = null

/**
 * Cleanup on unmount
 */
const cleanup = () => {
  if (pollInterval) {
    clearInterval(pollInterval)
  }
  clearTimeout(typingTimeout)
}

/**
 * Watch for unread messages and mark them as read
 */
watch(
  () => messages.value.length,
  () => {
    // Mark as read when chat is in view (would be better with Intersection Observer)
    markMessagesAsRead()
  }
)

/**
 * Lifecycle
 */
onMounted(() => {
  loadMessages()
  
  // Start polling for new messages
  lastPollTime.value = new Date().toISOString()
  pollInterval = setInterval(pollForMessages, POLL_INTERVAL)
  
  // Simulate online status
  isOnline.value = true
  
  // Mark initial messages as read
  markMessagesAsRead()
})

onUnmounted(() => {
  cleanup()
})
</script>

<style scoped>
.chatbox-component {
  @apply w-full h-full;
}

.field-input-re {
  @apply w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C4532]/20 focus:border-transparent bg-white text-gray-900 placeholder-gray-400;
}

/* Auto-expand textarea */
textarea {
  overflow: hidden;
}

textarea:focus {
  height: auto;
}
</style>
