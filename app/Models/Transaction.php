<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'kendaraan_id');
    }

    public function areaParkir()
    {
        return $this->belongsTo(AreaParkir::class, 'area_parkir_id');
    }

    public function calculateDuration($start, $end = null)
    {
        $startTime = Carbon::parse($start);
        $endTime = $end ? Carbon::parse($end) : now();

        $diff = $startTime->diff($endTime);
        return $diff->h . 'j ' . $diff->i . 'm';
    }

    public function calculateFee($start, $end = null, VehicleType $vehicleType)
    {
        $startTime = Carbon::parse($start);
        $endTime = $end ? Carbon::parse($end) : now();

        $totalMinutes = $startTime->diffInMinutes($endTime);

        $rate = $vehicleType->fee; 

        $freeDurationThresholdMinutes = 15;

        if ($totalMinutes <= $freeDurationThresholdMinutes) {
            return 0; 
        }

        $hours = ceil($totalMinutes / 60);

        return $hours * $rate;
    }
}
