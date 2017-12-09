<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;

class AccountController extends Controller
{
	

	public function index()
	{
		$view_data = [];

		return view('admin.account.index', $view_data);
	}



}