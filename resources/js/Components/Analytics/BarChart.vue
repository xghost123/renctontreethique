<template>
  <div>
    <svg :width="width" :height="height" class="drop-shadow-lg">
      <!-- Y-axis labels -->
      <text v-for="i in 5" :key="`y-${i}`" x="35" :y="height - i * (height / 5) + 5" class="text-xs fill-blue-300" text-anchor="end">
        {{ Math.round(maxValue * (i / 5)) }}
      </text>

      <!-- Y-axis -->
      <line x1="40" y1="0" x2="40" :y2="height" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
      
      <!-- X-axis -->
      <line x1="40" :y1="height" :x2="width" :y2="height" stroke="rgba(255,255,255,0.3)" stroke-width="2" />

      <!-- Bars -->
      <g v-for="(item, i) in chartData" :key="`bar-${i}`">
        <rect
          :x="40 + i * barWidth + barSpacing"
          :y="height - (item.value / maxValue) * (height - 20)"
          :width="barWidth - barSpacing"
          :height="(item.value / maxValue) * (height - 20)"
          :fill="colors[i % colors.length]"
          class="transition-all duration-300 hover:opacity-80 cursor-pointer"
          rx="4"
        >
          <title>{{ item.label }}: {{ item.value }}</title>
        </rect>
        <text
          :x="40 + i * barWidth + barSpacing + (barWidth - barSpacing) / 2"
          :y="height + 15"
          class="text-xs fill-blue-300"
          text-anchor="middle"
        >
          {{ item.label }}
        </text>
      </g>
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
  label: {
    type: String,
    default: 'Value'
  }
})

const width = 600
const height = 300
const barSpacing = 8

const colors = [
  'rgb(59, 130, 246)',    // blue-500
  'rgb(255, 107, 107)',   // coral-500
  'rgb(23, 162, 184)',    // teal-500
  'rgb(34, 197, 94)',     // green-500
  'rgb(168, 85, 247)',    // purple-500
]

const chartData = computed(() => {
  return Object.entries(props.data).map(([label, value]) => ({
    label: label.replace(/_/g, ' ').substring(0, 8),
    value: typeof value === 'number' ? value : 0
  }))
})

const maxValue = computed(() => {
  const max = Math.max(...chartData.value.map(d => d.value), 1)
  return Math.ceil(max / 10) * 10
})

const barWidth = computed(() => {
  return chartData.value.length > 0 ? (width - 60) / chartData.value.length : 1
})
</script>
