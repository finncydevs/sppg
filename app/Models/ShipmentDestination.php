<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDestination extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Opsional: Jika pengiriman ini spesifik mengambil dari WO tertentu
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }
    public function proofs()
    {
        return $this->hasMany(ShipmentProof::class);
    }
}
