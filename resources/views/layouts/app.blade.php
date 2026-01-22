<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Unloqit - 24/7 Locksmith Services')</title>
    
    <meta name="description" content="@yield('meta_description', 'Reliable 24/7 locksmith services in Cleveland, Ohio. Car lockouts, rekeys, key programming, residential & commercial.')">
    
    <link rel="canonical" href="@yield('canonical', url('/'))">
    
    {{-- Favicon - Multiple formats for browser compatibility --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    
    @yield('meta_extra')
    
    @php
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            try {
                @vite(['resources/css/app.css', 'resources/js/app.js']);
            } catch (\Exception $e) {
                // Fallback to direct asset links if Vite fails
                $assets = glob(public_path('build/assets/app-*.css'));
                $jsAssets = glob(public_path('build/assets/app-*.js'));
                if (!empty($assets)) {
                    echo '<link rel="stylesheet" href="' . asset('build/assets/' . basename($assets[0])) . '">';
                }
                if (!empty($jsAssets)) {
                    echo '<script src="' . asset('build/assets/' . basename($jsAssets[0])) . '"></script>';
                }
            }
        } else {
            // No manifest - try to find assets directly
            $assets = glob(public_path('build/assets/app-*.css'));
            $jsAssets = glob(public_path('build/assets/app-*.js'));
            if (!empty($assets)) {
                echo '<link rel="stylesheet" href="' . asset('build/assets/' . basename($assets[0])) . '">';
            }
            if (!empty($jsAssets)) {
                echo '<script src="' . asset('build/assets/' . basename($jsAssets[0])) . '"></script>';
            }
        }
    @endphp
    
    @yield('jsonld')
</head>
<body class="bg-brand-light text-brand-dark">
    <header class="industrial-bg border-b-2 border-brand-accent relative z-50">
        <nav class="container mx-auto px-6 py-5">
            <div class="flex items-center justify-between relative z-10">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ asset('unloqit-logo.png') }}?v={{ filemtime(public_path('unloqit-logo.png')) }}" alt="Unloqit" class="h-12 transition-transform duration-300 group-hover:scale-105">
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('services.index') }}" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Services</a>
                    <a href="{{ route('locations.index') }}" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Locations</a>
                    <a href="{{ route('request.show') }}" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Request</a>
                    <a href="{{ route('pro.login') }}" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Providers</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="relative">
        @yield('content')
    </main>

    <footer class="industrial-bg border-t-2 border-brand-accent mt-20 relative">
        <div class="container mx-auto px-6 py-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <h3 class="font-display font-bold text-2xl text-brand-white mb-4 tracking-wide uppercase">Unloqit</h3>
                    <p class="text-brand-gray text-sm leading-relaxed mb-4">Nationwide locksmith marketplace. Connect with verified professionals. Real-time tracking. Transparent pricing. 24/7.</p>
                    <a href="{{ route('pro.register') }}" class="inline-block px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm uppercase tracking-wide hover:bg-brand-accent-80 transition">
                        Become an Unloqit Pro Service Provider
                    </a>
                </div>
                <div>
                    <h4 class="font-display font-bold text-lg text-brand-white mb-5 tracking-wide uppercase">Services</h4>
                    <ul class="space-y-3">
                        @foreach($footerServices ?? [] as $service)
                        <li><a href="{{ route('services.show', ['service' => $service->slug]) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">{{ $service->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('services.index') }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">View All Services →</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-bold text-lg text-brand-white mb-5 tracking-wide uppercase">Locations</h4>
                    <ul class="space-y-3">
                        @foreach($footerCities ?? [] as $city)
                        <li><a href="{{ route('city.show', ['state' => strtolower($city->state), 'city' => $city->slug]) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">{{ $city->name }}, {{ $city->state }}</a></li>
                        @endforeach
                        <li><a href="{{ route('locations.index') }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">View All Locations →</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-brand-dark-60 pt-8">
                <p class="text-brand-gray text-sm text-center font-medium">&copy; {{ date('Y') }} Unloqit. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>

