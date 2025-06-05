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
                'name' => 'Parkir Utama Depok',
                'kapasitas' => 100,
                'keterangan' => 'Dekat Fakultas Teknik',
            ],
            [
                'kampus_id' => 1, // Depok
                'name' => 'Parkir Timur Depok',
                'kapasitas' => 50,
                'keterangan' => 'Dekat Fakultas Ekonomi',
            ],
            [
                'kampus_id' => 2, // Salemba
                'name' => 'Parkir Salemba Utara',
                'kapasitas' => 30,
                'keterangan' => 'Dekat Fakultas Kedokteran',
            ],
        ];

        AreaParkir::insert($areaParkirs);
    }
}
