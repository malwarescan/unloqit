@extends('layouts.app')

@section('title', $service->name . ' in ' . $neighborhood->name . ', ' . $city->name . ' | Unloqit')
@section('meta_description', 'Professional ' . $service->name . ' services in ' . $neighborhood->name . ', ' . $city->name . ', ' . $city->state . '. Fast, reliable, and available 24/7.')
@section('canonical', $neighborhoodUrl ?? ($city->slug === 'cleveland' ? route('cleveland.service.neighborhood.show', ['service' => $service->slug, 'neighborhood' => $neighborhood->slug]) : route('city.service.neighborhood.show', ['city' => $city->slug, 'service' => $service->slug, 'neighborhood' => $neighborhood->slug])))

@section('meta_extra')
<meta property="og:title" content="{{ $service->name }} in {{ $neighborhood->name }}, {{ $city->name }} | Unloqit">
<meta property="og:description" content="Professional {{ $service->name }} services in {{ $neighborhood->name }}, {{ $city->name }}, {{ $city->state }}.">
<meta property="og:url" content="{{ $neighborhoodUrl ?? ($city->slug === 'cleveland' ? route('cleveland.service.neighborhood.show', ['service' => $service->slug, 'neighborhood' => $neighborhood->slug]) : route('city.service.neighborhood.show', ['city' => $city->slug, 'service' => $service->slug, 'neighborhood' => $neighborhood->slug])) }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $service->name }} in {{ $neighborhood->name }} | Unloqit">
<meta name="twitter:description" content="Professional {{ $service->name }} services in {{ $neighborhood->name }}, {{ $city->name }}.">
@endsection

@section('jsonld')
<script type="application/ld+json">{!! $jsonld !!}</script>
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="bg-brand-light py-3">
    <div class="container mx-auto px-4">
        <ol class="flex items-center space-x-2 text-sm">
            @foreach($breadcrumbs as $crumb)
            <li>
                <a href="{{ $crumb['url'] }}" class="text-brand-accent hover:text-brand-accent-80">{{ $crumb['name'] }}</a>
            </li>
            @if(!$loop->last)
            <li class="text-brand-gray">/</li>
            @endif
            @endforeach
        </ol>
    </div>
</nav>

<!-- Hero -->
<section class="bg-brand-dark text-brand-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-4">{{ $service->name }} in {{ $neighborhood->name }}</h1>
        <p class="text-xl text-brand-gray">Professional locksmith services in {{ $neighborhood->name }}, {{ $city->name }}</p>
    </div>
</section>

<!-- Service Content -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-3xl font-bold mb-6">{{ $service->name }} Services in {{ $neighborhood->name }}</h2>
                <p class="text-lg text-brand-dark-60 mb-6">
                    {{ $service->description }}
                </p>
                
                <h3 class="text-2xl font-semibold mb-4">Serving {{ $neighborhood->name }}</h3>
                <p class="text-brand-dark-60 mb-6">
                    Unloqit Pro Service Providers offer fast and reliable {{ strtolower($service->name) }} services 
                    throughout {{ $neighborhood->name }}, {{ $city->name }}. Pros on the platform understand the local area 
                    and deliver quick response times for emergency situations.
                </p>

                <h3 class="text-2xl font-semibold mb-4">Why Use Unloqit?</h3>
                <ul class="list-disc list-inside space-y-2 text-brand-dark-60 mb-6">
                    <li>Instant matching with available pros 24/7</li>
                    <li>Fast response times in {{ $neighborhood->name }} (18-25 min average)</li>
                    <li>Verified Unloqit Pro Service Providers—all licensed and insured</li>
                    <li>Transparent pricing—pros quote before work begins</li>
                    <li>Real-time job tracking</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection

