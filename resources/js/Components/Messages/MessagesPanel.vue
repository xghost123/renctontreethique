<template>
  <div class="messages-panel min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-120px)]">
      <!-- Conversations List -->
      <div class="lg:col-span-1">
        <ConversationList
          :conversations="conversations"
          :selected-id="selectedConversationId"
          :loading="conversationsLoading"
          @select="selectConversation"
          @refresh="refreshConversations"
        />
      </div>

      <!-- Chat View -->
      <div class="lg:col-span-2">
        <div v-if="selectedConversation" class="h-full flex flex-col">
          <ChatBox
            :conversation-id="selectedConversation.id"
            :recipient-id="getRecipientId(selectedConversation)"
            :recipient-name="getRecipientName(selectedConversation)"
            @message-sent="onMessageSent"
            @unread-changed="onUnreadChanged"
          />
        </div>
        <div v-else class="h-full flex items-center justify-center bg-white rounded-lg border border-gray-200">
          <div class="text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-500 text-lg font-medium">Select a conversation</p>
            <p class="text-gray-400 text-sm mt-2">Choose from your conversations to start messaging</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import ConversationList from './ConversationList.vue'
import ChatBox from './ChatBox.vue'

const conversations = ref([])
const conversationsLoading = ref(false)
const selectedConversationId = ref(null)
const currentUserId = ref(null)

const selectedConversation = computed(() => {
  return conversations.value.find(c => c.id === selectedConversationId.value)
})

const getRecipientId = (conversation) => {
  if (!currentUserId.value) return null
  return conversation.owner_id === currentUserId.value
    ? conversation.dest_id
    : conversation.owner_id
}

const getRecipientName = (conversation) => {
  if (!currentUserId.value) return 'User'
  return conversation.owner_id === currentUserId.value
    ? conversation.dest_name
    : conversation.owner_name
}

const loadConversations = async () => {
  try {
    conversationsLoading.value = true
    // First get current user
    const userRes = await axios.get('/api/user')
    currentUserId.value = userRes.data.id

    // Then load conversations - we'll implement a dedicated endpoint
    // For now, we fetch from the backend
    const response = await axios.get('/api/conversations')
    conversations.value = response.data.conversations || []

    // Select first conversation by default
    if (conversations.value.length > 0 && !selectedConversationId.value) {
      selectedConversationId.value = conversations.value[0].id
    }
  } catch (error) {
    console.error('Error loading conversations:', error)
  } finally {
    conversationsLoading.value = false
  }
}

const selectConversation = (conversationId) => {
  selectedConversationId.value = conversationId
}

const refreshConversations = async () => {
  await loadConversations()
}

const onMessageSent = (message) => {
  // Update the conversation with new message
  const conversation = conversations.value.find(c => c.id === message.conversation_id)
  if (conversation) {
    conversation.last_message = message.body
    conversation.updated_at = message.created_at
  }
}

const onUnreadChanged = (conversationId, unreadCount) => {
  const conversation = conversations.value.find(c => c.id === conversationId)
  if (conversation) {
    conversation.unread_count = unreadCount
  }
}

onMounted(() => {
  loadConversations()
})
</script>

<style scoped>
.messages-panel {
  @apply w-full min-h-screen;
}
</style>
