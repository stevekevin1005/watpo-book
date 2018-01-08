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
		$log_list = Log::where('account_id', $request->account_id);
		$account_list = Account::all();
		if($request->start_time){
			$log_list = $log_list->where('created_at', ">=", $request->start_time);
		}

		if($request->end_time){
			$log_list = $log_list->where('created_at', "<=", $request->end_time);
		}

		$view_data = [];
		$view_data['account_list'] = $account_list;
		$view_data['request'] = $request;
		$view_data['log_list'] = $log_list->paginate(15);
		return view('admin.log.index', $view_data);
	}

	public function export(Request $request)
	{
		$log_list = Log::where('account_id', $request->account_id);
		$account = Account::where('id', $request->account_id)->first();
		if($request->start_time){
			$log_list = $log_list->where('created_at', ">=", $request->start_time);
		}

		if($request->end_time){
			$log_list = $log_list->where('created_at', "<=", $request->end_time);
		}
		$log_list = $log_list->get();
		return Excel::create($account->account.'操作記錄', function($excel) use ($log_list){
	    $excel->sheet('操作記錄', function($sheet) use ($log_list){
	    	$fromArrayData[] = [ "描述", "時間"];
	    	foreach ($log_list as $key => $log) {
	    		$fromArrayData[] = [ $log->description, $log->created_at];
	    	}
	    	$sheet->fromArray($fromArrayData);
	    });
		})->export('xlsx');
	}
}