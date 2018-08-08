<?php

namespace App\Http\Controllers;

if(!defined('FIREBASE_API_KEY')) define("FIREBASE_API_KEY", "AIzaSyAPbEBHf_6y0oZbMuGM3H3c_TyI7NPY8wU");
if(!defined('FIREBASE_FCM_URL')) define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");

use Illuminate\Http\Request;
use App\CarBrand;
use App\model1;
use App\CarModel;
use App\CarType;
use App\Car;
use App\RideType;
use App\Country;
use App\OffersNotification;
use App\VehicleCategory;
use App\State;
use App\Ride;
use App\City;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use App\Offers;
class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     public function __construct()
     {
		    $this->car=new Car();
      }
      
    public function index(Request $request)
    {
     
        $ActiveOffers = Offers::where('is_experied','=',0)
        ->orderBy('id', 'DESC')
        ->get();
        $ExpiredOffers = Offers::where('is_experied','=',1)->get();
        
        return view('offers.manage_offers',['activeoffers' => $ActiveOffers,'expiredoffers' => $ExpiredOffers]);
    }

    public function view_offers(Request $request,$id)
    {
     
        $Offers = Offers::where('id','=',$id)->first();
		    
        return view('offers.view_offers',['offers' => $Offers]);
    }


    public function send_gcm_notify($reg_id, $message,$ride_id) {

	 $fields = array(
	  'to' => $reg_id ,
	  'priority' => "high",
	  'notification' => array( "tag"=>"chat", "body" => $message,"ride_id"=> $ride_id),
	 );
	 // echo "<br>";
	 //json_encode($fields);
	 //echo "<br>"; 

	 $headers = array(
	  'Authorization: key=' . FIREBASE_API_KEY,
	  'Content-Type: application/json'
	 );
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_URL, FIREBASE_FCM_URL);
	 curl_setopt($ch, CURLOPT_POST, true);
	 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

	 $result = curl_exec($ch);
	 if ($result === FALSE) {
	  die('Problem occurred: ' . curl_error($ch));
	 }
	 curl_close($ch);
	 //echo $result;
     Session::flash('message', trans('Offer Successfully Created'));
                return redirect('/manage_offers');

	}


	public function apns_cus($devicetoken,$message,$rideid){
		 $key = '';
		//$batch = intval($count);
		 $payload['aps'] = array('alert' => $message, 'sound' => 'default','badge' => 0,'notify_key'=>$key,'rideid'=>$rideid);
		 $payload = json_encode($payload);
		 //print_r($payload);
		 $apnsHost = 'gateway.sandbox.push.apple.com';
		 $apnsPort = 2195;
		 $apnsCert = 'WrydesPartner_Dev.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
		 $options = array('ssl' => array(
		 'local_cert' => 'WrydesPartner_Dev.pem',
		 'passphrase' => 'armor'
		 ));
		 $streamContext = stream_context_create();
		 stream_context_set_option($streamContext, $options);
		 $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
		 $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
		 fwrite($apns, $apnsMessage);
		 fclose($apns);
		}



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ride_type = RideType::all();
        $car_type = CarType::all();
		return view('offers.add_offers',['car_type' => $car_type,'ride_type' => $ride_type]);
    }


    public function getvehicletype(Request $request)
    {

    	$id = $request->data_id;
    	$VehicleType = CarType::where('ride_category','=',$id)->get();
    	$list=array();
    	foreach($VehicleType as $val){
        $list []=array(
        'id'=>$val->id,
        'car_type'=>$val->car_type,
        'car_board'=>$val->car_board
        );
    }
    return json_encode($list);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        //$data = Offers::find($request->CouponCode);
        $data = DB::table('wy_offers')->where('coupon_code','=',$request->CouponCode)->get();
        $dc = count($data);
        
        if($dc != 0)
        {
            Session::flash('error_offers', 'Coupon code already exist');
                return redirect('/add_offers')->withInput();
        }

        if(strtotime($request->ValidFrom) > strtotime($request->ValidTo)){
            Session::flash('error_offers', trans('Date invalid'));
            return back()->withInput();
        }

        $CouponCategory = $request->CouponCategory;
        if(!$CouponCategory){
            Session::flash('error_offers', 'Select Coupon category');
            return back()->withInput();
        }
        
        if($CouponCategory == 4){

            $offers = new Offers();
            $offers->coupon_basedon = 4;
            $offers->coupon_code = $request->CouponCode;
            $offers->coupon_type = $request->CouponType;
            $offers->coupon_typevalue = $request->CouponValue;
            $offers->coupon_value = $request->CouponValue;
            $offers->coupon_desc = $request->CouponDescription;
            $offers->usage_count = 1;
            $offers->valid_from = date('Y-m-d H:i:s',strtotime($request->ValidFrom));
            $offers->valid_to = date('Y-m-d H:i:s',strtotime($request->ValidTo));
            $offers->is_experied = 0;
            $offers->save();
            $LastId = $offers->id;
            $user_info = DB::table('wy_customer')->get();
             foreach ($user_info as $user ) {

                //echo $user->customer_id; exit;
                    $notification = new OffersNotification();
                    $notification->offer_id = $LastId;
                    $notification->user_id = $user->id;
                    $notification->coupon_code = $request->CouponCode;
                    //$notification->usage_count = $CouponUsageCount;
                    $notification->save();
                    //$notification->offer_id = $LastId;
                   

             }
            Session::flash('message', trans('Offer Successfully Created'));
                return redirect('/manage_offers');exit;
        }
    	// Get All form data's submitted
   		if($CouponCategory == 6){

            $offers = new Offers();
            $offers->coupon_basedon = 6;
            $offers->coupon_code = $request->CouponCode;
            $offers->coupon_type = 3;
            $offers->coupon_typevalue = 0;
            $offers->coupon_value = $request->CouponValue;
            $offers->coupon_desc = $request->CouponDescription;
            $offers->usage_count = 1;
            $offers->valid_from = date('Y-m-d H:i:s',strtotime($request->ValidFrom));
            $offers->valid_to = date('Y-m-d H:i:s',strtotime($request->ValidTo));
            $offers->is_experied = 0;
            $offers->save();
            $LastId = $offers->id;
        }


    	if($CouponCategory == 3)
    	{
    		
    		$VehicleCategory = $request->VehicleCategory;
    		$Data = $request->VehicleType;
    	}
    	else
    	{
    		$Data = $request->Value;
    	}
        $CouponCode = $request->CouponCode;
        if($CouponCategory != 6){

            $Value = $request->Value;
        $Car = $request->Car;
        $CouponCode = $request->CouponCode;
        
        $CouponType = $request->CouponType;
        $CouponValue = $request->CouponValue;
        $CouponDescription = $request->CouponDescription;
        $CouponUsageCount = $request->CouponUsageCount;
        $ValidFrom = $request->ValidFrom; 
        $ValidTo = $request->ValidTo;
         
        // Creates an object for Offers model
        $Offers = new Offers();
        $Offers->coupon_basedon = $CouponCategory; 
        $Offers->coupon_typevalue = $Data;  
        $Offers->coupon_code = $CouponCode;
        $Offers->coupon_type = $CouponType;
        $Offers->coupon_value = $CouponValue;
        $Offers->coupon_desc = $CouponDescription;
        $Offers->usage_count = $CouponUsageCount;
        $ValidFromDate = date('Y-m-d H:i:s',strtotime($ValidFrom));
        $ValidToDate = date('Y-m-d H:i:s',strtotime($ValidTo));
        $Offers->valid_from = $ValidFromDate;
        $Offers->valid_to = $ValidToDate;
        $Offers->is_experied = 0;
        $Offers->save();

        $LastId = $Offers->id;
        }
    	
    	if($CouponCategory == 1)
    	{
    		$user_info = DB::table('wy_ride')
             ->select('customer_id', DB::raw('count(*) as total'))
             ->groupBy('customer_id')
             ->where('ride_status','=',4)
             ->get();
            foreach ($user_info as $user ) {
            	
            	if($user->total >= $Value)
            	{
            		//echo $user->customer_id; exit;
            		$notification = new OffersNotification();
            		$notification->offer_id = $LastId;
            		$notification->user_id = $user->customer_id;
            		$notification->coupon_code = $CouponCode;
            		//$notification->usage_count = $CouponUsageCount;
            		$notification->save();
            		//$notification->offer_id = $LastId;
            		// $message = "Message";
            		// $notifyid = DB::table('wy_customer')
		            //  ->select('device_type','device_token')
		            //  ->where('id','=',$user->customer_id)
		            //  ->first();
		         //     if($notifyid->device_type == 1)
		         //     {
		         // //     	if($notifyid->device_token!='' & $notifyid->device_token!='null'){
					      // //  apns_cus($notifyid->device_token, $message,$LastId);
					      // // }
		         //     }
		         //     if($notifyid->device_type == 2)
		         //     {
		         //     	return $this->send_gcm_notify($notifyid->device_token, $message,$LastId);
		         //     }
            	}
            }    
    	}
    	else
    	{
        
    	if($CouponCategory == 2)
    	{
    		/* $user_info = DB::table('wy_ride')
             ->select('customer_id')
             ->select(DB::raw("SUM(final_amount) as counts"))
             ->having('counts','>=',$Value)
			 ->groupBy('customer_id')
             //->distinct('customer_id')
             ->get(); */
			 // $user_info = DB::raw('select SUM(final_amount) as counts,customer_id from `wy_ride` group by `customer_id` having `counts` >= '.$Value.'');

             $user_info = Ride::select('customer_id', DB::raw('sum(final_amount) AS balance'))
                     ->groupBy('customer_id')
                     ->get();
                     
             foreach ($user_info as $user ) {
            	
            	if($user->balance >= $Value){
            		//echo $user->customer_id; exit;
            		$notification = new OffersNotification();
            		$notification->offer_id = $LastId;
            		$notification->user_id = $user->customer_id;
            		$notification->coupon_code = $CouponCode;
            		//$notification->usage_count = $CouponUsageCount;
            		$notification->save();
            		//$notification->offer_id = $LastId;
            		$message = "Message";
            		$notifyid = DB::table('wy_customer')
		             ->select('device_type','device_token')
		             ->where('id','=',$user->customer_id)
		             ->first();
		             
                 }
            	
            }    
             
    	}
    	else
    	{
    		$user_info = DB::table('wy_customer')
             ->select('id')
             ->get();
              foreach ($user_info as $user ) {

              	$notification = new OffersNotification();
        		$notification->offer_id = $LastId;
        		$notification->user_id = $user->id;
        		$notification->coupon_code = $CouponCode;
        		//$notification->usage_count = $CouponUsageCount;
        		$notification->save();

              }
    	}
    }
    	Session::flash('message', trans('Offer Successfully Created'));
				return redirect('/manage_offers');
    }

    public function expireoffer(Request $request)
    {
      
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');

            //$delete1 = DB::table('wy_driver')->where('id', '=',$taxi_id)->update(['profile_status' => 2]);
            $delete1 = DB::table('wy_offers')->where('id', '=', $taxi_id)->update(['is_experied' => 1]);
            $delete1 = DB::table('wy_offernotification')->where('offer_id', '=', $taxi_id)->update(['is_experied' => 1]);
            return response()->json([
                'Response' => 'Offer Successfully Deactivated',
                'Status' => 2
            ]);
        }


    }

    public function activateoffer(Request $request)
    {
      
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');

            //$delete1 = DB::table('wy_driver')->where('id', '=',$taxi_id)->update(['profile_status' => 2]);
            $delete1 = DB::table('wy_offers')->where('id', '=', $taxi_id)->update(['is_experied' => 0]);
            //$delete1 = DB::table('wy_offers')->where('id', '=', $taxi_id)->update(['is_experied' => 0]);
            return response()->json([
                'Response' => 'Offer Successfully Deactivated',
                'Status' => 2
            ]);
        }


    }

    public function deleteoffer(Request $request)
    {
      
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');

            //$delete1 = DB::table('wy_driver')->where('id', '=',$taxi_id)->update(['profile_status' => 2]);
            $delete1 = DB::table('wy_offers')->where('id', '=', $taxi_id)->delete();
            return response()->json([
                'Response' => 'Offer Successfully Deleted',
                'Status' => 2
            ]);
        }


    }

    public function store(Request $request)
    {
        $userid =Auth::user()->id;
        $rules = [
				'taxi_no'    		 => 'required',
				'taxi_brand'          => 'required',
				'taxi_model'          => 'required',
				'taxi_model'          => 'required',
				'taxi_capacity'         => 'required|numeric',
				'country'    		 => 'required',
				'state'          => 'required',
				//'fare_below_minkm'    => 'required',
				'city'          => 'required',
				'rc_book_image'          => 'required|mimes:jpeg,jpg,png',
				'rc_number'         => 'required|alpha_num|max:20',
				'insurance_image'    	=> 'required|mimes:jpeg,jpg,png',
				'insurance_expiry_date'       => 'required',
			
		    
			];
			//Define the validtion messgae for the rule
			$messages = [
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
            }
			

		//taxi Number exists or not 
			$rcbook_status = Car::Where('car_no','=',$request->input('taxi_no'))->get();
			if (count($rcbook_status)>0) {
					return back()->withInput()
							->with('error_status','Taxi number has already Exists');
            }
			
			//check RC book exists or not 
			$rcbook_status = Car::Where('rc_no','=',$request->input('rc_number'))->get();
			if (count($rcbook_status)>0) {
					return back()->withInput()
							->with('error_status','RC book number has already Exists');
            }
			
        //  store value
            $car    = new Car;
			$car->ride_category=$request->input('ride_category');
			$car->car_no = $request->input('taxi_no');
			$car->capacity	=$request->input('taxi_capacity');
			$car->brand=$request->input('taxi_brand');
			$car->model =$request->input('taxi_model');
			$car->car_type = $request->input('taxi_type');
            $car->rc_no = $request->input('rc_number');
            
            $car->insurance_expiration_date =date("Y-m-d",strtotime($request['insurance_expiry_date']));
			
            
             if ($request->file('insurance_image')->isValid()) {
                $ins_file = $request->file('insurance_image');
                $extension = $request->file('insurance_image')->getClientOriginalExtension(); // getting image extension
                $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                $path = public_path('uploads/insurance/' . date('Ymd'));
                $ins_file->move($path,$ins_filename);
                $car->insurance_image='/uploads/insurance/' . date('Ymd')."/".$ins_filename;
            }
            
             if ($request->file('rc_book_image')->isValid()) {
                $rc_file = $request->file('rc_book_image');
                $extension = $request->file('rc_book_image')->getClientOriginalExtension(); // getting image extension
                $rc_filename = rand(11111,99999).'.'.$extension; // renameing image
                $rcpath = public_path('uploads/rc_book/' . date('Ymd'));
                $rc_file->move($rcpath,$rc_filename);
                $car->rc_image='/uploads/rc_book/' . date('Ymd')."/".$rc_filename;
            }
            
   			$car->city =$request->input('city');
			$car->state =$request->input('state');
			$car->country=$request->input('country');
			$car->car_attached=1;
  	         $car->status = 0;
			$car->created_by =$userid;
			$car->created_at = date("Y-m-d H:i:s"); 
			$car->updated_by =$userid;
			$car->updated_at = date("Y-m-d H:i:s"); 
			$car->save();
           	
			Session::flash('message', trans('Taxi Successfully Created'));
				return redirect('/taxi');
     
        //
    }
  //edit taxi
  
  	public function edit($taxiid=""){
		$taxidetails=Car::find($taxiid);
	    	if(count($taxidetails)==0){
				return redirect('/taxi');
			}
		$ride_category = VehicleCategory::all();
        $countrylist=Country::all();
		
		 $vehicle_type=$taxidetails->ride_category;
		if(old('taxi_type')!=""){
			$vehicle_type=old('taxi_type');
		}

		$carmodel=CarModel::where('ride_category','=',$vehicle_type)
								->where('status', 1)
								->get();
        $cartype= CarType::where('ride_category','=',$vehicle_type)
								->where('status', 1)
								->get();
        $carbrand= CarBrand::where('status', 1)->get();
  
		return view('car.edit-taxi', ['ride_category'=>$ride_category ,'taxidetails' => $taxidetails,'carbrand' => $carbrand,'carmodel' => $carmodel,'cartype' => $cartype,'country_list'=>$countrylist]);
	}
    
    public function update(Request $request){
	$userid =Auth::user()->id;
      $rules = [
				'taxi_no'    		 => 'required',
				'taxi_brand'          => 'required',
				'taxi_model'          => 'required',
				'taxi_type'          => 'required',
				'taxi_capacity'         => 'required|numeric',
				'country'    		 => 'required',
				'state'          => 'required',
				//'fare_below_minkm'    => 'required',
				'city'          => 'required',
				'rc_book_image'          => 'mimes:jpeg,jpg,png',
				'rc_number'         => 'required|alpha_num|max:20',
				'insurance_image'    	=> 'mimes:jpeg,jpg,png',
				'insurance_expiry_date'       => 'required',
			
		    
			];
			//Define the validtion messgae for the rule
			$messages = [
			
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
            }
		
		
			//taxi Number exists or not 
			$carno_status = Car::Where('car_no','=',$request->input('taxi_no'))
			->Where('id','!=',$request->input('taxi_id'))->get();
		
			if (count($carno_status)>0) {
					return back()->withInput()
							->with('error_status','Taxi number has already Exists');
            }
			//check RC book exists or not 
			//echo $request->input('taxi_no');
			
			$rcbook_status = Car::Where('rc_no','=',$request->input('rc_number'))
			->Where('id','!=',$request->input('taxi_id'))->get();
			if (count($rcbook_status)>0) {
					return back()->withInput()
							->with('error_status','RC book number has already Exists');
            }
				
        //  store value
           	$car = Car::find($request->input('taxi_id'));
	       	$car->car_no = $request->input('taxi_no');
			$car->ride_category=$request->input('ride_category');
			$car->capacity	=$request->input('taxi_capacity');
			$car->brand=$request->input('taxi_brand');
			$car->model =$request->input('taxi_model');
			$car->car_type = $request->input('taxi_type');
			$car->rc_no = $request->input('rc_number');
            
            $car->insurance_expiration_date =date("Y-m-d",strtotime($request['insurance_expiry_date']));
			
            if($request->file('insurance_image')!=null){
                 if ($request->file('insurance_image')->isValid()) {
                    $ins_file = $request->file('insurance_image');
                    $extension = $request->file('insurance_image')->getClientOriginalExtension(); // getting image extension
                    $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('/uploads/insurance/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car->insurance_image='/uploads/insurance/' . date('Ymd')."/".$ins_filename;
                }
            }
            if($request->file('rc_book_image')!=null){
             if ($request->file('rc_book_image')->isValid()) {
                $rc_file = $request->file('rc_book_image');
                $extension = $request->file('rc_book_image')->getClientOriginalExtension(); // getting image extension
                $rc_filename = rand(11111,99999).'.'.$extension; // renameing image
                $rcpath = public_path('/uploads/rc_book/' . date('Ymd'));
                $rc_file->move($rcpath,$rc_filename);
                $car->rc_image='/uploads/rc_book/' . date('Ymd')."/".$rc_filename;
            }
            }
            
   			$car->city =$request->input('city');
			$car->state =$request->input('state');
			$car->country=$request->input('country');
			$car->car_attached=1;
  	       $car->updated_by =$userid;
			$car->updated_at = date("Y-m-d H:i:s");
			$car->save();
           	
			Session::flash('message', trans('Taxi Successfully Created'));
				return redirect('/taxi');
    }
    
    
    //view the texi details
    public function show($taxi_id){
        
            $taxidetails=Car::find($taxi_id);
        	return view('car.view-taxi',['taxi_details'=>$taxidetails]);

    }
    
  //get the state list based on country
  public function getstatelist(Request $request){
    
    $statelist = State::where('country_id','=',$request->input('data_id')) ->get();
    return json_encode($statelist);
  }
  
  //get city list based on state id
  public function getcitylist(Request $request){
    
   $statelist = City::where('state_id','=',$request->input('data_id')) ->get();
    $list=array();
    foreach($statelist as $val){
        $list []=array(
        'id'=>$val->id,
        'city'=>$val->name
        );
    }
    return json_encode($list);
  }
  
  //Taxi status move into block into non-active
	public function ajax_block_nonactive_taxi(Request $request){
		$status=0;
		if($request->input('_token')== null){
				$taxi_id=$request->input('data_id');
				$this->car->change_status($taxi_id,$status);
			return response()->json([
				'Response' => 'Vehicle successfully change into unassigned status',
				'Status' => 'Success'
			]);
		}
	}
 
   //Taxi status move into non-active into block
	public function ajax_nonactive_block_taxi(Request $request){
		$status=-1;
		if($request->input('_token')== null){
			 $taxi_id=$request->input('data_id');
			$this->car->change_status($taxi_id,$status);
			return response()->json([
				'Response' => 'Vehicle successfully change into Blocked',
				'Status' => '2'
			]);
		}
	} 
	//Taxi status move into active into non-active
	public function ajax_nonactive_taxi(Request $request){
		$status=0;
		if($request->input('_token')== null){
			 $taxi_id=$request->input('data_id');
		
		$taxi_Status= DB::table('wy_ridedetails')
            ->join('wy_ride', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
			->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
			->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
			->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
			->join('wy_cartype', 'wy_cartype.id', '=', 'wy_ride.car_type')
			->whereIn('wy_ridedetails.ride_status', array(0,1, 2, 3))
			->whereIn('wy_ridedetails.accept_status', array(0,1))
			->where('wy_assign_taxi.car_num', '=',$taxi_id)
			->select('wy_ridedetails.driver_id')
         	->get();
			if(count($taxi_Status) >0){
				return response()->json([
					'Response' => 'Driver/Taxi is in a ride.Try after Sometime',
					'Status' => '1'
				]);
			}
			$this->car->change_status($taxi_id,$status);
			return response()->json([
				'Response' => 'Vehicle Successfully unassigned',
				'Status' => '2'
			]);
		}
	}
//bulk data change status
	public function change_bulk_status(Request $request){
		if($request->input('_token')== null){
				$taxi_list=$request->input('curdata');
				$curstatus=$request->input('curstatus');
				foreach($taxi_list as $taxi_id){
					$this->car->change_status($taxi_id,$curstatus);
					if($curstatus==0){
						$taxi_Status= DB::table('wy_ridedetails')
						->join('wy_ride', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
						->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
						->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
						->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
						->join('wy_cartype', 'wy_cartype.id', '=', 'wy_ride.car_type')
						->whereIn('wy_ridedetails.ride_status', array(0,1, 2, 3))
						->whereIn('wy_ridedetails.accept_status', array(0,1))
						->where('wy_assign_taxi.car_num', '=',$taxi_id)
						->select('wy_ridedetails.driver_id')
						->get();
						if(count($taxi_Status) == 0){
							$this->car->change_status($taxi_id,$curstatus);
						}
					}
				}
				if($taxi_list == 0){
					$response_status='Taxi Successfully Added Non-Active Taxi';
				}else{
					$response_status='Taxi Successfully In Blocked Taxi';
				}
			return response()->json([
				'Response' => $response_status,
				'Status' => 'Success'
			]);
		}
	}
	
 //********* GET MODEL AND TYPE LIST BASED VEHICLE CATEGORY**********//

	public function getmodel_type_list(Request $request){
		//if($request->input('_token')== null){
	
		  $model_list = CarModel::where('ride_category','=',$request->input('category_id'))
								->where('status', 1)
								->get();
			$model=array();
			$type=array();
			foreach($model_list as $val){
				$model []=array(
				'id'=>$val->id,
				'model'=>$val->model
				);
			}
			$model_list = CarType::where('ride_category','=',$request->input('category_id'))->where('status', 1)->get();

			foreach($model_list as $val){
				$type []=array(
				'id'=>$val->id,
				'car_type'=>$val->car_type
				);
			}
			$datalist=array('model_list'=>$model,'type_list'=>$type);
			return json_encode($datalist);
		
	} 
	  
  
}
