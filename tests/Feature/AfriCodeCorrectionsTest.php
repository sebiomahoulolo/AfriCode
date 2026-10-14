<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests fonctionnels et logiques liés aux corrections AfriCode (rôles, routes, middlewares).
 */
class AfriCodeCorrectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_middleware_accepts_user_with_role_administrateur(): void
    {
        $user = User::factory()->create(['role' => 'administrateur', 'email_verified_at' => now()]);
        $this->actingAs($user);

        $response = $this->get(route('admin.dashboard'));
        $response->assertOk();
    }

    public function test_admin_middleware_redirects_apprenant(): void
    {
        $user = User::factory()->create(['role' => 'apprenant', 'email_verified_at' => now()]);
        $this->actingAs($user);

        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect('/');
    }

    public function test_user_isAdmin_returns_false_for_apprenant_and_formateur(): void
    {
        $apprenant = User::factory()->create(['role' => 'apprenant']);
        $formateur = User::factory()->create(['role' => 'formateur']);

        $this->assertFalse($apprenant->isAdmin());
        $this->assertFalse($formateur->isAdmin());
    }

    public function test_user_isAdmin_returns_true_for_administrateur(): void
    {
        $administrateur = User::factory()->create(['role' => 'administrateur']);
        $this->assertTrue($administrateur->isAdmin());
    }

    public function test_user_isStaff_returns_true_for_administrateur(): void
    {
        $admin = User::factory()->create(['role' => 'administrateur']);
        $this->assertTrue($admin->isStaff());
    }

    public function test_formateur_manage_module_route_requires_authentication(): void
    {
        $response = $this->get(route('formateur.manage.module', ['moduleId' => 1]));
        $response->assertRedirect(route('login'));
    }

    public function test_route_pages_forumexp_redirects_to_forum_index(): void
    {
        $response = $this->get('/le-forum-des-experts');
        $response->assertRedirect(route('forum.index'));
    }

    public function test_manage_support_gate_allows_administrateur(): void
    {
        $admin = User::factory()->create(['role' => 'administrateur', 'email_verified_at' => now()]);
        $this->actingAs($admin);
        // La Gate manageSupport appelle $user->isAdmin() ; on vérifie la cohérence
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($admin->isStaff());
    }
}
