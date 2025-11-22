<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Provider;
use App\Models\ProviderAvailability;
use App\Models\Service;
use App\Services\TitleMetaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProviderAuthController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta
    ) {}

    /**
     * Show registration form
     */
    public function showRegisterForm(): View
    {
        $titleMeta = $this->titleMeta->forProRegister();

        return view('marketplace.pro.register', [
            'cities' => City::all(),
            'services' => Service::all(),
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }

    /**
     * Handle registration
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:providers',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'service_areas' => 'required|array|min:1',
            'service_areas.*' => 'exists:cities,id',
            'service_types' => 'required|array|min:1',
            'service_types.*' => 'exists:services,id',
        ]);

        // Create provider
        $provider = Provider::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'license_number' => $validated['license_number'],
            'bio' => $validated['bio'] ?? null,
            'service_areas' => $validated['service_areas'],
            'service_types' => $validated['service_types'],
            'is_verified' => false, // Requires admin verification
            'is_active' => false, // Inactive until verified
        ]);

        // Create availability record
        ProviderAvailability::create([
            'provider_id' => $provider->id,
            'is_online' => false,
        ]);

        // Attach city-service combinations
        foreach ($validated['service_areas'] as $cityId) {
            foreach ($validated['service_types'] as $serviceId) {
                $provider->cityServices()->attach($cityId, [
                    'service_id' => $serviceId,
                    'is_available' => true,
                ]);
            }
        }

        // Log in the provider
        Auth::guard('provider')->login($provider);

        return redirect()->route('pro.dashboard')
            ->with('success', 'Registration successful! Your account is pending verification. You will be notified once verified.');
    }

    /**
     * Show login form
     */
    public function showLoginForm(): View
    {
        $titleMeta = $this->titleMeta->forProLogin();

        return view('marketplace.pro.login', [
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }

    /**
     * Handle login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('provider')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('pro.dashboard'))
                ->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('provider')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pro.login')
            ->with('success', 'You have been logged out.');
    }
}

