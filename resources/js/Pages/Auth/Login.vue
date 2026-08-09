<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

defineProps({
    translations: { type: Object, default: () => ({}) },
    locale: { type: String, default: 'fr' },
    locales: { type: Array, default: () => [] },
    canResetPassword: { type: Boolean, default: true },
    status: { type: String, default: '' },
    canRegister: { type: Boolean, default: true },
});

document.body.classList.remove(...document.body.classList);
document.body.classList.add("frontend.login");

const showPassword = ref(false);
const form = useForm({ email: '', password: '', remember: false });
const focusedField = ref(null);
const mouseX = ref(0);
const mouseY = ref(0);

const submit = () => {
    form.post(route('login'), { onFinish: () => form.reset('password') });
};

const handleMouseMove = (e) => {
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
};

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);
});
</script>

<template>
    <Head title="Connexion — Rencontre Éthique" />

    <div class="luxury-login-wrapper">
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
        <div class="login-container">
            <!-- Glassmorphism Card -->
            <div class="glass-card">
                <!-- Header with Logo -->
                <div class="card-header">
                    <Link href="/" class="brand-logo">
                        <div class="logo-dome">
                            <svg viewBox="0 0 100 100" class="dome-icon">
                                <!-- Mosque Dome -->
                                <defs>
                                    <linearGradient id="domeGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#ff8787;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <!-- Crescent Moon on top -->
                                <path d="M50 15c-15 0-20-5-20-5c0 8 5 15 20 18c15-3 20-10 20-18c0 0-5 5-20 5z" fill="url(#domeGradient)" />
                                <!-- Dome -->
                                <ellipse cx="50" cy="50" rx="28" ry="32" fill="url(#domeGradient)" opacity="0.9" />
                                <!-- Dome shine -->
                                <ellipse cx="45" cy="40" rx="12" ry="18" fill="#ffffff" opacity="0.3" />
                                <!-- Base -->
                                <rect x="30" y="80" width="40" height="12" fill="#0f3a7d" rx="2" />
                                <!-- Minarets -->
                                <rect x="22" y="70" width="6" height="20" fill="#17a2b8" rx="1" />
                                <rect x="72" y="70" width="6" height="20" fill="#17a2b8" rx="1" />
                            </svg>
                        </div>
                        <div class="brand-name">
                            <div class="brand-title">Rencontre Éthique</div>
                            <div class="brand-subtitle">حلال · مسجد · جدية</div>
                        </div>
                    </Link>

                    <div class="header-accent"></div>
                </div>

                <!-- Content Section -->
                <div class="card-content">
                    <!-- Welcome Text -->
                    <div class="welcome-section">
                        <h1 class="welcome-title">Bienvenue</h1>
                        <p class="welcome-text">Connectez-vous pour accéder à votre profil et rencontrer des personnes sérieuses</p>
                    </div>

                    <!-- Status/Error Messages -->
                    <transition name="slide-fade">
                        <div v-if="status" class="message-box success-message">
                            <svg class="message-icon" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="currentColor"/>
                            </svg>
                            <span>{{ status }}</span>
                        </div>
                    </transition>

                    <transition name="slide-fade">
                        <div v-if="$page.props.flash?.error" class="message-box error-message">
                            <svg class="message-icon" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="currentColor"/>
                            </svg>
                            <span>{{ $page.props.flash.error }}</span>
                        </div>
                    </transition>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="luxury-form">
                        <!-- Email Field -->
                        <div class="form-group" @mouseenter="focusedField = 'email'" @mouseleave="focusedField = 'email' && focusedField !== 'email' ? null : focusedField">
                            <label class="form-label" for="email">Email ou téléphone</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor"/>
                                </svg>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="text"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="luxury-input"
                                    placeholder="vous@exemple.fr"
                                    @focus="focusedField = 'email'"
                                    @blur="focusedField = null"
                                />
                            </div>
                            <transition name="slide-fade">
                                <span v-if="form.errors.email" class="form-error">{{ form.errors.email }}</span>
                            </transition>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group" @mouseenter="focusedField = 'password'" @mouseleave="focusedField = 'password' && focusedField !== 'password' ? null : focusedField">
                            <label class="form-label" for="password">Mot de passe</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 24 24">
                                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5s-5 2.24-5 5v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" fill="currentColor"/>
                                </svg>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    class="luxury-input"
                                    placeholder="••••••••"
                                    @focus="focusedField = 'password'"
                                    @blur="focusedField = null"
                                />
                                <button
                                    type="button"
                                    class="password-toggle"
                                    @click="showPassword = !showPassword"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg v-if="!showPassword" viewBox="0 0 24 24">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                    </svg>
                                    <svg v-else viewBox="0 0 24 24">
                                        <path d="M11.83 9L5.5 2.67c-1.41 1.41-2.73 3.15-3.77 5.15C2.5 9.5 2 10.72 2 12s.5 2.5 1.73 4.18c2.04 3.9 5.98 6.82 10.27 6.82.9 0 1.79-.1 2.66-.28L11.83 9zm11.29-1.73c-1.73-3.9-5.98-6.82-10.27-6.82-1.69 0-3.31.34-4.77.94l2.65 2.65C13.06 4.56 14.5 4 16 4c3.59 0 6.18 2.56 6.18 6 0 1.5-.56 2.94-1.69 4.04l2.83 2.83c1.12-1.45 2.04-3.17 2.75-5.12.59-1.42 1.14-2.77.52-4.44zM12 18c-3.59 0-6.18-2.56-6.18-6 0-1.5.56-2.94 1.69-4.04l-2.83-2.83c-1.12 1.45-2.04 3.17-2.75 5.12C.5 12.42 0 13.77.62 15.19 2.35 19.09 6.6 22 12 22c1.69 0 3.31-.34 4.77-.94l-2.65-2.65C10.94 19.44 9.5 20 8 20c-3.59 0-6.18-2.56-6.18-6 0-1.5.56-2.94 1.69-4.04l2.83 2.83c1.12 1.45 2.04 3.17 2.75 5.12.59 1.42 1.14 2.77.52 4.44 1.5 0 2.94-.56 4.04-1.69l2.83 2.83c1.45-1.12 3.17-2.04 5.12-2.75.59-1.42 1.14-2.77.52-4.44z" fill="currentColor"/>
                                    </svg>
                                </button>
                            </div>
                            <transition name="slide-fade">
                                <span v-if="form.errors.password" class="form-error">{{ form.errors.password }}</span>
                            </transition>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="form-row">
                            <label class="checkbox-wrapper">
                                <input
                                    type="checkbox"
                                    v-model="form.remember"
                                    class="checkbox-input"
                                />
                                <span class="checkbox-label">Se souvenir de moi</span>
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="forgot-password-link"
                            >
                                Mot de passe oublié ?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="submit-button"
                            :disabled="form.processing"
                        >
                            <transition mode="out-in" name="button-transition">
                                <div v-if="!form.processing" key="text" class="button-content">
                                    <span>Se connecter</span>
                                    <svg class="button-arrow" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                    </svg>
                                </div>
                                <div v-else key="spinner" class="button-content">
                                    <svg class="button-spinner" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2" stroke-opacity="0.25"/>
                                        <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    <span>Connexion...</span>
                                </div>
                            </transition>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="form-divider">
                        <span class="divider-text">Nouveau sur Rencontre Éthique ?</span>
                    </div>

                    <!-- Register Link -->
                    <Link :href="route('register')" class="register-button">
                        <span>Créer mon profil</span>
                        <svg viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        </svg>
                    </Link>
                </div>

                <!-- Bottom Accent -->
                <div class="card-footer-accent"></div>
            </div>

            <!-- Decorative Elements -->
            <div class="decorative-line decorative-line-1"></div>
            <div class="decorative-line decorative-line-2"></div>
        </div>
    </div>
</template>

<style scoped>
/* ===== VARIABLES & FOUNDATION ===== */
:root {
    --primary: #0f3a7d;
    --accent: #ff6b6b;
    --success: #17a2b8;
    --white: #ffffff;
    --dark-text: #1a1a1a;
    --light-text: #8b8b8b;
}

* {
    box-sizing: border-box;
}

/* ===== LAYOUT & BACKGROUND ===== */
.luxury-login-wrapper {
    min-height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0f3a7d 0%, #1a4d9e 25%, #17a2b8 75%, #0f3a7d 100%);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    position: relative;
    overflow: hidden;
    font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
    padding: 20px;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* ===== ANIMATED BACKGROUND ORBS ===== */
.gradient-bg {
    position: absolute;
    inset: 0;
    overflow: hidden;
    z-index: 0;
}

.gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.6;
    mix-blend-mode: screen;
}

.gradient-orb-1 {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 107, 107, 0.8), transparent);
    top: -100px;
    right: -100px;
    animation: float1 20s ease-in-out infinite;
}

.gradient-orb-2 {
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(23, 162, 184, 0.6), transparent);
    bottom: -50px;
    left: -50px;
    animation: float2 25s ease-in-out infinite;
}

.gradient-orb-3 {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, rgba(255, 135, 135, 0.5), transparent);
    top: 50%;
    left: 10%;
    animation: float3 22s ease-in-out infinite;
}

@keyframes float1 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(50px, -50px); }
}

@keyframes float2 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(-50px, 50px); }
}

@keyframes float3 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(30px, 30px); }
}

/* ===== FLOATING SHAPES ===== */
.floating-shapes {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
}

.shape {
    position: absolute;
    opacity: 0.08;
}

.shape-star-1 {
    width: 100px;
    height: 100px;
    top: 10%;
    right: 5%;
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    background: var(--accent);
    animation: rotate 20s linear infinite;
}

.shape-star-2 {
    width: 80px;
    height: 80px;
    bottom: 15%;
    left: 5%;
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    background: var(--success);
    animation: rotate 25s linear infinite reverse;
}

.shape-crescent-1 {
    width: 120px;
    height: 120px;
    top: 20%;
    left: 8%;
    background: radial-gradient(circle at 30% 50%, transparent 30%, var(--white) 30%, var(--white) 70%, transparent 70%);
    border-radius: 50%;
    animation: float1 22s ease-in-out infinite;
}

.shape-crescent-2 {
    width: 90px;
    height: 90px;
    bottom: 20%;
    right: 10%;
    background: radial-gradient(circle at 35% 50%, transparent 35%, var(--accent) 35%, var(--accent) 65%, transparent 65%);
    border-radius: 50%;
    animation: float2 24s ease-in-out infinite;
}

.shape-geometric-1 {
    width: 150px;
    height: 150px;
    top: 40%;
    right: 15%;
    clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);
    background: linear-gradient(45deg, var(--primary), var(--success));
    animation: rotate 30s linear infinite;
}

.shape-geometric-2 {
    width: 110px;
    height: 110px;
    bottom: 30%;
    left: 12%;
    clip-path: polygon(25% 0%, 75% 0%, 100% 25%, 100% 75%, 75% 100%, 25% 100%, 0% 75%, 0% 25%);
    background: var(--accent);
    animation: rotate 28s linear infinite reverse;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* ===== LOGIN CONTAINER ===== */
.login-container {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 500px;
    perspective: 1000px;
}

/* ===== GLASSMORPHISM CARD ===== */
.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 30px;
    padding: 50px 40px;
    box-shadow:
        0 8px 32px 0 rgba(15, 58, 125, 0.37),
        inset 0 1px 1px rgba(255, 255, 255, 0.6);
    position: relative;
    overflow: hidden;
}

.glass-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 20% 30%, rgba(255, 107, 107, 0.1), transparent 50%);
    pointer-events: none;
}

.glass-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.5), transparent);
}

/* ===== CARD HEADER ===== */
.card-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
    margin-bottom: 32px;
    position: relative;
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    cursor: pointer;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.brand-logo:hover {
    transform: translateY(-2px);
}

.logo-dome {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--accent), #ff8787);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        0 12px 24px rgba(15, 58, 125, 0.35),
        0 0 30px rgba(255, 107, 107, 0.4);
    position: relative;
    overflow: hidden;
    animation: pulse-glow 3s ease-in-out infinite;
}

.logo-dome::before {
    content: '';
    position: absolute;
    inset: -50%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: shimmer 3s infinite;
}

.dome-icon {
    width: 50px;
    height: 50px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    position: relative;
    z-index: 1;
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 12px 24px rgba(15, 58, 125, 0.35), 0 0 30px rgba(255, 107, 107, 0.4); }
    50% { box-shadow: 0 12px 24px rgba(15, 58, 125, 0.5), 0 0 50px rgba(255, 107, 107, 0.6); }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.brand-name {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.brand-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary);
    letter-spacing: -0.5px;
    line-height: 1.2;
}

.brand-subtitle {
    font-size: 13px;
    color: var(--success);
    letter-spacing: 2px;
    font-weight: 500;
}

.header-accent {
    height: 3px;
    width: 60px;
    background: linear-gradient(90deg, var(--accent), var(--success), var(--accent));
    border-radius: 2px;
    animation: slide 2s ease-in-out infinite;
}

@keyframes slide {
    0%, 100% { width: 60px; }
    50% { width: 100px; }
}

/* ===== CARD CONTENT ===== */
.card-content {
    position: relative;
    z-index: 2;
}

/* ===== WELCOME SECTION ===== */
.welcome-section {
    text-align: center;
    margin-bottom: 28px;
}

.welcome-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
    margin: 0 0 8px 0;
    letter-spacing: -0.5px;
}

.welcome-text {
    font-size: 14px;
    color: var(--light-text);
    margin: 0;
    line-height: 1.6;
}

/* ===== MESSAGE BOXES ===== */
.message-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 20px;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-message {
    background: rgba(23, 162, 184, 0.1);
    color: var(--success);
    border: 1px solid rgba(23, 162, 184, 0.3);
}

.error-message {
    background: rgba(255, 107, 107, 0.1);
    color: var(--accent);
    border: 1px solid rgba(255, 107, 107, 0.3);
}

.message-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

/* ===== FORM STYLES ===== */
.luxury-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* ===== INPUT STYLING ===== */
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
    color: var(--light-text);
    pointer-events: none;
    transition: color 0.3s ease;
    z-index: 2;
}

.luxury-input {
    width: 100%;
    height: 50px;
    padding: 0 16px 0 48px;
    background: rgba(255, 255, 255, 0.8);
    border: 2px solid rgba(15, 58, 125, 0.2);
    border-radius: 14px;
    font-size: 15px;
    color: var(--dark-text);
    font-family: 'Segoe UI', sans-serif;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.luxury-input::placeholder {
    color: rgba(139, 139, 139, 0.6);
}

.luxury-input:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.95);
    border-color: var(--accent);
    box-shadow:
        0 0 0 3px rgba(255, 107, 107, 0.1),
        inset 0 1px 2px rgba(0, 0, 0, 0.05);
}

.luxury-input:focus + .input-icon,
.input-wrapper:has(.luxury-input:focus) .input-icon {
    color: var(--accent);
}

.form-error {
    font-size: 12px;
    color: var(--accent);
    font-weight: 500;
}

/* ===== PASSWORD TOGGLE ===== */
.password-toggle {
    position: absolute;
    right: 14px;
    width: 24px;
    height: 24px;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--light-text);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s ease, transform 0.2s ease;
    z-index: 3;
}

.password-toggle:hover {
    color: var(--primary);
    transform: scale(1.1);
}

.password-toggle:active {
    transform: scale(0.95);
}

.password-toggle svg {
    width: 20px;
    height: 20px;
}

/* ===== FORM ROW ===== */
.form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 4px;
    margin-bottom: 8px;
}

/* ===== CHECKBOX ===== */
.checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    user-select: none;
}

.checkbox-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary);
    border-radius: 4px;
    transition: transform 0.2s ease;
}

.checkbox-input:checked {
    background: linear-gradient(135deg, var(--primary), var(--accent));
}

.checkbox-input:hover {
    transform: scale(1.1);
}

.checkbox-label {
    font-size: 13px;
    color: var(--light-text);
    font-weight: 500;
}

/* ===== FORGOT PASSWORD LINK ===== */
.forgot-password-link {
    font-size: 13px;
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.forgot-password-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--accent);
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.3s ease;
}

.forgot-password-link:hover::after {
    transform: scaleX(1);
    transform-origin: left;
}

/* ===== SUBMIT BUTTON ===== */
.submit-button {
    width: 100%;
    height: 56px;
    background: linear-gradient(135deg, var(--primary) 0%, #0d2a5f 100%);
    color: var(--white);
    border: none;
    border-radius: 14px;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow:
        0 10px 30px rgba(15, 58, 125, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
    margin-top: 8px;
}

.submit-button::before {
    content: '';
    position: absolute;
    inset: -50%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    animation: shimmer 3s infinite;
}

.submit-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow:
        0 15px 40px rgba(15, 58, 125, 0.5),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.submit-button:active:not(:disabled) {
    transform: translateY(0px);
    box-shadow:
        0 5px 15px rgba(15, 58, 125, 0.3),
        inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.submit-button:disabled {
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

.button-arrow {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
}

.submit-button:hover:not(:disabled) .button-arrow {
    transform: translateX(2px);
}

.button-spinner {
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* ===== TRANSITIONS ===== */
.button-transition-enter-active,
.button-transition-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.button-transition-enter-from,
.button-transition-leave-to {
    opacity: 0;
    transform: translateY(4px);
}

.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}

/* ===== FORM DIVIDER ===== */
.form-divider {
    position: relative;
    text-align: center;
    margin: 28px 0 24px 0;
}

.divider-text {
    font-size: 12px;
    color: var(--light-text);
    font-weight: 600;
    position: relative;
    background: rgba(255, 255, 255, 0.95);
    padding: 0 12px;
    letter-spacing: 0.5px;
}

.form-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(15, 58, 125, 0.2), transparent);
    z-index: 0;
}

/* ===== REGISTER BUTTON ===== */
.register-button {
    width: 100%;
    height: 50px;
    background: linear-gradient(135deg, var(--success), #1ac5d0);
    color: var(--white);
    border: none;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow:
        0 10px 30px rgba(23, 162, 184, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.register-button:hover {
    transform: translateY(-2px);
    box-shadow:
        0 15px 40px rgba(23, 162, 184, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.register-button:active {
    transform: translateY(0px);
}

.register-button svg {
    width: 18px;
    height: 18px;
    transition: transform 0.3s ease;
}

.register-button:hover svg {
    transform: translateX(2px);
}

/* ===== CARD FOOTER ===== */
.card-footer-accent {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: linear-gradient(to top, rgba(255, 107, 107, 0.05), transparent);
    border-radius: 0 0 30px 30px;
    pointer-events: none;
}

/* ===== DECORATIVE LINES ===== */
.decorative-line {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
}

.decorative-line-1 {
    width: 2px;
    height: 100px;
    left: 10%;
    top: -50px;
    animation: drawLine 2s ease-in-out infinite;
}

.decorative-line-2 {
    width: 2px;
    height: 100px;
    right: 10%;
    bottom: -50px;
    animation: drawLine 2.5s ease-in-out infinite reverse;
}

@keyframes drawLine {
    0%, 100% { height: 50px; opacity: 0; }
    50% { height: 150px; opacity: 1; }
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 640px) {
    .luxury-login-wrapper {
        padding: 16px;
    }

    .glass-card {
        padding: 32px 24px;
        border-radius: 24px;
    }

    .card-header {
        gap: 16px;
        margin-bottom: 24px;
    }

    .logo-dome {
        width: 70px;
        height: 70px;
    }

    .dome-icon {
        width: 44px;
        height: 44px;
    }

    .brand-title {
        font-size: 24px;
    }

    .brand-subtitle {
        font-size: 12px;
    }

    .welcome-title {
        font-size: 20px;
    }

    .welcome-text {
        font-size: 13px;
    }

    .luxury-input {
        height: 46px;
        font-size: 14px;
        padding: 0 14px 0 44px;
    }

    .input-icon {
        width: 18px;
        height: 18px;
        left: 12px;
    }

    .submit-button {
        height: 52px;
        font-size: 15px;
    }

    .register-button {
        height: 48px;
        font-size: 14px;
    }

    .form-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .forgot-password-link {
        align-self: flex-start;
    }

    .shape-star-1,
    .shape-star-2,
    .shape-crescent-1,
    .shape-crescent-2,
    .shape-geometric-1,
    .shape-geometric-2 {
        opacity: 0.04;
    }

    .gradient-orb {
        opacity: 0.4;
    }
}

@media (max-width: 380px) {
    .glass-card {
        padding: 24px 18px;
    }

    .card-header {
        gap: 12px;
    }

    .logo-dome {
        width: 60px;
        height: 60px;
    }

    .dome-icon {
        width: 38px;
        height: 38px;
    }

    .brand-title {
        font-size: 20px;
    }

    .brand-subtitle {
        font-size: 11px;
        letter-spacing: 1.5px;
    }

    .welcome-title {
        font-size: 18px;
    }

    .welcome-text {
        font-size: 12px;
    }

    .form-label {
        font-size: 11px;
    }

    .luxury-form {
        gap: 16px;
    }

    .form-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .checkbox-label,
    .forgot-password-link {
        font-size: 12px;
    }

    .submit-button {
        height: 50px;
    }

    .register-button {
        height: 46px;
        font-size: 13px;
    }
}
</style>
