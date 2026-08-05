<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import ChatBox from '@/Components/Messages/ChatBox.vue'

const conversations = ref([])
const activeConversationId = ref(null)
const activeConversation = ref(null)
const loading = ref(false)
const unreadCounts = ref({})
const totalUnread = computed(() => {
  return Object.values(unreadCounts.value).reduce((sum, count) => sum + count, 0)
})

/**
 * Load all conversations
 */
const loadConversations = async () => {
  try {
    loading.value = true
    const response = await axios.get('/api/chat/conversations')
    conversations.value = response.data.conversations || []
  } catch (error) {
    console.error('Error loading conversations:', error)
  } finally {
    loading.value = false
  }
}

/**
 * Open a conversation
 */
const openConversation = async (otherId, conversation = null) => {
  try {
    // If no conversation provided, create/get one
    if (!conversation) {
      const response = await axios.post('/api/chat/open', {
        other_id: otherId,
      })
      conversation = response.data.conversation
    }

    activeConversationId.value = conversation.id
    activeConversation.value = conversation
    
    // Clear unread count for this conversation
    if (unreadCounts.value[conversation.id]) {
      unreadCounts.value[conversation.id] = 0
    }
  } catch (error) {
    console.error('Error opening conversation:', error)
  }
}

/**
 * Handle message sent event
 */
const handleMessageSent = (message) => {
  // Reload conversations to update last_message and order
  loadConversations()
}

/**
 * Handle unread count change
 */
const handleUnreadChanged = (convId, count) => {
  unreadCounts.value[convId] = count
}

/**
 * Search or start new conversation
 */
const searchUsers = ref('')

/**
 * Format last message preview
 */
const getLastMessagePreview = (conversation) => {
  return conversation.last_message?.substring(0, 50) || 'No messages yet'
}

/**
 * Get unread count for conversation
 */
const getUnreadCount = (conversationId) => {
  return unreadCounts.value[conversationId] || 0
}

onMounted(() => {
  loadConversations()
  
  // Reload conversations every 5 seconds to stay in sync
  setInterval(loadConversations, 5000)
})
</script>

<template>
  <Head title="Messages — Rencontre Éthique" />

  <div class="min-h-screen pb-16" style="background: linear-gradient(180deg, #FBF7F0 0%, #F5EEDD 100%)">
    <div class="pattern-re absolute inset-0 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-5 pt-8">
      <div class="mb-8">
        <h1 class="font-display text-3xl font-medium text-[#0f3a7d] mb-2" style="font-family: 'Cormorant Garamond', serif">
          Messages
        </h1>
        <p class="text-sm text-[#8A9680]">
          <span v-if="totalUnread > 0" class="font-semibold">
            {{ totalUnread }} unread message<span v-if="totalUnread !== 1">s</span>
          </span>
          <span v-else>All caught up</span>
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <!-- Conversations Sidebar -->
        <div class="bg-white rounded-2xl border border-[#0f3a7d]/[.06] shadow-sm overflow-hidden md:col-span-1">
          <!-- Search -->
          <div class="px-4 py-3.5 border-b border-[#0f3a7d]/[.06]">
            <input
              v-model="searchUsers"
              type="text"
              placeholder="Search conversations..."
              class="field-input-re text-sm"
            />
          </div>

          <!-- Conversations List -->
          <div class="divide-y divide-[#0f3a7d]/[.04] max-h-[680px] overflow-y-auto">
            <button
              v-for="conv in conversations"
              :key="conv.id"
              @click="openConversation(conv.other_id, conv)"
              :class="[
                'w-full px-4 py-3.5 text-left hover:bg-[#F8F6F0] transition-colors flex items-center gap-3',
                activeConversationId === conv.id ? 'bg-[#F8F6F0] border-l-2 border-[#0f3a7d]' : 'border-l-2 border-transparent'
              ]"
            >
              <!-- Avatar -->
              <div class="w-10 h-10 rounded-full bg-[#0f3a7d]/[.07] flex items-center justify-center text-[#0f3a7d] font-bold text-sm flex-shrink-0">
                {{ (conv.other_name || '?').charAt(0).toUpperCase() }}
              </div>

              <!-- Info -->
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                  <span class="font-semibold text-[#0f3a7d] text-sm truncate">
                    {{ conv.other_name }}
                  </span>
                  <span
                    v-if="getUnreadCount(conv.id) > 0"
                    class="bg-[#ff6b6b] text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0"
                  >
                    {{ getUnreadCount(conv.id) }}
                  </span>
                </div>
                <p class="text-xs text-[#8A9680] truncate mt-0.5">
                  {{ getLastMessagePreview(conv) }}
                </p>
              </div>
            </button>

            <div v-if="!loading && conversations.length === 0" class="px-4 py-10 text-center text-sm text-[#8A9680]">
              No conversations yet<br />
              <span class="text-xs">Find members to start messaging</span>
            </div>
          </div>
        </div>

        <!-- Chat Area -->
        <div class="md:col-span-2">
          <template v-if="activeConversation">
            <ChatBox
              :key="activeConversation.id"
              :conversation-id="activeConversation.id"
              :recipient-id="activeConversation.other_id"
              :recipient-name="activeConversation.other_name"
              @message-sent="handleMessageSent"
              @unread-changed="(count) => handleUnreadChanged(activeConversation.id, count)"
            />
          </template>

          <template v-else>
            <div class="bg-white rounded-2xl border border-[#0f3a7d]/[.06] shadow-sm h-[600px] flex flex-col items-center justify-center p-10 text-center">
              <div class="w-16 h-16 rounded-full bg-[#0f3a7d]/[.06] flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#0f3a7d]" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm0 14H5.17L4 17.17V4h16v12zM7 9h10v2H7V9zm0 4h7v2H7v-2z" />
                </svg>
              </div>
              <h3 class="font-display text-xl font-medium text-[#0f3a7d] mb-2" style="font-family: 'Cormorant Garamond', serif">
                Select a conversation
              </h3>
              <p class="text-sm text-[#8A9680] max-w-xs mb-6">
                Choose a conversation from the list or find a member to start messaging.
              </p>
              <Link href="/app/members" class="btn-re btn-re-primary px-6 py-2.5 text-sm">
                Discover Members →
              </Link>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.field-input-re {
  @apply w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0f3a7d]/20 focus:border-transparent bg-white text-gray-900 placeholder-gray-400 text-sm;
}
</style>
