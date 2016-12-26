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

$resource = function ($name, $controller, $namePrefix = '', $api = true) use ($app) {
    if (! $api) {
        $app->get("$name/create", ['as' => "$namePrefix$name.create", 'uses' => "$controller@create"]);
        $app->get("$name/{id}/edit", ['as' => "$namePrefix$name.edit", 'uses' => "$controller@edit"]);
    }

    $app->get($name, ['as' => "$namePrefix$name.index", 'uses' => "$controller@index"]);
    $app->post($name, ['as' => "$namePrefix$name.store", 'uses' => "$controller@store"]);
    $app->get("$name/{id}", ['as' => "$namePrefix$name.show", 'uses' => "$controller@show"]);
    $app->put("$name/{id}", ['as' => "$namePrefix$name.update", 'uses' => "$controller@update"]);
    $app->delete("$name/{id}", ['as' => "$namePrefix$name.delete", 'uses' => "$controller@destroy"]);
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
    $app->get('interaction/pulse', ['as' => 'interaction.pulse', 'uses' => 'InteractionController@pulse']);

    // Segment routes
    $resource('segment', 'SegmentController');

    // Customer routes
    $app->get('customer', ['as' => 'customer.index', 'uses' => 'CustomerController@index']);
    $app->get('customer/{id}', ['as' => 'customer.show', 'uses' => 'CustomerController@show']);
    $app->get('customer/query/{segmentQuery}', ['as' => 'customer.query', 'uses' => 'CustomerController@query']);
});

// End-user routes

// Dashboard
$app->group(['middleware' => 'basicAuth', 'namespace' => 'App\Http\Controllers'], function () use ($app, $resource) {
    $app->get('/', ['as' => 'front.dashboard.home', 'uses' => 'Front\DashboardController@home']);

    // Customers routes
    $app->get('customer', ['as' => 'front.customer.index', 'uses' => 'Front\CustomerController@index']);
    $app->get('customer/{id}', ['as' => 'front.customer.show', 'uses' => 'Front\CustomerController@show']);

    // Segment routes
    $app->get('segment/{id}/exportCsv', ['as' => 'front.segment.exportCsv', 'uses' => 'Front\SegmentController@exportCsv']);
    $resource('segment', 'Front\SegmentController', 'front.', false);

    // Integration routes
    $app->get('integration', ['as' => 'front.integration.index', 'uses' => 'Front\IntegrationController@index']);
});
