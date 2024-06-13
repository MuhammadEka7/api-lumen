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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['middleware' => 'cors'], function ($router) {
    $router->group(['prefix' => 'lendings'], function() use ($router) {
        $router->get('/', 'LendingController@index');
        $router->post('/store', 'LendingController@store');
        $router->get('/{id}', 'LendingController@show');
    });
    
    $router->group(['prefix' => 'restorations'], function() use ($router) {
        $router->post('/{lending_id}', 'RestorationController@store');
    });
    
    $router->group(['prefix' => 'stuff'], function() use ($router) {
        $router->get('/', 'StuffController@index');
        $router->post('/create', 'StuffController@store');
        $router->get('/trash', 'StuffController@deleted');
    
        $router->get('/show/{id}', 'StuffController@show');
        $router->put('/update/{id}', 'StuffController@update');
        $router->delete('/destroy/{id}', 'StuffController@destroy');
        $router->put('/restore/{id}', 'StuffController@restore');
        $router->put('/restore', 'StuffController@restoreAll');
        $router->delete('/permanent/{id}', 'StuffController@permanentDelete');
        $router->delete('/permanent', 'StuffController@permanentDeleteAll');
    });
    
    $router->group(['prefix' => 'user'], function() use ($router) {
    
        $router->get('/', 'UserController@index');
        $router->post('/create', 'UserController@store');
        $router->get('/trash', 'UserController@deleted');
    
        $router->get('/show/{id}', 'UserController@show');
        $router->put('/update/{id}', 'UserController@update');
        $router->delete('/destroy/{id}', 'UserController@destroy');
        $router->put('/restore/{id}', 'UserController@restore');
        $router->put('/restore', 'UserController@restoreAll');
        $router->delete('/permanent/{id}', 'UserController@permanentDelete');
        $router->delete('/permanent', 'UserController@permanentDeleteAll');
    });
    
    $router->group(['prefix' => 'inbound'], function() use ($router) {
        $router->get('/', 'InboundStuffController@index');
        $router->post('/create', 'InboundStuffController@store');
        $router->get('/trash', 'InboundStuffController@deleted');
        
        $router->delete('/destroy/{id}', 'InboundStuffController@destroy');
        $router->delete('/permanent', 'InboundStuffController@permanentDeleteAll');
        $router->delete('/permanent/{id}', 'InboundStuffController@permanentDelete');
        
        $router->put('/restore/{id}', 'InboundStuffController@restore');
        $router->put('/restore', 'InboundStuffController@restoreAll');
    
        $router->get('/{id}', 'InboundStuffController@show');
        $router->patch('/{id}', 'InboundStuffController@update');
        $router->delete('/{id}', 'InboundStuffController@destroy');
    });
    
    
    $router->get('/StuffStock', 'StuffStockController@index');
    $router->post('/StuffStock/create', 'StuffStockController@store');
    $router->get('/StuffStock/trash', 'StuffStockController@deleted');
    $router->delete('/StuffStock/permanent', 'StuffStockController@permanentDeleteAll');
    $router->delete('/StuffStock/permanent/{id}', 'StuffStockController@permanentDelete');
    
    $router->put('/StuffStock/restore/{id}', 'StuffStockController@restore');
    $router->put('/StuffStock/restore', 'StuffStockController@restoreAll');
    
    $router->get('/StuffStock/{id}', 'StuffStockController@show');
    $router->patch('/StuffStock/{id}', 'StuffStockController@update');
    $router->delete('/StuffStock/{id}', 'StuffStockController@destroy');

        $router->post('/login', 'AuthController@login');
        $router->get('/profile', 'AuthController@me');
        $router->get('/logout', 'AuthController@logout');
});