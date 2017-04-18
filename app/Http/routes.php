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

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace'=>'App\Http\Api\V1\Controllers'],function($api){
        // register
        $api->post('register','UserController@register');
        // login
        $api->post('login','UserController@login');

        $api->group(['middleware'=>'jwt.auth'],function($api){
            $api->post('test','UserController@test');

            //logout
            $api->post('logout','UserController@logout');
        });


        //another register
        $api->post('admin_register','AdminController@register');
        //another login
        $api->post('admin_login','AdminController@login');


        // refresh token
        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);


    });


});