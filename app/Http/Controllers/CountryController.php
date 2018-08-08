<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\state;
use App\city;
use App\country;

class CountryController extends Controller
{
    //


  public function ShowState($id)
    {
      
      

         $d = new state();
       $ds = $d::where('country_id', $id)->get();
        

       echo '<div class="form-group">
                  <label for="" class="col-sm-2 control-label">State</label>
                  <div class="col-sm-4">
                    <select name="state" class="form-control" onchange="showCity(this.value)">';
                    //$d = new state();
                   foreach($ds as $sd){
                                  
                   echo '
                                      <option value="'.$sd->id.'">'.$sd->name.'</option>
                                      ';
                                    
                                     }
                                     echo ' </select>
                  </div>
                  </div>';                        
  }
    

    public function ShowCity($id)
    {
      
      

         $d = new city();
       $ds = $d::where('state_id', $id)->get();
        

       echo '<div class="form-group">
                  <label for="" class="col-sm-2 control-label">City</label>
                  <div class="col-sm-4">
                    <select name="city" class="form-control" >';
                    
                   foreach($ds as $sd){
                                  
                   echo '
                                      <option value="'.$sd->id.'">'.$sd->name.'</option>
                                      ';
                                    
                                     }
                                     echo ' </select>
                  </div>
                  </div>';                          
  } 

    
}
