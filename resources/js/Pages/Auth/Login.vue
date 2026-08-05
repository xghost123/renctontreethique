<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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

const submit = () => {
    form.post(route('login'), { onFinish: () => form.reset('password') });
};
</script>

<template>
    <Head title="Connexion — Rencontre Éthique" />

    <!-- Zawajuna-style auth shell -->
    <div class="auth-shell">
        <div class="pattern-re"></div>
        <!-- Ornaments -->
        <svg class="ornament ornament-tl" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-60a20 20 0 1 0 20 20 20 20 0 0 0-20-20z"/></svg>
        <svg class="ornament ornament-br" viewBox="0 0 100 100" fill="currentColor"><path d="M50 0a50 50 0 1 0 50 50A50 50 0 0 0 50 0zm0 90a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-60a20 20 0 1 0 20 20 20 20 0 0 0-20-20z"/></svg>

        <div class="auth-content re-fade-up">
            <!-- Logo -->
            <Link href="/" class="auth-logo">
                <div class="auth-logo-mark">
                    <svg class="logo-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5a7.5 7.5 0 0 0-7.5 7.5c0 5.2 6.2 10.6 7.5 10.6s7.5-5.4 7.5-10.6A7.5 7.5 0 0 0 12 1.5Z"/></svg>
                </div>
                <div class="auth-logo-title">Rencontre Éthique</div>
                <div class="auth-logo-arabic">حلال · مسجد · جدية</div>
            </Link>

            <div class="divider-re">
                <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.4-6.3 4.4L8 13.8 2 9.2h7.6z"/></svg>
            </div>

            <!-- Card -->
            <div class="auth-card">
                <div class="page-header">
                    <h1 class="page-title">Connexion</h1>
                    <p class="page-subtitle">Retrouvez votre profil et votre mosquée</p>
                </div>

                <div v-if="status" class="mb-4 text-sm font-medium text-emerald-600">{{ status }}</div>
                <div v-if="$page.props.flash?.error" class="server-error mb-4">
                    <svg class="error-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    {{ $page.props.flash.error }}
                </div>

                <form @submit.prevent="submit" class="form">
                    <div class="form-item">
                        <label class="field-label" for="email">Email ou téléphone</label>
                        <input id="email" v-model="form.email" type="text" required autofocus autocomplete="username" class="field-input" placeholder="vous@exemple.fr" />
                        <span v-if="form.errors.email" class="field-error">{{ form.errors.email }}</span>
                    </div>

                    <div class="form-item">
                        <label class="field-label" for="password">Mot de passe</label>
                        <div class="password-wrapper">
                            <input :id="'password'" v-model="form.password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" class="field-input" placeholder="••••••••" />
                            <button type="button" class="password-toggle" @click="showPassword = !showPassword">
                                <svg v-if="!showPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                        <span v-if="form.errors.password" class="field-error">{{ form.errors.password }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" v-model="form.remember" class="w-4 h-4 rounded border-[#E2DDD5] text-[#1C4532] focus:ring-[#1C4532]" />
                            <span class="text-xs text-[#8A9680]">Se souvenir de moi</span>
                        </label>
                        <Link v-if="canResetPassword" :href="route('password.request')" class="forgot-link">Mot de passe oublié ?</Link>
                    </div>

                    <button type="submit" class="submit-btn" :disabled="form.processing">
                        <span v-if="!form.processing">Se connecter</span>
                        <span v-else class="btn-spinner">
                            <svg class="spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10" stroke-opacity=".25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>
                            Connexion...
                        </span>
                    </button>
                </form>

                <p class="auth-link">
                    Pas encore de compte ?
                    <Link :href="route('register')" class="auth-link-cta">Créer mon profil</Link>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-shell {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fbf7f0;
    background-image: linear-gradient(135deg, #fbf7f0, #f3ebd8);
    padding: 24px 20px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    position: relative;
    overflow-x: hidden;
}
.pattern-re {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 25% 25%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px),
        radial-gradient(circle at 75% 75%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px),
        radial-gradient(circle at 75% 25%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px),
        radial-gradient(circle at 25% 75%, transparent 22px, rgba(28, 69, 50, .04) 22px, rgba(28, 69, 50, .04) 23px, transparent 23px);
    background-size: 60px 60px;
    pointer-events: none;
}
.ornament {
    position: absolute;
    width: 140px;
    height: 140px;
    color: #1c4532;
    opacity: .06;
    pointer-events: none;
}
.ornament-tl { top: -20px; left: -20px; }
.ornament-br { bottom: -20px; right: -20px; transform: rotate(180deg); }
.auth-content {
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
}
.auth-logo { display: flex; flex-direction: column; align-items: center; gap: 4px; margin-bottom: 20px; }
.auth-logo-mark {
    width: 44px; height: 44px;
    background: #1c4532;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 8px;
    box-shadow: 0 4px 12px rgba(28, 69, 50, .25);
}
.logo-icon { width: 22px; height: 22px; color: #c8a028; }
.auth-logo-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 2rem; font-weight: 500; color: #1c4532;
    letter-spacing: -.01em; line-height: 1; margin: 0;
}
.auth-logo-arabic {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: .95rem; color: #c8a028; font-weight: 400;
    letter-spacing: .08em; margin: 0;
}
.divider-re { display: flex; align-items: center; gap: 10px; width: 100%; max-width: 200px; margin-bottom: 24px; color: #c8a028; }
.divider-re::before, .divider-re::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(to right, transparent, rgba(200, 160, 40, .5), transparent);
}
.auth-card {
    width: 100%;
    background: #fff;
    border-radius: 20px;
    padding: 32px 28px;
    box-shadow: 0 1px 2px rgba(28, 69, 50, .04), 0 4px 16px rgba(28, 69, 50, .08), 0 16px 40px rgba(28, 69, 50, .06);
    border: 1px solid rgba(28, 69, 50, .08);
}
.page-title { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.6rem; font-weight: 500; color: #1c4532; margin: 0 0 4px; line-height: 1.2; }
.page-subtitle { font-size: .82rem; color: #8a9680; margin: 0 0 20px; }
.form { display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px; }
.form-item { display: flex; flex-direction: column; gap: 6px; }
.field-label { font-size: .78rem; font-weight: 500; color: #3d4a3e; letter-spacing: .03em; text-transform: uppercase; }
.field-input {
    height: 44px; border-radius: 10px; border: 1.5px solid #E2DDD5;
    background: #fafaf8; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .9rem; color: #1c1c1c; padding: 0 12px; width: 100%;
    transition: border-color .15s, box-shadow .15s, background .15s;
}
.field-input:focus { outline: none; border-color: #1c4532; box-shadow: 0 0 0 3px rgba(28, 69, 50, .1); background: #fff; }
.field-error { font-size: .75rem; color: #dc2626; margin-top: 2px; }
.password-wrapper { position: relative; }
.password-wrapper .field-input { padding-right: 44px; }
.forgot-link { font-size: .75rem; font-weight: 500; color: #c8a028; text-decoration: none; }
.forgot-link:hover { text-decoration: underline; }
.password-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    width: 20px; height: 20px; color: #9ca3af; background: none; border: none;
    cursor: pointer; padding: 0; display: flex; align-items: center;
}
.password-toggle:hover { color: #1c4532; }
.server-error {
    display: flex; align-items: center; gap: 8px; padding: 10px 14px;
    background: #fef2f2; border: 1px solid #FECACA; border-radius: 10px;
    font-size: .82rem; color: #dc2626;
}
.error-icon { width: 16px; height: 16px; flex-shrink: 0; }
.submit-btn {
    width: 100%; height: 48px; background: #1c4532; color: #fff;
    border: none; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .9rem; font-weight: 600; letter-spacing: .02em; cursor: pointer;
    transition: background .15s, transform .1s, box-shadow .15s;
    box-shadow: 0 2px 8px rgba(28, 69, 50, .25); margin-top: 4px;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.submit-btn:hover:not(:disabled) { background: #163828; box-shadow: 0 4px 16px rgba(28, 69, 50, .35); }
.submit-btn:active:not(:disabled) { transform: scale(.98); }
.submit-btn:disabled { opacity: .65; cursor: not-allowed; }
.btn-spinner { display: flex; align-items: center; gap: 8px; }
.spinner { width: 18px; height: 18px; animation: spin .8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.auth-link { text-align: center; font-size: .82rem; color: #8a9680; margin: 0; }
.auth-link-cta { color: #c8a028; font-weight: 600; text-decoration: none; margin-left: 4px; }
.auth-link-cta:hover { text-decoration: underline; }
</style>
