<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlackList;
use App\Models\Log;
use Hash, Exception; 

class BlackListController extends Controller
{
	

	public function index()
	{
		$blackList = BlackList::paginate(10);
		$view_data['blackList'] = $blackList;

		return view('admin.black_list.list', $view_data);
	}

	

	public function add(Request $request)
	{
		try{
			$blackList = new BlackList;
			$blackList->name = $request->name;
			$blackList->phone	 = $request->phone;
			$blackList->save();
			Log::create(['description' => '新增 名字:'.$request->name." 電話:".$request->phone." 黑名單"]);
			return redirect('/admin/blacklist/list');
		}
		catch(Exception $e){
			return redirect('/admin/blacklist/list')->withErrors(['fail'=>'系統錯誤 請洽系統管理商']);
		}
		catch(\Illuminate\Database\QueryException $e){
			return  redirect('/admin/blacklist/list')->withErrors(['fail'=>'資料庫錯誤 請洽系統管理商']);
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