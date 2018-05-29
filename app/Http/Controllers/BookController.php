<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash, Exception, DB, DateTime, DateInterval;
use App\Models\Shop;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Models\Order;
use App\Models\Room;
use App\Models\BlackList;
class BookController extends Controller
{
	const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
	public function index()
	{
		$view_data = [];
		return view('book', $view_data);
		
	}
	public function api_shop_list(Request $request)
	{
		try{
			$shop_list = Shop::All();
			
			return response()->json($shop_list);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}
	public function api_service_list(Request $request)
	{
		try{
			$service_list = Service::All();
			
			return response()->json($service_list);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

	public function api_service_provider_and_room_list(Request $request)
	{
		try{
			$service_id = $request->service_id;
			$shop_id = $request->shop_id;

			if(!$service_id){
				throw new Exception("缺少服務ID", 1);;
			}

			$service_providers = ServiceProvider::where('shop_id', $shop_id);
			$rooms = Room::where('shop_id', $shop_id);

			if($service_id == 1 || $service_id == 3){
				$service_providers = $service_providers->where('service_1', true);
			}
			else if($service_id == 2 || $service_id == 4){
				$service_providers = $service_providers->where('service_2', true);
			}
			else{
				$service_providers = $service_providers->where('service_3', true);
				$rooms = $rooms->where('shower', true);
			}

			$service_providers = $service_providers->orderBy('name', 'asc')->get();
			$rooms = $rooms->get();

			$result = [];
			$result['service_provider_list'] = null;
			$result['room'] = null;
			
			foreach($service_providers as $service_provider){
				$result['service_provider_list'][] = ['id' => $service_provider->id, 'name' => $service_provider->name];
			}
			foreach($rooms as $room){
				$result['room'][] = ['id' => $room->id, 'shower' => $room->shower, 'shop_id' => $room->shop_id, 'person' => $room->person];
			}

			return response()->json($result);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_time_list(Request $request)
	{
		try{

			$date = new Datetime($request->date);
			$shop_id = $request->shop_id;
			$service_id = $request->service_id;
			$person = $request->person;
			$service_provider_id = $request->service_provider_id;
			$room_id = $request->room_id;
			$shower = $request->shower;
			if(!$date){
				throw new Exception("缺少日期", 1);
			}
			if(!$shop_id){
				throw new Exception("缺少店家ID", 1);
			}
			if(!$service_id){
				throw new Exception("缺少服務ID", 1);
			}
			if(!$person){
				throw new Exception("缺少人數", 1);
			}


			$service = Service::where('id', $service_id)->first();
			$shop = Shop::where('id', $shop_id)->first();
			

			$start_time = clone $date;

			$end_time = clone $date;
			$end_time->add(new DateInterval("P1D"));
			
			$shop_start_time = new Datetime($date->format('Y-m-d')." ".$shop->start_time);

			$shop_end_time =new Datetime($date->format('Y-m-d')." ".$shop->end_time);
			$shop_end_time->modify("-2 hour");

			$i = 0;
			while($start_time < $end_time){
				$today = clone $date;
				if($start_time <= $shop_end_time) $today->modify("-1 day");
				if($start_time >= $shop_start_time || $start_time <= $shop_end_time){
					$time_list[$i]['time'] = $start_time->format('H:i:s');

					if(new DateTime(date("Y-m-d H:i:s")) > $start_time){
						$time_list[$i]['select'] = false;
					}
					else{
						$msg = $this->time_option($today->format('Y-m-d'), $start_time->format('Y-m-d H:i:s'), $service->time, $shower, $shop_id, $person, $service_provider_id);
						if($msg === true){
							$time_list[$i]['select'] = true;
						}
						else{
							$time_list[$i]['select'] = false;
							$time_list[$i]['time'] .= $msg === false ? "": $msg;
						}
						
					}
					$i++;
				}
				$start_time->add(new DateInterval("PT30M"));

				
			}

			return response()->json($time_list, 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
			
	}
	private function time_option($date, $start, $service_time, $shower,$shop_id, $person, $service_provider_id){
		
		$month = date("Y-m", strtotime($date));

		$start_time = new Datetime($start);
		$end_time = new Datetime($start);
		$end_time->add(new DateInterval("PT".$service_time."M"));

		$service_provider_id_list = explode(",", $service_provider_id);
		foreach($service_provider_id_list as $index => $service_provider_id){
			if($service_provider_id == '0'){
				unset($service_provider_id_list[$index]);
			}
		}
		
		//師傅預約15分鐘
		$start_time->sub(new DateInterval('PT15M'));
		$end_time->add(new DateInterval('PT15M'));
		
		$service_providers = ServiceProvider::whereHas('leaves' ,function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->whereIn('id', $service_provider_id_list)->pluck('name')->toArray();

		if(count($service_providers) != 0){
			return "\n師傅 ".implode(" ",$service_providers)." 休";
		}

		$service_providers = ServiceProvider::freeTime($month, $start_time, $end_time)->where('shop_id', $shop_id)->get();
		
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

		if(!empty(array_diff($service_provider_id_list, $service_provider_list))){
			return false;
		}

		/* 不指定人數 */
		$service_providers = ServiceProvider::whereHas('orders' ,function ($query) use ($start_time, $end_time) {
			$query->whereNotIn('status', [3,4,6]);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->get();

		$order_list = Order::
							where('start_time', '<', $end_time)->
							where('end_time', '>', $start_time)->
							whereNotIn('status', [3,4,6])->
							where('shop_id', $shop_id)->
							withCount('serviceProviders')->get();

		$no_specific_amount = $this->no_specific($order_list, $service_providers);
	
		if(count($service_provider_list) - $no_specific_amount < $person){
			return false;
		}

		//房間預約30分鐘
		$start_time->sub(new DateInterval('PT30M'));
		$end_time->add(new DateInterval('PT30M'));


		$room = Room::whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->whereNotIn('status', [3,4,6]);
		    $query->where('start_time', '<=', $end_time);
		    $query->where('end_time', '>=', $start_time);
		})->where('shop_id', $shop_id)->where('person', '>=', $person);

		//扣回
		$start_time->add(new DateInterval('PT30M'));
		$end_time->sub(new DateInterval('PT30M'));

		if($shower == "true"){
			$room = $room->where('shower', 1);
		}

		$room = $room->first();

		if(!$room){
			return false;
		}

		return true;
	}

	public function api_order(Request $request){
		try{
			$shop_id = $request->shop_id;
			$start_time = new DateTime($request->start_time);
			$end_time = new DateTime($request->start_time);
			$service_id = $request->service_id;
			$person = $request->person;
			$service_provider_id = $request->service_provider_id;
			$name = $request->name;
			$phone = $request->phone;
			$shower = $request->shower;

			if(!$start_time){
				throw new Exception("缺少開始時間", 1);
			}
			if(!$shop_id){
				throw new Exception("缺少店家ID", 1);
			}
			if(!$service_id){
				throw new Exception("缺少服務ID", 1);
			}
			if(!$person){
				throw new Exception("缺少人數", 1);
			}
			if(!$name){
				throw new Exception("缺少預約客姓名", 1);
			}
			if(!$phone){
				throw new Exception("缺少預約客電話", 1);
			}
			if(!is_null(BlackList::where('name', $name)->where('phone', $phone)->where('status', 1)->first())){
				throw new Exception("系統錯誤", 1);
			}

			$service = Service::where('id', $service_id)->first();
			$end_time = $end_time->add(new DateInterval("PT".$service->time."M"));

			$service_provider_id_list = explode(",", $service_provider_id);

			$service_provider_list = ServiceProvider::with(['leaves' => function ($query) use ($start_time, $end_time) {
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>', $start_time);
			}])->with(['orders' => function ($query) use ($start_time, $end_time) {
				$query->whereNotIn('status', [3,4,6]);
			    $query->where('start_time', '<', $end_time);
			    $query->where('end_time', '>',$start_time);
			}])->whereIn('id', $service_provider_id_list)->get();
			
			foreach ($service_provider_list as $key => $service_provider) {
				if($service_provider->leaves->count() > 0){
					throw new Exception($service_provider->name."號 該時段請假 請重新選擇", 1);
				}
				if($service_provider->orders->count() > 0){
					throw new Exception($service_provider->name."號 該時段已有約 請重新選擇", 1);
				}
			}

			$room = Room::whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
				$query->whereNotIn('status', [3,4,6]);
			    $query->where('start_time', '<=', $end_time);
			    $query->where('end_time', '>=', $start_time);
			})->where('shop_id', $shop_id)->where('person', '>=', $person);
			
			if($shower == "true"){
				$room = $room->where('shower', 1)->orderBy('person', 'asc');
			}
			else if($shower != "true" && ($service_id == 2 || $service_id == 4)){
				$room = $room->orderBy('service_2', 'desc');
			}
			else{
				$room = $room->orderBy('service_1', 'desc');
			}

			$room = $room->first();
			
			if(!$room){
				throw new Exception("該時段房間已滿 請重新選擇", 1);
			}

			$order = new Order;
			$order->name = $name;
			$order->phone = $phone;
			$order->status = 1;
			$order->person = $person;
			$order->service_id = $service_id;
			$order->room_id = $room->id;
			$order->shop_id = $shop_id;
			$order->start_time = $start_time;
			$order->end_time = $end_time;
			$order->save();

			foreach ($service_provider_list as $key => $service_provider) {
				$service_provider->orders()->save($order);
			}

			return response()->json('預約成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_order_list(Request $request)
	{
		try{
			$name = $request->name;
			$phone = $request->phone;
			$orders = Order::with('service')->with('shop')->with('serviceProviders')->where('start_time', '>', date('Y-m-d H:i:s'))->where('name', $name)->where('phone', $phone)->whereIn('status', [1,2])->get();
			$order_list = [];
			foreach($orders as $order){
				$service_provider = "";
				foreach ($order->serviceProviders as $serviceProvider) {
					$service_provider .= " $serviceProvider->name";
				}
				$order_list[] = ['shop' => $order->shop->name, 'service' => $order->service->title, 'person' => $order->person, 'start_time' => $order->start_time, 'service_provider' => $service_provider];
			}
			return response()->json($order_list);
		}
		catch(Exception $e){
			return response()->json([]);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json([]);
		}
	}

	public function api_order_customer_cancel(Request $request)
	{
		try{
			$order_id = $request->order_id;
			$name = $request->name;
			$phone = $request->phone;
			$order = Order::where('id', $order_id)->where('phone', $phone)->where('name', $name)->first();
			$order->status = 3;
			$order->save();
			return response()->json('預約取消成功!', 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	private function no_specific($order_list, $service_providers){
		$person = 0;
		foreach ($order_list as $order) {
			$no_limit = $order->person - $order->service_providers_count;
			if($no_limit > 0){
				foreach ($service_providers as $service_provider) {
					if($service_provider->select != true){
						$flag = ServiceProvider::whereHas('orders' ,function ($query) use ($order) {
								    $query->whereNotIn('status', [3,4,6]);
								    $query->where('start_time', '<', $order->end_time);
								    $query->where('end_time', '>', $order->start_time);
								})->where('id', $service_provider->id)->first();
						if($flag == null){
							$service_provider->select = true;
							$no_limit--;
						}
					}
					if($no_limit == 0) break;
				}
			}
			$person += $no_limit;
		}
		return $person;
	}
}