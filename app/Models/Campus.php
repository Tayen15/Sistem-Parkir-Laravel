<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function parkirArea()
    {
        return $this->hasMany(AreaParkir::class, 'id');
    }
}
