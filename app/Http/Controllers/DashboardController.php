<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;

class DashboardController extends Controller
{
	

	public function index()
	{
		$view_data = [];

		return view('admin.dashboard.index', $view_data);
	}



}