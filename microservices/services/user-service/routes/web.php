<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Health check
$router->get('/', function () use ($router) {
    return response()->json([
        'service' => 'User Service',
        'version' => $router->app->version(),
        'status' => 'healthy'
    ]);
});

$router->get('/health', function () use ($router) {
    return response()->json([
        'service' => 'User Service',
        'status' => 'healthy',
        'timestamp' => now()
    ]);
});

// Auth routes
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', ['middleware' => 'jwt.auth', 'uses' => 'AuthController@logout']);
    $router->post('refresh', ['middleware' => 'jwt.auth', 'uses' => 'AuthController@refresh']);
    $router->get('me', ['middleware' => 'jwt.auth', 'uses' => 'AuthController@me']);
});

// User routes
$router->group(['prefix' => 'users', 'middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/', 'UserController@index');
    $router->get('/{id}', 'UserController@show');
    $router->put('/{id}', 'UserController@update');
    $router->delete('/{id}', 'UserController@destroy');
    $router->get('/profile/me', 'UserController@profile');
    $router->put('/profile/me', 'UserController@updateProfile');
});
