<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChargingStation;
use Illuminate\Http\Request;

class StationController extends Controller
{

    public function index()
    {
        $stations = ChargingStation::all();
        return response()->json([
            'message'  => 'Stations retrieved successfully',
            'stations' => $stations,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
            'address'        => 'nullable|string',
            'connector_type' => 'required|in:Type1,Type2,CCS,CHAdeMO,Tesla',
            'power_kw'       => 'required|numeric',
        ]);

        $station = ChargingStation::create([
            'name'           => $request->name,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'address'        => $request->address,
            'connector_type' => $request->connector_type,
            'power_kw'       => $request->power_kw,
            'is_available'   => true,
        ]);

        return response()->json([
            'message' => 'Station created successfully',
            'station' => $station,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $station = ChargingStation::find($id);

        if (!$station) {
            return response()->json([
                'message' => 'Station not found',
            ], 404);
        }

        $station->update($request->all());

        return response()->json([
            'message' => 'Station updated successfully',
            'station' => $station,
        ]);
    }


    public function destroy($id)
    {
        $station = ChargingStation::find($id);

        if (!$station) {
            return response()->json([
                'message' => 'Station not found',
            ], 404);
        }

        $station->delete();

        return response()->json([
            'message' => 'Station deleted successfully',
        ]);
    }
}
