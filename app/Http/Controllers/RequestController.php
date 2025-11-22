<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Job;
use App\Models\Service;
use App\Services\DispatchService;
use App\Services\TitleMetaService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestController extends Controller
{
    public function __construct(
        private DispatchService $dispatchService,
        private TitleMetaService $titleMeta
    ) {}

    /**
     * Show request form (pre-filled from service page context)
     */
    public function show(?string $citySlug = null, ?string $serviceSlug = null): View
    {
        $city = null;
        $service = null;

        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
        }

        if ($serviceSlug) {
            $service = Service::where('slug', $serviceSlug)->first();
        }

        $titleMeta = $this->titleMeta->forRequest($city, $service);

        return view('marketplace.request', [
            'city' => $city,
            'service' => $service,
            'cities' => City::all(),
            'services' => Service::all(),
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }

    /**
     * Submit job request
     */
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'service_id' => 'required|exists:services,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:1000',
            'urgency' => 'required|in:low,normal,high,emergency',
            'address' => 'nullable|string|max:500',
        ]);

        $city = City::findOrFail($validated['city_id']);
        $service = Service::findOrFail($validated['service_id']);

        // Create job
        $job = Job::create([
            'city_id' => $validated['city_id'],
            'service_id' => $validated['service_id'],
            'neighborhood_id' => $validated['neighborhood_id'] ?? null,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'description' => $validated['description'] ?? null,
            'urgency' => $validated['urgency'],
            'address' => $validated['address'] ?? null,
            'status' => 'created',
            'requested_at' => now(),
        ]);

        // Broadcast to providers
        $this->dispatchService->broadcastJob($job);

        return redirect()->route('request.track', ['job' => $job->id])
            ->with('success', 'Your request has been sent. Unloqit Pro Service Providers in your area are being notified.');
    }

    /**
     * Track job status
     */
    public function track(Job $job): View
    {
        return view('marketplace.track', [
            'job' => $job->load(['city', 'service', 'neighborhood', 'provider', 'statuses']),
        ]);
    }
}

