<?php

namespace App\Providers;

use App\Http\Composers\Admin\AdminComposer;
use App\Http\Composers\Client\CartComposer;
use App\Http\Composers\Client\CategoryComposer;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('admin.layouts.master', AdminComposer::class);
        view()->composer('client.layouts.index', CategoryComposer::class);
        view()->composer('client.layouts.index', CartComposer::class);
    }
}
