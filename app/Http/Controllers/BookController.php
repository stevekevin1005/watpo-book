<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;
use App\Models\Shop;
use App\Models\Service;
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
		$date = "2017/10/01";
		$shop_id = 1;
		$service_id = 1;

		$shop = Shop::where('id', $shop_id)->first();
		
		$start_time = strtotime($date.' '.$shop->start_time); //將時間的字串形式轉成時間戳記
		$end_time = strtotime($date.' '.$shop->end_time);

		if($end_time <= $start_time){
			$end_time = strtotime("+1 day", $end_time);
		}

		while($start_time <= $end_time){
			echo date("Y-m-d H:i:s", $start_time)."<br>"; //輸出成你要的格式
	
			$start_time = strtotime("+30 min", $start_time); //指定要對這個時間的加減計算，第一個參數字串可參考官網
		}
	}

	private function time_option($datetime, $service_time){

	}
}