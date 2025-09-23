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
        'service' => 'Product Service',
        'version' => $router->app->version(),
        'status' => 'healthy'
    ]);
});

$router->get('/health', function () use ($router) {
    return response()->json([
        'service' => 'Product Service',
        'status' => 'healthy',
        'timestamp' => now()
    ]);
});

// Product routes
$router->group(['prefix' => 'products'], function () use ($router) {
    $router->get('/', 'ProductController@index');
    $router->get('/featured', 'ProductController@featured');
    $router->get('/{id}', 'ProductController@show');
    $router->post('/', 'ProductController@store');
    $router->put('/{id}', 'ProductController@update');
    $router->delete('/{id}', 'ProductController@destroy');
});

// Category routes
$router->group(['prefix' => 'categories'], function () use ($router) {
    $router->get('/', 'CategoryController@index');
    $router->get('/{id}', 'CategoryController@show');
    $router->post('/', 'CategoryController@store');
    $router->put('/{id}', 'CategoryController@update');
    $router->delete('/{id}', 'CategoryController@destroy');
});
