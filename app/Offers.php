<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    //
    protected $table = "wy_offers";

    public function getcarname(){
	  return $this->belongsTo('App\CarType','coupon_typevalue','id');
	}

}