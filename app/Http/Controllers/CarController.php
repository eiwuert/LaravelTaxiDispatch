<?php

namespace App\Http\Controllers;

use App\CarBrand;
use App\model1;
use App\CarModel;
use App\Driver;
use App\CarType;
use App\Car;
use App\AssignTaxi;
use App\Country;
use App\VehicleCategory;
use App\State;
use App\City;
use App\Franchise;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Session;
class CarController extends Controller
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
					$ride_category=$request->input('ride_category');
						$role=Session::get('user_role');
							switch($role)
						  {
						      case "1" : //ADMIN Dashboard
						         return view('car.manage_taxi',$this->admin_index($ride_category));
						         break;
						      case "3" : //Franchise Dashboard
						      	return view('car.manage_taxi',$this->franchise_index($ride_category));
						          break;
						      default :
						          return redirect('/');
						  }
			}
	
	//****** MANAGE VEHICLE FOR ADMIN ***********//
    public function admin_index($ride_category)
    {
    	
     	$franchise = Franchise::all();
		//echo $request->input('ride_category');exit;
		session(['cf_category' => $ride_category]); 
		if( (session('cf_category') !=NUll) && (session('cf_category') !=0)){
			$active_list=Car::where('wy_carlist.status','=', 1)
				->where('wy_carlist.car_attached','=', 1)
                ->where('ride_category','=',session('cf_category'))->get();
			$inactive_list=Car::where('wy_carlist.status','=', 0)
                ->where('ride_category','=',session('cf_category'))->get();
			$blocked_list=Car::where('wy_carlist.status','=', -1)
                ->where('ride_category','=',session('cf_category'))->get();
                 $driver = Driver::all();
		}else{
	
			$active_list=Car::join('wy_assign_taxi', 'wy_assign_taxi.car_num', '=', 'wy_carlist.id')
				->where('wy_carlist.status','=', 1)
				->where('wy_carlist.car_attached','=', 1)
				->where('wy_assign_taxi.status','=','1')
                ->get();
               
			$inactive_list=Car::where('wy_carlist.status','=', 0)
                ->get();
			$blocked_list=Car::where('wy_carlist.status','=', -1)
                ->get();
                $driver = Driver::all();
  
		}
		$ride_category = VehicleCategory::all();
			
        $vehicle_details=array('franchise'=>$franchise,'ride_category'=>$ride_category,
        	'driver'=>$driver,'active_list'=>$active_list,'inactive_list'=>$inactive_list,'blocked_list'=>$blocked_list);
  				return $vehicle_details;
    }


//**********MANAGE VEHICLE FOR Franchise****************//


 public function franchise_index($ride_category)
    {
    	
    	 //get the Franchise ID
				  $Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
				  $franchis_id=$Franchise[0]->id;
				  
     	$franchise = Franchise::all();
	
		session(['cf_category' => $ride_category]); 
		if( (session('cf_category') !=NUll) && (session('cf_category') !=0)){
			$active_list=Car::where('wy_carlist.status','=', 1)
									->where('wy_carlist.car_attached','=', 1)
				   				->where('wy_carlist.franchise_id','=',$franchis_id)
              	  ->where('ride_category','=',session('cf_category'))->get();
			$inactive_list=Car::where('wy_carlist.status','=', 0)
										->where('wy_carlist.franchise_id','=',$franchis_id)
                		->where('ride_category','=',session('cf_category'))->get();
			$blocked_list=Car::where('wy_carlist.status','=', -1)
										->where('wy_carlist.franchise_id','=',$franchis_id)
                		->where('ride_category','=',session('cf_category'))->get();
                 $driver = Driver::all();
		}else{
	
			$active_list=Car::join('wy_assign_taxi', 'wy_assign_taxi.car_num', '=', 'wy_carlist.id')
				->where('wy_carlist.status','=', 1)
				->where('wy_carlist.car_attached','=', 1)
				->where('wy_assign_taxi.status','=','1')
				->where('wy_carlist.franchise_id','=',$franchis_id)
                ->get();
               
			$inactive_list=Car::where('wy_carlist.status','=', 0)
										->where('wy_carlist.franchise_id','=',$franchis_id)
               			->get();
			$blocked_list=Car::where('wy_carlist.status','=', -1)
										->where('wy_carlist.franchise_id','=',$franchis_id)
                		->get();
                $driver = Driver::all();
            //echo json_encode($inactive_list); exit;
		}
		$ride_category = VehicleCategory::all();
	  
	   $vehicle_details=array('franchise'=>$franchise,'ride_category'=>$ride_category,
        	'driver'=>$driver,'active_list'=>$active_list,'inactive_list'=>$inactive_list,'blocked_list'=>$blocked_list);
  				return $vehicle_details;
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $franchise = Franchise::where('status','=',1)->get();
		$ride_category = VehicleCategory::all();
		$countrylist=Country::all();
        $countrylist=Country::all();
        $carbrand= CarBrand::where('status', 1)->get();
        $carmodel=CarModel::where('ride_category','=','1')
								->where('status', 1)
								->get();
        $cartype= CarType::where('ride_category','=','1')
								->where('status', 1)
								->get();
		    return view('car.add_taxi',['ride_category'=>$ride_category,'carbrand' => $carbrand,'carmodel' => $carmodel,'cartype' => $cartype,'franchise' => $franchise,'country_list'=>$countrylist]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid =Auth::user()->id;
        $rules = [
				'taxi_no'    		 => 'required|alpha_num',
				'taxi_brand'          => 'required',
				'taxi_model'          => 'required',
				'taxi_model'          => 'required',
				//'taxi_capacity'         => 'required|numeric',
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
					return back()->withErrors($validator);
            }
			

		//taxi Number exists or not 
			$rcbook_status = Car::Where('car_no','=',str_replace(' ','',$request->input('taxi_no')))->get();
			if (count($rcbook_status)>0) {
					return back()
							->with('error_status','Vehicle number has already Exists');
            }
			
			//check RC book exists or not 
			$rcbook_status = Car::Where('rc_no','=',$request->input('rc_number'))->get();
			if (count($rcbook_status)>0) {
					return back()
							->with('error_status','RC book number has already Exists');
            }

            // Get and upload vehicle image in vehiclepic directory and insert path in Database
           


        //  store value
            $car    = new Car;
			//$car->vehical_image=$imagename;
			$car->ride_category=$request->input('VehicleCategory');
			$car->car_no = str_replace(' ','',$request->input('taxi_no'));
			$car->capacity	= 0;
			$car->brand=$request->input('taxi_brand');
			$car->model =$request->input('taxi_model');
			$car->car_type = $request->input('VehicleType');
            $car->rc_no = $request->input('rc_number');
            
            $car->insurance_expiration_date =date("Y-m-d",strtotime($request['insurance_expiry_date']));
			
             if ($request->file('VehicleImage')->isValid()) {
                $ins_file = $request->file('VehicleImage');
                $extension = $request->file('VehicleImage')->getClientOriginalExtension(); 
                $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                $path = public_path('uploads/vehiclepic/' . date('Ymd'));
                $ins_file->move($path,$ins_filename);
                $car->vehical_image='/uploads/vehiclepic/' . date('Ymd')."/".$ins_filename;
            }
            
            
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
            
            if($request->franchise){
            	$car->franchise_id =$request->input('franchise');
            	$car->isfranchise =1;
            }
            else{
            	$car->franchise_id ='0';
            	$car->isfranchise ='0';
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
           	
			Session::flash('message', trans('Vehicle Successfully Created'));
				return redirect('/taxi');
     
        //
    }
  //edit taxi
  
  	public function edit($taxiid=""){

  		// Get carlist id from Assign taxi id
  		// $AssignTaxi = AssignTaxi::where('id','=',$taxiid)->first();
  		// $CarId = $AssignTaxi->car_num;
  		$franchise = Franchise::where('status','=',1)->get();
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
								->where('status','=', 1)
								->get();
        $carbrand= CarBrand::where('status', 1)->get();
  		
  		//print_r($taxidetails); exit;
		return view('car.edit-taxi', ['franchise'=>$franchise ,'ride_category'=>$ride_category ,'taxidetails' => $taxidetails,'carbrand' => $carbrand,'carmodel' => $carmodel,'cartype' => $cartype,'country_list'=>$countrylist]);
	}
    
    public function update(Request $request){
	$userid =Auth::user()->id;
      $rules = [
				'taxi_no'    		 => 'required',
				'taxi_brand'          => 'required',
				
				// 'taxi_type'          => 'required',
				//'taxi_capacity'         => 'required|numeric',
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
					return back()->withErrors($validator);
            }
		
			$ride_category = $request->ride_category;

			//taxi Number exists or not 
			$carno_status = Car::Where('car_no','=',$request->input('taxi_no'))
			->Where('id','!=',$request->input('taxi_id'))->get();
		
			if (count($carno_status)>0) {
					return back()
							->with('error_status','Vehicle number has already Exists');
            }
			//check RC book exists or not 
			//echo $request->input('taxi_no');
			
			$rcbook_status = Car::Where('rc_no','=',$request->input('rc_number'))
			->Where('id','!=',$request->input('taxi_id'))->get();
			if (count($rcbook_status)>0) {
					return back()
							->with('error_status','RC book number has already Exists');
            }
				
        //  store value
           	$car = Car::find($request->input('taxi_id'));
           	
           	if($request->Franchise == 1){
           		$car->isfranchise = 1;
           		$car->franchise_id = $request->franchise;
           	}
           	else{
           		$car->isfranchise = 0;
           		$car->franchise_id = 0;
           	}
	       	$car->car_no = $request->input('taxi_no');
			$car->ride_category= $ride_category;
			$car->capacity	= 0;
			$car->brand=$request->input('taxi_brand');
			//$car->model =$request->input('taxi_model');
			//$car->car_type = $request->input('taxi_type');
			$car->rc_no = $request->input('rc_number');
            
            $car->insurance_expiration_date =date("Y-m-d",strtotime($request['insurance_expiry_date']));
			
			if($request->file('vehicle_image')!=null){
                 if ($request->file('vehicle_image')->isValid()) {
                    $ins_file = $request->file('vehicle_image');
                    $extension = $request->file('vehicle_image')->getClientOriginalExtension(); // getting image extension
                     if(($extension != 'png') && ($extension != 'jpg'))
	                {
	                	return back()->withInput()
								->with('error_status','Vehicle image invalid');
	                }
                    $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('/uploads/vehiclepic/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car->vehical_image='/uploads/vehiclepic/' . date('Ymd')."/".$ins_filename;
                }
            }

            if($request->file('insurance_image')!=null){
                 if ($request->file('insurance_image')->isValid()) {
                    $ins_file = $request->file('insurance_image');
                    $extension = $request->file('insurance_image')->getClientOriginalExtension(); // getting image extension
                    if(($extension != 'png') && ($extension != 'jpg'))
	                {
	                	return back()->withInput()
								->with('error_status','Vehicle image invalid');
	                }
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
                if(($extension != 'png') && ($extension != 'jpg'))
	                {
	                	return back()->withInput()
								->with('error_status','Vehicle image invalid');
	                }
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
           	
			Session::flash('message', trans('Vehicle Successfully Updated'));
				return redirect('/taxi');
    }
    
    
    //view the texi details
    public function show($taxi_id){
        
        	$franchise = Franchise::all();
            $taxidetails=Car::find($taxi_id);
        	return view('car.view-taxi',['taxi_details'=>$taxidetails,'franchise'=>$franchise]);

    }
    
  
  
  //Taxi status move into block into non-active
	public function ajax_block_nonactive_taxi(Request $request){
		$status=0;
		if($request->input('_token')== null){
				$taxi_id=$request->input('data_id');
				$car = DB::table('wy_carlist')->where('id','=',$taxi_id)->first();
				$carbrand = DB::table('wy_brand')->where('id','=',$car->brand)->first();
				$carmodel = DB::table('wy_model')->where('id','=',$car->model)->first();
				$cartype = DB::table('wy_cartype')->where('id','=',$car->car_type)->first();
				if($carbrand->status == 0)
				{
					return response()->json([
						'Response' => 'brand is blocked',
						'Status' => 'Failure'
					]);	
				}
				if($carmodel->status == 0)
				{
					return response()->json([
						'Response' => 'carmodel is blocked',
						'Status' => 'Failure'
					]);	
				}
				if($cartype->status == 0)
				{
					return response()->json([
						'Response' => 'cartype is blocked',
						'Status' => 'Failure'
					]);	
				}
				$this->car->change_status($taxi_id,$status);
            // $date_fmt = date("d-m-Y");
            // $header = array();
            // $header[] = 'Content-Type: application/json';
            // $postdata = '{"status":true}';
            // $ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_status/$taxi_id.json");
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            // $result = curl_exec($ch);
            // curl_close($ch);
			return response()->json([
				'Response' => 'Vehicle Activated Successfully',
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
				'Response' => 'Vehicle Blocked Successfully',
				'Status' => '2'
			]);
		}
	} 
	//Taxi status move into active into non-active
	public function ajax_nonactive_taxi(Request $request){
		$status=-1;
		if($request->input('_token')== null){
			 $taxi_id=$request->input('data_id');
		
		$taxi_Status= DB::table('wy_ridedetails')
            ->join('wy_ride', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
			->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
			->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
			->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
			->join('wy_cartype', 'wy_cartype.id', '=', 'wy_ride.car_type')
			->whereIn('wy_ridedetails.ride_status', array(0, 1, 2, 3))
			->whereIn('wy_ridedetails.accept_status', array(0, 1))
			->where('wy_assign_taxi.car_num', '=',$taxi_id)
			->select('wy_ridedetails.driver_id')
         	->get();

        $driver = DB::table('wy_assign_taxi')->where('car_num','=',$taxi_id)->first();
        $driver_id = $driver->driver_id;

        $check5 = DB::table('wy_ridedetails')
                ->where('driver_id','=',$driver_id)
                ->whereIn('ride_status',array(0, 1, 2, 3))
                ->whereIn('accept_status',array(0,1))->count();

        if($check5 != 0){

        	return response()->json([
					'Response' => 'Driver/Taxi is in a ride.Try after Sometime',
					'Status' => '2'
				]);
        }
         
         $update_driver = DB::table('wy_driver')->where('id','=',$driver_id)->update(['profile_status' => 0]);
   //       $checkride = DB::table('wy_ridedetails')->where('driver_id','=',$driverid)->where('wy_ridedetails.ride_status', '=',3)->count();

   //       	if($checkride != 0){
   //       		return response()->json([
			// 		'Response' => 'Driver/Taxi is in a ride.Try after Sometime',
			// 		'Status' => '2'
			// 	]);
   //       	}
			// if(count($taxi_Status) >0){
			// 	return response()->json([
			// 		'Response' => 'Driver/Taxi is in a ride.Try after Sometime',
			// 		'Status' => '1'
			// 	]);
			// }
			$update_driver = DB::table('wy_assign_taxi')->where('driver_id','=',$driver_id)->where('car_num','=',$taxi_id)->update(['status' => 2]);
			$this->car->change_status($taxi_id,$status);
			return response()->json([
				'Response' => 'Vehicle Blocked Successfully',
				'Status' => '2'
			]);
		}
	}

	// Delete car
	public function delete_taxi(Request $request){
		
		if($request->input('_token')== null){
			 $taxi_id=$request->input('data_id');
			
			$model = Car::find($taxi_id);
			$model->delete();
			//$driver = DB::table('wy_assign_taxi')->where('car_num','=',$taxi_id)->where('status','=',1)->first();
			
			//$driver_id = $driver->driver_id;
			//$update_driver = DB::table('wy_driver')->where('id', '=', $driver_id)->update(['profile_status' => 0]);

			return response()->json([
				'Response' => 'Vehicle Deleted Successfully',
				'Status' => '2'
			]);
		}
	} 

//bulk data change status
	public function change_bulk_status(Request $request){
		if($request->input('_token')== null){
				$taxi_list=$request->input('curdata');
				$curstatus=$request->input('curstatus');
				$te = $request->input('ver_block');
				foreach($taxi_list as $taxi_id){

					if($curstatus==0){
						if($te != 1){
					$assign = DB::table('wy_assign_taxi')->where('id','=',$taxi_id)->first();
	                $CarId = $assign->car_num;
	                $DriverId = $assign->driver_id;

					$check5 = DB::table('wy_ridedetails')
					                ->where('driver_id','=',$DriverId)
					                ->whereIn('ride_status',array(0, 1, 2, 3))
					                ->whereIn('accept_status',array(0,1))->count();

        		if($check5 == 0){

						$this->car->change_status($CarId,$curstatus);
						
							$taxi_Status= DB::table('wy_ridedetails')
							->join('wy_ride', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
							->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
							->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
							->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
							->join('wy_cartype', 'wy_cartype.id', '=', 'wy_ride.car_type')
							->whereIn('wy_ridedetails.ride_status', array(0,1, 2, 3))
							->whereIn('wy_ridedetails.accept_status', array(0,1))
							->where('wy_assign_taxi.car_num', '=',$CarId)
							->select('wy_ridedetails.driver_id')
							->get();
								if(count($taxi_Status) == 0){
								$this->car->change_status($CarId,$curstatus);
								}
						}
				 	}
				 }
				 	$this->car->change_status($taxi_id,$curstatus);

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
			$type_list = CarType::where('ride_category','=',$request->input('category_id'))->where('status', 1)->get();

			foreach($type_list as $val){
				$type []=array(
				'id'=>$val->id,
				'car_type'=>$val->car_type,
				'car_board'=>$val->car_board
				);
			}
			$datalist=array('model_list'=>$model,'type_list'=>$type);
			return json_encode($datalist);
		
	} 

  
}
