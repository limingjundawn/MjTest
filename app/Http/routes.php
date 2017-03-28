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
        //注册
        $api->post('register','UserController@register');
        //登录
        $api->post('login','UserController@login');

        $api->group(['middleware'=>'jwt.auth'],function($api){
            $api->post('test','UserController@test');

            //退出登录
            $api->post('logout','UserController@logout');
        });


        //注册
        $api->post('admin_register','AdminController@register');
        //登录
        $api->post('admin_login','AdminController@login');


        //刷新token
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