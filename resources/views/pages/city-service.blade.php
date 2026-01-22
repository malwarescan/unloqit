@extends('layouts.app')

@section('title', $title ?? ($service->name . ' in ' . $city->name . ' | ' . $city->name . ' Locksmith | Unloqit'))
@section('meta_description', $meta_description ?? ('Professional ' . $service->name . ' services in ' . $city->name . ', ' . $city->state . '. Fast, reliable, and available 24/7.'))
@section('canonical', $serviceUrl ?? route('city.service.show', ['state' => strtolower($city->state), 'city' => $city->slug, 'service' => $service->slug]))

@section('meta_extra')
@if(!($isIndexable ?? true))
<meta name="robots" content="noindex,follow">
@endif
<meta property="og:title" content="{{ $service->name }} in {{ $city->name }} | Unloqit">
<meta property="og:description" content="Professional {{ $service->name }} services in {{ $city->name }}, {{ $city->state }}. Fast, reliable, and available 24/7.">
<meta property="og:url" content="{{ $serviceUrl }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $service->name }} in {{ $city->name }} | Unloqit">
<meta name="twitter:description" content="Professional {{ $service->name }} services in {{ $city->name }}, {{ $city->state }}.">
@endsection

@section('jsonld')
<script type="application/ld+json">{!! $jsonld !!}</script>
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="atmospheric-surface py-4 border-b border-brand-gray">
    <div class="container mx-auto px-6">
        <ol class="flex items-center space-x-3 text-sm">
            @foreach($breadcrumbs as $crumb)
            <li>
                <a href="{{ $crumb['url'] }}" class="text-brand-accent hover:text-brand-dark font-medium transition-colors duration-300">{{ $crumb['name'] }}</a>
            </li>
            @if(!$loop->last)
            <li class="text-brand-gray">/</li>
            @endif
            @endforeach
        </ol>
    </div>
</nav>

<!-- Hero -->
<section class="industrial-bg text-brand-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background: radial-gradient(circle at 30% 50%, rgba(255, 106, 58, 0.3) 0%, transparent 50%);"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl">
            <div class="mb-4 animate-fade-in-down">
                <span class="inline-block px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm tracking-widest uppercase">24/7 Emergency Service</span>
            </div>
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg animate-fade-in-up">
                {{ $service->name }}<br>in {{ $city->name }}
            </h1>
            <p class="text-xl md:text-2xl text-brand-gray font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Professional locksmith services available 24/7
            </p>
        </div>
    </div>
</section>

<!-- Service Content -->
<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto section-reveal">
            <div class="space-y-8">
                <div>
                    <h2 class="font-display font-black text-4xl md:text-5xl mb-6 text-brand-dark tracking-tight">
                        About {{ $service->name }} Services
                    </h2>
                    <p class="text-lg text-brand-dark-60 mb-8 leading-relaxed">
                        {{ $service->description }}
                    </p>
                </div>
                
                <div class="bg-brand-dark border-l-4 border-brand-accent p-8">
                    <h3 class="font-display font-bold text-2xl mb-6 text-brand-white tracking-tight uppercase">
                        Why Use Unloqit for {{ $service->name }}?
                    </h3>
                    <ul class="space-y-4 text-brand-gray text-lg">
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Instant matching with available pros 24/7</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Fast response times in {{ $city->name }} (20-30 min average)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Verified Unloqit Pro Service Providers—all licensed and insured</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Transparent pricing—pros quote before work begins</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Real-time job tracking—see when your pro arrives</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-display font-bold text-3xl mb-4 text-brand-dark tracking-tight uppercase">
                        Serving {{ $city->name }} Neighborhoods
                    </h3>
                    <p class="text-lg text-brand-dark-60 leading-relaxed">
                        Unloqit Pro Service Providers offer {{ strtolower($service->name) }} services throughout {{ $city->name }} and surrounding areas. 
                        Pros on the platform are familiar with local neighborhoods and deliver fast service.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Marketplace CTA -->
<section class="industrial-bg py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center section-reveal">
            <h2 class="font-display font-black text-5xl md:text-6xl mb-6 text-brand-white tracking-tight">
                Need {{ $service->name }} Now?
            </h2>
            <p class="text-xl text-brand-gray mb-10 leading-relaxed max-w-2xl mx-auto">
                Unloqit Pro Service Providers in {{ $city->name }} are ready. Request help and get matched with a verified locksmith instantly.
            </p>
            <a href="{{ route('request.show.context', ['city' => $city->slug, 'service' => $service->slug]) }}" class="emergency-button inline-block">
                Request an Unloqit Pro
            </a>
            <p class="text-brand-gray text-sm mt-4">
                Average response time: 20-30 minutes
            </p>
        </div>
    </div>
</section>
@endsection

