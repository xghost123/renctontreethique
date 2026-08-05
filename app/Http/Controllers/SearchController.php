<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;

class SearchController extends Controller
{
    /**
     * Search and filter biodatas
     * GET /api/search
     */
    public function index(SearchRequest $request)
    {
        try {
            $user = Auth::user();
            $params = $request->getSearchParams();

            // Build query with filters
            $query = $this->buildSearchQuery($params, $user);

            // Apply sorting
            $query = $this->applySorting($query, $params['sort_by']);

            // Get total count before pagination
            $total = $query->count();

            // Paginate results
            $perPage = min($params['per_page'], 100);
            $results = $query
                ->paginate($perPage, ['*'], 'page', $params['page'])
                ->setPath(route('search.index'));

            // Format response
            $biodatas = $results->getCollection()->map(function ($biodata) use ($user) {
                return $this->formatBiodataResponse($biodata, $user);
            });

            return response()->json([
                'success' => true,
                'data' => $biodatas,
                'pagination' => [
                    'current_page' => $results->currentPage(),
                    'total' => $results->total(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'from' => $results->firstItem(),
                    'to' => $results->lastItem(),
                    'total_count' => $total,
                ],
                'filters_applied' => $this->getAppliedFilters($params),
            ]);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error performing search',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get smart recommendations based on user profile
     * GET /api/search/recommendations
     */
    public function recommendations(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user->biodata) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have a complete profile',
                ], 422);
            }

            // Cache recommendations for 1 hour
            $cacheKey = "recommendations:{$user->id}";
            $recommendations = Cache::remember($cacheKey, 3600, function () use ($user) {
                return $this->generateRecommendations($user);
            });

            return response()->json([
                'success' => true,
                'data' => $recommendations,
                'message' => 'Smart recommendations based on your profile preferences',
            ]);
        } catch (\Exception $e) {
            \Log::error('Recommendations error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating recommendations',
            ], 500);
        }
    }

    /**
     * Get filter options available
     * GET /api/search/filters
     */
    public function getFilterOptions()
    {
        try {
            $cacheKey = 'search_filter_options';
            $options = Cache::remember($cacheKey, 86400, function () { // 24 hours
                return [
                    'genders' => ['male', 'female'],
                    'education_levels' => ['general', 'aliya', 'kowmi', 'other'],
                    'prayer_levels' => [
                        'non_practising',
                        'occasional',
                        'regular',
                        'very_observant',
                    ],
                    'practice_religion_years' => [
                        'less_than_1',
                        '1_to_5',
                        '5_to_10',
                        'more_than_10',
                    ],
                    'family_goals' => [
                        'wants_children',
                        'open_to_children',
                        'no_children',
                    ],
                    'have_children' => [
                        'no',
                        'yes_living_with_me',
                        'yes_not_living_with_me',
                    ],
                    'skin_colors' => [
                        'fair',
                        'light_brown',
                        'brown',
                        'dark_brown',
                        'dark',
                    ],
                    'maritial_statuses' => ['single', 'divorced', 'widowed'],
                    'madhabs' => ['hanafi', 'maliki', 'shafei', 'hanbali', 'other'],
                    'countries' => Biodata::whereNotNull('permanent_country')
                        ->distinct()
                        ->pluck('permanent_country')
                        ->filter()
                        ->sort()
                        ->values(),
                    'divisions' => Biodata::whereNotNull('permanent_division')
                        ->distinct()
                        ->pluck('permanent_division')
                        ->filter()
                        ->sort()
                        ->values(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $options,
            ]);
        } catch (\Exception $e) {
            \Log::error('Filter options error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching filter options',
            ], 500);
        }
    }

    /**
     * Build search query with all filters
     */
    private function buildSearchQuery($params, $user)
    {
        $query = Biodata::where('is_approved', true)
            ->where('in_trash', false);

        // Exclude user's own biodata
        if ($user && $user->biodata) {
            $query->where('id', '!=', $user->biodata->id);
        }

        // Gender filter (opposite gender by default)
        if ($params['gender']) {
            $query->where('gender', $params['gender']);
        } elseif ($user && $user->biodata) {
            // Auto filter opposite gender
            $oppositeGender = $user->biodata->gender === 'male' ? 'female' : 'male';
            $query->where('gender', $oppositeGender);
        }

        // Age range filter
        if ($params['age_min']) {
            $query->where('age', '>=', $params['age_min']);
        }
        if ($params['age_max']) {
            $query->where('age', '<=', $params['age_max']);
        }

        // Location filters
        if ($params['country']) {
            $query->where('permanent_country', $params['country']);
        }
        if ($params['division']) {
            $query->where('permanent_division', $params['division']);
        }
        if ($params['district']) {
            $query->where('permanent_district', $params['district']);
        }

        // Education level filter
        if ($params['education_level']) {
            $this->filterByEducation($query, $params['education_level']);
        }

        // Prayer/Religious practice filter
        if ($params['prayer_level']) {
            $query->where('prayer_level', $params['prayer_level']);
        }

        if ($params['practice_religion_years']) {
            $query->where('practice_religion_years', $params['practice_religion_years']);
        }

        // Family goals filter
        if ($params['family_goals']) {
            $query->where('children_pref', $params['family_goals']);
        }

        if ($params['have_children']) {
            $query->where('have_children', $params['have_children']);
        }

        // Appearance filters
        if (!empty($params['skin_color'])) {
            $query->whereIn('skin_color', $params['skin_color']);
        }

        if ($params['height_min']) {
            $query->where('height', '>=', $params['height_min']);
        }
        if ($params['height_max']) {
            $query->where('height', '<=', $params['height_max']);
        }

        // Marital status filter
        if ($params['maritial_status']) {
            $query->where('maritial_status', $params['maritial_status']);
        }

        // Madhab filter
        if ($params['madhab']) {
            $query->where('madhab', $params['madhab']);
        }

        // Photos filter
        if ($params['has_photos']) {
            $query->whereHas('photos', function ($q) {
                $q->where('approved', true);
            });
        }

        // Text search
        if ($params['q']) {
            $searchTerm = '%' . $params['q'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('job_title', 'like', $searchTerm)
                    ->orWhere('job', 'like', $searchTerm)
                    ->orWhere('bio', 'like', $searchTerm)
                    ->orWhere('looking_for', 'like', $searchTerm);
            });
        }

        return $query;
    }

    /**
     * Filter by education level
     */
    private function filterByEducation(&$query, $level)
    {
        switch ($level) {
            case 'general':
                $query->where('general_selected', true);
                break;
            case 'aliya':
                $query->where('aliya_selected', true);
                break;
            case 'kowmi':
                $query->where('kowmi_selected', true);
                break;
            case 'other':
                $query->where('study_others_selected', true);
                break;
        }
    }

    /**
     * Apply sorting to query
     */
    private function applySorting($query, $sortBy)
    {
        return match ($sortBy) {
            'recently_active' => $query->orderByDesc('updated_at'),
            'age_asc' => $query->orderBy('age'),
            'age_desc' => $query->orderByDesc('age'),
            'newest_profiles' => $query->orderByDesc('created_at'),
            'compatibility' => $query->orderByDesc('updated_at'), // Default in absence of compatibility score
            default => $query->orderByDesc('created_at'),
        };
    }

    /**
     * Generate smart recommendations
     */
    private function generateRecommendations($user)
    {
        $userBiodata = $user->biodata;
        if (!$userBiodata) {
            return [];
        }

        // Build recommendation query based on user's preferences
        $params = [
            'gender' => $userBiodata->gender === 'male' ? 'female' : 'male',
            'age_min' => $userBiodata->age - 10,
            'age_max' => $userBiodata->age + 10,
            'country' => $userBiodata->permanent_country,
            'prayer_level' => $userBiodata->prayer_level,
            'madhab' => $userBiodata->madhab,
            'has_photos' => true,
        ];

        $query = $this->buildSearchQuery($params, $user);
        $recommendations = $query
            ->limit(10)
            ->get()
            ->map(function ($biodata) use ($user) {
                return $this->formatBiodataResponse($biodata, $user);
            });

        return $recommendations;
    }

    /**
     * Format biodata for response
     */
    private function formatBiodataResponse($biodata, $user)
    {
        $approvedPhotos = $biodata->approvedPhotos()->get();

        return [
            'id' => $biodata->id,
            'user_id' => $biodata->user_id,
            'gender' => $biodata->gender,
            'age' => $biodata->age,
            'country' => $biodata->permanent_country,
            'division' => $biodata->permanent_division,
            'district' => $biodata->permanent_district,
            'skin_color' => $biodata->skin_color,
            'height' => $biodata->height,
            'prayer_level' => $biodata->prayer_level,
            'education_level' => $this->getEducationLevel($biodata),
            'job_title' => $biodata->job_title ?? $biodata->job,
            'maritial_status' => $biodata->maritial_status,
            'have_children' => $biodata->have_children,
            'madhab' => $biodata->madhab,
            'bio' => $biodata->bio ? substr($biodata->bio, 0, 150) . '...' : null,
            'photo_count' => count($approvedPhotos),
            'primary_photo' => count($approvedPhotos) > 0
                ? asset('storage/' . $approvedPhotos[0]->path)
                : null,
            'created_at' => $biodata->created_at->toIso8601String(),
            'updated_at' => $biodata->updated_at->toIso8601String(),
        ];
    }

    /**
     * Get education level label
     */
    private function getEducationLevel($biodata)
    {
        if ($biodata->general_selected) {
            return 'General';
        }
        if ($biodata->aliya_selected) {
            return 'Aliya';
        }
        if ($biodata->kowmi_selected) {
            return 'Kowmi';
        }
        if ($biodata->study_others_selected) {
            return 'Other';
        }
        return null;
    }

    /**
     * Get applied filters
     */
    private function getAppliedFilters($params)
    {
        $applied = [];

        if ($params['gender']) {
            $applied[] = ['type' => 'gender', 'value' => $params['gender']];
        }
        if ($params['age_min'] || $params['age_max']) {
            $applied[] = [
                'type' => 'age',
                'min' => $params['age_min'],
                'max' => $params['age_max'],
            ];
        }
        if ($params['country']) {
            $applied[] = ['type' => 'country', 'value' => $params['country']];
        }
        if ($params['prayer_level']) {
            $applied[] = ['type' => 'prayer_level', 'value' => $params['prayer_level']];
        }
        if ($params['family_goals']) {
            $applied[] = ['type' => 'family_goals', 'value' => $params['family_goals']];
        }
        if (!empty($params['skin_color'])) {
            $applied[] = ['type' => 'skin_color', 'values' => $params['skin_color']];
        }
        if ($params['has_photos']) {
            $applied[] = ['type' => 'has_photos', 'value' => true];
        }
        if ($params['q']) {
            $applied[] = ['type' => 'search_query', 'value' => $params['q']];
        }

        return $applied;
    }
}
