<?php

namespace App\Console\Commands;

use App\Services\CityRolloutService;
use Illuminate\Console\Command;

class RolloutCity extends Command
{
    protected $signature = 'city:rollout 
                            {name : City name}
                            {state : State abbreviation}
                            {--slug= : Custom slug (defaults to name)}
                            {--lat= : Latitude}
                            {--lng= : Longitude}
                            {--neighborhoods=* : Neighborhood names}
                            {--no-content : Skip content generation}';

    protected $description = 'Rollout a new city with all services and neighborhoods';

    public function handle(CityRolloutService $rolloutService): int
    {
        $cityData = [
            'name' => $this->argument('name'),
            'state' => $this->argument('state'),
            'slug' => $this->option('slug') ?: \Illuminate\Support\Str::slug($this->argument('name')),
            'lat' => $this->option('lat'),
            'lng' => $this->option('lng'),
        ];

        $neighborhoods = $this->option('neighborhoods');
        $generateContent = !$this->option('no-content');

        $this->info("Rolling out city: {$cityData['name']}, {$cityData['state']}");

        try {
            $city = $rolloutService->rolloutCity($cityData, $neighborhoods, $generateContent);

            $this->info("✓ City created: {$city->name}");
            $this->info("✓ Neighborhoods created: " . $city->neighborhoods->count());
            $this->info("✓ Service pages created: " . $city->services->count());

            if ($generateContent) {
                $this->info("✓ Content generated for all pages");
            }

            $this->info("\nCity rollout complete!");
            $this->info("URL: /locksmith/{$city->slug}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to rollout city: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

