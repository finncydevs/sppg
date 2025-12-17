<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Detail bahan baku (BOM)
    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }

    // Resep dipakai di Work Order (Produksi)
    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class);
    }
}
