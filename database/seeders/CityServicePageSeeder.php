<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityServicePage;
use App\Models\Service;
use Illuminate\Database\Seeder;

class CityServicePageSeeder extends Seeder
{
    public function run(): void
    {
        $cleveland = City::where('slug', 'cleveland')->first();
        $services = Service::all();

        if (!$cleveland || $services->isEmpty()) {
            return;
        }

        foreach ($services as $service) {
            CityServicePage::firstOrCreate(
                [
                    'city_id' => $cleveland->id,
                    'service_id' => $service->id,
                ],
                [
                    'custom_intro' => null,
                    'custom_pricing' => null,
                ]
            );
        }
    }
}

