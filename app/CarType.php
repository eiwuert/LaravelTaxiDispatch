<?php

namespace App;
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CarType extends Model
{
  //
	protected $table="wy_cartype";

	protected $primaryKey = 'id';
	//Store Brand
	
	public function getvehicle_name(){
	  return $this->belongsTo('App\VehicleCategory','ride_category','id');
	}

	
	//change the brand status
	public function change_status($type_id,$cur_status){
			$userid =Auth::user()->id;
			CarType::where('id', '=', $type_id)
			->update([
			'status' => $cur_status,
			'updated_by'=>1,	
			'updated_at'=>date("Y-m-d H:i:s")				
			]);
			return true;
		
	}
}