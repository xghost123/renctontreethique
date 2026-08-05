<script setup>
import Homepage from './Frontend/Homepage.vue'

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
    districts: {
        type: Object,
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    single_biodata: {
        type: Object,
    },
});
</script>

<template>
    <Homepage :translations :locale :districts />
</template>
