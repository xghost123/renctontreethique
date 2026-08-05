<template>
  <div class="filter-group">
    <label class="flex items-center gap-2 mb-3 text-white font-semibold">
      <span class="text-lg">{{ icon }}</span>
      <span>{{ label }}</span>
    </label>
    <div class="pl-6">
      <slot />
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  icon: String,
})
</script>

<style scoped>
.filter-group {
  animation: fadeIn 0.2s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
