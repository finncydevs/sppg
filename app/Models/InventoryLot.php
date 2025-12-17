<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLot extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'received_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Riwayat transaksi spesifik untuk lot ini
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
