<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Sekolah menerima banyak pengiriman
    public function shipmentDestinations()
    {
        return $this->hasMany(ShipmentDestination::class);
    }
}
