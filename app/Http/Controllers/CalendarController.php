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
		if($shop_id == 2){
			$service_providers = ServiceProvider::with('shop')->orderBy('shop_id', 'desc')->get();
		}
		else{
			$service_providers = ServiceProvider::with('shop')->orderBy('shop_id', 'asc')->get();
		}
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

		$date = date('Y-m-d');
		$view_data['today'] = $date;

		$shop = Shop::where('id', $shop_id)->first();
		$shop_service_providers = ServiceProvider::where('shop_id', $shop_id)->get();

		$view_data['start_time'] = $shop->start_time;


		$start_time = strtotime($date.' '.$shop->start_time);

		$view_data['shop_service_providers'] = [];
		foreach ($shop_service_providers as $key => $shop_service_provider) {
			$view_data['shop_service_providers'][] = ['id'=> $shop_service_provider->id, 'title'=>$shop_service_provider->name];
		}

		return view('admin.calendar.index', $view_data);
	}

	public function api_shop_calendar(Request $request, $shop_id)
	{	
		try{
			$date = date('Y-m-d');
			$shop = Shop::where('id', $shop_id)->first();
			$start_time = strtotime($date.' '.$shop->start_time);

			$orders = Order::with('serviceProviders')->with('service')->with('room')->where('shop_id', $shop_id)->where('start_time', '>=', date("Y/m/d H:i:s", $start_time))->where('status', '!=', 3)->get();
			$result = null;
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
						case 4:
							$color = "#ffaa00";
							break;
						case 5:
							$color = "#5cb85c";
							break;	
						default:
							$color = "#3ddcf7";
							break;
					}
					$result[] = [
						'id'=>$i ,
						'order_id'=>$order->id, 
						'resourceId'=>$serviceProvider->id, 
						'start'=>$order->start_time, 
						'end'=>$order->end_time, 
						'title'=>$order->name, 
						'phone'=>$order->phone, 
						'person'=>$order->person, 
						'color'=> $color, 
						'service'=>$order->service->title, 
						'room'=> $order->room->name, 
						'room_id'=> $order->room_id,
						
					];
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
			$start_time = $request->start_time;
			$end_time = $request->end_time;
			$room_id = $request->room_id;
			$service_id = $request->service_id;
			
			$service_provider_id_list = $request->service_provider_list;
			$name = $request->name;
			$phone = $request->phone;
			$person = count($service_provider_id_list);
			
			if(!isset($name)){
				$name = "現場客";
			}
			if(!isset($phone)){
				$phone = "現場客";
			}

			if(!isset($start_time)){
				throw new Exception("缺少開始時間", 1);
			}
			if(!isset($end_time)){
				throw new Exception("缺少結束時間", 1);
			}
			if($start_time > $end_time){
				throw new Exception("結束時間比開始時間早", 1);
			}
			if(!isset($shop_id)){
				throw new Exception("缺少店家ID", 1);
			}
			if(!isset($service_id)){
				throw new Exception("缺少服務ID", 1);
			}
			if(!isset($room_id)){
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
			$order->status = 2;
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

	public function update_order(Request $request, $order_id)
	{
		try{

			$start_time = $request->start_time;
			$end_time = $request->end_time;
			$room_id = $request->room_id;
			$service_id = $request->service_id;
			
			$service_provider_id_list = $request->service_provider_list;
			$name = $request->name;
			$phone = $request->phone;
			$person = count($service_provider_id_list);
			
			if(!isset($name)){
				$name = "現場客";
			}
			if(!isset($phone)){
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
			if(!isset($service_id)){
				throw new Exception("缺少服務ID", 1);
			}
			if(!isset($room_id)){
				throw new Exception("缺少房間ID", 1);
			}
			if($person < 1){
				throw new Exception("沒有選擇師傅", 1);
			}

			
			

			$order = Order::where('id', $order_id)->first();
			$order->serviceProviders()->detach();
			$order->name = $name;
			$order->phone = $phone;
			$order->status = 2;
			$order->service_id = $service_id;
			
			if($order->room_id != $room_id){
				$room = Room::with(['orders' => function ($query) use ($start_time, $end_time) {
					$query->where('status', '!=', 3);
					$query->where('status', '!=', 4);
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>', $start_time);
				}])->where('id', $room_id)->first();
				if($room->orders->count() > 0){
					throw new Exception("該時段房間已有預訂 請重新選擇", 1);
				}
				$order->room_id = $room_id;
			}
			
			$order->start_time = $start_time;
			$order->end_time = $end_time;
			$order->save();

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
			
			foreach ($service_provider_list as $key => $service_provider) {
				$service_provider->orders()->save($order);
			}

			return redirect()->back()->withInput(['message' => '訂單更改成功']);
		}
		catch(Exception $e){
			return redirect()->back()->withErrors(['fail'=> "訂單更改失敗: ".$e->getMessage()]);
		}
		catch(\Illuminate\Database\QueryException $e){
			return redirect()->back()->withErrors(['fail'=> "訂單更改失敗: ".$e->getMessage()]);
		}
	}

	public function api_order_confirm(Request $request)
	{
		try{
			$order_id = $request->order_id;
			$order = Order::where('id', $order_id)->first();
			$order->status = 5;
			$order->save();
			return response()->json('訂單確認成功!', 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

	public function api_order_cancel(Request $request)
	{
		try{
			$order_id = $request->order_id;
			$order = Order::where('id', $order_id)->first();
			$order->status = 4;
			$order->save();
			return response()->json('訂單取消成功!', 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}
}