@extends('layouts.app')

@section('title', $title ?? 'Unloqit | 24/7 Locksmith Dispatch Marketplace')
@section('meta_description', $meta_description ?? 'Nationwide locksmith marketplace. Get matched with verified locksmith professionals in minutes. Real-time tracking, transparent pricing, 24/7 availability.')
@section('canonical', route('home'))

@section('meta_extra')
<meta property="og:title" content="Unloqit | 24/7 Locksmith Dispatch Marketplace">
<meta property="og:description" content="Nationwide locksmith marketplace. Get matched with verified locksmith professionals in minutes. Real-time tracking, transparent pricing, 24/7 availability.">
<meta property="og:url" content="{{ route('home') }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Unloqit | 24/7 Locksmith Dispatch Marketplace">
<meta name="twitter:description" content="Nationwide locksmith marketplace. Get matched with verified locksmith professionals in minutes. Real-time tracking, transparent pricing, 24/7 availability.">
@endsection

@section('jsonld')
<script type="application/ld+json">{!! $jsonld !!}</script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="industrial-bg text-brand-white py-32 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background: radial-gradient(circle at 30% 20%, rgba(255, 106, 58, 0.3) 0%, transparent 50%);"></div>
        <div class="absolute bottom-0 right-0 w-full h-full" style="background: radial-gradient(circle at 70% 80%, rgba(255, 106, 58, 0.2) 0%, transparent 50%);"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-6 animate-fade-in-down">
                <span class="inline-block px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm tracking-widest uppercase">24/7 Emergency Service</span>
            </div>
            <h1 class="font-display font-black text-7xl md:text-8xl mb-8 tracking-tight text-shadow-lg animate-fade-in-up" style="line-height: 0.95;">
                24/7<br>Locksmith<br><span class="text-brand-accent">Marketplace</span>
            </h1>
            <p class="text-xl md:text-2xl mb-12 text-brand-gray font-medium max-w-2xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Connect with verified locksmith professionals nationwide. Real-time matching. Transparent pricing. Available around the clock.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fade-in-up" style="animation-delay: 0.4s;">
                <a href="{{ route('request.show') }}" class="emergency-button">
                    Request Unloqit Pro Now
                </a>
                <a href="{{ route('locations.index') }}" class="px-8 py-4 bg-transparent border-2 border-brand-white text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:bg-brand-white hover:text-brand-dark transition-all duration-300">
                    View Locations
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16 section-reveal">
            <h2 class="font-display font-black text-5xl md:text-6xl mb-4 text-brand-dark tracking-tight">Our Services</h2>
            <div class="w-24 h-1 bg-brand-accent mx-auto"></div>
        </div>
        @forelse($services ?? [] as $index => $service)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $index => $service)
            <div class="service-card p-8">
                <h3 class="font-display font-bold text-2xl mb-4 text-brand-dark tracking-tight uppercase">
                    <a href="{{ route('services.show', ['service' => $service->slug]) }}" class="text-brand-dark hover:text-brand-accent transition-colors duration-300">
                        {{ $service->name }}
                    </a>
                </h3>
                <p class="text-brand-dark-60 mb-6 leading-relaxed">{{ $service->description ?? 'Professional ' . strtolower($service->name) . ' services available nationwide through the Unloqit marketplace.' }}</p>
                <a href="{{ route('services.show', ['service' => $service->slug]) }}" class="inline-flex items-center text-brand-accent font-bold hover:text-brand-accent-80 transition-colors duration-300 group">
                    <span class="mr-2">Learn more</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-brand-dark-60 text-lg">Services are being loaded. Please check back shortly.</p>
            <a href="{{ route('services.index') }}" class="inline-block mt-4 text-brand-accent font-bold hover:text-brand-accent-80 transition-colors duration-300">
                View All Services →
            </a>
        </div>
        @endif
    </div>
</section>

<!-- How It Works -->
<section class="industrial-bg py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center section-reveal">
            <h2 class="font-display font-black text-5xl md:text-6xl mb-6 text-brand-white tracking-tight">
                How It Works
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mt-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand-accent rounded-full flex items-center justify-center mx-auto mb-4 text-brand-white font-display font-bold text-2xl">1</div>
                    <h3 class="font-display font-bold text-lg text-brand-white mb-2 uppercase">Request</h3>
                    <p class="text-brand-gray text-sm">Submit your locksmith request with location and service needed.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand-accent rounded-full flex items-center justify-center mx-auto mb-4 text-brand-white font-display font-bold text-2xl">2</div>
                    <h3 class="font-display font-bold text-lg text-brand-white mb-2 uppercase">Match</h3>
                    <p class="text-brand-gray text-sm">Get matched with verified pros in your area instantly.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand-accent rounded-full flex items-center justify-center mx-auto mb-4 text-brand-white font-display font-bold text-2xl">3</div>
                    <h3 class="font-display font-bold text-lg text-brand-white mb-2 uppercase">Track</h3>
                    <p class="text-brand-gray text-sm">Follow your pro's progress in real-time from dispatch to arrival.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand-accent rounded-full flex items-center justify-center mx-auto mb-4 text-brand-white font-display font-bold text-2xl">4</div>
                    <h3 class="font-display font-bold text-lg text-brand-white mb-2 uppercase">Complete</h3>
                    <p class="text-brand-gray text-sm">Transparent pricing. Professional service. Job done.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Assurance -->
<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16 section-reveal">
            <h2 class="font-display font-black text-5xl md:text-6xl mb-4 text-brand-dark tracking-tight">Why Choose Unloqit?</h2>
            <div class="w-24 h-1 bg-brand-accent mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
            <div class="text-center section-reveal">
                <div class="w-20 h-20 bg-brand-dark rounded-none flex items-center justify-center mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-brand-accent opacity-20"></div>
                    <svg class="w-10 h-10 text-brand-accent relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-2xl mb-3 text-brand-dark tracking-tight uppercase">24/7 Availability</h3>
                <p class="text-brand-dark-60 leading-relaxed">We're available around the clock, including holidays and weekends.</p>
            </div>
            <div class="text-center section-reveal" style="animation-delay: 0.2s;">
                <div class="w-20 h-20 bg-brand-dark rounded-none flex items-center justify-center mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-brand-accent opacity-20"></div>
                    <svg class="w-10 h-10 text-brand-accent relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-2xl mb-3 text-brand-dark tracking-tight uppercase">Fast Response</h3>
                <p class="text-brand-dark-60 leading-relaxed">Quick arrival times to get you back on track as soon as possible.</p>
            </div>
            <div class="text-center section-reveal" style="animation-delay: 0.4s;">
                <div class="w-20 h-20 bg-brand-dark rounded-none flex items-center justify-center mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-brand-accent opacity-20"></div>
                    <svg class="w-10 h-10 text-brand-accent relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-2xl mb-3 text-brand-dark tracking-tight uppercase">Vetted Professionals</h3>
                <p class="text-brand-dark-60 leading-relaxed">All locksmiths are background-checked and fully licensed.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Preview -->
<section class="industrial-bg py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16 section-reveal">
            <h2 class="font-display font-black text-5xl md:text-6xl mb-4 text-brand-white tracking-tight">Frequently Asked Questions</h2>
            <div class="w-24 h-1 bg-brand-accent mx-auto"></div>
        </div>
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="bg-brand-dark border-l-4 border-brand-accent p-8 section-reveal">
                <h3 class="font-display font-bold text-xl mb-3 text-brand-white tracking-tight uppercase">How quickly can a locksmith arrive?</h3>
                <p class="text-brand-gray leading-relaxed">Response times vary by location and traffic conditions. Unloqit Pro Service Providers typically arrive within 20-45 minutes of job claim, depending on your area.</p>
            </div>
            <div class="bg-brand-dark border-l-4 border-brand-accent p-8 section-reveal" style="animation-delay: 0.2s;">
                <h3 class="font-display font-bold text-xl mb-3 text-brand-white tracking-tight uppercase">Do you work on all car makes and models?</h3>
                <p class="text-brand-gray leading-relaxed">Yes, Unloqit Pro Service Providers on the platform are trained to work on all vehicle types, including modern keyless entry systems.</p>
            </div>
            <div class="bg-brand-dark border-l-4 border-brand-accent p-8 section-reveal" style="animation-delay: 0.4s;">
                <h3 class="font-display font-bold text-xl mb-3 text-brand-white tracking-tight uppercase">Are Unloqit Pro Service Providers licensed and insured?</h3>
                <p class="text-brand-gray leading-relaxed">All Unloqit Pro Service Providers are verified: fully licensed, bonded, and insured. We verify credentials before activation.</p>
            </div>
        </div>
        <div class="text-center mt-12 section-reveal" style="animation-delay: 0.6s;">
            <a href="{{ route('home') }}#faq" class="inline-flex items-center text-brand-accent font-display font-bold text-lg tracking-wide uppercase hover:text-brand-white transition-colors duration-300 group">
                <span class="mr-2">View All FAQs</span>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
@endsection

