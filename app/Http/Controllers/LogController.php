<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;

class LogController extends Controller
{
	

	public function index()
	{
		$view_data = [];

		return view('admin.log.index', $view_data);
	}



}