<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //
    protected $table = "wy_driver";

    protected $primaryKey = 'id';

    public function ride_categorys(){
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

    public function active_car(){
	    return $this->belongsTo('App\Car','car_id','id');
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
    
    public function getfranchise(){
        return $this->belongsTo('App\Franchise','franchise_id','id');
    }

    public function CarNo()
    {
        return $this->belongsToMany('App\Car','wy_assign_taxi','driver_id','id');
    }

     public function CarType()
    {
        return $this->belongsToMany('App\CarType','wy_carlist','driver_id','id');
    }

    //change the brand status
    public function change_status($taxi_id,$cur_status){
        $userid =Auth::user()->id;
            Driver::where('id', '=', $taxi_id)
            ->update([
            'profile_status' => $cur_status , 
            'status' => 0  ,
            'online_status' => 0  
            ]);
            return true;
        
    }
   
	
}
