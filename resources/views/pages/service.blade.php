@extends('layouts.app')

@section('title', $title ?? $service->name . ' | Unloqit')
@section('meta_description', $meta_description ?? $service->description)
@section('canonical', route('services.show', ['service' => $service->slug]))

@section('jsonld')
<script type="application/ld+json">{!! $jsonld !!}</script>
@endsection

@section('content')
<section class="industrial-bg text-brand-white py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-brand-gray text-sm">
                    @foreach($breadcrumbs as $crumb)
                    <li><a href="{{ $crumb['url'] }}" class="hover:text-brand-accent transition">{{ $crumb['name'] }}</a></li>
                    <li class="text-brand-accent">/</li>
                    @endforeach
                    <li class="text-brand-white">{{ $service->name }}</li>
                </ol>
            </nav>
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg">
                {{ $service->name }}
            </h1>
            <p class="text-xl text-brand-gray font-medium leading-relaxed">
                {{ $service->description }}
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <div class="bg-brand-white p-8 border-l-4 border-brand-accent">
                <h2 class="font-display font-bold text-3xl mb-6 text-brand-dark tracking-tight uppercase">Available Nationwide</h2>
                <p class="text-brand-dark-60 leading-relaxed mb-6">
                    {{ $service->name }} services are available through the Unloqit marketplace in all covered locations. 
                    Get matched with verified locksmith professionals who specialize in this service.
                </p>
                <a href="{{ route('request.show') }}" class="emergency-button inline-block">
                    Request {{ $service->name }} Now
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
