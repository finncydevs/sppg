<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
    ];

    // Helper untuk dynamic reference
    public function getReferenceAttribute()
    {
        if (!$this->reference_type || !$this->reference_id) return null;

        $modelClass = 'App\\Models\\' . $this->reference_type;
        if (class_exists($modelClass)) {
            return $modelClass::find($this->reference_id);
        }
        return null;
    }
}
