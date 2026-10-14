<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TutoringFeedback;
use App\Models\TutoringSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TutoringFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TutoringFeedback::with(['session.tutor.user', 'session.student', 'user']);

        if ($request->has('session_id')) {
            $query->where('session_id', $request->session_id);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('rating')) {
            $query->byRating($request->rating);
        }
        if ($request->has('with_comments')) {
            $query->withComments();
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->latest()->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:tutoring_sessions,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'specific_ratings' => 'nullable|array',
            'specific_ratings.*' => 'integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $session = TutoringSession::findOrFail($request->session_id);
        
        // Vérifier si la session est terminée
        if (!$session->isCompleted()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot provide feedback for a session that is not completed'
            ], 400);
        }

        // Vérifier si l'utilisateur a déjà donné son feedback
        if ($session->feedback()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already provided feedback for this session'
            ], 400);
        }

        $feedback = TutoringFeedback::create($request->all());

        // Mettre à jour la note moyenne du tuteur
        $session->tutor->updateRating();

        return response()->json([
            'status' => 'success',
            'message' => 'Feedback submitted successfully',
            'data' => $feedback->load(['session.tutor.user', 'session.student', 'user'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TutoringFeedback $feedback)
    {
        return response()->json([
            'status' => 'success',
            'data' => $feedback->load(['session.tutor.user', 'session.student', 'user'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TutoringFeedback $feedback)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'specific_ratings' => 'nullable|array',
            'specific_ratings.*' => 'integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $feedback->update($request->all());

        // Mettre à jour la note moyenne du tuteur
        $feedback->session->tutor->updateRating();

        return response()->json([
            'status' => 'success',
            'message' => 'Feedback updated successfully',
            'data' => $feedback->load(['session.tutor.user', 'session.student', 'user'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TutoringFeedback $feedback)
    {
        $feedback->delete();

        // Mettre à jour la note moyenne du tuteur
        $feedback->session->tutor->updateRating();

        return response()->json([
            'status' => 'success',
            'message' => 'Feedback deleted successfully'
        ]);
    }

    public function getSessionFeedback(TutoringSession $session)
    {
        $feedback = $session->feedback()->with(['user'])->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'feedback' => $feedback,
                'average_rating' => $feedback->avg('rating'),
                'total_feedback' => $feedback->count()
            ]
        ]);
    }

    public function getTutorFeedback(Request $request, $tutorId)
    {
        $query = TutoringFeedback::whereHas('session', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId);
        })->with(['session', 'user']);

        if ($request->has('rating')) {
            $query->byRating($request->rating);
        }
        if ($request->has('with_comments')) {
            $query->withComments();
        }

        $feedback = $query->latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => [
                'feedback' => $feedback,
                'average_rating' => $feedback->avg('rating'),
                'total_feedback' => $feedback->total()
            ]
        ]);
    }
}
