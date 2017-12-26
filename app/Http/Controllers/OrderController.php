<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\ServiceProvider;

class OrderController extends Controller
{
	
	public function index(Request $request)
	{

		$order_list = Order::with('shop')->with('service')->with('serviceProviders');
		$service_provider_list = ServiceProvider::with('shop')->get();

		if($request->service_provider){
			$service_provider_id = $request->service_provider;
			$order_list = $order_list->whereHas('serviceProviders', function($query) use ($service_provider_id ){
				$query->where('id', $service_provider_id);
			});
		}

		if($request->name){
			$order_list = $order_list->where('name', $request->name);
		}

		if($request->phone){
			$order_list = $order_list->where('phone', $request->phone);
		}

		if($request->start_time){
			$order_list = $order_list->where('created_at', ">=", $request->start_time);
		}

		if($request->end_time){
			$order_list = $order_list->where('created_at', "<=", $request->end_time);
		}

		if($request->service){
			$order_list = $order_list->where('service_id', $request->service);
		}

		if($request->shop){
			$order_list = $order_list->where('shop_id', $request->shop);
		}

		foreach ($service_provider_list as $key => $service_provider) {
			$service_provider_name = $service_provider->name."(".$service_provider->shop->name.")";
			$view_data['service_provider_list'][] = ["id" => $service_provider->id, "name" => $service_provider_name];
		}

		$view_data['request'] = $request;
		$view_data['order_list'] = $order_list->paginate(15);
		$view_data['service_list'] = Service::all();
		$view_data['shop_list'] = Shop::all();

		return view('admin.order.index', $view_data);
	}



}