<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChargingStation;
use App\Models\Reservation;
use App\Models\ChargingSession;
use App\Models\User;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalStations     = ChargingStation::count();
        $availableStations = ChargingStation::where('is_available', true)->count();

        $totalReservations   = Reservation::count();
        $enCoursReservations = Reservation::where('status', 'en_cours')->count();
        $payeeReservations   = Reservation::where('status', 'payee')->count();

        $lastReservations = Reservation::with(['user', 'chargingStation'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Dashboard retrieved successfully',
            'data'    => [
                'stats' => [
                    'total_stations'     => $totalStations,
                    'available_stations' => $availableStations,
                    'total_reservations' => $totalReservations,
                    'en_cours'           => $enCoursReservations,
                    'payee'              => $payeeReservations,
                ],
                'last_reservations' => $lastReservations,
            ],
        ]);
    }

    public function dashboard()
    {

        $totalReservations   = Reservation::count();
        $enCoursReservations = Reservation::where('status', 'en_cours')->count();
        $payeeReservations   = Reservation::where('status', 'payee')->count();
        $annuleeReservations = Reservation::where('status', 'annulee')->count();


        $lastReservations = Reservation::with(['user', 'chargingStation'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Dashboard retrieved successfully',
            'data'    => [
                'stats' => [
                    'total'    => $totalReservations,
                    'en_cours' => $enCoursReservations,
                    'payee'    => $payeeReservations,
                    'annulee'  => $annuleeReservations,
                ],
                'last_reservations' => $lastReservations,
            ],
        ]);
    }
}
