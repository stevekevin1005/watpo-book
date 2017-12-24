<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlackList;
use Hash, Exception;
use App\Models\ServiceProvider;
use App\Models\Shop;
use App\Models\Order;

class CalenderController extends Controller
{
	

	public function index(Request $request, $shop_id)
	{
		$blackList = BlackList::paginate(10);
		$view_data['blackList'] = $blackList;
		$view_data['shop_id'] = $shop_id;

		return view('admin.calender.index', $view_data);
	}

	public function api_shop_calender(Request $request, $shop_id)
	{	
		try{
			$date = date('Y-m-d');
			$result['today'] = $date;

			$shop = Shop::where('id', $shop_id)->first();
			$service_providers = ServiceProvider::where('shop_id', $shop_id)->get();

			$result['start_time'] = $shop->start_time;


			$start_time = strtotime($date.' '.$shop->start_time);

			$result['service_providers'] = [];
			foreach ($service_providers as $key => $service_provider) {
				$result['service_providers'][] = ['id'=> $service_provider->id, 'title'=>$service_provider->name];
			}

			$orders = Order::with('serviceProviders')->where('start_time', '>=', date("Y/m/d H:i:s", $start_time))->where('status', '!=', 4)->get();

			$result['orders'] = [];
			
			$i = 1;
			foreach ($orders as $key => $order) {
				foreach ($order->serviceProviders as $key => $serviceProvider) {
					
					switch ($order->status) {
						case 1:
							$color = "royalblue";
							break;
						case 2:
							$color = "khaki";
							break;
						case 3:
							$color = "indianred";
							break;
						case 5:
							$color = "lime";
							break;	
						default:
							$color = "royalblue";
							break;
					}

					$result['orders'][] = ['id'=>$i ,'data-id'=>$order->id, 'resourceId'=>$serviceProvider->id, 'start'=>$order->start_time, 'end'=>$order->end_time, 'title'=>$order->name, 'phone'=>$order->phone, 'person'=>$order->person, 'color'=> $color];
					$i++;
				}
			}

			return response()->json($result, 200);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}
}