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
class StaffController extends Controller
{
	const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
	public function index()
	{
		$shops = Shop::all();
		$view_data['shops'] = $shops;
		return view('staff/index', $view_data);
		
	}

	public function api_order(Request $request)
	{
		dd($request);
	}

	public function api_check_status(Request $request){
		
		$shop_id = $request->shop_id;

		$shop = Shop::where('id', $shop_id)->first();

		$start_time = new DateTime($request->time);
		$end_time = new Datetime($request->time);

		//開店時間根據早的時間需會變化
		$month = $start_time->format('Y-m');
		$date = new DateTime($start_time->format('Y-m-d'));
		$shop_start_time = new DateTime($date->format('Y-m-d')."T".$shop->start_time);
		if($shop_start_time > $start_time){
			$date->sub(new DateInterval("P1D"));
		}
		
		//計算師傅兩小時
		$end_time->add(new DateInterval("PT120M"));
		
		//2h以上 ok的師傅名單
		$service_providers = ServiceProvider::whereHas('shifts' ,function ($query) use ($month) {
		    $query->where('month', $month);
		})->with(['shifts' => function ($query) use ($month) {
		    $query->where('month', $month);
		}])->whereDoesntHave('leaves' ,function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->get();

		$service_providers_2h = [];
		foreach ($service_providers as $service_provider) {
			$shift = $service_provider->shifts->first();
			$on_duty = new DateTime($date->format('Y-m-d')." ".$shift->start_time);
			$off_duty =  new DateTime($date->format('Y-m-d')." ".$shift->end_time);
			if($off_duty < $on_duty){
				$off_duty->add(new DateInterval("P1D"));
			}
			// echo $on_duty->format('Y-m-d H:i:s')."	".$off_duty->format('Y-m-d H:i:s').$start_time->format('Y-m-d H:i:s')."	".$end_time->format('Y-m-d H:i:s')."<br>";
			if($on_duty <= $start_time && $off_duty >= $end_time){
				$service_providers_2h[] = $service_provider;
			}
		}

		//2h以上房間名單
		$rooms = Room::whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
		    $query->where('start_time', '<=', $end_time);
		    $query->where('end_time', '>=', $start_time);
		})->where('shop_id', $shop_id)->get();
		
		$rooms_2h = [];
		foreach ($rooms as $room){
			if(!in_array($room, $rooms_2h)){
				$rooms_2h[] = $room;
			}
		}

		//扣一小
		$end_time->sub(new DateInterval("PT60M"));
		//1h以上 ok的師傅名單
		$service_providers = ServiceProvider::whereHas('shifts' ,function ($query) use ($month) {
		    $query->where('month', $month);
		})->with(['shifts' => function ($query) use ($month) {
		    $query->where('month', $month);
		}])->whereDoesntHave('leaves' ,function ($query) use ($start_time, $end_time) {
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
		    $query->where('start_time', '<', $end_time);
		    $query->where('end_time', '>', $start_time);
		})->where('shop_id', $shop_id)->get();

		$service_providers_1h = [];
		foreach ($service_providers as $service_provider) {
			$shift = $service_provider->shifts->first();
			$on_duty = new DateTime($date->format('Y-m-d')." ".$shift->start_time);
			$off_duty =  new DateTime($date->format('Y-m-d')." ".$shift->end_time);
			if($off_duty < $on_duty){
				$off_duty->add(new DateInterval("P1D"));
			}
			if($on_duty <= $start_time && $off_duty >= $end_time){
				if(!in_array($service_provider, $service_providers_2h)){
					$service_providers_1h[] = $service_provider;
				}
			}
		}

		$rooms = Room::whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
			$query->where('status', '!=', 3);
			$query->where('status', '!=', 4);
		    $query->where('start_time', '<=', $end_time);
		    $query->where('end_time', '>=', $start_time);
		})->where('shop_id', $shop_id)->get();
		
		$rooms_1h = [];
		foreach ($rooms as $room){
			if(!in_array($room, $rooms_2h)){
				$rooms_1h[] = $room;
			}
		}

		$i = 0;
		$service_provider_status = [];
		foreach ($service_providers_1h as $service_provider) {
			$service_provider_status[] = ["index" => $i, "info" => $service_provider->name."號(一小時)", "id" => $service_provider->id];
			$i++;
		}
		foreach ($service_providers_2h as $service_provider) {
			$service_provider_status[] = ["index" => $i, "info" => $service_provider->name."號(二小時)", "id" => $service_provider->id];
			$i++;
		}
		$i = 0;
		$room_status = [];
		foreach ($rooms_1h as $room) {
			$room_status[] = ["index" => $i, "info" => $room->name."號(一小時)", "id" => $room->id];
			$i++;
		}
		foreach ($rooms_2h as $room) {
			$room_status[] = ["index" => $i, "info" => $room->name."號(二小時)", "id" => $room->id];
			$i++;
		}
		
		$result['service_provider_status'] = $service_provider_status;
		$result['room_status'] = $room_status;


		return response()->json($result, 200,  self::headers, JSON_UNESCAPED_UNICODE);
	}
}