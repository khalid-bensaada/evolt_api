<?php

namespace App\Http\Controllers;

use App\Models\ChargingSession;
use Illuminate\Http\Request;

class ChargingSessionController extends Controller
{
    
    public function index(Request $request)
    {
        $sessions = ChargingSession::where('user_id', $request->user()->id)
            ->with(['chargingStation', 'reservation'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message'  => 'Sessions retrieved successfully',
            'sessions' => $sessions,
        ]);
    }

    
    public function show(Request $request, $id)
    {
        $session = ChargingSession::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['chargingStation', 'reservation'])
            ->first();

        if (!$session) {
            return response()->json([
                'message' => 'Session not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Session retrieved successfully',
            'session' => $session,
        ]);
    }
}