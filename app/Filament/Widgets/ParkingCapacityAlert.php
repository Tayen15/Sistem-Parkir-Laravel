<?php

namespace App\Filament\Widgets;

use App\Models\AreaParkir;
use App\Models\Transaction;
use Filament\Widgets\Widget;

class ParkingCapacityAlert extends Widget
{
    protected static string $view = 'filament.widgets.parking-capacity-alert';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    const THRESHOLD_SLOTS = 5;
    const THRESHOLD_PERCENTAGE = 0.10;

    public function getAlmostFullAreas(): \Illuminate\Database\Eloquent\Collection
    {
        return AreaParkir::all()->map(function ($area) {
            $occupiedSlots = Transaction::where('area_parkir_id', $area->id)->whereNull('end')->count();
            $area->available_slots_count = $area->kapasitas - $occupiedSlots;
            $area->occupancy_percentage = ($area->kapasitas > 0) ? ($occupiedSlots / $area->kapasitas) : 0;
            return $area;
        })->filter(function ($area) {
            // Filter area yang memenuhi kriteria 'hampir penuh'
            return $area->available_slots_count <= self::THRESHOLD_SLOTS || 
                   $area->occupancy_percentage >= (1 - self::THRESHOLD_PERCENTAGE);
        });
    }

    public function hasAlmostFullAreas(): bool
    {
        return $this->getAlmostFullAreas()->isNotEmpty();
    }
}
