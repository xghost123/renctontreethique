<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6">
      <p class="text-sm text-gray-700"><strong>Étape 3:</strong> Décrivez votre apparence physique de manière respectueuse et honnête.</p>
    </div>

    <!-- Taille (Height) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Taille (cm) <span class="text-red-500">*</span>
      </label>
      <select
        v-model="form.height"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A028] focus:border-transparent outline-none transition"
        required
      >
        <option value="">-- Sélectionner --</option>
        <option v-for="h in heightOptions" :key="h" :value="h">{{ h }} cm</option>
      </select>
      <p v-if="errors.height" class="text-red-500 text-sm mt-1">{{ errors.height }}</p>
    </div>

    <!-- Morphologie (Build) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-3">
        Morphologie <span class="text-red-500">*</span>
      </label>
      <div class="space-y-2">
        <label class="flex items-center">
          <input v-model="form.build" type="radio" value="thin" class="w-4 h-4" required />
          <span class="ml-3 text-gray-700">Mince</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.build" type="radio" value="athletic" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Athlétique</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.build" type="radio" value="normal" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Normal</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.build" type="radio" value="overweight" class="w-4 h-4" />
          <span class="ml-3 text-gray-700">Corpulent</span>
        </label>
      </div>
      <p v-if="errors.build" class="text-red-500 text-sm mt-1">{{ errors.build }}</p>
    </div>

    <!-- Couleur de la peau (Skin color) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Couleur de peau <span class="text-red-500">*</span>
      </label>
      <select
        v-model="form.skinColor"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A028] focus:border-transparent outline-none transition"
        required
      >
        <option value="">-- Sélectionner --</option>
        <option value="very_light">Très clair</option>
        <option value="light">Clair</option>
        <option value="medium">Moyen</option>
        <option value="dark">Foncé</option>
        <option value="very_dark">Très foncé</option>
      </select>
      <p v-if="errors.skinColor" class="text-red-500 text-sm mt-1">{{ errors.skinColor }}</p>
    </div>

    <!-- Couleur des yeux (Eye color) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Couleur des yeux <span class="text-red-500">*</span>
      </label>
      <select
        v-model="form.eyeColor"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A028] focus:border-transparent outline-none transition"
        required
      >
        <option value="">-- Sélectionner --</option>
        <option value="black">Noirs</option>
        <option value="brown">Bruns</option>
        <option value="blue">Bleus</option>
        <option value="green">Verts</option>
        <option value="gray">Gris</option>
      </select>
      <p v-if="errors.eyeColor" class="text-red-500 text-sm mt-1">{{ errors.eyeColor }}</p>
    </div>

    <!-- Cheveux (Hair) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Cheveux <span class="text-red-500">*</span>
      </label>
      <select
        v-model="form.hair"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A028] focus:border-transparent outline-none transition"
        required
      >
        <option value="">-- Sélectionner --</option>
        <option value="black">Noirs</option>
        <option value="brown">Bruns</option>
        <option value="blonde">Blonds</option>
        <option value="red">Roux</option>
        <option value="gray">Gris</option>
        <option value="bald">Chauve</option>
      </select>
      <p v-if="errors.hair" class="text-red-500 text-sm mt-1">{{ errors.hair }}</p>
    </div>

    <!-- Particularités (Distinctive features) -->
    <div>
      <label class="block text-sm font-semibold text-[#0D2218] mb-2">
        Particularités (tatouages, cicatrices, etc.)
      </label>
      <textarea
        v-model="form.distinctiveFeatures"
        placeholder="Décrivez les éléments distinctifs..."
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A028] focus:border-transparent outline-none transition"
        rows="3"
      ></textarea>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
      <p class="text-sm text-gray-700">
        <strong>💡 Conseil:</strong> Les descriptions honnêtes et détaillées trouvent de meilleures correspondances. La modestie est importante.
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
  height: props.data.height || '',
  build: props.data.build || '',
  skinColor: props.data.skinColor || '',
  eyeColor: props.data.eyeColor || '',
  hair: props.data.hair || '',
  distinctiveFeatures: props.data.distinctiveFeatures || ''
});

const errors = ref({});
const heightOptions = Array.from({ length: 71 }, (_, i) => 130 + i);

const validateForm = () => {
  errors.value = {};
  
  if (!form.value.height) errors.value.height = 'La taille est requise';
  if (!form.value.build) errors.value.build = 'La morphologie est requise';
  if (!form.value.skinColor) errors.value.skinColor = 'La couleur de peau est requise';
  if (!form.value.eyeColor) errors.value.eyeColor = 'La couleur des yeux est requise';
  if (!form.value.hair) errors.value.hair = 'Décrivez vos cheveux';
  
  return Object.keys(errors.value).length === 0;
};

const handleSubmit = () => {
  if (validateForm()) {
    emit('update', form.value);
  }
};

const emit = defineEmits(['update']);

watch(form, (newVal) => {
  if (Object.keys(errors.value).length === 0) {
    emit('update', newVal);
  }
}, { deep: true });
</script>
