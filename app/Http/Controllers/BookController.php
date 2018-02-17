<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash, Exception, DB;
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

			if($service_id == 1 || $service_id == 2){
				$service_providers = $service_providers->where('service_1', true);
				$rooms = $rooms->where('service_1', true);
			}
			else if($service_id == 3 || $service_id == 4){
				$service_providers = $service_providers->where('service_2', true);
				$rooms = $rooms->where('service_2', true);
			}
			else{
				$service_providers = $service_providers->where('service_3', true);
				$rooms = $rooms->where('service_3', true);
			}
			$service_providers = $service_providers->get();
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

			$date = $request->date;
			$shop_id = $request->shop_id;
			$service_id = $request->service_id;
			$person = $request->person;
			$service_provider_id = $request->service_provider_id;
			$room_id = $request->room_id;

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
			if(!$service_provider_id){
				throw new Exception("缺少師傅ID", 1);
			}
			if(!$room_id){
				throw new Exception("缺少房間ID", 1);
			}



			$service = Service::where('id', $service_id)->first();
			$shop = Shop::where('id', $shop_id)->first();
			
			$start_time = strtotime($date.' '.$shop->start_time);
			$end_time = strtotime($date.' '.$shop->end_time);
			if($end_time <= $start_time){
				$end_time = strtotime("+1 day", $end_time);
			}
			$i = 0;
			while($start_time <= $end_time){
				$time_list[$i]['time'] = date("H:i:s", $start_time);
				$time_list[$i]['select'] = $this->time_option($date, $start_time, $service->time, $service->shower, $shop_id, $person, $service_provider_id, $room_id);
				$start_time = strtotime("+30 min", $start_time); 
				$i++;
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
	private function time_option($date, $start_time, $service_time, $shower,$shop_id, $person, $service_provider_id, $room_id){
		
		$month = date("Y-m", strtotime($date));

		$end_time = strtotime("+".$service_time." min", $start_time);

		$service_provider_id_list = explode(",", $service_provider_id);

		
		$service_providers = ServiceProvider::whereHas('shifts' ,function ($query) use ($month) {
		    $query->where('month', $month);
		})->with(['shifts' => function ($query) use ($month) {
		    $query->where('month', $month);
		}])->whereDoesntHave('leaves' ,function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', date("Y/m/d H:i:s", $end_time));
		    $query->where('end_time', '>', date("Y/m/d H:i:s", $start_time));
		})->whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
		    $query->where('start_time', '<', date("Y/m/d H:i:s", $end_time));
		    $query->where('end_time', '>', date("Y/m/d H:i:s", $start_time));
		})->where('shop_id', $shop_id)->get();

		$service_provider_list = [];
		foreach($service_providers as $service_provider){
			$shift = $service_provider->shifts->first();
			$on_duty = strtotime($date." ".$shift->start_time);
			$off_duty = strtotime($date." ".$shift->end_time);
			if($off_duty < $on_duty){
				$off_duty = strtotime("+1 day", $start_time);
			}
			
			if($on_duty <= $start_time && $off_duty >= $end_time){
				$service_provider_list[] = $service_provider->id;
			}
		}

		if(!empty(array_diff($service_provider_id_list, $service_provider_list))){
			return false;
		}

		if(count($service_provider_list) < $person){
			return false;
		}

		$room = Room::whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
		    $query->where('start_time', '<', date("Y/m/d H:i:s", $end_time));
		    $query->where('end_time', '>', date("Y/m/d H:i:s", $start_time));
		})->where('id', $room_id)->first();

		if(!$room){
			return false;
		}

		return true;
	}

	public function api_order(Request $request){
		try{
			$shop_id = $request->shop_id;
			$start_time = $request->start_time;
			$end_time = $request->end_time;
			$room_id = $request->room_id;
			$service_id = $request->service_id;
			$person = $request->person;
			$service_provider_id = $request->service_provider_id;
			$name = $request->name;
			$phone = $request->phone;
			if(!$start_time){
				throw new Exception("缺少開始時間", 1);
			}
			if(!$end_time){
				throw new Exception("缺少結束時間", 1);
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
			if(!$person){
				throw new Exception("缺少人數", 1);
			}
			if(!$name){
				throw new Exception("缺少預約客姓名", 1);
			}
			if(!$phone){
				throw new Exception("缺少預約客電話", 1);
			}
			if(!is_null(BlackList::where('name', $name)->where('phone', $phone)->first())){
				throw new Exception("系統錯誤", 1);
			}
			$service_provider_id_list = explode(",", $service_provider_id);

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
			$order->person = $person;
			$order->service_id = $service_id;
			$order->room_id = $room_id;
			$order->shop_id = $shop_id;
			$order->start_time = $start_time;
			$order->end_time = $end_time;
			$order->save();

			foreach ($service_provider_list as $key => $service_provider) {
				$service_provider->orders()->save($order);
			}

			return response()->json('預約成功', 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

	public function api_order_list(Request $request)
	{
		try{
			$name = $request->name;
			$phone = $request->phone;
			$order_list = Order::where('start_time', '>', date('Y-m-d H:i:s'))->where('name', $name)->where('phone', $phone)->where('status', 1)->get();
			
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
			return response()->json('預約取消成功!', 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}
}