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
	public function index(Request $request)
	{
		$shops = Shop::all();
		$view_data['shops'] = $shops;
		if($request->shop_id && $request->service_provider_id){
			$view_data['shop_id'] = $request->shop_id;
			$view_data['service_provider_id'] = $request->service_provider_id;
			$leaves = Leave::where('service_provider_id', $request->service_provider_id)->get();
			$view_data['leaves'] = $leaves;
		}
		return view('admin.leave.index', $view_data);
	}

	public function add(Request $request)
	{
		try{
			$start_time = new DateTime($request->start_time);
			$end_time = new DateTime($request->end_time);
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

			$shop_daily_start_time = new DateTime($service_provider->shop->start_time);
			$shop_daily_end_time = new DateTime($service_provider->shop->end_time);
			
			$leave_start = new DateTime($request->start_time);
			$leave_end = new DateTime($request->start_time);

			while ($leave_end <= $end_time) {
				
				if($shop_daily_end_time > $shop_daily_start_time){
					$leave_end->setTime($shop_daily_end_time->format('H'), $shop_daily_end_time->format('i'), $shop_daily_end_time->format('s'));
				}
				else{
					$leave_end->add(new DateInterval("P1D"))->setTime($shop_daily_end_time->format('H'), $shop_daily_end_time->format('i'), $shop_daily_end_time->format('s'));
				}

				if($leave_end >= $end_time){
					$leave = new Leave;
					$leave->service_provider_id = $service_provider_id;
					$leave->start_time	= $leave_start;
					$leave->end_time = $end_time;
					$leave->save();
					
					Log::create(['description' => '設置 '.$leave->ServiceProvider()->first()->name.'('.$leave->ServiceProvider()->first()->shop()->first()->name.') 休假 開始時間:'.$leave->start_time->format('Y-m-d H:i:s').' 結束時間:'.$leave->end_time->format('Y-m-d H:i:s')]);
				}
				else{
					$leave = new Leave;
					$leave->service_provider_id = $service_provider_id;
					$leave->start_time	= $leave_start;
					$leave->end_time = $leave_end;
					$leave->save();
					$leave_start->add(new DateInterval("P1D"))->setTime($shop_daily_start_time->format('H'), $shop_daily_start_time->format('i'), $shop_daily_start_time->format('s'));

					Log::create(['description' => '設置 '.$leave->ServiceProvider()->first()->name.'('.$leave->ServiceProvider()->first()->shop()->first()->name.') 休假 開始時間:'.$leave->start_time->format('Y-m-d H:i:s').' 結束時間:'.$leave->end_time->format('Y-m-d H:i:s')]);
				}
			}
			
			return redirect()->back();

		}
		catch(Exception $e){
			return redirect()->back()->withErrors(['fail'=> $e->getMessage()]);
		}
	}

	public function api_delete(Request $request)
	{
		try{
			
			$leave = new Leave;
			$leave = $leave->where('id', $request->id)->first();
			$leave->delete();
			Log::create(['description' => '刪除 '.$leave->ServiceProvider()->first()->name.'('.$leave->ServiceProvider()->first()->shop()->first()->name.') 休假 開始時間:'.$leave->start_time.' 結束時間:'.$leave->end_time]);
			return response()->json('刪除成功');
		}
		catch(Exception $e){
			return response()->json('刪除失敗 請洽系統管理商');
		}
	}
}