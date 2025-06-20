<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaParkir extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'area_parkir_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'kampus_id');
    }
}
