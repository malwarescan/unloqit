@extends('layouts.app')

@section('title', 'Unloqit Pro Dashboard | Unloqit')
@section('meta_description', 'Manage your locksmith jobs, earnings, and availability.')
@section('canonical', route('pro.dashboard'))
@section('meta_extra')
<meta name="robots" content="noindex,follow">
@endsection

@section('content')
<section class="industrial-bg text-brand-white py-16 relative">
    <div class="container mx-auto px-6 relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-display font-black text-4xl mb-2 tracking-tight">Unloqit Pro Dashboard</h1>
                    <p class="text-brand-gray">Welcome back, {{ $provider->name }}</p>
                    @if(!$provider->is_verified)
                        <p class="text-brand-accent text-sm mt-2">⚠️ Your account is pending verification</p>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('pro.toggle-online') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 {{ $provider->isOnline() ? 'bg-brand-accent' : 'bg-brand-dark-60' }} text-brand-white font-display font-bold uppercase tracking-wide hover:opacity-90 transition" {{ !$provider->is_verified || !$provider->is_active ? 'disabled' : '' }}>
                            {{ $provider->isOnline() ? 'Online' : 'Go Online' }}
                        </button>
                    </form>
                    <form action="{{ route('pro.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-brand-dark-60 text-brand-white font-display font-bold uppercase tracking-wide hover:opacity-90 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
    </div>
</section>

<section class="atmospheric-surface py-12 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-brand-white p-6 border-l-4 border-brand-accent">
                <h3 class="font-display font-bold text-lg text-brand-dark mb-2 uppercase">Active Jobs</h3>
                <p class="text-3xl font-bold text-brand-accent">{{ $activeJobs->count() }}</p>
            </div>
            <div class="bg-brand-white p-6 border-l-4 border-brand-accent">
                <h3 class="font-display font-bold text-lg text-brand-dark mb-2 uppercase">Available Jobs</h3>
                <p class="text-3xl font-bold text-brand-accent">{{ $availableJobs->count() }}</p>
            </div>
            <div class="bg-brand-white p-6 border-l-4 border-brand-accent">
                <h3 class="font-display font-bold text-lg text-brand-dark mb-2 uppercase">Rating</h3>
                <p class="text-3xl font-bold text-brand-accent">{{ number_format($provider->rating, 1) }}</p>
            </div>
        </div>

        @if($activeJobs->count() > 0)
        <div class="bg-brand-white p-8 mb-8">
            <h2 class="font-display font-bold text-2xl text-brand-dark mb-6 uppercase tracking-tight">Active Jobs</h2>
            <div class="space-y-4">
                @foreach($activeJobs as $job)
                <div class="border-l-4 border-brand-accent p-4 bg-brand-light">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-display font-bold text-lg text-brand-dark">{{ $job->service->name }}</h3>
                        <span class="px-3 py-1 bg-brand-accent text-brand-white text-sm font-bold uppercase">{{ $job->status }}</span>
                    </div>
                    <p class="text-brand-dark-60 mb-2">{{ $job->city->name }}, {{ $job->city->state }}</p>
                    <a href="{{ route('pro.jobs.show', ['job' => $job->id]) }}" class="text-brand-accent font-bold hover:text-brand-accent-80 transition">
                        View Details →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($availableJobs->count() > 0)
        <div class="bg-brand-white p-8">
            <h2 class="font-display font-bold text-2xl text-brand-dark mb-6 uppercase tracking-tight">Available Jobs Near You</h2>
            <div class="space-y-4">
                @foreach($availableJobs->take(5) as $job)
                <div class="border border-brand-gray p-4 hover:border-brand-accent transition">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-display font-bold text-lg text-brand-dark">{{ $job->service->name }}</h3>
                        <span class="px-3 py-1 bg-brand-dark text-brand-white text-sm font-bold uppercase">{{ $job->urgency }}</span>
                    </div>
                    <p class="text-brand-dark-60 mb-3">{{ $job->city->name }}, {{ $job->city->state }}</p>
                    <form action="{{ route('pro.jobs.claim', ['job' => $job->id]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="emergency-button text-sm px-4 py-2">
                            Claim Job
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('pro.jobs') }}" class="text-brand-accent font-bold hover:text-brand-accent-80 transition">
                    View All Available Jobs →
                </a>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

