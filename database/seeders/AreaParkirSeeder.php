<?php

namespace Database\Seeders;

use App\Models\AreaParkir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaParkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areaParkirs = [
            [
                'kampus_id' => 1, // Depok
                'name' => 'Parkir Utama Kampus A',
                'kapasitas' => 100,
                'keterangan' => 'Dekat Kampus A',
            ],
            [
                'kampus_id' => 2, // Depok
                'name' => 'Parkir Utama Kampus B',
                'kapasitas' => 50,
                'keterangan' => 'Dekat Kampus B',
            ],
        ];

        AreaParkir::insert($areaParkirs);
    }
}
