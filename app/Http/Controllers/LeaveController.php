<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Shop;
use App\Models\ServiceProvider;
use Hash, Exception, DateTime, DateInterval;
use App\Models\Log;

class LeaveController extends Controller
{
	const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');

	public function index(Request $request)
	{
		$shops = Shop::with(['serviceProviders' => function($query){
			$query->orderBy('name', 'asc');
		}])->get();

		$view_data['shops'] = $shops;
		if($request->shop_id && $request->service_provider_id){
			$shop_id = $request->shop_id;
			$view_data['shop_id'] = $shop_id;
			$view_data['service_provider_id'] = $request->service_provider_id;
			$leaves = Leave::where('service_provider_id', $request->service_provider_id)->get();
			$view_data['leaves'] = $leaves;

			
			$shop = Shop::where('id', $shop_id)->first();

			$start_time = new DateTime(date('Y-m-d').' '.$shop->start_time);

			$end_time = new DateTime(date('Y-m-d').' '.$shop->end_time);
			if($end_time <= $start_time){
				$end_time->add(new DateInterval("P1D"));
			}

			$view_data['start_time'] = $start_time->format("Y-m-d\TH:i");
			$view_data['end_time'] = $end_time->format("Y-m-d\TH:i");
			// dd($end_time);
		}
		
		return view('admin.leave.index', $view_data);
	}

	public function api_add(Request $request)
	{
		try{
			$now = new DateTime();
			
			$start_time = new DateTime($request->start_time);
			$end_time = new DateTime($request->end_time);
			//remove secound (ipod, iphone)
			$start_time->setTime($start_time->format("H"), $start_time->format("i"), 0);
			$end_time->setTime($end_time->format("H"), $end_time->format("i"), 0);

			if($start_time < $now){
				$start_time->add(new DateInterval("P1D"));
			}
			if($end_time < $now){
				$end_time->add(new DateInterval("P1D"));
			}

			$service_provider_id = $request->service_provider_id;

			if($start_time >= $end_time){
				throw new Exception("結束時間不能小於開始時間");
			}

			$service_provider = ServiceProvider::with('shop')->with(['leaves' => function ($query) use($start_time, $end_time) {
    			$query->where('start_time', '<=', $end_time);
    			$query->where('end_time', '>=', $start_time);
    		}])->where('id', $service_provider_id)->first();

			if($service_provider->leaves->count() > 0){
				throw new Exception("此時間區間已有休假");
			}

			$shop_daily_start_time = new DateTime($start_time->format('Y-m-d').$service_provider->shop->start_time);
			$shop_daily_end_time = new DateTime($start_time->format('Y-m-d').$service_provider->shop->end_time);
			if($shop_daily_end_time < $shop_daily_start_time){
				$shop_daily_end_time->add(new DateInterval("P1D"));
			}
			$leave_start = clone $start_time;
			$leave_end = clone $end_time;

			while (true) {

				$leave = new Leave;
				$leave->service_provider_id = $service_provider_id;
				$leave->start_time	= $leave_start;

				if($leave_end <= $shop_daily_end_time){
					$leave->end_time = $leave_end;
				}
				else{
					$leave->end_time = $shop_daily_end_time;
				}
				$leave->save();
				$leave_start = $shop_daily_start_time->add(new DateInterval("P1D"));
				$shop_daily_end_time->add(new DateInterval("P1D"));
				if($end_time < $leave_start) break;
			}
			Log::create(['description' => '設置 '.$leave->ServiceProvider()->first()->name.'('.$leave->ServiceProvider()->first()->shop()->first()->name.') 休假 開始時間:'.$start_time->format('Y-m-d H:i:s').' 結束時間:'.$end_time->format('Y-m-d H:i:s')]);
			return response()->json('新增成功', 200, self::headers, JSON_UNESCAPED_UNICODE);

		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_delete(Request $request)
	{
		try{
			
			$leave = new Leave;
			$leave = $leave->where('id', $request->id)->first();
			$leave->delete();
			Log::create(['description' => '刪除 '.$leave->ServiceProvider()->first()->name.'('.$leave->ServiceProvider()->first()->shop()->first()->name.') 休假 開始時間:'.$leave->start_time.' 結束時間:'.$leave->end_time]);
			return response()->json('刪除成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_list(Request $request, $service_provider_id)
	{
		try{
			$start_time = $request->start;
			$end_time = $request->end;
			$leaves = Leave::where('service_provider_id', $service_provider_id)->
							where('start_time', '>=', $start_time)->
							where('end_time', '<=', $end_time)
							->get();
			$result = null;
			foreach ($leaves as $leave) {
				$result[] = [
					'id' => $leave->id,
					'start' => date('Y-m-d', strtotime($leave->start_time)),
					'end' => date('Y-m-d', strtotime($leave->end_time)),
					'title' => date('H:i', strtotime($leave->start_time)).' - '.date('H:i', strtotime($leave->end_time)),
					'color' => 'green',
					'allDay' => true
				];
			}
			return response()->json($result, 200, self::headers, JSON_UNESCAPED_UNICODE);
		} 
		catch(Exception $e) {
			return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}
}