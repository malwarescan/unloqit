<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NeighborhoodSeeder extends Seeder
{
    public function run(): void
    {
        $cleveland = City::where('slug', 'cleveland')->first();

        if (!$cleveland) {
            return;
        }

        $neighborhoods = [
            'Ohio City',
            'Tremont',
            'Lakewood',
            'Detroit-Shoreway',
            'University Circle',
        ];

        foreach ($neighborhoods as $name) {
            Neighborhood::create([
                'city_id' => $cleveland->id,
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}

