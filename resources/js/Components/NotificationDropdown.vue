<template>
    <div class="relative">
        <!-- Bell Icon Button -->
        <button
            @click="toggleDropdown"
            class="relative p-2 text-gray-600 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 transition-colors"
            :class="{ 'text-green-600 dark:text-green-400': isOpen }"
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
            </svg>
            <!-- Unread badge -->
            <span
                v-if="unreadCount > 0"
                class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold"
            >
                {{ unreadCount > 99 ? '99+' : unreadCount }}
            </span>
        </button>

        <!-- Dropdown Menu -->
        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-96 max-h-96 overflow-y-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
        >
            <!-- Header -->
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Notifications
                    </h3>
                    <button
                        v-if="notifications.length > 0"
                        @click="markAllAsRead"
                        class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300"
                    >
                        Mark all as read
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div v-if="notifications.length > 0" class="divide-y divide-gray-200 dark:divide-gray-700">
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    @click="selectNotification(notification)"
                    class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                    :class="{ 'bg-green-50 dark:bg-green-900/20': !notification.read }"
                >
                    <div class="flex items-start gap-3">
                        <!-- Icon -->
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                            :class="getIconBackgroundClass(notification.color)"
                        >
                            <svg
                                class="w-5 h-5 text-white"
                                :class="`icon-${notification.icon}`"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <!-- Icon rendering based on type -->
                                <template v-if="notification.icon === 'heart'">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </template>
                                <template v-else-if="notification.icon === 'envelope'">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22 6 12 13 2 6" fill="none" stroke="white" stroke-width="2" />
                                </template>
                                <template v-else-if="notification.icon === 'check'">
                                    <polyline points="20 6 9 17 4 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                        {{ notification.title }}
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1 line-clamp-2">
                                        {{ notification.message }}
                                    </p>
                                </div>
                                <!-- Unread indicator -->
                                <div v-if="!notification.read" class="flex-shrink-0 ml-2">
                                    <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ formatDate(notification.created_at) }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex-shrink-0 flex gap-1">
                            <button
                                @click.stop="toggleRead(notification)"
                                class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                :title="notification.read ? 'Mark as unread' : 'Mark as read'"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </button>
                            <button
                                @click.stop="deleteNotification(notification)"
                                class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                title="Delete"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No notifications yet</p>
            </div>

            <!-- Footer -->
            <div v-if="notifications.length > 0" class="border-t border-gray-200 dark:border-gray-700 p-4">
                <router-link
                    to="/notifications/settings"
                    class="block text-center text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium"
                >
                    Notification Settings →
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import axios from 'axios'

const isOpen = ref(false)
const notifications = ref([])
const unreadCount = ref(0)
let pollInterval = null

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
    const now = new Date()
    const notifDate = new Date(date)
    const diff = Math.floor((now - notifDate) / 1000)

    if (diff < 60) return 'just now'
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`
    if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`
    
    return notifDate.toLocaleDateString()
}

const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

const fetchNotifications = async () => {
    try {
        const response = await axios.get('/notifications/unread')
        notifications.value = response.data.notifications
        unreadCount.value = response.data.count
    } catch (error) {
        console.error('Failed to fetch notifications:', error)
    }
}

const toggleRead = async (notification) => {
    try {
        const endpoint = notification.read ? 'unread' : 'read'
        await axios.post(`/notifications/${notification.id}/${endpoint}`)
        
        notification.read = !notification.read
        unreadCount.value = notifications.value.filter(n => !n.read).length
    } catch (error) {
        console.error('Failed to toggle notification read status:', error)
    }
}

const deleteNotification = async (notification) => {
    try {
        await axios.delete(`/notifications/${notification.id}`)
        notifications.value = notifications.value.filter(n => n.id !== notification.id)
        unreadCount.value = notifications.value.filter(n => !n.read).length
    } catch (error) {
        console.error('Failed to delete notification:', error)
    }
}

const markAllAsRead = async () => {
    try {
        await axios.post('/notifications/mark-all-as-read')
        notifications.value.forEach(n => n.read = true)
        unreadCount.value = 0
    } catch (error) {
        console.error('Failed to mark all as read:', error)
    }
}

const selectNotification = (notification) => {
    if (!notification.read) {
        toggleRead(notification)
    }
}

const closeDropdown = (event) => {
    const dropdown = document.querySelector('[class*="relative"]')
    if (dropdown && !dropdown.contains(event.target)) {
        isOpen.value = false
    }
}

onMounted(() => {
    fetchNotifications()
    
    // Poll for new notifications every 30 seconds
    pollInterval = setInterval(fetchNotifications, 30000)
    
    // Close dropdown when clicking outside
    document.addEventListener('click', closeDropdown)
})

onBeforeUnmount(() => {
    if (pollInterval) {
        clearInterval(pollInterval)
    }
    document.removeEventListener('click', closeDropdown)
})
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
