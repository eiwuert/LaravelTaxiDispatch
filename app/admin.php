<?php

namespace wyrades;

use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    //
protected $table = 'admin';

public $timestamps = false;

protected $fillable = array('id','username','password');

}
