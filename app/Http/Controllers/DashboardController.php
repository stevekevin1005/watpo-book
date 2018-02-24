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
			$week_start = date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days'));
			$week_start_time = $week_start.' '.$shop->start_time;
			
			if($shop->end_time <= $shop->start_time){
				$day_end_time = date('Y-m-d H:i:s', strtotime ("+1 day", strtotime($date.' '.$shop->end_time)));
				$week_end_time = date('Y-m-d', strtotime("$week_start +7 days")).' '.$shop->end_time;
			}
			else{
				$day_end_time = $date.' '.$shop->end_time;
				$week_end_time = date('Y-m-d', strtotime("$week_start +6 days")).' '.$shop->end_time;
			}

			$info['name'] = $shop->name;
			
			$day_orders = new Order;
			$week_orders = new Order;

			if($request->session()->has('service_provider_id')){
				$service_provider_id = $request->session()->get('service_provider_id');
				$day_orders = $day_orders->whereHas('serviceProviders' ,function ($query) use ($service_provider_id) {
				    $query->where('id', $service_provider_id);
				});
				$week_orders = $week_orders->whereHas('serviceProviders' ,function ($query) use ($service_provider_id) {
				    $query->where('id', $service_provider_id);
				});
			}


			$day_orders = $day_orders->with('service')->where('shop_id', $shop->id)->where('start_time', '<=', $day_end_time)->where('start_time', '>=', $day_start_time)->get();
			$info['order_day'] = $day_orders->count();		
			$info['revenue_day'] = 0;
			foreach ($day_orders as $key => $order) {
				if($order->status == 5){
					$info['revenue_day'] += $order->service->price * $order->person;
				}
			}

			$week_orders = $week_orders->with('service')->where('shop_id', $shop->id)->where('start_time', '<=', $week_end_time)->where('start_time', '>=', $week_start_time)->get();
			$info['order_week'] = $week_orders->count();
			$info['revenue_week'] = 0;
			foreach ($week_orders as $key => $order) {
				if($order->status == 5){
					$info['revenue_week'] += $order->service->price * $order->person;
				}
			}
	
			$view_data['shop_list'][] = $info;
		}
		return view('admin.dashboard.index', $view_data);
	}



}