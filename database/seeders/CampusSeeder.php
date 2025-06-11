<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = [
            [
                'name' => 'Kampus A',
                'alamat' => 'Kampus A Cimanggis, Depok, Jawa Barat',
            ],
            [
                'name' => 'Kampus B',
                'alamat' => 'Kampus B Jagakarsa, Jakarta Selatan, DKI Jakarta',
            ],
        ];

        Campus::insert($campuses);
    }
}
