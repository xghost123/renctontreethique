<template>
  <div class="bg-white/5 border border-white/20 rounded-xl p-5 flex items-center justify-between hover:bg-white/10 transition-all duration-300">
    <div>
      <p class="text-blue-300 text-sm mb-1">{{ title }}</p>
      <p class="text-3xl font-bold text-white">{{ value }}</p>
      <p class="text-xs text-blue-400 mt-1">{{ subtitle }}</p>
    </div>
    <div class="text-4xl opacity-80">{{ getIcon(icon) }}</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  title: String,
  value: Number,
  subtitle: String,
  icon: String,
})

const getIcon = (icon) => {
  const icons = {
    send: '📤',
    inbox: '📥',
    users: '👥',
  }
  return icons[icon] || '📊'
}
</script>
