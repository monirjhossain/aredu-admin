<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Providers\AppServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\Branch;


class ViewServiceProvider extends AppServiceProvider
{
    public function boot()
    {
        view()->composer('layouts.base.aside', function ($view) {
        $branches = Branch::all();
            $view->with('branches', $branches);
        });
    }
    public function register()
    {
        //
    }
}
