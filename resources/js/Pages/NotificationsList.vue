<template>
    <authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Notifications
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Action Bar -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-600 dark:text-gray-400">
                                Unread: <strong>{{ unreadCount }}</strong>
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button
                                v-if="unreadCount > 0"
                                @click="markAllAsRead"
                                :disabled="actionInProgress"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition-colors font-medium text-sm"
                            >
                                Mark All as Read
                            </button>
                            <button
                                v-if="notifications.length > 0"
                                @click="deleteAll"
                                :disabled="actionInProgress"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-400 transition-colors font-medium text-sm"
                            >
                                Delete All
                            </button>
                            <router-link
                                to="/notifications/settings"
                                class="px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors font-medium text-sm"
                            >
                                Settings
                            </router-link>
                        </div>
                    </div>
                </div>

                <!-- Notifications List -->
                <div v-if="loading" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Loading notifications...</p>
                </div>

                <div v-else-if="notifications.length > 0" class="space-y-4">
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow"
                        :class="{ 'border-l-4 border-green-500': !notification.read }"
                    >
                        <div class="p-6 flex items-start gap-4">
                            <!-- Icon -->
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center"
                                :class="getIconBackgroundClass(notification.color)"
                            >
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <template v-if="notification.icon === 'heart'">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                    </template>
                                    <template v-else-if="notification.icon === 'envelope'">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    </template>
                                    <template v-else-if="notification.icon === 'check'">
                                        <polyline points="20 6 9 17 4 12" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </template>
                                    <template v-else-if="notification.icon === 'eye'">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </template>
                                    <template v-else>
                                        <circle cx="12" cy="12" r="10" />
                                    </template>
                                </svg>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ notification.title }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                                            {{ notification.message }}
                                        </p>
                                    </div>
                                    <!-- Unread Indicator -->
                                    <div v-if="!notification.read" class="flex-shrink-0 ml-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            New
                                        </span>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">
                                    {{ formatDate(notification.created_at) }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex-shrink-0 flex gap-2">
                                <button
                                    @click="toggleRead(notification)"
                                    :disabled="actionInProgress"
                                    :title="notification.read ? 'Mark as unread' : 'Mark as read'"
                                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors disabled:opacity-50"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </button>
                                <button
                                    @click="deleteNotification(notification)"
                                    :disabled="actionInProgress"
                                    title="Delete"
                                    class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">No notifications yet</p>
                    <router-link
                        to="/home"
                        class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium"
                    >
                        Back to Home
                    </router-link>
                </div>
            </div>
        </div>
    </authenticated-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from 'axios'

const notifications = ref([])
const loading = ref(true)
const actionInProgress = ref(false)
const unreadCount = ref(0)

const getIconBackgroundClass = (color) => {
    const classes = {
        'success': 'bg-green-600',
        'info': 'bg-blue-600',
        'warning': 'bg-yellow-600',
        'danger': 'bg-red-600',
        'default': 'bg-gray-600'
    }
    return classes[color] || classes['default']
}

const formatDate = (date) => {
    return new Date(date).toLocaleString()
}

const fetchNotifications = async () => {
    try {
        const response = await axios.get('/notifications')
        notifications.value = response.data.notifications.data || response.data.notifications
        unreadCount.value = response.data.unread_count
        loading.value = false
    } catch (error) {
        console.error('Failed to fetch notifications:', error)
        loading.value = false
    }
}

const toggleRead = async (notification) => {
    try {
        actionInProgress.value = true
        const endpoint = notification.read ? 'unread' : 'read'
        await axios.post(`/notifications/${notification.id}/${endpoint}`)
        
        notification.read = !notification.read
        unreadCount.value = notifications.value.filter(n => !n.read).length
    } catch (error) {
        console.error('Failed to toggle notification:', error)
    } finally {
        actionInProgress.value = false
    }
}

const deleteNotification = async (notification) => {
    try {
        actionInProgress.value = true
        await axios.delete(`/notifications/${notification.id}`)
        notifications.value = notifications.value.filter(n => n.id !== notification.id)
        unreadCount.value = notifications.value.filter(n => !n.read).length
    } catch (error) {
        console.error('Failed to delete notification:', error)
    } finally {
        actionInProgress.value = false
    }
}

const markAllAsRead = async () => {
    try {
        actionInProgress.value = true
        await axios.post('/notifications/mark-all-as-read')
        notifications.value.forEach(n => n.read = true)
        unreadCount.value = 0
    } catch (error) {
        console.error('Failed to mark all as read:', error)
    } finally {
        actionInProgress.value = false
    }
}

const deleteAll = async () => {
    if (!confirm('Delete all notifications? This cannot be undone.')) return

    try {
        actionInProgress.value = true
        await axios.delete('/notifications')
        notifications.value = []
        unreadCount.value = 0
    } catch (error) {
        console.error('Failed to delete all notifications:', error)
    } finally {
        actionInProgress.value = false
    }
}

onMounted(() => {
    fetchNotifications()
})
</script>
