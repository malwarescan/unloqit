<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Service;
use App\Services\ProviderOnboardingService;
use Illuminate\Console\Command;

class OnboardProvider extends Command
{
    protected $signature = 'provider:onboard 
                            {name : Provider name}
                            {email : Provider email}
                            {--phone= : Phone number}
                            {--license= : License number}
                            {--cities=* : City slugs}
                            {--services=* : Service slugs}
                            {--verify : Mark as verified}
                            {--response-time= : Average response time}';

    protected $description = 'Onboard a new locksmith provider';

    public function handle(ProviderOnboardingService $onboardingService): int
    {
        $citySlugs = $this->option('cities');
        $serviceSlugs = $this->option('services');

        $cityIds = [];
        $serviceIds = [];

        if (!empty($citySlugs)) {
            $cities = City::whereIn('slug', $citySlugs)->get();
            $cityIds = $cities->pluck('id')->toArray();

            if ($cities->count() !== count($citySlugs)) {
                $this->warn('Some cities were not found');
            }
        }

        if (!empty($serviceSlugs)) {
            $services = Service::whereIn('slug', $serviceSlugs)->get();
            $serviceIds = $services->pluck('id')->toArray();

            if ($services->count() !== count($serviceSlugs)) {
                $this->warn('Some services were not found');
            }
        }

        $providerData = [
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'phone' => $this->option('phone'),
            'license_number' => $this->option('license'),
            'is_verified' => $this->option('verify'),
            'response_time' => $this->option('response-time'),
        ];

        try {
            $provider = $onboardingService->onboardProvider($providerData, $cityIds, $serviceIds);

            $this->info("✓ Provider onboarded: {$provider->name}");
            $this->info("  Email: {$provider->email}");
            $this->info("  Cities: " . count($cityIds));
            $this->info("  Services: " . count($serviceIds));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to onboard provider: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

