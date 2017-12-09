<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
  
  Route::get('/', ['uses' => 'BookController@index', 'as' => 'book']);

	Route::get('/admin/login', ['uses' => 'LoginController@index', 'as' => 'login']);
	Route::get('/admin/logout', ['uses' => 'LoginController@logout', 'as' => 'logout']);
	Route::post('/auth/logincheck', ['uses' => 'LoginController@loginCheck', 'as' => 'loginCheck']);

	Route::group(['prefix' => '/admin', 'middleware' => 'auth.login'], function () {

		Route::get('/serviceprovider/list', ['uses' => 'ServiceProviderController@index', 'as' => 'serviceProviderIndex']);

		Route::get('/blacklist/list', ['uses' => 'BlackListController@index', 'as' => 'blackListIndex']);
		Route::post('/blacklist/add', ['uses' => 'BlackListController@add', 'as' => 'blackListAdd']);
		Route::post('/blacklist/delete', ['uses' => 'BlackListController@delete', 'as' => 'blackListDelete']);

		Route::get('/leave', ['uses' => 'LeaveController@index', 'as' => 'leaveIndex']);
		Route::post('/leave/add', ['uses' => 'LeaveController@add', 'as' => 'leaveAdd']);

		Route::get('/calender/{id}', ['uses' => 'CalenderController@index', 'as' => 'CalenderIndex']);

		Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'DashboardIndex']);

		Route::get('/order', ['uses' => 'OrderController@index', 'as' => 'OrderIndex']);

		Route::get('/log', ['uses' => 'LogController@index', 'as' => 'LogIndex']);

		Route::get('/account', ['uses' => 'AccountController@index', 'as' => 'AccountIndex']);
	});

	Route::group(['prefix' => '/api'], function () {

		Route::get('/serviceprovider/list', ['uses' => 'ServiceProviderController@api_list', 'as' => 'apiServiceProviderList']);
		Route::post('/serviceprovider/add', ['uses' => 'ServiceProviderController@api_add', 'as' => 'apiServiceProviderAdd']);
		Route::post('/serviceprovider/delete', ['uses' => 'ServiceProviderController@api_delete', 'as' => 'apiServiceProviderDelete']);

		Route::post('/leave/delete', ['uses' => 'LeaveController@api_delete', 'as' => 'apiLeaveDelete']);
	});
});
