<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = [
            ['name' => 'mobil', 'fee' => 5000],
            ['name' => 'motor', 'fee' => 2000],
        ];

        VehicleType::insert($vehicleTypes);
    }
}
