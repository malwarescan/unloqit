<?php

namespace Database\Seeders;

use App\Models\ContentTemplate;
use Illuminate\Database\Seeder;

class ContentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // City templates - Sharp, direct, authoritative
        ContentTemplate::firstOrCreate(
            [
                'type' => 'city',
                'name' => 'City Landing Page - Direct Authority',
            ],
            [
            'template' => "Unloqit car? Unloqit house? Get matched instantly.\n\nUnloqit connects you with verified Unloqit Pro Service Providers in {city_name}, {city_state}. Submit a request and get matched with a licensed locksmith in minutes—not hours.\n\nThe marketplace includes pros who handle car lockouts, residential rekeys, commercial installations, and key programming. Each pro carries tools for every lock type—from vintage deadbolts in historic districts to modern smart locks in new construction.\n\nResponse time matters. In {city_name}, traffic patterns and building access vary by neighborhood. Our matching system connects you with pros based on real-time availability and location, not generic estimates.\n\nAll Unloqit Pro Service Providers are verified: licensed, bonded, and background-checked. We verify credentials before activation. Independent contractors, vetted professionals.\n\nWhen you need entry, request a pro. Fast matching. Professional service. Real-time tracking.",
            'meta_description_template' => "24/7 locksmith services in {city_name}, {city_state}. Car lockouts, rekeys, key programming, commercial. Licensed technicians. 20-30 min response.",
            'title_template' => "{city_name} Locksmith | 24/7 Lockout & Car Keys | Unloqit",
            'variables' => ['city_name', 'city_state', 'city_slug', 'city_full'],
            'is_active' => true,
            'priority' => 10,
            ]
        );

        // Service templates - Specific, technical, confident
        ContentTemplate::firstOrCreate(
            [
                'type' => 'service',
                'name' => 'Service Page - Technical Authority',
            ],
            [
            'template' => "{service_description}\n\nIn {city_name}, {service_name_lower} requests spike during rush hours and extreme weather. Unloqit Pro Service Providers maintain availability to deliver sub-30-minute response times citywide.\n\nHow it works:\n\n• Request submitted. Job broadcast to available pros in your area.\n• Pro claims job. You're matched with a verified locksmith instantly.\n• Real-time tracking. See when your pro is en route, arrived, and working.\n• Transparent pricing. Pros quote before work begins. No hidden fees, no upsells.\n\nWhy speed matters: Every minute in an unloqit situation increases stress and risk. Pros move fast because they've handled thousands of jobs through the platform.\n\nThe marketplace serves all {city_name} neighborhoods—from dense urban cores to suburban developments. Same matching speed, same verification standard.",
            'meta_description_template' => "{service_name} in {city_name}, {city_state}. Licensed locksmiths. 20-30 min response. Non-destructive entry. Transparent pricing.",
            'title_template' => "{service_name} in {city_name} | {city_name} Locksmith | Unloqit",
            'variables' => ['city_name', 'city_state', 'service_name', 'service_slug', 'service_description', 'service_name_lower'],
            'is_active' => true,
            'priority' => 10,
            ]
        );

        // Neighborhood templates - Hyperlocal, specific, grounded
        ContentTemplate::firstOrCreate(
            [
                'type' => 'neighborhood',
                'name' => 'Neighborhood Page - Hyperlocal Precision',
            ],
            [
            'template' => "{service_name} in {neighborhood_name}, {city_name}.\n\n{neighborhood_name} presents specific challenges: building access protocols, parking constraints, lock types common to the area's construction era. Unloqit Pro Service Providers active in this area know these details.\n\nPros on the platform have handled {service_name_lower} requests throughout {neighborhood_name}. They know which buildings require key fobs, which have restricted parking, and which locksmith shops stock parts for older hardware.\n\nResponse time to {neighborhood_name} averages 18-25 minutes from job claim. Matching happens based on pro proximity and availability, not generic zones.\n\nPlatform standard: Pros arrive prepared. Assess quickly. Execute cleanly. Document clearly.\n\nAll pros verified: licensed, insured, background-checked. No exceptions.",
            'meta_description_template' => "{service_name} in {neighborhood_name}, {city_name}, {city_state}. Local locksmiths. 18-25 min response. Licensed & insured.",
            'title_template' => "{service_name} in {neighborhood_name}, {city_name} | Unloqit",
            'variables' => ['city_name', 'city_state', 'service_name', 'neighborhood_name', 'service_name_lower'],
            'is_active' => true,
            'priority' => 10,
            ]
        );
    }
}

