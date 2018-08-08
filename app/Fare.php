<?php
namespace App;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Flight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Fare extends Model
{
    //
    protected $table = 'wy_faredetails';
	protected $primaryKey = 'fare_id';
	
	public $timestamps = false;
	public function getvehicle_name(){
	  return $this->belongsTo('App\VehicleCategory','ride_category','id');
	}
	public function getcartype_name(){
	    return $this->belongsTo('App\CarType','car_id','id');
    }
	public function getride_name(){
	    return $this->belongsTo('App\FareCategory','ride_name','id');
    }
    public function getcreated_by(){
	    return $this->belongsTo('App\User','created_by','id');
    }
     public function getupdated_by(){
	    return $this->belongsTo('App\User','updated_by','id');
    }
    
     public function get_franchise(){
	    return $this->belongsTo('App\Franchise','franchise_id','id');
    }
    
	public function storeFare_details($request){
		 
			$userid =Auth::user()->id;
			$fare = new Fare;
			$fare->car_id = $request['taxt_type'];
			$fare->ride_category = $request['ride_category'];
			$fare->fare_type = $request['fare_type'];
			$fare->ride_each_km=1;
			if($request['franchise'] !='') {	$fare->franchise_id=$request['franchise'];}
			if($request['ride_fare'] !='') {	$fare->ride_fare=$request['ride_fare'];}
			if($request['minimum_km'] !='') {	$fare->min_km = $request['minimum_km'];}
			if($request['minimum_fare'] !='') {	$fare->min_fare_amount = $request['minimum_fare'];}
			if($request['booking_type'] !='') {	$fare->booking_type = $request['booking_type'];}
			//if($request['distance_time'] !='') {	$fare->distance_time=1;}
			if($request['distance_fare'] !='') {	$fare->distance_fare=$request['distance_fare'];}
			//if($request['waiting_time'] !='') {	$fare->waiting_time = 1;}
			if($request['waiting_time_fare'] !='') {$fare->waiting_charge =$request['waiting_time_fare'];}
			
			//$fare->below_min_km_fare = $request['fare_below_minkm'];
			$fare->ride_start_time=date("H:i:s",strtotime($request['mstart_time']));
			$fare->ride_end_time=date("H:i:s",strtotime($request['mend_time']));
			
			$fare->fare_percent=$request['fare_value'];
				$fare->nit_start_time=date("H:i:s",strtotime($request['estart_time']));
			$fare->nit_end_time=date("H:i:s",strtotime($request['eend_time']));
			
			$fare->created_by = $userid;
			$fare->created_date = date("Y-m-d H:i:s"); 
			$fare->updated_by = $userid;
			$fare->updated_date = date("Y-m-d H:i:s"); 
			$fare->save();
		
			return true;
	}
		public function updateFare_details($request){
			
			/*Fare::where('fare_id', '=', $request['fare_id'])
			->update([
			'car_id' => $request['taxt_type'],
			'ride_name'=>$request['ride_name'],		
			]);*/
			$userid =Auth::user()->id;
			$fare = Fare::find($request['fare_id']);
			$fare->car_id = $request['taxt_type'];
			$fare->ride_category = $request['ride_category'];
			$fare->fare_type = $request['fare_type'];
			$fare->ride_each_km=1;
			
			if(isset($request['ride_fare'])) {	$fare->ride_fare=$request['ride_fare'];}
			if(isset($request['minimum_km'])) {	$fare->min_km = $request['minimum_km'];}
			if(isset($request['minimum_fare'])) {	$fare->min_fare_amount = $request['minimum_fare'];}
			if(isset($request['booking_type'])) {	$fare->booking_type = $request['booking_type'];}
			//if(isset($request['distance_time'])) {	$fare->distance_time=1;}
			if(isset($request['distance_fare'])) {	$fare->distance_fare=$request['distance_fare'];}
			//if(isset($request['waiting_time'])) {	$fare->waiting_time = 1;}
			if(isset($request['waiting_time_fare'])) {$fare->waiting_charge =$request['waiting_time_fare'];}
			
			
			
			if(isset($request['mstart_time'])) {	$fare->ride_start_time=date("H:i:s",strtotime($request['mstart_time']));}
			if(isset($request['mend_time'])) { $fare->ride_end_time=date("H:i:s",strtotime($request['mend_time']));}
			if(isset($request['fare_value'])) {	$fare->fare_percent=$request['fare_value'];}
			if(isset($request['estart_time'])) {	$fare->nit_start_time=date("H:i:s",strtotime($request['estart_time']));}
			if(isset($request['eend_time'])) {$fare->nit_end_time=date("H:i:s",strtotime($request['eend_time']));}
			
		
			$fare->updated_by = $userid;
			$fare->updated_date = date("Y-m-d H:i:s"); 
			$fare->save();
			return true;
	}
	public function change_status($fare_id,$cur_status){
		
		$userid =Auth::user()->id;
			Fare::where('fare_id', '=', $fare_id)
			->update([
			'status' => $cur_status,
			'updated_by'=>$userid,	
			'updated_date'=>date("Y-m-d H:i:s")				
			]);
			return true;
	}

	
}
