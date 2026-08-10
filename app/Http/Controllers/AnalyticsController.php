<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get profile views analytics
     */
    public function profileViews(Request $request)
    {
        $user = $request->user();
        $biodata = $user->biodata;
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // Total views
        $totalViews = DB::table('profile_views')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Views trend (per day)
        $trend = DB::table('profile_views')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn($item) => [$item->date => $item->count])
            ->toArray();

        // Fill in missing dates
        $trend = $this->fillMissingDates($trend, $days);

        // Comparison with previous period
        $previousStartDate = $startDate->copy()->subDays($days);
        $previousEndDate = $startDate->copy()->subDay();
        $previousViews = DB::table('profile_views')
            ->where('biodata_id', $biodata->id)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $change = $previousViews > 0 
            ? round((($totalViews - $previousViews) / $previousViews) * 100, 1)
            : ($totalViews > 0 ? 100 : 0);

        return response()->json([
            'total' => $totalViews,
            'previous_period' => $previousViews,
            'change' => $change,
            'trend' => $trend,
            'days' => $days,
        ]);
    }

    /**
     * Get likes analytics
     */
    public function likes(Request $request)
    {
        $user = $request->user();
        $biodata = $user->biodata;
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // Total likes
        $totalLikes = DB::table('like_analytics')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Likes breakdown
        $breakdown = DB::table('like_analytics')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('like_type, COUNT(*) as count')
            ->groupBy('like_type')
            ->pluck('count', 'like_type')
            ->toArray();

        // Likes trend
        $trend = DB::table('like_analytics')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn($item) => [$item->date => $item->count])
            ->toArray();

        $trend = $this->fillMissingDates($trend, $days);

        // Previous period
        $previousStartDate = $startDate->copy()->subDays($days);
        $previousEndDate = $startDate->copy()->subDay();
        $previousLikes = DB::table('like_analytics')
            ->where('biodata_id', $biodata->id)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $change = $previousLikes > 0 
            ? round((($totalLikes - $previousLikes) / $previousLikes) * 100, 1)
            : ($totalLikes > 0 ? 100 : 0);

        return response()->json([
            'total' => $totalLikes,
            'previous_period' => $previousLikes,
            'change' => $change,
            'breakdown' => $breakdown,
            'trend' => $trend,
        ]);
    }

    /**
     * Get messages analytics
     */
    public function messages(Request $request)
    {
        $user = $request->user();
        $biodata = $user->biodata;
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // Count sent and received from actual message table
        $sent = DB::table('messages')
            ->where('sender_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        $received = DB::table('messages')
            ->where('receiver_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Daily breakdown
        $dailyTrend = DB::table('messages')
            ->where(fn($q) => $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id))
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn($item) => [$item->date => $item->count])
            ->toArray();

        $dailyTrend = $this->fillMissingDates($dailyTrend, $days);

        // Previous period
        $previousStartDate = $startDate->copy()->subDays($days);
        $previousEndDate = $startDate->copy()->subDay();
        $previousSent = DB::table('messages')
            ->where('sender_id', $user->id)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $previousReceived = DB::table('messages')
            ->where('receiver_id', $user->id)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $total = $sent + $received;
        $previousTotal = $previousSent + $previousReceived;
        $change = $previousTotal > 0 
            ? round((($total - $previousTotal) / $previousTotal) * 100, 1)
            : ($total > 0 ? 100 : 0);

        // Unique conversations
        $conversations = DB::table('messages')
            ->where(fn($q) => $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id))
            ->where('created_at', '>=', $startDate)
            ->selectRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as other_user', [$user->id])
            ->distinct()
            ->count();

        return response()->json([
            'sent' => $sent,
            'received' => $received,
            'total' => $total,
            'previous_period' => $previousTotal,
            'change' => $change,
            'conversations' => $conversations,
            'trend' => $dailyTrend,
        ]);
    }

    /**
     * Get proposals analytics
     */
    public function proposals(Request $request)
    {
        $user = $request->user();
        $biodata = $user->biodata;
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // Sent proposals
        $sent = DB::table('proposal_analytics')
            ->where('sent_by_biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Received proposals
        $received = DB::table('proposal_analytics')
            ->where('sent_to_biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Accepted proposals
        $accepted = DB::table('proposal_analytics')
            ->where('sent_by_biodata_id', $biodata->id)
            ->where('status', 'accepted')
            ->where('created_at', '>=', $startDate)
            ->count();

        $acceptanceRate = $sent > 0 ? round(($accepted / $sent) * 100, 1) : 0;

        // Received status breakdown
        $receivedBreakdown = DB::table('proposal_analytics')
            ->where('sent_to_biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Average response time
        $avgResponseTime = DB::table('proposal_analytics')
            ->where('sent_to_biodata_id', $biodata->id)
            ->where('response_time_seconds', '>', 0)
            ->where('created_at', '>=', $startDate)
            ->avg('response_time_seconds');

        // Funnel data (sent -> accepted)
        $sentCount = DB::table('proposal_analytics')
            ->where('sent_by_biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        $rejectedCount = DB::table('proposal_analytics')
            ->where('sent_by_biodata_id', $biodata->id)
            ->where('status', 'rejected')
            ->where('created_at', '>=', $startDate)
            ->count();

        return response()->json([
            'sent' => $sent,
            'received' => $received,
            'accepted' => $accepted,
            'acceptance_rate' => $acceptanceRate,
            'received_breakdown' => $receivedBreakdown,
            'average_response_time' => $avgResponseTime ? round($avgResponseTime / 3600, 1) : null,
            'funnel' => [
                'sent' => $sentCount,
                'rejected' => $rejectedCount,
                'accepted' => $accepted,
            ],
        ]);
    }

    /**
     * Get activity heatmap
     */
    public function activityHeatmap(Request $request)
    {
        $user = $request->user();
        $days = $request->get('days', 30);

        $heatmap = DB::table('activity_heatmap')
            ->where('user_id', $user->id)
            ->selectRaw('day_of_week, hour, SUM(activity_count) as total')
            ->groupBy('day_of_week', 'hour')
            ->get()
            ->groupBy('day_of_week');

        // Initialize empty heatmap
        $result = [];
        $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        for ($day = 0; $day < 7; $day++) {
            for ($hour = 0; $hour < 24; $hour++) {
                $result[$dayNames[$day]][$hour] = 0;
            }
        }

        // Fill in actual data
        foreach ($heatmap as $day => $hours) {
            foreach ($hours as $hourData) {
                $result[$dayNames[$day]][$hourData->hour] = $hourData->total;
            }
        }

        return response()->json($result);
    }

    /**
     * Get demographics of profile viewers
     */
    public function demographics(Request $request)
    {
        $user = $request->user();
        $biodata = $user->biodata;
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // Age distribution of viewers
        $ageDistribution = DB::table('viewer_demographics')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('FLOOR(viewer_age / 5) * 5 as age_range, COUNT(*) as count')
            ->whereNotNull('viewer_age')
            ->groupBy('age_range')
            ->orderBy('age_range')
            ->pluck('count', 'age_range')
            ->toArray();

        // Location distribution
        $locationDistribution = DB::table('viewer_demographics')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('viewer_location, COUNT(*) as count')
            ->whereNotNull('viewer_location')
            ->groupBy('viewer_location')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'viewer_location')
            ->toArray();

        return response()->json([
            'age_distribution' => $ageDistribution,
            'location_distribution' => $locationDistribution,
        ]);
    }

    /**
     * Get profile completion percentage
     */
    public function profileCompletion(Request $request)
    {
        $user = $request->user();
        $biodata = $user->biodata;

        $completion = $biodata->biodata_completion ?? 0;

        return response()->json([
            'completion' => $completion,
            'status' => $completion >= 80 ? 'excellent' : ($completion >= 50 ? 'good' : 'incomplete'),
        ]);
    }

    /**
     * Get key metrics summary
     */
    public function summary(Request $request)
    {
        $user = $request->user();
        $biodata = $user->biodata;

        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = $thisMonth->copy()->subMonth();

        // This month stats
        $thisMonthViews = DB::table('profile_views')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $thisMonth)
            ->count();

        $thisMonthLikes = DB::table('like_analytics')
            ->where('biodata_id', $biodata->id)
            ->where('created_at', '>=', $thisMonth)
            ->count();

        // Last month stats
        $lastMonthViews = DB::table('profile_views')
            ->where('biodata_id', $biodata->id)
            ->whereBetween('created_at', [$lastMonth, $thisMonth->copy()->subSecond()])
            ->count();

        $lastMonthLikes = DB::table('like_analytics')
            ->where('biodata_id', $biodata->id)
            ->whereBetween('created_at', [$lastMonth, $thisMonth->copy()->subSecond()])
            ->count();

        // Messages
        $messages = DB::table('messages')
            ->where(fn($q) => $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id))
            ->where('created_at', '>=', $thisMonth)
            ->count();

        // Proposals
        $proposalsSent = DB::table('proposal_analytics')
            ->where('sent_by_biodata_id', $biodata->id)
            ->where('created_at', '>=', $thisMonth)
            ->count();

        $proposalsAccepted = DB::table('proposal_analytics')
            ->where('sent_by_biodata_id', $biodata->id)
            ->where('status', 'accepted')
            ->where('created_at', '>=', $thisMonth)
            ->count();

        return response()->json([
            'this_month' => [
                'views' => $thisMonthViews,
                'likes' => $thisMonthLikes,
                'messages' => $messages,
                'proposals_sent' => $proposalsSent,
                'proposals_accepted' => $proposalsAccepted,
            ],
            'last_month' => [
                'views' => $lastMonthViews,
                'likes' => $lastMonthLikes,
            ],
            'profile_completion' => $biodata->biodata_completion ?? 0,
        ]);
    }

    /**
     * Helper to fill missing dates in trend data
     */
    private function fillMissingDates($data, $days)
    {
        $filled = [];
        $now = Carbon::now();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->toDateString();
            $filled[$date] = $data[$date] ?? 0;
        }

        return $filled;
    }
}
