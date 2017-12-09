<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlackList;
use Hash, Exception;

class CalenderController extends Controller
{
	

	public function index()
	{
		$blackList = BlackList::paginate(10);
		$view_data['blackList'] = $blackList;

		return view('admin.calender.index', $view_data);
	}

}