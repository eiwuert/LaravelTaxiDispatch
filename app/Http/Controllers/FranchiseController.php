<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Country;
use App\Franchise;
use App\User;
use App\Role;
use Session;
use DB;
use Crypt;

class FranchiseController extends Controller
{
    //
    public function add(Request $request)
    {
    	$countrylist = Country::all();
    	return view('franchise.add_franchise', ['country_list' => $countrylist]);
    }

    // Get id and display edit form 
    public function edit(Request $request,$id){

    	$countrylist = Country::all();
    	$data = Franchise::where('id','=',$id)->first();
//print_r($data); exit;
    	return view('franchise.edit_franchise',['data'=>$data,'country_list'=>$countrylist]);
    }

    // View Franchise
    public function view(Request $request,$id){
        $countrylist = Country::all();
        $data = Franchise::where('id','=',$id)->first();

        return view('franchise.view_franchise',['data'=>$data,'country_list'=>$countrylist]);
    }
    // Manage franchise
    public function manage(Request $request)
    {
    	$countrylist = Country::all();
    	$activefranchise = Franchise::where('status','=',1)->get();
    	$blockedfranchise = Franchise::where('status','=',0)->get();
    	return view('franchise.manage_franchise', ['activefranchise' => $activefranchise,'blockedfranchise'=>$blockedfranchise]);
    }

    // Method to update edited value into database
    public function update(Request $request){

    	// Encrypt password
        $password = Hash::make($request->password);


			 // Check franchise already present for district(city)
        $district = Franchise::where('city','=', $request->city)->where('id','!=', $request->id)->count();
     		if($district > 0){
            return back()->withInput()->with('message', 'Franchise already present for this District');
        }

        $emailc = Franchise::where('email', '=', $request->email)->where('id','=',$request->id)->count();
        if($emailc == 0){
		      $emailcheck = Franchise::where('email', '=', $request->email)->count();
		      if ($emailcheck > 0) {
		          return back()->withInput()->with('message', 'Email ID Already Present');
		       }
    		}
		
				$service_tax = Franchise::where('service_tax_number', '=', $request->service_tax_number)->where('id','=',$request->id)->count();
				if($service_tax == 0){
					$service_taxcheck = Franchise::where('service_tax_number', '=', $request->service_tax_number)->count();
					if ($service_taxcheck > 0) {
						return back()->withInput()->with('message', 'Service tax number Already Present');
					}
				}

         $mobilec = Franchise::where('mobile', '=', $request->mobile)->where('id','=',$request->id)->count();
         if($mobilec == 0){
            $mobilecheck = Franchise::where('mobile', '=', $request->mobile)->count();
        if ($mobilecheck > 0) {
            return back()->withInput()->with('message', 'Mobile number Already Present');
         }
         }

        $taximage = $request->file('service_tax_image');
        if($taximage){

            $extension = $request->file('service_tax_image')->getClientOriginalExtension(); 
            $random_name = rand(11111,99999).'.'.$extension; // renaming image
            $path = public_path('uploads/servicetaxpic/' . date('Ymd'));
            $taximage->move($path,$random_name);

        }   

        // Check landline number already exits
         if($request->landlinenumber){

            $check_landline = Franchise::where('landline','=',$request->landlinenumber)
                                ->where('id','=',$request->id)
                                ->count();
            if($check_landline == 0){
                $check_landline1 = Franchise::where('landline','=',$request->landlinenumber)->count();
                if($check_landline1 > 0){
                    return back()->withInput()->with('message', 'Landline number Already Present');
                }
                
            }
         }    


			//update main table also
			
			      // Encrypt password
			      
			  	$password = Hash::make($request->password);
  		 $searchuserid = Franchise::find($request->id);
			 	
	 			$user = User::find($searchuserid->user_id);
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        if($request->password !=""){
        $user->password = $password;
        }
        $user->save();
       
        //end of user login table
 
    	$add = Franchise::find($request->id);
    	$add->first_name = $request->firstname;
    	$add->last_name = $request->lastname;
    	$add->email = $request->email;
        // update landline number
        if($request->landlinenumber){
            $add->landline = $request->landlinenumber;
        }
    	$add->password = $password;
    	$add->mobile = $request->mobile;
    	$add->address = $request->address;
    	$add->company_name = $request->companyname;
    	$add->country = $request->country;
    	$add->state = $request->state;
    	$add->city = $request->city;
        $add->service_tax_number = $request->service_tax_number;
    	$add->company_address = $request->companyaddress;
        if($taximage){
            $add->service_tax_image = '/uploads/servicetaxpic/'.date('Ymd').'/'.$random_name;
        }
    	$add->save();

    	Session::flash('message', "Franchise details updated");
        return redirect()->intended('/manage_franchise');
    }
    // Insert data to database for new franchise from form
    public function insert(Request $request){

        // Check franchise already present for district(city)
        $district = Franchise::where('city','=',$request->city)->count();
        if($district != 0){
            return back()->withInput()->with('message', 'Franchise already present for this District');
        }

    	$emailcheck = Franchise::where('email', '=', $request->email)->count();
        if ($emailcheck > 0) {
            return back()->withInput()->with('message', 'Email ID Already Present');
         }

         $service_taxcheck = Franchise::where('service_tax_number', '=', $request->service_tax_number)->count();
        if ($service_taxcheck > 0) {
            return back()->withInput()->with('message', 'Service tax number Already Present');
         }

         $mobilecheck = Franchise::where('mobile', '=', $request->mobile)->count();
        if ($mobilecheck > 0) {
            return back()->withInput()->with('message', 'Mobile number Already Present');
         }

         // Check landline number already exits
         if($request->landlinenumber){

            $check_landline = Franchise::where('landline','=',$request->landlinenumber)->count();
            if($check_landline > 0){
                return back()->withInput()->with('message', 'Landline number Already Present');
            }
         }

         $taximage = $request->file('service_tax_image');
         $extension = $request->file('service_tax_image')->getClientOriginalExtension(); 
         $random_name = rand(11111,99999).'.'.$extension; // renaming image
         $path = public_path('uploads/servicetaxpic/' . date('Ymd'));
         $taximage->move($path,$random_name);
        // Encrypt password
		$password = Hash::make($request->password);

        $user = new User();
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->password = $password;
        $user->save();
        $id = $user->id;

    	$add = new Franchise();
        $add->user_id = $id;
        // Add landline number
        if($request->landlinenumber){
            $add->landline = $request->landlinenumber;
        }
    	$add->first_name = $request->firstname;
    	$add->last_name = $request->lastname;
    	$add->email = $request->email;
    	$add->password = $password;
    	$add->mobile = $request->mobile;
    	$add->address = $request->address;
    	$add->company_name = $request->companyname;
    	$add->country = $request->country;
    	$add->state = $request->state;
    	$add->city = $request->city;
    	$add->company_address = $request->companyaddress;

        $add->service_tax_image = '/uploads/servicetaxpic/'.date('Ymd').'/'.$random_name;
        $add->service_tax_number = $request->service_tax_number;
    	$add->save();
        $d = date('Y-m-d H:i:s');
        DB::table('user_role')->insert(
    ['user_id' => $id, 'role_id' => 3, 'created_at' => $d, 'updated_at' => $d]
);
        // $role = new Role();
        // $role->user_id = $id;
        // $role->role_id = 3;
        // $role->save();

    	Session::flash('message', "New Franchise Added");
        return redirect()->intended('/manage_franchise');


    }

    // Method to Activate the franchise from ajax post
    public function activatefranchise(Request $request)
    {
        $status = 1;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');
            Franchise::where('id', '=', $taxi_id)->update(['status' => $status]);
            return response()->json([
                'Response' => 'Franchise successfully activated',
                'Status' => 'Success'
            ]);
        }
    }

    // Method to Block the franchise from ajax post
    public function blockfranchise(Request $request)
    {
        $status = 0;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');
            Franchise::where('id', '=', $taxi_id)->update(['status' => $status]);
            return response()->json([
                'Response' => 'Franchise successfully blocked',
                'Status' => 'Success'
            ]);
        }
    }

    // Method to Delete the franchise from ajax post
    public function deletefranchise(Request $request)
    {
        $status = 2;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');
            Franchise::where('id', '=', $taxi_id)->update(['status' => $status]);
            return response()->json([
                'Response' => 'Franchise successfully deleted',
                'Status' => 'Success'
            ]);
        }
    }
}
