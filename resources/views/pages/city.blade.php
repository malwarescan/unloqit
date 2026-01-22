@extends('layouts.app')

@section('title', $title ?? ($city->name . ' Locksmith | 24/7 Lockout & Car Keys | Unloqit'))
@section('meta_description', $meta_description ?? ('Reliable 24/7 locksmith services in ' . $city->name . ', ' . $city->state . '. Car lockouts, rekeys, key programming, residential & commercial. Fast arrival times.'))
@section('canonical', $cityUrl ?? route('city.show', ['state' => strtolower($city->state), 'city' => $city->slug]))

@section('meta_extra')
@if(!($isIndexable ?? true))
<meta name="robots" content="noindex,follow">
@endif
<meta property="og:title" content="{{ $city->name }} Locksmith | 24/7 Lockout & Car Keys | Unloqit">
<meta property="og:description" content="Reliable 24/7 locksmith services in {{ $city->name }}, {{ $city->state }}. Car lockouts, rekeys, key programming, residential & commercial.">
<meta property="og:url" content="{{ $cityUrl }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $city->name }} Locksmith | Unloqit">
<meta name="twitter:description" content="Reliable 24/7 locksmith services in {{ $city->name }}, {{ $city->state }}.">
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
        <div class="absolute top-0 right-0 w-full h-full" style="background: radial-gradient(circle at 70% 30%, rgba(255, 106, 58, 0.3) 0%, transparent 50%);"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl">
            <div class="mb-4 animate-fade-in-down">
                <span class="inline-block px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm tracking-widest uppercase">24/7 Emergency Service</span>
            </div>
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg animate-fade-in-up">
                {{ $city->name }}<br>Locksmith Services
            </h1>
            <p class="text-xl md:text-2xl text-brand-gray font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                24/7 emergency locksmith services in {{ $city->name }}, {{ $city->state }}
            </p>
        </div>
    </div>
</section>

<!-- Services -->
<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16 section-reveal">
            <h2 class="font-display font-black text-5xl md:text-6xl mb-4 text-brand-dark tracking-tight">Available Services</h2>
            <div class="w-24 h-1 bg-brand-accent mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $index => $service)
            <div class="service-card p-8 section-reveal" style="animation-delay: {{ ($index + 1) * 0.1 }}s;">
                <h3 class="font-display font-bold text-2xl mb-4 text-brand-dark tracking-tight uppercase">
                    <a href="{{ $city->slug === 'cleveland' ? route('cleveland.service.show', ['service' => $service->slug]) : route('city.service.show', ['city' => $city->slug, 'service' => $service->slug]) }}" class="text-brand-dark hover:text-brand-accent transition-colors duration-300">
                        {{ $service->name }}
                    </a>
                </h3>
                <p class="text-brand-dark-60 mb-6 leading-relaxed">{{ $service->description }}</p>
                <a href="{{ $city->slug === 'cleveland' ? route('cleveland.service.show', ['service' => $service->slug]) : route('city.service.show', ['city' => $city->slug, 'service' => $service->slug]) }}" class="inline-flex items-center text-brand-accent font-bold hover:text-brand-accent-80 transition-colors duration-300 group">
                    <span class="mr-2">Learn more</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Marketplace CTA -->
<section class="industrial-bg py-16 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="font-display font-black text-4xl md:text-5xl mb-4 text-brand-white tracking-tight">
                Unloqit Pro Service Providers<br>Ready in {{ $city->name }}
            </h2>
            <p class="text-lg text-brand-gray mb-6">
                Request help instantly. Verified Unloqit Pro Service Providers in your area are standing by.
            </p>
            <a href="{{ route('request.show.context', ['city' => $city->slug, 'service' => 'car-lockout']) }}" class="emergency-button inline-block">
                Request an Unloqit Pro
            </a>
        </div>
    </div>
</section>

<!-- City Info -->
<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto section-reveal">
            @if(isset($generatedContent) && $generatedContent)
                <div class="prose prose-lg max-w-none text-brand-white">
                    <div class="text-brand-gray leading-relaxed text-lg">
                        {!! nl2br(e($generatedContent->content)) !!}
                    </div>
                </div>
            @else
                <h2 class="font-display font-black text-4xl md:text-5xl mb-8 text-brand-white tracking-tight">
                    Professional Locksmith Services in {{ $city->name }}
                </h2>
                <div class="space-y-6 text-brand-gray text-lg leading-relaxed">
                    <p>
                        Unloqit connects you with verified Unloqit Pro Service Providers throughout {{ $city->name }}, {{ $city->state }}. 
                        Pros on the platform are available 24/7 to help with emergency lockouts, lock installation, 
                        rekeying, and more.
                    </p>
                    <p>
                        Whether you have an unloqit car, unloqit house, or need commercial locksmith services, 
                        submit a request and get matched with a verified pro instantly. Fast matching, professional service, real-time tracking.
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

