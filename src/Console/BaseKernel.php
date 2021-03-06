<?php namespace NewUp\Console;

use Illuminate\Foundation\Console\Kernel as LaravelKernel;
use NewUp\Console\Application as NewUpApplication;

abstract class BaseKernel extends LaravelKernel
{

    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        'Illuminate\Foundation\Bootstrap\DetectEnvironment',
        'Illuminate\Foundation\Bootstrap\LoadConfiguration',
        'NewUp\Foundation\Bootstrap\ConfigureLogging',
        'Illuminate\Foundation\Bootstrap\HandleExceptions',
        'Illuminate\Foundation\Bootstrap\SetRequestForConsole',
        'Illuminate\Foundation\Bootstrap\RegisterProviders',
        'Illuminate\Foundation\Bootstrap\BootProviders',
    ];

    /**
     * Get the Artisan application instance.
     *
     * @return \Illuminate\Console\Application
     */
    protected function getArtisan()
    {
        if (is_null($this->artisan)) {
            return $this->artisan = (new NewUpApplication($this->app, $this->events))
                ->resolveCommands($this->commands);
        }

        return $this->artisan;
    }

}