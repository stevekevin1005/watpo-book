<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlackList;
use App\Models\Log;
use Hash, Exception; 

class BlackListController extends Controller
{
	const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
	public function index(Request $request)
	{
		$blackList = new BlackList;
		$name = $request->name;
		$phone = $request->phone;
		$description = $request->description;
		$blackList = $blackList->where('status', 1);

		if($name){
			$blackList = $blackList->where('name', 'Like', '%'.$name.'%');
		}
		if($phone){
			$blackList = $blackList->where('phone', $phone);
		}
		if($description){
			if($description == "逾時"){
				$blackList = $blackList->whereNull('description');
			}
			else{
				$blackList = $blackList->where('description', $description);
			}
		}

		$blackList = $blackList->paginate(10);
		$view_data['blackList'] = $blackList;
		$view_data['request'] = $request;
		return view('admin.black_list.list', $view_data);
	}

	

	public function api_add(Request $request)
	{
		try{
			$blackList = new BlackList;
			$blackList = $blackList->firstOrNew(['name' => $request->name, 'phone' => $request->phone]);

			if ($blackList->exists) {
			    $blackList->description = $request->description;
			    Log::create(['description' => '更改黑名單 名字:'.$request->name." 電話:".$request->phone." 描述:".$request->description." 黑名單"]);
			} else {
				$blackList->status = 0;
			    $blackList->description = $request->description;
			    Log::create(['description' => '新增黑名單 名字:'.$request->name." 電話:".$request->phone." 描述:".$request->description." 黑名單"]);
			}

			$blackList->status = 1;
			$blackList->save();

			return response()->json("success" , 200,  self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400,  self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json($e->getMessage(), 400,  self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function api_search(Request $request){
		try{
			$blackList = BlackList::where('phone', $request->phone)->first();
			
			$result['overtime'] = 0;
			$result['description'] = "";
			if($blackList){
				$result['status'] = $blackList->status == 1 ? true : false;
				$result['overtime'] = $blackList->overtime;
				$result['description'] = $blackList->description == null ? "" : $blackList->description;
			}
			else{
				$result['status'] = false;
			}

			return response()->json($result , 200,  self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400,  self::headers, JSON_UNESCAPED_UNICODE);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json($e->getMessage(), 400,  self::headers, JSON_UNESCAPED_UNICODE);
		}
	}

	public function delete(Request $request)
	{
		try{
			
			$blackList = new BlackList;
			$blackList = $blackList->where('id', $request->id)->first();
			$blackList->delete();
			Log::create(['description' => '移除 名字:'.$blackList->name." 電話:".$blackList->phone." 黑名單"]);
			return redirect('/admin/blacklist/list');
		}
		catch(Exception $e){
			return redirect('/admin/blacklist/list')->withErrors(['fail'=>'系統錯誤 請洽系統管理商']);
		}
		catch(\Illuminate\Database\QueryException $e){
			return  redirect('/admin/blacklist/list')->withErrors(['fail'=>'資料庫錯誤 請洽系統管理商']);
		}
	}

}