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
	
	Route::get('/staff/login', ['uses' => 'LoginController@staff_index', 'as' => 'staff_login']);
	Route::post('/staff/auth/logincheck', ['uses' => 'LoginController@staffLoginCheck', 'as' => 'staffLoginCheck']);
	Route::get('/staff/logout', ['uses' => 'LoginController@staff_logout', 'as' => 'staff_logout']);

	Route::group(['middleware' => 'auth.login'], function () {
		Route::get('/staff/index', ['uses' => 'StaffController@index', 'as' => 'staffIndex']);
		Route::post('/staff/order', ['uses' => 'StaffController@order', 'as' => 'staffOrder']);
	});
	

	Route::group(['prefix' => '/admin', 'middleware' => 'auth.login'], function () {
		
		Route::get('/serviceprovider/list', ['uses' => 'ServiceProviderController@index', 'as' => 'serviceProviderIndex']);
		
		Route::get('/blacklist/list', ['uses' => 'BlackListController@index', 'as' => 'blackListIndex']);
		Route::post('/blacklist/add', ['uses' => 'BlackListController@add', 'as' => 'blackListAdd']);
		Route::post('/blacklist/delete', ['uses' => 'BlackListController@delete', 'as' => 'blackListDelete']);
		
		Route::get('/order', ['uses' => 'OrderController@index', 'as' => 'orderIndex']);
		Route::get('/order/export', ['uses' => 'OrderController@export', 'as' => 'orderExport']);

		Route::get('/calendar/{shop_id}', ['uses' => 'CalendarController@index', 'as' => 'calendarIndex']);
		Route::post('/calendar/{shop_id}/add_order', ['uses' => 'CalendarController@add_order', 'as' => 'calendarAddOrder']);
		Route::post('/calendar/order/{order_id}/update', ['uses' => 'CalendarController@update_order', 'as' => 'calendarUpdateOrder']);
		
		Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboardIndex']);
		
		Route::group(['middleware' => 'auth.level'], function () {
			
			Route::get('/leave', ['uses' => 'LeaveController@index', 'as' => 'leaveIndex']);
			
			Route::get('/log', ['uses' => 'LogController@index', 'as' => 'logIndex']);
			Route::get('/log/export', ['uses' => 'LogController@export', 'as' => 'logExport']);
			
			Route::get('/account', ['uses' => 'AccountController@index', 'as' => 'accountIndex']);
			Route::post('/account/update_password', ['uses' => 'AccountController@update_password', 'as' => 'accountUpdatePassword']);
		
			Route::get('/shift', ['uses' => 'ShiftController@index', 'as' => 'ShiftIndex']);
			Route::post('/shift/update', ['uses' => 'ShiftController@update', 'as' => 'ShiftUpdate']);
		});
	});

	Route::group(['prefix' => '/api', 'middleware' => 'auth.login'], function () {
		Route::get('/serviceprovider/list', ['uses' => 'ServiceProviderController@api_list', 'as' => 'apiServiceProviderList']);
		Route::post('/serviceprovider/add', ['uses' => 'ServiceProviderController@api_add', 'as' => 'apiServiceProviderAdd']);
		Route::post('/serviceprovider/delete', ['uses' => 'ServiceProviderController@api_delete', 'as' => 'apiServiceProviderDelete']);
		Route::get('/serviceprovider/leave', ['uses' => 'ServiceProviderController@api_leave', 'as' => 'apiServiceProviderLeave']);
		Route::post('/serviceprovider/service', ['uses' => 'ServiceProviderController@api_service', 'as' => 'apiServiceProviderService']);
		
		Route::post('/leave/delete', ['uses' => 'LeaveController@api_delete', 'as' => 'apiLeaveDelete']);
		Route::post('/leave/add', ['uses' => 'LeaveController@api_add', 'as' => 'apiLeaveAdd']);
		Route::get('{service_provider_id}/leave/list', ['uses' => 'LeaveController@api_list', 'as' => 'apiLeaveList']);

		Route::post('/account/add', ['uses' => 'AccountController@api_add', 'as' => 'apiAccountAdd']);
		Route::post('/worker_account/add', ['uses' => 'AccountController@api_worker_add', 'as' => 'apiWorkerAccountAdd']);
		Route::post('/account/delete', ['uses' => 'AccountController@api_delete', 'as' => 'apiAccountDelete']);
		Route::post('/account/reset_password', ['uses' => 'AccountController@api_reset_password', 'as' => 'apiAccountResetPassword']);
		
		Route::get('/calendar/{shop_id}', ['uses' => 'CalendarController@api_shop_calendar', 'as' => 'apiShopClander']);
		
		Route::get('/order/schedule', ['uses' => 'CalendarController@api_order_list', 'as' => 'apiOrderList']);
		Route::post('/order/confirm', ['uses' => 'CalendarController@api_order_confirm', 'as' => 'apiOrderConfirm']);
		Route::post('/order/cancel', ['uses' => 'CalendarController@api_order_cancel', 'as' => 'apiOrderCancel']);
	
		Route::get('/shift/list', ['uses' => 'ShiftController@api_list', 'as' => 'apiShiftList']);

		Route::get('/staff/check_status', ['uses' => 'StaffController@api_check_status', 'as' => 'apiCheckStatus']);
		Route::get('/staff/service_provider_list', ['uses' => 'StaffController@api_service_provider_list', 'as' => 'apiServiceProvideList']);
		Route::get('/staff/service_provider_time', ['uses' => 'StaffController@api_service_provider_time', 'as' => 'apiServiceProvideTime']);
	});

	Route::group(['prefix' => '/api'], function () {
		Route::get('/shop_list', ['uses' => 'BookController@api_shop_list', 'as' => 'apiShopList']);
		Route::get('/service_list', ['uses' => 'BookController@api_service_list', 'as' => 'apiServiceList']);
		Route::get('/service_provider_and_room_list', ['uses' => 'BookController@api_service_provider_and_room_list', 'as' => 'apiServiceProviderAndRoomList']);
		Route::get('/time_list', ['uses' => 'BookController@api_time_list', 'as' => 'apiTimeList']);
		Route::get('/order/list', ['uses' => 'BookController@api_order_list', 'as' => 'apiOrderList']);
		Route::post('/order/customer/cancel', ['uses' => 'BookController@api_order_customer_cancel', 'as' => 'apiOrderCustomerCancel']);
		Route::post('/order', ['uses' => 'BookController@api_order', 'as' => 'apiOrder']);
		Route::get('/sendSMS', ['uses' => 'SmsController@send_SMS', 'as' => 'sendSMS']);
		Route::get('/checkCode', ['uses' => 'SmsController@check_Code', 'as' => 'checkCode']);
		
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
