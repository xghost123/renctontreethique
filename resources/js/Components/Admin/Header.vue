<script setup>

import { onMounted } from "vue";
import { Link } from '@inertiajs/vue3';


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

onMounted(() => {
    // smooth sticky header
    let element = document.querySelector('#main_header');
    let element2 = document.querySelector('#main_header_inner');
    let isPositionSticky = (element.style.position == 'sticky');

    window.addEventListener("scroll", function (e) {
        e.preventDefault();
        if (window.scrollY > 0 && !isPositionSticky) {
            element.classList.add("amimate_header");
            element.classList.remove("header_backgound");
            element2.classList.add("header_transparent_background");
        } else {
            element.classList.add("header_backgound");
            element.classList.remove("amimate_header");
            element2.classList.remove("header_transparent_background");
        }
    });

    const trigger = document.querySelector(".od-mobile-menu-trigger");
    if (trigger) {
        trigger.addEventListener("click", function (e) {
            e.preventDefault();
            const menuContainer = document.querySelector(".menu-lists-container");
            menuContainer.classList.toggle("active");
            document.addEventListener("click", function (e) {
                if (!menuContainer.contains(e.target) && !trigger.contains(e.target)) {
                    menuContainer.classList.remove("active");
                }
            });
        });
    }
});

const localeLabel = (l) => ({ fr: 'Français', en: 'English' }[l] || l);
</script>

<template>


    <header id="main_header" class="header_backgound">
        <div id="od_header">
            <div id="main_header_inner" class="od-w-100">
                <div class="main-container">
                    <div class="od-row od-align-items-center">
                        <div class="od-col-4 od-col-md-3 md-order-2">
                            <div class="od-site-logo">
                                <Link href="/admin" class="re-admin-brand">
                                    <div class="re-admin-brand-mark">
                                        <svg class="w-5 h-5 text-[#E4B84A]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5a7.5 7.5 0 0 0-7.5 7.5c0 5.2 6.2 10.6 7.5 10.6s7.5-5.4 7.5-10.6A7.5 7.5 0 0 0 12 1.5Z"/></svg>
                                    </div>
                                    <div class="re-admin-brand-text">
                                        <span class="re-admin-brand-name">Rencontre Éthique</span>
                                        <span class="re-admin-brand-sub">Administration</span>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div class="od-col-4 od-col-md-6 md-order-1">
                            <div class="od-mobile-menu-trigger">
                                <a href="#"><i class="fa fa-bars"></i></a>
                            </div>
                            <nav class="menu-lists-container">
                                <ul class="od-menu-lists ">
                                    <li class="od-menu-list-item od-localization-container hide-od-xl">
                                        <a href="javascript:void(0);">
                                            <div class="od-localization-content od-display-flex od-align-items-center">
                                                <div class="od-icon"><i class="fa fa-language"></i></div>
                                                <div class="od-selected-language">
                                                    <span>{{ localeLabel(props.locale) }}</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="od-dropdown-menu-container od-animate od-slideIn">
                                            <div class="dropdown-menu-content">
                                                <ul class="dropdown-menu-lists">
                                                    <li v-for="lang in locales" :key="lang.id">
                                                        <Link v-if="lang != locale" :href="route('localization', lang)">
                                                        {{ localeLabel(lang) }}
                                                        </Link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="od-menu-list-item od-localization-container hide-od-xl">
                                        <a href="javascript:void(0);">
                                            <Link :href="route('backend.logout')" method="post" as="button" class="flex items-center justify-center md:ml-4">
                                                <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                                                Déconnexion
                                            </Link>
                                        </a>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                        <div class="od-col-4 od-col-md-3 md-order-3">
                            <div class="">
                                <div class="od-menu-lists od-display-flex od-align-items-center od-justify-content-end">
                                    <div class="od-menu-list-item">
                                        <div class="od-localization-container hide-od">
                                            <a href="javascript:void(0);">
                                                <div
                                                    class="od-localization-content od-display-flex od-align-items-center">
                                                    <div class="od-icon"><i class="fa fa-language"></i></div>
                                                    <div class="od-selected-language">
                                                        <span>{{ localeLabel(props.locale) }}</span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="od-dropdown-menu-container od-animate od-slideIn">
                                                <div class="dropdown-menu-content">
                                                    <ul class="dropdown-menu-lists">
                                                        <li v-for="lang in locales" :key="lang.id" class="">
                                                            <Link v-if="lang != locale"
                                                                :href="route('localization', lang)">
                                                            {{ localeLabel(lang) }}
                                                            </Link>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="od-menu-list-item hide-od">
                                        <Link :href="route('backend.logout')" method="post" as="button" class="flex items-center justify-center md:ml-4">
                                            <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                                            Déconnexion
                                        </Link>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

</template>

<style>
header{
    overflow: visible;
}
.header_transparent_background{
    position: relative;
}
.header_transparent_background::before{
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #0D2218;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    z-index: -999;
}
.header_backgound{
    background-color: #0D2218;
}
.amimate_header{
    position: sticky !important;
    top: 0;
    z-index: 999 !important;
}
.logout_image{
    background: #fef1f6;
    border-radius: 4px;
    width: 25px;
    height: 25px;
    filter: grayscale(100%);
    transition: filter .29s ease 0s;
    padding: 5px;
    border-radius: 4px;
    object-fit: contain;
    object-position: center;
}

/* ═══ Brand header ═══ */
.re-admin-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}
.re-admin-brand-mark {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #0f3a7d;
    border: 1px solid rgba(200, 160, 40, .3);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,.3);
}
.re-admin-brand-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}
.re-admin-brand-name {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.05rem;
    font-weight: 600;
    color: #FBF7F0;
    letter-spacing: .01em;
}
.re-admin-brand-sub {
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: .22em;
    color: #C8A028;
    margin-top: 2px;
}
</style>
