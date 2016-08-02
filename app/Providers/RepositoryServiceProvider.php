<?php

namespace CodeProject\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\CodeProject\Repositories\ProjectNoteRepository::class, \CodeProject\Repositories\ProjectNoteRepositoryEloquent::class);
        //:end-bindings:
    }
}
