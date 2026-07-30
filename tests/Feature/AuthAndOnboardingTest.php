<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_onboarding_page_can_be_rendered(): void
    {
        $response = $this->get('/onboarding');

        $response->assertStatus(200);
    }

    public function test_user_can_register_and_is_redirected_to_onboarding(): void
    {
        $response = $this->post('/register', [
            'name' => 'Nguyễn Văn Anh',
            'email' => 'vananh@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/onboarding');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'vananh@example.com']);
    }

    public function test_user_can_complete_onboarding_wizard_and_create_workspace(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/onboarding', [
            'groom_name' => 'Văn Anh',
            'bride_name' => 'Bích Trần',
            'wedding_date' => '2026-10-24',
            'budget_cap' => 250000000,
            'estimated_guests' => 200,
            'invitation_template' => 'romantic-pastel',
        ]);

        $response->assertRedirect('/wedding/timeline');
        $this->assertDatabaseHas('workspaces', [
            'budget_cap' => 250000000,
        ]);
    }
}
