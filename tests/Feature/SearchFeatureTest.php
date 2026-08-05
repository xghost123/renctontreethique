<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Biodata;
use App\Models\SavedSearch;

class SearchFeatureTest extends TestCase
{
    protected User $user;
    protected Biodata $userBiodata;
    protected Biodata $targetBiodata;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users and biodatas
        $this->user = User::factory()->create();
        $this->userBiodata = Biodata::factory()->create([
            'user_id' => $this->user->id,
            'gender' => 'male',
            'age' => 30,
            'is_approved' => true,
        ]);

        $this->targetBiodata = Biodata::factory()->create([
            'gender' => 'female',
            'age' => 28,
            'is_approved' => true,
            'permanent_country' => 'Bangladesh',
            'permanent_division' => 'Dhaka',
            'prayer_level' => 'regular',
            'madhab' => 'hanafi',
            'maritial_status' => 'single',
        ]);
    }

    /**
     * Test basic search endpoint
     */
    public function test_search_endpoint_returns_results()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/search');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'gender', 'age', 'country'],
                ],
                'pagination',
                'filters_applied',
            ]);
    }

    /**
     * Test search with gender filter
     */
    public function test_search_with_gender_filter()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/search?gender=female');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        // Verify only female profiles are returned
        foreach ($response->json('data') as $result) {
            $this->assertEquals('female', $result['gender']);
        }
    }

    /**
     * Test search with age range filter
     */
    public function test_search_with_age_range_filter()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/search?age_min=25&age_max=35');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    /**
     * Test filter options endpoint
     */
    public function test_filter_options_endpoint()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/search/filters');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'genders',
                    'education_levels',
                    'prayer_levels',
                    'family_goals',
                    'skin_colors',
                    'maritial_statuses',
                    'madhabs',
                    'countries',
                    'divisions',
                ],
            ]);
    }

    /**
     * Test recommendations endpoint
     */
    public function test_recommendations_endpoint()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/search/recommendations');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
            ]);
    }

    /**
     * Test save search functionality
     */
    public function test_save_search()
    {
        $filters = [
            'gender' => 'female',
            'age_min' => 25,
            'age_max' => 35,
            'country' => 'Bangladesh',
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/saved-searches', [
                'name' => 'Religious Women in Bangladesh',
                'description' => 'Looking for religious women aged 25-35',
                'filters' => $filters,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Religious Women in Bangladesh');

        // Verify saved in database
        $this->assertDatabaseHas('saved_searches', [
            'user_id' => $this->user->id,
            'name' => 'Religious Women in Bangladesh',
        ]);
    }

    /**
     * Test retrieve saved searches
     */
    public function test_retrieve_saved_searches()
    {
        SavedSearch::create([
            'user_id' => $this->user->id,
            'name' => 'Test Search 1',
            'filters' => ['gender' => 'female'],
        ]);

        SavedSearch::create([
            'user_id' => $this->user->id,
            'name' => 'Test Search 2',
            'filters' => ['age_min' => 25, 'age_max' => 35],
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/saved-searches');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test update saved search
     */
    public function test_update_saved_search()
    {
        $search = SavedSearch::create([
            'user_id' => $this->user->id,
            'name' => 'Original Name',
            'filters' => ['gender' => 'female'],
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/saved-searches/{$search->id}", [
                'name' => 'Updated Name',
                'description' => 'Updated description',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name');
    }

    /**
     * Test delete saved search
     */
    public function test_delete_saved_search()
    {
        $search = SavedSearch::create([
            'user_id' => $this->user->id,
            'name' => 'Search to Delete',
            'filters' => ['gender' => 'female'],
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/saved-searches/{$search->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('saved_searches', [
            'id' => $search->id,
        ]);
    }

    /**
     * Test unauthorized access to saved search
     */
    public function test_unauthorized_access_to_saved_search()
    {
        $otherUser = User::factory()->create();
        $search = SavedSearch::create([
            'user_id' => $this->user->id,
            'name' => 'Other User Search',
            'filters' => ['gender' => 'female'],
        ]);

        $response = $this->actingAs($otherUser)
            ->putJson("/api/saved-searches/{$search->id}", [
                'name' => 'Hacked Name',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test pagination
     */
    public function test_search_pagination()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/search?per_page=10&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'pagination' => [
                    'current_page',
                    'total',
                    'per_page',
                    'last_page',
                    'from',
                    'to',
                    'total_count',
                ],
            ]);
    }

    /**
     * Test sorting
     */
    public function test_search_sorting()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/search?sort_by=age_asc');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }
}
