<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash, Exception, DB, DateTime, DateInterval, Session;
use App\Models\Shop;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Models\Order;
use App\Models\Room;
use App\Models\BlackList;
use App\Models\Log;
class StaffController extends Controller
{
	const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
	public function index()
	{
		if (!Session::has('account')) {
          return redirect('/staff/login');
        }
		$shops = Shop::all();
		$services = Service::all();
		$view_data['shops'] = $shops;
		$view_data['services'] = $services;
		return view('staff/index', $view_data);
		
	}

	public function order(Request $request)
	{
		$name = $request->name != "" ? $request->name : "現場客";
		$phone = $request->phone != "" ? $request->phone : "現場客";
		$start_time = $request->date_time;
		$shop_id = $request->shop_id;
		$order_list = $request->order;

		$msg = "姓名: $name 電話: $phone <br/> 開始時間: ".str_replace("T"," ",$start_time)."<br/>";
		
		$order_count = 1;
		foreach ($order_list as $key => $order_info) {
			$service = Service::where('id', $order_info['service_id'])->first();
			$end_time = new Datetime($start_time);
			$end_time = $end_time->add(new DateInterval("PT".$service->time."M"));
			$service_provider_id_list = $order_info['service_provider_list'];
			$room_id = $order_info['room_id'];

			$room = Room::where('id', $room_id)->first();

			$order = new Order;
			$order->name = $name;
			$order->phone = $phone;
			$order->status = 1;
			$order->person = count($service_provider_id_list);
			$order->service_id = $service->id;
			$order->room_id = $room_id;
			$order->shop_id = $shop_id;
			$order->start_time = $start_time;
			$order->end_time = $end_time->format('Y-m-d H:i:s');
			$order->account_id = $request->session()->get('account_id');
			$order->save();

			$msg .= "<br/>第 ".intval($order_count)." 筆訂單   人數: $order->person <br/> 服務: $service->title<br/> 指定師傅: ";
			
			$service_provider_list = ServiceProvider::whereIn('id', $service_provider_id_list)->get();
			$service_provider_name = "";
			foreach ($service_provider_list as $key => $service_provider) {
				$msg .= "$service_provider->name ";
				$service_provider->orders()->save($order);
				$service_provider_name = $service_provider_name.$service_provider->name." ";
			}
			$order_count++;
		}

		Log::create(['description' => '新增 訂單#'.$order->id." 店家:".$order->shop()->first()->name." 姓名:".$order->name." 電話:".$order->phone." 服務:".$order->service()->first()->title." 人數:".$order->person."房號:".$room->name."師傅:".$service_provider_name." 開始時間:".$order->start_time." 結束時間".$order->end_time."(櫃台訂位)"]);
		$request->session()->flush();

		return redirect('/staff/login')->withInput(['message' => $msg]);
	}

	public function api_check_status(Request $request){
		
		$shop_id = $request->shop_id;
		$limit_time = $request->limit_time;
		$shop = Shop::where('id', $shop_id)->first();

		$start_time = new DateTime($request->time);
		$end_time = new Datetime($request->time);

		//開店時間根據早的時間需會變化
		$month = $start_time->format('Y-m');
		$date = new DateTime($start_time->format('Y-m-d'));
		$shop_start_time = new DateTime($date->format('Y-m-d')."T".$shop->start_time);
		if($shop_start_time > $start_time){
			$date->sub(new DateInterval("P1D"));
		}
		
		//計算師傅兩小時
		$end_time->add(new DateInterval("PT120M"));
		//限制時間
		if($limit_time == "true"){
			//師傅預約15分鐘
			$start_time->sub(new DateInterval('PT15M'));
			$end_time->add(new DateInterval('PT15M'));
		}
		//2h以上 ok的師傅名單
		$service_providers = ServiceProvider::whereHas('shifts' ,function ($query) use ($month) {
		    $query->where('month', $month);
		})->with(['shifts' => function ($query) use ($month) {
		    $query->where('month', $month);
		}])->whereDoesntHave('leaves' ,function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
			$query->where('status', '!=', 6);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->orderBy('name', 'asc')->get();
		
		//限制時間
		if($limit_time == "true"){
			//扣回 避免出勤錯誤
			$start_time->add(new DateInterval('PT15M'));
			$end_time->sub(new DateInterval('PT15M'));
		}
		$service_providers_2h = [];
		foreach ($service_providers as $service_provider) {
			$shift = $service_provider->shifts->first();
			$on_duty = new DateTime($date->format('Y-m-d')." ".$shift->start_time);
			$off_duty =  new DateTime($date->format('Y-m-d')." ".$shift->end_time);
			if($off_duty < $on_duty){
				$off_duty->add(new DateInterval("P1D"));
			}
			// echo $on_duty->format('Y-m-d H:i:s')."	".$off_duty->format('Y-m-d H:i:s').$start_time->format('Y-m-d H:i:s')."	".$end_time->format('Y-m-d H:i:s')."<br>";
			if($on_duty <= $start_time && $off_duty >= $end_time){
				$service_providers_2h[] = $service_provider;
			}
		}
		//限制時間
		if($limit_time == "true"){
			//房間預約30分鐘
			$start_time->sub(new DateInterval('PT30M'));
			$end_time->add(new DateInterval('PT30M'));
		}
		//2h以上房間名單
		$rooms = Room::whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
			$query->where('status', '!=', 6);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->orderBy('name', 'asc')->get();
		
		$rooms_2h = [];
		foreach ($rooms as $room){
			if(!in_array($room, $rooms_2h)){
				$rooms_2h[] = $room;
			}
		}
		//限制時間
		if($limit_time == "true"){
			//扣回
			$start_time->add(new DateInterval('PT30M'));
			$end_time->sub(new DateInterval('PT30M'));
		}
		//限制時間
		if($limit_time == "true"){
			//師傅預約15分鐘
			$start_time->sub(new DateInterval('PT15M'));
			$end_time->add(new DateInterval('PT15M'));
		}
		//扣一小
		$end_time->sub(new DateInterval("PT60M"));
		//1h以上 ok的師傅名單
		$service_providers = ServiceProvider::whereHas('shifts' ,function ($query) use ($month) {
		    $query->where('month', $month);
		})->with(['shifts' => function ($query) use ($month) {
		    $query->where('month', $month);
		}])->whereDoesntHave('leaves' ,function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
			$query->where('status', '!=', 6);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->orderBy('name', 'asc')->get();

		$service_providers_1h = [];
		foreach ($service_providers as $service_provider) {
			$shift = $service_provider->shifts->first();
			$on_duty = new DateTime($date->format('Y-m-d')." ".$shift->start_time);
			$off_duty =  new DateTime($date->format('Y-m-d')." ".$shift->end_time);
			if($off_duty < $on_duty){
				$off_duty->add(new DateInterval("P1D"));
			}
			if($on_duty <= $start_time && $off_duty >= $end_time){
				if(!in_array($service_provider, $service_providers_2h)){
					$service_providers_1h[] = $service_provider;
				}
			}
		}
		//限制時間
		if($limit_time == "true"){
			//扣回 避免出勤錯誤
			$start_time->add(new DateInterval('PT15M'));
			$end_time->sub(new DateInterval('PT15M'));
		}
		//限制時間
		if($limit_time == "true"){
			//房間預約30分鐘
			$start_time->sub(new DateInterval('PT30M'));
			$end_time->add(new DateInterval('PT30M'));
		}
		$rooms = Room::whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
			$query->where('status', '!=', 6);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->orderBy('name', 'asc')->get();
		//限制時間
		if($limit_time == "true"){
			//扣回
			$start_time->add(new DateInterval('PT30M'));
			$end_time->sub(new DateInterval('PT30M'));
		}
		$rooms_1h = [];
		foreach ($rooms as $room){
			if(!in_array($room, $rooms_2h)){
				$rooms_1h[] = $room;
			}
		}

		$i = 0;
		$service_provider_status = [];
		foreach ($service_providers_1h as $service_provider) {
			$service_provider_status[] = ["index" => $i, "info" => $service_provider->name."號(一小時)", "id" => $service_provider->id, 'selected' => false];
			$i++;
		}
		foreach ($service_providers_2h as $service_provider) {
			$service_provider_status[] = ["index" => $i, "info" => $service_provider->name."號(二小時)", "id" => $service_provider->id, 'selected' => false];
			$i++;
		}
		$i = 0;
		$room_status = [];
		foreach ($rooms_1h as $room) {
			$room_status[] = ["index" => $i, "info" => $room->name."號(一小時)", "id" => $room->id, 'selected' => false];
			$i++;
		}
		foreach ($rooms_2h as $room) {
			$room_status[] = ["index" => $i, "info" => $room->name."號(二小時)", "id" => $room->id, 'selected' => false];
			$i++;
		}
		
		$result['service_provider_status'] = $service_provider_status;
		$result['room_status'] = $room_status;


		return response()->json($result, 200,  self::headers, JSON_UNESCAPED_UNICODE);
	}

	public function api_service_provider_list(Request $request){
		try {
			$shop_id = $request->shop_id;
			$result = ServiceProvider::where('shop_id', $shop_id)->get();

			return response()->json($result, 200,  self::headers, JSON_UNESCAPED_UNICODE);
		} catch (Exception $e) {
			return response()->json($e->getMessage(), 400,  self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_service_provider_time(Request $request){
		try {

			$date = $request->date;
			$shop_id = $request->shop_id;
			$worker_list_1hr = $request->worker_list_1hr ? $request->worker_list_1hr : [];
			$worker_list_2hr = $request->worker_list_2hr ? $request->worker_list_2hr : [];
			$no_limit_1hr = $request->no_limit_1hr;
			$no_limit_2hr = $request->no_limit_2hr;
			$limit_time = $request->limit_time;

			$shop = Shop::where('id', $shop_id)->first();

			$start_time = new DateTime($date.' '.$shop->start_time);

			$end_time = new DateTime($date.' '.$shop->end_time);
			if($end_time <= $start_time){
				$end_time->add(new DateInterval("P1D"))->modify("-2 hour");
			}

			$result = [];

			while($start_time <= $end_time){
				if($this->time_option($date, $limit_time, 60, $start_time->format('Y-m-d H:i:s'), $shop_id, $worker_list_1hr, $no_limit_1hr) && $this->time_option($date, $limit_time, 120, $start_time->format('Y-m-d H:i:s'), $shop_id, $worker_list_2hr, $no_limit_2hr)){
					$result[] = $start_time->format('H:i');
				}
				$start_time->add(new DateInterval("PT30M"));
			}

			return response()->json($result, 200,  self::headers, JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			return response()->json($e->getMessage(), 400,  self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	private function time_option($date, $limit_time, $service_time,$start, $shop_id, $worker_list, $no_limit){
		$month = date("Y-m", strtotime($date));

		$start_time = new Datetime($start);
		$end_time = clone $start_time;
		$end_time->add(new DateInterval("PT".$service_time."M"));

		if($limit_time == "true"){
			//師傅預約15分鐘
			$start_time->sub(new DateInterval('PT15M'));
			$end_time->add(new DateInterval('PT15M'));
		}

		$service_providers = ServiceProvider::whereHas('shifts' ,function ($query) use ($month) {
		    $query->where('month', $month);
		})->with(['shifts' => function ($query) use ($month) {
		    $query->where('month', $month);
		}])->whereDoesntHave('leaves' ,function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
			$query->where('status', '!=', 6);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->get();
		//扣回 避免出勤錯誤
		$start_time->add(new DateInterval('PT15M'));
		$end_time->sub(new DateInterval('PT15M'));

		$service_provider_list = [];
		foreach($service_providers as $service_provider){
			$shift = $service_provider->shifts->first();
			$on_duty = new DateTime($date." ".$shift->start_time);
			$off_duty =  new DateTime($date." ".$shift->end_time);
			if($off_duty < $on_duty){
				$off_duty->add(new DateInterval("P1D"));
			}
			if($on_duty <= $start_time && $off_duty >= $end_time){
				$service_provider_list[] = $service_provider->id;
			}
		}

		$service_providers = ServiceProvider::with(['orders' => function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
			$query->where('status', '!=', 6);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		}])->where('shop_id', $shop_id)->get();

		$service_providers_count = 0;
		foreach($service_providers as $service_provider){
			$service_providers_count += $service_provider->orders->count();
		}

		$order_person_count = Order::
									where('start_time', '<', $end_time)->
									where('end_time', '>', $start_time)->
									where('status', '!=', 3)->
									where('status', '!=', 4)->
									where('status', '!=', 6)->
									where('shop_id', $shop_id)->get()->sum('person');

		if(!empty(array_diff($worker_list, $service_provider_list))){
			$service_providers = ServiceProvider::whereIn('id', array_diff($worker_list, $service_provider_list))->pluck('name')->toArray();
			return false;
		}
	
		if(count($service_provider_list) - $order_person_count + $service_providers_count< count($worker_list) + $no_limit){
			return false;
		}

		return true;
	}
}