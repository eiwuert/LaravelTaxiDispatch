<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\state;
use App\city;
use App\country;

class CountryController1 extends Controller
{
    //


  public function ShowState($id)
    {
      $s = new country();
      $dfg = $s::where('name', $id)->get();
      foreach ($dfg as $gfd) {
        # code...
        $ids = $gfd->id;
        
      }
      

         $d = new state();
       $ds = $d::where('country_id', $ids)->get();
        

       echo '<div class="form-group">
                  <label for="" class="col-sm-4 control-label">State</label>
                  <div class="col-sm-8">
                    <select name="state" class="form-control" onchange="showCity1(this.value)">
                    <option disabled selected value> -- select an State -- </option>';
                    //$d = new state();
                   foreach($ds as $sd){
                                  
                   echo '
                                      <option value="'.$sd->name.'">'.$sd->name.'</option>
                                      ';
                                    
                                     }
                                     echo ' </select>
                  </div>
                  </div>';                        
  }
    

    public function ShowCity($id)
    {
      $s = new state();
      $dfg = $s::where('name', $id)->get();
      foreach ($dfg as $gfd) {
        # code...
        $ids = $gfd->id;
        
      }
      

         $d = new city();
       $ds = $d::where('state_id', $ids)->get();
        

       echo '<div class="form-group">
                  <label for="" class="col-sm-4 control-label">City</label>
                  <div class="col-sm-8">
                    <select name="city" class="form-control" >
                    <option disabled selected value> -- select an City -- </option>';
                    
                   foreach($ds as $sd){
                                  
                   echo '
                                      <option value="'.$sd->name.'">'.$sd->name.'</option>
                                      ';
                                    
                                     }
                                     echo ' </select>
                  </div>
                  </div>';                          
  } 


  public function ShowState1($id)
    {
      $s = new country();
      $dfg = $s::where('name', $id)->get();
      foreach ($dfg as $gfd) {
        # code...
        $ids = $gfd->id;
        
      }
      

         $d = new state();
       $ds = $d::where('country_id', $ids)->get();
        

       echo '<div class="form-group">
                  <label for="" class="col-sm-4 control-label">State</label>
                  <div class="col-sm-8">
                    <select name="state" class="form-control" onchange="showCity(this.value)"><option disabled selected value> -- select an State -- </option>';
                    //$d = new state();
                   foreach($ds as $sd){
                                  
                   echo '
                                      <option value="'.$sd->name.'">'.$sd->name.'</option>
                                      ';
                                    
                                     }
                                     echo ' </select>
                  </div>
                  </div>';                        
  }
    

    public function ShowCity1($id)
    {
      $s = new state();
      $dfg = $s::where('name', $id)->get();
      foreach ($dfg as $gfd) {
        # code...
        $ids = $gfd->id;
        
      }
      

         $d = new city();
       $ds = $d::where('state_id', $ids)->get();
        

       echo '<div class="form-group">
                  <label for="" class="col-sm-4 control-label">City</label>
                  <div class="col-sm-8">
                    <select name="city" class="form-control" >
                    <option disabled selected value> -- select an City -- </option>';
                   foreach($ds as $sd){
                                  
                   echo '
                                      <option value="'.$sd->name.'">'.$sd->name.'</option>
                                      ';
                                    
                                     }
                                     echo ' </select>
                  </div>
                  </div>';                          
  } 
    
}
