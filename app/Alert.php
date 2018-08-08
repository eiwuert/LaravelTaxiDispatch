<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Alert extends Model
{
    protected $table = 'wy_alert'; 

}
