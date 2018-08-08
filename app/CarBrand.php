<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class CarBrand extends Model
{
    //
	protected $table="wy_brand";
	//public $timestamps = false;
	protected $primaryKey = 'id';
	//Store Brand
	public function addbrand($request){
			$userid =Auth::user()->id;
			$check_brand = CarBrand::where("brand","=",$request['brand_name'])->get();
			if(count($check_brand)=== 1){
				return false;
			}
			$brand = new CarBrand;
			$brand->brand = $request['brand_name'];
			$brand->created_by = $userid;
			$brand->status=1;
			$brand->created_at = date("Y-m-d H:i:s"); 
			$brand->save();
			return true;
	}
	
	public function updatebrand($request){
			
		
			$userid =Auth::user()->id;
			$check_brand = CarBrand::where("brand","=",$request['brand_name'])
			->where('id', "!=",$request['brand_id'])
			->get();
			if(count($check_brand) >=1){
				return false;
			}
			$brand = CarBrand::find($request['brand_id']);
			$brand->brand = $request['brand_name'];
		
			$brand->status=1;
			$brand->updated_by = $userid;
			$brand->updated_at = date("Y-m-d H:i:s"); 
			$brand->save();
			return true;
	}
	//change the brand status
	public function change_status($brand_id,$cur_status){
		$userid =Auth::user()->id;
			CarBrand::where('id', '=', $brand_id)
			->update([
			'status' => $cur_status,
			'updated_by'=>$userid,	
			'updated_at'=>date("Y-m-d H:i:s")				
			]);
			return true;
		
	}
	
}
