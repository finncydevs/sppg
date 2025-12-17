<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Item ini ada di resep mana saja? (via pivot table recipe_items)
    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }

    // Item ini ada di PO mana saja?
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Stok fisik (Batch/Lots)
    public function inventoryLots()
    {
        return $this->hasMany(InventoryLot::class);
    }

    // Riwayat keluar masuk barang
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
