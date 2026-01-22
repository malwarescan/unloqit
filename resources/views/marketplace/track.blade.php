@extends('layouts.app')

@section('title', 'Track Your Request | Unloqit')
@section('meta_description', 'Track your locksmith request in real-time. See when your Unloqit Pro arrives.')
@section('canonical', route('request.track', ['job' => $job->id]))
@section('meta_extra')
<meta name="robots" content="noindex,follow">
@endsection

@section('content')
<section class="industrial-bg text-brand-white py-24 relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg">
                Request Status
            </h1>
            <p class="text-xl text-brand-gray font-medium">
                Job #{{ $job->id }}
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto">
            <div class="bg-brand-white border-l-4 border-brand-accent p-8 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display font-bold text-2xl text-brand-dark uppercase tracking-tight">
                        {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                    </h2>
                    <span class="px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm uppercase">
                        {{ $job->urgency }}
                    </span>
                </div>
                
                <div class="space-y-3 text-brand-dark-60">
                    <p><strong class="text-brand-dark">Service:</strong> {{ $job->service->name }}</p>
                    <p><strong class="text-brand-dark">Location:</strong> {{ $job->city->name }}, {{ $job->city->state }}</p>
                    @if($job->neighborhood)
                        <p><strong class="text-brand-dark">Neighborhood:</strong> {{ $job->neighborhood->name }}</p>
                    @endif
                    @if($job->provider)
                        <p><strong class="text-brand-dark">Unloqit Pro:</strong> {{ $job->provider->name }}</p>
                    @endif
                </div>
            </div>

            @if($job->provider)
            <div class="bg-brand-dark border-l-4 border-brand-accent p-8 mb-8">
                <h3 class="font-display font-bold text-xl text-brand-white mb-4 uppercase tracking-tight">Your Unloqit Pro</h3>
                <div class="space-y-2 text-brand-gray">
                    <p><strong class="text-brand-white">Name:</strong> {{ $job->provider->name }}</p>
                    <p><strong class="text-brand-white">Rating:</strong> {{ number_format($job->provider->rating, 1) }}/5.0</p>
                    <p><strong class="text-brand-white">Jobs Completed:</strong> {{ $job->provider->total_jobs }}</p>
                    @if($job->provider->phone)
                        <p><strong class="text-brand-white">Phone:</strong> {{ $job->provider->phone }}</p>
                    @endif
                </div>
            </div>
            @endif

            <div class="bg-brand-white p-8">
                <h3 class="font-display font-bold text-xl text-brand-dark mb-4 uppercase tracking-tight">Status History</h3>
                <div class="space-y-4">
                    @foreach($job->statuses as $status)
                    <div class="border-l-2 border-brand-accent pl-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-display font-bold text-brand-dark uppercase">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                            <span class="text-brand-gray text-sm">{{ $status->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        @if($status->notes)
                            <p class="text-brand-dark-60 text-sm">{{ $status->notes }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

