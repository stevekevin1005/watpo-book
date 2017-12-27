<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;
use App\Models\ServiceProvider;
use App\Models\Shop;
use App\Models\Room;
use App\Models\Order;
use App\Models\Service;


class CalendarController extends Controller
{
	
	public function index(Request $request, $shop_id)
	{
		$service_providers = ServiceProvider::with('shop')->get();
		$rooms = Room::where('shop_id', $shop_id)->get();

		foreach ($service_providers as $key => $service_provider) {
			$service_provider_name = $service_provider->name."(".$service_provider->shop->name.")";
			$view_data['service_providers'][] = ['id' => $service_provider->id, 'name' => $service_provider_name];
		}

		foreach ($rooms as $key => $room) {
			if($room->shower){
				$shower = "可沖洗";
			}
			else{
				$shower = "";
			}
			$room_name = $room->name."(".$room->person."人房 ".$shower.")";
			$view_data['rooms'][] = ['id' => $room->id, 'name' => $room_name ];
		}

		$view_data['shop'] = Shop::where('id', $shop_id)->first();
		$view_data['service_list'] = Service::all();
		$view_data['shop_id'] = $shop_id;

		return view('admin.calendar.index', $view_data);
	}

	public function api_shop_calendar(Request $request, $shop_id)
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
				$result['service_providers'][] = ['id'=> $service_provider->id, 'title'=>$service_provider->name];
			}

			$orders = Order::with('serviceProviders')->where('shop_id', $shop_id)->where('start_time', '>=', date("Y/m/d H:i:s", $start_time))->where('status', '!=', 4)->get();

			$result['orders'] = [];
			
			$i = 1;
			foreach ($orders as $key => $order) {
				foreach ($order->serviceProviders as $key => $serviceProvider) {
					
					switch ($order->status) {
						case 1:
							$color = "#3ddcf7";
							break;
						case 2:
							$color = "#1d7dca";
							break;
						case 3:
							$color = "#ef5350";
							break;
						case 5:
							$color = "#5cb85c";
							break;	
						default:
							$color = "#3ddcf7";
							break;
					}

					$result['orders'][] = ['id'=>$i ,'data-id'=>$order->id, 'resourceId'=>$serviceProvider->id, 'start'=>$order->start_time, 'end'=>$order->end_time, 'title'=>$order->name, 'phone'=>$order->phone, 'person'=>$order->person, 'color'=> $color];
					$i++;
				}
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

	public function add_order(Request $request, $shop_id)
	{
		try{

			$shop_id = $request->shop_id;
			$start_time = $request->start_time;
			$end_time = $request->end_time;
			$room_id = $request->room_id;
			$service_id = $request->service_id;
			
			$service_provider_id_list = $request->service_provider_list;
			$name = $request->name;
			$phone = $request->phone;
			$person = count($service_provider_id_list);
			
			if(!$name){
				$name = "現場客";
			}
			if(!$phone){
				$phone = "現場客";
			}

			if(!$start_time){
				throw new Exception("缺少開始時間", 1);
			}
			if(!$end_time){
				throw new Exception("缺少結束時間", 1);
			}
			if($start_time > $end_time){
				throw new Exception("結束時間比開始時間早", 1);
			}
			if(!$shop_id){
				throw new Exception("缺少店家ID", 1);
			}
			if(!$service_id){
				throw new Exception("缺少服務ID", 1);
			}
			if(!$room_id){
				throw new Exception("缺少房間ID", 1);
			}
			if($person < 1){
				throw new Exception("沒有選擇師傅", 1);
			}

			$service_provider_list = ServiceProvider::with(['leaves' => function ($query) use ($start_time, $end_time) {
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>', $start_time);
			}])->with(['orders' => function ($query) use ($start_time, $end_time) {
					$query->where('status', '!=', 3);
					$query->where('status', '!=', 4);
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>',$start_time);
			}])->whereIn('id', $service_provider_id_list)->get();
			
			foreach ($service_provider_list as $key => $service_provider) {
				if($service_provider->leaves->count() > 0){
					throw new Exception("該師傅該時段請假 請重新選擇", 1);
				}
				if($service_provider->orders->count() > 0){
					throw new Exception("該師傅該時段已有約 請重新選擇", 1);
				}
			}
			$room = Room::with(['orders' => function ($query) use ($start_time, $end_time) {
					$query->where('status', '!=', 3);
					$query->where('status', '!=', 4);
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>', $start_time);
			}])->where('id', $room_id)->first();

			if($room->orders->count() > 0){
				throw new Exception("該時段房間已有預訂 請重新選擇", 1);
			}

			$order = new Order;
			$order->name = $name;
			$order->phone = $phone;
			$order->status = 1;
			$order->service_id = $service_id;
			$order->room_id = $room_id;
			$order->shop_id = $shop_id;
			$order->start_time = $start_time;
			$order->end_time = $end_time;
			$order->save();

			foreach ($service_provider_list as $key => $service_provider) {
				$service_provider->orders()->save($order);
			}

			return redirect()->back()->withInput(['message' => '訂單新增成功']);
		}
		catch(Exception $e){
			return redirect()->back()->withErrors(['fail'=> "訂單新增失敗: ".$e->getMessage()]);
		}
		catch(\Illuminate\Database\QueryException $e){
			return redirect()->back()->withErrors(['fail'=> "訂單新增失敗: ".$e->getMessage()]);
		}
	}
}