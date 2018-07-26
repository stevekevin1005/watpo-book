<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception, Excel;
use App\Models\Account;
use App\Models\Log;

class LogController extends Controller
{
	public function index(Request $request)
	{
		$log_list = new Log;
		$account_list = Account::all();
		if($request->account_id){
			$log_list = $log_list->where('account_id', $request->account_id);
		}
		if($request->description){
			$log_list = $log_list->where('description', 'LIKE', '%'.$request->description.'%');
		}
		if($request->start_time){
			$log_list = $log_list->where('created_at', ">=", $request->start_time);
		}
		if($request->end_time){
			$log_list = $log_list->where('created_at', "<=", $request->end_time);
		}

		$view_data = [];
		$view_data['account_list'] = $account_list;
		$view_data['request'] = $request;
		$view_data['log_list'] = $log_list->with('account')->orderBy('created_at','desc')->paginate(15);

		return view('admin.log.index', $view_data);
	}

	public function export(Request $request)
	{	
		$log_list = new Log;

		if($request->account_id){
			$log_list = $log_list->where('account_id', $request->account_id);
		}
		if($request->description){
			$log_list = $log_list->where('description', 'LIKE', '%'.$request->description.'%');
		}
		if($request->start_time){
			$log_list = $log_list->where('created_at', ">=", $request->start_time);
		}
		if($request->end_time){
			$log_list = $log_list->where('created_at', "<=", $request->end_time);
		}
		$log_list = $log_list->with('account')->orderBy('created_at','desc')->get();

		return Excel::create('操作記錄', function($excel) use ($log_list){
	    $excel->sheet('操作記錄', function($sheet) use ($log_list){
	    	$fromArrayData[] = [ "帳號", "描述", "時間"];
	    	foreach ($log_list as $key => $log) {
	    		$fromArrayData[] = [ $log->account['account'], $log->description, $log->created_at];
	    	}
	    	$sheet->fromArray($fromArrayData);
	    });
		})->export('xlsx');
	}
}