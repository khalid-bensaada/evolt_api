<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'charging_station_id',
        'reservation_id',
        'started_at',
        'ended_at',
        'energy_delivered',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'energy_delivered' => 'float',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function chargingStation()
    {
        return $this->belongsTo(ChargingStation::class);
    }


    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
