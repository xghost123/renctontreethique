<template>
  <div class="overflow-x-auto">
    <div class="min-w-max">
      <div class="flex gap-1 mb-4">
        <div v-for="day in dayLabels" :key="day" class="w-12 text-center text-xs text-blue-300 font-medium">
          {{ day }}
        </div>
      </div>
      
      <div v-for="hour in 24" :key="hour" class="flex gap-1 mb-1 items-center">
        <span class="text-xs text-blue-300 w-8 text-right">{{ formatHour(hour) }}</span>
        <div class="flex gap-1">
          <div
            v-for="day in dayLabels"
            :key="`${day}-${hour}`"
            class="w-12 h-12 rounded transition-all duration-300 cursor-pointer hover:scale-110"
            :style="{
              backgroundColor: getHeatmapColor(data[day]?.[hour] || 0),
              boxShadow: getHeatmapColor(data[day]?.[hour] || 0) + '80 0px 8px'
            }"
            :title="`${day} at ${formatHour(hour)}: ${data[day]?.[hour] || 0} activities`"
          ></div>
        </div>
      </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 flex items-center gap-3 justify-center">
      <span class="text-xs text-blue-300">Less</span>
      <div class="flex gap-1">
        <div v-for="i in 5" :key="`legend-${i}`" class="w-4 h-4 rounded" :style="{ backgroundColor: getHeatmapColor(maxValue * (i / 5)) }"></div>
      </div>
      <span class="text-xs text-blue-300">More</span>
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

const dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

const maxValue = computed(() => {
  let max = 0
  Object.values(props.data).forEach(dayData => {
    if (typeof dayData === 'object') {
      const dayMax = Math.max(...Object.values(dayData))
      max = Math.max(max, dayMax)
    }
  })
  return Math.max(max, 1)
})

const getHeatmapColor = (value) => {
  const intensity = Math.min(value / maxValue.value, 1)
  
  if (intensity === 0) {
    return 'rgba(255, 255, 255, 0.1)'
  } else if (intensity < 0.2) {
    return 'rgb(59, 130, 246)' // blue-500
  } else if (intensity < 0.4) {
    return 'rgb(37, 99, 235)' // blue-600
  } else if (intensity < 0.6) {
    return 'rgb(29, 78, 216)' // blue-700
  } else if (intensity < 0.8) {
    return 'rgb(23, 162, 184)' // teal-500
  } else {
    return 'rgb(34, 197, 94)' // green-500
  }
}

const formatHour = (hour) => {
  return `${hour.toString().padStart(2, '0')}:00`
}
</script>
