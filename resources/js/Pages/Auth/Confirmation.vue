<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

defineProps({
    translations: { type: Object, default: () => ({}) },
    locale: { type: String, default: 'fr' },
    locales: { type: Array, default: () => [] },
    user: { type: Object, default: () => ({}) },
});

document.body.classList.remove(...document.body.classList);
document.body.classList.add("frontend.confirmation");

// STATE MANAGEMENT
const validationCode = ref('CODE-2024-ABC123');
const confirmationStatus = ref('pending'); // 'pending', 'one', 'two'
const copiedCode = ref(false);
const showFAQ = ref(false);
const witnesses = ref([
    { id: 1, name: 'En attente', status: 'pending', date: null, avatar: '👤' },
    { id: 2, name: 'En attente', status: 'pending', date: null, avatar: '👤' }
]);

// COMPUTED
const witnessesConfirmed = computed(() => {
    return witnesses.value.filter(w => w.status === 'confirmed').length;
});

const confirmationComplete = computed(() => witnessesConfirmed.value === 2);

const statusLabel = computed(() => {
    if (confirmationComplete.value) return '2/2 - Confirmé';
    if (witnessesConfirmed.value === 1) return '1/2 - En cours';
    return 'En attente';
});

const statusColor = computed(() => {
    if (confirmationComplete.value) return '#10b981';
    if (witnessesConfirmed.value === 1) return '#f59e0b';
    return '#ef4444';
});

const progressPercent = computed(() => {
    return (witnessesConfirmed.value / 2) * 100;
});

// METHODS
const copyValidationCode = async () => {
    try {
        await navigator.clipboard.writeText(validationCode.value);
        copiedCode.value = true;
        setTimeout(() => {
            copiedCode.value = false;
        }, 2000);
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};

const openWhatsApp = () => {
    const message = encodeURIComponent(`Je dois confirmer mon profil sur Rencontre Éthique. Code de validation: ${validationCode.value}`);
    window.open(`https://wa.me/?text=${message}`, '_blank');
};

const openEmail = () => {
    const subject = encodeURIComponent('Confirmation de profil - Rencontre Éthique');
    const body = encodeURIComponent(`Bonjour,\n\nPeut-tu confirmer mon profil?\nCode: ${validationCode.value}\n\nMerci!`);
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
};

// LIFECYCLE
onMounted(() => {
    document.documentElement.style.opacity = '1';
});
</script>

<template>
    <Head title="Confirmation de Profil — Rencontre Éthique" />

    <div class="confirmation-shell">
        <!-- ANIMATED BACKGROUND PATTERNS -->
        <div class="animated-bg">
            <div class="floating-orb orb-1"></div>
            <div class="floating-orb orb-2"></div>
            <div class="floating-orb orb-3"></div>
            <div class="islamic-pattern"></div>
        </div>

        <!-- CONTENT WRAPPER -->
        <div class="confirmation-content">
            <!-- HERO SECTION -->
            <section class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">Étape 2: Confirmation de Votre Profil</h1>
                    <p class="hero-subtitle">Deux témoins fidèles de votre mosquée doivent confirmer votre profil</p>
                </div>
                <div class="hero-accent">✨</div>
            </section>

            <!-- EXPLANATION CARDS -->
            <section class="explanation-section">
                <div class="cards-grid">
                    <!-- Card 1 -->
                    <div class="glass-card card-1">
                        <div class="card-icon">✅</div>
                        <h3 class="card-title">Qu'est-ce que la confirmation?</h3>
                        <p class="card-description">
                            La confirmation garantit l'authenticité de votre profil. Deux membres de confiance de votre communauté vérifient que vous êtes bien la personne décrite.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="glass-card card-2">
                        <div class="card-icon">🤝</div>
                        <h3 class="card-title">Pourquoi deux témoins?</h3>
                        <p class="card-description">
                            Deux témoins créent un système de confiance transparent. C'est un principe que nous empruntons aux traditions islamiques d'authenticité et de communauté.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="glass-card card-3">
                        <div class="card-icon">📋</div>
                        <h3 class="card-title">Comment procéder?</h3>
                        <p class="card-description">
                            Partagez votre code de validation avec vos témoins. Ils confirmeront votre profil en ligne. Le processus prend généralement 24 à 48 heures.
                        </p>
                    </div>
                </div>
            </section>

            <!-- PROCESS FLOW SECTION -->
            <section class="process-section">
                <h2 class="section-title">Le Processus de Confirmation</h2>
                <div class="process-flow">
                    <!-- Step 1 -->
                    <div class="process-step">
                        <div class="step-number step-active">1</div>
                        <div class="step-content">
                            <h4 class="step-title">Soumettre le Profil</h4>
                            <p class="step-description">Votre profil a été envoyé pour validation</p>
                        </div>
                        <div class="step-icon">📝</div>
                    </div>

                    <!-- Connector -->
                    <div class="connector"></div>

                    <!-- Step 2 -->
                    <div class="process-step">
                        <div class="step-number step-active">2</div>
                        <div class="step-content">
                            <h4 class="step-title">Partager le Code</h4>
                            <p class="step-description">Partager le code avec vos témoins</p>
                        </div>
                        <div class="step-icon">🔐</div>
                    </div>

                    <!-- Connector -->
                    <div class="connector"></div>

                    <!-- Step 3 -->
                    <div class="process-step" :class="{ 'step-completed': confirmationComplete }">
                        <div class="step-number" :class="{ 'step-active': confirmationComplete }">3</div>
                        <div class="step-content">
                            <h4 class="step-title">Validation des Témoins</h4>
                            <p class="step-description">Les témoins confirment votre profil</p>
                        </div>
                        <div class="step-icon">✓</div>
                    </div>
                </div>
            </section>

            <!-- STATUS SECTION -->
            <section class="status-section">
                <div class="status-card">
                    <div class="status-header">
                        <h2 class="section-title">Statut de Confirmation</h2>
                        <div class="status-badge" :style="{ backgroundColor: statusColor }">
                            {{ statusLabel }}
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-label">Témoins confirmés</div>
                        <div class="progress-bar">
                            <div class="progress-fill" :style="{ width: progressPercent + '%' }"></div>
                        </div>
                        <div class="progress-text">{{ witnessesConfirmed }}/2</div>
                    </div>

                    <!-- Witnesses Timeline -->
                    <div class="witnesses-timeline">
                        <div 
                            v-for="(witness, index) in witnesses" 
                            :key="witness.id"
                            class="witness-item"
                            :class="{ 'witness-confirmed': witness.status === 'confirmed' }"
                        >
                            <div class="witness-avatar">{{ witness.avatar }}</div>
                            <div class="witness-info">
                                <h4 class="witness-name">Témoin {{ index + 1 }}</h4>
                                <p class="witness-status" :class="{ 'status-confirmed': witness.status === 'confirmed' }">
                                    {{ witness.status === 'pending' ? 'En attente' : 'Confirmé le ' + witness.date }}
                                </p>
                            </div>
                            <div v-if="witness.status === 'confirmed'" class="witness-check">✓</div>
                            <div v-else class="witness-pending">⏳</div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="next-steps">
                        <h3 class="next-steps-title">Prochaines étapes</h3>
                        <ul class="steps-list">
                            <li v-if="!confirmationComplete">Partager votre code de validation avec vos témoins</li>
                            <li v-if="!confirmationComplete">Attendez que les témoins confirment votre profil</li>
                            <li v-else>🎉 Félicitations! Votre profil est vérifié</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- CTA SECTION -->
            <section class="cta-section">
                <div class="code-display-card">
                    <h2 class="section-title">Votre Code de Validation</h2>
                    
                    <div class="code-container">
                        <div class="code-box">
                            <code class="validation-code">{{ validationCode }}</code>
                            <button 
                                @click="copyValidationCode"
                                class="copy-btn"
                                :class="{ 'copied': copiedCode }"
                            >
                                <span v-if="!copiedCode">📋 Copier</span>
                                <span v-else>✓ Copié!</span>
                            </button>
                        </div>
                    </div>

                    <div class="code-description">
                        <p>Partagez ce code avec vos deux témoins pour qu'ils puissent confirmer votre profil.</p>
                    </div>
                </div>

                <!-- SHARE OPTIONS -->
                <div class="share-options">
                    <h3 class="share-title">Partager le Code</h3>
                    <div class="share-buttons">
                        <button @click="openWhatsApp" class="share-btn whatsapp-btn">
                            <span class="share-icon">💬</span>
                            <span class="share-text">WhatsApp</span>
                        </button>
                        <button @click="openEmail" class="share-btn email-btn">
                            <span class="share-icon">✉️</span>
                            <span class="share-text">Email</span>
                        </button>
                        <button class="share-btn sms-btn">
                            <span class="share-icon">📱</span>
                            <span class="share-text">SMS</span>
                        </button>
                    </div>
                </div>

                <!-- FAQ SECTION -->
                <div class="faq-section">
                    <button 
                        @click="showFAQ = !showFAQ"
                        class="faq-toggle"
                    >
                        <span class="faq-icon" :class="{ 'rotated': showFAQ }">▶</span>
                        <span class="faq-label">Questions Fréquemment Posées</span>
                    </button>

                    <div v-if="showFAQ" class="faq-content">
                        <div class="faq-item">
                            <h4 class="faq-question">Qui peut être un témoin?</h4>
                            <p class="faq-answer">Un témoin doit être un membre de confiance de votre communauté/mosquée qui peut confirmer votre identité et votre sincérité.</p>
                        </div>
                        <div class="faq-item">
                            <h4 class="faq-question">Combien de temps prend la confirmation?</h4>
                            <p class="faq-answer">Généralement 24 à 48 heures. Les témoins reçoivent un lien pour confirmer votre profil en ligne.</p>
                        </div>
                        <div class="faq-item">
                            <h4 class="faq-question">Que se passe-t-il après confirmation?</h4>
                            <p class="faq-answer">Votre profil sera marqué comme "Vérifié" et vous pourrez commencer à explorer les profils compatibles.</p>
                        </div>
                        <div class="faq-item">
                            <h4 class="faq-question">Et si je n'ai pas de témoins?</h4>
                            <p class="faq-answer">Contactez notre équipe support. Nous pouvons discuter des alternatives ou vous aider à trouver des témoins.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ACTION BUTTONS -->
            <section class="action-section">
                <Link href="/dashboard" class="btn btn-primary">
                    ← Retour au Tableau de Bord
                </Link>
                <button class="btn btn-secondary" @click="showFAQ = !showFAQ">
                    💬 Contacter le Support
                </button>
            </section>
        </div>

        <!-- DECORATIVE ACCENT -->
        <div class="crescent-accent"></div>
    </div>
</template>

<style scoped>
/* ROOT COLORS */
:root {
    --color-sapphire: #0f172a;
    --color-coral: #ff6b6b;
    --color-teal: #14b8a6;
    --color-gold: #fbbf24;
    --color-white: #ffffff;
    --color-light-gray: #f8fafc;
    --color-medium-gray: #cbd5e1;
    --color-dark-gray: #334155;
    --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* BODY & SHELL */
.confirmation-shell {
    min-height: 100vh;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.98) 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

/* ANIMATED BACKGROUND */
.animated-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
}

.floating-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.15;
    animation: float 20s ease-in-out infinite;
}

.orb-1 {
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #0f172a, #14b8a6);
    top: -100px;
    left: -100px;
    animation-delay: 0s;
}

.orb-2 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #ff6b6b, #fbbf24);
    top: 50%;
    right: -50px;
    animation-delay: 5s;
}

.orb-3 {
    width: 350px;
    height: 350px;
    background: linear-gradient(135deg, #14b8a6, #0f172a);
    bottom: -100px;
    left: 50%;
    animation-delay: 10s;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(30px, 30px) scale(1.1); }
}

/* ISLAMIC PATTERN */
.islamic-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0.05;
    background-image: 
        radial-gradient(circle at 20% 50%, transparent 20%, rgba(20, 184, 166, 0.1) 50%),
        radial-gradient(circle at 80% 80%, transparent 30%, rgba(15, 23, 42, 0.1) 60%);
    animation: patternShift 60s linear infinite;
}

@keyframes patternShift {
    0% { transform: translate(0, 0); }
    100% { transform: translate(50px, 50px); }
}

/* CONTENT WRAPPER */
.confirmation-content {
    max-width: 1200px;
    width: 100%;
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 60px;
}

/* HERO SECTION */
.hero-section {
    text-align: center;
    position: relative;
    margin-bottom: 20px;
}

.hero-content {
    animation: slideDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-title {
    font-size: 48px;
    font-weight: 800;
    background: linear-gradient(135deg, #14b8a6 0%, #fbbf24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 0 16px 0;
    letter-spacing: -1px;
}

.hero-subtitle {
    font-size: 20px;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
    font-weight: 400;
    letter-spacing: 0.3px;
}

.hero-accent {
    font-size: 60px;
    position: absolute;
    top: -20px;
    right: 0;
    opacity: 0.3;
    animation: float 6s ease-in-out infinite;
}

/* EXPLANATION CARDS */
.explanation-section {
    margin-top: 40px;
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    animation: staggerIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes staggerIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    padding: 32px;
    position: relative;
    overflow: hidden;
    transition: var(--transition-base);
    transform-origin: center;
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(20, 184, 166, 0.2) 0%, rgba(255, 107, 107, 0.1) 100%);
    opacity: 0;
    transition: var(--transition-base);
}

.glass-card:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 255, 255, 0.4);
    background: rgba(255, 255, 255, 0.15);
}

.glass-card:hover::before {
    opacity: 1;
}

.card-icon {
    font-size: 48px;
    margin-bottom: 16px;
    display: block;
    animation: bounce 2s ease-in-out infinite;
}

.card-1 .card-icon { animation-delay: 0s; }
.card-2 .card-icon { animation-delay: 0.2s; }
.card-3 .card-icon { animation-delay: 0.4s; }

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--color-white);
    margin: 0 0 12px 0;
    position: relative;
    z-index: 1;
}

.card-description {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.75);
    line-height: 1.6;
    margin: 0;
    position: relative;
    z-index: 1;
}

/* PROCESS FLOW SECTION */
.process-section {
    margin-top: 40px;
}

.section-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--color-white);
    margin: 0 0 40px 0;
    text-align: center;
}

.process-flow {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    align-items: flex-start;
    position: relative;
}

.process-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
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

.step-number {
    width: 60px;
    height: 60px;
    background: rgba(20, 184, 166, 0.2);
    border: 2px solid rgba(20, 184, 166, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 800;
    color: var(--color-teal);
    margin-bottom: 20px;
    transition: var(--transition-base);
    position: relative;
    z-index: 2;
}

.step-number.step-active {
    background: linear-gradient(135deg, var(--color-teal) 0%, var(--color-gold) 100%);
    color: var(--color-sapphire);
    box-shadow: 0 0 30px rgba(20, 184, 166, 0.5);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 30px rgba(20, 184, 166, 0.5); }
    50% { box-shadow: 0 0 50px rgba(20, 184, 166, 0.8); }
}

.step-icon {
    font-size: 36px;
    margin-top: 12px;
}

.step-content {
    margin-top: 16px;
}

.step-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--color-white);
    margin: 0 0 8px 0;
}

.step-description {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.6);
    margin: 0;
}

.connector {
    position: absolute;
    top: 30px;
    left: -15px;
    width: calc(100% + 30px);
    height: 2px;
    background: linear-gradient(90deg, rgba(20, 184, 166, 0.3) 0%, rgba(251, 191, 36, 0.3) 100%);
    z-index: 1;
}

.process-step:last-child .connector {
    display: none;
}

/* STATUS SECTION */
.status-section {
    margin-top: 40px;
}

.status-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 24px;
    padding: 40px;
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
}

.status-badge {
    background: rgba(255, 255, 255, 0.2);
    color: var(--color-white);
    padding: 12px 24px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* PROGRESS BAR */
.progress-container {
    margin-bottom: 40px;
}

.progress-label {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 12px;
    font-weight: 600;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--color-teal) 0%, var(--color-gold) 100%);
    border-radius: 10px;
    transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 0 15px rgba(20, 184, 166, 0.5);
}

.progress-text {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 8px;
    text-align: right;
}

/* WITNESSES TIMELINE */
.witnesses-timeline {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 40px;
}

.witness-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    transition: var(--transition-base);
}

.witness-item.witness-confirmed {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
}

.witness-avatar {
    font-size: 32px;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.witness-info {
    flex: 1;
}

.witness-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-white);
    margin: 0 0 4px 0;
}

.witness-status {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
    margin: 0;
}

.witness-status.status-confirmed {
    color: #10b981;
    font-weight: 600;
}

.witness-check {
    font-size: 24px;
    color: #10b981;
    animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.witness-pending {
    font-size: 20px;
    opacity: 0.6;
    animation: spin 2s linear infinite;
}

@keyframes popIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* NEXT STEPS */
.next-steps {
    background: rgba(20, 184, 166, 0.1);
    border: 1px solid rgba(20, 184, 166, 0.3);
    border-radius: 16px;
    padding: 24px;
}

.next-steps-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--color-white);
    margin: 0 0 12px 0;
}

.steps-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.steps-list li {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 8px;
    padding-left: 24px;
    position: relative;
}

.steps-list li:before {
    content: '→';
    position: absolute;
    left: 0;
    color: var(--color-teal);
    font-weight: 700;
}

.steps-list li:last-child {
    margin-bottom: 0;
}

/* CTA SECTION */
.cta-section {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.code-display-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 24px;
    padding: 40px;
    text-align: center;
}

.code-container {
    margin: 32px 0;
}

.code-box {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.5) 0%, rgba(30, 41, 59, 0.5) 100%);
    border: 2px dashed rgba(20, 184, 166, 0.4);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.validation-code {
    font-size: 28px;
    font-family: 'Courier New', monospace;
    color: var(--color-teal);
    font-weight: 700;
    letter-spacing: 3px;
    margin: 0;
    flex: 1;
}

.copy-btn {
    background: linear-gradient(135deg, var(--color-teal) 0%, var(--color-gold) 100%);
    color: var(--color-sapphire);
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: var(--transition-base);
    white-space: nowrap;
    box-shadow: 0 8px 24px rgba(20, 184, 166, 0.3);
}

.copy-btn:hover:not(.copied) {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(20, 184, 166, 0.4);
}

.copy-btn.copied {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}

.code-description {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 20px;
}

/* SHARE OPTIONS */
.share-options {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 24px;
    padding: 40px;
}

.share-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--color-white);
    margin: 0 0 24px 0;
    text-align: center;
}

.share-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.share-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 24px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    color: var(--color-white);
    cursor: pointer;
    transition: var(--transition-base);
    font-weight: 600;
    font-size: 14px;
}

.share-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-4px);
}

.share-icon {
    font-size: 32px;
}

.share-btn.whatsapp-btn:hover {
    background: rgba(37, 211, 102, 0.2);
    border-color: rgba(37, 211, 102, 0.4);
}

.share-btn.email-btn:hover {
    background: rgba(20, 184, 166, 0.2);
    border-color: rgba(20, 184, 166, 0.4);
}

.share-btn.sms-btn:hover {
    background: rgba(251, 191, 36, 0.2);
    border-color: rgba(251, 191, 36, 0.4);
}

/* FAQ SECTION */
.faq-section {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 24px;
    padding: 40px;
}

.faq-toggle {
    width: 100%;
    background: none;
    border: none;
    color: var(--color-white);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 18px;
    font-weight: 700;
    padding: 0;
    transition: var(--transition-base);
}

.faq-toggle:hover {
    color: var(--color-teal);
}

.faq-icon {
    display: inline-flex;
    transition: var(--transition-base);
    color: var(--color-teal);
}

.faq-icon.rotated {
    transform: rotate(90deg);
}

.faq-content {
    margin-top: 24px;
    display: grid;
    gap: 16px;
    animation: slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.faq-item {
    padding: 20px;
    background: rgba(20, 184, 166, 0.1);
    border-left: 4px solid var(--color-teal);
    border-radius: 8px;
    transition: var(--transition-base);
}

.faq-item:hover {
    background: rgba(20, 184, 166, 0.15);
}

.faq-question {
    font-size: 15px;
    font-weight: 700;
    color: var(--color-white);
    margin: 0 0 8px 0;
}

.faq-answer {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.75);
    line-height: 1.6;
    margin: 0;
}

/* ACTION BUTTONS */
.action-section {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 40px;
}

.btn {
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    border: none;
    transition: var(--transition-base);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-align: center;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--color-teal) 0%, var(--color-gold) 100%);
    color: var(--color-sapphire);
    box-shadow: 0 8px 24px rgba(20, 184, 166, 0.3);
}

.btn-primary:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(20, 184, 166, 0.4);
}

.btn-secondary {
    background: rgba(255, 107, 107, 0.2);
    color: var(--color-white);
    border: 1px solid rgba(255, 107, 107, 0.4);
}

.btn-secondary:hover {
    background: rgba(255, 107, 107, 0.3);
    border-color: rgba(255, 107, 107, 0.6);
}

/* DECORATIVE ACCENT */
.crescent-accent {
    position: fixed;
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, var(--color-coral) 0%, var(--color-gold) 100%);
    border-radius: 50%;
    top: -80px;
    right: -80px;
    opacity: 0.1;
    z-index: 1;
    animation: float 15s ease-in-out infinite;
}

/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .confirmation-shell {
        padding: 24px 16px;
    }

    .confirmation-content {
        gap: 40px;
    }

    .hero-title {
        font-size: 32px;
    }

    .hero-subtitle {
        font-size: 16px;
    }

    .cards-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .process-flow {
        grid-template-columns: 1fr;
        gap: 20px;
        gap-row: 40px;
    }

    .connector {
        display: none;
    }

    .process-step {
        padding: 20px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 16px;
    }

    .status-card {
        padding: 24px;
    }

    .status-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .code-box {
        flex-direction: column;
    }

    .validation-code {
        font-size: 20px;
    }

    .share-buttons {
        grid-template-columns: repeat(2, 1fr);
    }

    .action-section {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .confirmation-shell {
        padding: 16px;
    }

    .confirmation-content {
        gap: 32px;
    }

    .hero-title {
        font-size: 24px;
    }

    .hero-subtitle {
        font-size: 14px;
    }

    .hero-accent {
        font-size: 40px;
    }

    .section-title {
        font-size: 24px;
        margin-bottom: 24px;
    }

    .glass-card {
        padding: 20px;
    }

    .card-icon {
        font-size: 36px;
    }

    .card-title {
        font-size: 16px;
    }

    .card-description {
        font-size: 13px;
    }

    .step-number {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .step-icon {
        font-size: 28px;
    }

    .step-title {
        font-size: 16px;
    }

    .step-description {
        font-size: 12px;
    }

    .status-card {
        padding: 20px;
    }

    .code-display-card,
    .share-options,
    .faq-section {
        padding: 24px;
    }

    .code-container {
        margin: 20px 0;
    }

    .validation-code {
        font-size: 18px;
        letter-spacing: 2px;
    }

    .share-buttons {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .share-btn {
        padding: 16px;
    }

    .share-icon {
        font-size: 24px;
    }

    .share-text {
        font-size: 12px;
    }

    .faq-toggle {
        font-size: 16px;
        gap: 12px;
    }

    .btn {
        padding: 14px 24px;
        font-size: 13px;
    }

    .floating-orb {
        filter: blur(60px);
    }

    .orb-1 {
        width: 300px;
        height: 300px;
    }

    .orb-2 {
        width: 200px;
        height: 200px;
    }

    .orb-3 {
        width: 250px;
        height: 250px;
    }

    .crescent-accent {
        width: 150px;
        height: 150px;
        top: -60px;
        right: -60px;
    }
}
</style>
