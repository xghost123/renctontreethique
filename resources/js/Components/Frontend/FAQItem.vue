<template>
  <div class="border border-gray-200 rounded-lg mb-4 overflow-hidden">
    <button
      @click="isOpen = !isOpen"
      class="w-full px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition"
    >
      <h3 class="font-semibold text-[#0D2218] text-left">{{ question }}</h3>
      <span :class="['text-[#C8A028] text-xl transition', isOpen ? 'rotate-180' : '']">
        ▼
      </span>
    </button>
    
    <transition name="expand">
      <div v-if="isOpen" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <p class="text-gray-600 leading-relaxed">{{ answer }}</p>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  question: String,
  answer: String
});

const isOpen = ref(false);
</script>

<style scoped>
.expand-enter-active, .expand-leave-active {
  transition: all 0.3s ease;
}
.expand-enter-from, .expand-leave-to {
  opacity: 0;
  max-height: 0;
}
.expand-enter-to, .expand-leave-from {
  opacity: 1;
  max-height: 500px;
}
</style>
