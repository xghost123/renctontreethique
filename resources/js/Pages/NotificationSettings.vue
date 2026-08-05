<template>
    <authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Notification Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Loading State -->
                <div v-if="loading" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Loading preferences...</p>
                </div>

                <!-- Settings Form -->
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="savePreferences" class="p-6">
                        <!-- Email Frequency -->
                        <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Email Frequency
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_frequency"
                                        type="radio"
                                        value="immediate"
                                        class="w-4 h-4 text-green-600 dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Immediate - Get notifications right away
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_frequency"
                                        type="radio"
                                        value="daily"
                                        class="w-4 h-4 text-green-600 dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Daily - Get a daily digest
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_frequency"
                                        type="radio"
                                        value="weekly"
                                        class="w-4 h-4 text-green-600 dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Weekly - Get a weekly summary
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Email Preferences -->
                        <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Email Notifications
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_proposal_created"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        New proposal received
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_proposal_response"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Proposal accepted or declined
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_message_received"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        New message received
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_profile_approved"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Profile approved by admin
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.email_profile_viewed"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Profile viewed by someone
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- In-App Preferences -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                In-App Notifications
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.inapp_proposal_created"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        New proposal received
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.inapp_proposal_response"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Proposal accepted or declined
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.inapp_message_received"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        New message received
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.inapp_profile_approved"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Profile approved by admin
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="preferences.inapp_profile_viewed"
                                        type="checkbox"
                                        class="w-4 h-4 text-green-600 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                        Profile viewed by someone
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <button
                                type="submit"
                                :disabled="saving"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors font-medium"
                            >
                                {{ saving ? 'Saving...' : 'Save Preferences' }}
                            </button>
                            <button
                                type="button"
                                @click="resetPreferences"
                                class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-700 transition-colors font-medium"
                            >
                                Reset to Defaults
                            </button>
                        </div>

                        <!-- Success Message -->
                        <div
                            v-if="successMessage"
                            class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg text-green-700 dark:text-green-300"
                        >
                            {{ successMessage }}
                        </div>

                        <!-- Error Message -->
                        <div
                            v-if="errorMessage"
                            class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg text-red-700 dark:text-red-300"
                        >
                            {{ errorMessage }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </authenticated-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from 'axios'

const preferences = ref({})
const loading = ref(true)
const saving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const defaultPreferences = {
    email_proposal_created: true,
    email_message_received: true,
    email_profile_approved: true,
    email_proposal_response: true,
    email_profile_viewed: false,
    inapp_proposal_created: true,
    inapp_message_received: true,
    inapp_profile_approved: true,
    inapp_proposal_response: true,
    inapp_profile_viewed: true,
    email_frequency: 'immediate',
}

const fetchPreferences = async () => {
    try {
        const response = await axios.get('/notifications/settings/preferences')
        preferences.value = response.data
        loading.value = false
    } catch (error) {
        console.error('Failed to fetch preferences:', error)
        errorMessage.value = 'Failed to load preferences'
        loading.value = false
    }
}

const savePreferences = async () => {
    saving.value = true
    errorMessage.value = ''
    successMessage.value = ''

    try {
        await axios.post('/notifications/settings/preferences', preferences.value)
        successMessage.value = 'Preferences saved successfully!'
        setTimeout(() => {
            successMessage.value = ''
        }, 3000)
    } catch (error) {
        console.error('Failed to save preferences:', error)
        errorMessage.value = error.response?.data?.message || 'Failed to save preferences'
    } finally {
        saving.value = false
    }
}

const resetPreferences = () => {
    if (confirm('Reset all preferences to defaults?')) {
        preferences.value = { ...defaultPreferences }
    }
}

onMounted(() => {
    fetchPreferences()
})
</script>
