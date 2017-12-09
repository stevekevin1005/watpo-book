<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Hash;

class LoginController extends Controller
{
	public function index()
	{
		return view('admin.login');
	}

	public function loginCheck(Request $request)
	{
		$account = Account::where('account', $request->account)->first();

		if ($account != null && Hash::check($request->password, $account->password))
		{
		  $request->session()->put('account', $request->account);
		  return redirect('/admin/dashboard');
		}
		return redirect('/admin/login')->withErrors(['fail'=>'帳號或密碼錯誤']);
	}

	public function logout(Request $request)
	{
		$request->session()->flush();
		return redirect('/admin/login');
	}
}