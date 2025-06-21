<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TutoringSession;
use App\Models\TutoringSessionParticipant;
use App\Models\TutoringResource;
use App\Models\TutoringLearningObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TutoringSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TutoringSession::with(['tutor.user', 'student', 'participants']);

        // Filtres
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }
        if ($request->has('type')) {
            $query->byType($request->type);
        }
        if ($request->has('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'start_time');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        return response()->json([
            'status' => 'success',
            'data' => $query->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tutor_id' => 'required|exists:tutors,id',
            'student_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:one_on_one,group,workshop',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:15',
            'price' => 'required|numeric|min:0',
            'meeting_link' => 'nullable|url',
            'participants' => 'required_if:type,group,workshop|array',
            'participants.*' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $session = TutoringSession::create($request->all());

        // Ajouter les participants pour les sessions de groupe
        if ($request->has('participants')) {
            foreach ($request->participants as $participantId) {
                $session->addParticipant($participantId);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tutoring session created successfully',
            'data' => $session->load(['tutor.user', 'student', 'participants'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TutoringSession $session)
    {
        return response()->json([
            'status' => 'success',
            'data' => $session->load([
                'tutor.user',
                'student',
                'participants.user',
                'resources',
                'learningObjectives',
                'feedback'
            ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TutoringSession $session)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date|after:now',
            'end_time' => 'sometimes|date|after:start_time',
            'duration_minutes' => 'sometimes|integer|min:15',
            'price' => 'sometimes|numeric|min:0',
            'meeting_link' => 'nullable|url',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $session->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Tutoring session updated successfully',
            'data' => $session->load(['tutor.user', 'student', 'participants'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TutoringSession $session)
    {
        if ($session->isInProgress()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete a session that is in progress'
            ], 400);
        }

        $session->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tutoring session deleted successfully'
        ]);
    }

    public function start(TutoringSession $session)
    {
        if ($session->start()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Session started successfully',
                'data' => $session
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Cannot start the session'
        ], 400);
    }

    public function complete(TutoringSession $session)
    {
        if ($session->complete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Session completed successfully',
                'data' => $session
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Cannot complete the session'
        ], 400);
    }

    public function cancel(TutoringSession $session)
    {
        if ($session->cancel()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Session cancelled successfully',
                'data' => $session
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Cannot cancel the session'
        ], 400);
    }

    public function addParticipant(Request $request, TutoringSession $session)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:student,observer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $participant = $session->addParticipant($request->user_id, $request->role);

        return response()->json([
            'status' => 'success',
            'message' => 'Participant added successfully',
            'data' => $participant->load('user')
        ]);
    }

    public function addResource(Request $request, TutoringSession $session)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'file_path' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $resource = $session->addResource($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Resource added successfully',
            'data' => $resource
        ]);
    }

    public function addLearningObjective(Request $request, TutoringSession $session)
    {
        $validator = Validator::make($request->all(), [
            'objective' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $objective = $session->addLearningObjective($request->objective);

        return response()->json([
            'status' => 'success',
            'message' => 'Learning objective added successfully',
            'data' => $objective
        ]);
    }

    public function markObjectiveAsAchieved(Request $request, TutoringSession $session)
    {
        $validator = Validator::make($request->all(), [
            'objective_id' => 'required|exists:tutoring_learning_objectives,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($session->markObjectiveAsAchieved($request->objective_id)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Objective marked as achieved successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to mark objective as achieved'
        ], 400);
    }
}
