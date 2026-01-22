<?php

namespace App\Services;

use App\Models\City;
use App\Models\ContentTemplate;
use App\Models\GeneratedContent;
use App\Models\Neighborhood;
use App\Models\Service;
use App\Services\IndexabilityGate;
use App\Services\PageDataService;
use Illuminate\Support\Str;

class ContentGeneratorService
{
    public function __construct(
        private IndexabilityGate $indexabilityGate,
        private PageDataService $pageData
    ) {}
    /**
     * Generate content for a city page
     */
    public function generateCityContent(City $city): GeneratedContent
    {
        $template = ContentTemplate::active()
            ->byType('city')
            ->orderedByPriority()
            ->first();

        if (!$template) {
            $template = $this->getDefaultCityTemplate();
        }

        $variables = $this->getCityVariables($city);
        $content = $this->processTemplate($template->template, $variables);
        $metaDescription = $template->meta_description_template 
            ? $this->processTemplate($template->meta_description_template, $variables)
            : $this->generateDefaultMetaDescription('city', $variables);
        $title = $template->title_template
            ? $this->processTemplate($template->title_template, $variables)
            : $this->generateDefaultTitle('city', $variables);

        // Quality checks before publishing
        $shouldPublish = $this->shouldPublishContent('city', $city, null, null, $content);

        return GeneratedContent::updateOrCreate(
            [
                'content_type' => 'city',
                'city_id' => $city->id,
            ],
            [
                'service_id' => null,
                'neighborhood_id' => null,
                'template_id' => $template->id,
                'content' => $content,
                'meta_description' => $metaDescription,
                'title' => $title,
                'is_auto_generated' => true,
                'is_published' => $shouldPublish,
                'generated_at' => now(),
            ]
        );
    }

    /**
     * Generate content for a city-service page
     */
    public function generateCityServiceContent(City $city, Service $service): GeneratedContent
    {
        $template = ContentTemplate::active()
            ->byType('service')
            ->orderedByPriority()
            ->first();

        if (!$template) {
            $template = $this->getDefaultServiceTemplate();
        }

        $variables = $this->getCityServiceVariables($city, $service);
        $content = $this->processTemplate($template->template, $variables);
        $metaDescription = $template->meta_description_template 
            ? $this->processTemplate($template->meta_description_template, $variables)
            : $this->generateDefaultMetaDescription('service', $variables);
        $title = $template->title_template
            ? $this->processTemplate($template->title_template, $variables)
            : $this->generateDefaultTitle('service', $variables);

        // Quality checks before publishing
        $shouldPublish = $this->shouldPublishContent('service', $city, $service, null, $content);

        return GeneratedContent::updateOrCreate(
            [
                'content_type' => 'service',
                'city_id' => $city->id,
                'service_id' => $service->id,
            ],
            [
                'neighborhood_id' => null,
                'template_id' => $template->id,
                'content' => $content,
                'meta_description' => $metaDescription,
                'title' => $title,
                'is_auto_generated' => true,
                'is_published' => $shouldPublish,
                'generated_at' => now(),
            ]
        );
    }

    /**
     * Generate content for a neighborhood-service page
     */
    public function generateNeighborhoodContent(City $city, Service $service, Neighborhood $neighborhood): GeneratedContent
    {
        $template = ContentTemplate::active()
            ->byType('neighborhood')
            ->orderedByPriority()
            ->first();

        if (!$template) {
            $template = $this->getDefaultNeighborhoodTemplate();
        }

        $variables = $this->getNeighborhoodVariables($city, $service, $neighborhood);
        $content = $this->processTemplate($template->template, $variables);
        $metaDescription = $template->meta_description_template 
            ? $this->processTemplate($template->meta_description_template, $variables)
            : $this->generateDefaultMetaDescription('neighborhood', $variables);
        $title = $template->title_template
            ? $this->processTemplate($template->title_template, $variables)
            : $this->generateDefaultTitle('neighborhood', $variables);

        // Quality checks before publishing
        $shouldPublish = $this->shouldPublishContent('neighborhood', $city, $service, $neighborhood, $content);

        return GeneratedContent::updateOrCreate(
            [
                'content_type' => 'neighborhood',
                'city_id' => $city->id,
                'service_id' => $service->id,
                'neighborhood_id' => $neighborhood->id,
            ],
            [
                'template_id' => $template->id,
                'content' => $content,
                'meta_description' => $metaDescription,
                'title' => $title,
                'is_auto_generated' => true,
                'is_published' => $shouldPublish,
                'generated_at' => now(),
            ]
        );
    }

    /**
     * Process template with variables
     */
    protected function processTemplate(string $template, array $variables): string
    {
        $content = $template;
        
        foreach ($variables as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
            $content = str_replace('{' . Str::upper($key) . '}', Str::upper($value), $content);
            $content = str_replace('{' . Str::lower($key) . '}', Str::lower($value), $content);
        }

        return $content;
    }

    /**
     * Get variables for city content
     */
    protected function getCityVariables(City $city): array
    {
        return [
            'city_name' => $city->name,
            'city_state' => $city->state,
            'city_slug' => $city->slug,
            'city_full' => "{$city->name}, {$city->state}",
        ];
    }

    /**
     * Get variables for city-service content
     */
    protected function getCityServiceVariables(City $city, Service $service): array
    {
        return array_merge(
            $this->getCityVariables($city),
            [
                'service_name' => $service->name,
                'service_slug' => $service->slug,
                'service_description' => $service->description ?? '',
                'service_name_lower' => Str::lower($service->name),
            ]
        );
    }

    /**
     * Get variables for neighborhood content
     */
    protected function getNeighborhoodVariables(City $city, Service $service, Neighborhood $neighborhood): array
    {
        return array_merge(
            $this->getCityServiceVariables($city, $service),
            [
                'neighborhood_name' => $neighborhood->name,
                'neighborhood_slug' => $neighborhood->slug,
            ]
        );
    }

    /**
     * Generate default meta description
     */
    protected function generateDefaultMetaDescription(string $type, array $variables): string
    {
        switch ($type) {
            case 'city':
                return "24/7 locksmith services in {$variables['city_name']}, {$variables['city_state']}. Unloqit car, unloqit house, rekeys, key programming, commercial. Licensed technicians. 20-30 min response.";
            case 'service':
                return "{$variables['service_name']} in {$variables['city_name']}, {$variables['city_state']}. Licensed locksmiths. 20-30 min response. Non-destructive entry. Transparent pricing.";
            case 'neighborhood':
                return "{$variables['service_name']} in {$variables['neighborhood_name']}, {$variables['city_name']}, {$variables['city_state']}. Local locksmiths. 18-25 min response. Licensed & insured.";
            default:
                return '';
        }
    }

    /**
     * Generate default title
     */
    protected function generateDefaultTitle(string $type, array $variables): string
    {
        switch ($type) {
            case 'city':
                return "{$variables['city_name']} Locksmith | 24/7 Lockout & Car Keys | Unloqit";
            case 'service':
                return "{$variables['service_name']} in {$variables['city_name']} | {$variables['city_name']} Locksmith | Unloqit";
            case 'neighborhood':
                return "{$variables['service_name']} in {$variables['neighborhood_name']}, {$variables['city_name']} | Unloqit";
            default:
                return '';
        }
    }

    /**
     * Get default city template
     */
    protected function getDefaultCityTemplate(): ContentTemplate
    {
        return ContentTemplate::create([
            'type' => 'city',
            'name' => 'Default City Template',
            'template' => "Unloqit car? Unloqit house? Get matched instantly.\n\nUnloqit connects you with verified Unloqit Pro Service Providers in {city_name}, {city_state}. Submit a request and get matched with a licensed locksmith in minutes—not hours.\n\nThe marketplace includes pros who handle unloqit car situations, residential rekeys, commercial installations, and key programming. Each pro carries tools for every lock type—from vintage deadbolts in historic districts to modern smart locks in new construction.\n\nResponse time matters. In {city_name}, traffic patterns and building access vary by neighborhood. Our matching system connects you with pros based on real-time availability and location, not generic estimates.\n\nAll Unloqit Pro Service Providers are verified: licensed, bonded, and background-checked. We verify credentials before activation. Independent contractors, vetted professionals.\n\nWhen you need entry, request a pro. Fast matching. Professional service. Real-time tracking.",
            'meta_description_template' => "24/7 locksmith services in {city_name}, {city_state}. Unloqit car, unloqit house, rekeys, key programming, commercial. Licensed technicians. 20-30 min response.",
            'title_template' => "{city_name} Locksmith | 24/7 Lockout & Car Keys | Unloqit",
            'is_active' => true,
            'priority' => 0,
        ]);
    }

    /**
     * Get default service template
     */
    protected function getDefaultServiceTemplate(): ContentTemplate
    {
        return ContentTemplate::create([
            'type' => 'service',
            'name' => 'Default Service Template',
            'template' => "{service_description}\n\nIn {city_name}, {service_name_lower} requests spike during rush hours and extreme weather. Unloqit Pro Service Providers maintain availability to deliver sub-30-minute response times citywide.\n\nHow it works:\n\n• Request submitted. Job broadcast to available pros in your area.\n• Pro claims job. You're matched with a verified locksmith instantly.\n• Real-time tracking. See when your pro is en route, arrived, and working.\n• Transparent pricing. Pros quote before work begins. No hidden fees, no upsells.\n\nWhy speed matters: Every minute in an unloqit situation increases stress and risk. Pros move fast because they've handled thousands of jobs through the platform.\n\nThe marketplace serves all {city_name} neighborhoods—from dense urban cores to suburban developments. Same matching speed, same verification standard.",
            'meta_description_template' => "{service_name} in {city_name}, {city_state}. Licensed locksmiths. 20-30 min response. Non-destructive entry. Transparent pricing.",
            'title_template' => "{service_name} in {city_name} | {city_name} Locksmith | Unloqit",
            'is_active' => true,
            'priority' => 0,
        ]);
    }

    /**
     * Get default neighborhood template
     */
    protected function getDefaultNeighborhoodTemplate(): ContentTemplate
    {
        return ContentTemplate::create([
            'type' => 'neighborhood',
            'name' => 'Default Neighborhood Template',
            'template' => "{service_name} in {neighborhood_name}, {city_name}.\n\n{neighborhood_name} presents specific challenges: building access protocols, parking constraints, lock types common to the area's construction era. Our technicians know these details.\n\nWe've handled {service_name_lower} calls throughout {neighborhood_name} for years. We know which buildings require key fobs, which have restricted parking, and which locksmith shops stock parts for older hardware.\n\nResponse time to {neighborhood_name} averages 18-25 minutes from dispatch. We route based on technician proximity, not generic zones.\n\nOur service standard: Arrive prepared. Assess quickly. Execute cleanly. Document clearly.\n\nLicensed, insured, background-checked. No exceptions.",
            'meta_description_template' => "{service_name} in {neighborhood_name}, {city_name}, {city_state}. Local locksmiths. 18-25 min response. Licensed & insured.",
            'title_template' => "{service_name} in {neighborhood_name}, {city_name} | Unloqit",
            'is_active' => true,
            'priority' => 0,
        ]);
    }

    /**
     * Check if content should be published based on quality gates
     */
    protected function shouldPublishContent(string $type, City $city, ?Service $service, ?Neighborhood $neighborhood, string $content): bool
    {
        // 1. Indexability gate - must have real coverage
        if ($type === 'city') {
            if (!$this->indexabilityGate->isCityIndexable($city)) {
                return false;
            }
        } elseif ($type === 'service') {
            if (!$this->indexabilityGate->isCityServiceIndexable($city, $service)) {
                return false;
            }
        } elseif ($type === 'neighborhood') {
            if (!$this->indexabilityGate->isNeighborhoodServiceIndexable($city, $service, $neighborhood)) {
                return false;
            }
        }

        // 2. Uniqueness check - content must not be too similar to other pages
        if (!$this->checkContentUniqueness($type, $city, $service, $neighborhood, $content)) {
            return false;
        }

        // 3. Data presence check - must have real data modules available
        if (!$this->checkDataPresence($type, $city, $service, $neighborhood)) {
            return false;
        }

        return true;
    }

    /**
     * Check content uniqueness (prevent duplicate/spam content)
     */
    protected function checkContentUniqueness(string $type, City $city, ?Service $service, ?Neighborhood $neighborhood, string $content): bool
    {
        // Hash the content (excluding variable tokens)
        $normalizedContent = preg_replace('/\{[^}]+\}/', '', $content);
        $contentHash = md5(strtolower(trim($normalizedContent)));

        // Check for similar content in other pages of the same type
        $query = GeneratedContent::where('content_type', $type)
            ->where('id', '!=', 0) // Exclude current (will be updated)
            ->where('is_published', true);

        if ($type === 'city') {
            $query->where('city_id', '!=', $city->id);
        } elseif ($type === 'service') {
            $query->where(function ($q) use ($city, $service) {
                $q->where('city_id', '!=', $city->id)
                  ->orWhere('service_id', '!=', $service->id);
            });
        } elseif ($type === 'neighborhood') {
            $query->where(function ($q) use ($city, $service, $neighborhood) {
                $q->where('city_id', '!=', $city->id)
                  ->orWhere('service_id', '!=', $service->id)
                  ->orWhere('neighborhood_id', '!=', $neighborhood->id);
            });
        }

        // Check if any existing content has the same hash (too similar)
        $similarCount = $query->get()->filter(function ($existing) use ($contentHash) {
            $existingNormalized = preg_replace('/\{[^}]+\}/', '', $existing->content);
            $existingHash = md5(strtolower(trim($existingNormalized)));
            return $existingHash === $contentHash;
        })->count();

        // If more than 2 pages have identical content, it's likely spam
        return $similarCount < 2;
    }

    /**
     * Check if page has enough real data to be valuable
     */
    protected function checkDataPresence(string $type, City $city, ?Service $service, ?Neighborhood $neighborhood): bool
    {
        $dataModulesCount = 0;

        if ($type === 'city') {
            $coverageData = $this->pageData->getCityCoverageData($city);
            if ($coverageData['provider_count'] > 0) $dataModulesCount++;
            if ($coverageData['online_providers'] > 0) $dataModulesCount++;
            if ($coverageData['avg_response_time_minutes'] !== null) $dataModulesCount++;
        } elseif ($type === 'service') {
            $coverageData = $this->pageData->getCityServiceCoverageData($city, $service);
            $activityData = $this->pageData->getCityServiceActivity($city, $service);
            $pricingRange = $this->pageData->getCityServicePricingRange($city, $service);

            if ($coverageData['provider_count'] > 0) $dataModulesCount++;
            if ($coverageData['online_providers'] > 0) $dataModulesCount++;
            if ($coverageData['avg_response_time_minutes'] !== null) $dataModulesCount++;
            if ($activityData['completed_jobs_30_days'] > 0) $dataModulesCount++;
            if ($pricingRange !== null) $dataModulesCount++;
        } elseif ($type === 'neighborhood') {
            $neighborhoodData = $this->pageData->getNeighborhoodData($city, $service, $neighborhood);
            if ($neighborhoodData['completed_jobs_180_days'] > 0) $dataModulesCount++;
            if ($neighborhoodData['avg_response_time_minutes'] !== null) $dataModulesCount++;
        }

        // Require at least 2 real data modules for city/service, 1 for neighborhood
        $requiredModules = $type === 'neighborhood' ? 1 : 2;
        return $dataModulesCount >= $requiredModules;
    }
}

