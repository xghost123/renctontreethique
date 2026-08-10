<template>
  <div class="typing-indicator flex items-center gap-1">
    <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0s"></span>
    <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
    <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
  </div>
</template>

<script setup>
// Simple typing indicator component - no logic needed
</script>

<style scoped>
.typing-indicator {
  @apply inline-flex;
}
</style>
