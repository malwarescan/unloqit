<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        City::firstOrCreate(
            ['slug' => 'cleveland'],
            [
                'name' => 'Cleveland',
                'state' => 'OH',
                'lat' => 41.4993,
                'lng' => -81.6944,
            ]
        );
    }
}

