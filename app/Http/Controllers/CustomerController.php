<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Customer;
use DB;

class CustomerController extends Controller
{
//
    public function ManageCustomer(Request $request)
    {

        $adata = Customer::where('profile_status', 1)->get();
        $bdata = Customer::where('profile_status', 0)->get();
        return view('customer.Manage_Customer', ['adata' => $adata, 'bdata' => $bdata]);
    }

    public function blockcustomer(Request $request)
    {
        if($request->input('_token')== null){
            
            $taxi_id=$request->input('data_id');
            $check = DB::table('wy_ride')->where('customer_id','=',$taxi_id)->where('ride_status','=',1)->count();
            if($check > 0){
                return response()->json([
                'Response' => 'Customer is in ride',
                'Status' => 'Failure'
            ]);
            }
            DB::table('wy_customer')->where('id', '=',$taxi_id)->update(['profile_status' => 0]);
			$header = array();
			$header[] = 'Content-Type: application/json';
			$postdata = '{"status":"true"}';
			$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_customer/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
            return response()->json([
                'Response' => 'Customer Successfully Deactivated',
                'Status' => 'Success'
            ]);
        }
    }
    
    public function activatecustomer(Request $request)
    {
        if($request->input('_token')== null){
            
            $taxi_id=$request->input('data_id');
            DB::table('wy_customer')->where('id', '=',$taxi_id)->update(['profile_status' => 1]);
			
			$header = array();
			$header[] = 'Content-Type: application/json';
			$postdata = '{"status":"false"}';
			$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_customer/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
			
            return response()->json([
                'Response' => 'Customer Successfully Activated',
                'Status' => 'Success'
            ]);
        }
    } 

    public function bulkblockcustomer(Request $request)
    {
        if($request->input('_token')== null){
            
            
            $taxi_id=$request->input('id');
        
           DB::table('wy_customer')->where('id', '=',$taxi_id)->update(['profile_status' => 0]);
		   
			$header = array();
			$header[] = 'Content-Type: application/json';
			$postdata = '{"status":"true"}';
			$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_customer/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
			
            return response()->json([
                'id' => $taxi_id,
                'Response' => 'Selected customers blocked Successfully',
                'Status' => 'bulkactivatecustomer'
            ]);
        }
    }
    
    public function bulkactivatecustomer(Request $request)
    {
        if($request->input('_token')== null){
            
            
            $taxi_id=$request->input('id');
        
           DB::table('wy_customer')->where('id', '=',$taxi_id)->update(['profile_status' => 1]);
		   
		   $header = array();
			$header[] = 'Content-Type: application/json';
			$postdata = '{"status":"false"}';
			$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_customer/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
			
            return response()->json([
                'id' => $taxi_id,
                'Response' => 'Selected customers activated Successfully',
                'Status' => 'bulkactivatecustomer'
            ]);
        }
    }
}
