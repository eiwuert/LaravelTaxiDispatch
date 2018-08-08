<?php
namespace wyrades\Http\Controllers;

use Illuminate\Http\Request;
use wyrades\Item;
use Excel;
use wyrades\admin;

class PageController extends Controller
{

public function add_taxi(Request $request){

	$uri = $request->path();
    return view('add-taxi',['active'=>$uri]);

}
  
public function login(Request $request){

$username = $request->input('username');
$password = $request->input('password');
//echo $username;

$da = admin::all();
foreach($da as $sd) {
echo $sd->username;
 
var_dump($username1);
}
$password1 = $da->password;
if($username1 == $username){
if($password1 == $password){
session(['__userid' => 'siva']);
$value = $request->session()->get('__userid');
 return redirect('home');
}
}

}

}