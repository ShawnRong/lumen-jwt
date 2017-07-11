<?php

namespace App\Custom;

use Illuminate\Http\Request;
use Dingo\Api\Exception\UnknownVersionException;
use Dingo\Api\Routing\Adapter\Lumen as BaseDingoAdapter;

class DingoAdapter extends BaseDingoAdapter
{
    /**
     * Dispatch a request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $version
     *
     * @return mixed
     */
    public function dispatch(Request $request, $version)
    {
        if (! isset($this->routes[$version])) {
            throw new UnknownVersionException;
        }

        $this->removeMiddlewareFromApp();

        $routes = $this->routes[$version];

        // This is what we extended the class for, to use the dispatcher created
        // by LumenRouteBinding instead of creating a new one.
        $this->app['dispatcher']->setRoutesResolver(function () use ($routes) {
            return $routes->getData();
        });

        $this->normalizeRequestUri($request);

        return $this->app->dispatch($request);
    }
}
