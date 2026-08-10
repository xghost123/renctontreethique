<template>
  <div :class="['online-status inline-flex items-center gap-1.5', statusClass]">
    <span class="w-2.5 h-2.5 rounded-full" :class="dotClass"></span>
    <span class="text-xs font-medium">{{ statusText }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  isOnline: {
    type: Boolean,
    default: false,
  },
  lastSeen: {
    type: String,
    default: null,
  },
})

const statusClass = computed(() => {
  return props.isOnline ? 'text-green-600' : 'text-gray-500'
})

const dotClass = computed(() => {
  return props.isOnline ? 'bg-green-500' : 'bg-gray-400'
})

const statusText = computed(() => {
  if (props.isOnline) {
    return 'Online'
  }
  if (props.lastSeen) {
    return `Last seen ${props.lastSeen}`
  }
  return 'Offline'
})
</script>

<style scoped>
.online-status {
  @apply inline-flex items-center gap-1.5;
}
</style>
