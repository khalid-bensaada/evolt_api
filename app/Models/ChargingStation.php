<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargingStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address',
        'connector_type',
        'power_kw',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'power_kw' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    
    public function chargingSessions()
    {
        return $this->hasMany(ChargingSession::class);
    }
}
