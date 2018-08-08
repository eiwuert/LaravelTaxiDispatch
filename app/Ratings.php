<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    //
      protected $table = 'wy_rating_reasons';
		protected $primaryKey = 'id';
	
	//	public $timestamps = false;

		public function getratings(){
		  return $this->belongsTo('App\VehicleCategory','rating','id');
		}
}
