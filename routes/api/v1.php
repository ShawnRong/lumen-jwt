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

    //User
    $api->post('users', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
    ]);
    //User list
    $api->get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
    ]);
    //User Detail
    $api->get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
    ]);

    //require login request
    $api->group(['middleware' => 'api.auth'], function ($api) {
        //Delete Token
        $api->delete('authorizations/current', [
            'as' => 'authorizatoins.delete',
            'uses' => 'AuthController@delete',
        ]);

        //User Detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);

        //User update name
        $api->patch('user', [
            'as' => 'user.update',
            'uses' => 'UserController@patch',
        ]);

        //Update User password
        $api->put('user/password', [
            'as'  => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);
    });

});



