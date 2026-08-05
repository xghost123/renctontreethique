<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6">
      <p class="text-sm text-gray-700"><strong>Étape 8 (Finale):</strong> Présentez-vous en quelques mots mémorables.</p>
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Mot de présentation personnel <span class="text-red-500">*</span>
      </label>
      <textarea
        v-model="form.presentation"
        placeholder="Présentez-vous de manière authentique... (50-500 caractères)"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        rows="6"
        minlength="50"
        maxlength="500"
        required
      ></textarea>
      <p class="text-sm text-gray-500 mt-1">{{ form.presentation?.length || 0 }} / 500 caractères</p>
    </div>

    <div>
      <label class="flex items-start">
        <input v-model="form.acceptTerms" type="checkbox" class="w-4 h-4 mt-1" required />
        <span class="ml-3 text-sm text-gray-700">
          Je confirme que les informations fournies sont honnêtes et respectueuses <span class="text-red-500">*</span>
        </span>
      </label>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
      <p class="text-sm text-gray-700">
        <strong>💡 Conseil:</strong> Soyez authentique, bref et positif. C'est votre dernière chance de faire bonne impression!
      </p>
    </div>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({ data: { type: Object, default: () => ({}) } });
const emit = defineEmits(['update']);
const form = ref({ 
  presentation: props.data.presentation || '',
  acceptTerms: props.data.acceptTerms || false
});

const handleSubmit = () => { 
  emit('update', form.value); 
};

watch(form, () => { 
  emit('update', form.value); 
}, { deep: true });
</script>
