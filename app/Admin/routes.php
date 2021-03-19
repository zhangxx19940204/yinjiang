<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    //后台的制作
    $router->resource('links', Home\LinksController::class);
    $router->resource('products', Home\ProductsController::class);
    $router->resource('stores', Home\StoresController::class);
    $router->resource('seo', Home\SeoController::class);
    $router->resource('messages', Home\MessagesController::class);
    $router->resource('news', Home\NewsController::class);

});
