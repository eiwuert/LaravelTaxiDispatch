<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignTaxi extends Model
{
    //
	protected $table = 'wy_assign_taxi';
		protected $primaryKey = 'id';
			
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
	public function driver_details(){
	    return $this->belongsTo('App\Driver','driver_id','id');
    }
	public function taxi_num(){
	    return $this->belongsTo('App\Car','car_num','id');
    }

    public function car_type(){
	   return $this->belongsToMany('App\CarType','wy_carlist','id','id');
    }

}
