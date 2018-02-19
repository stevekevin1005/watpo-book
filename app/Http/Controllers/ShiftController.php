<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Shop;
use App\Models\ServiceProvider;
use Hash, Exception;
use App\Models\Log;

class ShiftController extends Controller
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
		return view('admin.shift.index', $view_data);
	}

	public function api_list(Request $request)
	{
		$headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
		try{
			$shop_id = $request->shop_id;
			$month = $request->month;

			$shop = Shop::where('id', $shop_id)->first();

			$serviceProviders = ServiceProvider::where('shop_id', $shop_id)->with(['shifts' => function($query) use ($month){
				$query->where('month', $month);
			}])->get();

			foreach ($serviceProviders as $key => $serviceProvider) {
				if(count($serviceProvider->shifts ) > 0){
					$serviceProvider->start_time = $serviceProvider->shifts[0]['start_time'];
					$serviceProvider->end_time = $serviceProvider->shifts[0]['end_time'];
				}
				else{
					$serviceProvider->start_time = $shop->start_time;
					$serviceProvider->end_time = $shop->end_time;
				}
			}

			$response['serviceProviders'] = $serviceProviders;
			$response['month'] = $month;
			return response()->json($response, 200, $headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json('系統錯誤 請洽系統管理商', 400, $headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function update(Request $request)
	{
		try{
			$shifts = $request->shifts;
			$month = $request->month;
			foreach ($shifts as $key => $shift) {
				if($shift['start_time'] != '' && $shift['end_time'] != ''){
					$db_shift = Shift::firstOrNew(['month' => $month, 'service_provider_id' => $shift['id']]);
					$db_shift->start_time = $shift['start_time'];
					$db_shift->end_time = $shift['end_time'];
					$db_shift->save();
				}
			}
			return redirect()->back()->with('status', '更新 '.$month.' 排班成功');;
		}
		catch(Exception $e){
			return redirect()->back()->withErrors(['fail'=> $e->getMessage()]);
		}
	}

}