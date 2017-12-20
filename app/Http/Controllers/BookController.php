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
				$time_list[$i]['detail'] = $this->time_option($start_time, $service->time , $shop_id);
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

	private function time_option($datetime, $service_time, $shop_id){
		
		$service_providers = ServiceProvider::where('shop_id', $shop_id)->get();
		$start_time = $datetime;
		$end_time = strtotime("+".$service_time." min", $start_time);
		
		$result['service_provider_list'] = null;
		foreach($service_providers as $service_provider){
			if(is_null($service_provider->leaves()->where('start_time', '<', date("Y/m/d H:i:s", $end_time))->where('end_time', '>', date("Y/m/d H:i:s", $start_time))->first())){
				if(is_null($service_provider->orders()->where('start_time', '<', date("Y/m/d H:i:s", $end_time))->where('end_time', '>', date("Y/m/d H:i:s", $start_time))->first())){
					$result['service_provider_list'][] = $service_provider;
				}
			}
		}

		$rooms = Room::where('shop_id', $shop_id)->get();
		$result['room'] = null;
		foreach($rooms as $room){
			if(is_null($room->orders()->where('start_time', '<', date("Y/m/d H:i:s", $end_time))->where('end_time', '>', date("Y/m/d H:i:s", $start_time))->first())){
				if($room->shower){
					$result['room']['shower'][$room->person][] = $room;
				}
				else{
					$result['room']['normal'][$room->person][] = $room;
				}
			}
		}

		return $result;
	}

	public function api_order(Request $request){
		try{
			$shop_id = $request->shop_id;
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}
}