<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash, Exception;
use App\Models\Shop;

class BookController extends Controller
{
	

	public function index()
	{
		$view_data = [];

		return view('book', $view_data);
	}

	public function api_shop_list(Request $request)
	{
		try{
			$shop_list = Shop::All();
			
			return response()->json($shop_list);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

}