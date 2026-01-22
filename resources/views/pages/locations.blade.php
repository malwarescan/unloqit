@extends('layouts.app')

@section('title', $title ?? 'Locations We Serve | Unloqit')
@section('meta_description', $meta_description ?? 'Find locksmith services in your city. Unloqit connects you with verified locksmith professionals across multiple locations.')
@section('canonical', route('locations.index'))

@section('jsonld')
<script type="application/ld+json">{!! $jsonld !!}</script>
@endsection

@section('content')
<section class="industrial-bg text-brand-white py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg">
                Locations We Serve
            </h1>
            <p class="text-xl text-brand-gray font-medium">
                Find locksmith services in your city. Browse covered locations below.
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-6xl mx-auto">
            @foreach($citiesByState as $state => $stateCities)
            <div class="mb-12">
                <h2 class="font-display font-bold text-3xl mb-6 text-brand-dark tracking-tight uppercase">{{ $state }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($stateCities as $city)
                    <a href="{{ route('city.show', ['state' => strtolower($city->state), 'city' => $city->slug]) }}" class="service-card p-6 block hover:border-brand-accent transition">
                        <h3 class="font-display font-bold text-xl text-brand-dark mb-2">{{ $city->name }}</h3>
                        <p class="text-brand-dark-60 text-sm">{{ $city->state }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
