<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'departure_time' => 'datetime',
    ];

    // Driver diambil dari tabel employees
    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    // Tujuan pengiriman (Delivery Lines)
    public function destinations()
    {
        return $this->hasMany(ShipmentDestination::class);
    }
}
