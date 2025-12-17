<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentProof extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function destination()
    {
        return $this->belongsTo(ShipmentDestination::class, 'shipment_destination_id');
    }
}
