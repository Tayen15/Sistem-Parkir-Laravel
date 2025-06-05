<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class, 'jenis_kendaraan');
    }
}
