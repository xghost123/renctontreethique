<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue';

defineProps({
    translations: { type: Object, default: () => ({}) },
    locale: { type: String, default: 'fr' },
    locales: { type: Array, default: () => [] },
});

document.body.classList.remove(...document.body.classList);
document.body.classList.add("frontend.inscription");

const currentStep = ref(1);
const totalSteps = 4;

const form = useForm({
    // Step 1
    gender: '',
    commitment: false,
    
    // Step 2
    name: '',
    age: '',
    location: '',
    mosque: '',
    occupation: '',
    education: '',
    bio: '',
    
    // Step 3
    ageMin: 18,
    ageMax: 65,
    locationPreference: '',
    educationPreference: '',
    personalityTraits: [],
    
    // Step 4
    termsAccepted: false,
});

const personalityOptions = [
    { id: 'humorous', label: 'Humoristique' },
    { id: 'serious', label: 'Sérieux' },
    { id: 'artistic', label: 'Artistique' },
    { id: 'sporty', label: 'Sportif' },
    { id: 'intellectual', label: 'Intellectuel' },
    { id: 'spiritual', label: 'Spirituel' },
    { id: 'adventurous', label: 'Aventurier' },
    { id: 'homebody', label: 'Casanier' },
];

const mosques = [
    'Grande Mosquée de Paris',
    'Mosquée de Lyon',
    'Mosquée de Marseille',
    'Mosquée de Toulouse',
    'Mosquée de Lille',
    'Mosquée de Bordeaux',
    'Mosquée de Strasbourg',
    'Mosquée de Montpellier',
    'Mosquée de Nice',
    'Mosquée de Nantes',
];

const educationOptions = [
    'Baccalauréat',
    'License',
    'Master',
    'Doctorat',
    'Formation professionnelle',
];

const progress = computed(() => (currentStep.value / totalSteps) * 100);

const canProceedToNextStep = computed(() => {
    if (currentStep.value === 1) {
        return form.gender && form.commitment;
    } else if (currentStep.value === 2) {
        return form.name && form.age && form.location && form.mosque && form.occupation && form.education && form.bio;
    } else if (currentStep.value === 3) {
        return form.ageMin && form.ageMax && form.locationPreference && form.educationPreference && form.personalityTraits.length > 0;
    } else if (currentStep.value === 4) {
        return form.termsAccepted;
    }
    return true;
});

const nextStep = () => {
    if (canProceedToNextStep.value && currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const previousStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const togglePersonalityTrait = (traitId) => {
    const index = form.personalityTraits.indexOf(traitId);
    if (index > -1) {
        form.personalityTraits.splice(index, 1);
    } else {
        form.personalityTraits.push(traitId);
    }
};

const submit = () => {
    form.post(route('inscription.store'), {
        onFinish: () => form.reset(),
    });
};

const stepTitles = [
    'Genre & Intention',
    'Informations Personnelles',
    'Critères de Recherche',
    'Confirmation',
];
</script>

<template>
    <Head title="Inscription — Rencontre Éthique" />

    <div class="luxury-inscription-wrapper">
        <!-- Animated Background Gradients -->
        <div class="gradient-bg">
            <div class="gradient-orb gradient-orb-1"></div>
            <div class="gradient-orb gradient-orb-2"></div>
            <div class="gradient-orb gradient-orb-3"></div>
        </div>

        <!-- Floating Islamic Geometric Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-star-1"></div>
            <div class="shape shape-star-2"></div>
            <div class="shape shape-crescent-1"></div>
            <div class="shape shape-crescent-2"></div>
            <div class="shape shape-geometric-1"></div>
            <div class="shape shape-geometric-2"></div>
        </div>

        <!-- Main Container -->
        <div class="inscription-container">
            <!-- Header Section -->
            <div class="inscription-header">
                <Link href="/" class="brand-logo-mini">
                    <div class="logo-dome-mini">
                        <svg viewBox="0 0 100 100" class="dome-icon-mini">
                            <defs>
                                <linearGradient id="domeGradientMini" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#ff8787;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path d="M50 15c-15 0-20-5-20-5c0 8 5 15 20 18c15-3 20-10 20-18c0 0-5 5-20 5z" fill="url(#domeGradientMini)" />
                            <ellipse cx="50" cy="50" rx="28" ry="32" fill="url(#domeGradientMini)" opacity="0.9" />
                            <ellipse cx="45" cy="40" rx="12" ry="18" fill="#ffffff" opacity="0.3" />
                            <rect x="30" y="80" width="40" height="12" fill="#0f3a7d" rx="2" />
                            <rect x="22" y="70" width="6" height="20" fill="#17a2b8" rx="1" />
                            <rect x="72" y="70" width="6" height="20" fill="#17a2b8" rx="1" />
                        </svg>
                    </div>
                </Link>
                <div class="header-content">
                    <h1 class="header-title">Étape {{ currentStep }}: {{ stepTitles[currentStep - 1] }}</h1>
                    <div class="progress-section">
                        <div class="progress-bar-container">
                            <div class="progress-bar" :style="{ width: progress + '%' }"></div>
                        </div>
                        <span class="progress-text">{{ currentStep }} / {{ totalSteps }}</span>
                    </div>
                </div>
            </div>

            <!-- Glassmorphism Card -->
            <div class="glass-card inscription-card">
                <!-- Step 1: Genre & Intention -->
                <div v-if="currentStep === 1" class="form-step active">
                    <div class="step-content">
                        <!-- Gender Selection -->
                        <div class="form-section">
                            <label class="section-label">Sélectionnez votre genre</label>
                            <div class="gender-options">
                                <button
                                    type="button"
                                    class="gender-button"
                                    :class="{ active: form.gender === 'femme' }"
                                    @click="form.gender = 'femme'"
                                >
                                    <svg class="gender-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="9" r="4"></circle>
                                        <path d="M12 13v8M8 21h8"></path>
                                        <line x1="9.35" y1="17.5" x2="14.65" y2="17.5"></line>
                                    </svg>
                                    <span class="gender-label">Femme</span>
                                </button>
                                <button
                                    type="button"
                                    class="gender-button"
                                    :class="{ active: form.gender === 'homme' }"
                                    @click="form.gender = 'homme'"
                                >
                                    <svg class="gender-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="6" r="4"></circle>
                                        <path d="M12 10v8M8 18h8"></path>
                                        <line x1="15" y1="13" x2="18" y2="10"></line>
                                    </svg>
                                    <span class="gender-label">Homme</span>
                                </button>
                            </div>
                        </div>

                        <!-- Commitment Checkbox -->
                        <div class="form-section">
                            <label class="checkbox-wrapper">
                                <input
                                    type="checkbox"
                                    v-model="form.commitment"
                                    class="checkbox-input"
                                />
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-label">
                                    Je m'engage dans une démarche respectueuse et saine en vue du mariage uniquement
                                </span>
                            </label>
                        </div>

                        <!-- Islamic Quote -->
                        <div class="islamic-quote">
                            <svg class="quote-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 21c3 0 7-1 7-8V5c0-1.25-4.5-5-7-5m0 18c-1 .5-2.5 1-4 1-1.75 0-3-1-3-3.72s2-7.28 5-7.28c1.5 0 2.5.5 3.5 1.5l-2 2c-1-1-1.5-1.5-3-1.5-1 0-2 .5-2 1.972V20c0 1 0 2 1 2z" />
                            </svg>
                            <p class="quote-text">La mariage est la moitié de la foi</p>
                            <p class="quote-source">— Hadith</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Information Personnelle -->
                <div v-else-if="currentStep === 2" class="form-step active">
                    <div class="step-content">
                        <!-- Name and Age Row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Prénom</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="luxury-input"
                                    placeholder="Votre prénom"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Âge</label>
                                <input
                                    v-model="form.age"
                                    type="number"
                                    class="luxury-input"
                                    placeholder="Votre âge"
                                    min="18"
                                    max="120"
                                />
                            </div>
                        </div>

                        <!-- Location and Mosque Row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Ville/Région</label>
                                <input
                                    v-model="form.location"
                                    type="text"
                                    class="luxury-input"
                                    placeholder="Votre localisation"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mosquée de référence</label>
                                <select v-model="form.mosque" class="luxury-input select-input">
                                    <option value="">Sélectionnez une mosquée</option>
                                    <option v-for="mosque in mosques" :key="mosque" :value="mosque">
                                        {{ mosque }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Occupation and Education Row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Profession</label>
                                <input
                                    v-model="form.occupation"
                                    type="text"
                                    class="luxury-input"
                                    placeholder="Votre profession"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Niveau d'étude</label>
                                <select v-model="form.education" class="luxury-input select-input">
                                    <option value="">Sélectionnez un niveau</option>
                                    <option v-for="edu in educationOptions" :key="edu" :value="edu">
                                        {{ edu }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="form-group full-width">
                            <label class="form-label">À propos de vous</label>
                            <textarea
                                v-model="form.bio"
                                class="luxury-textarea"
                                placeholder="Décrivez-vous brièvement..."
                                rows="4"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Critères de Recherche -->
                <div v-else-if="currentStep === 3" class="form-step active">
                    <div class="step-content">
                        <!-- Age Range -->
                        <div class="form-section">
                            <label class="section-label">Préférence d'âge</label>
                            <div class="age-range-container">
                                <div class="age-input-group">
                                    <label class="mini-label">De</label>
                                    <input
                                        v-model.number="form.ageMin"
                                        type="number"
                                        class="luxury-input small-input"
                                        min="18"
                                        max="120"
                                    />
                                </div>
                                <div class="age-input-group">
                                    <label class="mini-label">À</label>
                                    <input
                                        v-model.number="form.ageMax"
                                        type="number"
                                        class="luxury-input small-input"
                                        min="18"
                                        max="120"
                                    />
                                </div>
                                <div class="age-display">{{ form.ageMin }} - {{ form.ageMax }} ans</div>
                            </div>
                        </div>

                        <!-- Location Preference -->
                        <div class="form-group">
                            <label class="form-label">Préférence géographique</label>
                            <select v-model="form.locationPreference" class="luxury-input select-input">
                                <option value="">Sélectionnez une région</option>
                                <option value="ile-de-france">Île-de-France</option>
                                <option value="rhone-alpes">Rhône-Alpes</option>
                                <option value="paca">Provence-Alpes-Côte d'Azur</option>
                                <option value="occitanie">Occitanie</option>
                                <option value="auvergne">Auvergne-Rhône-Alpes</option>
                                <option value="pays-de-la-loire">Pays de la Loire</option>
                                <option value="nouvelle-aquitaine">Nouvelle-Aquitaine</option>
                                <option value="bretagne">Bretagne</option>
                                <option value="bourgogne">Bourgogne-Franche-Comté</option>
                                <option value="alsace">Alsace</option>
                            </select>
                        </div>

                        <!-- Education Preference -->
                        <div class="form-group">
                            <label class="form-label">Niveau d'étude recherché</label>
                            <select v-model="form.educationPreference" class="luxury-input select-input">
                                <option value="">Sélectionnez un niveau</option>
                                <option v-for="edu in educationOptions" :key="edu" :value="edu">
                                    {{ edu }}
                                </option>
                            </select>
                        </div>

                        <!-- Personality Traits -->
                        <div class="form-section">
                            <label class="section-label">Traits de personnalité recherchés</label>
                            <div class="traits-grid">
                                <button
                                    v-for="trait in personalityOptions"
                                    :key="trait.id"
                                    type="button"
                                    class="trait-button"
                                    :class="{ active: form.personalityTraits.includes(trait.id) }"
                                    @click="togglePersonalityTrait(trait.id)"
                                >
                                    {{ trait.label }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Confirmation & Engagement -->
                <div v-else-if="currentStep === 4" class="form-step active">
                    <div class="step-content">
                        <!-- Profile Summary -->
                        <div class="summary-section">
                            <h3 class="summary-title">Résumé de votre profil</h3>
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <span class="summary-label">Genre</span>
                                    <span class="summary-value">{{ form.gender === 'femme' ? 'Femme' : 'Homme' }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Nom</span>
                                    <span class="summary-value">{{ form.name }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Âge</span>
                                    <span class="summary-value">{{ form.age }} ans</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Localisation</span>
                                    <span class="summary-value">{{ form.location }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Mosquée</span>
                                    <span class="summary-value">{{ form.mosque }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Profession</span>
                                    <span class="summary-value">{{ form.occupation }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Études</span>
                                    <span class="summary-value">{{ form.education }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Fourchette d'âge</span>
                                    <span class="summary-value">{{ form.ageMin }} - {{ form.ageMax }} ans</span>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Acceptance -->
                        <div class="form-section terms-section">
                            <label class="checkbox-wrapper large-checkbox">
                                <input
                                    type="checkbox"
                                    v-model="form.termsAccepted"
                                    class="checkbox-input"
                                />
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-label">
                                    J'accepte les conditions d'utilisation et la politique de confidentialité de Rencontre Éthique
                                </span>
                            </label>
                        </div>

                        <!-- Final Islamic Message -->
                        <div class="islamic-quote final-quote">
                            <svg class="quote-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                            <p class="quote-text">Que le Seigneur bénisse votre démarche sincère</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation">
                    <button
                        type="button"
                        class="nav-button back-button"
                        @click="previousStep"
                        :disabled="currentStep === 1"
                    >
                        ← Précédent
                    </button>

                    <button
                        v-if="currentStep < totalSteps"
                        type="button"
                        class="nav-button next-button"
                        @click="nextStep"
                        :disabled="!canProceedToNextStep"
                    >
                        Suivant →
                    </button>

                    <button
                        v-else
                        type="button"
                        class="submit-button inscription-submit"
                        @click="submit"
                        :disabled="!canProceedToNextStep || form.processing"
                    >
                        <span class="button-content">
                            <span v-if="!form.processing">Procéder à l'Inscription</span>
                            <span v-else class="button-spinner">⏳</span>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Footer Accent -->
            <div class="card-footer-accent"></div>
        </div>
    </div>
</template>

<style scoped>
/* ===== CSS VARIABLES ===== */
:root {
    --primary: #0f3a7d;
    --secondary: #17a2b8;
    --success: #28a745;
    --accent: #ff6b6b;
    --light: #f8f9fa;
    --white: #ffffff;
    --dark: #1a1a2e;
    --light-text: #6c757d;
    --border: rgba(0, 0, 0, 0.08);
    --glass-bg: rgba(255, 255, 255, 0.95);
    --glass-border: rgba(255, 255, 255, 0.5);
}

/* ===== WRAPPER & BACKGROUND ===== */
.luxury-inscription-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    position: relative;
    overflow: hidden;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
}

/* Gradient Background Orbs */
.gradient-bg {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    z-index: -2;
}

.gradient-orb {
    position: absolute;
    filter: blur(60px);
    opacity: 0.7;
    animation: float 15s ease-in-out infinite;
}

.gradient-orb-1 {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(15, 58, 125, 0.3) 0%, transparent 70%);
    top: -100px;
    left: -100px;
    animation-delay: 0s;
}

.gradient-orb-2 {
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255, 107, 107, 0.2) 0%, transparent 70%);
    top: 50%;
    right: -150px;
    animation-delay: -5s;
}

.gradient-orb-3 {
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(23, 162, 184, 0.15) 0%, transparent 70%);
    bottom: -50px;
    left: 20%;
    animation-delay: -10s;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, -30px) rotate(120deg); }
    66% { transform: translate(-20px, 20px) rotate(240deg); }
}

/* Floating Islamic Shapes */
.floating-shapes {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    z-index: -1;
}

.shape {
    position: absolute;
    opacity: 0.08;
    animation: float 20s ease-in-out infinite;
}

.shape-star-1 {
    width: 100px;
    height: 100px;
    top: 10%;
    right: 5%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="50,10 61,40 93,40 67,60 78,90 50,70 22,90 33,60 7,40 39,40" fill="%230f3a7d"/></svg>');
    animation-delay: 0s;
}

.shape-star-2 {
    width: 80px;
    height: 80px;
    bottom: 15%;
    left: 10%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="50,10 61,40 93,40 67,60 78,90 50,70 22,90 33,60 7,40 39,40" fill="%230f3a7d"/></svg>');
    animation-delay: -3s;
}

.shape-crescent-1 {
    width: 120px;
    height: 120px;
    top: 30%;
    left: 5%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="%230f3a7d"/><circle cx="55" cy="50" r="45" fill="white"/></svg>');
    animation-delay: -5s;
}

.shape-crescent-2 {
    width: 90px;
    height: 90px;
    bottom: 25%;
    right: 8%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="%230f3a7d"/><circle cx="55" cy="50" r="45" fill="white"/></svg>');
    animation-delay: -8s;
}

.shape-geometric-1 {
    width: 110px;
    height: 110px;
    top: 60%;
    right: 15%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect x="10" y="10" width="30" height="30" fill="%230f3a7d" transform="rotate(45 25 25)"/><rect x="50" y="50" width="30" height="30" fill="%230f3a7d" transform="rotate(45 65 65)"/></svg>');
    animation-delay: -12s;
}

.shape-geometric-2 {
    width: 85px;
    height: 85px;
    top: 20%;
    left: 50%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect x="10" y="10" width="30" height="30" fill="%230f3a7d" transform="rotate(45 25 25)"/><rect x="50" y="50" width="30" height="30" fill="%230f3a7d" transform="rotate(45 65 65)"/></svg>');
    animation-delay: -15s;
}

/* ===== INSCRIPTION CONTAINER ===== */
.inscription-container {
    width: 100%;
    max-width: 800px;
    position: relative;
    z-index: 10;
}

/* ===== HEADER SECTION ===== */
.inscription-header {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 32px;
    animation: slideDown 0.6s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.brand-logo-mini {
    flex-shrink: 0;
}

.logo-dome-mini {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.8));
    backdrop-filter: blur(10px);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 32px rgba(15, 58, 125, 0.1);
    transition: all 0.3s ease;
}

.brand-logo-mini:hover .logo-dome-mini {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(15, 58, 125, 0.2);
}

.dome-icon-mini {
    width: 50px;
    height: 50px;
}

.header-content {
    flex: 1;
}

.header-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
    margin: 0 0 16px 0;
    letter-spacing: -0.5px;
}

.progress-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress-bar-container {
    flex: 1;
    height: 6px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 3px;
    transition: width 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 0 10px rgba(15, 58, 125, 0.3);
}

.progress-text {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary);
    min-width: 50px;
}

/* ===== GLASS CARD ===== */
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(30px);
    border: 2px solid var(--glass-border);
    border-radius: 30px;
    padding: 48px;
    box-shadow:
        0 20px 60px rgba(15, 58, 125, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    position: relative;
    overflow: hidden;
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.inscription-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
}

/* ===== FORM STEPS ===== */
.form-step {
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
    pointer-events: none;
}

.form-step.active {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
}

.step-content {
    display: flex;
    flex-direction: column;
    gap: 28px;
    min-height: 300px;
}

/* ===== FORM SECTIONS ===== */
.form-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.section-label {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ===== GENDER SELECTION ===== */
.gender-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.gender-button {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 24px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.3));
    border: 2px solid rgba(15, 58, 125, 0.2);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    font-weight: 600;
    color: var(--primary);
}

.gender-button:hover {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
    border-color: var(--secondary);
    transform: translateY(-2px);
}

.gender-button.active {
    background: linear-gradient(135deg, var(--secondary), #0d96a6);
    border-color: var(--secondary);
    color: var(--white);
    box-shadow: 0 12px 24px rgba(23, 162, 184, 0.3);
}

.gender-icon {
    width: 40px;
    height: 40px;
    stroke: currentColor;
}

.gender-label {
    font-size: 16px;
    font-weight: 600;
}

/* ===== CHECKBOX STYLES ===== */
.checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    user-select: none;
}

.checkbox-wrapper.large-checkbox {
    gap: 16px;
}

.checkbox-input {
    appearance: none;
    width: 0;
    height: 0;
    opacity: 0;
    cursor: pointer;
}

.checkbox-custom {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    background: rgba(255, 255, 255, 0.5);
    border: 2px solid var(--primary);
    border-radius: 6px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
}

.checkbox-input:checked ~ .checkbox-custom {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(15, 58, 125, 0.3);
}

.checkbox-custom::after {
    content: '✓';
    color: var(--white);
    font-size: 16px;
    font-weight: 700;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.checkbox-input:checked ~ .checkbox-custom::after {
    opacity: 1;
}

.checkbox-label {
    font-size: 15px;
    color: var(--dark);
    line-height: 1.5;
    flex: 1;
}

.checkbox-wrapper.large-checkbox .checkbox-label {
    font-size: 16px;
    font-weight: 500;
}

/* ===== FORM FIELDS ===== */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-row.full-width {
    grid-template-columns: 1fr;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    font-size: 14px;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.luxury-input,
.luxury-textarea {
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.6);
    border: 2px solid rgba(15, 58, 125, 0.15);
    border-radius: 12px;
    font-size: 15px;
    color: var(--dark);
    font-family: inherit;
    transition: all 0.3s ease;
}

.luxury-input::placeholder,
.luxury-textarea::placeholder {
    color: var(--light-text);
}

.luxury-input:focus,
.luxury-textarea:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.95);
    border-color: var(--secondary);
    box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
}

.select-input {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%230f3a7d' d='M1 4l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
    cursor: pointer;
}

.small-input {
    width: 100%;
}

.age-range-container {
    display: flex;
    align-items: flex-end;
    gap: 12px;
}

.age-input-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
}

.mini-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--light-text);
    text-transform: uppercase;
}

.age-display {
    padding: 8px 16px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--white);
    border-radius: 8px;
    font-weight: 700;
    white-space: nowrap;
}

.luxury-textarea {
    resize: vertical;
    min-height: 100px;
    padding: 12px 16px;
}

/* ===== PERSONALITY TRAITS ===== */
.traits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
}

.trait-button {
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.5);
    border: 2px solid rgba(15, 58, 125, 0.2);
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.trait-button:hover {
    background: rgba(255, 255, 255, 0.8);
    border-color: var(--secondary);
    transform: translateY(-2px);
}

.trait-button.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-color: var(--secondary);
    color: var(--white);
    box-shadow: 0 8px 16px rgba(23, 162, 184, 0.3);
}

/* ===== SUMMARY SECTION ===== */
.summary-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 24px;
    background: linear-gradient(135deg, rgba(15, 58, 125, 0.05), rgba(23, 162, 184, 0.05));
    border-radius: 16px;
    border: 1px solid rgba(15, 58, 125, 0.1);
}

.summary-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.summary-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--light-text);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.summary-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--primary);
}

.terms-section {
    padding: 20px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 12px;
    border: 1px dashed rgba(15, 58, 125, 0.2);
}

/* ===== ISLAMIC QUOTE ===== */
.islamic-quote {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(23, 162, 184, 0.1));
    border-radius: 16px;
    border-left: 4px solid var(--accent);
}

.quote-icon {
    width: 28px;
    height: 28px;
    color: var(--accent);
    flex-shrink: 0;
}

.quote-text {
    font-size: 15px;
    font-weight: 600;
    color: var(--primary);
    margin: 0;
    line-height: 1.5;
}

.quote-source {
    font-size: 12px;
    color: var(--light-text);
    margin: 4px 0 0 0;
    font-style: italic;
}

.islamic-quote.final-quote {
    flex-direction: column;
    text-align: center;
    border-left: none;
    border-top: 4px solid var(--success);
}

.islamic-quote.final-quote .quote-icon {
    width: 40px;
    height: 40px;
    color: var(--success);
}

/* ===== NAVIGATION BUTTONS ===== */
.form-navigation {
    display: flex;
    gap: 16px;
    margin-top: 32px;
    padding-top: 28px;
    border-top: 1px solid rgba(15, 58, 125, 0.1);
}

.nav-button,
.submit-button {
    flex: 1;
    padding: 14px 24px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.back-button {
    background: rgba(255, 255, 255, 0.5);
    border: 2px solid rgba(15, 58, 125, 0.2);
    color: var(--primary);
}

.back-button:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.8);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.back-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.next-button {
    background: linear-gradient(135deg, var(--secondary), #0d96a6);
    color: var(--white);
    box-shadow: 0 10px 25px rgba(23, 162, 184, 0.3);
}

.next-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(23, 162, 184, 0.4);
}

.next-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.submit-button.inscription-submit {
    background: linear-gradient(135deg, var(--primary), #0d2a5f);
    color: var(--white);
    box-shadow: 0 10px 30px rgba(15, 58, 125, 0.4);
    position: relative;
    overflow: hidden;
}

.submit-button.inscription-submit::before {
    content: '';
    position: absolute;
    inset: -50%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    animation: shimmer 3s infinite;
}

.submit-button.inscription-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 15px 40px rgba(15, 58, 125, 0.5);
}

.submit-button.inscription-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.button-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    position: relative;
    z-index: 1;
}

.button-spinner {
    display: inline-block;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* ===== CARD FOOTER ACCENT ===== */
.card-footer-accent {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: linear-gradient(to top, rgba(23, 162, 184, 0.05), transparent);
    border-radius: 0 0 30px 30px;
    pointer-events: none;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .luxury-inscription-wrapper {
        padding: 16px;
    }

    .inscription-container {
        max-width: 100%;
    }

    .inscription-header {
        flex-direction: column;
        gap: 16px;
        margin-bottom: 24px;
    }

    .logo-dome-mini {
        width: 70px;
        height: 70px;
    }

    .dome-icon-mini {
        width: 42px;
        height: 42px;
    }

    .header-title {
        font-size: 24px;
    }

    .glass-card {
        padding: 32px 24px;
        border-radius: 24px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .gender-options {
        grid-template-columns: 1fr;
    }

    .traits-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .form-navigation {
        flex-direction: column;
        gap: 12px;
    }

    .nav-button,
    .submit-button {
        width: 100%;
    }
}

@media (max-width: 640px) {
    .inscription-header {
        gap: 12px;
    }

    .header-title {
        font-size: 20px;
    }

    .glass-card {
        padding: 24px 16px;
    }

    .step-content {
        gap: 20px;
        min-height: 250px;
    }

    .traits-grid {
        grid-template-columns: 1fr;
    }

    .islamic-quote {
        gap: 12px;
        padding: 16px;
    }

    .quote-text {
        font-size: 14px;
    }
}
</style>
