<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
    }

    private function member(): User
    {
        return User::create([
            'name' => 'Member',
            'email' => 'member@test.com',
            'password' => bcrypt('password123'),
            'role' => 'member',
        ]);
    }

    public function test_admin_panel_requires_authentication()
    {
        $this->get('/panel')->assertRedirect('/login');
    }

    public function test_admin_panel_blocks_non_admin()
    {
        $this->actingAs($this->member())->get('/panel')->assertRedirect('/login');
    }

    public function test_admin_panel_allows_admin()
    {
        $this->actingAs($this->admin())->get('/panel')->assertOk();
    }

    public function test_admin_api_blocks_member()
    {
        $this->actingAs($this->member())
            ->getJson('/api/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_admin_api_allows_admin()
    {
        $this->actingAs($this->admin())
            ->getJson('/api/admin/dashboard')
            ->assertOk()
            ->assertJsonStructure(['metrics' => ['total_users', 'total_mosques']]);
    }
}
