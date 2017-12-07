<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlackList;
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

			return redirect('/admin/blacklist/list');
		}
		catch(Exception $e){
			return redirect('/admin/blacklist/list')->withErrors(['fail'=>'新增失敗 請洽系統管理商']);
		}
	}

	public function delete(Request $request)
	{
		try{
			
			$blackList = new BlackList;
			$blackList = $blackList->where('id', $request->id)->first();
			$blackList->delete();

			return redirect('/admin/blacklist/list');
		}
		catch(Exception $e){
			return redirect('/admin/blacklist/list')->withErrors(['fail'=>'刪除失敗 請洽系統管理商']);
		}
	}

}