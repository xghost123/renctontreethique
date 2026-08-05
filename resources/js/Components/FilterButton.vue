<template>
  <button
    @click="$emit('click')"
    :class="[
      'px-4 py-2 rounded-lg font-medium transition duration-200 border',
      active
        ? 'bg-amber-600 text-white border-amber-500 shadow-lg'
        : 'bg-gray-700 text-gray-300 border-gray-600 hover:bg-gray-600 hover:border-amber-500',
    ]"
  >
    {{ label }}
  </button>
</template>

<script setup>
defineProps({
  label: String,
  active: Boolean,
})

defineEmits(['click'])
</script>
