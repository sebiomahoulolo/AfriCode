<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PushSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint'                         => 'required|url',
            'keys.auth'                        => 'required|string',
            'keys.p256dh'                      => 'required|string',
        ]);

        if (!Schema::hasTable('push_subscriptions')) {
            return response()->json(['message' => 'Push not configured'], 503);
        }

        DB::table('push_subscriptions')->updateOrInsert(
            [
                'user_id'  => auth()->id(),
                'endpoint' => $request->input('endpoint'),
            ],
            [
                'public_key'  => $request->input('keys.p256dh'),
                'auth_token'  => $request->input('keys.auth'),
                'updated_at'  => now(),
                'created_at'  => now(),
            ]
        );

        return response()->json(['message' => 'Abonnement enregistré'], 201);
    }

    public function destroy(Request $request): JsonResponse
    {
        if (!Schema::hasTable('push_subscriptions')) {
            return response()->json(['message' => 'Push not configured'], 503);
        }

        DB::table('push_subscriptions')
            ->where('user_id', auth()->id())
            ->where('endpoint', $request->input('endpoint'))
            ->delete();

        return response()->json(['message' => 'Abonnement supprimé']);
    }
}
