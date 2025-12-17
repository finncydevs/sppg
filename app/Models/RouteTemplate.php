<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteTemplate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function driver()
    {
        return $this->belongsTo(Employee::class, 'default_driver_id');
    }

    public function destinations()
    {
        return $this->hasMany(RouteTemplateDestination::class);
    }
}
