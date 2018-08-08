<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RideDetails extends Model
{
    //
	protected $table = 'wy_ridedetails';
	
	public function driver_details(){
	    return $this->belongsTo('App\AddDriver','driver_id','id');
    }
	public function ride(){
	    return $this->hasManyThrough('App\Ride');
    }
	public function car_type1(){
	    return $this->belongsTo('App\CarType','car_type','id');
    }
	
}
