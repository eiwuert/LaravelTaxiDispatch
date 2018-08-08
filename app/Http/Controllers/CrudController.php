<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AddDriver;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;


class CrudController extends Controller
{
    //
    public function AddDriver(Request $request)
    {
    	$s = strtoupper(md5(uniqid(rand(),true))); 
    	$file = $request->file('license');
		$destinationPath = 'uploads';
      	$file->move($destinationPath,$s.$file->getClientOriginalName());
		
		$firstname = $request->firstname;
		$lastname = $request->lastname;
		$email = $request->email;
		$password = $request->password;
		$gender = $request->gender;
		$dob = $request->dob;
		$licenseid = $request->licenseid;
		$mobile = $request->mobile;
		$country = $request->country;
		$state = $request->state;
		$city = $request->city;
		$address = $request->address;

		 $c = strtoupper($country[0]).strtoupper($country[1]).strtoupper($state[0]).strtoupper($state[1]);
		$lid = $mobile[0].$mobile[1].$dob[0].$dob[1].rand(1,10);;
		echo '<center><h2>Driver Added</h2>';
		echo '<br><a href="add-driver">Back</a></center>';
			$e = new AddDriver();

		 	$e->firstname=$firstname;
		 	$e->license=$s.$file->getClientOriginalName();
		  	$e->lastname =$lastname;
		 	$e->email =$email ;
		 	$e->password=$password ;
		  	$e->gender=strtoupper($gender);
			$e->dob=$dob;
		  	$e->licenseid=$licenseid;
		 	$e->mobile =$mobile;
		 	$e->country =$country;
		 	$e->state= $state;
		 	$e->city=$city;
		 	$e->address= $request->address;
		 	$e->driver_id = $c.$lid;
			$e->save();
    }
}
