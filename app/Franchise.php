<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    //
    protected $table = "wy_franchise";
    
    public function country_name(){
	    return $this->belongsTo('App\Country','country','id');
    }
	public function state_name(){
	    return $this->belongsTo('App\State','state','id');
    }
	public function city_name(){
	    return $this->belongsTo('App\City','city','id');
    }
}
