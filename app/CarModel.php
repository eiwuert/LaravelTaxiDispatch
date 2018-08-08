<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class CarModel extends Model
{
    //
	protected $table="wy_model";
	
	protected $primaryKey = 'id';
	
	public function getvehicle_name(){
	  return $this->belongsTo('App\VehicleCategory','ride_category','id');
	}
	
	public function getbrand(){
	  return $this->belongsTo('App\CarBrand','brand_id','id');
	}

	//Store Brand
	public function addModel($request){
			$userid =Auth::user()->id;
			$check_model = CarModel::where("model","=",$request['model_type'])
							->where("ride_category","=",$request['ride_category'])
							->get();
			if(count($check_model)> 0){
				return false;
			}
			$model = new CarModel;
			$model->brand_id = $request['model_brand'];
			$model->model = $request['model_type'];
			$model->ride_category = $request['ride_category'];
			$model->created_by = $userid;
			$model->status=1;
			$model->save();
			return true;
	}
	
	public function updatemodel($request){
			
		
			$userid =Auth::user()->id;
			$check_model = CarModel::where("model","=",$request['model_type'])
							->where("ride_category","=",$request['ride_category'])
							->where('id', "!=",$request['model_id'])
							->get();
			if(count($check_model) > 0){
				return false;
			}
			$model = CarModel::find($request['model_id']);
			$model->brand_id = $request['taxi_brand'];
			$model->model = $request['model_type'];
			$model->ride_category = $request['ride_category'];
			$model->status=1;
			$model->updated_by = $userid;
			$model->save();
			return true;
	}
	//change the brand status
	public function change_status($model_id,$cur_status){
			$userid =Auth::user()->id;
			CarModel::where('id', '=', $model_id)
			->update([
			'status' => $cur_status,
			'updated_by'=>$userid,	
			]);
			return true;
		
	}
}