<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ChargingStation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'charging_station_id' => 'required|exists:charging_stations,id',
            'start_time'          => 'required|date|after:now',
            'duration'            => 'required|integer|min:1|max:480',
        ]);

        $station = ChargingStation::find($request->charging_station_id);

        if (!$station->is_available) {
            return response()->json([
                'message' => 'Charging station is not available',
            ], 400);
        }

        $endTime = Carbon::parse($request->start_time)
            ->addMinutes($request->duration);

        $reservation = Reservation::create([
            'user_id'             => auth()->id(),
            'charging_station_id' => $request->charging_station_id,
            'start_time'          => $request->start_time,
            'duration'            => $request->duration,
            'end_time'            => $endTime,
            'status'              => 'en_cours', // ← تلقائي
        ]);

        $station->update(['is_available' => false]);

        return response()->json([
            'message'     => 'Reservation created successfully',
            'reservation' => $reservation,
        ], 201);
    }


    public function mesReservations(Request $request)
    {
        $reservations = auth()->user()
            ->reservations()
            ->with('chargingStation')
            ->get();

        return response()->json([
            'message'      => 'Reservations retrieved successfully',
            'reservations' => $reservations,
        ]);
    }


    public function pay(Request $request, $id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$reservation) {
            return response()->json([
                'message' => 'Reservation not found',
            ], 404);
        }

        if ($reservation->status !== 'en_cours') {
            return response()->json([
                'message' => 'Only en_cours reservations can be paid',
            ], 400);
        }

        $reservation->update(['status' => 'payee']);

        return response()->json([
            'message'     => 'Reservation paid successfully',
            'reservation' => $reservation,
        ]);
    }


    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$reservation) {
            return response()->json([
                'message' => 'Reservation not found',
            ], 404);
        }

        $reservation->update(['status' => 'annulee']);

        $reservation->chargingStation->update(['is_available' => true]);

        return response()->json([
            'message' => 'Reservation cancelled successfully',
        ]);
    }
}
