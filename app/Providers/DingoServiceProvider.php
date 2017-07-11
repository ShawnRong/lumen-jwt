<?php


namespace App\Providers;

use App\Custom\DingoAdapter;
use FastRoute\RouteParser\Std as StdRouteParser;
use FastRoute\DataGenerator\GroupCountBased as GcbDataGenerator;
use Dingo\Api\Provider\LumenServiceProvider as BaseDingoServiceProvider;

class DingoServiceProvider extends BaseDingoServiceProvider
{
    public function register()
    {
        parent::register();

        // Replace dingo adapter with an extended one to use the dispatcher from LumenRouteBinding
        $this->app->singleton('api.router.adapter', function ($app) {
            return new DingoAdapter($app, new StdRouteParser, new GcbDataGenerator, '');
        });
    }
}