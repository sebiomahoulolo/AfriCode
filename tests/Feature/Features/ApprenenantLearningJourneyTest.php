<?php

namespace Tests\Feature\Features;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Mode        $certification = Certification::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'issued_at' => now(),
            'certificate_identifier' => 'CERT-' . $user->id . '-' . $course->id . '-' . time()
        ]);ollment;
use App\Models\LessonCompletion;
use App\Models\Certification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class ApprenenantLearningJourneyTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test that an apprenant can access the dashboard.
     */
    public function test_apprenant_can_access_dashboard()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get(route('apprenant.dashboard'));
        
        $response->assertStatus(200);
        $response->assertViewIs('apprenants.dashboard');
    }
    
    /**
     * Test that an apprenant can view a course lesson.
     */
    public function test_apprenant_can_view_course_lesson()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a course with modules and lessons
        $course = Course::factory()->create();
        $module = Module::factory()->create(['course_id' => $course->id]);
        $lesson = Lesson::factory()->create(['module_id' => $module->id]);
        
        // Enroll the user in the course
        $enrollment = Enrollment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);
        
        // Access the lesson
        $response = $this->actingAs($user)
                         ->get(route('apprenant.lesson', ['lessonId' => $lesson->id]));
        
        $response->assertStatus(200);
        $response->assertViewIs('apprenants.lesson');
    }
    
    /**
     * Test that an apprenant can mark a lesson as completed.
     */
    public function test_apprenant_can_complete_lesson()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a course with modules and lessons
        $course = Course::factory()->create();
        $module = Module::factory()->create(['course_id' => $course->id]);
        $lesson = Lesson::factory()->create(['module_id' => $module->id]);
        
        // Enroll the user in the course
        $enrollment = Enrollment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);
        
        // Mark lesson as completed
        $response = $this->actingAs($user)
                         ->post(route('apprenant.lesson.complete', ['lessonId' => $lesson->id]));
        
        // Assert the lesson completion was recorded
        $this->assertDatabaseHas('lesson_completions', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id
        ]);
        
        // Assert that the enrollment progress was updated
        $updatedEnrollment = Enrollment::find($enrollment->id);
        $this->assertEquals(100, $updatedEnrollment->progress_percentage);
    }
    
    /**
     * Test that certification is created when all lessons are completed.
     */
    public function test_certification_is_created_when_course_completed()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a course with module and lesson
        $course = Course::factory()->create();
        $module = Module::factory()->create(['course_id' => $course->id]);
        $lesson = Lesson::factory()->create(['module_id' => $module->id]);
        
        // Enroll the user in the course
        $enrollment = Enrollment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);
        
        // Mark lesson as completed
        $response = $this->actingAs($user)
                         ->post(route('apprenant.lesson.complete', ['lessonId' => $lesson->id]));
        
        // Assert that certification was created
        $this->assertDatabaseHas('certifications', [
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);
    }
    
    /**
     * Test that apprenant can view certification.
     */
    public function test_apprenant_can_view_certification()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a course 
        $course = Course::factory()->create();
        
        // Create certification
        $certification = Certification::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'issued_at' => now(),
            'certificate_identifier' => 'CERT-' . $user->id . '-' . $course->id . '-' . time()
        ]);
        
        // View certification
        $response = $this->actingAs($user)
                         ->get(route('apprenant.certification.download', ['certificationId' => $certification->id]));
        
        $response->assertStatus(200);
        $response->assertViewIs('apprenants.certification');
    }
}
