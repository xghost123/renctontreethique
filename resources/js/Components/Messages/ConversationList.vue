<template>
  <div class="conversation-list-component h-full flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm">
    <!-- Header -->
    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0f3a7d]/5 to-transparent">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-[#0f3a7d]">Messages</h2>
        <button
          @click="$emit('refresh')"
          :disabled="loading"
          class="text-gray-500 hover:text-[#0f3a7d] transition-colors disabled:opacity-50"
          title="Refresh conversations"
        >
          <svg class="w-5 h-5 animate-spin" v-if="loading" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 4v6h6M23 20v-6h-6"></path>
            <path d="M20.49 9A9 9 0 0 0 5.64 5.64M3.51 15A9 9 0 0 0 18.36 18.36"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Search -->
    <div class="px-4 py-3 border-b border-gray-100">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search conversations..."
        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0f3a7d]/20"
      />
    </div>

    <!-- Conversations List -->
    <div class="flex-1 overflow-y-auto">
      <!-- Loading state -->
      <div v-if="loading && conversations.length === 0" class="space-y-2 px-3 py-3">
        <div v-for="i in 3" :key="i" class="p-3 rounded-lg bg-gray-100 animate-pulse">
          <div class="h-4 bg-gray-200 rounded w-2/3 mb-2"></div>
          <div class="h-3 bg-gray-200 rounded w-1/2"></div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-else-if="filteredConversations.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-gray-500 text-sm font-medium">No conversations</p>
        <p class="text-gray-400 text-xs mt-1">Start a new conversation</p>
      </div>

      <!-- Conversations -->
      <div class="divide-y divide-gray-100">
        <button
          v-for="conv in filteredConversations"
          :key="conv.id"
          @click="$emit('select', conv.id)"
          :class="[
            'w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors flex items-center justify-between',
            selectedId === conv.id ? 'bg-[#0f3a7d]/5 border-l-4 border-[#0f3a7d]' : ''
          ]"
        >
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3">
              <!-- Avatar -->
              <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#0f3a7d]/10 flex items-center justify-center text-[#0f3a7d] font-bold text-sm">
                {{ getInitial(conv) }}
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 truncate text-sm">
                  {{ getConversationName(conv) }}
                </p>
                <p class="text-gray-500 truncate text-xs">
                  {{ conv.last_message || 'No messages yet' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Unread badge -->
          <div v-if="conv.unread_count && conv.unread_count > 0" class="ml-2 flex-shrink-0">
            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#ff6b6b] text-white">
              {{ conv.unread_count }}
            </span>
          </div>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  conversations: {
    type: Array,
    default: () => [],
  },
  selectedId: {
    type: Number,
    default: null,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['select', 'refresh'])

const searchQuery = ref('')

const filteredConversations = computed(() => {
  if (!searchQuery.value.trim()) {
    return props.conversations
  }

  const query = searchQuery.value.toLowerCase()
  return props.conversations.filter(conv => {
    const name = getConversationName(conv).toLowerCase()
    const message = (conv.last_message || '').toLowerCase()
    return name.includes(query) || message.includes(query)
  })
})

const getConversationName = (conversation) => {
  // Assumes we have context of who the current user is
  // This should be passed as a prop or determined from the parent
  return conversation.owner_name === 'You'
    ? conversation.dest_name
    : conversation.owner_name
}

const getInitial = (conversation) => {
  const name = getConversationName(conversation)
  return name.charAt(0).toUpperCase()
}
</script>

<style scoped>
.conversation-list-component {
  @apply w-full;
}
</style>
