<template>
  <div class="flex flex-col items-center text-center">
    <div class="w-16 h-16 bg-[#C8A028] text-[#0D2218] rounded-full flex items-center justify-center text-2xl font-bold mb-4">
      {{ step }}
    </div>
    <h3 class="text-xl font-bold text-[#0D2218] mb-3">{{ title }}</h3>
    <p class="text-gray-600">{{ description }}</p>
  </div>
</template>

<script setup>
defineProps({
  step: Number,
  title: String,
  description: String
});
</script>
