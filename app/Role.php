<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Model
{
    protected $fillable = [
        'name','description'
    ];
	 protected $table = "user_role";

    //create a function that belong to user model
    public function user(){
        return  $this->belongsToMany('App\User',"user_role",'role_id','user_id');
        
    }
}
