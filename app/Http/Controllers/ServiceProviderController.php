<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Log;
use App\Models\ServiceProvider;
use Hash, Exception, Datetime, DateInterval;

class ServiceProviderController extends Controller
{
	const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
	public function index()
	{
		$shops = Shop::all();
		$view_data['shops'] = $shops;

		return view('admin.service_provider.list', $view_data);
	}

	public function api_list(Request $request)
	{
		try{
			$serviceProviders = ServiceProvider::where('shop_id', $request->id)->get();
			$response['serviceProviders'] = $serviceProviders;
			return response()->json($response, 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_add(Request $request)
	{
		try{
			$serviceProvider = new ServiceProvider;
			$serviceProvider->name = $request->name;
			$serviceProvider->shop_id	 = $request->id;
			$serviceProvider->save();
			Log::create(['description' => '增加師傅id '.$serviceProvider->id]);
			return response()->json('新增成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_delete(Request $request)
	{
		try{
			
			$serviceProvider = new ServiceProvider;
			$serviceProvider = $serviceProvider->where('id', $request->id)->first();
			$serviceProvider->delete();
			Log::create(['description' => '刪除師傅id '.$serviceProvider->id]);
			return response()->json('刪除成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_leave(Request $request)
	{
		try{
			$date = $request->date;
			$shop_id = $request->shop_id;
			$shop = Shop::where('id', $shop_id)->first();
			if($date == date('Y:m:d')){
				$shop_start_time = new DateTime(date('Y:m:d H:i:s'));
			}
			else{
				$shop_start_time = new DateTime($date.' '.$shop->start_time);
			}
			
			$shop_end_time = new DateTime($date.' '.$shop->end_time);

			if($shop_end_time <= $shop_start_time){
				$shop_end_time->add(new DateInterval("P1D"));
			}

			$serviceProviders = ServiceProvider::with(['leaves' => function ($query) use($shop_start_time, $shop_end_time) {
    			$query->where('start_time', '<=', $shop_end_time);
    			$query->where('end_time', '>=', $shop_start_time);
    		}])->where('shop_id', $shop_id)->get();
			
			$result['serviceProviders'] = [];

			foreach ($serviceProviders as $serviceProvider) {
				if(count($serviceProvider->leaves) == 0 ){
					$result['serviceProviders'][] = ['id' => $serviceProvider->id, 'name' => $serviceProvider->name, 'leave_id' => null, 'leave_status' => '無'];
				}
				else{
					$result['serviceProviders'][] = ['id' => $serviceProvider->id, 'name' => $serviceProvider->name, 'leave_id' => $serviceProvider->leaves[0]->id, 'leave_status' => '有', 'leave_start_time' => date("H:i", strtotime($serviceProvider->leaves[0]->start_time)), 'leave_end_time' => date("H:i", strtotime($serviceProvider->leaves[0]->end_time))];
				}
			}

			return response()->json($result, 200, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_service(Request $request)
	{
		try {
			$id = $request->id;
			$service = $request->service;

			$serviceProvider = ServiceProvider::where('id', $id)->first();
			switch ($service) {
				case 1:
					$serviceProvider->service_1 = !$serviceProvider->service_1;
					break;

				case 2:
					$serviceProvider->service_2 = !$serviceProvider->service_2;
					break;

				case 3:
					$serviceProvider->service_3 = !$serviceProvider->service_3;
					break;
				default:
					break;
			}
			$serviceProvider->save();
			Log::create(['description' => '更改師傅id '.$serviceProvider->id.' 服務 '.$service]);
			return response()->json("ok", 200, self::headers, JSON_UNESCAPED_UNICODE);
		} catch (Exception $e) {
			return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

}