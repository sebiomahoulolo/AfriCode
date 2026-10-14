<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePaymentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_paid_course_without_payment()
    {
        // Créer un utilisateur apprenant
        $user = User::factory()->create(['role' => 'apprenant']);
        
        // Créer un cours payant
        $course = Course::factory()->create([
            'price' => 50.00,
            'status' => 'published'
        ]);
        
        // Créer une inscription sans paiement
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);
        
        // Tenter d'accéder au cours
        $response = $this->actingAs($user)
            ->get(route('apprenant.course.access', ['courseId' => $course->id]));
        
        // Vérifier la redirection vers la page d'inscription
        $response->assertRedirect(route('enrollment.show', $course));
        $response->assertSessionHas('error', 'Le paiement pour ce cours n\'a pas été effectué. Veuillez compléter votre paiement pour accéder au cours.');
    }

    public function test_user_can_access_paid_course_with_successful_payment()
    {
        // Créer un utilisateur apprenant
        $user = User::factory()->create(['role' => 'apprenant']);
        
        // Créer un cours payant
        $course = Course::factory()->create([
            'price' => 50.00,
            'status' => 'published'
        ]);
        
        // Créer une inscription
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);
        
        // Créer un paiement réussi
        Payment::create([
            'user_id' => $user->id,
            'enrollment_id' => $enrollment->id,
            'payable_id' => $course->id,
            'payable_type' => 'App\\Models\\Course',
            'amount' => $course->price,
            'currency' => 'EUR',
            'payment_gateway' => 'stripe',
            'status' => 'succeeded',
            'paid_at' => now()
        ]);
        
        // Tenter d'accéder au cours
        $response = $this->actingAs($user)
            ->get(route('apprenant.course.access', ['courseId' => $course->id]));
        
        // Vérifier que l'accès est autorisé (redirection vers une leçon ou le contenu)
        $response->assertStatus(200);
    }

    public function test_user_can_access_free_course_without_payment()
    {
        // Créer un utilisateur apprenant
        $user = User::factory()->create(['role' => 'apprenant']);
        
        // Créer un cours gratuit
        $course = Course::factory()->create([
            'price' => 0,
            'status' => 'published'
        ]);
        
        // Créer une inscription
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);
        
        // Tenter d'accéder au cours
        $response = $this->actingAs($user)
            ->get(route('apprenant.course.access', ['courseId' => $course->id]));
        
        // Vérifier que l'accès est autorisé
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_course_without_enrollment()
    {
        // Créer un utilisateur apprenant
        $user = User::factory()->create(['role' => 'apprenant']);
        
        // Créer un cours
        $course = Course::factory()->create([
            'price' => 50.00,
            'status' => 'published'
        ]);
        
        // Tenter d'accéder au cours sans inscription
        $response = $this->actingAs($user)
            ->get(route('apprenant.course.access', ['courseId' => $course->id]));
        
        // Vérifier la redirection vers le dashboard
        $response->assertRedirect(route('apprenant.dashboard'));
        $response->assertSessionHas('error', 'Vous n\'êtes pas inscrit à ce cours');
    }

    public function test_course_canBeAccessedBy_method_includes_payment_check()
    {
        // Créer un utilisateur apprenant
        $user = User::factory()->create(['role' => 'apprenant']);
        
        // Créer un cours payant
        $course = Course::factory()->create([
            'price' => 50.00,
            'status' => 'published'
        ]);
        
        // Créer une inscription sans paiement
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);
        
        // Vérifier que canBeAccessedBy retourne false
        $this->assertFalse($course->canBeAccessedBy($user));
        
        // Créer un paiement réussi
        Payment::create([
            'user_id' => $user->id,
            'enrollment_id' => $enrollment->id,
            'payable_id' => $course->id,
            'payable_type' => 'App\\Models\\Course',
            'amount' => $course->price,
            'currency' => 'EUR',
            'payment_gateway' => 'stripe',
            'status' => 'succeeded',
            'paid_at' => now()
        ]);
        
        // Vérifier que canBeAccessedBy retourne true maintenant
        $this->assertTrue($course->canBeAccessedBy($user));
    }
}



