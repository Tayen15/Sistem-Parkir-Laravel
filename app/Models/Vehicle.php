<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'kendaraan_id');
    }
}
