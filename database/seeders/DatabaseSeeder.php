<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            ServiceSeeder::class,
            NeighborhoodSeeder::class,
            CityServicePageSeeder::class,
            ContentTemplateSeeder::class,
        ]);
    }
}

