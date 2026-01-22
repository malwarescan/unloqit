<?php

namespace App\Providers;

use App\View\Composers\LayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share footer data with layout
        View::composer('layouts.app', LayoutComposer::class);
    }
}

