<?php
/*
 * Api routes
 * v1
 */

$api = app('Dingo\Api\Routing\Router');

//api version : v1
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
], function ($api) {
    //Auth
    //Login
    //Create a token
    $api->post('authorizations', [
        'as' => 'authorizations.store',
        'uses' => 'AuthController@store',
    ]);

    //Refresh Token
    $api->put('authorizations/current', [
        'as' => 'authorizatons.update',
        'uses' => 'AuthController@update',
    ]);

    //require login request
    $api->group(['middleware' => 'api.auth'], function ($api) {
        //Delete Token
        $api->delete('authorizations/current', [
            'as' => 'authorizatoins.delete',
            'uses' => 'AuthController@delete',
        ]);
    });

});



