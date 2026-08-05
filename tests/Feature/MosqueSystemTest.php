<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Biodata;
use App\Models\Mosque;
use App\Models\MosqueMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MosqueSystemTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $email, string $role = 'member', ?string $gender = null, ?int $mosqueId = null): User
    {
        $user = User::create([
            'name' => $email,
            'email' => $email,
            'password' => bcrypt('password123'),
            'role' => $role,
        ]);

        if ($gender) {
            Biodata::create([
                'user_id' => $user->id,
                'gender' => $gender,
                'age' => 30,
                'maritial_status' => 'single',
                'mosque_id' => $mosqueId,
                'is_approved' => 1,
            ]);
        }

        if ($mosqueId) {
            MosqueMembership::create([
                'mosque_id' => $mosqueId,
                'user_id' => $user->id,
                'role' => 'member',
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        return $user;
    }

    public function test_mosque_membership_isolation()
    {
        $mosqueA = Mosque::create(['name' => 'Mosquée A', 'slug' => 'mosquee-a', 'status' => 'active']);
        $mosqueB = Mosque::create(['name' => 'Mosquée B', 'slug' => 'mosquee-b', 'status' => 'active']);

        $brotherA = $this->makeUser('brotherA@test.com', 'member', 'male', $mosqueA->id);
        $sisterA = $this->makeUser('sisterA@test.com', 'member', 'female', $mosqueA->id);
        $sisterB = $this->makeUser('sisterB@test.com', 'member', 'female', $mosqueB->id);

        // Brother A sees sister A (same mosque) but NOT sister B (different mosque)
        $response = $this->actingAs($brotherA)->getJson("/api/mosque/{$mosqueA->slug}");
        $response->assertOk();
        $members = collect($response->json('members'))->pluck('id');
        $this->assertTrue($members->contains($sisterA->biodata->id));
        $this->assertFalse($members->contains($sisterB->biodata->id));
    }

    public function test_outsider_cannot_see_mosque_members()
    {
        $mosque = Mosque::create(['name' => 'Mosquée X', 'slug' => 'mosquee-x', 'status' => 'active']);
        $outsider = $this->makeUser('outsider@test.com');

        $response = $this->actingAs($outsider)->getJson("/api/mosque/{$mosque->slug}");
        $response->assertStatus(403);
    }

    public function test_only_men_can_send_proposals()
    {
        $mosque = Mosque::create(['name' => 'Mosquée Y', 'slug' => 'mosquee-y', 'status' => 'active']);
        $brother = $this->makeUser('brotherY@test.com', 'member', 'male', $mosque->id);
        $sister = $this->makeUser('sisterY@test.com', 'member', 'female', $mosque->id);

        // Sister tries to propose → forbidden
        $response = $this->actingAs($sister)->postJson('/api/mosque/propose', ['receiver_id' => $brother->id]);
        $response->assertStatus(403);
    }

    public function test_proposal_flow_brother_to_sister()
    {
        $mosque = Mosque::create(['name' => 'Mosquée Z', 'slug' => 'mosquee-z', 'status' => 'active']);
        $brother = $this->makeUser('brotherZ@test.com', 'member', 'male', $mosque->id);
        $sister = $this->makeUser('sisterZ@test.com', 'member', 'female', $mosque->id);

        // Brother proposes to sister
        $response = $this->actingAs($brother)->postJson('/api/mosque/propose', [
            'receiver_id' => $sister->id,
            'message' => 'Salam, je suis intéressé.',
        ]);
        $response->assertStatus(201);

        // Duplicate proposal rejected
        $dup = $this->actingAs($brother)->postJson('/api/mosque/propose', ['receiver_id' => $sister->id]);
        $dup->assertStatus(422);

        // Sister accepts
        $proposalId = $response->json('proposal.id');
        $accept = $this->actingAs($sister)->postJson("/api/mosque/proposals/{$proposalId}/accept");
        $accept->assertOk();
    }
}
