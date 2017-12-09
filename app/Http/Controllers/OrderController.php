<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;

class OrderController extends Controller
{
	

	public function index()
	{
		$view_data = [];

		return view('admin.order.index', $view_data);
	}



}