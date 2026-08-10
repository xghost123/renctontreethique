<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Analytics Dashboard</h1>
        <p class="text-blue-200">Track your profile views, interactions, and engagement</p>
      </div>

      <!-- Date Range Selector -->
      <div class="mb-6 flex gap-3">
        <button
          v-for="range in dateRanges"
          :key="range.days"
          @click="selectedDays = range.days"
          :class="[
            'px-4 py-2 rounded-lg font-medium transition-all duration-300',
            selectedDays === range.days
              ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-500/50'
              : 'bg-white/10 text-blue-200 hover:bg-white/20'
          ]"
        >
          {{ range.label }}
        </button>
      </div>

      <!-- Key Metrics Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
          title="Profile Views"
          :value="summary.thisMonth?.views || 0"
          :trend="calculateTrend(summary.thisMonth?.views, summary.lastMonth?.views)"
          color="sapphire"
          icon="eye"
          unit="views"
        />
        <StatCard
          title="Likes Received"
          :value="summary.thisMonth?.likes || 0"
          :trend="calculateTrend(summary.thisMonth?.likes, summary.lastMonth?.likes)"
          color="coral"
          icon="heart"
          unit="likes"
        />
        <StatCard
          title="Messages"
          :value="messageStats.total || 0"
          :trend="calculateTrend(messageStats.total, messageStats.previous_period)"
          color="teal"
          icon="message"
          unit="messages"
        />
        <StatCard
          title="Profile Completion"
          :value="summary.profile_completion || 0"
          :trend="0"
          color="emerald"
          icon="check"
          unit="%"
        />
      </div>

      <!-- Profile Views Trend -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 mb-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">Profile Views Trend</h2>
          <span class="text-blue-300">Last {{ selectedDays }} days</span>
        </div>
        <LineChart :data="profileViewsTrend" :days="selectedDays" />
      </div>

      <!-- Two Column Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Likes Distribution -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl">
          <h2 class="text-2xl font-bold text-white mb-6">Likes Distribution</h2>
          <PieChart :data="likesBreakdown" />
        </div>

        <!-- Message Volume -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl">
          <h2 class="text-2xl font-bold text-white mb-6">Messages (Sent vs Received)</h2>
          <div class="flex items-end justify-center gap-8 h-64">
            <div class="flex flex-col items-center">
              <div class="w-16 bg-gradient-to-t from-blue-500 to-blue-600 rounded-lg mb-3" :style="{ height: messageSentHeight + 'px' }"></div>
              <p class="text-blue-200 font-medium">Sent</p>
              <p class="text-white text-lg font-bold">{{ messageStats.sent }}</p>
            </div>
            <div class="flex flex-col items-center">
              <div class="w-16 bg-gradient-to-t from-coral-500 to-coral-600 rounded-lg mb-3" :style="{ height: messageReceivedHeight + 'px' }"></div>
              <p class="text-blue-200 font-medium">Received</p>
              <p class="text-white text-lg font-bold">{{ messageStats.received }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Proposals & Heatmap -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Proposal Funnel -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl">
          <h2 class="text-2xl font-bold text-white mb-6">Proposal Funnel</h2>
          <div class="space-y-4">
            <FunnelBar
              label="Sent"
              :value="proposalStats.funnel?.sent || 0"
              :max="proposalStats.funnel?.sent || 1"
              color="bg-blue-500"
            />
            <FunnelBar
              label="Accepted"
              :value="proposalStats.funnel?.accepted || 0"
              :max="proposalStats.funnel?.sent || 1"
              color="bg-emerald-500"
            />
            <FunnelBar
              label="Rejected"
              :value="proposalStats.funnel?.rejected || 0"
              :max="proposalStats.funnel?.sent || 1"
              color="bg-red-500"
            />
            <div class="pt-4 border-t border-white/20 mt-4">
              <p class="text-blue-200 text-sm mb-2">Acceptance Rate</p>
              <p class="text-3xl font-bold text-white">{{ proposalStats.acceptance_rate || 0 }}%</p>
            </div>
          </div>
        </div>

        <!-- Activity Heatmap -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl">
          <h2 class="text-2xl font-bold text-white mb-6">Activity Heatmap</h2>
          <Heatmap :data="heatmapData" />
        </div>
      </div>

      <!-- Demographics -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Age Distribution -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl">
          <h2 class="text-2xl font-bold text-white mb-6">Viewer Age Distribution</h2>
          <BarChart :data="demographicsData.ageDistribution" label="Viewers" />
        </div>

        <!-- Top Locations -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl">
          <h2 class="text-2xl font-bold text-white mb-6">Top Viewer Locations</h2>
          <div class="space-y-3">
            <div v-for="(count, location) in demographicsData.locationDistribution" :key="location" class="flex items-center justify-between">
              <span class="text-blue-200">{{ location }}</span>
              <div class="flex items-center gap-2">
                <div class="w-48 h-2 bg-white/10 rounded-full overflow-hidden">
                  <div
                    class="h-full bg-gradient-to-r from-teal-500 to-teal-400 transition-all duration-500"
                    :style="{ width: ((count / maxLocationCount) * 100) + '%' }"
                  ></div>
                </div>
                <span class="text-white font-semibold w-8 text-right">{{ count }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Info -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <InfoCard
          title="Messages Sent"
          :value="messageStats.sent || 0"
          subtitle="This month"
          icon="send"
        />
        <InfoCard
          title="Messages Received"
          :value="messageStats.received || 0"
          subtitle="This month"
          icon="inbox"
        />
        <InfoCard
          title="Unique Conversations"
          :value="messageStats.conversations || 0"
          subtitle="Active chats"
          icon="users"
        />
      </div>

      <!-- Footer -->
      <div class="mt-12 text-center text-blue-300 text-sm">
        <p>Data updates every hour • Last updated: {{ lastUpdated }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import StatCard from '../../Components/Analytics/StatCard.vue'
import LineChart from '../../Components/Analytics/LineChart.vue'
import PieChart from '../../Components/Analytics/PieChart.vue'
import Heatmap from '../../Components/Analytics/Heatmap.vue'
import BarChart from '../../Components/Analytics/BarChart.vue'
import FunnelBar from '../../Components/Analytics/FunnelBar.vue'
import InfoCard from '../../Components/Analytics/InfoCard.vue'

const selectedDays = ref(30)
const lastUpdated = ref(new Date().toLocaleTimeString())

const dateRanges = [
  { label: 'Last 7 days', days: 7 },
  { label: 'Last 30 days', days: 30 },
  { label: 'Last 90 days', days: 90 },
]

const summary = ref({
  thisMonth: { views: 0, likes: 0, messages: 0, proposals_sent: 0, proposals_accepted: 0 },
  lastMonth: { views: 0, likes: 0 },
  profile_completion: 0,
})

const profileViewsTrend = ref({})
const likesBreakdown = ref({})
const messageStats = ref({ sent: 0, received: 0, total: 0, previous_period: 0, conversations: 0, trend: {} })
const proposalStats = ref({ sent: 0, received: 0, accepted: 0, acceptance_rate: 0, funnel: {}, average_response_time: null })
const heatmapData = ref({})
const demographicsData = ref({ ageDistribution: {}, locationDistribution: {} })

const maxLocationCount = computed(() => {
  const values = Object.values(demographicsData.value.locationDistribution)
  return values.length > 0 ? Math.max(...values) : 1
})

const messageSentHeight = computed(() => {
  const max = Math.max(messageStats.value.sent, messageStats.value.received)
  return max > 0 ? (messageStats.value.sent / max) * 200 : 0
})

const messageReceivedHeight = computed(() => {
  const max = Math.max(messageStats.value.sent, messageStats.value.received)
  return max > 0 ? (messageStats.value.received / max) * 200 : 0
})

const calculateTrend = (current, previous) => {
  if (!previous || previous === 0) return 0
  return Math.round(((current - previous) / previous) * 100)
}

const fetchAnalytics = async () => {
  try {
    const [summary, views, likes, messages, proposals, heatmap, demographics] = await Promise.all([
      fetch(`/api/analytics/summary?days=${selectedDays.value}`).then(r => r.json()),
      fetch(`/api/analytics/profile-views?days=${selectedDays.value}`).then(r => r.json()),
      fetch(`/api/analytics/likes?days=${selectedDays.value}`).then(r => r.json()),
      fetch(`/api/analytics/messages?days=${selectedDays.value}`).then(r => r.json()),
      fetch(`/api/analytics/proposals?days=${selectedDays.value}`).then(r => r.json()),
      fetch(`/api/analytics/activity-heatmap?days=${selectedDays.value}`).then(r => r.json()),
      fetch(`/api/analytics/demographics?days=${selectedDays.value}`).then(r => r.json()),
    ])

    summary.value = summary
    profileViewsTrend.value = views.trend
    likesBreakdown.value = likes.breakdown
    messageStats.value = messages
    proposalStats.value = proposals
    heatmapData.value = heatmap
    demographicsData.value = demographics
    lastUpdated.value = new Date().toLocaleTimeString()
  } catch (error) {
    console.error('Error fetching analytics:', error)
  }
}

onMounted(() => {
  fetchAnalytics()
  // Auto-refresh every 5 minutes
  setInterval(fetchAnalytics, 5 * 60 * 1000)
})
</script>

<style scoped>
/* Custom scrollbar for the dashboard */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: rgba(59, 130, 246, 0.5);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(59, 130, 246, 0.8);
}
</style>
