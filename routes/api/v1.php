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

    //Thread List
    $api->get('threads/{channel}', [
        'as' => 'threads.index',
        'uses' => 'ThreadsController@index',
    ]);
    $api->get('threads', [
        'as' => 'threads.index',
        'uses' => 'ThreadsController@index',
    ]);

    //Thread Detail
    $api->get('threads/{channel}/{thread}', [
        'as' => 'threads.show',
        'uses' => 'ThreadsController@show',
    ]);

    //Channel List
    $api->get('channels', [
        'as' => 'channels.index',
        'uses' => 'ChannelsController@index',
    ]);

    //Reply List
    $api->get('threads/{thread}/replies', [
        'as' => 'replies.index',
        'uses' => 'RepliesController@index',
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

        //Create Thread
        $api->post('threads', [
            'as' => 'threads.store',
            'uses' => 'ThreadsController@store',
        ]);

        //Delete a Thread
        $api->delete('threads/{channel}/{thread}', [
            'as' => 'threads.destroy',
            'uses' => 'ThreadsController@destroy',
        ]);

        //Update Thread
        $api->put('threads/{thread}', [
            'as' => 'threads.update',
            'uses' => 'ThreadsController@update',
        ]);

        //TODO: Update part of thread
//        $api->patch('threads/{thread}', [
//            'as'  => 'threads.patch',
//            'uses' => 'ThreadsController@patch',
//        ]);


        //Create Channel
        //TODO: Add create channel
//        $api->post('channel', [
//            'as' => 'channel.store',
//            'uses' => 'ChannelsController@store',
//        ]);

        //Destroy Channel
        //TODO: Add destroy channel
//        $api->delete('channel', [
//            'as' => 'channel.destroy',
//            'uses' => 'ChannelsController@destroy',
//        ]);

        //Post Reply
        $api->post('threads/{thread}/replies', [
            'as'  => 'replies.store',
            'uses' => 'RepliesController@store',
        ]);

        //Delete Reply
        $api->delete('replies/{reply}', [
            'as' => 'replies.destroy',
            'uses' => 'RepliesController@destroy',
        ]);

        //Update Reply

        $api->patch('replies/{reply}', [
            'as' => 'replies.update',
            'uses' => 'RepliesController@update',
        ]);


    });

});



