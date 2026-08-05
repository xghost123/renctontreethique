<script setup>
import Checkbox from '../../Components/Checkbox.vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import InputError from '../../Components/InputError.vue';
import InputLabel from '../../Components/InputLabel.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import TextInput from '../../Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from "vue";
import ApplicationLogo from '../../Components/ApplicationLogo.vue';


defineProps({
    translations: {
        type: Object,
    },
    locale: {
        type: String,
    },
    locales: {
        type: Array,
    },
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

// initializing
const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('backend.login.post'), {
        onFinish: () => form.reset('password'),
        // onSuccess: (response) => form.reset('password'),
    });
};

// const loginWithGoogle = () => {
//     window.location.href = route('googleRedirect');
// };

// const loginWithFacebook = () => {
//     window.location.href = route('facebookRedirect');
// };

document.body.classList.remove(...document.body.classList);
document.body.classList.add("backend.login");

</script>

<template>

    <Head title="Connexion admin — Rencontre Éthique" />

    <AdminLayout :translations :locale :locales>

        <div class="relative flex min-h-[80vh] flex-col items-center justify-center sm:pt-0 px-4 py-12">
            <div class="pattern-re absolute inset-0 opacity-60 pointer-events-none"></div>

            <div class="relative w-full max-w-md card-re overflow-hidden shadow-xl">

                <!-- Brand top band -->
                <div class="bg-[#0D2218] px-8 pt-10 pb-8 text-center relative overflow-hidden">
                    <div class="pattern-re absolute inset-0 opacity-[.12] pointer-events-none"></div>
                    <div class="relative">
                        <div class="inline-flex w-14 h-14 rounded-2xl bg-[#0f3a7d] border border-[#ff6b6b]/30 items-center justify-center mb-4 shadow-lg" style="box-shadow: 0 6px 20px rgba(0,0,0,.35)">
                            <svg class="w-7 h-7 text-[#E4B84A]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5a7.5 7.5 0 0 0-7.5 7.5c0 5.2 6.2 10.6 7.5 10.6s7.5-5.4 7.5-10.6A7.5 7.5 0 0 0 12 1.5Z"/></svg>
                        </div>
                        <h2 class="font-display text-2xl font-medium text-[#FBF7F0]" style="font-family:'Cormorant Garamond',serif">Rencontre Éthique</h2>
                        <div class="text-[10px] uppercase tracking-[.22em] text-[#ff6b6b] mt-1">Espace administration</div>
                    </div>
                </div>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                    {{ status }}
                </div>

                <div v-if="$page.props.flash.error" class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <div class="alert-body">
                        {{ $page.props.flash.error }}
                    </div>
                </div>

                <form @submit.prevent="submit" class="px-8 py-8">
                    <div>
                        <InputLabel for="email" :value="translations.login.login_email_title" class="field-label-re" />

                        <TextInput id="email" type="text" class="mt-1 block w-full field-input-re" v-model="form.email" required
                            autofocus autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-5 relative">
                        <InputLabel for="password" :value="translations.login.login_password_title" class="field-label-re" />

                        <div class="flex items-center relative">
                            <TextInput v-if="showPassword" id="password" type="text" class="mt-1 block w-full field-input-re" v-model="form.password" required autocomplete="current-password" />
                            <TextInput v-else id="password" type="password" class="mt-1 block w-full field-input-re" v-model="form.password" required autocomplete="current-password" />
                            <span class="button cursor-pointer focus:outline-none absolute right-2 bottom-2.5" @click="showPassword=!showPassword">
                                <span class="icon is-small is-right text-[#8A9680]">
                                    <i class="fas" :class="{ 'fa-eye': showPassword, 'fa-eye-slash': !showPassword }"></i>
                                </span>
                            </span>
                        </div>

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4 block">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">
                                {{ translations.login.login_saved }}
                            </span>
                        </label>
                    </div>

                    <div class="mt-6">
                        <PrimaryButton class="w-full justify-center !bg-[#0f3a7d] hover:!bg-[#163828] !rounded-xl !py-3.5 !text-sm" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            {{ translations.login.login_button_text }}
                        </PrimaryButton>
                    </div>

                </form>


            <!-- <div class="mt-4 flex items-center justify-center">
                    <button @click="loginWithGoogle"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <svg width="20px" height="20px" viewBox="-3 0 262 262" xmlns="http://www.w3.org/2000/svg"
                            preserveAspectRatio="xMidYMid">
                            <path
                                d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"
                                fill="#4285F4" />
                            <path
                                d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"
                                fill="#34A853" />
                            <path
                                d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"
                                fill="#FBBC05" />
                            <path
                                d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"
                                fill="#EB4335" />
                        </svg>
                        <span class="ml-1">Login With Google</span>
                    </button>
                </div>

                <div class="mt-4 flex items-center justify-center">
                    <button @click="loginWithFacebook"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <svg width="20px" height="20px" viewBox="0 0 48 48" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Color-" transform="translate(-200.000000, -160.000000)" fill="#4460A0">
                                    <path
                                        d="M225.638355,208 L202.649232,208 C201.185673,208 200,206.813592 200,205.350603 L200,162.649211 C200,161.18585 201.185859,160 202.649232,160 L245.350955,160 C246.813955,160 248,161.18585 248,162.649211 L248,205.350603 C248,206.813778 246.813769,208 245.350955,208 L233.119305,208 L233.119305,189.411755 L239.358521,189.411755 L240.292755,182.167586 L233.119305,182.167586 L233.119305,177.542641 C233.119305,175.445287 233.701712,174.01601 236.70929,174.01601 L240.545311,174.014333 L240.545311,167.535091 C239.881886,167.446808 237.604784,167.24957 234.955552,167.24957 C229.424834,167.24957 225.638355,170.625526 225.638355,176.825209 L225.638355,182.167586 L219.383122,182.167586 L219.383122,189.411755 L225.638355,189.411755 L225.638355,208 L225.638355,208 Z"
                                        id="Facebook">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span class="ml-1">Login With Facebook</span>
                    </button>
                </div> -->

            </div>
        </div>

    </AdminLayout>

</template>
