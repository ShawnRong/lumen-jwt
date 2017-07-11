<?php

//Get current User
if (! function_exists('auth_user')) {
}

//Get route with dingo route alias
if (! function_exists('dingo_route')) {
    function dingo_route($version, $name, $params=[])
    {
        return app('Dingo\Api\Routing\UrlGenerator')
            ->version($version)
            ->route($name, $params);
    }
}
