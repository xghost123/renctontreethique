# Advanced Search & Filtering System - Implementation Guide

## Overview

The Advanced Search & Filtering System for Rencontre Éthique is a production-ready, full-featured search solution with:

- **Complex Eloquent Queries** with multiple filters
- **Smart Recommendations** based on user profiles
- **Filter Persistence** using localStorage/session storage
- **Saved Searches** feature for users to store favorite search combinations
- **Database Optimization** with strategic indexes
- **Responsive Vue 3 Components** with Inertia.js integration
- **RESTful API** with comprehensive validation

## Architecture

### Backend Components

#### 1. SearchController (`app/Http/Controllers/SearchController.php`)

Main search controller handling:
- Advanced filtering with multiple criteria
- Smart recommendations generation
- Filter options caching
- Sorting and pagination
- Response formatting

**Key Methods:**
- `index()` - Main search with all filters applied
- `recommendations()` - Smart recommendations based on user profile
- `getFilterOptions()` - Available filter choices (cached)

#### 2. SearchRequest (`app/Http/Requests/SearchRequest.php`)

Form request validation for:
- Age ranges (18-80)
- Location (country, division, district)
- Education levels (general, aliya, kowmi, other)
- Religious practice (prayer level, practice years)
- Family goals and children status
- Appearance preferences (skin color, height)
- Marital status and madhab

#### 3. SavedSearchController (`app/Http/Controllers/SavedSearchController.php`)

CRUD operations for saved searches:
- Create/Read/Update/Delete
- Ownership verification
- Efficient filtering by user

#### 4. SavedSearch Model (`app/Models/SavedSearch.php`)

Database model for storing user search preferences:
- JSON filters storage
- User association
- Active status flag

### Frontend Components

#### 1. SearchFilters.vue (`resources/js/Components/SearchFilters.vue`)

Main filter component featuring:
- Expandable/collapsible filter groups
- Applied filter pills with removal
- Real-time filter updates
- Filter persistence via localStorage
- Save/Load saved searches
- Clean dark-themed UI (green #0D2218, gold #C8A028)

#### 2. SearchResults.vue (`resources/js/Components/SearchResults.vue`)

Results display component:
- Grid layout with profile cards
- Photo display with fallback
- Key profile attributes
- Sorting options
- Pagination with smart page numbers
- Loading and empty states

#### 3. Supporting Components

- `FilterGroup.vue` - Grouped filter display
- `FilterButton.vue` - Togglable filter button
- `SavedSearchesModal.vue` - Saved searches list and load
- `SaveSearchModal.vue` - Save new search dialog
- `TransitionExpand.vue` - Smooth expand/collapse transitions

### Composables

#### useLocalStorage.js
- `storeFilters()` - Save filters to localStorage
- `getStoredFilters()` - Retrieve stored filters
- `clearStoredFilters()` - Clear saved filters

#### useSearch.js
- `performSearch()` - Execute search with filters
- `fetchFilterOptions()` - Get available filter choices
- `fetchSavedSearches()` - Get user's saved searches
- `getRecommendations()` - Fetch smart recommendations

## API Endpoints

### Search Endpoints

#### GET /api/search
Main search endpoint with filters

**Query Parameters:**
```
page=1                          # Page number for pagination
per_page=20                     # Results per page (max: 100)
sort_by=newest_profiles         # Sort: newest_profiles, recently_active, age_asc, age_desc, compatibility
gender=female|male              # Gender filter
age_min=18&age_max=45          # Age range
country=Bangladesh              # Country
division=Dhaka                  # Division
district=Dhaka                  # District
education_level=general|aliya|kowmi|other
prayer_level=regular|occasional|non_practising|very_observant
practice_religion_years=less_than_1|1_to_5|5_to_10|more_than_10
family_goals=wants_children|open_to_children|no_children
have_children=no|yes_living_with_me|yes_not_living_with_me
skin_color[]=fair&skin_color[]=brown  # Multiple colors
height_min=150&height_max=180
maritial_status=single|divorced|widowed
madhab=hanafi|maliki|shafei|hanbali|other
has_photos=true|false           # Only profiles with approved photos
q=keyword                       # Text search in bio/job/interests
min_compatibility=70            # Minimum compatibility score
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "gender": "female",
      "age": 28,
      "country": "Bangladesh",
      "division": "Dhaka",
      "education_level": "Aliya",
      "job_title": "Teacher",
      "prayer_level": "regular",
      "maritial_status": "single",
      "have_children": "no",
      "madhab": "hanafi",
      "bio": "Practicing Muslim woman...",
      "photo_count": 2,
      "primary_photo": "https://...",
      "created_at": "2026-08-04T10:30:00Z",
      "updated_at": "2026-08-04T15:45:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total": 125,
    "per_page": 20,
    "last_page": 7,
    "from": 1,
    "to": 20,
    "total_count": 125
  },
  "filters_applied": [
    {"type": "gender", "value": "female"},
    {"type": "age", "min": 25, "max": 35}
  ]
}
```

#### GET /api/search/filters
Get available filter options (cached 24h)

**Response:**
```json
{
  "success": true,
  "data": {
    "genders": ["male", "female"],
    "education_levels": ["general", "aliya", "kowmi", "other"],
    "prayer_levels": [...],
    "practice_religion_years": [...],
    "family_goals": [...],
    "have_children": [...],
    "skin_colors": ["fair", "light_brown", "brown", "dark_brown", "dark"],
    "maritial_statuses": [...],
    "madhabs": [...],
    "countries": [...],
    "divisions": [...]
  }
}
```

#### GET /api/search/recommendations
Get smart recommendations based on user's profile (cached 1h)

**Response:**
```json
{
  "success": true,
  "data": [
    {/* biodata objects */}
  ],
  "message": "Smart recommendations based on your profile preferences"
}
```

### Saved Searches Endpoints

#### GET /api/saved-searches
Get user's saved searches

#### POST /api/saved-searches
Create new saved search

**Request Body:**
```json
{
  "name": "Religious Women in Bangladesh",
  "description": "Optional description",
  "filters": {
    "gender": "female",
    "age_min": 25,
    "age_max": 35,
    "prayer_level": "regular"
  }
}
```

#### PUT /api/saved-searches/{id}
Update saved search

#### DELETE /api/saved-searches/{id}
Delete saved search

## Database Schema

### saved_searches Table
```sql
CREATE TABLE saved_searches (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    filters JSON NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id, is_active)
)
```

### biodata Table Indexes
```sql
-- Composite indexes for common search patterns
INDEX (gender, is_approved, in_trash)
INDEX (age, is_approved)
INDEX (permanent_country, is_approved)
INDEX (permanent_division, is_approved)
INDEX (prayer_level, is_approved)
INDEX (madhab, is_approved)
INDEX (maritial_status, is_approved)
INDEX (created_at, is_approved)
INDEX (updated_at, is_approved)

-- Education level indexes
INDEX (general_selected, is_approved)
INDEX (aliya_selected, is_approved)
INDEX (kowmi_selected, is_approved)

-- Other attributes
INDEX (practice_religion_years, is_approved)
INDEX (skin_color, is_approved)
INDEX (height, is_approved)
```

## Filter Persistence

### localStorage Strategy
Filters are saved to browser localStorage with key: `rencontre_search_filters`

```javascript
// Save
localStorage.setItem('rencontre_search_filters', JSON.stringify(filters))

// Retrieve
const filters = JSON.parse(localStorage.getItem('rencontre_search_filters'))

// Clear
localStorage.removeItem('rencontre_search_filters')
```

## Smart Recommendations Algorithm

Generates recommendations based on:
1. **Opposite Gender** - Filters for opposite gender automatically
2. **Age Proximity** - ±10 years from user's age
3. **Same Location** - Prioritizes user's country
4. **Religious Alignment** - Matches prayer level and madhab
5. **Photo Availability** - Only recommends profiles with approved photos
6. **Caching** - Results cached for 1 hour per user

## Usage Examples

### Vue Component Integration

```vue
<template>
  <div class="search-page">
    <SearchFilters @search="handleSearch" />
    <SearchResults
      :results="results"
      :pagination="pagination"
      :loading="loading"
      @changeSorting="changeSorting"
      @changePage="changePage"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import SearchFilters from '@/Components/SearchFilters.vue'
import SearchResults from '@/Components/SearchResults.vue'

const results = ref([])
const pagination = ref({})
const loading = ref(false)

const handleSearch = async (response) => {
  loading.value = true
  results.value = response.data
  pagination.value = response.pagination
  loading.value = false
}

const changeSorting = (sortBy) => {
  // Implement sorting logic
}

const changePage = (page) => {
  // Implement pagination logic
}
</script>
```

### API Usage from JavaScript

```javascript
// Perform search
const response = await fetch('/api/search?gender=female&age_min=25&age_max=35')
const data = await response.json()

// Get recommendations
const recs = await fetch('/api/search/recommendations')
const recommendations = await recs.json()

// Save search
await fetch('/api/saved-searches', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    name: 'My Search',
    filters: { gender: 'female', age_min: 25 }
  })
})
```

## Performance Optimizations

1. **Database Indexes** - Strategic indexing on frequently filtered columns
2. **Query Optimization** - Efficient Eloquent queries with proper eager loading
3. **Caching** - Filter options cached 24h, recommendations cached 1h
4. **Pagination** - Default 20 results per page, max 100
5. **Lazy Loading** - Filter options loaded on-demand
6. **localStorage** - Client-side filter persistence prevents unnecessary API calls

## Security Features

1. **Authentication** - All endpoints require user authentication (auth:sanctum)
2. **Authorization** - Users can only access/modify their own saved searches
3. **Input Validation** - Comprehensive request validation with SearchRequest
4. **CSRF Protection** - All POST/PUT/DELETE requests require CSRF token
5. **Rate Limiting** - Can be implemented via Laravel middleware

## Testing

Run feature tests:
```bash
php artisan test tests/Feature/SearchFeatureTest.php
```

Test coverage includes:
- Basic search functionality
- Individual filter testing
- Multi-filter combinations
- Pagination and sorting
- Saved search CRUD operations
- Authorization checks
- Error handling

## Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Optimize autoloader: `composer install --optimize-autoloader`
- [ ] Build frontend: `npm run build`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Test search endpoints with sample data

## Future Enhancements

1. **Elasticsearch Integration** - For larger datasets and faster full-text search
2. **Advanced Matching Algorithm** - Compatibility scoring based on multiple factors
3. **Search History** - Track and suggest recent searches
4. **Alerts** - Notify users when new profiles match saved searches
5. **Search Analytics** - Track popular search patterns
6. **Export Results** - Download search results as CSV/PDF
7. **Advanced Filters UI** - Date range pickers, location maps, etc.

## Troubleshooting

### No search results returned
1. Check if filters are too restrictive
2. Verify biodata records have `is_approved = true`
3. Ensure biodata records have `in_trash = false`
4. Check that user's own biodata is being excluded

### Filter options not loading
1. Clear cache: `php artisan cache:clear`
2. Check database connectivity
3. Verify biodata table has sample data

### Saved searches not persisting
1. Check user authentication status
2. Verify user_id is set correctly
3. Check database permissions

### localStorage not working
1. Verify browser localStorage is enabled
2. Check browser storage quota
3. Clear browser cache and try again

## Files Modified/Created

### Controllers
- `app/Http/Controllers/SearchController.php` (NEW)
- `app/Http/Controllers/SavedSearchController.php` (NEW)

### Models
- `app/Models/SavedSearch.php` (NEW)

### Requests
- `app/Http/Requests/SearchRequest.php` (NEW)

### Migrations
- `database/migrations/2026_08_04_000001_create_search_features.php` (NEW)

### Routes
- `routes/api.php` (MODIFIED)

### Vue Components
- `resources/js/Components/SearchFilters.vue` (NEW)
- `resources/js/Components/SearchResults.vue` (NEW)
- `resources/js/Components/FilterGroup.vue` (NEW)
- `resources/js/Components/FilterButton.vue` (NEW)
- `resources/js/Components/SavedSearchesModal.vue` (NEW)
- `resources/js/Components/SaveSearchModal.vue` (NEW)
- `resources/js/Components/TransitionExpand.vue` (NEW)

### Composables
- `resources/js/composables/useLocalStorage.js` (NEW)
- `resources/js/composables/useSearch.js` (NEW)

### Tests
- `tests/Feature/SearchFeatureTest.php` (NEW)

## Summary

The Advanced Search & Filtering System provides a complete, production-ready solution for helping users find compatible matches on the Rencontre Éthique platform. With comprehensive filtering options, smart recommendations, and persistent search preferences, users can efficiently discover compatible profiles while maintaining optimal performance through strategic caching and database optimization.
