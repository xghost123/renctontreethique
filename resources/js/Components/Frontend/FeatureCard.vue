<template>
  <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition h-full border-t-4 border-[#C8A028]">
    <div class="text-4xl mb-4">{{ icon }}</div>
    <h3 class="text-2xl font-bold text-[#0D2218] mb-4">{{ title }}</h3>
    <p class="text-gray-600 leading-relaxed">{{ description }}</p>
  </div>
</template>

<script setup>
defineProps({
  icon: String,
  title: String,
  description: String
});
</script>
