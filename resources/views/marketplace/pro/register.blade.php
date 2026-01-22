@extends('layouts.app')

@section('title', $title ?? 'Become an Unloqit Pro Service Provider | Join Unloqit Marketplace')
@section('meta_description', $meta_description ?? 'Join Unloqit as a verified locksmith. Set your own schedule, earn competitive rates, and help customers in your area.')
@section('canonical', route('pro.register'))
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
                Become an Unloqit Pro<br>Service Provider
            </h1>
            <p class="text-xl md:text-2xl text-brand-gray font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Join the Unloqit marketplace as a verified locksmith. Set your schedule, earn competitive rates, help customers in your area.
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('pro.register.submit') }}" method="POST" class="bg-brand-white p-8 border-l-4 border-brand-accent">
                @csrf
                
                <div class="space-y-6">
                    <h2 class="font-display font-bold text-2xl text-brand-dark mb-6 uppercase tracking-tight">Account Information</h2>
                    
                    <div>
                        <label for="name" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Full Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                        @error('name')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                        @error('email')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Password</label>
                        <input type="password" name="password" id="password" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                        @error('password')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                    </div>

                    <div>
                        <label for="phone" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Phone Number</label>
                        <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="(555) 123-4567">
                        @error('phone')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_number" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">License Number *</label>
                        <input type="text" name="license_number" id="license_number" required value="{{ old('license_number') }}" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="Required for verification">
                        <p class="text-brand-dark-60 text-sm mt-1">Your locksmith license number. Required for verification.</p>
                        @error('license_number')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bio" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Bio (Optional)</label>
                        <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="Tell customers about your experience...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <h2 class="font-display font-bold text-2xl text-brand-dark mb-6 uppercase tracking-tight mt-8">Service Areas</h2>
                    
                    <div>
                        <label class="block font-display font-bold text-lg mb-3 text-brand-dark uppercase tracking-tight">Cities You Serve *</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-48 overflow-y-auto border border-brand-gray p-4">
                            @foreach($cities as $city)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="service_areas[]" value="{{ $city->id }}" {{ in_array($city->id, old('service_areas', [])) ? 'checked' : '' }} class="w-4 h-4 text-brand-accent border-brand-gray focus:ring-brand-accent">
                                <span class="text-brand-dark text-sm">{{ $city->name }}, {{ $city->state }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('service_areas')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-display font-bold text-lg mb-3 text-brand-dark uppercase tracking-tight">Services You Offer *</label>
                        <div class="space-y-2 border border-brand-gray p-4">
                            @foreach($services as $service)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="service_types[]" value="{{ $service->id }}" {{ in_array($service->id, old('service_types', [])) ? 'checked' : '' }} class="w-4 h-4 text-brand-accent border-brand-gray focus:ring-brand-accent">
                                <span class="text-brand-dark">{{ $service->name }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('service_types')
                            <p class="text-brand-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-brand-light p-4 border-l-4 border-brand-accent">
                        <p class="text-brand-dark text-sm">
                            <strong>Verification Process:</strong> After registration, your account will be reviewed for verification. 
                            You'll receive an email once your account is verified and activated. This typically takes 24-48 hours.
                        </p>
                    </div>

                    <button type="submit" class="emergency-button w-full text-center">
                        Join as Unloqit Pro Service Provider
                    </button>

                    <p class="text-center text-brand-dark-60 text-sm">
                        Already have an account? <a href="{{ route('pro.login') }}" class="text-brand-accent font-bold hover:text-brand-accent-80 transition">Log in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

