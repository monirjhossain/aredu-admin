<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Providers\AppServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Message;

class ViewServiceProvider extends AppServiceProvider
{
    public function boot()
    {
        //
    }
    public function register()
    {
        //
    }
}
