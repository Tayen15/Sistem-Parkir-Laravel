<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function areaParkirs()
    {
        return $this->hasMany(AreaParkir::class, 'kampus_id');
    }
}
