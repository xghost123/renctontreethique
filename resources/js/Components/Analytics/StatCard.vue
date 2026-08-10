<template>
  <div class="bg-gradient-to-br from-white/20 to-white/5 backdrop-blur-md border border-white/30 rounded-xl p-6 hover:shadow-xl transition-all duration-300">
    <div class="flex items-start justify-between mb-4">
      <div>
        <p class="text-blue-300 text-sm font-medium mb-1">{{ title }}</p>
        <p class="text-4xl font-bold text-white">{{ formatNumber(value) }}<span class="text-xl text-blue-300">{{ unit }}</span></p>
      </div>
      <div class="text-3xl" :class="iconClass">{{ iconEmoji }}</div>
    </div>

    <!-- Trend indicator -->
    <div v-if="trend !== 0" class="flex items-center gap-2">
      <span :class="[
        'text-sm font-semibold',
        trend > 0 ? 'text-green-400' : 'text-red-400'
      ]">
        {{ trend > 0 ? '↑' : '↓' }} {{ Math.abs(trend) }}%
      </span>
      <span class="text-xs text-blue-300">vs last period</span>
    </div>
    <div v-else class="text-xs text-blue-300">No trend data</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  value: Number,
  trend: Number,
  color: String,
  icon: String,
  unit: String,
})

const iconEmoji = computed(() => {
  const icons = {
    eye: '👁️',
    heart: '❤️',
    message: '💬',
    check: '✓',
    send: '📤',
    inbox: '📥',
    users: '👥',
  }
  return icons[props.icon] || '📊'
})

const iconClass = computed(() => {
  return {
    sapphire: 'text-blue-400',
    coral: 'text-red-400',
    teal: 'text-teal-400',
    emerald: 'text-green-400',
  }[props.color] || 'text-blue-400'
})

const formatNumber = (num) => {
  if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M'
  if (num >= 1000) return (num / 1000).toFixed(1) + 'K'
  return num.toString()
}
</script>
