<?php

namespace App\Http\Controllers;
use App\Fare;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Country;
use App\Car;
use App\State;
use App\CarType;
use Session;
use App\Tax;
use App\Franchise;
use App\VehicleCategory;
use Illuminate\Support\Facades\Auth;
class FareController extends Controller
{
	public function __construct(){
		
		$this->fare=new Fare();
	}
	public function createFare(Request $request){
		$franchise = Franchise::where('status','=',1)->get();
		$ride_category = VehicleCategory::all();
		$ridetype= DB::table('wy_ridetype')->get();
		$cartype = CarType::where('ride_category','=',1)->where('status', 1)->get();

		return view('fare.add_fare', ['ride_category' => $ride_category,'franchise' => $franchise,'ridetype' => $ridetype,'cartype' => $cartype]);
	}
  
  	//Delete fare
  	public function deletefare(Request $request)
  	{
  		# code...
  		if ($request->input('_token') == null) {

  			$id = $request->input('id');
  			$delete = DB::table('wy_faredetails')->where('fare_id','=',$id)
  			->where('fare_type','!=',1)
  			->delete();

  			if($delete){
	  				return response()->json([
	                'Response' => 'Fare deleted Successfully',
	                'Status' => '1'
            	]);
  			}else{
  				return response()->json([
	                'Response' => 'Fare deleted Successfully',
	                'Status' => '0'
            	]);
  			}
  			
  		}
  	}

  	// Delete Tax
  	public function deletetax(Request $request)
  	{
  		# code...
  		if ($request->input('_token') == null) {

            $tax_id = $request->input('data_id');

          $delete = DB::table('wy_tax')->where('id','=',$tax_id)->delete();
            return response()->json([
                'Response' => 'Tax Detail Deleted',
                'Status' => 'Success'
            ]);
        }
  	}

	//store the Fare details for the car
	public function storeFare(Request $request){
		
		if(isset($_POST)){
			
			$token = $request->input('_token');
			//Define validation rules	
			
			if($request->input('fare_type') == 1){
					$rules = [
						'franchise'    			=> 'required',
						'taxt_type'    			=> 'required',
						'ride_category'  		=>'required',
						'fare_type'         => 'required',
						'booking_type'     	=>'required',
						'ride_fare'         => 'required',
						'minimum_km'        => 'required',
						'minimum_fare'    	=> 'required',
						//'distance_time'     => 'required',
						'distance_fare'     => 'required',
						//'waiting_time'      => 'required',
						'waiting_time_fare' => 'required',
				];
			}else{
					$rules = [
						'franchise'    			=> 'required',
						'taxt_type'    			=> 'required',
						'ride_category'  		=>'required',
						'fare_type'         => 'required',
						'booking_type'     	=>'required',
						'mstart_time'         => 'required',
						'mend_time'         => 'required',
						'mstart_time'         => 'required',
						'mend_time'         => 'required',
						'estart_time' 		=> 'required',
						'eend_time'         => 'required',
				];
			}
			
			
			//Define the validtion messgae for the rule
			$messages = [
				'taxt_type.required'        => 'The Vehicle Type should required',
				//'minimum_fare.numeric'        => 'Not same',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
						
			}

			if(!$request->booking_type){
				return back()->withInput()
								->with('error_status', 'Please select booking type');
			}

			//Base Fare checking
			$fare_status= Fare::where('fare_type','=',1)
			->where('ride_category','=',$request['ride_category'])
			->where('franchise_id','=',$request['franchise'])
			->where('car_id','=',$request->input('taxt_type'))
			->get();
		
	
			
			if($request->input('fare_type')==1){
				if(count($fare_status) > 0 ){
						return back()->withInput()
								->with('error_status', 'Base Fare already configured for this Franchise');
					}
			}		

				//Mornging and  Night Fare checking
				/*if($request->input('fare_type')==2 || $request->input('fare_type') == 3){
				
					if(count($fare_status) == 0){
						return back()->withInput()
								->with('error_status', 'Please Configure Base Fare First');
					}
					
					$status=$this->checkfare_type_2status($request->input('start_time_fare'),$request->input('end_time_fare'),$request->input('fare_type'),$request->input('taxt_type'),1);
					
					if($status > 0){
						return back()->withInput()
								->with('error_status', 'Time Frame is already exists');
					} 
					
				}
*/
		
				//Peek and Super Fare checking
				if($request->input('fare_type')==4 || $request->input('fare_type') == 5){
					
					if(count($fare_status) == 0 ){
						return back()->withInput()
								->with('error_status', 'Please Configure Base Fare First');
					}


					//check francsis
						$check_francsis = Fare::where('fare_type','=',$request->fare_type)
					->where('franchise_id','=',$request['franchise'])
					->where('car_id','=',$request['taxt_type'])
					->where('ride_category','=',$request['ride_category'])
					->where('booking_type','=',$request['booking_type'])
					->count();
			
					if($check_francsis != 0){
						return back()->withInput()
								->with('error_status', 'This Franchise category details is exits.');
					}
					
				/*	//end francsis check
					$check_fare = Fare::where('fare_type','=',$request->fare_type)
					->where('car_id','=',$request['taxt_type'])
					->where('ride_category','=',$request['ride_category'])
					->where('booking_type','=',$request['booking_type'])
					
					->count();
				
					//$check_end_fare = Fare::where('fare_type','=',$request->fare_type)->where('ride_end_time','=',$request->end_time_fare)->count();

				//	$check_mid = Fare::where('fare_type','=',$request->fare_type)->count();
		
					if($check_fare != 0){
						return back()->withInput()
								->with('error_status', 'This Category Fare details is exits.');
					}*/
					
				
				/* $status=$this->checkfare_type_3status($request->input('start_time_fare'),$request->input('end_time_fare'),$request->input('fare_type'),$request->input('taxt_type'),1);
				
					if($status > 0){
						return back()->withInput()
								->with('error_status', 'Time Frame is already exists');
					} */
				}

				//echo "az";
				//exit();
				//	exit();
				$status=$this->fare->storeFare_details($request->all());
				
					Session::flash('message', trans('Successfully Created'));
					return redirect('/manage_fare');
				
			
    	//return view('fare.Add_Fare');
		}
	}

	public function getmanagefare(Request $request)
	{
		$VehicleCategory = $request->VehicleCategory;
		$VehicleType = $request->VehicleType;
		$FareType = $request->FareType;
		$start_time_fare = $request->start_time_fare;
		$end_time_fare = $request->end_time_fare;

		$ride_category = VehicleCategory::all();
		$car = Car::all();
		$cartype= DB::table('wy_cartype')->get();
		//$fare_list = Fare::where('ride_category','=',$VehicleCategory)->get();
		//$fare_list = Fare::all();
		$where = array();
		if($VehicleCategory != NULL)
		{
			
			$where[] = "fa.ride_category ='$VehicleCategory'";
			
		}

		if($VehicleType != NULL){
			$where[] = "ct.id = '$VehicleType'";
		}

		if($FareType != NULL){
			$where[] = "fa.fare_type = '$FareType'";
		}

		if($start_time_fare != NULL){
			$where[] = "fa.ride_start_time > '$start_time_fare'";
		}

		if($end_time_fare != NULL){
			$where[] = "fa.ride_start_time < '$end_time_fare'";
		}

		$first = "SELECT fa.*,ct.car_type,ct.car_board FROM wy_faredetails fa INNER JOIN wy_cartype ct ON ct.id = fa.car_id WHERE fa.status = 1";
echo $first; exit;
		$final = array();
		$final[] = $first;
		foreach ($where as $w) {
			$final[] = $w;
		}
		$wheref = implode(" AND ", $final);


		$fare_list = DB::select( DB::raw($wheref) );
		return view('fare.get_manage_fare', ['ride_category'=>$ride_category,'farelist' => $fare_list,'car'=>$car,'cartype' => $cartype]);
		//$data = array( 'data'=>$fare_list);

	//echo json_encode($fare_list);

	}



    public function manageFare(Request $request){

    	$request->session()->put('search', '0');

		$VehicleCategory = $request->VehicleCategory;
		$VehicleType = $request->VehicleType;
		$FareType = $request->FareType;
		$start_time_fare = $request->start_time_fare;
		$end_time_fare = $request->end_time_fare;
		$franchise_request = $request->franchise_id;
		
		
		$franchise = Franchise::where('status','=',1)->get();
		$ride_category = VehicleCategory::all();
		$car = Car::all();
		
		if($VehicleCategory!=""){
			$cartype= DB::table('wy_cartype')->where('ride_category','=',$VehicleCategory)->get();
		}else{
			$cartype= DB::table('wy_cartype')->get();
		}
		//$fare_list = Fare::where('ride_category','=',$VehicleCategory)->get();
		//$fare_list = Fare::all();
		$where = array();
		if($VehicleCategory != NULL)
		{

			//$request->session()->push('search', $VehicleCategory);
			$where[] = "fa.ride_category ='$VehicleCategory'";
			
		}


		if($franchise_request != NULL){
			//$request->session()->push('search', $start_time_fare);
			$where[] = "fa.franchise_id = '$franchise_request'";
		}
		
		if($VehicleType != NULL){
			//$request->session()->push('search', $VehicleType);
			$where[] = "ct.id = '$VehicleType'";
		}

		if($FareType != NULL){
			//$request->session()->push('search', $FareType);
			$where[] = "fa.fare_type = '$FareType'";
		}

		if($start_time_fare != NULL){
			//$request->session()->push('search', $start_time_fare);
			$t = strtotime($start_time_fare);
			$sfare = date('H:i:s',$t);
			$where[] = "fa.ride_start_time >= '$sfare'";
		}

		if($end_time_fare != NULL){
			//$request->session()->push('search', $start_time_fare);
			$te = strtotime($end_time_fare);
			$efare = date('H:i:s',$te);
			$where[] = "fa.ride_start_time <= '$efare'";
		}

		$first = "SELECT fr.company_name as FranchiseName,fa.*,ct.car_type,ct.car_board FROM wy_faredetails fa INNER JOIN wy_cartype ct ON ct.id = fa.car_id INNER JOIN wy_franchise fr ON fa.franchise_id = fr.id";
	
		$final = array();
		$final[] = $first;
		foreach ($where as $w) {
			$final[] = $w;
		}
		$wheref = implode(" AND ", $final);


		$fare_list = DB::select( DB::raw($wheref) );
		//$flights = App\Flight::all();
		return view('fare.manage_fare', ['franchise'=>$franchise,'ride_category'=>$ride_category,'farelist' => $fare_list,'car'=>$car,'cartype' => $cartype]);

    	
    }
	
	public function edite_fare($fareid=""){
		$ridetype= DB::table('wy_ridetype')->get();
		$cartype= DB::table('wy_cartype')->get();
		$fare_list =Fare::find($fareid); 
		$ride_category = VehicleCategory::all();
			if(count($fare_list)==0){
				return redirect('/manage_fare');
			}
			return view('fare.edit-fare', ['ride_category'=>$ride_category,'ridetype' => $ridetype,'cartype' => $cartype,'fare_list' => $fare_list]);
	}
	
	//store the Fare details for the car
	public function update_fare(Request $request){
		
		if(isset($_POST)){
			$token = $request->input('_token');
			//Define validation rules	
			
			if($request->input('fare_type') == 1){
					$rules = [
						'taxt_type'    			=> 'required',
						'ride_category'  		=>'required',
						'fare_type'         => 'required',
						'booking_type'     	=>'required',
						'ride_fare'         => 'required',
						'minimum_km'        => 'required',
						'minimum_fare'    	=> 'required',
						//'distance_time'     => 'required',
						'distance_fare'     => 'required',
						//'waiting_time'      => 'required',
						'waiting_time_fare' => 'required',
				];
			}else{
			
					$rules = [
						'taxt_type'    			=> 'required',
						'ride_category'  		=>'required',
						'fare_type'         => 'required',
						'booking_type'     	=>'required',
						'mstart_time'         => 'required',
						'mend_time'         => 'required',
						'mstart_time'         => 'required',
						'mend_time'         => 'required',
						'estart_time' 		=> 'required',
						'eend_time'         => 'required',
				];
			}
			
		
			//Define the validtion messgae for the rule
			$messages = [
				'taxt_type.required'        => 'The Vehicle Type should required',
				//'minimum_fare.numeric'        => 'Not same',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
			
					return back()->withInput()
							->withErrors($validator);
			}

			//Mornging and  Night Fare checking
				if($request->input('fare_type')==2 || $request->input('fare_type') == 3){
					
						$status=$this->checkfare_type_2status($request->input('start_time_fare'),$request->input('end_time_fare'),$request->input('fare_type'),$request->input('taxt_type'),2,$request->input('fare_id'));
					if($status > 0){
						return back()->withInput()
								->with('error_status', 'Time Frame is already exists');
					} 
				}
			
				//Peek and Super Fare checking
				/*if($request->input('fare_type')==4 || $request->input('fare_type') == 5){
					
					// if(count($fare_status) == 0 ){
					// 	return back()->withInput()
					// 			->with('error_status', 'Please Configure Base Fare First');
					// }
	
				$check_fare = Fare::where('fare_type','=',$request->fare_type)->where('ride_start_time','=',date("H:i:s",strtotime($request->start_time_fare)))->count();

				$check_end_fare = Fare::where('fare_type','=',$request->fare_type)->where('ride_end_time','=',date("H:i:s",strtotime($request->end_time_fare)))->count();

				$check_mid = Fare::where('fare_type','=',$request->fare_type)->where('ride_start_time','>',date("H:i:s",strtotime($request->start_time_fare)))->count();

				$check_l = Fare::where('fare_type','=',$request->fare_type)->where('ride_end_time','<',date("H:i:s",strtotime($request->end_time_fare)))->count();

					if($check_fare != 0 || $check_end_fare != 0 || $check_mid != 0 || $$check_l != 0){
						return back()->withInput()
								->with('error_status', 'Time frame already exists');
					}

				 $status=$this->checkfare_type_3status($request->input('start_time_fare'),$request->input('end_time_fare'),$request->input('fare_type'),$request->input('taxt_type'),2,$request->input('fare_id'));
				
					if($status > 1){
						return back()->withInput()
								->with('error_status', 'Time Frame is already exists');
					} 
				}
			*/
			$status=$this->fare->updateFare_details($request->all());
				
				Session::flash('message', trans('Successfully Created'));
				return redirect('/manage_fare');
			
		}
    	//return view('fare.Add_Fare');
    }
	
	public function viewfare($fareid=""){
		
			/*$fare_list = DB::table('wy_faredetails')
            ->join('wy_cartype', 'wy_faredetails.car_id', '=', 'wy_cartype.id')
            ->join('wy_ridetype', 'wy_faredetails.ride_name', '=', 'wy_ridetype.id')
			 ->join('users', 'wy_faredetails.created_by', '=', 'users.id')
			->where('wy_faredetails.fare_id', '=', $fareid)
            ->select('wy_faredetails.*','users.*','wy_cartype.car_type','wy_ridetype.ride_name')
            ->get();
			*/
			  $fare_list=Fare::find($fareid);
			if(count($fare_list)==0){
				return redirect('/manage_fare');
			}
			return view('fare.view-fare',['fare_list' => $fare_list]);
	}
//Deactive the status using Ajax
	public function ajax_deactive_fare(Request $request){
		$status=0;
		if($request->input('_token')== null){
				$fare_id=$request->input('data_id');
				$farecheck = Fare::where('fare_id','=',$fare_id)->where('fare_type','=',1)->count();
				if($farecheck != 0){
					exit;
				}
				$this->fare->change_status($fare_id,$status);
			return response()->json([
				'Response' => 'Fare Successfully Deactivated',
				'Status' => 'Success'
			]);
		}
	}
	//Activate the status using Ajax
	public function ajax_activate_fare(Request $request){
		$status=1;
		if($request->input('_token')== null){
				$fare_id=$request->input('data_id');
				$this->fare->change_status($fare_id,$status);
			return response()->json([
				'Response' => 'Fare Successfully Activated',
				'Status' => 'Success'
			]);
		}
	}
	public  function change_bulk_status(Request $request){
		
		if($request->input('_token')== null){
				$taxi_list=$request->input('curdata');
			 $curstatus=$request->input('curstatus');
				
				foreach($taxi_list as $taxi_id){
					$this->fare->change_status($taxi_id,$curstatus);
				}
				if($curstatus == 0){
					$response_status='Fare Details In Activated Successfully';
				}else{
					$response_status='Fare Details Activated Successfully';
				}
			return response()->json([
				'Response' => $response_status,
				'Status' => 'Success'
			]);
		}
	}
	
	public function createTax(){
		
		$countrylist=Country::all();
		return view('fare.tax.add-tax', ['country_list' => $countrylist]);
	}

	public function storeTax(Request $request)
    {
        $userid =Auth::user()->id;
        $rules = [
				'tax_name'          => 'required',
				'tax_percentage'=>'required|between:1,100',
				'country'    		 => 'required',
				'state'          => 'required',
			];
			//Define the validtion messgae for the rule
			$messages = [
			
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
            }
		 //  store value
            $car    = new Tax;
			$car->tax_name = $request->input('tax_name');
			$car->percentage	=$request->input('tax_percentage');
			$car->country = $request->input('country');
			$car->state	=$request->input('state');
			$car->status = 1;
			$car->created_by =$userid;
			$car->updated_by =$userid;
			$car->save();
           	Session::flash('message', trans('Tax Successfully Created'));
				return redirect('/manage-tax');
    }
    public function manageTax(Request $request){
	   $tax_list = Tax::all();
       return view('fare.tax.manage-tax', ['tax_list' => $tax_list]);
	}
	
	public function editeTax($taxid){
	
		$taxdetails=Tax::find($taxid);
	    	if(count($taxdetails)==0){
				return redirect('/taxi');
			}
        $countrylist=Country::all();
		return view('fare.tax.edit-tax', ['taxdetails'=>$taxdetails,'country_list'=>$countrylist]);
	}
	public function updateTax(Request $request){
		
		  	$tax_id = Tax::find($request->input('tax_id'));
			if(count($tax_id)==0){
				return redirect('/manage-tax');
			}
		
			$userid =Auth::user()->id;
			$rules = [
				'tax_name'          => 'required',
				'tax_percentage'=>'required|integer|between:1,100',
				'country'    		 => 'required',
				'state'          => 'required',
			];
			//Define the validtion messgae for the rule
			$messages = [
			
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
            }
		 //  store value
           	$tax = Tax::find($request->input('tax_id'));
			$tax->tax_name = $request->input('tax_name');
			$tax->percentage	=$request->input('tax_percentage');
			$tax->country = $request->input('country');
			$tax->state	=$request->input('state');
			$tax->updated_by =$userid;
			$tax->save();
           	Session::flash('message', trans('Tax Successfully Created'));
				return redirect('/manage-tax');
		
	}
	//Deactive the status using Ajax
	public function ajax_deactive_tax(Request $request){
		   $userid =Auth::user()->id;
	
		if($request->input('_token')== null){
				$tax = Tax::find($request->input('data_id'));
				$tax->status = 0;
				$tax->updated_by =$userid;
				$tax->save();
			return response()->json([
				'Response' => 'Tax Blocked Successfully',
				'Status' => 'Success'
			]);
		}
	}

    //Activate the status using Ajax
	public function ajax_activate_tax(Request $request){
	   $userid =Auth::user()->id;
		if($request->input('_token')== null){
			
			$tax = Tax::find($request->input('data_id'));
			$tax->status = 1;
			$tax->updated_by =$userid;
			$tax->save();
			
			return response()->json([
				'Response' => 'Tax Activated Successfully ',
				'Status' => 'Success'
			]);
		}
	}
//bulk data change status
	public function change_tax_status(Request $request){
		 $userid =Auth::user()->id;
		if($request->input('_token')== null){
				$tax_list=$request->input('curdata');
				$curstatus=$request->input('curstatus');
				foreach($tax_list as $tax_id){
					
					$tax = Tax::find($tax_id);
					$tax->status = $curstatus;
					$tax->updated_by =$userid;
					$tax->save();
				}
				if($curstatus == 0){
					$response_status='Tax Successfully In activated';
				}else{
					$response_status='Tax Successfully In Activated';
				}
			return response()->json([
				'Response' => $response_status,
				'Status' => 'Success'
			]);
		}
	}
	//check status for morning and night SHift Timing
	public function checkfare_type_2status($starttime,$endtime,$fare_type,$taxi_type,$method="",$fare_id=''){
		
		if($fare_id ==''){
			$fare_id=0;	
		}
		 $starttime=date("H:i:s",strtotime($starttime));
		 $endtime=date("H:i:s",strtotime($endtime ));
		DB::SELECT("call fare_type_mornight_status('$starttime','$endtime','$fare_type','$taxi_type','$method','$fare_id',@cur_status);");
		$final_status=DB::SELECT("select @cur_status as cur_status;");
	
		return $final_status[0]->cur_status;
	}
	//check status for Peek time and Super Time
	public function checkfare_type_3status($starttime,$endtime,$fare_type,$taxi_type, $method="", $fare_id=''){
		if($fare_id ==''){
			$fare_id=0;	
		}
		 $starttime=date("H:i:s",strtotime($starttime));
		 $endtime=date("H:i:s",strtotime($endtime ));
		DB::SELECT("call fare_type_peekspecial_status('$starttime','$endtime','$fare_type','$taxi_type','$method','$fare_id',@cur_status);");
		$final_status=DB::SELECT("select @cur_status as cur_status;");
	
		return $final_status[0]->cur_status;
		
		
	}
	
	//************* Implement the type based on vehicle category ****************//
	public function gettype_basedonfare(Request $request){
		//if($request->input('_token')== null){
			$type=array();
			$type_list = CarType::where('ride_category','=',$request->input('category_id'))->where('status', 1)->get();

			foreach($type_list as $val){
				$type []=array(
				'id'=>$val->id,
				'car_board'=>$val->car_board,
				'car_type'=>$val->car_type
				);
			}
			$datalist=array('type_list'=>$type);
			return json_encode($datalist);
		
	} 
	  
	
}
