<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FareCategory extends Model
{
  	protected $table="wy_ridetype";
	public $timestamps = false;
	protected $primaryKey = 'id';
}
