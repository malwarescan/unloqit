<?php

namespace App\View\Composers;

use App\Models\City;
use App\Models\Service;
use App\Services\IndexabilityGate;
use Illuminate\View\View;

class LayoutComposer
{
    public function __construct(
        private IndexabilityGate $indexabilityGate
    ) {}

    public function compose(View $view): void
    {
        // Services for footer
        $services = Service::all()->take(4); // Show top 4 services
        
        // Only covered cities for footer
        $coveredCities = City::all()
            ->filter(function ($city) {
                return $this->indexabilityGate->isCityIndexable($city);
            })
            ->sortBy('name')
            ->take(4); // Show top 4 covered cities
        
        $view->with([
            'footerServices' => $services,
            'footerCities' => $coveredCities,
        ]);
    }
}
