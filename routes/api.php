use App\Http\Controllers\Api\TutorController;
use App\Http\Controllers\Api\TutoringSessionController;
use App\Http\Controllers\Api\TutoringFeedbackController;

// Routes pour le système de tutorat
Route::prefix('tutoring')->group(function () {
    // Routes pour les tuteurs
    Route::get('tutors', [TutorController::class, 'index']);
    Route::post('tutors', [TutorController::class, 'store']);
    Route::get('tutors/{tutor}', [TutorController::class, 'show']);
    Route::put('tutors/{tutor}', [TutorController::class, 'update']);
    Route::delete('tutors/{tutor}', [TutorController::class, 'destroy']);
    Route::put('tutors/{tutor}/availability', [TutorController::class, 'updateAvailability']);
    Route::get('tutors/{tutor}/sessions', [TutorController::class, 'getSessions']);
    Route::get('tutors/{tutor}/feedback', [TutorController::class, 'getFeedback']);

    // Routes pour les sessions de tutorat
    Route::get('sessions', [TutoringSessionController::class, 'index']);
    Route::post('sessions', [TutoringSessionController::class, 'store']);
    Route::get('sessions/{session}', [TutoringSessionController::class, 'show']);
    Route::put('sessions/{session}', [TutoringSessionController::class, 'update']);
    Route::delete('sessions/{session}', [TutoringSessionController::class, 'destroy']);
    Route::post('sessions/{session}/start', [TutoringSessionController::class, 'start']);
    Route::post('sessions/{session}/complete', [TutoringSessionController::class, 'complete']);
    Route::post('sessions/{session}/cancel', [TutoringSessionController::class, 'cancel']);
    Route::post('sessions/{session}/participants', [TutoringSessionController::class, 'addParticipant']);
    Route::post('sessions/{session}/resources', [TutoringSessionController::class, 'addResource']);
    Route::post('sessions/{session}/objectives', [TutoringSessionController::class, 'addLearningObjective']);
    Route::post('sessions/{session}/objectives/achieve', [TutoringSessionController::class, 'markObjectiveAsAchieved']);

    // Routes pour les feedbacks
    Route::get('feedback', [TutoringFeedbackController::class, 'index']);
    Route::post('feedback', [TutoringFeedbackController::class, 'store']);
    Route::get('feedback/{feedback}', [TutoringFeedbackController::class, 'show']);
    Route::put('feedback/{feedback}', [TutoringFeedbackController::class, 'update']);
    Route::delete('feedback/{feedback}', [TutoringFeedbackController::class, 'destroy']);
    Route::get('sessions/{session}/feedback', [TutoringFeedbackController::class, 'getSessionFeedback']);
    Route::get('tutors/{tutorId}/feedback', [TutoringFeedbackController::class, 'getTutorFeedback']);
}); 