<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'planned_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
