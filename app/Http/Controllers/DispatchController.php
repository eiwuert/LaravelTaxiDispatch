<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DispatchController extends Controller
{
    //
    public function AddDispatcher(Request $request){

    	return view('dispatcher.Add_Dispatcher');
    }

    public function ManageDispatcher(Request $request){

    	return view('dispatcher.Manage_Dispatcher');
    }


}
