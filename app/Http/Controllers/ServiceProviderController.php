<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\ServiceProvider;
use Hash;

class ServiceProviderController extends Controller
{
	public function index()
	{
		$shops = Shop::all();
		$view_data['shops'] = $shops;

		return view('admin.service_provider.list', $view_data);
	}

	public function api_list(Request $request)
	{
		try{
			$serviceProviders = ServiceProvider::where('shop_id', $request->id)->get();
			$response['serviceProviders'] = $serviceProviders;
			return response()->json($response);
		}
		catch(Exception $e){
			return response()->json('系統獲取列表失敗 請洽系統管理商');
		}
	}

	public function api_add(Request $request)
	{
		try{
			$serviceProvider = new ServiceProvider;
			$serviceProvider->name = $request->name;
			$serviceProvider->shop_id	 = $request->id;
			$serviceProvider->save();

			return response()->json('新增成功');
		}
		catch(Exception $e){
			return response()->json('新增失敗 請洽系統管理商');
		}
	}

	public function api_delete(Request $request)
	{
		try{
			
			$serviceProvider = new ServiceProvider;
			$serviceProvider = $serviceProvider->where('id', $request->id)->first();
			$serviceProvider->delete();

			return response()->json('刪除成功');
		}
		catch(Exception $e){
			return response()->json('刪除失敗 請洽系統管理商');
		}
	}

}