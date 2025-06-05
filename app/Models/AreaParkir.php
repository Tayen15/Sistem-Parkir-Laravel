<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'area_parkir_id');
    }

    public function kampus()
    {
        return $this->belongsTo(Campus::class, 'kampus_id');
    }
}
