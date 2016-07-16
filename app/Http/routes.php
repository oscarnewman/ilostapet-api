ost
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers\API\v1', 'middleware' => 'cors'], function ($api) {
    $api->resource('/posts', 'PostController');
    $api->resource('/pets', 'PetController');
    $api->resource('/users', 'UserController', ['only' => ['store', 'show', 'update', 'destroy']]);

    $api->post('/sessions', 'SessionController@store');
    $api->delete('/sessions', 'SessionController@destroy');
    $api->put('/sessions', 'SessionController@update');
    $api->patch('/sessions', 'SessionController@update');
    $api->get('/sessions/current', 'SessionController@current');

    $api->post('/images', 'ImageController@store');
    $api->delete('/images/{id}', 'ImageController@destroy');

    $api->get('/search/{lat}/{lng}', 'SearchController@byLocation');
});
