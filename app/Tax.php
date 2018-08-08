<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    //
	protected $table="wy_tax";
	
	public function country_name(){
	    return $this->belongsTo('App\Country','country','id');
    }
	public function state_name(){
	    return $this->belongsTo('App\State','state','id');
    }
}
