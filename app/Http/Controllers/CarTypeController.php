<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\CarModel;
use App\CarBrand;
use App\CarType;
use App\Car;
use App\State;
use App\City;
use App\VehicleCategory;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
class CarTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 public function __construct(){
		$this->model=new CarType();
	}
    public function  index(Request $request)
    {
		$request->session()->put('cf_type', $request->input('ride_category'));
		if( session('cf_type') !=NUll){
			$type_list = CarType::where('ride_category','=',session('cf_type'))->get();
		}else{
			$type_list = CarType::all();
		}
		$ride_category = VehicleCategory::all();
		
		return view('car.type.manage_type',['ride_category'=>$ride_category,'type_list' => $type_list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$ride_category = VehicleCategory::all();
		return view('car.type.add_car_type',['ride_category' => $ride_category]);
     
	
    }

    public function view(Request $request,$id){

    	$type_list = CarType::where('id','=',$id)->first();
    	$ride_category = VehicleCategory::all();
    	return view('car.type.view',['ride_category'=>$ride_category,'list' => $type_list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
	$userid =Auth::user()->id;
		if(isset($_POST)){
			
			$rules = [
			'car_type'  => 'required|min:3|max:30',
			'ride_category'=>'required',
			'companydriver_share'=>'required|integer|between:0,100',
			'attacheddriver_share'=>'required|integer|between:0,100',
			
			];
			//Define the validtion messgae for the rule
			$messages = [
				'car_type.required'        => 'The Brand Name should be required',
			];
	
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
							
			}


			$total_share = $request->companydriver_share + $request->attacheddriver_share + $request->franchise_share;
			if($total_share != 100){

				return back()->withInput()
							->with('message', 'Total share value should be 100');
			}


			$color = $request->input('taxi_status'); 
			$check_model = CarType::where("car_type","=", $request->input('car_type'))
									->where("ride_category","=", $request->input('ride_category'))
									->where("ride_category","=", $color)
									->get();
			if(count($check_model)=== 1){
				return back()->withInput()
							->with('message', 'Car Type Already Exists');
			}
			$car_type = new CarType;
			$car_type->capacity = $request->input('taxi_capacity');
			$car_type->car_type = $request->input('car_type');
			$car_type->car_board = $color;
			$car_type->ride_category = $request->input('ride_category');
			$car_type->companydriver_share = $request->input('companydriver_share');
			$car_type->attacheddriver_share = $request->input('attacheddriver_share');
			$car_type->franchise_share = $request->input('franchise_share');
			if($request->file('yellow_caricon')!=null){
                 if ($request->file('yellow_caricon')->isValid()) {
                    $ins_file = $request->file('yellow_caricon');
                    $extension = $request->file('yellow_caricon')->getClientOriginalExtension(); // getting image extension
                    echo $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('uploads/cartype/yellow_caricon/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car_type->yellow_caricon='/uploads/cartype/yellow_caricon/' . date('Ymd')."/".$ins_filename;
                }
            }
            if($request->file('grey_caricon')!=null){
                 if ($request->file('grey_caricon')->isValid()) {
                    $ins_file = $request->file('grey_caricon');
                    $extension = $request->file('grey_caricon')->getClientOriginalExtension(); // getting image extension
                    $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('uploads/cartype/grey_caricon/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car_type->grey_caricon='/uploads/cartype/grey_caricon/' . date('Ymd')."/".$ins_filename;
                }
            }
			if($request->file('black_caricon')!=null){
                 if ($request->file('black_caricon')->isValid()) {
                    $ins_file = $request->file('black_caricon');
                    $extension = $request->file('black_caricon')->getClientOriginalExtension(); // getting image extension
                    $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('uploads/cartype/black_caricon/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car_type->black_caricon='/uploads/cartype/black_caricon/' . date('Ymd')."/".$ins_filename;
                }
            }
            $car_type->black_caricon = '';
			$car_type->status=1;
			$car_type->created_by = $userid;
			$car_type->created_at = date("Y-m-d H:i:s"); 
			$car_type->updated_by = $userid;
			$car_type->updated_at = date("Y-m-d H:i:s"); 
			$car_type->save();
				
			Session::flash('message', trans('Vehicle Type Successfully Created'));
			return redirect('/type');
		}
		
	}

   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $car_type = CarType::where('id',"=", $id)->get();
		if(count($car_type)==0){
				return back();
		}

		$ride_category = VehicleCategory::all();
		return view('car.type.edit_cartype',['car_type' => $car_type, 'ride_category'=>$ride_category ]);
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$userid =Auth::user()->id;
		if(isset($_POST)){
			
			$rules = [
				'car_type'  => 'required|min:3|max:30',
				'companydriver_share'=>'required|integer|between:0,100',
				'attacheddriver_share'=>'required|integer|between:0,100',
				
			];
			//Define the validtion messgae for the rule
			$messages = [
				'car_type.required'        => 'The Vehicle Type Name should be required',
				
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
			}
			
			
			$check_model = CarType::where("car_type","=",$request->input('car_type'))
							->where("ride_category","=", $request->input('ride_category'))
							->where('id', "!=",$request->input('type_id'))
							->get();
						
							
			if(count($check_model) >=1){
				return back()->withInput()
							->with('error_status', 'Vehicle Type Already Exists for this Vehicle Category');
			}
			//upadte the image with car type
		
			$car_type = CarType::find($request->input('type_id'));
			$car_type->car_type = $request->input('car_type');
			$car_type->franchise_share = $request->input('franchise_share');
			//$car_type->ride_category = $request->input('ride_category');
			$car_type->companydriver_share = $request->input('companydriver_share');
			$car_type->attacheddriver_share = $request->input('attacheddriver_share');
			if($request->file('yellow_caricon')!=null){
                 if ($request->file('yellow_caricon')->isValid()) {
                    $ins_file = $request->file('yellow_caricon');
                    $extension = $request->file('yellow_caricon')->getClientOriginalExtension(); // getting image extension
                    echo $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('uploads/cartype/yellow_caricon/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car_type->yellow_caricon='/uploads/cartype/yellow_caricon/' . date('Ymd')."/".$ins_filename;
                }
            }
            if($request->file('grey_caricon')!=null){
                 if ($request->file('grey_caricon')->isValid()) {
                    $ins_file = $request->file('grey_caricon');
                    $extension = $request->file('grey_caricon')->getClientOriginalExtension(); // getting image extension
                    $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('uploads/cartype/grey_caricon/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car_type->grey_caricon='/uploads/cartype/grey_caricon/' . date('Ymd')."/".$ins_filename;
                }
            }
			if($request->file('black_caricon')!=null){
                 if ($request->file('black_caricon')->isValid()) {
                    $ins_file = $request->file('black_caricon');
                    $extension = $request->file('black_caricon')->getClientOriginalExtension(); // getting image extension
                    $ins_filename = rand(11111,99999).'.'.$extension; // renameing image
                    $path = public_path('uploads/cartype/black_caricon/' . date('Ymd'));
                    $ins_file->move($path,$ins_filename);
                    $car_type->black_caricon='/uploads/cartype/black_caricon/' . date('Ymd')."/".$ins_filename;
                }
            }
			$car_type->capacity=$request->taxi_capacity;
			$car_type->status=1;
			$car_type->updated_by = $userid;
			$car_type->updated_at = date("Y-m-d H:i:s"); 
			$car_type->save();
			Session::flash('message', trans('Car Type Updated Successfully'));
			return redirect('/type');
		}
    }

   
	
	//Deactive the status using Ajax
	public function ajax_detivate_type(Request $request){
		$status=0;
		if($request->input('_token')== null){
				$type_id=$request->input('data_id');
				if($this->check_type_status($type_id)== false){
					return response()->json([
						'Response' => 'Vehicle has assigned to this Type.Please try again later',
						'Status' => '2'
					]);
				}
				$this->update_mapping_status($type_id,2);
				$this->model->change_status($type_id,$status);
			return response()->json([
				'Response' => 'Vehicle Type Successfully Deactivated',
				'Status' => 'Success'
			]);
		}
	}
	//Activate the status using Ajax
	public function ajax_activate_type(Request $request){
		$status=1;
		if($request->input('_token')== null){
				$type_id=$request->input('data_id');
				$this->update_mapping_status($type_id,1);
				$this->model->change_status($type_id,$status);
			return response()->json([
				'Response' => 'Vehicle Type Successfully Activated',
				'Status' => 'Success'
			]);
		}
	}
	
		//updatae the multi Type status 
	public function change_type_status(Request $request){
		$userid =Auth::user()->id;
		if($request->input('_token')== null){
				$type_list=$request->input('curdata');
				$curstatus=$request->input('curstatus');
				foreach($type_list as $type){
					if($this->check_type_status($type)== true){
						$this->update_mapping_status($type,$curstatus);
						CarType::where('id', '=', $type)
						->update([
						'status' => $curstatus,
						'updated_by'=>$userid,	
						'updated_at'=>date("Y-m-d H:i:s")				
						]);
					}
				}
				
				if($curstatus == 0){
					$response_status='Vehicle Type Successfully In-Activated ';
				}else{
					$response_status='Vehicle Model  Successfully Activated';
				}
			return response()->json([
				'Response' => $response_status,
				'Status' => 'Success'
			]);
		}
	}
	
	
	//********* CHECK TYPE STATUS ***********//
	public function check_type_status($type_id){
		$type_status= DB::table('wy_carlist')
				->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
				->Where('wy_carlist.model','=',$type_id)
				->Where('wy_assign_taxi.status','=',1)
				->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
				->get();
			if(count($type_status)>0){
				return false;
			}else{
				return true;
			}
	}
	
	
	public function update_mapping_status($type_id,$status){
		$userid =Auth::user()->id;
		//1 -active , 2 -block
	
		if($status==1){
			//Block the related car and assign taxi
			$type_status= DB::table('wy_cartype')
				->leftJoin('wy_carlist', 'wy_cartype.id', '=', 'wy_carlist.car_type')
				->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
				->Where('wy_cartype.id','=',$type_id)
				->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
				->get();
			
			if(count($type_status)>0){
					foreach($type_status as $val){
						//assign taxi update
						if($val->assign_id == null && $val->assign_id == "" ){
							Car::where('id', '=', $val->vehicle_id)
							->update([
							'status' => 0,
							'updated_by'=>$userid,	
							'updated_at'=>date("Y-m-d H:i:s")				
							]);
						}
					}
			}
		}else{
			//Block the related car and assign taxi
				$type_status= DB::table('wy_cartype')
					->leftJoin('wy_carlist', 'wy_cartype.id', '=', 'wy_carlist.car_type')
					->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
					->Where('wy_cartype.id','=',$type_id)
					->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
					->get();
				
				if(count($type_status)>0){
						foreach($type_status as $val){
							//assign Vehicle Status update
							if($val->assign_id == null && $val->assign_id == "" ){
								//assign taxi update
								Car::where('id', '=', $val->vehicle_id)
								->update([
								'status' => -1,
								'updated_by'=>$userid,	
								'updated_at'=>date("Y-m-d H:i:s")				
								]);
							}
						}
				}
			
		}
		return true;
		
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
	
	public function get_model(Request $request){
		//if($request->input('_token')== null){
	
		  $model_list = CarModel::where('brand_id','=',$request->input('category_id'))
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
			
			$datalist=array('model_list'=>$model);
			return json_encode($datalist);
		
	}

}