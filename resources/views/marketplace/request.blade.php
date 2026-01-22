@extends('layouts.app')

@section('title', $title ?? 'Request a Locksmith | Instant Help | Unloqit')
@section('meta_description', $meta_description ?? 'Request a locksmith instantly. Unloqit Pro Service Providers in your area are ready to help. Fast response, professional service.')
@section('canonical', route('request.show'))
@section('meta_extra')
<meta name="robots" content="noindex,follow">
@endsection

@section('content')
<section class="industrial-bg text-brand-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background: radial-gradient(circle at 30% 50%, rgba(255, 106, 58, 0.3) 0%, transparent 50%);"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg animate-fade-in-up">
                Request an Unloqit Pro
            </h1>
            <p class="text-xl md:text-2xl text-brand-gray font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Unloqit Pro Service Providers in your area are ready. Submit your request and get matched instantly.
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('request.submit') }}" method="POST" class="bg-brand-white p-8 border-l-4 border-brand-accent">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="city_id" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">City</label>
                        <select name="city_id" id="city_id" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                            <option value="">Select a city</option>
                            @foreach($cities as $cityOption)
                                <option value="{{ $cityOption->id }}" {{ $city && $city->id === $cityOption->id ? 'selected' : '' }}>
                                    {{ $cityOption->name }}, {{ $cityOption->state }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="service_id" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Service Needed</label>
                        <select name="service_id" id="service_id" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                            <option value="">Select a service</option>
                            @foreach($services as $serviceOption)
                                <option value="{{ $serviceOption->id }}" {{ $service && $service->id === $serviceOption->id ? 'selected' : '' }}>
                                    {{ $serviceOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="urgency" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Urgency</label>
                        <select name="urgency" id="urgency" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                            <option value="normal">Normal</option>
                            <option value="high">High - Need help soon</option>
                            <option value="emergency">Emergency - Locked out now</option>
                        </select>
                    </div>

                    <div>
                        <label for="customer_name" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Your Name</label>
                        <input type="text" name="customer_name" id="customer_name" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="John Doe">
                    </div>

                    <div>
                        <label for="customer_phone" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Phone Number</label>
                        <input type="tel" name="customer_phone" id="customer_phone" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="(555) 123-4567">
                    </div>

                    <div>
                        <label for="customer_email" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Email (Optional)</label>
                        <input type="email" name="customer_email" id="customer_email" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="john@example.com">
                    </div>

                    <div>
                        <label for="address" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Address (Optional)</label>
                        <input type="text" name="address" id="address" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="123 Main St, Cleveland, OH">
                    </div>

                    <div>
                        <label for="description" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Details (Optional)</label>
                        <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="Describe your situation..."></textarea>
                    </div>

                    <button type="submit" class="emergency-button w-full text-center">
                        Request Unloqit Pro Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

