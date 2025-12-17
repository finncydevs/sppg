<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'production_date' => 'date',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    // Hasil produksi ini dikirim ke mana saja?
    public function shipmentDestinations()
    {
        return $this->hasMany(ShipmentDestination::class);
    }
}
