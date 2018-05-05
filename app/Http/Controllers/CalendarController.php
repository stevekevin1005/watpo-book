<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception, Datetime, DateInterval;
use App\Models\ServiceProvider;
use App\Models\Shop;
use App\Models\Room;
use App\Models\Order;
use App\Models\Service;
use App\Models\Log;
use Session;

class CalendarController extends Controller
{
	
	public function index(Request $request, $shop_id)
	{
	
		$service_providers = ServiceProvider::with('shop')->orderBy('name', 'asc')->get();
		
		$rooms = Room::where('shop_id', $shop_id)->orderBy('name', 'asc')->get();

		$view_data['service_providers_1'] = [];
		$view_data['service_providers_2'] = [];
		foreach ($service_providers as $key => $service_provider) {
			$service_provider_name = $service_provider->name."(".$service_provider->shop->name.")";
			if($service_provider->shop_id == $shop_id){
				$view_data['service_providers_1'][] = ['id' => $service_provider->id, 'name' => $service_provider_name];
			}
			else{
				$view_data['service_providers_2'][] = ['id' => $service_provider->id, 'name' => $service_provider_name];
			}
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

		$shop = Shop::where('id', $shop_id)->first();
		$view_data['shop'] = $shop;
		$view_data['service_list'] = Service::all();
		$view_data['shop_id'] = $shop->id;

		$start_time = new DateTime($shop->start_time);
		$date = new DateTime();
		if($date < $start_time){
			$date->modify("-1 day");
		}

		$view_data['today'] = $date->format('Y-m-d');

		
		$shop_service_providers = ServiceProvider::where('shop_id', $shop_id)->get();

		/* shop_service_providers */
		$view_data['shop_service_providers'] = [];
		foreach ($shop_service_providers as $key => $shop_service_provider) {
			$view_data['shop_service_providers'][] = ['id'=> $shop_service_provider->id, 'title'=>$shop_service_provider->name];
		}


		$order_list = $this->order_list($date->format('Y-m-d'), $shop_id);
		$view_data['order_list'] = $order_list;

		return view('admin.calendar.index', $view_data);
	}

	private function order_list($date, $shop_id)
	{

		$now = new Datetime();

		$shop = Shop::where('id', $shop_id)->first();
		$shop_start_time = strtotime($date.' '.$shop->start_time);
		$shop_end_time = strtotime($date.' '.$shop->end_time);
		
		if($shop_end_time <= $shop_start_time){
			$shop_end_time = strtotime("+1 day", $shop_end_time);
		}
		/* order */
		$orders =	new Order;
		if(Session::has('service_provider_id')){
			$service_provider_id = Session::get('service_provider_id');
			$orders = $orders->whereHas('serviceProviders' ,function ($query) use ($service_provider_id) {
			    $query->where('id', $service_provider_id);
			});
		}

		$orders =	$orders->with('service')
							->with('room')
							->with('serviceProviders')
							->where('shop_id', $shop_id)
							->where('start_time', '>=', date("Y-m-d H:i:s", $shop_start_time))
							->where('end_time', '<=', date("Y-m-d H:i:s", $shop_end_time));

		if(Session::get('account_level') != 1){
			//30分後才隱藏訂單
			$now->add(new DateInterval('PT30M'));
			$orders = $orders->where('end_time', '>=', $now);
			$now->sub(new DateInterval('PT30M'));
		}
		$orders = $orders->orderBy('start_time', 'asc')
						 ->get();
		$order_list = [];

		foreach ($orders as $key => $order) {
			$data = (object)[];
			$data->id = $order->id;
			$data->name = $order->name;
			$data->phone = $order->phone;
			
			$data->person = $order->person;
			$data->room = $order->room->name;
			$data->room_id = $order->room->id;
			$data->service = $order->service->title;
			$data->service_id = $order->service_id;
			if($order->account != null){
				$data->account = $order->account->account;
			}
			else
			{
				$data->account = null;
			}
			$data->start_time = date("Y-m-d\TH:i" ,strtotime($order->start_time));
			$data->end_time = date("Y-m-d\TH:i" ,strtotime($order->end_time));
			$data->time = date("H:i" ,strtotime($order->start_time))." - ".date("H:i" ,strtotime($order->end_time));

			if(strtotime(date('Y-m-d H:i:s')) - strtotime($order->start_time) >= 600 && ($order->status == 1 || $order->status == 2)){
				$order->status = 6;
				$order->save();
			}

			$data->status = $order->status;

			$data->provider = "";
			$person = $order->person;
			$service_provider_count = 0;	
			foreach ($order->serviceProviders as $serviceProvider) {
				if($serviceProvider->shop_id == $shop_id){
					$data->provider .= $serviceProvider->name." ";
				}
				else{
					$data->provider .= $serviceProvider->name."(調) ";
				}
				$service_provider_count++;
			}
			for($i = $service_provider_count;$i < $order->person;$i++){
				$data->provider .= "排 ";
			}

			switch ($order->status) {
				case 1:
					$data->color = "#1d7dca";
					break;
				case 2:
					$data->color = "#BA55D3";
					break;
				case 3:
					$data->color = "gray";
					break;
				case 4:
					$data->color = "#ffaa00";
					break;
				case 5:
					$data->color = "#5cb85c";
					break;
				case 6:
					$data->color = "red";
					break;
				default:
					$data->color = "";
					break;
			}
			if($data->phone != '現場客' ){
				if(($key - 1 >= 0 && $data->phone == $orders[$key-1]->phone ) || ($key + 1 < count($orders) && $data->phone == $orders[$key+1]->phone )){
					$data->same_phone = 'border-left: 2px red solid;border-right: 2px red solid;';
					if(!($key - 1 >= 0 && $data->phone == $orders[$key-1]->phone )) $data->same_phone .= 'border-top: 2px red solid;';
					else if(!($key + 1 < count($orders) && $data->phone == $orders[$key+1]->phone )) $data->same_phone .= 'border-bottom: 2px red solid;';
				}
				else{
					$data->same_phone = '';
				}
			}
			else{
				$data->same_phone = '';
			}

			//10分後開始訂單開始閃爍
			$now->add(new DateInterval('PT3M'));
			if($now >= new Datetime($order->start_time) && $now <= new Datetime($order->end_time) && $order->status != 3 && $order->status != 4 && $order->status != 5 && $order->status != 6){
				$data->class = "animation";	
			}
			else{
				$data->class = "";	
			}
			$now->sub(new DateInterval('PT3M'));
			$order_list[] = $data;
		}

		return $order_list;
	}

	public function api_order_list(Request $request)
	{	
		try{
			$date = $request->date;
			$shop_id = $request->shop_id;

			$result['order_list'] = $this->order_list($date, $shop_id);

			return response()->json($result, 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}


	public function api_shop_calendar(Request $request, $shop_id)
	{	
		try{
			$date = date('Y-m-d');
			$shop = Shop::where('id', $shop_id)->first();

			$order = new Order;
			if($request->start){
				$order = $order->where('start_time', '>=', $request->start);
			}
			if($request->end){
				$order = $order->where('end_time', '<=', $request->end);
			}
			$orders = $order->with('serviceProviders')->with('service')->with('room')->where('shop_id', $shop_id)->where('status', '!=', 3)->get();
			$result = null;
			$i = 1;

			foreach ($orders as $key => $order) {
				$service_providers = [];
				foreach ($order->serviceProviders as $key => $serviceProvider) {
					$service_providers[] = $serviceProvider->id;
				}
				switch ($order->status) {
					case 1:
						$color = "#1d7dca";
						break;
					case 2:
						$color = "#BA55D3";
						break;
					case 4:
						$color = "#ffaa00";
						break;
					case 5:
						$color = "#5cb85c";
						break;	
					default:
						$color = "#0066FF";
						break;
				}
				$result[] = [
					'id'=>$i ,
					'order_id'=>$order->id, 
					'resourceIds'=>$service_providers, 
					'start'=>$order->start_time, 
					'end'=>$order->end_time, 
					'start_time'=>$order->start_time, 
					'end_time'=>$order->end_time,
					'title'=>$order->name, 
					'phone'=>$order->phone, 
					'person'=>$order->person, 
					'color'=> $color, 
					'service'=>$order->service->title, 
					'room'=> $order->room->name, 
					'room_id'=> $order->room_id,
					'status' => $order->status,
				];
				$i++;
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
			$limit_room = $request->limit_room;
			$start_time = $request->start_time;
			$end_time = $request->end_time;
			$room_id = $request->room_id;
			$service_id = $request->service_id;
			
			$service_provider_id_list = $request->service_provider_list;
			$name = $request->name;
			$phone = $request->phone;
			$person = count($service_provider_id_list);
			
			if(!isset($name) || $name == ''){
				$name = "現場客";
			}
			if(!isset($phone) || $phone == ''){
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
					$query->where('status', '!=', 6);
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>',$start_time);
			}])->whereIn('id', $service_provider_id_list)->get();
			
			foreach ($service_provider_list as $key => $service_provider) {
				if($service_provider->leaves->count() > 0){
					throw new Exception($service_provider->name."號 師傅該時段請假 請重新選擇", 1);
				}
				if($service_provider->orders->count() > 0){
					throw new Exception($service_provider->name."號 師傅該時段已有約 請重新選擇", 1);
				}
			}
			
			$room = Room::with(['orders' => function ($query) use ($start_time, $end_time) {
					$query->where('status', '!=', 3);
					$query->where('status', '!=', 4);
					$query->where('status', '!=', 6);
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>', $start_time);
			}])->where('id', $room_id)->first();

			if($limit_room == "true"){
				if($room->orders->count() > 0){
					throw new Exception("該時段房間已有預訂 請重新選擇", 1);
				}
			}
				
			$order = new Order;
			$order->name = $name;
			$order->phone = $phone;
			$order->status = 1;
			$order->service_id = $service_id;
			$order->room_id = $room_id;
			$order->shop_id = $shop_id;
			$order->person = $person;
			$order->start_time = $start_time;
			$order->end_time = $end_time;
			$order->account_id = $request->session()->get('account_id');
			$order->save();

			$service_provider_name = "";
			foreach ($service_provider_list as $key => $service_provider) {
				$service_provider->orders()->save($order);
				$service_provider_name = $service_provider_name.$service_provider->name." ";
			}

			Log::create(['description' => '新增 訂單#'.$order->id." 店家:".$order->shop()->first()->name." 姓名:".$order->name." 電話:".$order->phone." 服務:".$order->service()->first()->title." 人數:".$order->person."房號:".$room->name."師傅:".$service_provider_name." 開始時間:".$order->start_time." 結束時間".$order->end_time."(預約排程)"]);
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
			$limit_room = $request->limit_room;

			$start_time = $request->start_time;
			$end_time = $request->end_time;
			$room_id = $request->room_id;
			$service_id = $request->service_id;
			
			$service_provider_id_list = $request->service_provider_list;
			$name = $request->name;
			$phone = $request->phone;
			$person = count($service_provider_id_list);
			
			if(!isset($name) || $name == ''){
				$name = "現場客";
			}
			if(!isset($phone) || $phone == ''){
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
			// if($person < 1){
			// 	throw new Exception("沒有選擇師傅", 1);
			// }

			$order = Order::with('serviceProviders')->with('service')->with('shop')->with('room')->where('id', $order_id)->first();
			$order->name = $name;
			$order->phone = $phone;
			$order->status = 2;
			$order->service_id = $service_id;
			$room_name = $order->room->name;
			$service_provider_name = " ";
			if($order->start_time != $start_time || $order->end_time != $end_time){
				$room = Room::with(['orders' => function ($query) use ($start_time, $end_time) {
					$query->where('status', '!=', 3);
					$query->where('status', '!=', 4);
					$query->where('status', '!=', 6);
				    $query->where('start_time', '<', $end_time);
				    $query->where('end_time', '>', $start_time);
				}])->where('id', $room_id)->first();
				if($limit_room == "true"){
					foreach ($room->orders as $room_order) {
						if($room_order->id != $order->id){
							throw new Exception("該時段房間已有預訂 請重新選擇", 1);
						}
					}
				}
				$room_name = $room->name;
				$order->room_id = $room_id;
				if($person > 0){
					$order->serviceProviders()->detach();
					$order->person = $person;
					$service_provider_list = ServiceProvider::with(['leaves' => function ($query) use ($start_time, $end_time) {
					    $query->where('start_time', '<', $end_time);
					    $query->where('end_time', '>', $start_time);
					}])->with(['orders' => function ($query) use ($start_time, $end_time) {
						$query->where('status', '!=', 3);
						$query->where('status', '!=', 4);
						$query->where('status', '!=', 6);
					    $query->where('start_time', '<', $end_time);
					    $query->where('end_time', '>',$start_time);
					}])->whereIn('id', $service_provider_id_list)->get();
					
					foreach ($service_provider_list as $key => $service_provider) {
						if($service_provider->leaves->count() > 0){
							throw new Exception($service_provider->name."號 師傅該時段請假 請重新選擇", 1);
						}
						if($service_provider->orders->count() > 0){
							throw new Exception($service_provider->name."號 師傅該時段已有約 請重新選擇", 1);
						}
					}
					
					foreach ($service_provider_list as $key => $service_provider) {
						$service_provider_name = $service_provider_name.$service_provider->name." ";
						$service_provider->orders()->save($order);
					}
				}
				else{
					foreach ($order->serviceProviders as $key => $service_provider) {
						$orders = Order::whereHas('ServiceProviders', function($query) use ($service_provider){
							$query->where('id', $service_provider->id);
						})
						->where('status', '!=', 3)
						->where('status', '!=', 4)
						->where('status', '!=', 6)
						->where('start_time', '<', $end_time)
						->where('end_time', '>',$start_time)->get();
						foreach ($orders as $service_provider_order) {
							if($service_provider_order->id != $order->id){
								throw new Exception($service_provider->name."號 師傅該時段已有約 請重新選擇", 1);
							}
						}
					}
				}
			}
			else{
				if($order->room_id != $room_id){
					$room = Room::with(['orders' => function ($query) use ($start_time, $end_time) {
						$query->where('status', '!=', 3);
						$query->where('status', '!=', 4);
						$query->where('status', '!=', 6);
					    $query->where('start_time', '<', $end_time);
					    $query->where('end_time', '>', $start_time);
					}])->where('id', $room_id)->first();

					if($limit_room == "true" && $room->orders->count() > 0){
						throw new Exception("該時段房間已有預訂 請重新選擇", 1);
					}
					$room_name = $room->name;
					$order->room_id = $room_id;
				}
				if($person > 0){
					$order->serviceProviders()->detach();
					$order->person = $person;
					$service_provider_list = ServiceProvider::with(['leaves' => function ($query) use ($start_time, $end_time) {
					    $query->where('start_time', '<', $end_time);
					    $query->where('end_time', '>', $start_time);
					}])->with(['orders' => function ($query) use ($start_time, $end_time) {
						$query->where('status', '!=', 3);
						$query->where('status', '!=', 4);
						$query->where('status', '!=', 6);
					    $query->where('start_time', '<', $end_time);
					    $query->where('end_time', '>',$start_time);
					}])->whereIn('id', $service_provider_id_list)->get();
					
					foreach ($service_provider_list as $key => $service_provider) {
						if($service_provider->leaves->count() > 0){
							throw new Exception($service_provider->name."號 師傅該時段請假 請重新選擇", 1);
						}
						if($service_provider->orders->count() > 0){
							throw new Exception($service_provider->name."號 師傅該時段已有約 請重新選擇", 1);
						}
					}
					foreach ($service_provider_list as $key => $service_provider) {
						$service_provider->orders()->save($order);
						$service_provider_name = $service_provider_name.$service_provider->name." ";
					}

				}
			}
			$order->start_time = $start_time;
			$order->end_time = $end_time;
			$order->save();

			Log::create(['description' => '更改 訂單#'.$order->id." 店家:".$order->shop->name." 姓名:".$order->name." 電話:".$order->phone." 服務:".$order->service->title." 人數:".$order->person."房號:".$room_name."師傅:".$service_provider_name." 開始時間:".$order->start_time." 結束時間".$order->end_time."(預約排程)"]);
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
			$order = Order::with('service')->with('shop')->where('id', $order_id)->first();
			$order->status = 5;
			$order->save();
			Log::create(['description' => '確認 訂單#'.$order->id]);
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
			$order = Order::with('service')->with('shop')->where('id', $order_id)->first();
			$order->status = 4;
			$order->save();
			Log::create(['description' => '取消 訂單#'.$order->id]);
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