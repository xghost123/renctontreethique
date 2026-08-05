<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <!-- Prénom (First name) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Prénom <span class="text-red-500">*</span>
      </label>
      <input
        v-model="form.firstName"
        type="text"
        placeholder="Votre prénom"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      />
      <p v-if="errors.firstName" class="text-red-500 text-sm mt-1">{{ errors.firstName }}</p>
    </div>

    <!-- Nom de famille (Last name) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Nom de famille <span class="text-red-500">*</span>
      </label>
      <input
        v-model="form.lastName"
        type="text"
        placeholder="Votre nom"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      />
      <p v-if="errors.lastName" class="text-red-500 text-sm mt-1">{{ errors.lastName }}</p>
    </div>

    <!-- Date de naissance (Date of birth) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Date de naissance <span class="text-red-500">*</span>
      </label>
      <input
        v-model="form.dateOfBirth"
        type="date"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      />
      <p v-if="errors.dateOfBirth" class="text-red-500 text-sm mt-1">{{ errors.dateOfBirth }}</p>
    </div>

    <!-- Genre (Gender) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-3">
        Genre <span class="text-red-500">*</span>
      </label>
      <div class="space-y-2">
        <label class="flex items-center">
          <input v-model="form.gender" type="radio" value="male" class="w-4 h-4" required />
          <span class="ml-3 text-gray-700">Homme</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.gender" type="radio" value="female" class="w-4 h-4" required />
          <span class="ml-3 text-gray-700">Femme</span>
        </label>
      </div>
      <p v-if="errors.gender" class="text-red-500 text-sm mt-1">{{ errors.gender }}</p>
    </div>

    <!-- Nationalité (Nationality) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Nationalité <span class="text-red-500">*</span>
      </label>
      <select
        v-model="form.nationality"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      >
        <option value="">-- Sélectionner --</option>
        <option value="french">Française</option>
        <option value="algerian">Algérienne</option>
        <option value="moroccan">Marocaine</option>
        <option value="tunisian">Tunisienne</option>
        <option value="belgian">Belge</option>
        <option value="swiss">Suisse</option>
        <option value="other">Autre</option>
      </select>
      <p v-if="errors.nationality" class="text-red-500 text-sm mt-1">{{ errors.nationality }}</p>
    </div>

    <!-- Pays de résidence (Country of residence) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Pays de résidence <span class="text-red-500">*</span>
      </label>
      <select
        v-model="form.countryOfResidence"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      >
        <option value="">-- Sélectionner --</option>
        <option value="france">France</option>
        <option value="belgium">Belgique</option>
        <option value="switzerland">Suisse</option>
        <option value="algeria">Algérie</option>
        <option value="morocco">Maroc</option>
        <option value="tunisia">Tunisie</option>
        <option value="other">Autre</option>
      </select>
      <p v-if="errors.countryOfResidence" class="text-red-500 text-sm mt-1">{{ errors.countryOfResidence }}</p>
    </div>

    <!-- Région/Division (Region/Division) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Région/Division <span class="text-red-500">*</span>
      </label>
      <input
        v-model="form.region"
        type="text"
        placeholder="Votre région"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      />
      <p v-if="errors.region" class="text-red-500 text-sm mt-1">{{ errors.region }}</p>
    </div>

    <!-- Ville (City) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Ville <span class="text-red-500">*</span>
      </label>
      <input
        v-model="form.city"
        type="text"
        placeholder="Votre ville"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      />
      <p v-if="errors.city" class="text-red-500 text-sm mt-1">{{ errors.city }}</p>
    </div>

    <!-- Profession (Profession) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Profession <span class="text-red-500">*</span>
      </label>
      <input
        v-model="form.profession"
        type="text"
        placeholder="Votre profession"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      />
      <p v-if="errors.profession" class="text-red-500 text-sm mt-1">{{ errors.profession }}</p>
    </div>

    <!-- Niveau d'études (Education level) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Niveau d'études <span class="text-red-500">*</span>
      </label>
      <select
        v-model="form.educationLevel"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff6b6b] focus:border-transparent outline-none transition"
        required
      >
        <option value="">-- Sélectionner --</option>
        <option value="primary">Primaire</option>
        <option value="secondary">Collège</option>
        <option value="highschool">Lycée</option>
        <option value="bachelor">Licence</option>
        <option value="master">Master</option>
        <option value="doctorate">Doctorat</option>
      </select>
      <p v-if="errors.educationLevel" class="text-red-500 text-sm mt-1">{{ errors.educationLevel }}</p>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
      <p class="text-sm text-gray-700">
        <strong>💡 Conseil:</strong> Ces informations vous aideront à faire de bonnes rencontres. Soyez honnête et complet dans vos réponses.
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
  firstName: props.data.firstName || '',
  lastName: props.data.lastName || '',
  dateOfBirth: props.data.dateOfBirth || '',
  gender: props.data.gender || '',
  nationality: props.data.nationality || '',
  countryOfResidence: props.data.countryOfResidence || '',
  region: props.data.region || '',
  city: props.data.city || '',
  profession: props.data.profession || '',
  educationLevel: props.data.educationLevel || ''
});

const errors = ref({});

const validateForm = () => {
  errors.value = {};
  
  if (!form.value.firstName) errors.value.firstName = 'Le prénom est requis';
  if (!form.value.lastName) errors.value.lastName = 'Le nom est requis';
  if (!form.value.dateOfBirth) errors.value.dateOfBirth = 'La date de naissance est requise';
  if (!form.value.gender) errors.value.gender = 'Le genre est requis';
  if (!form.value.nationality) errors.value.nationality = 'La nationalité est requise';
  if (!form.value.countryOfResidence) errors.value.countryOfResidence = 'Le pays de résidence est requis';
  if (!form.value.region) errors.value.region = 'La région est requise';
  if (!form.value.city) errors.value.city = 'La ville est requise';
  if (!form.value.profession) errors.value.profession = 'La profession est requise';
  if (!form.value.educationLevel) errors.value.educationLevel = 'Le niveau d\'études est requis';
  
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
