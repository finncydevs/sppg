<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi balik ke User (Akun Login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Seorang karyawan bisa menjadi Driver untuk banyak pengiriman
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'driver_id');
    }

    // Relasi ke Gaji
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    // Relasi ke Absensi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
