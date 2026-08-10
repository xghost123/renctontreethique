<template>
  <div class="flex items-center gap-4">
    <div class="flex-1">
      <p class="text-blue-300 text-sm mb-2">{{ label }}</p>
      <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden border border-white/20">
        <div
          class="h-full rounded-full transition-all duration-500"
          :class="color"
          :style="{ width: (value / max) * 100 + '%' }"
        ></div>
      </div>
    </div>
    <div class="text-right">
      <p class="text-2xl font-bold text-white">{{ value }}</p>
      <p class="text-xs text-blue-300">{{ Math.round((value / max) * 100) }}%</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: Number,
  max: Number,
  color: {
    type: String,
    default: 'bg-blue-500'
  }
})
</script>
