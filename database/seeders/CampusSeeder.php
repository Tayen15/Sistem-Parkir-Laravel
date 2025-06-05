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
                'name' => 'Depok',
                'alamat' => 'Kampus UI Depok, Jawa Barat, Indonesia',
            ],
            [
                'name' => 'Salemba',
                'alamat' => 'Kampus UI Salemba, Jakarta Pusat, Indonesia',
            ],
        ];

        Campus::insert($campuses);
    }
}
