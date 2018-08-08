<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\CarModel;
use App\CarBrand;
use App\VehicleCategory;
use App\Car;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
class CarModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 public function __construct(){
		$this->model=new CarModel();
	}
    public function index(Request $request)
    {
		//$request->session()->forget('cf_model');
		$request->input('ride_category');
		session(['cf_model' => $request->input('ride_category')]);
		if( session('cf_model') !=NUll){
			$model_list = CarModel::where('ride_category','=',session('cf_model'))->get();
		}else{
			$model_list = CarModel::all();
		}
		$ride_category = VehicleCategory::all();
		return view('car.model.manage_model',['ride_category'=>$ride_category,'model_list' => $model_list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ride_category = VehicleCategory::all();
        $brand = CarBrand::all();
		return view('car.model.add_model',['ride_category' => $ride_category,'brand' => $brand]);
	
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
		if(isset($_POST)){
			
			$rules = [
			//'brand_name'  => 'required',
			'model_type'  => 'required|min:2|max:30',
			'ride_category'=>'required'
			];
			//Define the validtion messgae for the rule
			$messages = [
				'model_type.required'        => 'The Model Name should be required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
			}
			$status=$this->model->addModel($request->all());
			if($status==false){
				return back()->withInput()
							->with('error_status', 'Model Type Already Exists ');
			}
				
			Session::flash('message', trans('Model Successfully Created'));
			return redirect('/model');
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
		$brand_list = CarBrand::all();
        $chk_model_status = CarModel::where('id',"=", $id)->get();
		if(count($chk_model_status)==0){
				return back();
		}
		$ride_category = VehicleCategory::all();
		$model_list = CarModel::where('id',"=", $id)->get();
		return view('car.model.edit_model',['model_list' => $model_list,'ride_category' => $ride_category,'brand' => $brand_list]);
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
		
		if(isset($_POST)){
			
			$rules = [
					
				'model_type'  => 'required|min:2|max:30',
			];
			//Define the validtion messgae for the rule
			$messages = [
				'model_type.required'        => 'The Brand Name should be required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
			}
			$status=$this->model->updatemodel($request->all());
			if($status==false){
				return back()->withInput()
							->with('error_status', 'Model Type Already Exists');
			}
				
			Session::flash('message', trans('Model Type Updated Successfully'));
			return redirect('/model');
		}
    }

   
	
	//Deactive the status using Ajax
	public function ajax_detivate_model(Request $request){
		$status=0;
		if($request->input('_token')== null){
				$model_id=$request->input('data_id');
				if($this->check_model_status($model_id)== false){
					return response()->json([
						'Response' => 'Vehicle has assigned to this Model.Please try again later',
						'Status' => '2'
					]);
				}
				$this->update_mapping_status($model_id,2);
				$this->model->change_status($model_id,$status);
			return response()->json([
				'Response' => 'Model Successfully Blocked',
				'Status' => 'Success'
			]);
		}
	}
	//Activate the status using Ajax
	public function ajax_activate_model(Request $request){
		$status=1;
		if($request->input('_token')== null){
				$model_id=$request->input('data_id');
				$this->update_mapping_status($model_id,1);
				$this->model->change_status($model_id,$status);
			return response()->json([
				'Response' => 'Model Successfully Activated',
				'Status' => 'Success'
			]);
		}
	}
	
	//updatae the multi Model status 
	public function change_model_status(Request $request){
		$userid =Auth::user()->id;
		if($request->input('_token')== null){
				$model_list=$request->input('curdata');
				$curstatus=$request->input('curstatus');
				
				
				foreach($model_list as $model){
					if($this->check_model_status($model)== true){
						$this->update_mapping_status($model,$curstatus);
						CarModel::where('id', '=', $model)
						->update([
						'status' => $curstatus,
						'updated_by'=>$userid,	
						'updated_at'=>date("Y-m-d H:i:s")				
						]);
					}
				}
				
				if($curstatus == 0){
					$response_status='Car Model Successfully Blocked ';
				}else{
					$response_status='Car Model  Successfully Activated';
				}
			return response()->json([
				'Response' => $response_status,
				'Status' => 'Success'
			]);
		}
	}
	
	//********* CHECK MODEL STATUS ***********//
	public function check_model_status($model_id){
		$model_status= DB::table('wy_carlist')
				->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
				->Where('wy_carlist.model','=',$model_id)
				->Where('wy_assign_taxi.status','=',1)
				->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
				->get();
			if(count($model_status)>0){
				return false;
			}else{
				return true;
			}
	}
	
	public function update_mapping_status($model_id,$status){
		$userid =Auth::user()->id;
		//1 -active , 2 -block
	
		if($status==1){
			//Block the related car and assign taxi
			$model_status= DB::table('wy_model')
				->leftJoin('wy_carlist', 'wy_model.id', '=', 'wy_carlist.model')
				->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
				->Where('wy_model.id','=',$model_id)
				->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
				->get();
			
			if(count($model_status)>0){
					foreach($model_status as $val){
						//assign taxi update
						if($val->assign_id == null && $val->assign_id == "" ){
							Car::where('id', '=', $val->vehicle_id)
							->update([
							'status' => 0,
							'updated_by'=>$userid,	
							'updated_at'=>date("Y-m-d H:i:s")				
							]);
						//assign Vehicle Status update
					
							/*AssignTaxi::where('id', '=', $val->assign_id)
							->update([
							'status' => 1,
							'updated_by'=>$userid,	
							'updated_at'=>date("Y-m-d H:i:s")				
							]);*/
						}
					}
			}
		}else{
			//Block the related car and assign taxi
				$model_status= DB::table('wy_model')
					->leftJoin('wy_carlist', 'wy_model.id', '=', 'wy_carlist.model')
					->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
					->Where('wy_model.id','=',$model_id)
					->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
					->get();
				
				if(count($model_status)>0){
						foreach($model_status as $val){
							//assign Vehicle Status update
							if($val->assign_id == null && $val->assign_id == "" ){
								//assign taxi update
								Car::where('id', '=', $val->vehicle_id)
								->update([
								'status' => -1,
								'updated_by'=>$userid,	
								'updated_at'=>date("Y-m-d H:i:s")				
								]);
								
								/*AssignTaxi::where('id', '=', $val->assign_id)
								->update([
								'status' => 2,
								'updated_by'=>$userid,	
								'updated_at'=>date("Y-m-d H:i:s")				
								]);*/
							}
						}
				}
			
		}
		return true;
		
	}
	
		
}
