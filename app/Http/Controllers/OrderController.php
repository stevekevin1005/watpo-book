<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception, Excel;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\Room;
use App\Models\ServiceProvider;
use DB;

class OrderController extends Controller
{
	
	public function index(Request $request)
	{

		$order_list = Order::with('shop')->with('service')->with('serviceProviders')->with('room')->with('account');
		$service_provider_list = ServiceProvider::with('shop')->where('activate', true)->get();

		if($request->service_provider){
			$service_provider_id = $request->service_provider;
			$order_list = $order_list->whereHas('serviceProviders', function($query) use ($service_provider_id ){
				$query->where('id', $service_provider_id);
			});
		}

		if($request->id){
			$order_list = $order_list->where('id', $request->id);
		}

		if($request->name){
			$order_list = $order_list->where('name', $request->name);
		}

		if($request->phone){
			$order_list = $order_list->where('phone', $request->phone);
		}

		if($request->start_time){
			$order_list = $order_list->whereDate('start_time', ">=", $request->start_time);
		}

		if($request->end_time){
			$order_list = $order_list->whereDate('end_time', "<=", $request->end_time);
		}

		if($request->service){
			$order_list = $order_list->where('service_id', $request->service);
		}

		if($request->shop){
			$order_list = $order_list->where('shop_id', $request->shop);
		}

		if($request->room){
			$order_list = $order_list->where('room_id', $request->room);
		}

		if($request->count && $request->count > 0){
			$order_list = $order_list->select('name', 'phone', DB::raw('count(*) as total'))
				->groupBy('phone')
				->havingRaw('count(*) >= ?', [$request->count])
				->where('status', 5);
		}
		if($request->count_end && $request->count_end > 0){
			$order_list = $order_list->havingRaw('count(*) <= ?', [$request->count_end]);
		}
		foreach ($service_provider_list as $key => $service_provider) {
			$service_provider_name = $service_provider->name."(".$service_provider->shop->name.")";
			$view_data['service_provider_list'][] = ["id" => $service_provider->id, "name" => $service_provider_name];
		}

		$view_data['request'] = $request;
		$view_data['order_list'] = $order_list->orderBy('end_time', 'desc')->paginate(15);
		$view_data['service_list'] = Service::all();
		$view_data['shop_list'] = Shop::all();
		$view_data['room_list'] = Room::with('shop')->get();
		return view('admin.order.index', $view_data);
	}

	public function export(Request $request)
	{
		$order_list = Order::with('shop')->with('service')->with('serviceProviders')->with('room')->with('account');
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
			$order_list = $order_list->whereDate('created_at', ">=", $request->start_time);
		}

		if($request->end_time){
			$order_list = $order_list->whereDate('created_at', "<=", $request->end_time);
		}

		if($request->service){
			$order_list = $order_list->where('service_id', $request->service);
		}

		if($request->shop){
			$order_list = $order_list->where('shop_id', $request->shop);
		}

		if($request->room){
			$order_list = $order_list->where('room_id', $request->room);
		}

		if($request->count && $request->count > 0){
			$order_list = $order_list->select('name', 'phone', DB::raw('count(*) as total'))->groupBy('phone')->havingRaw('count(*) >= ?', [$request->count])->where('status', 5);
		}

		$order_list = $order_list->get();

		return Excel::create('泰和殿訂單列表', function($excel) use ($order_list, $request){
		    $excel->sheet('訂單', function($sheet) use ($order_list, $request){
		    	if (!$request->count) {
		    		$fromArrayData[] = [ "訂單編號", "姓名", "電話", "人數", "服務項目", "房間", "預約人","開始時間", "結束時間", "訂單時間", "訂單狀態"];
			    	foreach ($order_list as $key => $order) {
			    		if($order->room->shower){
								$shower = "可沖洗";
							}
							else{
								$shower = "";
							}
							$status = "";
							switch ($order->status) {
								case '1':
									$status = "客戶預定";
									break;
								case '2':
									$status = "櫃檯預定";
									break;
								case '3':
									$status = "客戶取消";
									break;
								case '4':
									$status = "櫃檯取消";
									break;
								case '5':
									$status = "訂單成立";
									break;
								default:
									# code...
									break;
							}
							$room_name = $order->room->name."(".$order->room->person."人房 ".$shower.")";
							$order_account = $order->account != null ? $order->account->account: "";
			    		$fromArrayData[] = [ $order->id, $order->name, $order->phone, $order->person, $order->service->title, $room_name, $order_account,$order->start_time, $order->end_time, $order->created_at, $status];
			    	}
			    	$sheet->fromArray($fromArrayData);
		    	}
		    	else {
		    		$fromArrayData[] = [ "姓名", "電話", "次數"];
		    		foreach ($order_list as $key => $order) {
		    			$fromArrayData[] = [$order->name, $order->phone, $order->total];
		    		}
		    		$sheet->fromArray($fromArrayData);
		    	}
		    	
		    });
		})->export('xls');
	}

}