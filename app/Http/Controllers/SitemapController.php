<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Faq;
use App\Models\Guide;
use App\Models\Service;
use App\Services\IndexabilityGate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function __construct(
        private IndexabilityGate $indexabilityGate
    ) {}

    public function index(): Response
    {
        $baseUrl = 'https://www.unloqit.com';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= '  <sitemap><loc>' . $baseUrl . '/sitemap-services.xml</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>' . $baseUrl . '/sitemap-locations.xml</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>' . $baseUrl . '/sitemap-city-services.xml</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>' . $baseUrl . '/sitemap-guides.xml</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>' . $baseUrl . '/sitemap-faq.xml</loc></sitemap>' . "\n";
        $xml .= '</sitemapindex>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Services sitemap: /services and /services/{service}
     */
    public function services(): Response
    {
        $services = Service::all();
        $baseUrl = 'https://www.unloqit.com';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Services directory
        $xml .= '  <url><loc>' . $baseUrl . '/services</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
        
        // Individual service pages
        foreach ($services as $service) {
            $xml .= '  <url><loc>' . $baseUrl . '/services/' . $service->slug . '</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>' . "\n";
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Locations sitemap: /locations and eligible city pages
     */
    public function locations(): Response
    {
        $cities = City::all();
        $baseUrl = 'https://www.unloqit.com';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Locations directory
        $xml .= '  <url><loc>' . $baseUrl . '/locations</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
        
        // City pages (only indexable ones, using new canonical structure)
        foreach ($cities as $city) {
            if ($this->indexabilityGate->isCityIndexable($city)) {
                $xml .= '  <url><loc>' . $baseUrl . '/locksmith/' . strtolower($city->state) . '/' . $city->slug . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
            }
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * City-services sitemap: eligible city-service pages only
     */
    public function cityServices(): Response
    {
        $cities = City::all();
        $services = Service::all();
        $baseUrl = 'https://www.unloqit.com';
        $urlCount = 0;
        $maxUrls = 50000;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($cities as $city) {
            if (!$this->indexabilityGate->isCityIndexable($city)) {
                continue;
            }

            foreach ($services as $service) {
                if ($urlCount >= $maxUrls) {
                    break 2;
                }

                if (!$this->indexabilityGate->isCityServiceIndexable($city, $service)) {
                    continue;
                }

                // New canonical structure: /locksmith/{state}/{city}/{service}
                $xml .= '  <url><loc>' . $baseUrl . '/locksmith/' . strtolower($city->state) . '/' . $city->slug . '/' . $service->slug . '</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
                $urlCount++;
            }
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }


    public function guides(): Response
    {
        $guides = Guide::all();
        $baseUrl = 'https://www.unloqit.com';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($guides as $guide) {
            $xml .= '  <url><loc>' . $baseUrl . '/guides/' . $guide->slug . '</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>' . "\n";
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function faq(): Response
    {
        $faqs = Faq::all();
        $baseUrl = 'https://www.unloqit.com';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($faqs as $faq) {
            $xml .= '  <url><loc>' . $baseUrl . '/faq/' . $faq->slug . '</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>' . "\n";
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function ndjson(): Response
    {
        $cities = City::all();
        $services = Service::all();
        $baseUrl = 'https://unloqit.com';
        
        $lines = [];
        
        // Homepage
        $lines[] = json_encode([
            'url' => $baseUrl . '/',
            'title' => 'Unloqit - 24/7 Locksmith Services in Cleveland, Ohio',
            'about' => ['locksmith', 'cleveland', 'ohio'],
        ]);
        
        // City pages
        foreach ($cities as $city) {
            $lines[] = json_encode([
                'url' => $baseUrl . '/locksmith/' . $city->slug,
                'title' => "{$city->name} Locksmith | 24/7 Lockout & Car Keys | Unloqit",
                'about' => ['locksmith', strtolower($city->name), strtolower($city->state)],
            ]);
            
            // Service pages
            foreach ($services as $service) {
                $lines[] = json_encode([
                    'url' => $baseUrl . '/locksmith/' . $city->slug . '/' . $service->slug,
                    'title' => "{$service->name} in {$city->name} | {$city->name} Locksmith | Unloqit",
                    'about' => ['locksmith', strtolower($city->name), strtolower($service->slug)],
                ]);
            }
        }
        
        $content = implode("\n", $lines);
        
        return response($content, 200)->header('Content-Type', 'application/x-ndjson');
    }
}

