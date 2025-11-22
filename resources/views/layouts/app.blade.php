<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Unloqit - 24/7 Locksmith Services')</title>
    
    <meta name="description" content="@yield('meta_description', 'Reliable 24/7 locksmith services in Cleveland, Ohio. Car lockouts, rekeys, key programming, residential & commercial.')">
    
    <link rel="canonical" href="@yield('canonical', url('/'))">
    
    @yield('meta_extra')
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
                    <a href="{{ route('cleveland.show') }}" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Services</a>
                    <a href="{{ route('pro.register') }}" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Become an Unloqit Pro</a>
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
                    <p class="text-brand-gray text-sm leading-relaxed mb-4">24/7 emergency locksmith services in Cleveland, Ohio. Precision. Speed. Reliability.</p>
                    <a href="{{ route('pro.register') }}" class="inline-block px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm uppercase tracking-wide hover:bg-brand-accent-80 transition">
                        Become an Unloqit Pro Service Provider
                    </a>
                </div>
                <div>
                    <h4 class="font-display font-bold text-lg text-brand-white mb-5 tracking-wide uppercase">Services</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('cleveland.service.show', ['service' => 'car-lockout']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Car Lockout</a></li>
                        <li><a href="{{ route('cleveland.service.show', ['service' => 'house-lockout']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">House Lockout</a></li>
                        <li><a href="{{ route('cleveland.service.show', ['service' => 'rekeying']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Rekeying</a></li>
                        <li><a href="{{ route('cleveland.service.show', ['service' => 'commercial-locksmith']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Commercial</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-bold text-lg text-brand-white mb-5 tracking-wide uppercase">Areas We Serve</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'ohio-city']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Ohio City</a></li>
                        <li><a href="{{ route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'tremont']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Tremont</a></li>
                        <li><a href="{{ route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'lakewood']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Lakewood</a></li>
                        <li><a href="{{ route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'university-circle']) }}" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">University Circle</a></li>
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

