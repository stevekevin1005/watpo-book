<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash, Exception, DB;
use App\Models\Shop;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Models\Order;
use App\Models\Room;
class BookController extends Controller
{
	
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
	public function api_time_list(Request $request)
	{
		try{

			$date = $request->date;
			$shop_id = $request->shop_id;
			$service_id = $request->service_id;
			if(!$date){
				throw new Exception("缺少日期", 1);
			}
			if(!$shop_id){
				throw new Exception("缺少店家ID", 1);
			}
			if(!$service_id){
				throw new Exception("缺少服務ID", 1);
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
				$time_list[$i]['detail'] = $this->time_option($start_time, $service->time, $service->shower,$shop_id);
				$start_time = strtotime("+30 min", $start_time); 
				$i++;
			}

			return response()->json($time_list, 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
			
	}
	private function time_option($datetime, $service_time, $shower,$shop_id){
		
		$start_time = $datetime;
		$end_time = strtotime("+".$service_time." min", $start_time);

		$service_providers = ServiceProvider::with(['leaves' => function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', date("Y/m/d H:i:s", $end_time));
		    $query->where('end_time', '>', date("Y/m/d H:i:s", $start_time));
		}])->with(['orders' => function ($query) use ($start_time, $end_time) {
				$query->where('status', '!=', 3);
				$query->where('status', '!=', 4);
		    $query->where('start_time', '<', date("Y/m/d H:i:s", $end_time));
		    $query->where('end_time', '>', date("Y/m/d H:i:s", $start_time));
		}])->where('shop_id', $shop_id)->get();
		
		$result['service_provider_list'] = null;

		foreach($service_providers as $service_provider){
			if($service_provider->leaves->count() == 0){
				if($service_provider->orders->count() == 0){
					$result['service_provider_list'][] = ['id' => $service_provider->id, 'name' => $service_provider->name, 'shop_id' => $service_provider->shop_id];
				}
			}
		}
		$rooms = Room::with(['orders' => function ($query) use ($start_time, $end_time) {
				$query->where('status', '!=', 3);
				$query->where('status', '!=', 4);
		    $query->where('start_time', '<', date("Y/m/d H:i:s", $end_time));
		    $query->where('end_time', '>', date("Y/m/d H:i:s", $start_time));
		}])->where('shop_id', $shop_id);
		if($shower == 2){
			$rooms = $rooms->where('shower', 1);
		}
		$rooms = $rooms->orderBy("shower", "asc")->get();
		$result['room'] = null;
		foreach($rooms as $room){
			if($room->orders->count() == 0){
				$result['room'][] = ['id' => $room->id, 'shower' => $room->shower, 'shop_id' => $room->shop_id, 'person' => $room->person];
			}
		}
		return $result;
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
			if(!$service_provider_id){
				throw new Exception("缺少師傅ID", 1);
			}
			if(!$name){
				throw new Exception("缺少預約客姓名", 1);
			}
			if(!$phone){
				throw new Exception("缺少預約客電話", 1);
			}

			$service_provider = ServiceProvider::where('id', $service_provider_id)->first();
			
			if(is_null($service_provider->leaves()->where('start_time', '<', $end_time)->where('end_time', '>', $start_time)->first())){
				if(is_null($service_provider->orders()->where('start_time', '<', $end_time)->where('end_time', '>', $start_time)->first())){
					$room = Room::where('id', $room_id)->first();
					if(is_null($room->orders()->where('start_time', '<', $end_time)->where('end_time', '>', $start_time)->first())){
						$order = new Order;
						$order->name = $name;
						$order->phone = $phone;
						$order->status = 1;
						$order->service_id = $service_id;
						$order->room_id = $room_id;
						$order->service_provider_id = $service_provider_id;
						$order->start_time = $start_time;
						$order->end_time = $end_time;
						$order->save();
					}
					else{
						throw new Exception("該時段房間已有預訂 請重新選擇", 1);
					}
				}
				else{
					throw new Exception("該師傅該時段已有約", 1);
				}
			}
			else{
				throw new Exception("該師傅該時段請假", 1);
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
}