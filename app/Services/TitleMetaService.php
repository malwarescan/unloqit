<?php

namespace App\Services;

use App\Models\City;
use App\Models\Service;
use App\Models\Neighborhood;

class TitleMetaService
{
    /**
     * Generate title and meta for city page
     */
    public function forCity(City $city): array
    {
        $title = "Locksmith in {$city->name} – Fast, Verified Pros | Unloqit";
        $meta = "Locked out in {$city->name}? Get matched with verified locksmith pros in minutes. Unloqit connects you to nearby experts who claim your job instantly.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for city-service page
     */
    public function forCityService(City $city, Service $service): array
    {
        $serviceName = $service->name;
        $cityName = $city->name;
        
        // Service-specific title variations
        $title = "{$serviceName} in {$cityName} – Fast, Verified Pros | Unloqit";
        
        // Service-specific meta variations
        $meta = "Need {$serviceName} in {$cityName}? Get matched with verified locksmith pros in minutes. Pros claim your job instantly. 24/7 availability.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for neighborhood-service page
     */
    public function forNeighborhoodService(City $city, Service $service, Neighborhood $neighborhood): array
    {
        $serviceName = $service->name;
        $neighborhoodName = $neighborhood->name;
        $cityName = $city->name;
        
        $title = "{$serviceName} in {$neighborhoodName}, {$cityName} – Fast Help | Unloqit";
        $meta = "{$serviceName} in {$neighborhoodName}? Matched with verified locksmith pros near you. Pros claim jobs instantly. 18-25 min response.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for homepage
     */
    public function forHome(): array
    {
        $title = "Locksmith in Cleveland – Fast, Verified Pros | Unloqit";
        $meta = "Locked out? Get matched with verified locksmith pros in minutes. Unloqit connects you to nearby experts who claim your job instantly. 24/7.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for guide page
     */
    public function forGuide(string $guideTitle): array
    {
        $title = "{$guideTitle} | Unloqit";
        $meta = "{$guideTitle}. Expert locksmith tips and guides from verified Unloqit Pro Service Providers.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for FAQ page
     */
    public function forFaq(string $question): array
    {
        $title = "{$question} | Unloqit";
        $meta = "{$question} Get answers from verified Unloqit Pro Service Providers. Fast, reliable locksmith help.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for pro registration
     */
    public function forProRegister(): array
    {
        $title = "Become an Unloqit Pro – Join Marketplace | Unloqit";
        $meta = "Join Unloqit as a verified locksmith pro. Claim jobs instantly, set your schedule, earn competitive rates. Get verified and start today.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for pro login
     */
    public function forProLogin(): array
    {
        $title = "Unloqit Pro Login – Dashboard Access | Unloqit";
        $meta = "Login to your Unloqit Pro dashboard. Manage jobs, track earnings, toggle availability. Access your marketplace account.";

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Generate title and meta for request page
     */
    public function forRequest(?City $city = null, ?Service $service = null): array
    {
        if ($city && $service) {
            $title = "Request {$service->name} in {$city->name} – Instant Match | Unloqit";
            $meta = "Request {$service->name} in {$city->name}. Get matched with verified locksmith pros instantly. Pros claim your job in real-time. Fast response.";
        } elseif ($city) {
            $title = "Request Locksmith in {$city->name} – Instant Match | Unloqit";
            $meta = "Request a locksmith in {$city->name}. Get matched with verified pros instantly. Pros claim your job in real-time. 24/7 availability.";
        } else {
            $title = "Request Locksmith – Instant Match | Unloqit";
            $meta = "Request a locksmith instantly. Get matched with verified pros who claim your job in real-time. Fast response, professional service.";
        }

        return [
            'title' => $this->truncateTitle($title),
            'meta_description' => $this->truncateMeta($meta),
        ];
    }

    /**
     * Truncate title to 50-60 characters
     */
    protected function truncateTitle(string $title): string
    {
        if (mb_strlen($title) <= 60) {
            return $title;
        }
        
        // Try to truncate at word boundary
        $truncated = mb_substr($title, 0, 57);
        $lastSpace = mb_strrpos($truncated, ' ');
        
        if ($lastSpace !== false) {
            return mb_substr($title, 0, $lastSpace) . '...';
        }
        
        return mb_substr($title, 0, 57) . '...';
    }

    /**
     * Truncate meta description to 130-155 characters
     */
    protected function truncateMeta(string $meta): string
    {
        if (mb_strlen($meta) <= 155) {
            return $meta;
        }
        
        // Try to truncate at word boundary
        $truncated = mb_substr($meta, 0, 152);
        $lastSpace = mb_strrpos($truncated, ' ');
        
        if ($lastSpace !== false) {
            return mb_substr($meta, 0, $lastSpace) . '...';
        }
        
        return mb_substr($meta, 0, 152) . '...';
    }
}

