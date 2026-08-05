<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <!-- Statut matrimonial (Marital status) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-3">
        Statut matrimonial <span class="text-red-500">*</span>
      </label>
      <div class="space-y-2">
        <label class="flex items-center">
          <input v-model="form.maritalStatus" type="radio" value="single" class="w-4 h-4" required />
          <span class="ml-3 text-gray-700">Célibataire</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.maritalStatus" type="radio" value="divorced" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Divorcé(e)</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.maritalStatus" type="radio" value="widowed" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Veuf/Veuve</span>
        </label>
      </div>
      <p v-if="errors.maritalStatus" class="text-red-500 text-sm mt-1">{{ errors.maritalStatus }}</p>
    </div>

    <!-- Enfants (Children) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-3">
        Avez-vous des enfants? <span class="text-red-500">*</span>
      </label>
      <div class="space-y-2">
        <label class="flex items-center">
          <input v-model="form.hasChildren" type="radio" value="yes" class="w-4 h-4" required />
          <span class="ml-3 text-gray-700">Oui</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.hasChildren" type="radio" value="no" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Non</span>
        </label>
      </div>

      <!-- Nombre d'enfants (conditional) -->
      <div v-if="form.hasChildren === 'yes'" class="mt-4">
        <label class="block text-sm font-semibold text-[#0D2218] mb-2">
          Nombre d'enfants <span class="text-red-500">*</span>
        </label>
        <input
          v-model.number="form.numberOfChildren"
          type="number"
          min="1"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A028] focus:border-transparent outline-none transition"
          required
        />
      </div>

      <!-- Garde des enfants (conditional) -->
      <div v-if="form.hasChildren === 'yes'" class="mt-4">
        <label class="block text-sm font-semibold text-[#0D2218] mb-2">
          Garde des enfants <span class="text-red-500">*</span>
        </label>
        <div class="space-y-2">
          <label class="flex items-center">
            <input v-model="form.childCustody" type="radio" value="with_me" class="w-4 h-4" required />
            <span class="ml-3 text-gray-700">Avec moi</span>
          </label>
          <label class="flex items-center">
            <input v-model="form.childCustody" type="radio" value="not_with_me" class="w-4 h-4" />
            <span class="ml-3 text-gray-700">Pas avec moi</span>
          </label>
          <label class="flex items-center">
            <input v-model="form.childCustody" type="radio" value="other" class="w-4 h-4" />
            <span class="ml-3 text-gray-700">Autre</span>
          </label>
        </div>
      </div>
    </div>

    <!-- Situation financière (Financial situation) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-3">
        Situation financière
      </label>
      <div class="space-y-2">
        <label class="flex items-center">
          <input v-model="form.financialSituation" type="radio" value="comfortable" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Aisée</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.financialSituation" type="radio" value="balanced" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Équilibrée</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.financialSituation" type="radio" value="difficult" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Difficile</span>
        </label>
      </div>
    </div>

    <!-- Logement (Housing) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-3">
        Situation de logement <span class="text-red-500">*</span>
      </label>
      <div class="space-y-2">
        <label class="flex items-center">
          <input v-model="form.housing" type="radio" value="owner" class="w-4 h-4" required />
          <span class="ml-3 text-gray-700">Propriétaire</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.housing" type="radio" value="renter" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Locataire</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.housing" type="radio" value="with_parents" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Chez les parents</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.housing" type="radio" value="other" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Autre</span>
        </label>
      </div>
      <p v-if="errors.housing" class="text-red-500 text-sm mt-1">{{ errors.housing }}</p>
    </div>

    <!-- Indépendance financière (Financial independence) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-3">
        Êtes-vous financièrement indépendant(e)? <span class="text-red-500">*</span>
      </label>
      <div class="space-y-2">
        <label class="flex items-center">
          <input v-model="form.financialIndependence" type="radio" value="yes" class="w-4 h-4" required />
          <span class="ml-3 text-gray-700">Oui</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.financialIndependence" type="radio" value="no" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Non</span>
        </label>
      </div>
      <p v-if="errors.financialIndependence" class="text-red-500 text-sm mt-1">{{ errors.financialIndependence }}</p>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
      <p class="text-sm text-gray-700">
        <strong>💡 Conseil:</strong> Ces informations aident les autres utilisateurs à mieux vous connaître. Soyez transparent et honnête.
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

const emit = defineEmits(['update']);

const form = ref({
  maritalStatus: props.data.maritalStatus || '',
  hasChildren: props.data.hasChildren || 'no',
  numberOfChildren: props.data.numberOfChildren || null,
  childCustody: props.data.childCustody || '',
  financialSituation: props.data.financialSituation || '',
  housing: props.data.housing || '',
  financialIndependence: props.data.financialIndependence || ''
});

const errors = ref({});

const validateForm = () => {
  errors.value = {};
  
  if (!form.value.maritalStatus) errors.value.maritalStatus = 'Le statut matrimonial est requis';
  if (!form.value.hasChildren) errors.value.hasChildren = 'Veuillez répondre';
  if (form.value.hasChildren === 'yes') {
    if (!form.value.numberOfChildren || form.value.numberOfChildren < 1) {
      errors.value.numberOfChildren = 'Veuillez indiquer le nombre d\'enfants';
    }
    if (!form.value.childCustody) errors.value.childCustody = 'Veuillez indiquer la garde';
  }
  if (!form.value.housing) errors.value.housing = 'La situation de logement est requise';
  if (!form.value.financialIndependence) errors.value.financialIndependence = 'Veuillez répondre';
  
  return Object.keys(errors.value).length === 0;
};

const handleSubmit = () => {
  if (validateForm()) {
    emit('update', form.value);
  }
};

watch(form, (newVal) => {
  if (Object.keys(errors.value).length === 0) {
    emit('update', newVal);
  }
}, { deep: true });
</script>
