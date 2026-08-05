<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Search is available to all authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Pagination & sorting
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort_by' => 'nullable|in:recently_active,age_asc,age_desc,newest_profiles,compatibility',

            // Gender filter
            'gender' => 'nullable|in:male,female',

            // Age range filter
            'age_min' => 'nullable|integer|min:18|max:80',
            'age_max' => 'nullable|integer|min:18|max:80',

            // Location filters
            'country' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',

            // Education level filters
            'education_level' => 'nullable|in:general,aliya,kowmi,other',
            'education_degree' => 'nullable|string|max:100',

            // Religious practice level
            'prayer_level' => 'nullable|in:non_practising,occasional,regular,very_observant',
            'practice_religion_years' => 'nullable|in:less_than_1,1_to_5,5_to_10,more_than_10',

            // Family goals
            'family_goals' => 'nullable|in:wants_children,open_to_children,no_children',
            'have_children' => 'nullable|in:no,yes_living_with_me,yes_not_living_with_me',

            // Appearance preferences
            'skin_color' => 'nullable|array',
            'skin_color.*' => 'string|max:50',
            'height_min' => 'nullable|integer|min:100|max:250',
            'height_max' => 'nullable|integer|min:100|max:250',

            // Other filters
            'maritial_status' => 'nullable|in:single,divorced,widowed',
            'madhab' => 'nullable|in:hanafi,maliki,shafei,hanbali,other',
            'has_photos' => 'nullable|boolean',

            // Search query
            'q' => 'nullable|string|max:255',

            // Compatibility score minimum
            'min_compatibility' => 'nullable|integer|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'age_min.min' => 'Minimum age must be at least 18',
            'age_max.min' => 'Maximum age must be at least 18',
            'age_max.gte' => 'Maximum age must be greater than or equal to minimum age',
            'per_page.max' => 'Results per page cannot exceed 100',
        ];
    }

    /**
     * Get sanitized search parameters.
     */
    public function getSearchParams(): array
    {
        return [
            'gender' => $this->input('gender'),
            'age_min' => $this->input('age_min'),
            'age_max' => $this->input('age_max'),
            'country' => $this->input('country'),
            'division' => $this->input('division'),
            'district' => $this->input('district'),
            'education_level' => $this->input('education_level'),
            'education_degree' => $this->input('education_degree'),
            'prayer_level' => $this->input('prayer_level'),
            'practice_religion_years' => $this->input('practice_religion_years'),
            'family_goals' => $this->input('family_goals'),
            'have_children' => $this->input('have_children'),
            'skin_color' => $this->input('skin_color', []),
            'height_min' => $this->input('height_min'),
            'height_max' => $this->input('height_max'),
            'maritial_status' => $this->input('maritial_status'),
            'madhab' => $this->input('madhab'),
            'has_photos' => $this->input('has_photos'),
            'q' => $this->input('q'),
            'min_compatibility' => $this->input('min_compatibility', 0),
            'sort_by' => $this->input('sort_by', 'newest_profiles'),
            'per_page' => $this->input('per_page', 20),
            'page' => $this->input('page', 1),
        ];
    }
}
