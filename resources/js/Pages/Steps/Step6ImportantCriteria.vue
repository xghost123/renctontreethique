<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6">
      <p class="text-sm text-gray-700"><strong>Étape 6:</strong> Définissez vos critères importants pour un partenaire.</p>
    </div>
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
      <p class="text-sm text-gray-700"><strong>💡 Conseil:</strong> Soyez réaliste dans vos attentes envers un partenaire.</p>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({ data: { type: Object, default: () => ({}) } });
const emit = defineEmits(['update']);
const form = ref(props.data || {});
const handleSubmit = () => { emit('update', form.value); };
</script>
