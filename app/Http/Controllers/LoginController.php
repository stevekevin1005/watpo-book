<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\ServiceProvider;
use Hash;
use App\Models\Log;

class LoginController extends Controller
{
	public function index()
	{
		return view('admin.login');
	}

	public function staff_index()
	{
		return view('staff.login');
	}

	public function staffLoginCheck(Request $request)
	{
		$account = Account::where('account', $request->account)->first();

		if ($account != null && Hash::check($request->password, $account->password))
		{
			$request->session()->put('account', $request->account);
			$request->session()->put('account_id', $account->id);
			$request->session()->put('account_level', $account->level);
			if($account->level == 3){
				return redirect('/admin/logout');
			}
			Log::create(['description' => '登入櫃檯系統']);
			return redirect('/staff/index');
		}
		return redirect('/admin/login')->withErrors(['fail'=>'帳號或密碼錯誤']);
	}

	public function loginCheck(Request $request)
	{
		$account = Account::where('account', $request->account)->first();

		if ($account != null && Hash::check($request->password, $account->password))
		{
			$request->session()->flush();
			$request->session()->put('account', $request->account);
			$request->session()->put('account_id', $account->id);
			$request->session()->put('account_level', $account->level);
			if($account->level == 3){
				$request->session()->put('service_provider_id', $account->service_provider_id);
				$shop_id = ServiceProvider::where('id', $account->service_provider_id)->first()->shop_id;
				$request->session()->put('account_shop_id', $shop_id);
			}
			Log::create(['description' => '登入系統']);
			return redirect('/admin/dashboard');
		}
		return redirect('/admin/login')->withErrors(['fail'=>'帳號或密碼錯誤']);
	}

	public function logout(Request $request)
	{
		Log::create(['description' => '登出系統']);
		$request->session()->flush();
		return redirect('/admin/login');
	}
}