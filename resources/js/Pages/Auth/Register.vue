<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

defineProps({
    translations: { type: Object, default: () => ({}) },
    locale: { type: String, default: 'fr' },
    locales: { type: Array, default: () => [] },
    canLogin: { type: Boolean, default: true },
});

document.body.classList.remove(...document.body.classList);
document.body.classList.add("frontend.register");

// STATE MANAGEMENT
const currentStep = ref(1);
const showPassword = ref(false);
const showPasswordConfirm = ref(false);
const isSubmitting = ref(false);
const animatingOut = ref(false);

// FORM STATE
const form = useForm({
    gender: '',
    email: '',
    password: '',
    password_confirmation: '',
    name: '',
    mobile: '',
    agree_terms: false,
    agree_privacy: false
});

// COMPUTED
const totalSteps = 4;
const progressPercent = computed(() => (currentStep.value / totalSteps) * 100);
const canProceedToNextStep = computed(() => {
    switch (currentStep.value) {
        case 1: return form.gender.length > 0;
        case 2: return form.email.length > 0 && isValidEmail(form.email) && form.mobile.length === 11 && /^01[0-9]{9}$/.test(form.mobile);
        case 3: return form.password.length >= 8 && form.password === form.password_confirmation;
        case 4: return form.agree_terms && form.agree_privacy;
        default: return false;
    }
});

// VALIDATION
const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

// STEP NAVIGATION
const nextStep = () => {
    if (canProceedToNextStep.value && currentStep.value < totalSteps) {
        animatingOut.value = true;
        setTimeout(() => {
            currentStep.value++;
            animatingOut.value = false;
        }, 300);
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        animatingOut.value = true;
        setTimeout(() => {
            currentStep.value--;
            animatingOut.value = false;
        }, 300);
    }
};

// SUBMIT
const submit = async () => {
    if (canProceedToNextStep.value) {
        isSubmitting.value = true;
        await form.post(route('register'), {
            onSuccess: () => {
                isSubmitting.value = false;
            },
            onError: () => {
                isSubmitting.value = false;
            }
        });
    }
};

// LIFECYCLE
onMounted(() => {
    // Add fade-in animation on mount
    document.documentElement.style.opacity = '1';
});
</script>

<template>
    <Head title="Inscription — Rencontre Éthique" />

    <div class="luxury-register-shell">
        <!-- ANIMATED BACKGROUND PATTERNS -->
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>
        
        <!-- ISLAMIC GEOMETRIC PATTERNS -->
        <svg class="geometric-pattern geometric-tl" viewBox="0 0 200 200" preserveAspectRatio="none">
            <defs>
                <pattern id="islamic-grid" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0,20 Q10,10 20,20 Q10,30 0,20" fill="none" stroke="rgba(15, 58, 125, 0.08)" stroke-width="0.5"/>
                    <circle cx="20" cy="20" r="2" fill="rgba(15, 58, 125, 0.12)"/>
                    <path d="M40,0 L0,40" stroke="rgba(15, 58, 125, 0.06)" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="200" height="200" fill="url(#islamic-grid)"/>
        </svg>

        <svg class="geometric-pattern geometric-br" viewBox="0 0 200 200" preserveAspectRatio="none">
            <defs>
                <pattern id="islamic-grid-2" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0,20 Q10,10 20,20 Q10,30 0,20" fill="none" stroke="rgba(255, 107, 107, 0.08)" stroke-width="0.5"/>
                    <circle cx="20" cy="20" r="2" fill="rgba(255, 107, 107, 0.12)"/>
                    <path d="M40,0 L0,40" stroke="rgba(255, 107, 107, 0.06)" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="200" height="200" fill="url(#islamic-grid-2)"/>
        </svg>

        <!-- CRESCENT MOON ACCENT -->
        <svg class="crescent-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>

        <!-- MAIN CONTENT -->
        <div class="luxury-content">
            <!-- HEADER -->
            <div class="luxury-header">
                <Link href="/" class="luxury-logo">
                    <div class="logo-container">
                        <svg class="logo-mosque" viewBox="0 0 24 24" fill="currentColor">
                            <!-- Mosque dome -->
                            <path d="M12 2C10.34 2 9 3.34 9 5v2h6V5c0-1.66-1.34-3-3-3z"/>
                            <path d="M12 2c2.21 0 4 1.79 4 4v1h3v2h-1v10h-2v-4h-4v4H9V9H8V7h3V6c0-2.21 1.79-4 4-4z"/>
                            <!-- Crescent above -->
                            <path d="M12 1c.83 0 1.5-.67 1.5-1.5S12.83-1 12-1s-1.5.67-1.5 1.5S11.17 1 12 1z" opacity="0.6"/>
                        </svg>
                    </div>
                    <div class="logo-text">
                        <div class="logo-title">Rencontre Éthique</div>
                        <div class="logo-subtitle">حلال · مسجد · جدية</div>
                    </div>
                </Link>
            </div>

            <!-- PROGRESS BAR -->
            <div class="progress-section">
                <div class="progress-bar-container">
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" :style="{ width: progressPercent + '%' }"></div>
                    </div>
                </div>
                <div class="progress-steps">
                    <div 
                        v-for="step in totalSteps" 
                        :key="step"
                        class="progress-step"
                        :class="{ 
                            active: currentStep === step,
                            completed: step < currentStep
                        }"
                    >
                        <div class="step-circle">
                            <svg v-if="step < currentStep" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                            </svg>
                            <span v-else>{{ step }}</span>
                        </div>
                        <div class="step-label">
                            {{ ['Genre', 'Email', 'Sécurité', 'Accord'][step - 1] }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- LUXURY CARD -->
            <div class="luxury-card">
                <!-- STEP 1: GENDER SELECTION -->
                <div v-if="currentStep === 1" class="step-content" :class="{ 'animating-out': animatingOut }">
                    <div class="step-header">
                        <h2 class="step-title">Qui êtes-vous?</h2>
                        <p class="step-description">Commençons par vérifier votre genre</p>
                    </div>

                    <div class="gender-selector">
                        <div 
                            v-for="option in [{ value: 'male', label: 'Homme', icon: '👨' }, { value: 'female', label: 'Femme', icon: '👩' }]"
                            :key="option.value"
                            class="gender-option"
                            :class="{ selected: form.gender === option.value }"
                            @click="form.gender = option.value"
                        >
                            <div class="gender-icon">{{ option.icon }}</div>
                            <div class="gender-label">{{ option.label }}</div>
                        </div>
                    </div>

                    <div v-if="form.errors.gender" class="error-message">
                        {{ form.errors.gender }}
                    </div>
                </div>

                <!-- STEP 2: EMAIL & MOBILE -->
                <div v-if="currentStep === 2" class="step-content" :class="{ 'animating-out': animatingOut }">
                    <div class="step-header">
                        <h2 class="step-title">Vos coordonnées</h2>
                        <p class="step-description">Email et numéro de téléphone</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input 
                                id="email"
                                v-model="form.email" 
                                type="email" 
                                placeholder="votre@email.com" 
                                class="luxury-input"
                                autocomplete="email"
                            />
                        </div>
                        <div v-if="form.email.length > 0" class="email-validation">
                            <svg v-if="isValidEmail(form.email)" viewBox="0 0 24 24" fill="currentColor" class="validation-icon valid">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                            </svg>
                            <svg v-else viewBox="0 0 24 24" fill="currentColor" class="validation-icon invalid">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                        </div>
                        <span v-if="form.errors.email" class="field-error">{{ form.errors.email }}</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="mobile">Téléphone (01XXXXXXXXX)</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <input 
                                id="mobile"
                                v-model="form.mobile" 
                                type="tel" 
                                placeholder="01XXXXXXXXX" 
                                maxlength="11"
                                class="luxury-input"
                                autocomplete="tel"
                            />
                        </div>
                        <div v-if="form.mobile.length > 0" class="mobile-validation">
                            <svg v-if="form.mobile.length === 11 && /^01[0-9]{9}$/.test(form.mobile)" viewBox="0 0 24 24" fill="currentColor" class="validation-icon valid">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                            </svg>
                            <svg v-else viewBox="0 0 24 24" fill="currentColor" class="validation-icon invalid">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                        </div>
                        <span v-if="form.errors.mobile" class="field-error">{{ form.errors.mobile }}</span>
                    </div>
                </div>

                <!-- STEP 3: PASSWORD -->
                <div v-if="currentStep === 3" class="step-content" :class="{ 'animating-out': animatingOut }">
                    <div class="step-header">
                        <h2 class="step-title">Créez un mot de passe</h2>
                        <p class="step-description">Au minimum 8 caractères pour votre sécurité</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Mot de passe</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input 
                                id="password"
                                v-model="form.password" 
                                :type="showPassword ? 'text' : 'password'" 
                                placeholder="••••••••" 
                                class="luxury-input"
                                autocomplete="new-password"
                            />
                            <button 
                                type="button" 
                                class="input-toggle"
                                @click="showPassword = !showPassword"
                            >
                                <svg v-if="!showPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div 
                                    class="strength-fill" 
                                    :style="{ 
                                        width: (form.password.length / 16 * 100) + '%',
                                        backgroundColor: form.password.length < 8 ? '#ff6b6b' : form.password.length < 12 ? '#ffc93c' : '#00d4aa'
                                    }"
                                ></div>
                            </div>
                            <span class="strength-text">
                                {{ form.password.length === 0 ? 'Entrez votre mot de passe' : form.password.length < 8 ? 'Faible' : form.password.length < 12 ? 'Moyen' : 'Fort' }}
                            </span>
                        </div>
                        <span v-if="form.errors.password" class="field-error">{{ form.errors.password }}</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input 
                                id="password_confirmation"
                                v-model="form.password_confirmation" 
                                :type="showPasswordConfirm ? 'text' : 'password'" 
                                placeholder="••••••••" 
                                class="luxury-input"
                                autocomplete="new-password"
                            />
                            <button 
                                type="button" 
                                class="input-toggle"
                                @click="showPasswordConfirm = !showPasswordConfirm"
                            >
                                <svg v-if="!showPasswordConfirm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                        <div v-if="form.password.length > 0 && form.password_confirmation.length > 0" class="password-match">
                            <svg v-if="form.password === form.password_confirmation" viewBox="0 0 24 24" fill="currentColor" class="match-icon valid">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                            </svg>
                            <svg v-else viewBox="0 0 24 24" fill="currentColor" class="match-icon invalid">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                            <span class="match-text">
                                {{ form.password === form.password_confirmation ? 'Les mots de passe correspondent' : 'Les mots de passe ne correspondent pas' }}
                            </span>
                        </div>
                        <span v-if="form.errors.password_confirmation" class="field-error">{{ form.errors.password_confirmation }}</span>
                    </div>
                </div>

                <!-- STEP 4: AGREEMENT -->
                <div v-if="currentStep === 4" class="step-content" :class="{ 'animating-out': animatingOut }">
                    <div class="step-header">
                        <h2 class="step-title">Presque là!</h2>
                        <p class="step-description">Veuillez accepter nos conditions</p>
                    </div>

                    <div class="agreement-section">
                        <div class="checkbox-group">
                            <input 
                                id="terms"
                                v-model="form.agree_terms" 
                                type="checkbox" 
                                class="luxury-checkbox"
                            />
                            <label for="terms" class="checkbox-label">
                                J'accepte les <a href="#" class="agreement-link">Conditions d'utilisation</a>
                            </label>
                        </div>

                        <div class="checkbox-group">
                            <input 
                                id="privacy"
                                v-model="form.agree_privacy" 
                                type="checkbox" 
                                class="luxury-checkbox"
                            />
                            <label for="privacy" class="checkbox-label">
                                J'accepte la <a href="#" class="agreement-link">Politique de confidentialité</a>
                            </label>
                        </div>

                        <div class="trust-badge">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                            <span>Vos données sont chiffrées et sécurisées</span>
                        </div>
                    </div>
                </div>

                <!-- NAVIGATION BUTTONS -->
                <div class="button-group">
                    <button 
                        v-if="currentStep > 1"
                        type="button" 
                        class="btn btn-secondary"
                        @click="prevStep"
                    >
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>
                        Retour
                    </button>

                    <button 
                        v-if="currentStep < totalSteps"
                        type="button" 
                        class="btn btn-primary"
                        :disabled="!canProceedToNextStep"
                        @click="nextStep"
                    >
                        Suivant
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                        </svg>
                    </button>

                    <button 
                        v-if="currentStep === totalSteps"
                        type="button" 
                        class="btn btn-primary btn-submit"
                        :disabled="!canProceedToNextStep || isSubmitting"
                        @click="submit"
                    >
                        <span v-if="!isSubmitting" class="btn-text">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                            </svg>
                            Créer mon compte
                        </span>
                        <span v-else class="btn-loading">
                            <svg class="spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" opacity="0.25"/>
                                <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
                            </svg>
                            Inscription...
                        </span>
                    </button>
                </div>

                <!-- ADDITIONAL INFO -->
                <p class="additional-info">
                    Déjà inscrit?
                    <Link :href="route('login')" class="info-link">Se connecter</Link>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* ============================================
   LUXURY REGISTER - PREMIUM DESIGN SYSTEM
   ============================================ */

* {
    box-sizing: border-box;
}

/* ROOT VARIABLES */
:root {
    --color-sapphire: #0f3a7d;
    --color-coral: #ff6b6b;
    --color-teal: #17a2b8;
    --color-white: #ffffff;
    --color-dark: #0a1929;
    --color-light: #f5f7fa;
    --shadow-sm: 0 2px 8px rgba(15, 58, 125, 0.08);
    --shadow-md: 0 8px 24px rgba(15, 58, 125, 0.12);
    --shadow-lg: 0 16px 48px rgba(15, 58, 125, 0.15);
    --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.luxury-register-shell {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 50%, #dfe8f0 100%);
    position: relative;
    overflow: hidden;
    font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
    padding: 20px;
}

/* ANIMATED BACKGROUND ORBS */
.floating-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    opacity: 0.1;
    animation: float 15s ease-in-out infinite;
}

.orb-1 {
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, var(--color-sapphire), var(--color-teal));
    top: -100px;
    left: -100px;
    animation-delay: 0s;
}

.orb-2 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, var(--color-coral), var(--color-sapphire));
    bottom: -50px;
    right: -50px;
    animation-delay: 3s;
}

.orb-3 {
    width: 250px;
    height: 250px;
    background: linear-gradient(135deg, var(--color-teal), var(--color-coral));
    top: 50%;
    right: 5%;
    animation-delay: 6s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(30px); }
}

/* GEOMETRIC PATTERNS */
.geometric-pattern {
    position: absolute;
    width: 250px;
    height: 250px;
    opacity: 0.4;
    pointer-events: none;
}

.geometric-tl {
    top: -50px;
    left: -50px;
}

.geometric-br {
    bottom: -50px;
    right: -50px;
    transform: rotate(180deg);
}

/* CRESCENT ACCENT */
.crescent-accent {
    position: absolute;
    width: 120px;
    height: 120px;
    top: 60px;
    right: 40px;
    color: var(--color-sapphire);
    opacity: 0.08;
    pointer-events: none;
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* MAIN CONTENT */
.luxury-content {
    width: 100%;
    max-width: 500px;
    z-index: 10;
    animation: slideUp 0.6s ease-out 0.1s both;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* HEADER */
.luxury-header {
    text-align: center;
    margin-bottom: 40px;
    animation: fadeIn 0.6s ease-out 0.2s both;
}

.luxury-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: inherit;
    transition: var(--transition-base);
}

.luxury-logo:hover .logo-container {
    transform: scale(1.05) rotateZ(-5deg);
}

.logo-container {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--color-sapphire), var(--color-teal));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-white);
    box-shadow: var(--shadow-lg), 0 0 30px rgba(15, 58, 125, 0.2);
    transition: var(--transition-base);
}

.logo-mosque {
    width: 32px;
    height: 32px;
}

.logo-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.logo-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--color-sapphire);
    letter-spacing: -0.5px;
    line-height: 1;
}

.logo-subtitle {
    font-size: 12px;
    color: var(--color-coral);
    letter-spacing: 2px;
    font-weight: 500;
}

/* PROGRESS SECTION */
.progress-section {
    margin-bottom: 30px;
}

.progress-bar-container {
    width: 100%;
    height: 3px;
    background: rgba(15, 58, 125, 0.1);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 24px;
}

.progress-bar-track {
    width: 100%;
    height: 100%;
    position: relative;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--color-sapphire), var(--color-teal), var(--color-coral));
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 2px;
    box-shadow: 0 0 10px rgba(23, 162, 184, 0.5);
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex: 1;
    opacity: 0.5;
    transition: var(--transition-base);
}

.progress-step.active,
.progress-step.completed {
    opacity: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--color-white);
    border: 2px solid rgba(15, 58, 125, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: var(--color-sapphire);
    transition: var(--transition-base);
    position: relative;
}

.progress-step.active .step-circle {
    background: linear-gradient(135deg, var(--color-sapphire), var(--color-teal));
    border-color: var(--color-teal);
    color: var(--color-white);
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(15, 58, 125, 0.3);
}

.progress-step.completed .step-circle {
    background: var(--color-teal);
    border-color: var(--color-teal);
    color: var(--color-white);
}

.step-circle svg {
    width: 20px;
    height: 20px;
}

.step-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--color-sapphire);
    text-align: center;
    line-height: 1.2;
}

/* LUXURY CARD */
.luxury-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    border: 1px solid rgba(15, 58, 125, 0.08);
    padding: 40px 32px;
    box-shadow: var(--shadow-lg);
    animation: fadeIn 0.6s ease-out 0.3s both;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* STEP CONTENT */
.step-content {
    animation: stepIn 0.4s ease-out both;
    animation-delay: 0.1s;
}

.step-content.animating-out {
    animation: stepOut 0.3s ease-in forwards;
}

@keyframes stepIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes stepOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

.step-header {
    text-align: center;
    margin-bottom: 32px;
}

.step-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--color-sapphire);
    margin: 0 0 8px;
    letter-spacing: -0.5px;
}

.step-description {
    font-size: 14px;
    color: #64748b;
    margin: 0;
    letter-spacing: 0.3px;
}

/* GENDER SELECTOR */
.gender-selector {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.gender-option {
    padding: 24px 16px;
    border: 2px solid rgba(15, 58, 125, 0.15);
    border-radius: 16px;
    background: var(--color-white);
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    transition: var(--transition-base);
    text-align: center;
}

.gender-option:hover {
    border-color: var(--color-teal);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.15);
    transform: translateY(-2px);
}

.gender-option.selected {
    background: linear-gradient(135deg, rgba(15, 58, 125, 0.05), rgba(23, 162, 184, 0.05));
    border-color: var(--color-teal);
    box-shadow: 0 8px 24px rgba(23, 162, 184, 0.2);
    transform: scale(1.02);
}

.gender-icon {
    font-size: 40px;
}

.gender-label {
    font-weight: 600;
    color: var(--color-sapphire);
    font-size: 16px;
}

/* FORM GROUP */
.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--color-sapphire);
    margin-bottom: 8px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 14px;
    width: 20px;
    height: 20px;
    color: var(--color-sapphire);
    opacity: 0.6;
    pointer-events: none;
    z-index: 2;
}

.luxury-input {
    width: 100%;
    height: 48px;
    padding: 0 14px 0 42px;
    border: 2px solid rgba(15, 58, 125, 0.15);
    border-radius: 12px;
    background: var(--color-white);
    font-size: 15px;
    color: var(--color-dark);
    font-family: inherit;
    transition: var(--transition-base);
}

.luxury-input::placeholder {
    color: #cbd5e1;
    font-weight: 500;
}

.luxury-input:focus {
    outline: none;
    border-color: var(--color-teal);
    box-shadow: 0 0 0 4px rgba(23, 162, 184, 0.1), 0 4px 12px rgba(23, 162, 184, 0.15);
    background: rgba(23, 162, 184, 0.02);
}

.input-toggle {
    position: absolute;
    right: 14px;
    width: 20px;
    height: 20px;
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-base);
    padding: 0;
    z-index: 3;
}

.input-toggle:hover {
    color: var(--color-sapphire);
    transform: scale(1.1);
}

.input-toggle svg {
    width: 20px;
    height: 20px;
}

/* EMAIL VALIDATION */
.email-validation {
    position: absolute;
    right: 42px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;
}

.validation-icon {
    width: 18px;
    height: 18px;
}

.validation-icon.valid {
    color: #10b981;
}

.validation-icon.invalid {
    color: var(--color-coral);
}

/* PASSWORD STRENGTH */
.password-strength {
    margin-top: 8px;
}

.strength-bar {
    width: 100%;
    height: 4px;
    background: rgba(15, 58, 125, 0.1);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 6px;
}

.strength-fill {
    height: 100%;
    transition: width 0.3s ease, background-color 0.3s ease;
    border-radius: 2px;
}

.strength-text {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

/* PASSWORD MATCH */
.password-match {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
    font-size: 12px;
    font-weight: 500;
}

.match-icon {
    width: 16px;
    height: 16px;
}

.match-icon.valid {
    color: #10b981;
}

.match-icon.invalid {
    color: var(--color-coral);
}

.match-text {
    color: #64748b;
}

/* AGREEMENT SECTION */
.agreement-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 24px;
}

.checkbox-group {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.luxury-checkbox {
    width: 20px;
    height: 20px;
    min-width: 20px;
    cursor: pointer;
    appearance: none;
    border: 2px solid rgba(15, 58, 125, 0.2);
    border-radius: 6px;
    background: var(--color-white);
    transition: var(--transition-base);
    margin-top: 2px;
}

.luxury-checkbox:hover {
    border-color: var(--color-teal);
    box-shadow: 0 0 8px rgba(23, 162, 184, 0.2);
}

.luxury-checkbox:checked {
    background: linear-gradient(135deg, var(--color-sapphire), var(--color-teal));
    border-color: var(--color-teal);
    position: relative;
}

.luxury-checkbox:checked::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: var(--color-white);
    font-weight: bold;
    font-size: 14px;
}

.checkbox-label {
    font-size: 14px;
    color: #475569;
    cursor: pointer;
    line-height: 1.5;
    flex: 1;
}

.agreement-link {
    color: var(--color-sapphire);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-base);
    border-bottom: 1px solid transparent;
}

.agreement-link:hover {
    color: var(--color-teal);
    border-bottom-color: var(--color-teal);
}

/* TRUST BADGE */
.trust-badge {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.08), rgba(15, 58, 125, 0.08));
    border-radius: 12px;
    border: 1px solid rgba(23, 162, 184, 0.15);
    font-size: 13px;
    color: #475569;
    font-weight: 500;
}

.trust-badge svg {
    width: 20px;
    height: 20px;
    color: var(--color-teal);
    flex-shrink: 0;
}

/* BUTTONS */
.button-group {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.btn {
    flex: 1;
    height: 48px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: var(--transition-base);
    letter-spacing: 0.3px;
    text-transform: uppercase;
    font-family: inherit;
}

.btn svg {
    width: 18px;
    height: 18px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--color-sapphire), var(--color-teal));
    color: var(--color-white);
    box-shadow: var(--shadow-md), 0 0 20px rgba(15, 58, 125, 0.25);
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg), 0 0 30px rgba(23, 162, 184, 0.4);
}

.btn-primary:active:not(:disabled) {
    transform: translateY(0);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: rgba(15, 58, 125, 0.08);
    color: var(--color-sapphire);
    border: 1px solid rgba(15, 58, 125, 0.2);
}

.btn-secondary:hover {
    background: rgba(15, 58, 125, 0.12);
    border-color: var(--color-sapphire);
    transform: translateY(-2px);
}

.btn-secondary:active {
    transform: translateY(0);
}

.btn-submit {
    min-height: 52px;
    font-size: 15px;
    letter-spacing: 0.5px;
}

.btn-text,
.btn-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.spinner {
    width: 18px;
    height: 18px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* ERROR MESSAGE */
.error-message {
    font-size: 13px;
    color: var(--color-coral);
    text-align: center;
    padding: 12px;
    background: rgba(255, 107, 107, 0.08);
    border-radius: 8px;
    border-left: 3px solid var(--color-coral);
}

.field-error {
    font-size: 12px;
    color: var(--color-coral);
    margin-top: 6px;
    display: block;
}

/* ADDITIONAL INFO */
.additional-info {
    text-align: center;
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

.info-link {
    color: var(--color-sapphire);
    text-decoration: none;
    font-weight: 700;
    margin-left: 4px;
    transition: var(--transition-base);
    border-bottom: 2px solid transparent;
}

.info-link:hover {
    color: var(--color-teal);
    border-bottom-color: var(--color-teal);
}

/* RESPONSIVE DESIGN */
@media (max-width: 640px) {
    .luxury-content {
        max-width: 100%;
    }

    .luxury-card {
        padding: 28px 20px;
        border-radius: 20px;
    }

    .step-title {
        font-size: 22px;
    }

    .step-description {
        font-size: 13px;
    }

    .logo-title {
        font-size: 20px;
    }

    .gender-selector {
        grid-template-columns: 1fr;
    }

    .button-group {
        gap: 8px;
    }

    .btn {
        height: 44px;
        font-size: 13px;
    }

    .crescent-accent {
        width: 80px;
        height: 80px;
        top: 40px;
        right: 20px;
    }

    .floating-orb {
        filter: blur(40px);
    }
}

@media (max-width: 480px) {
    .luxury-register-shell {
        padding: 16px;
    }

    .luxury-header {
        margin-bottom: 28px;
    }

    .logo-container {
        width: 52px;
        height: 52px;
    }

    .logo-title {
        font-size: 18px;
    }

    .logo-subtitle {
        font-size: 10px;
    }

    .progress-steps {
        gap: 8px;
    }

    .step-circle {
        width: 36px;
        height: 36px;
        font-size: 12px;
    }

    .step-label {
        font-size: 10px;
    }

    .step-title {
        font-size: 20px;
        margin-bottom: 4px;
    }

    .luxury-card {
        padding: 24px 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .luxury-input {
        height: 44px;
        font-size: 14px;
    }

    .btn {
        height: 40px;
        font-size: 12px;
        gap: 6px;
    }

    .btn svg {
        width: 16px;
        height: 16px;
    }
}
</style>
