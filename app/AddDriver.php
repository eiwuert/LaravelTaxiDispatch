<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddDriver extends Model
{
    //
    protected $table = "wy_driver";

    protected $primaryKey = 'id';

    public function active_car(){
	    return $this->belongsTo('App\Car','car_id','id');
    }
}
