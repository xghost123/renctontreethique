<template>
  <div class="flex flex-col items-center justify-center">
    <svg :width="size" :height="size" class="drop-shadow-lg">
      <!-- Pie slices -->
      <path
        v-for="(slice, i) in slices"
        :key="`slice-${i}`"
        :d="slice.path"
        :fill="colors[i % colors.length]"
        class="transition-all duration-300 hover:opacity-80 cursor-pointer"
        stroke="rgba(30, 41, 59, 0.9)"
        stroke-width="2"
      >
        <title>{{ slice.label }}: {{ slice.value }}</title>
      </path>

      <!-- Labels -->
      <text
        v-for="(slice, i) in slices"
        :key="`label-${i}`"
        :x="slice.labelX"
        :y="slice.labelY"
        class="text-sm font-semibold fill-white pointer-events-none"
        text-anchor="middle"
      >
        {{ slice.label }}
      </text>
    </svg>

    <!-- Legend -->
    <div class="mt-6 flex flex-wrap gap-4 justify-center">
      <div v-for="(slice, i) in slices" :key="`legend-${i}`" class="flex items-center gap-2">
        <div :class="`w-3 h-3 rounded-full`" :style="{ backgroundColor: colors[i % colors.length] }"></div>
        <span class="text-sm text-blue-200">{{ slice.label }}: {{ slice.value }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  data: {
    type: Object,
    default: () => ({})
  }
})

const size = 300
const radius = 100
const centerX = size / 2
const centerY = size / 2

const colors = [
  'rgb(59, 130, 246)',    // blue-500
  'rgb(255, 107, 107)',   // coral-500
  'rgb(23, 162, 184)',    // teal-500
  'rgb(34, 197, 94)',     // green-500
  'rgb(168, 85, 247)',    // purple-500
]

const slices = computed(() => {
  const entries = Object.entries(props.data)
  const total = entries.reduce((sum, [_, value]) => sum + (value || 0), 0)
  
  if (total === 0) return []

  let currentAngle = -Math.PI / 2
  
  return entries.map(([label, value], index) => {
    const sliceValue = value || 0
    const sliceAngle = (sliceValue / total) * 2 * Math.PI
    const endAngle = currentAngle + sliceAngle
    
    // Calculate path
    const x1 = centerX + radius * Math.cos(currentAngle)
    const y1 = centerY + radius * Math.sin(currentAngle)
    const x2 = centerX + radius * Math.cos(endAngle)
    const y2 = centerY + radius * Math.sin(endAngle)
    
    const largeArc = sliceAngle > Math.PI ? 1 : 0
    
    const path = [
      `M ${centerX} ${centerY}`,
      `L ${x1} ${y1}`,
      `A ${radius} ${radius} 0 ${largeArc} 1 ${x2} ${y2}`,
      'Z'
    ].join(' ')
    
    // Label position
    const labelAngle = currentAngle + sliceAngle / 2
    const labelRadius = radius * 0.65
    const labelX = centerX + labelRadius * Math.cos(labelAngle)
    const labelY = centerY + labelRadius * Math.sin(labelAngle)
    
    currentAngle = endAngle
    
    return {
      label: label.replace(/_/g, ' '),
      value: sliceValue,
      path,
      labelX,
      labelY,
      percentage: ((sliceValue / total) * 100).toFixed(1)
    }
  })
})
</script>
