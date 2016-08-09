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

$app->group(['prefix' => 'api/v1', 'namespace' => 'App\Http\Controllers'], function () use ($app, $resource) {
    $resource('interactionType', 'InteractionTypeController');
    $app->post('interaction', ['as' => 'interaction.store', 'uses' => 'InteractionController@store']);
});

$app->get('/', function () use ($app) {
    return $app->version();
});
