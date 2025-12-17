<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function lot()
    {
        return $this->belongsTo(InventoryLot::class, 'inventory_lot_id');
    }

    // Helper untuk mengambil object referensi (misal: PO atau Produksi)
    // Walaupun di DB tidak relasi, di sini kita bantu Laravel mencarinya
    public function getReferenceAttribute()
    {
        if (!$this->reference_type || !$this->reference_id) return null;

        // Contoh logic sederhana mapping string ke Model
        // 'PurchaseOrder' -> App\Models\PurchaseOrder
        $modelClass = 'App\\Models\\' . $this->reference_type;
        if (class_exists($modelClass)) {
            return $modelClass::find($this->reference_id);
        }
        return null;
    }
}
