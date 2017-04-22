<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Http\Composers\GlobalComposer;
use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvider.
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Global
         */
        View::composer(
            // This class binds the $logged_in_user variable to every view
            '*', GlobalComposer::class
        );

        /*
         * Frontend
         */
        View::composer(['frontend.includes.right','frontend.includes.places','frontend.includes.popups.post-add'], 'App\Http\Composers\Frontend\CategoriesComposer');

        View::composer(['frontend.includes.left'], 'App\Http\Composers\Frontend\CountComposer');

        View::composer(['frontend.includes.nav'], 'App\Http\Composers\Frontend\HeaderComposer');

        /*
         * Backend
         */
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
