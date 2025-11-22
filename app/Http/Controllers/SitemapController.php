<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Faq;
use App\Models\Guide;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= '  <sitemap><loc>https://unloqit.com/sitemap-cities.xml</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>https://unloqit.com/sitemap-services.xml</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>https://unloqit.com/sitemap-guides.xml</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>https://unloqit.com/sitemap-faq.xml</loc></sitemap>' . "\n";
        $xml .= '</sitemapindex>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function cities(): Response
    {
        $cities = City::all();
        $baseUrl = 'https://unloqit.com';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        $xml .= '  <url><loc>' . $baseUrl . '/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";
        
        // Cleveland special route
        $xml .= '  <url><loc>' . $baseUrl . '/cleveland-locksmith</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>' . "\n";
        
        // City pages
        foreach ($cities as $city) {
            $xml .= '  <url><loc>' . $baseUrl . '/locksmith/' . $city->slug . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function services(): Response
    {
        $cities = City::all();
        $services = Service::all();
        $baseUrl = 'https://unloqit.com';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($cities as $city) {
            foreach ($services as $service) {
                // Cleveland special routes
                if ($city->slug === 'cleveland') {
                    $xml .= '  <url><loc>' . $baseUrl . '/cleveland-locksmith/' . $service->slug . '</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
                }
                
                // Standard routes
                $xml .= '  <url><loc>' . $baseUrl . '/locksmith/' . $city->slug . '/' . $service->slug . '</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
                
                // Neighborhood pages
                foreach ($city->neighborhoods as $neighborhood) {
                    if ($city->slug === 'cleveland') {
                        $xml .= '  <url><loc>' . $baseUrl . '/cleveland-locksmith/' . $service->slug . '/' . $neighborhood->slug . '</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>' . "\n";
                    }
                    $xml .= '  <url><loc>' . $baseUrl . '/locksmith/' . $city->slug . '/' . $service->slug . '/' . $neighborhood->slug . '</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>' . "\n";
                }
            }
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function guides(): Response
    {
        $guides = Guide::all();
        $baseUrl = 'https://unloqit.com';

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
        $baseUrl = 'https://unloqit.com';

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

