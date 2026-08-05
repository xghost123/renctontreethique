<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
      <p class="text-sm text-gray-700">
        <strong>⚠️  Step ${i}:</strong> Form fields for this step will be implemented based on requirements.
      </p>
    </div>
    
    <textarea
      v-model="form.notes"
      placeholder="Notes pour cette étape..."
      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A028] focus:border-transparent outline-none transition"
      rows="4"
    ></textarea>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
      <p class="text-sm text-gray-700">
        <strong>💡 Conseil:</strong> Complétez cette étape avec des informations honnêtes et détaillées.
      </p>
    </div>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  data: {
    type: Object,
    default: () => ({})
  }
});

const form = ref({
  notes: props.data.notes || ''
});

const handleSubmit = () => {
  emit('update', form.value);
};

const emit = defineEmits(['update']);

watch(form, (newVal) => {
  emit('update', newVal);
}, { deep: true });
</script>
