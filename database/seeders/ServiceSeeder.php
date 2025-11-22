<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Car Lockout',
                'slug' => 'car-lockout',
                'description' => 'Fast 24/7 car lockout service. We help you get back into your vehicle quickly and safely.',
            ],
            [
                'name' => 'Car Key Programming',
                'slug' => 'car-key-programming',
                'description' => 'Professional car key programming and replacement for all makes and models.',
            ],
            [
                'name' => 'House Lockout',
                'slug' => 'house-lockout',
                'description' => 'Emergency residential lockout service. We get you back into your home fast.',
            ],
            [
                'name' => 'Rekeying',
                'slug' => 'rekeying',
                'description' => 'Professional lock rekeying services to change your locks without replacing them.',
            ],
            [
                'name' => 'Lock Installation',
                'slug' => 'lock-installation',
                'description' => 'Expert lock installation for residential and commercial properties.',
            ],
            [
                'name' => 'Commercial Locksmith',
                'slug' => 'commercial-locksmith',
                'description' => 'Complete commercial locksmith services for businesses and offices.',
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}

