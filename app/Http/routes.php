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

$resource = function ($name, $controller) use ($app) {
    $app->get($name, ['as' => "$name.index", 'uses' => "$controller@index"]);
    $app->post($name, ['as' => "$name.store", 'uses' => "$controller@store"]);
    $app->get("$name/{id}", ['as' => "$name.show", 'uses' => "$controller@show"]);
    $app->put("$name/{id}", ['as' => "$name.update", 'uses' => "$controller@update"]);
    $app->delete("$name/{id}", ['as' => "$name.delete", 'uses' => "$controller@destroy"]);
};

/**
 * @SWG\Swagger(
 *     host="{{ $host }}",
 *     basePath="/api/v1",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     @SWG\Info(
 *         title="Leadgen API",
 *         version="0.1-beta",
 *     )
 * )
 */
$app->group(['prefix' => 'api/v1', 'namespace' => 'App\Http\Controllers'], function () use ($app, $resource) {
    $app->get('/', ['as' => 'root', 'uses' => 'DocsController@index']);

    // Interaction type routes
    $resource('interactionType', 'InteractionTypeController');

    // Interaction routes
    $app->post('interaction', ['as' => 'interaction.store', 'uses' => 'InteractionController@store']);

    // Customer routes
    $app->get('customer', ['as' => 'customer.index', 'uses' => 'CustomerController@index']);
    $app->get('customer/{id}', ['as' => 'customer.show', 'uses' => 'CustomerController@show']);
});

$app->get('/', ['as' => 'dashboard.home', 'uses' => 'DashboardController@home']);
