<template>
  <div class="flex flex-col items-center justify-center">
    <svg :width="width" :height="height" class="drop-shadow-lg">
      <!-- Grid lines -->
      <line v-for="i in 5" :key="`h-${i}`" :x1="0" :y1="i * (height / 5)" :x2="width" :y2="i * (height / 5)" stroke="rgba(255,255,255,0.1)" stroke-width="1" />
      
      <!-- Y-axis labels -->
      <text v-for="i in 5" :key="`y-${i}`" :x="40" :y="height - i * (height / 5) + 5" class="text-xs fill-blue-300" text-anchor="end">
        {{ Math.round(maxValue * (i / 5)) }}
      </text>

      <!-- Y-axis -->
      <line x1="50" :y1="0" x2="50" :y2="height" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
      
      <!-- X-axis -->
      <line x1="50" :y1="height" :x2="width" :y2="height" stroke="rgba(255,255,255,0.3)" stroke-width="2" />

      <!-- Path for line chart -->
      <polyline
        :points="linePoints"
        fill="none"
        stroke="url(#gradient)"
        stroke-width="3"
        stroke-linejoin="round"
      />

      <!-- Gradient definition -->
      <defs>
        <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" style="stop-color: rgb(59, 130, 246); stop-opacity: 1" />
          <stop offset="100%" style="stop-color: rgb(59, 130, 246); stop-opacity: 0.1" />
        </linearGradient>
      </defs>

      <!-- Area under curve -->
      <polygon
        :points="areaPoints"
        fill="url(#gradient)"
        opacity="0.3"
      />

      <!-- Data points -->
      <circle
        v-for="(point, i) in chartData"
        :key="`point-${i}`"
        :cx="50 + (i / (chartData.length - 1)) * (width - 70)"
        :cy="height - (point.value / maxValue) * (height - 20)"
        r="4"
        fill="rgb(59, 130, 246)"
        class="transition-all duration-300 hover:r-6 cursor-pointer"
      >
        <title>{{ point.date }}: {{ point.value }} views</title>
      </circle>

      <!-- X-axis labels (every 5th or 10th point) -->
      <text
        v-for="(point, i) in chartData"
        v-show="i % Math.ceil(chartData.length / 6) === 0"
        :key="`x-${i}`"
        :x="50 + (i / (chartData.length - 1)) * (width - 70)"
        :y="height + 20"
        class="text-xs fill-blue-300"
        text-anchor="middle"
      >
        {{ point.label }}
      </text>
    </svg>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  data: {
    type: Object,
    default: () => ({})
  },
  days: {
    type: Number,
    default: 30
  }
})

const width = 600
const height = 300

const chartData = computed(() => {
  const entries = Object.entries(props.data)
  return entries.map(([date, value], index) => ({
    date,
    value: typeof value === 'number' ? value : 0,
    label: new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
  }))
})

const maxValue = computed(() => {
  const max = Math.max(...chartData.value.map(d => d.value), 1)
  return Math.ceil(max / 10) * 10
})

const linePoints = computed(() => {
  if (chartData.value.length === 0) return ''
  
  return chartData.value
    .map((point, i) => {
      const x = 50 + (i / (chartData.value.length - 1)) * (width - 70)
      const y = height - (point.value / maxValue.value) * (height - 20)
      return `${x},${y}`
    })
    .join(' ')
})

const areaPoints = computed(() => {
  if (chartData.value.length === 0) return ''
  
  const points = chartData.value.map((point, i) => {
    const x = 50 + (i / (chartData.value.length - 1)) * (width - 70)
    const y = height - (point.value / maxValue.value) * (height - 20)
    return `${x},${y}`
  })
  
  points.push(`${width - 20},${height}`)
  points.push(`50,${height}`)
  
  return points.join(' ')
})
</script>
