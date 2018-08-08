<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverRating extends Model
{
    //
    protected $table = 'wy_customerrate';

    public function getratings(){
	  return $this->belongsTo('App\Ratings','rating','id');
	}
}
