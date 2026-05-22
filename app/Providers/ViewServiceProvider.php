<?php

namespace App\Providers;

use App\Http\View\Composers\StudentStatusDropdownComposer;
use App\Models\Branch;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.base.aside', function ($view) {
            $view->with('branches', Branch::all());
        });

        View::composer(
            StudentStatusDropdownComposer::views(),
            StudentStatusDropdownComposer::class
        );
    }

    public function register(): void
    {
        //
    }
}
