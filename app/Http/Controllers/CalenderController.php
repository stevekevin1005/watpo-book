<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlackList;
use Hash, Exception;
use App\Models\ServiceProvider;
use App\Models\Shop;
use App\Models\Order;

class CalenderController extends Controller
{
	

	public function index(Request $request, $shop_id)
	{
		$blackList = BlackList::paginate(10);
		$view_data['blackList'] = $blackList;
		$view_data['shop_id'] = $shop_id;

		return view('admin.calender.index', $view_data);
	}

	public function api_shop_calender(Request $request, $shop_id)
	{	
		try{
			$date = date('Y-m-d');
			$result['today'] = $date;

			$shop = Shop::where('id', $shop_id)->first();
			$service_providers = ServiceProvider::where('shop_id', $shop_id)->get();

			$result['start_time'] = $shop->start_time;


			$start_time = strtotime($date.' '.$shop->start_time);

			$result['service_providers'] = [];
			foreach ($service_providers as $key => $service_provider) {
				$result['service_providers'][] = ['id'=> $service_provider->id, 'name'=>$service_provider->name];
			}

			$orders = Order::where('start_time', '>=', date("Y/m/d H:i:s", $start_time))->get();

			$result['orders'] = [];
			foreach ($orders as $key => $order) {
				$result['orders'][] = ['id'=>$order->id, 'resourceId'=>$order->service_provider_id, 'start'=>$order->start_time, 'end'=>$order->end_time, 'title'=>$order->name, 'phone'=>$order->phone, 'person'=>$order->person];
			}

			return response()->json($result, 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}
}