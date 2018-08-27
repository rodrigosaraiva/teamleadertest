<?php

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

$router->group(['prefix' => 'customer'], function () use ($router) {
    $router->get('getcustomer/{id}', ['uses' => 'CustomerController@getCustomer']);
});

$router->group(['prefix' => 'product'], function () use ($router) {
    $router->get('getproduct/{id}', ['uses' => 'ProductController@getProduct']);
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->post('getdiscount', ['uses' => 'DiscountController@getDiscount']);
});
