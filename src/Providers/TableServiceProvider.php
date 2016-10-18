<?php

namespace Codeator\Table\Providers;

use Illuminate\Support\ServiceProvider;
use Codeator\Table\Table;

class TableServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'table');
        $this->publishes([
            dirname(__DIR__) . '/resources/views' => resource_path('views/vendor/table'),
            dirname(__DIR__) . '/config/table.php' => config_path('table.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/table.php', 'table'
        );

        $this->app->bind('table', function ($app) {
            $table = new Table();

            /** @var \App $app */
            if ($app->runningInConsole()) {
                /** @var \Request $request */
                $request = $app['request'];
                if (!$request->hasSession()) {
                    $request->setSession($app['session.store']);
                }
            }
            
            return $table;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['table', 'Codeator\Table'];
    }
}
