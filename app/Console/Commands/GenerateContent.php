<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Service;
use App\Models\Neighborhood;
use App\Services\ContentGeneratorService;
use Illuminate\Console\Command;

class GenerateContent extends Command
{
    protected $signature = 'content:generate 
                            {--city= : City slug to generate content for}
                            {--service= : Service slug}
                            {--neighborhood= : Neighborhood slug}
                            {--all-cities : Generate content for all cities}
                            {--all-services : Generate content for all services}';

    protected $description = 'Generate programmatic SEO content';

    public function handle(ContentGeneratorService $contentGenerator): int
    {
        if ($this->option('all-cities')) {
            return $this->generateAllCities($contentGenerator);
        }

        $citySlug = $this->option('city');
        $serviceSlug = $this->option('service');
        $neighborhoodSlug = $this->option('neighborhood');

        if (!$citySlug) {
            $this->error('Please specify --city or use --all-cities');
            return Command::FAILURE;
        }

        $city = City::where('slug', $citySlug)->first();

        if (!$city) {
            $this->error("City not found: {$citySlug}");
            return Command::FAILURE;
        }

        if ($neighborhoodSlug && $serviceSlug) {
            return $this->generateNeighborhoodContent($city, $serviceSlug, $neighborhoodSlug, $contentGenerator);
        }

        if ($serviceSlug) {
            return $this->generateServiceContent($city, $serviceSlug, $contentGenerator);
        }

        return $this->generateCityContent($city, $contentGenerator);
    }

    protected function generateCityContent(City $city, ContentGeneratorService $contentGenerator): int
    {
        $this->info("Generating content for city: {$city->name}");
        $contentGenerator->generateCityContent($city);
        $this->info("✓ City content generated");
        return Command::SUCCESS;
    }

    protected function generateServiceContent(City $city, string $serviceSlug, ContentGeneratorService $contentGenerator): int
    {
        $service = Service::where('slug', $serviceSlug)->first();

        if (!$service) {
            $this->error("Service not found: {$serviceSlug}");
            return Command::FAILURE;
        }

        $this->info("Generating content for {$service->name} in {$city->name}");
        $contentGenerator->generateCityServiceContent($city, $service);
        $this->info("✓ Service content generated");
        return Command::SUCCESS;
    }

    protected function generateNeighborhoodContent(City $city, string $serviceSlug, string $neighborhoodSlug, ContentGeneratorService $contentGenerator): int
    {
        $service = Service::where('slug', $serviceSlug)->first();
        $neighborhood = Neighborhood::where('city_id', $city->id)
            ->where('slug', $neighborhoodSlug)
            ->first();

        if (!$service) {
            $this->error("Service not found: {$serviceSlug}");
            return Command::FAILURE;
        }

        if (!$neighborhood) {
            $this->error("Neighborhood not found: {$neighborhoodSlug}");
            return Command::FAILURE;
        }

        $this->info("Generating content for {$service->name} in {$neighborhood->name}, {$city->name}");
        $contentGenerator->generateNeighborhoodContent($city, $service, $neighborhood);
        $this->info("✓ Neighborhood content generated");
        return Command::SUCCESS;
    }

    protected function generateAllCities(ContentGeneratorService $contentGenerator): int
    {
        $cities = City::all();
        $services = Service::all();
        $bar = $this->output->createProgressBar($cities->count() * ($services->count() + 1));
        $bar->start();

        foreach ($cities as $city) {
            $contentGenerator->generateCityContent($city);
            $bar->advance();

            foreach ($services as $service) {
                $contentGenerator->generateCityServiceContent($city, $service);

                foreach ($city->neighborhoods as $neighborhood) {
                    $contentGenerator->generateNeighborhoodContent($city, $service, $neighborhood);
                }

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Generated content for all cities");
        return Command::SUCCESS;
    }
}

