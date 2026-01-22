@extends('layouts.app')

@section('title', $title ?? 'Locksmith Services | Unloqit')
@section('meta_description', $meta_description ?? 'Browse all locksmith services available through Unloqit. Car lockouts, house lockouts, rekeying, key programming, and more.')
@section('canonical', route('services.index'))

@section('jsonld')
<script type="application/ld+json">{!! $jsonld !!}</script>
@endsection

@section('content')
<section class="industrial-bg text-brand-white py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg">
                Locksmith Services
            </h1>
            <p class="text-xl text-brand-gray font-medium">
                Professional locksmith services available nationwide through the Unloqit marketplace.
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="service-card p-8">
                <h2 class="font-display font-bold text-2xl mb-4 text-brand-dark tracking-tight uppercase">
                    <a href="{{ route('services.show', ['service' => $service->slug]) }}" class="text-brand-dark hover:text-brand-accent transition-colors duration-300">
                        {{ $service->name }}
                    </a>
                </h2>
                <p class="text-brand-dark-60 mb-6 leading-relaxed">{{ $service->description }}</p>
                <a href="{{ route('services.show', ['service' => $service->slug]) }}" class="inline-flex items-center text-brand-accent font-bold hover:text-brand-accent-80 transition-colors duration-300 group">
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
@endsection
