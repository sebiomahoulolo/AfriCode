<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tutor::with('user');

        // Filtres
        if ($request->has('expertise')) {
            $query->byExpertise($request->expertise);
        }
        if ($request->has('language')) {
            $query->byLanguage($request->language);
        }
        if ($request->has('min_rating')) {
            $query->byRating($request->min_rating);
        }
        if ($request->has('available')) {
            $query->available();
        }

        // Tri
        $sortBy = $request->get('sort_by', 'rating');
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
            'user_id' => 'required|exists:users,id',
            'bio' => 'nullable|string|max:1000',
            'expertise_areas' => 'required|array',
            'expertise_areas.*' => 'string',
            'languages' => 'required|array',
            'languages.*' => 'string',
            'hourly_rate' => 'required|numeric|min:0',
            'availability_schedule' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tutor = Tutor::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Tutor profile created successfully',
            'data' => $tutor->load('user')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tutor $tutor)
    {
        return response()->json([
            'status' => 'success',
            'data' => $tutor->load(['user', 'sessions' => function ($query) {
                $query->where('status', 'completed')
                    ->with('feedback')
                    ->latest();
            }])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tutor $tutor)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'nullable|string|max:1000',
            'expertise_areas' => 'sometimes|array',
            'expertise_areas.*' => 'string',
            'languages' => 'sometimes|array',
            'languages.*' => 'string',
            'hourly_rate' => 'sometimes|numeric|min:0',
            'is_available' => 'sometimes|boolean',
            'availability_schedule' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tutor->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Tutor profile updated successfully',
            'data' => $tutor->load('user')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tutor $tutor)
    {
        $tutor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tutor profile deleted successfully'
        ]);
    }

    public function updateAvailability(Request $request, Tutor $tutor)
    {
        $validator = Validator::make($request->all(), [
            'is_available' => 'required|boolean',
            'availability_schedule' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tutor->setAvailability($request->is_available);
        if ($request->has('availability_schedule')) {
            $tutor->updateSchedule($request->availability_schedule);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Availability updated successfully',
            'data' => $tutor
        ]);
    }

    public function getSessions(Tutor $tutor, Request $request)
    {
        $query = $tutor->sessions()->with(['student', 'participants', 'learningObjectives']);

        if ($request->has('status')) {
            $query->byStatus($request->status);
        }
        if ($request->has('type')) {
            $query->byType($request->type);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->latest()->paginate(10)
        ]);
    }

    public function getFeedback(Tutor $tutor)
    {
        $feedback = $tutor->sessions()
            ->whereHas('feedback')
            ->with('feedback')
            ->get()
            ->pluck('feedback')
            ->flatten();

        return response()->json([
            'status' => 'success',
            'data' => [
                'feedback' => $feedback,
                'average_rating' => $tutor->rating,
                'total_sessions' => $tutor->total_sessions
            ]
        ]);
    }
}
