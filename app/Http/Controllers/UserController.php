<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use App\Role;
use App\User;
use Excel;
use PDF;
use App\Franchise;
use App\City;
class UserController extends Controller
{
    //
    public function updb(){

        for($i=1;$i<38;$i++){

            $state = file_get_contents('https://www.whizapi.com/api/v2/util/ui/in/indian-city-by-state?project-app-key=uyy2picx3o2l52hlblrzakl6&stateid='.$i);
        $s = json_decode($state);
        //print_r($s);
            foreach($s->Data as $d){

                $n = new City();
                $n->name = $d->city;
                $n->state_id = $i;
                $n->save();
            }
        }
        
    }

	 public function index(Request $request) 
    {
		
		if (Auth::viaRemember()) {
    //
		}
			 return view('login');
		
    }
	
    public function AdminLogin(Request $request) 
    {
		if(isset($_POST)){
			// Check username & password from form and database
			$token = $request->input('_token');
			$username = $request->input('username');
			$password = $request->input('password');
			$remember=$request->input('remember');
			if (Auth::attempt(['email' => $username, 'password' => $password], $remember)) {
				Session::flash('message', "Successfully Logged in");
            // Authentication passed...
            //***********CHECK USER ROLE 
             $rolestatus=Role::where('user_id','=',Auth::user()->id)->get();
			 if($rolestatus[0]->role_id==3){
				 $franchisestatus=Franchise::where('user_id','=',Auth::user()->id)->get();
				 if($franchisestatus[0]->status==1){
					 session(['user_role' => $rolestatus[0]->role_id]);
					return redirect()->intended('home'); 
				 }else{
					 Session::flash('message', "You have been blocked by admin");
					 return redirect()->back();
				 }
			 }else{
				session(['user_role' => $rolestatus[0]->role_id]);
				return redirect()->intended('home'); //admin login
			 }
         
			}else{
				Session::flash('message', "Invalid Login Credintials ..Please Try again Later");
				return redirect()->back();
				
			}
			
			//return redirect('home');
		}
    }

    // After login redirect to home page
    public function home()
    {
			if (Auth::check()) {
				return view('home');
			}
		}

    // Logout Service
    public function logout(Request $request)
    {
		unset($_COOKIE['tabstatus']);
   	Auth::logout();
		Session::flash('message', "Logout Successfully Done");
		return redirect()->intended('/');
    }

    public function forgotpassword(Request $request,$id,$token)
    {
    	 return view('resetpassword',['id' => $id,'token' => $token]);
    }

    public function resetpassword(Request $request,$id,$token)
    {
    	$email = urldecode(base64_decode($id));

    	$pass = $request->password;
    	$key = hash('sha256', 'wrydes');
        $iv = substr(hash('sha256', 'dispatch'), 0, 16);
        echo $output = openssl_encrypt($pass, "AES-256-CBC", $key, 0, $iv);
        $password = base64_encode($output);

    	$newtoken = bin2hex(openssl_random_pseudo_bytes(10));
    	$token1 = DB::table('wy_customer')
               ->where('email', '=',$email)->select('temp_token')->first();

        if($token1->temp_token == ""){
        	Session::flash('message', "Link expired.");
			return redirect()->intended('/forgotpassword/a/a');
        }
        if($token != $token1->temp_token)
        {
        	Session::flash('message', "Link expired.");
			return redirect()->intended('/forgotpassword/a/a');
        }
        $ch = DB::table('wy_customer')->where('email', '=',$email)->update(['password' =>$password,'temp_token' => $newtoken]);
        Session::flash('message_success', "Your password resetted Successfully");
			return redirect()->intended('/forgotpassword/resetted/Successfully');
    	  
    }
    
    // Excel export
    public function excel_export()
    {
 				/*Excel::create('New file', function($excel) {
					$excel->sheet('New sheet', function($sheet) {
					  $sheet->loadView('reports.index');
					});
				})->export('xls');*/
				
				//view('reports.rejected_ride.blade.php',['name' => 'vignesh','descp' => "Test desp"]);
				view()->share(['name' => 'vignesh','descp' => "Test desp"]);
		//	return view()->share('reports.index', ['name' => 'vignesh','descp' => "Test desp"]);
				$pdf = PDF::loadView('reports.index');
           return $pdf->download('reports.pdf');

		}

}
