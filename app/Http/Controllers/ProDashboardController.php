<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Provider;
use App\Services\DispatchService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProDashboardController extends Controller
{
    public function __construct(
        private DispatchService $dispatchService
    ) {
        $this->middleware('auth:provider')->except([]);
    }

    /**
     * Pro dashboard
     */
    public function dashboard(Request $request): View
    {
        $provider = $request->user('provider');
        
        $activeJobs = $provider->activeJobs()->with(['city', 'service', 'customer'])->get();
        $availableJobs = Job::where('status', 'broadcast')
            ->whereHas('city', function ($query) use ($provider) {
                $query->whereIn('id', $provider->service_areas ?? []);
            })
            ->with(['city', 'service', 'neighborhood'])
            ->orderBy('requested_at', 'desc')
            ->limit(20)
            ->get();

        return view('marketplace.pro.dashboard', [
            'provider' => $provider,
            'activeJobs' => $activeJobs,
            'availableJobs' => $availableJobs,
        ]);
    }

    /**
     * Available jobs feed
     */
    public function jobs(Request $request): View
    {
        $provider = $request->user('provider');

        $jobs = Job::where('status', 'broadcast')
            ->whereHas('city', function ($query) use ($provider) {
                $query->whereIn('id', $provider->service_areas ?? []);
            })
            ->with(['city', 'service', 'neighborhood'])
            ->orderBy('requested_at', 'desc')
            ->paginate(20);

        return view('marketplace.pro.jobs', [
            'provider' => $provider,
            'jobs' => $jobs,
        ]);
    }

    /**
     * Claim a job
     */
    public function claimJob(Request $request, Job $job): RedirectResponse
    {
        $provider = $request->user('provider');

        if ($this->dispatchService->claimJob($job, $provider)) {
            return redirect()->route('pro.jobs.show', ['job' => $job->id])
                ->with('success', 'Job claimed successfully!');
        }

        return back()->with('error', 'Unable to claim job. It may have been claimed by another pro.');
    }

    /**
     * View job details
     */
    public function showJob(Request $request, Job $job): View
    {
        $provider = $request->user('provider');
        
        return view('marketplace.pro.job', [
            'provider' => $provider,
            'job' => $job->load(['city', 'service', 'neighborhood', 'customer', 'statuses']),
        ]);
    }

    /**
     * Update job status
     */
    public function updateJobStatus(Request $request, Job $job): RedirectResponse
    {
        $provider = $request->user('provider');

        if ($job->provider_id !== $provider->id) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:en_route,arrived,in_progress,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        $this->dispatchService->updateJobStatus(
            $job,
            $validated['status'],
            $validated['notes'] ?? null,
            $provider
        );

        return back()->with('success', 'Job status updated');
    }

    /**
     * Earnings dashboard
     */
    public function earnings(Request $request): View
    {
        $provider = $request->user('provider');

        $earnings = $provider->earnings()
            ->with('job')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalEarnings = $provider->earnings()->where('status', 'paid')->sum('payout_amount');
        $pendingEarnings = $provider->earnings()->where('status', 'pending')->sum('payout_amount');

        return view('marketplace.pro.earnings', [
            'provider' => $provider,
            'earnings' => $earnings,
            'totalEarnings' => $totalEarnings,
            'pendingEarnings' => $pendingEarnings,
        ]);
    }

    /**
     * Toggle online status
     */
    public function toggleOnline(Request $request): RedirectResponse
    {
        $provider = $request->user('provider');
        
        $availability = $provider->availability()->firstOrCreate([
            'provider_id' => $provider->id,
        ]);

        $availability->update([
            'is_online' => !$availability->is_online,
            'last_seen_at' => now(),
        ]);

        return back()->with('success', $availability->is_online ? 'You are now online' : 'You are now offline');
    }
}

