<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
class Car extends Model
{

     //
    protected $table = 'wy_carlist';
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	public function getvehicle_name(){
	  return $this->belongsTo('App\VehicleCategory','ride_category','id');
	}
	public function country_name(){
	    return $this->belongsTo('App\Country','country','id');
    }
	public function state_name(){
	    return $this->belongsTo('App\State','state','id');
    }
	public function city_name(){
	    return $this->belongsTo('App\City','city','id');
    }
	public function brand_name(){
	    return $this->belongsTo('App\CarBrand','brand','id');
    }
	public function model_name(){
	    return $this->belongsTo('App\CarModel','model','id');
    }
	public function type_name(){
	    return $this->belongsTo('App\CarType','car_type','id');
    }
    public function getcreated_by(){
	    return $this->belongsTo('App\User','created_by','id');
    }
     public function getupdated_by(){
	    return $this->belongsTo('App\User','updated_by','id');
    }
     
	public function driver_nameid()
    {
		$r = $this->belongsToMany('App\Driver','wy_assign_taxi','car_num','id');
		return $r;
    }
	 
	 public function getcarid($taxiid){
	 $CarId="";
	 	$AssignTaxi = AssignTaxi::where('id','=',$taxiid)->first();
	 	if(count($AssignTaxi) !=0){
  		$CarId = $AssignTaxi->car_num;
  	
  		}	
  		return $CarId;
	 }
	

	//change the brand status
	public function change_status($taxi_id,$cur_status){
		$userid =Auth::user()->id;
		if($cur_status == 0){
			
			$this->change_nonactive_vehicle_mapping($taxi_id);
		}//else if($cur_status == -1){
			//$this->change_activate_vehicle_mapping($taxi_id);
		//}
		$userid =Auth::user()->id;
			Car::where('id', '=', $taxi_id)
			->update([
			'status' => $cur_status,
			'updated_by'=>$userid,	
			]);
			return true;
		
	}
	
	//vehicle related mapping drive need to acrivate
	/*public function change_activate_vehicle_mapping($vehicle_id){
		$userid =Auth::user()->id;
		$vehicle_status= DB::table('wy_assign_taxi')
				->Join('wy_driver', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
				->Where('wy_assign_taxi.car_num','=',$vehicle_id)
				->SELECT('wy_assign_taxi.*')
				->get();
			if(count($vehicle_status)>0){
				foreach($vehicle_status as $vehicle){
					if($vehicle->id != "" || $vehicle->id != NULL){
						DB::table('wy_assign_taxi')
						->where('id', $vehicle->id)
						->update([
						'status' => 1,
						'updated_by'=>$userid,	
						]);
					}
					if($vehicle->driver_id != "" || $vehicle->driver_id != NULL){
						DB::table('wy_driver')
						->where('id', $vehicle->driver_id)
						->update(['profile_status' => 1]);
					}
				}
			}
	}
	*/
		
	//vehicle related mapping drive need to acrivate
	public function change_nonactive_vehicle_mapping($vehicle_id){
	
		$userid =Auth::user()->id;
		$vehicle_status= DB::table('wy_assign_taxi')
				->Join('wy_driver', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
				->Where('wy_assign_taxi.car_num','=',$vehicle_id)
				->SELECT('wy_assign_taxi.*')
				->get();
			
			if(count($vehicle_status)>0){
				foreach($vehicle_status as $vehicle){
					if($vehicle->id != "" || $vehicle->id != NULL){
						DB::table('wy_assign_taxi')
						->where('id', $vehicle->id)
						->update([
						'status' => 2,
						'updated_by'=>$userid,	
						]);
					}
					if($vehicle->driver_id != "" || $vehicle->driver_id != NULL){
						DB::table('wy_driver')
						->where('id', $vehicle->driver_id)
						->update(['profile_status' => 0]);
					}
				}
			}
	}

}



