<?php

namespace Cbyte\Unsplash;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

Use Cbyte\Unsplash\UnsplashClient;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadAutoloader(base_path('packages'));
        $this->publishes([
            __DIR__.'/../config/unsplash.php' => config_path('unsplash.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/unsplash.php', 'unsplash'
        );

        $app = $this->app;


        $app->singleton('unsplash', function() use ($app) {
            $config  = $app['config'];
            return new UnsplashClient($config['unsplash']);
        });

    }

    /**
     * Require composer's autoload file the packages.
     *
     * @return void
     **/
    protected function loadAutoloader($path)
    {
        $finder = new Finder;
        $files = new Filesystem;

        $autoloads = $finder->in($path)->files()->name('autoload.php')->depth('<= 3')->followLinks();

        foreach ($autoloads as $file)
        {
            $files->requireOnce($file->getRealPath());
        }
    }
}
