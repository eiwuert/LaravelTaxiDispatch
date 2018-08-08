<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CarController extends Controller
{
    //CarController 
    public function AddTaxi(Request $request){

    	return view('car.Add_Taxi');
    }

    public function ManageTaxi(Request $request){

    	return view('car.Manage_Taxi');
    }

    public function AddBrand(Request $request){

    	return view('car.Add_Brand');
    }

    public function AddCarType(Request $request){

    	return view('car.Add_Car_Type');
    }

    public function AddModel(Request $request){

    	return view('car.Add_Model');
    }

    public function ManageBrand(Request $request){

    	return view('car.Manage_Brand');
    }

    public function ManageModel(Request $request){

    	return view('car.Manage_Model');
    }

    public function ManageType(Request $request){

    	return view('car.Manage_Type');
    }

    public function InsertBrand(){

        $d = 'siva';
        echo $d;
    }

}
