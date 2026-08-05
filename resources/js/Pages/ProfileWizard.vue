<template>
  <div class="min-h-screen bg-gradient-to-br from-[#0D2218] to-[#1C4532] py-12 px-4">
    <div class="container mx-auto max-w-4xl">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-2">Complétez votre profil</h1>
        <p class="text-gray-300 text-lg">Temps estimé: <strong>10 à 20 minutes</strong></p>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
          <span class="text-white font-semibold">Étape {{ currentStep }} sur {{ totalSteps }}</span>
          <span class="text-[#C8A028] font-bold">{{ Math.round((currentStep / totalSteps) * 100) }}%</span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-3">
          <div 
            class="bg-[#C8A028] h-3 rounded-full transition-all duration-500"
            :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
          ></div>
        </div>
      </div>

      <!-- Step Indicators -->
      <div class="flex justify-between mb-12 overflow-x-auto pb-4">
        <button
          v-for="step in totalSteps"
          :key="step"
          @click="goToStep(step)"
          :disabled="step > currentStep"
          :class="[
            'flex-shrink-0 w-12 h-12 rounded-full font-bold transition-all',
            step === currentStep 
              ? 'bg-[#C8A028] text-[#0D2218] shadow-lg' 
              : step < currentStep 
                ? 'bg-green-600 text-white hover:bg-green-700' 
                : 'bg-gray-600 text-gray-300 cursor-not-allowed'
          ]"
        >
          {{ step < currentStep ? '✓' : step }}
        </button>
      </div>

      <!-- Form Container -->
      <div class="bg-white rounded-lg shadow-2xl p-8 md:p-12">
        <!-- Step Title & Description -->
        <div class="mb-8">
          <h2 class="text-3xl font-bold text-[#0D2218] mb-2">{{ stepTitle }}</h2>
          <p class="text-gray-600">{{ stepDescription }}</p>
        </div>

        <!-- Form Content -->
        <component 
          :is="currentStepComponent"
          :data="formData[currentStep - 1]"
          @update="updateStepData"
        />

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-12 pt-8 border-t border-gray-200">
          <button
            @click="previousStep"
            :disabled="currentStep === 1"
            :class="[
              'px-6 py-3 rounded-lg font-semibold transition',
              currentStep === 1 
                ? 'bg-gray-200 text-gray-400 cursor-not-allowed' 
                : 'bg-gray-200 text-[#0D2218] hover:bg-gray-300'
            ]"
          >
            ← Précédent
          </button>

          <div v-if="saveStatus" class="text-green-600 font-semibold flex items-center">
            ✓ {{ saveStatus }}
          </div>

          <button
            @click="nextStep"
            :disabled="!isStepValid || isLoading"
            :class="[
              'px-6 py-3 rounded-lg font-semibold transition',
              isStepValid && !isLoading
                ? 'bg-[#C8A028] text-[#0D2218] hover:bg-[#D4B44D]' 
                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
            ]"
          >
            {{ currentStep === totalSteps ? 'Terminer' : 'Suivant' }} →
          </button>
        </div>
      </div>

      <!-- Auto-save Draft Indicator -->
      <div class="mt-6 text-center text-gray-300 text-sm">
        <span v-if="isSaving">💾 Sauvegarde en cours...</span>
        <span v-else-if="lastSaved">Dernière sauvegarde: {{ lastSaved }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

// Import step components
import Step1GeneralInfo from './Steps/Step1GeneralInfo.vue';
import Step2PersonalSituation from './Steps/Step2PersonalSituation.vue';
import Step3PhysicalDescription from './Steps/Step3PhysicalDescription.vue';
import Step4Personality from './Steps/Step4Personality.vue';
import Step5FamilyProject from './Steps/Step5FamilyProject.vue';
import Step6ImportantCriteria from './Steps/Step6ImportantCriteria.vue';
import Step7MarriageVision from './Steps/Step7MarriageVision.vue';
import Step8Presentation from './Steps/Step8Presentation.vue';

const props = defineProps({
  initialStep: {
    type: Number,
    default: 1
  },
  draftData: {
    type: Object,
    default: () => ({})
  }
});

const currentStep = ref(props.initialStep);
const totalSteps = 8;
const isLoading = ref(false);
const isSaving = ref(false);
const saveStatus = ref('');
const lastSaved = ref('');

const stepTitles = [
  'Informations générales',
  'Situation personnelle',
  'Description physique',
  'Personnalité',
  'Projet familial',
  'Critères importants',
  'Vision du mariage',
  'Mot de présentation'
];

const stepDescriptions = [
  'Commençons par les informations de base vous concernant',
  'Parlez-nous de votre situation personnelle',
  'Décrivez votre apparence physique',
  'Présentez votre personnalité et vos qualités',
  'Exprimez votre vision familiale',
  'Définissez vos critères importants',
  'Partagez votre vision du mariage',
  'Présentez-vous en quelques mots'
];

const stepComponents = [
  Step1GeneralInfo,
  Step2PersonalSituation,
  Step3PhysicalDescription,
  Step4Personality,
  Step5FamilyProject,
  Step6ImportantCriteria,
  Step7MarriageVision,
  Step8Presentation
];

const formData = ref(Array(totalSteps).fill({}));
const validationErrors = ref({});

const stepTitle = computed(() => stepTitles[currentStep.value - 1]);
const stepDescription = computed(() => stepDescriptions[currentStep.value - 1]);
const currentStepComponent = computed(() => stepComponents[currentStep.value - 1]);
const isStepValid = computed(() => !validationErrors.value[currentStep.value]);

onMounted(() => {
  if (props.draftData && Object.keys(props.draftData).length > 0) {
    formData.value = props.draftData;
  }
});

const updateStepData = (data) => {
  formData.value[currentStep.value - 1] = data;
  autoSave();
};

const autoSave = async () => {
  isSaving.value = true;
  try {
    const response = await axios.post('/api/biodata/step/' + currentStep.value, {
      step: currentStep.value,
      data: formData.value[currentStep.value - 1]
    });
    
    if (response.data.success) {
      lastSaved.value = new Date().toLocaleTimeString('fr-FR');
      saveStatus.value = 'Données sauvegardées';
      setTimeout(() => { saveStatus.value = ''; }, 3000);
    }
  } catch (error) {
    console.error('Auto-save failed:', error);
  } finally {
    isSaving.value = false;
  }
};

const nextStep = async () => {
  if (currentStep.value < totalSteps) {
    currentStep.value++;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } else {
    await completeWizard();
  }
};

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

const goToStep = (step) => {
  if (step <= currentStep.value) {
    currentStep.value = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

const completeWizard = async () => {
  isLoading.value = true;
  try {
    const response = await axios.post('/api/biodata/complete', {
      allData: formData.value
    });
    
    if (response.data.success) {
      router.visit('/wizard/complete');
    }
  } catch (error) {
    console.error('Error completing wizard:', error);
  } finally {
    isLoading.value = false;
  }
};

watch(currentStep, () => {
  // Update URL or breadcrumb if needed
});
</script>
