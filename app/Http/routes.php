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
		Route::get('/calender/{shop_id}', ['uses' => 'CalenderController@index', 'as' => 'calenderIndex']);
		Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboardIndex']);
		Route::get('/order', ['uses' => 'OrderController@index', 'as' => 'orderIndex']);
		Route::get('/log', ['uses' => 'LogController@index', 'as' => 'logIndex']);
		Route::get('/account', ['uses' => 'AccountController@index', 'as' => 'accountIndex']);
		Route::post('/account/update_password', ['uses' => 'AccountController@update_password', 'as' => 'accountUpdatePassword']);
	});

	Route::group(['prefix' => '/api', 'middleware' => 'auth.login'], function () {
		Route::get('/serviceprovider/list', ['uses' => 'ServiceProviderController@api_list', 'as' => 'apiServiceProviderList']);
		Route::post('/serviceprovider/add', ['uses' => 'ServiceProviderController@api_add', 'as' => 'apiServiceProviderAdd']);
		Route::post('/serviceprovider/delete', ['uses' => 'ServiceProviderController@api_delete', 'as' => 'apiServiceProviderDelete']);
		Route::post('/leave/delete', ['uses' => 'LeaveController@api_delete', 'as' => 'apiLeaveDelete']);
		Route::post('/account/add', ['uses' => 'AccountController@api_add', 'as' => 'apiAccountAdd']);
		Route::post('/account/delete', ['uses' => 'AccountController@api_delete', 'as' => 'apiAccountDelete']);
		Route::post('/account/reset_password', ['uses' => 'AccountController@api_reset_password', 'as' => 'apiAccountResetPassword']);
		Route::get('/calender/{shop_id}', ['uses' => 'CalenderController@api_shop_calender', 'as' => 'apiShopClander']);
	});

	Route::group(['prefix' => '/api'], function () {
		Route::get('/shop_list', ['uses' => 'BookController@api_shop_list', 'as' => 'apiShopList']);
		Route::get('/service_list', ['uses' => 'BookController@api_service_list', 'as' => 'apiServiceList']);
		Route::get('/time_list', ['uses' => 'BookController@api_time_list', 'as' => 'apiTimeList']);
		Route::post('/order', ['uses' => 'BookController@api_order', 'as' => 'apiOrder']);
	});
	//admin redirect
	Route::get('/admin/{path?}', ['where' => ['path' => '.*'], function(){
		return redirect('/admin/login');
	}]);
	//api redirect
	Route::get('/api/{path?}', ['where' => ['path' => '.*'], function(){
		return redirect('/');
	}]);
	//frontend react
	Route::get('/{path?}', ['uses' => 'BookController@index', 'as' => 'book', 'where' => ['path' => '.*']]);
});
