<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = ['id'];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'jenis_kendaraan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'pemilik');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'kendaraan_id');
    }
}
