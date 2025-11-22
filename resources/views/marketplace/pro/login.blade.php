@extends('layouts.app')

@section('title', $title ?? 'Unloqit Pro Login | Unloqit')
@section('meta_description', $meta_description ?? 'Login to your Unloqit Pro dashboard to manage jobs and earnings.')
@section('canonical', route('pro.login'))

@section('content')
<section class="industrial-bg text-brand-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background: radial-gradient(circle at 30% 50%, rgba(255, 106, 58, 0.3) 0%, transparent 50%);"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg animate-fade-in-up">
                Unloqit Pro Login
            </h1>
            <p class="text-xl md:text-2xl text-brand-gray font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Access your dashboard to manage jobs, earnings, and availability.
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('pro.login.submit') }}" method="POST" class="bg-brand-white p-8 border-l-4 border-brand-accent">
                @csrf
                
                <div class="space-y-6">
                    @if(session('success'))
                        <div class="bg-brand-accent text-brand-white p-4">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" autofocus>
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

                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-brand-accent border-brand-gray focus:ring-brand-accent">
                            <span class="text-brand-dark text-sm">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="emergency-button w-full text-center">
                        Log In
                    </button>

                    <p class="text-center text-brand-dark-60 text-sm">
                        Don't have an account? <a href="{{ route('pro.register') }}" class="text-brand-accent font-bold hover:text-brand-accent-80 transition">Become an Unloqit Pro</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

