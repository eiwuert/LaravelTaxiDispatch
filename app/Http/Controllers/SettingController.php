<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class SettingController extends Controller
{
    //
	
	public function index(){
		$driver_allocation = DB::table('wy_driver_allocation')
                    ->whereIn('id', [1])->first();
		return view('setting.index',['driver_allocation'=>$driver_allocation]);
	}
	
	public function update_status(Request $request)
    {
		$userid =Auth::user()->id;
        $rules = [
				'assign_driver_allocation'    		 => 'required',
			];
			//Define the validtion messgae for the rule
			$messages = [
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
            }
			DB::table('wy_driver_allocation')
            ->where('id', 1)
            ->update(['ad_allocation' => $request->input('assign_driver_allocation')]);
				
			Session::flash('message', trans('Assign Driver AllocationSuccessfully Updated'));
			return redirect('/setting');
	}
}
