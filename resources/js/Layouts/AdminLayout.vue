<script setup>

import Footer from '../Components/Admin/Footer.vue';
import { Head } from '@inertiajs/vue3';


const props = defineProps({
    translations: {
        type: Object,
    },
    locale: {
        type: String,
    },
    locales: {
        type: Array,
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});


function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}


</script>

<template>


    <Head>
        <link rel="stylesheet" :href="`/assets/css/frontend/frontend.css`">
        <link rel="stylesheet" :href="`/assets/css/frontend/font-awesome.min.css`">
        <link rel="stylesheet" :href="`/assets/css/backend/dataTables.min.css`">
    </Head>

    <div class="shadibari-container-main re-admin-shell">

        <slot />

        <Footer />
    </div>
</template>

<style>
/* ═══ Rencontre Éthique — legacy admin chrome ═══ */
.re-admin-shell {
    min-height: 100vh;
    background:
        radial-gradient(circle at 15% 10%, rgba(28, 69, 50, .05) 0, transparent 45%),
        radial-gradient(circle at 85% 90%, rgba(200, 160, 40, .06) 0, transparent 40%),
        var(--re-cream, #FBF7F0);
}

/* override the legacy peach header with the brand deep green */
.re-admin-shell .header_backgound,
.re-admin-shell .header_transparent_background::before {
    background-color: #0D2218;
}

.re-admin-shell #main_header {
    border-bottom: 1px solid rgba(200, 160, 40, .25);
}

.re-admin-shell .od-menu-list-item a,
.re-admin-shell .od-selected-language,
.re-admin-shell .text-gray-950 {
    color: #FBF7F0 !important;
}

.re-admin-shell .od-mobile-menu-trigger a {
    color: #FBF7F0 !important;
}

.re-admin-shell .od-dropdown-menu-content,
.re-admin-shell .dropdown-menu-content {
    background: #0D2218;
    border: 1px solid rgba(200, 160, 40, .3);
    border-radius: 12px;
}

.re-admin-shell .dropdown-menu-lists a {
    color: #C8A028 !important;
}

.re-admin-shell .logout_image {
    background: rgba(200, 160, 40, .15);
    filter: none;
}
</style>
