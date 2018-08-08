<?php

namespace App\Http\Controllers;
if(!defined('FIREBASE_API_KEY')) define("FIREBASE_API_KEY", "AIzaSyAPbEBHf_6y0oZbMuGM3H3c_TyI7NPY8wU");
if(!defined('FIREBASE_FCM_URL')) define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Driver;
use App\Car;
use App\Fare;
use App\CarBrand;
use App\Country;
use App\CarModel;
use App\RideType;
use App\CarType;
use App\VehicleCategory;
use App\AssignTaxi;
use App\Franchise;
use Session;
use DB;


class AttachedDriversController extends Controller
{

    public function __construct()
    {
        $this->driver = new Driver();
    }

    public function AddAttachedDrivers(Request $request)
    {

        $ridetype = RideType::all();
        $franchise = Franchise::where('status','=',1)->get();
        $countrylist = Country::all();
        $carbrand = CarBrand::where('status', 1)->get();
        $carmodel = CarModel::where('status', 1)->get();
        $cartype = CarType::where('status', 1)->get();
        return view('attached_drivers.Add_Attached_Drivers', ['carbrand' => $carbrand, 'carmodel' => $carmodel, 'cartype' => $cartype, 'country_list' => $countrylist, 'franchise' => $franchise, 'ridetype' => $ridetype]);

    }

    public function addattacheddriver(Request $request)
    {

        return view('test');
    }


    public function ManageAttachedDrivers(Request $request)
				{
                    $ride_category=$request->input('ride_category');
                    $franchiseid=$request->input('Franchise');
					$ctypeid=$request->input('ctype');

						$role=Session::get('user_role');
							switch($role)
						  {
						      case "1" : //ADMIN Dashboard
						         return view('attached_drivers.Manage_Attached_Drivers',$this->driverlist_all($ride_category,$franchiseid,$role,$ctypeid));
						         break;
						      case "3" : //Franchise Dashboard
						      	return view('attached_drivers.Manage_Attached_Drivers',$this->franchise_driver($ride_category,$role));
						          break;
						      default :
						          return redirect('/');
						  }
			}
	
	/*********GET COMPLETE ATACHED DRIVER LIST**********/
    public function driverlist_all($ride_category=0,$franchiseid=0,$role,$ctypeid=0)
    {


        $ctype = CarType::all();
        // echo json_encode($ctype); 

        if($ctypeid != 0){

            session(['cf_ctype' => $ctypeid]);

                    if($franchiseid != 0){

            $franchise = Franchise::all();
            session(['cf_franchise' => $franchiseid]);
            session(['cf_driver' => $ride_category]);
       if( (session('cf_driver') !=NUll) && (session('cf_driver') !=0)){

            $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
         ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

       }
       else{
     
        $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
           ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();
        }
        }else{
            $franchise = Franchise::all();
            session(['cf_franchise' => 0]);
              session(['cf_driver' => $ride_category]);
       if( (session('cf_driver') !=NUll) && (session('cf_driver') !=0)){

            $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
          ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
         ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

       }
       else{
     
        $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
           ->where('wy_cartype.id','=',$ctypeid)
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();
        }
    }

        }

else{
    session(['cf_ctype' => 0]);
        if($franchiseid != 0){
            $franchise = Franchise::all();
            session(['cf_franchise' => $franchiseid]);
            session(['cf_driver' => $ride_category]);
       if( (session('cf_driver') !=NUll) && (session('cf_driver') !=0)){

            $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
          
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
         
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

       }
       else{
     
        $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
           
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id', '=', $franchiseid)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();
        }
        }else{
            $franchise = Franchise::all();
            session(['cf_franchise' => 0]);
              session(['cf_driver' => $ride_category]);
       if( (session('cf_driver') !=NUll) && (session('cf_driver') !=0)){

            $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
          
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
         
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

       }
       else{
     
        $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
           
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.driver_type', '=', 2)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();
        }
    }
}
        
        //   var_dump($blocked_driver);
        $franchis_id = 0;
            $ride_category = VehicleCategory::all();
        $attach_driver_details=array('franchise'=>$franchise,'active_driver' => $active_driver, 'blocked_driver' => $blocked_driver,'ride_category' => $ride_category,'role' => $role,'ctype' => $ctype,'franchis_id' => $franchis_id);
        	return $attach_driver_details;
    }
//**********GET Franchise ATTACHED DRIVER LIST *************//


    public function franchise_driver($ride_category,$role)
    {
        $ctype = CarType::all();
   			 //get the Franchise ID
				  $Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
				  $franchis_id=$Franchise[0]->id;
				  
        $franchise = Franchise::where('status','=',1)->get();
        session(['cf_driver' => $ride_category]);
       if( (session('cf_driver') !=NUll) && (session('cf_driver') !=0)){

            $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id','=',$franchis_id)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.ride_category','=',session('cf_driver'))
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id','=',$franchis_id)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

       }
       else{
        $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_carlist.ride_category')
            ->where('wy_driver.profile_status', '=', 1)
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id','=',$franchis_id)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();

        $blocked_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_carlist.ride_category')
            ->where('wy_driver.profile_status', '=', -1)
            ->where('wy_driver.driver_type', '=', 2)
            ->where('wy_driver.franchise_id','=',$franchis_id)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type','wy_cartype.car_board')
            ->get();
        }
         $ride_category = VehicleCategory::all();
            $attach_driver_details=array('franchise'=>$franchise,'active_driver' => $active_driver, 'blocked_driver' => $blocked_driver,'ride_category' => $ride_category,'role' => $role,'ctype' => $ctype,'franchis_id' => $franchis_id);
        	return $attach_driver_details;
    }


    public function InsertAttachedDrivers(Request $request)
    {

        if ($request->olddriverid == 1) {
            $data = array();
            $data = $request->all();
            //var_dump($data);
            //$ca = new car();
            $driver = new Driver;

            $car = new Car;

            $assign_taxi = new AssignTaxi;

            // $s = strtoupper(md5(uniqid(rand(),true))); 
            // $s1 = strtoupper(md5(uniqid(rand(),true))); 

            $randnum = rand(111111, 999999);
            $randnum1 = rand(111111, 999999);
            $randnum2 = rand(111111, 999999);

            $rc_image = $request->file('rc_image');
            $insurance_image = $request->file('insurance_image');
            $driverpic = $request->file('profilepicture');
            $carpic = $request->file('taxipicture');

            $ext = $rc_image->getClientOriginalExtension();
            if(($ext != 'jpg')  && ($ext != 'png'))
                {
                    return back()->withInput()
                            ->with('fail_message','Unsupported image format');
                }

            $ext = $insurance_image->getClientOriginalExtension();
            if(($ext != 'jpg')  && ($ext != 'png'))
                {
                    return back()->withInput()
                            ->with('fail_message','Unsupported image format');
                }

            $ext = $driverpic->getClientOriginalExtension();
            if(($ext != 'jpg')  && ($ext != 'png'))
                {
                    return back()->withInput()
                            ->with('fail_message','Unsupported image format');
                }

            $ext = $carpic->getClientOriginalExtension();
            if(($ext != 'jpg')  && ($ext != 'png'))
                {
                    return back()->withInput()
                            ->with('fail_message','Unsupported image format');
                }

            if($request->email){

                $emailcheck = Driver::where('email', '=', $request->email)->count();

                if ($emailcheck > 0) {
                    Session::flash('fail_message', "Email ID Already Present");
                    return redirect()->intended('/add_attached_drivers');
                }
            }
            

            $license = Driver::where('licenseid', '=', $request->licenseid)->count();
            if ($license > 0) {
                Session::flash('fail_message', "License id already exists");
                return redirect()->intended('/add_attached_drivers');
            }

            $mobile = Driver::where('mobile', '=', $request->mobile_number)->count();

            if ($mobile > 0) {
                Session::flash('fail_message', "mobile number already exists");
                return redirect()->intended('/add_attached_drivers');
            }

            $taxi_number_check = Car::where('car_no', '=', $request->taxi_number)->count();
            if ($taxi_number_check > 0) {
                Session::flash('fail_message', "Vehicle number Already Present");
                return redirect()->intended('/add_attached_drivers');
            }

            $rc_number_check = Car::where('rc_no', '=', $request->rc_number)->count();
            if ($rc_number_check > 0) {
                Session::flash('fail_message', "RC number Already Present");
                return redirect()->intended('/add_attached_drivers');
            }

			$fare_check = Fare::where('car_id', '=', $request->taxi_type)->where('franchise_id','=',$request->franchise)->count();
            if ($fare_check <= 0) {
                Session::flash('fail_message', "First add a Base Fare  for the selected vehicle type");
                return redirect()->intended('/add_attached_drivers');
            }
            // $destinationPath = '/uploads'; // Directory for Image Upload
            $destination_rc = public_path('uploads/rc_book/' . date("dmY")); // Directory for Image Upload
            $destination_rcdb = '/uploads/rc_book/' . date("dmY"); // Directory for Image Upload
            
            $destination_license = public_path('uploads/license/' . date("dmY")); // Directory for Image Upload
            $destination_licensedb = '/uploads/license/' . date("dmY"); // Directory for Image Upload
            
            $destination_insurance = public_path('uploads/insurance/' . date("dmY")); // Directory for Image Upload
            $destination_insurancedb = '/uploads/insurance/' . date("dmY"); // Directory for Image Upload

            $destination_insurance1 = public_path('uploads/driverpic/' . date("dmY")); // Directory for Image Upload
            $destination_insurance1db = '/uploads/driverpic/' . date("dmY"); // Directory for Image Upload

            $destination_insurance2 = public_path('uploads/carpic/' . date("dmY")); // Directory for Image Upload
            $destination_insurance2db = '/uploads/carpic/' . date("dmY"); // Directory for Image Upload

           

            $rc_image->move($destination_rc, $randnum . '.' . $rc_image->getClientOriginalExtension());
            $insurance_image->move($destination_insurance, $randnum1 . '.' . $insurance_image->getClientOriginalExtension());
            $driverpic->move($destination_insurance1, $randnum1 . '.' . $driverpic->getClientOriginalExtension());
            $carpic->move($destination_insurance2, $randnum1 . '.' . $carpic->getClientOriginalExtension());

            $date = date_create($data['insurance_exp_date']);
            $expdate = date('Y-m-d',strtotime($data['insurance_exp_date']));
            if($request->franchise){
                $car->franchise_id =$request->input('franchise');
                $car->isfranchise =1;
            }
            else{
                $car->franchise_id ='0';
                $car->isfranchise ='0';
            }

            $car->car_attached = 2;
            $car->status = 1;
            $car->ride_category = $data['ridetype'];
            $car->car_no = $data['taxi_number'];
            $car->brand = $data['taxi_brand'];
            $car->model = $data['taxi_model'];
            $car->car_type = $data['taxi_type'];
            //$car->capacity = $data['capacity'];
            $car->country = $data['country'];
            $car->state = $data['state'];
            $car->city = $data['city'];
            $car->rc_no = $data['rc_number'];
            $car->rc_image = $destination_rcdb . '/' . $randnum .'.' . $rc_image->getClientOriginalExtension();
            $car->vehical_image = $destination_insurance2db . '/' . $randnum1 . '.' . $carpic->getClientOriginalExtension();
            $car->insurance_image = $destination_insurancedb . '/' . $randnum1 . '.' .$insurance_image->getClientOriginalExtension();
            $car->insurance_expiration_date = $expdate;
            $car->created_at = date("Y/m/d");
            $car->updated_at = date("Y/m/d");
            $value = $request->session()->get('Admin__userid');
            $car->created_by = 1;
            $car->updated_by = 1;
            $car->save();

            $i = 'ID';
            $idc = rand(11111, 99999);
            $ida = $i . $idc;
            $driver->driver_id = $ida;
            // $driver->car_id = $car->id;
            $key = hash('sha256', 'wrydes');
            $iv = substr(hash('sha256', 'dispatch'), 0, 16);
            $en_password = openssl_encrypt($data['password'], "AES-256-CBC", $key, 0, $iv);
            $encrypt_password = base64_encode($en_password);
            $driver->password = $encrypt_password;
            $driver->firstname = $data['driver_first_name'];
            $driver->lastname = $data['driver_last_name'];
            if($request->email){
            $driver->email = $data['email'];
            }else{
                $driver->email = "";
            }
            $driver->gender = $data['gender'];
            $driver->address = $data['address'];
            $originalDate = $data['dob'];
            $newDate = date("Y-m-d", strtotime($originalDate));
            $driver->dob = $newDate;
            $driver->ride_category = $data['ridetype'];
            $driver->profile_photo = $destination_insurance1db . '/' . $randnum1 . '.' . $driverpic->getClientOriginalExtension();
            $driver->driver_type = 2;
            $driver->profile_status = 1;
            $driver->country = $data['country'];
            $driver->state = $data['state'];
            $driver->city = $data['city'];
            //$driver->address = $data['address'];
            $driver->mobile = $data['mobile_number'];
            $driver->licenseid = $data['licenseid'];
            $s2 = strtoupper(md5(uniqid(rand(), true)));
            $lic_image = $request->file('driver_license_number');
            $lic_image->move($destination_license, $randnum2 . '.' . $lic_image->getClientOriginalExtension());
            $driver->license = $destination_licensedb . '/' . $randnum2 . '.' . $lic_image->getClientOriginalExtension();
            if($request->franchise){
                $driver->franchise_id =$request->input('franchise');
                $driver->isfranchise =1;
            }
            $driver->save();

            $assign_taxi->driver_id = $driver->id;
            $assign_taxi->car_num = $car->id;
            $assign_taxi->ride_category = $data['ridetype'];
            $assign_taxi->country = $data['country'];
            $assign_taxi->state = $data['state'];
            $assign_taxi->city = $data['city'];
            $assign_taxi->owner_ship = 1;
            $assign_taxi->created_by = 1;
            $assign_taxi->updated_by = 1;
            $assign_taxi->save();

            Session::flash('success_message', "Driver Added Successfully");
            $path = 'manage_attached_drivers';
            return redirect()->intended($path);
        } else {
            $id = $request->id;
            $data = array();
            $data = $request->all();
            $t = new Driver;
            $ca = new Car;

            if($request->email){
            $emailcheck = Driver::where('email', '=', $request->email)
                 ->where('driver_id','=',$request->olddriverid)
                 ->count();
                if ($emailcheck == 0) {
                    $emailcheck1 = Driver::where('email', '=', $request->email)->count();
                    if($emailcheck1 > 0){
                    //Session::flash('message', "Email already exist");
                    return back()->withInput()->with('fail_message', "Email already exist");
                    //return redirect()->intended('/add_driver');
                }
                }
            }

                $license = Driver::where('licenseid', '=', $request->licenseid)
                ->where('driver_id','=',$request->olddriverid)
                ->count();
                if ($license == 0) {
                    $license1 = Driver::where('licenseid', '=', $request->licenseid)->count();
                    if($license1 > 0){
                    Session::flash('fail_message', "License already exists");
                    return redirect()->intended('/add_driver');
                }
                }

                $mobile = Driver::where('mobile', '=', $request->mobile_number)->where('driver_id','=',$request->olddriverid)->count();
                if ($mobile == 0) {
                    $mobile1 = Driver::where('mobile', '=', $request->mobile_number)->count();
                    if($mobile1 > 0){
                    Session::flash('fail_message', "mobile number already exists");
                    return redirect()->intended('/add_driver');
                }
            }
            $driver = $t::find($id);

            $carnum = DB::table('wy_assign_taxi')
                ->where('driver_id', '=', $id)
                ->select('car_num')->first();
            // $driver->firstname =  $data['driver_first_name'];

            $car = $ca::find($carnum->car_num);

            $s = strtoupper(md5(uniqid(rand(), true)));
            $s1 = strtoupper(md5(uniqid(rand(), true)));
            $rc_image = $request->file('rc_image');
            $insurance_image = $request->file('insurance_image');
            

            $destination_rc = public_path('uploads/rc_book/' . date("dmY")); // Directory for Image Upload
            $destination_rcdb = '/uploads/rc_book/' . date("dmY"); // Directory for Image Upload
            
            $destination_license = public_path('uploads/license/' . date("dmY")); // Directory for Image Upload
            $destination_licensedb = '/uploads/license/' . date("dmY"); // Directory for Image Upload
            
            $destination_insurance = public_path('uploads/insurance/' . date("dmY")); // Directory for Image Upload
            $destination_insurancedb = '/uploads/insurance/' . date("dmY"); // Directory for Image Upload

            $destination_insurance1 = public_path('uploads/driverpic/' . date("dmY")); // Directory for Image Upload
            $destination_insurance1db = '/uploads/driverpic/' . date("dmY"); // Directory for Image Upload

            $destination_insurance2 = public_path('uploads/carpic/' . date("dmY")); // Directory for Image Upload
            $destination_insurance2db = '/uploads/carpic/' . date("dmY"); // Directory for Image Upload



            $rc_image->move($destination_rc, $s . '.' . $rc_image->getClientOriginalExtension());
            $insurance_image->move($destination_insurance, $s1 . '.' . $insurance_image->getClientOriginalExtension());

            $date = date_create($data['insurance_exp_date']);
            $expdate = date_format($date, "Y-m-d");


            $car->status = 1;
            $car->car_no = $data['taxi_number'];
            $car->brand = $data['taxi_brand'];
            $car->model = $data['taxi_model'];
            $car->car_type = $data['taxi_type'];
            $car->capacity = $data['capacity'];
            $car->rc_no = $data['rc_number'];
            $car->rc_image = $destination_rcdb . $s . '.' . $rc_image->getClientOriginalExtension();
            $car->insurance_image = $destination_insurancedb . $s1 . '.' . $insurance_image->getClientOriginalExtension();
            $car->insurance_expiration_date = $expdate;
            $car->save();
            $car->id;
            //$driver->car_id = $car->id;
            $driver->profile_status = 1;

            $key = hash('sha256', 'wrydes');
            $iv = substr(hash('sha256', 'dispatch'), 0, 16);
            $output = openssl_encrypt($data['password'], "AES-256-CBC", $key, 0, $iv);
            $output2 = base64_encode($output);

            $driver->password = $output2;
            $driver->firstname = $data['driver_first_name'];
            $driver->lastname = $data['driver_last_name'];
            if($request->email){
            $driver->email = $data['email'];
            }
            $driver->gender = $data['gender'];
            $driver->dob = $data['dob'];
            $driver->mobile = $data['mobile_number'];
            $s2 = strtoupper(md5(uniqid(rand(), true)));
            $lic_image = $request->file('driver_license_number');
            $lic_image->move($destination_license, $s2 . '.' . $lic_image->getClientOriginalExtension());
            $driver->license = $destination_licensedb . $s2 . '.' . $lic_image->getClientOriginalExtension();
            $driver->save();
            Session::flash('attached_message', "Driver Details Updated Successfully");
            $path = 'edit_attached_driver/' . $id;
            return redirect()->intended($path);
        }
    }


    public function EditAttachedDrivers(Request $request, $id)
    {

        
        // Check in ride
        $check5 = DB::table('wy_ridedetails')
                ->where('driver_id','=',$id)
                ->whereIn('ride_status',array(0, 1, 2,3))
                ->whereIn('accept_status',array(0,1))->count();

        if($check5 != 0){

            return back()->with('attached_message','Driver is in ride');
        }
        $data = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_driver.id', '=', $id)
            ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_carlist.brand', 'wy_carlist.capacity', 'wy_carlist.rc_no', 'wy_carlist.insurance_image', 'wy_carlist.rc_image', 'wy_carlist.insurance_expiration_date', 'wy_carlist.model', 'wy_carlist.car_type', 'wy_cartype.car_type', 'wy_cartype.car_board','wy_carlist.vehical_image')
            ->first();
            // print_r($data);exit;
        $rc = RideType::all();
        $countrylist = Country::all();
        $franchise = Franchise::where('status',1)->get();
        $carbrand = CarBrand::where('status', 1)->get();
        $carmodel = CarModel::where('status', 1)->get();
        $cartype = CarType::where('status', 1)->get();
        //  var_dump($data);    
        $pass = $data->password;
        $key = hash('sha256', 'wrydes');
        $iv = substr(hash('sha256', 'dispatch'), 0, 16);
        $password = openssl_decrypt(base64_decode($pass), "AES-256-CBC", $key, 0, $iv);

        return view('attached_drivers.Edit_Attached_Drivers', ['rc' => $rc,'franchise' => $franchise,'carbrand' => $carbrand, 'carmodel' => $carmodel, 'cartype' => $cartype, 'country_list' => $countrylist, 'data' => $data, 'password' => $password]);
    }

    public function UpdateAttachedDrivers(Request $request)
    {
        $id = $_POST['id'];
        $data = array();
        $data = $request->all();
        $t = new Driver;
        $ca = new Car;
        $driver = $t::find($id);
		$device_type=$driver->device_type;
		$device_token=$driver->device_token;
        // $driver->firstname =  $data['driver_first_name'];
        $carnum = DB::table('wy_assign_taxi')
            ->where('driver_id', '=', $id)
            ->select('car_num')->first();

        $car = $ca::find($carnum->car_num);

        // Already presented date checking in DB -----------------------

            if($data['email']){
                // Check given email id already present for another driver 
            $emailcheck = Driver::where('email', '=', $data['email'])->where('id','=',$id)->count();
            // $emailcheck has check already this user has email id posted
                if ($emailcheck == 0) {
                    $emailcheck1 = Driver::where('email', '=', $data['email'])->count();
                        if($emailcheck1 > 0){
                    //Session::flash('message', "Email already exist");
                        return back()->withInput()->with('fail_message', "Email already exist");
                    //return redirect()->intended('/add_driver');
                }
                }
            }
            

                // Check given email id already present for another driver 
            $mobilecheck = Driver::where('mobile', '=', $data['mobile_number'])->where('id','=',$id)->count();
            // $emailcheck has check already this user has email id posted
                if ($mobilecheck == 0) {
                    $mobilecheck1 = Driver::where('mobile', '=', $data['mobile_number'])->count();
                        if($mobilecheck1 > 0){
                    //Session::flash('message', "Email already exist");
                        return back()->withInput()->with('fail_message', "Mobile number already exist");
                    //return redirect()->intended('/add_driver');
                }
                } 
                // Check given email id already present for another driver 
            $licensecheck = Driver::where('licenseid', '=', $data['licenseid'])->where('id','=',$id)->count();
            // $emailcheck has check already this user has email id posted
                if ($licensecheck == 0) {
                    $licensecheck1 = Driver::where('licenseid', '=', $data['licenseid'])->count();
                        if($licensecheck1 > 0){
                    //Session::flash('message', "Email already exist");
                        return back()->withInput()->with('fail_message', "License id already exist");
                    //return redirect()->intended('/add_driver');
                }
                }

            $taxi_numbercheck = Car::where('car_no', '=', $data['taxi_number'])->where('id','=',$carnum->car_num)->count();
                if ($taxi_numbercheck == 0) {
                    $taxi_numbercheck1 = Car::where('car_no', '=', $data['taxi_number'])->count();
                        if($taxi_numbercheck1 > 0){
                    //Session::flash('message', "Email already exist");
                        return back()->withInput()->with('fail_message', "Vehicle number already exist");
                    //return redirect()->intended('/add_driver');
                        }
                }

            $rc_numbercheck = Car::where('rc_no', '=', $data['rc_number'])->where('id','=',$carnum->car_num)->count();
                if ($rc_numbercheck == 0) {
                    $rc_numbercheck1 = Car::where('rc_no', '=', $data['rc_number'])->count();
                        if($rc_numbercheck1 > 0){
                    //Session::flash('message', "Email already exist");
                        return back()->withInput()->with('fail_message', "RC number already exist");
                    //return redirect()->intended('/add_driver');
                        }
                }
 
        //var_dump($car);
        //echo $car->car_no;
        //$driver->save();

          $franchise_value = $request->franchise;

        $s = mt_rand(111111, 999999);
        $s1 = mt_rand(111111, 999999);
        $rc_image = $request->file('rc_image');
        $insurance_image = $request->file('insurance_image');
        
        $vehicleimage = $request->file('vehiclepicture');
        if($vehicleimage){
        $ext = $vehicleimage->getClientOriginalExtension();
        if(($ext != 'jpg') && ($ext != 'png'))
        {
            return back()->withInput()
                            ->with('fail_message','Unsupported image format');
        }
        $vehiclepath = public_path('uploads/vehiclepic/' . date("dmY"));
        $vehicleimage->move($vehiclepath, $s . '.' . $vehicleimage->getClientOriginalExtension());
        }
        $destination_rc = public_path('uploads/rc_book/' . date("dmY")); // Directory for Image Upload
            $destination_rcdb = '/uploads/rc_book/' . date("dmY"); // Directory for Image Upload
            
            $destination_license = public_path('uploads/license/' . date("dmY")); // Directory for Image Upload
            $destination_licensedb = '/uploads/license/' . date("dmY"); // Directory for Image Upload
            
            $destination_insurance = public_path('uploads/insurance/' . date("dmY")); // Directory for Image Upload
            $destination_insurancedb = '/uploads/insurance/' . date("dmY"); // Directory for Image Upload

             
        $destinationPathr = $destination_rc; // Directory for Image Upload
        $destinationPathi = $destination_insurance; // Directory for Image Upload

        if($rc_image){
            $rc_image->move($destinationPathr, $s . '.' . $rc_image->getClientOriginalExtension());
        }
        
        if($insurance_image){
            $insurance_image->move($destinationPathi, $s1 . '.' . $insurance_image->getClientOriginalExtension());
        }
        

        $date = $data['insurance_exp_date'];
        $expdate = date("Y-m-d",strtotime($date));
        
        
        $car->car_no = $data['taxi_number'];
        $car->brand = $data['taxi_brand'];
        $car->model = $data['taxi_model'];
        if($vehicleimage){
            $car->vehical_image = '/uploads/vehiclepic/'.date("dmY").'/'.$s.'.'.$ext;
        }
        
        //$car->car_type = $data['VehicleType'];
       // $car->capacity = $data['capacity'];
        //$car->country = $data['country'];
        //$car->state = $data['state'];
        //$car->city = $data['city'];
        
       /*  if($request->Franchise == 0){
            $car->isfranchise = 0;
            $car->franchise_id;
        }
        else{
            $car->franchise_id = $franchise_value;
        if($franchise_value != 0){
            $car->isfranchise = 1;
        }
        else{
            $car->isfranchise = 0;
        }
        } */

        $car->rc_no = $data['rc_number'];
        if($rc_image){
        $car->rc_image = $destination_rcdb . '/'.$s . '.' . $rc_image->getClientOriginalExtension();}
        if($insurance_image){
        $car->insurance_image = $destination_insurancedb .'/'. $s1 . '.' . $insurance_image->getClientOriginalExtension();}
        $d = explode('/', $date);
        $de = $d[2]."-".$d[0]."-".$d[1];
        $car->insurance_expiration_date = $de; 
        $car->car_type = $request->taxi_type;
        $car->ride_category = $request->ridetype;
        

        $car->save();
        $car->id;
 
        //$driver->car_id = $car->id;
        $key = hash('sha256', 'wrydes');
        $iv = substr(hash('sha256', 'dispatch'), 0, 16);
        $output = openssl_encrypt($data['password'], "AES-256-CBC", $key, 0, $iv);
        $output2 = base64_encode($output);
        $profilepicture = $request->file('profilepicture');
    if($profilepicture){
        
        $ext = $profilepicture->getClientOriginalExtension();
        if(($ext != 'jpg') && ($ext != 'png'))
        {
            return back()->withInput()
                            ->with('fail_message','Unsupported image format');
        }
        $profilepath = public_path('uploads/driverpic/' . date("dmY"));
        $profilepicture->move($profilepath, $s . '.' . $profilepicture->getClientOriginalExtension());
        $driver->profile_photo = '/uploads/driverpic/'.date("dmY").'/'.$s.'.'.$ext;
    }


            $driver->ride_category = $request->ridetype;
        
        /* if($request->Franchise == 0){
            $driver->isfranchise = 0;
            $driver->franchise_id;
        }
        else{
        $driver->franchise_id = $franchise_value;
        if($franchise_value != 0){
            $driver->isfranchise = 1;
        }
        else{
            $driver->isfranchise = 0;
         }
        } */
        $driver->password = $output2;
        $driver->firstname = $data['driver_first_name'];
        $driver->lastname = $data['driver_last_name'];
        if($data['email']){
            $driver->email = $data['email'];
        }else{
            $driver->email = '';
        }
        $driver->gender = $data['gender'];
        $driver->dob = $data['dob'];
        $driver->licenseid = $data['licenseid'];
        $driver->country = $data['country'];
        $driver->state = $data['state'];
        $driver->city = $data['city'];
        $driver->address = $data['address'];
        $destinationPathl = $destination_license;
        $driver->mobile = $data['mobile_number'];
        $s2 = mt_rand(111111, 999999);
        $lic_image = $request->file('driver_license_number');
    if($lic_image){
        $lic_image->move($destinationPathl, $s2 . '.' . $lic_image->getClientOriginalExtension());
        $driver->license = $destination_licensedb . '/'.$s2 . '.' . $lic_image->getClientOriginalExtension();
    }
        $driver->save();
		$message = "Profile updated";
		if($device_token!='' && $device_token!=NULL){
			if($device_type==1){
				$this->apns_cus($device_token, $message);
			}else{
				$this->send_gcm_notify($device_token, $message);
			} 
		}
		$old_type = $data['VehicleType_old'];
		$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/driver_location/$old_type/$id.json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$result = curl_exec($ch); 
		curl_close($ch); 
		
        Session::flash('success_message', "Driver Details Updated Successfully");
        $path = 'manage_attached_drivers';
        return redirect()->intended($path);
    }


    public function changestatus(Request $request)
    {
        $id = $_GET['id'];
        $status = $_GET['status'];
        $driver = new Driver;

        $driverdata = $driver::find($id);
        $driverdata->profile_status = $status;
        $driverdata->save();
        echo '<h3>Status Changed to ' . $status . '</h3>';
        $path = 'manage_attached_drivers';
        return redirect()->intended($path);

    }

    public function ViewAttachedDrivers(Request $request, $id)
    {
        $data = Driver::select('wy_driver.*','wy_cartype.car_type','wy_carlist.*')
			->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_driver.id', '=', $id)
            ->first();
        $countrylist = Country::all();
        $driver = Driver::where('id', $id)->get();
        $carbrand = CarBrand::where('status', 1)->get();
        $carmodel = CarModel::where('status', 1)->get();
        $cartype = CarType::where('status', 1)->get();
        // var_dump($driver);

        return view('attached_drivers.view_attached_drivers', ['carbrand' => $carbrand, 'carmodel' => $carmodel, 'cartype' => $cartype, 'country_list' => $countrylist, 'data' => $data, 'driver' => $driver]);
    }


// Block Attached driver -----------
    public function block_attached_driver(Request $request)
    {
        $status = -1;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');
            $this->driver->change_status($taxi_id, $status);
            
            $header = array();
            $header[] = 'Content-Type: application/json';
            $postdata = '{"status":"true"}';
            $ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_status/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
			
			$data = Driver::select('wy_cartype.car_board','wy_cartype.car_type')
			->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_driver.id', '=', $taxi_id)
            ->first();
			$old_type= $data['car_type']."_".$data['car_board'];
			$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/driver_location/$old_type/$taxi_id.json");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			$result = curl_exec($ch); 
			curl_close($ch);
			
            return response()->json([
                'Response' => 'Taxi Successfully Blocked',
                'Status' => 'Success'
            ]);
        }
    }


//get the state list based on country
    public function getstatelist(Request $request)
    {

        $statelist = State::where('country_id', '=', $request->input('data_id'))->get();
        return json_encode($statelist);
    }

    //get city list based on state id
    public function getcitylist(Request $request)
    {

        $statelist = City::where('state_id', '=', $request->input('data_id'))->get();
        $list = array();
        foreach ($statelist as $val) {
            $list [] = array(
                'id' => $val->id,
                'city' => $val->name
            );
        }
        return json_encode($list);
    }


    public function ActivateAttached(Request $request)
    {
        $status = 1;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');
            Driver::where('id', '=', $taxi_id)
                ->update([
                    'profile_status' => $status
                ]);
			$header = array();
            $header[] = 'Content-Type: application/json';
            $postdata = '{"status":"false"}';
            $ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_status/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
            $delete = DB::table('wy_assign_taxi')->where('driver_id', '=', $taxi_id)->update(['status' => 1]);
            return response()->json([
                'Response' => 'Attached Driver Successfully Activated',
                'Status' => 'Success'
            ]);
        }
    }

    public function BlockAttached(Request $request)
    {
        $status = -1;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');

            $checkdriver = DB::table('wy_assign_taxi')->where('driver_id','=',$taxi_id)->first();
         $carnum = $checkdriver->car_num;

         $driver_id = $taxi_id;


        $check5 = DB::table('wy_ridedetails')
                ->where('driver_id','=',$driver_id)
                ->whereIn('ride_status',array(0, 1, 2, 3))
                ->whereIn('accept_status',array(0,1))
                ->count();

        if($check5 != 0){

            return response()->json([
                    'Response' => 'Driver/Taxi is in a ride.Try after Sometime',
                    'Status' => '2'
                ]);
        }
         
         
         

            $delete = DB::table('wy_assign_taxi')->where('driver_id', '=', $taxi_id)->update(['status' => -1]);
            $this->driver->change_status($taxi_id, $status);
            
            $header = array();
            $header[] = 'Content-Type: application/json';
            $postdata = '{"status":"true"}';
            $ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_status/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
			
			$data = Driver::select('wy_cartype.car_board','wy_cartype.car_type')
			->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
            ->where('wy_driver.id', '=', $taxi_id)
            ->first();
			$old_type= $data['car_type']."_".$data['car_board'];
			$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/driver_location/$old_type/$taxi_id.json");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			$result = curl_exec($ch); 
			curl_close($ch);
			
            return response()->json([
                'Response' => 'Attached Driver Successfully Blocked',
                'Status' => '2'
            ]);
        }
    }

    public function ActivateAttachedDriver(Request $request)
    {
        $status = 1;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');
            $this->driver->change_status($taxi_id, $status);
			$header = array();
            $header[] = 'Content-Type: application/json';
            $postdata = '{"status":"false"}';
            $ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_status/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
            return response()->json([
                'Response' => 'Driver Successfully activated',
                'Status' => 'Success'
            ]);
        }
    }

    public function DeleteDriver(Request $request)
    {
        $status = 2;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');

            $c_num = DB::table('wy_assign_taxi')->where('driver_id', '=', $taxi_id)->select('car_num')->first();
            DB::table('wy_carlist')->where('id', '=', $c_num->car_num)->delete();
            $this->driver->change_status($taxi_id, $status);
            return response()->json([
                'Response' => 'Taxi Successfully Deactivated',
                'Status' => 'Success'
            ]);
        } else {
            echo 's';
        }
    }

    public function checkattacheduser(Request $request)
    {
        $id = $request->input('data_id');
        $data = DB::table('wy_driver')
            ->where('driver_id', '=', $id)
            ->where('profile_status', '=', 2)
            ->where('driver_type','=',2)
            ->first();
        $key = hash('sha256', 'wrydes');
        $iv = substr(hash('sha256', 'dispatch'), 0, 16);
        $pass = openssl_decrypt(base64_decode($data->password), "AES-256-CBC", $key, 0, $iv);

        $carnum = DB::table('wy_assign_taxi')
            ->where('driver_id', '=', $data->id)
            ->select('car_num')->first();

        //$tdata = DB::table('wy_carlist')
            //->where('id', '=', $carnum->car_num)
            //->select('*')->first();

        if ($data != "") {
           // return response()->json(['id' => $carnum->car_num,'firstname' => $tdata]);exit;
            return response()->json([
                'firstname' => $data->firstname, 
                'lastname' => $data->lastname,
                'email' => $data->email,
                'password' => $pass,
                'password1' => $pass,
                'licenseid' => $data->licenseid,
                'country' => $data->country,
                'dob' => $data->dob,
                'state' => $data->state,
                'city' => $data->city,
                'address' => $data->address,
                'mobile' => $data->mobile,
                'ride_category' => $data->ride_category,
                'licenseid' => $data->licenseid,
                'id' => $data->id,
                'Response' => $data,
                'Status' => 'Success'
            ]);
        } else {
            return response()->json([
                'Status' => 'Failed'
            ]);
        }
    }
	
	public function send_gcm_notify($reg_id, $message) {

		 $fields = array(
		  'to' => $reg_id ,
		  'priority' => "high",
		  'notification' => array( "tag"=>"chat", "body" => $message),
		 ); 

		 $headers = array(
		  'Authorization: key=AIzaSyAPbEBHf_6y0oZbMuGM3H3c_TyI7NPY8wU',
		  'Content-Type: application/json'
		 );  
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, FIREBASE_FCM_URL);
		 curl_setopt($ch, CURLOPT_POST, true);
		 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		 $result = curl_exec($ch);
		 if ($result === FALSE) {
		  die('Problem occurred: ' . curl_error($ch));
		 }
		 curl_close($ch);
		// echo $result;
		/*  Session::flash('message', trans('Offer Successfully Created'));
		return redirect('/manage_offers'); */

	}


	public function apns_cus($devicetoken,$message){
		 $key = 'profile';
		//$batch = intval($count);
		 $payload['aps'] = array('alert' => $message, 'sound' => 'default','badge' => 0,'notify_key'=>$key);
		 $payload = json_encode($payload);
		 //print_r($payload);
		 $apnsHost = 'gateway.sandbox.push.apple.com';
		 //$apnsHost = 'gateway.push.apple.com';
		 $apnsPort = 2195;
		 $apnsCert = 'api/GoPartner.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
		 //$apnsCert = 'api/GOPartner_pro.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
		 $options = array('ssl' => array(
		 'local_cert' => 'api/GoPartner.pem',
		 //'local_cert' => 'api/GOPartner_pro.pem',
		 'passphrase' => 'armor'
		 ));
		 $streamContext = stream_context_create();
		 stream_context_set_option($streamContext, $options);
		 $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
		 $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
		 fwrite($apns, $apnsMessage);
		 fclose($apns);
	}


}
