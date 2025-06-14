<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleType extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = ['id'];

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class, 'jenis_kendaraan');
    }
}
