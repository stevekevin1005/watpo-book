<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;

class BookController extends Controller
{
	

	public function index()
	{
		$view_data = [];

		return view('book', $view_data);
	}



}