<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Shop;
use App\Models\ServiceProvider;
use Hash, Exception;
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
			if($request->start_time >= $request->end_time){
				throw new Exception("結束時間不能小於開始時間");
			}
			if(is_null(Leave::where('service_provider_id', $request->service_provider_id)->where('start_time', '<=', $request->end_time)->where('end_time', '>=', $request->start_time)->first())){
				$leave = new Leave;
				$leave->service_provider_id = $request->service_provider_id;
				$leave->start_time	= $request->start_time;
				$leave->end_time = $request->end_time;
				$leave->save();
				Log::create(['description' => '設置 '.$leave->ServiceProvider()->first()->name.'('.$leave->ServiceProvider()->first()->shop()->first()->name.') 休假 開始時間:'.$leave->start_time.' 結束時間:'.$leave->end_time]);
				return redirect()->back();
			}
			else{
				throw new Exception("此時間區間已有休假");
			}
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