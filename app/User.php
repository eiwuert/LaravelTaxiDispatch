<?php
namespace App;
use Session;
use DB;
use Auth;
use App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	  //create a function that belong to user model
    public function roles(){
        
      return $this->belongsToMany('App\Role','user_role','user_id','role_id');
        
    }
    //check user having any role 
    public function hasAnyRole($roles){
        if(is_array($roles)){
            foreach($roles as $role){
                if($this->hasRole($role)){
                    return true;
                }
            }
        }else{
             if($this->hasRole($roles)){
                return true;
             }
        }
        return false;
    }
    
    //check user having specific role permission is there or not
    
    public function hasRole($role){
      $userid =Auth::user()->id;
	  $results = DB::select('select * from user_role u JOIN roles r ON  u.role_id=r.id where u.user_id = :user_id and r.name=:name', ['user_id' => $userid,'name' => $role]);
      if($results){
       
        //if($this->roles()->with('name',$role)->first()){
          return true;
      }
         return false;
    }
}
