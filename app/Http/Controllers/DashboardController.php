<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Service;

class DashboardController extends Controller
{
	

	public function index(Request $request)
	{
		$view_data = [];
		$shop_list = Shop::all();
		foreach ($shop_list as $key => $shop) {
			
			$date = date("Y-m-d");

			$first = 1;
			$w = date('w',strtotime($date));
			
			$day_start_time = $date.' '.$shop->start_time;
			$month_start = date('Y-m-01', strtotime($date));
			$month_end = date('Y-m-d', strtotime("$month_start +1 month -1 day")); 
			$month_start_time = $month_start.' '.$shop->start_time;
			
			if($shop->end_time <= $shop->start_time){
				$day_end_time = date('Y-m-d H:i:s', strtotime ("+1 day", strtotime($date.' '.$shop->end_time)));
				$month_end_time = date('Y-m-d', strtotime("$month_end +1 days")).' '.$shop->end_time;
			}
			else{
				$day_end_time = $date.' '.$shop->end_time;
				$month_end_time = date('Y-m-d', strtotime("$month_end")).' '.$shop->end_time;
			}

			$info['name'] = $shop->name;
			
			$day_orders = new Order;
			$month_orders = new Order;

			if($request->session()->has('service_provider_id')){
				$service_provider_id = $request->session()->get('service_provider_id');
				$day_orders = $day_orders->whereHas('serviceProviders' ,function ($query) use ($service_provider_id) {
				    $query->where('id', $service_provider_id);
				});
				$month_orders = $month_orders->whereHas('serviceProviders' ,function ($query) use ($service_provider_id) {
				    $query->where('id', $service_provider_id);
				});
			}

			if($request->session()->get('account_level') == 1){
				$day_orders = $day_orders->with('service')->where('status', '!=', 3)->where('status', '!=', 4)->where('status', '!=', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $day_end_time)->where('start_time', '>=', $day_start_time)->get();
				$info['order_day'] = $day_orders->count();		
				$info['revenue_day'] = 0;
				foreach ($day_orders as $key => $order) {
					if($order->status == 5){
						$info['revenue_day'] += $order->service->price * $order->person;
					}
				}

				$month_orders = $month_orders->with('service')->where('status', '!=', 3)->where('status', '!=', 4)->where('status', '!=', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $month_end_time)->where('start_time', '>=', $month_start_time)->get();
				$info['order_month'] = $month_orders->count();
				$info['revenue_month'] = 0;
				foreach ($month_orders as $key => $order) {
					if($order->status == 5){
						$info['revenue_month'] += $order->service->price * $order->person;
					}
				}
			}
			else if($request->session()->get('account_level') == 2){
				$day_orders = $day_orders->with('service')->where('status', '!=', 3)->where('status', '!=', 4)->where('status', '!=', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $day_end_time)->where('start_time', '>=', $day_start_time)->get();
				$info['order_day'] = $day_orders->count();		
				$day_cancel_orders = Order::where('status', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $day_end_time)->where('start_time', '>=', $day_start_time)->get();
				$info['cancel_day'] = $day_cancel_orders->count();

				$month_orders = $month_orders->with('service')->where('status', '!=', 3)->where('status', '!=', 4)->where('status', '!=', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $month_end_time)->where('start_time', '>=', $month_start_time)->get();
				$info['order_month'] = $month_orders->count();
				$month_cancel_orders = Order::where('status', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $month_end_time)->where('start_time', '>=', $month_start_time)->get();
				$info['cancel_month'] = $month_cancel_orders->count();
				
			}
			else if($request->session()->get('account_level') == 3){
				$day_orders = $day_orders->whereHas('serviceProviders' ,function ($query) use ($service_provider_id) {
				    $query->where('id', $service_provider_id);
				})->with('service')->where('status', '!=', 3)->where('status', '!=', 4)->where('status', '!=', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $day_end_time)->where('start_time', '>=', $day_start_time)->get();
				$info['order_day'] = $day_orders->count();		
				$month_orders = $month_orders->whereHas('serviceProviders' ,function ($query) use ($service_provider_id) {
				    $query->where('id', $service_provider_id);
				})->with('service')->where('status', '!=', 3)->where('status', '!=', 4)->where('status', '!=', 6)->where('shop_id', $shop->id)->where('start_time', '<=', $month_end_time)->where('start_time', '>=', $month_start_time)->get();
				$info['order_month'] = $month_orders->count();
			}
			$info['id'] = $shop->id;
			$view_data['shop_list'][] = $info;
		}
		return view('admin.dashboard.index', $view_data);
	}
}