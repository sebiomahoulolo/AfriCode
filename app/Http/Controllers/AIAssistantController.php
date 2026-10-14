<?php

namespace App\Http\Controllers;

use App\Services\AIAssistantService;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    public function __construct(private readonly AIAssistantService $aiService)
    {
    }

    public function chat(Request $request)
    {
        $data = $request->validate(['message' => 'required|string|max:1000']);
        $response = $this->aiService->chat($request->user(), $data['message']);
        return response()->json(['success' => true, 'response' => $response]);
    }
}


