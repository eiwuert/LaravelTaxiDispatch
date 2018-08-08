<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CarBrand;
use App\Car;
use App\AssignTaxi;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
class CarBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 public function __construct(){
		$this->brand=new CarBrand();
	}
    public function index()
    {
        //
		$brand_list = CarBrand::all();
		return view('car.brand.manage_brand',['brand_list' => $brand_list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		return view('car.brand.add_brand');
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
			'brand_name'  => 'required|min:3|max:30',
			];
			//Define the validtion messgae for the rule
			$messages = [
				'brand_name.required'        => 'The Brand Name should be required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
			}
			$status=$this->brand->addbrand($request->all());
			if($status==false){
				return redirect('/brand/add')->withInput()
							->with('error_status', 'Brand Name Already Exists');
			}
				
			Session::flash('message', trans('Brand Successfully Created'));
			return redirect('/brand');
		}
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
	
	
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand_details = CarBrand::where('id',"=", $id)->get();
		if(count($brand_details)==0){
				return redirect('/manage_fare');
		}
		return view('car.brand.edit_brand',['brand_details' => $brand_details]);
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
			'brand_name'  => 'required|min:3|max:30',
			];
			//Define the validtion messgae for the rule
			$messages = [
				'brand_name.required'        => 'The Brand Name should be required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
					return back()->withInput()
							->withErrors($validator);
			}
			$status=$this->brand->updatebrand($request->all());
			if($status==false){
				return back()->withInput()
							->with('error_status', 'Brand Name Already Exists');
			}
				
			Session::flash('message', trans('Brand Updated Successfully'));
			return redirect('/brand');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	
	//Deactive the status using Ajax
	public function ajax_detivate_brand(Request $request){
		$status=0;
		
		if($request->input('_token')== null){
				$brand_id=$request->input('data_id');
				if($this->check_brand_status($brand_id)== false){
					return response()->json([
						'Response' => 'Vehicle has assigned to this Brand.Please try again later',
						'Status' => '2'
					]);
				}
				$this->update_mapping_status($brand_id,2);
				$this->brand->change_status($brand_id,$status);
			return response()->json([
				'Response' => 'Brand Successfully Blocked',
				'Status' => 'Success'
			]);
		}
	}
	//Activate the status using Ajax
	public function ajax_activate_brand(Request $request){
		$status=1;
		
		if($request->input('_token')== null){
				$brand_id=$request->input('data_id');
				
				$this->update_mapping_status($brand_id,1);
				$this->brand->change_status($brand_id,$status);
			return response()->json([
				'Response' => 'Brand Successfully Activated',
				'Status' => 'Success'
			]);
		}
	}
	
	//updatae the multi brand status 
	public function change_brand_status(Request $request){
		$userid =Auth::user()->id;
		if($request->input('_token')== null){
				$brand_list=$request->input('curdata');
				$curstatus=$request->input('curstatus');
				foreach($brand_list as $brand){
					
					if($this->check_brand_status($brand)== true){
						$this->update_mapping_status($brand,$curstatus);
						CarBrand::where('id', '=', $brand)
						->update([
						'status' => $curstatus,
						'updated_by'=>$userid,	
						'updated_at'=>date("Y-m-d H:i:s")				
						]);
					}
				}
				if($curstatus == 0){
					$response_status='Car Model Successfully Blocked';
				}else{
					$response_status='Car Model  Successfully Activated';
				}
			return response()->json([
				'Response' => $response_status,
				'Status' => 'Success'
			]);
		}
	}
		
	//********* CHECK BRAND STATUS ***********//
	public function check_brand_status($brand_id){
		$brand_status= DB::table('wy_carlist')
				->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
				->Where('wy_carlist.brand','=',$brand_id)
				->Where('wy_assign_taxi.status','=',1)
				->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
				->get();
			if(count($brand_status)>0){
				return false;
			}else{
				return true;
			}
	}
	
	public function update_mapping_status($brand_id,$status){
		$userid =Auth::user()->id;
		//1 -active , 2 -block
	
		if($status==1){
			//Block the related car and assign taxi
			$brand_status= DB::table('wy_brand')
				->leftJoin('wy_carlist', 'wy_brand.id', '=', 'wy_carlist.brand')
				->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
				->Where('wy_brand.id','=',$brand_id)
				->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
				->get();
			
			if(count($brand_status)>0){
					foreach($brand_status as $val){
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
				$brand_status= DB::table('wy_brand')
					->leftJoin('wy_carlist', 'wy_brand.id', '=', 'wy_carlist.brand')
					->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
					->Where('wy_brand.id','=',$brand_id)
					->SELECT('wy_assign_taxi.status','wy_assign_taxi.id as assign_id','wy_carlist.id as vehicle_id')
					->get();
				
				if(count($brand_status)>0){
						foreach($brand_status as $val){
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
