<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

//Include for AddDriver Model
use App\Driver;
use App\CarType;
use App\AssignTaxi;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Country;
use App\Alert;
use App\Customer;
use App\DriverRating;
use App\RideType;
use App\Franchise;
use App\Car;
use Auth;
use DB;
use App\VehicleCategory;
use File;


class TaxiDriverController extends Controller
{
    //

    public function __construct()
    {
        $this->driver = new Driver();
    }

    // Driver functions ---------------------- Start ----------------------

    public function EditDriver(Request $request, $id)
    {
         $taxi_Status= DB::table('wy_ridedetails')
            ->join('wy_ride', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
            ->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
            ->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_ride.car_type')
            ->whereIn('wy_ridedetails.ride_status', array(0, 1, 2, 3))
            ->whereIn('wy_ridedetails.accept_status', array(0, 1))
            ->where('wy_assign_taxi.driver_id', '=',$id)
            ->select('wy_ridedetails.driver_id')
            ->get();

        $ride_category = VehicleCategory::all();
        $franchise = Franchise::all();
        $countrylist = Country::all();
        $data = Driver::where('id', $id)->get();
        $key = hash('sha256', 'wrydes');
        $iv = substr(hash('sha256', 'dispatch'), 0, 16);
        foreach ($data as $d) {
            $pass = $d->password;
        }

        $password = openssl_decrypt(base64_decode($pass), "AES-256-CBC", $key, 0, $iv);

        return view('driver.edit-driver', ['ride_category'=>$ride_category,'franchise'=>$franchise,'data' => $data, 'countrylist' => $countrylist, 'password' => $password]);
    }

    public function AddDriver(Request $request)
    {

        $ridetype = RideType::all();
        $countrylist = Country::all();
        return view('driver.add-driver', ['country_list' => $countrylist, 'ridetype' => $ridetype]);
    }

    public function ViewDriver(Request $request, $id)
    {
        $franchise = Franchise::all();
        $countrylist = Country::all();
        $data = array();
        $data = Driver::where('id', $id)->get();
        // var_dump($data);
        return view('driver.view-driver', ['franchise'=>$franchise,'datas' => $data, 'countrylist' => $countrylist]);
    }


    public function InsertDriver(Request $request)
    {

        $e = new Driver();
        if (isset($_POST)) {

            if ($request->olddriverid == 1) {
                //Define validation rules
                $rules = [
                    'firstname' => 'required',
                    'gender' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'mobile' => 'required|max:10',
                    
                ];
                //Define the validation messgae for the rule
                $messages = [
                    'firstname.numeric' => 'The Name should be numeric',
                ];
                $validator = Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    return back()->withInput()
                        ->withErrors($validator);
                } else {

                    $emailcheck = Driver::where('email', '=', $request->email)->count();
                    if ($emailcheck > 0) {
                        return back()->withInput(Input::all())
                                ->with('message', 'Email Already Exist');
                       
                    }

                    $license = Driver::where('licenseid', '=', $request->licenseid)->count();
                    if ($license > 0) {
                        return back()->withInput(Input::all())->with('message', "License already exist");
                    }

                    $s = strtoupper(md5(uniqid(rand(), true)));
                    //$file = $request->file('license');
                    // $destinationPath = 'uploads'; // Directory for Image Upload

                    $r = rand(111111, 999999);

                   
                    $destination_driver = public_path('uploads/driverpic/' . date("Ymd")); // Directory for Image Upload
                    

                    $file = $request->file('license');
                    $destination_license = public_path('uploads/license/'. date("Ymd")); // Directory for Image Upload

                    $file->move($destination_license, $r . '.' . $file->getClientOriginalExtension());

                    $filep = $request->file('profilepicture');

                    $filep->move($destination_driver, $r . '.' . $filep->getClientOriginalExtension());


                    $mobile = Driver::where('mobile', '=', $request->mobile)->count();
                    if ($mobile > 0) {
                        return back()->withInput()->with('message', "Mobile already exist");
                    } else {
                        $e->firstname = $request->firstname;
                        $e->license = '/uploads/license/'.date('Ymd').'/' . $r . '.' . $file->getClientOriginalExtension();
                        $e->lastname = $request->lastname;
                        $e->ride_category = $request->ridetype;
                        $e->profile_photo = '/uploads/driverpic/'.date('Ymd') . '/' . $r . '.' . $filep
                        ->getClientOriginalExtension();
                        $e->email = $request->email;
                        $key = hash('sha256', 'wrydes');
                        $iv = substr(hash('sha256', 'dispatch'), 0, 16);
                        $output = openssl_encrypt($request->password, "AES-256-CBC", $key, 0, $iv);
                        $output2 = base64_encode($output);
                        $e->password = $output2;
                        $e->gender = strtoupper($request->gender);
                        $e->dob = $request->dob;
                        $e->licenseid = $request->licenseid;
                        $e->mobile = $request->mobile;
                        $e->country = $request->country;
                        $e->state = $request->state;
                        $e->city = $request->city;
                        $e->address = $request->address;
                        $i = 'ID';
                        $idc = rand(11111, 99999);
                        $ida = $i . $idc;
                        $idcheck = Driver::where('driver_id', '=', $ida)->count();
                        if($idcheck != 0){
                            $i = 'ID';
                            $idc = rand(11111, 99999);
                            $ida = $i . $idc;
                        }
                        $e->driver_id = $ida;
                        $e->save();
                        Session::flash('message', "Driver Added Successfully");
                        return redirect()->intended('/manage_driver');
                    }
                }
            } else {


                 $emailcheck = Driver::where('email', '=', $request->email)
                 ->where('driver_id','=',$request->olddriverid)
                 ->count();
                if ($emailcheck == 0) {
                    $emailcheck1 = Driver::where('email', '=', $request->email)->count();
                    if($emailcheck1 > 0){
                    //Session::flash('message', "Email already exist");
                    return back()->withInput()->with('message', "Email already exist");
                    //return redirect()->intended('/add_driver');
                }
                }

                $license = Driver::where('licenseid', '=', $request->licenseid)
                ->where('driver_id','=',$request->olddriverid)
                ->count();
                if ($license == 0) {
                    $license1 = Driver::where('licenseid', '=', $request->licenseid)->count();
                    if($license1 > 0){
                    Session::flash('message', "License Invalid");
                    return redirect()->intended('/add_driver');
                }
                }

                $mobile = Driver::where('mobile', '=', $request->mobile)->where('driver_id','=',$request->olddriverid)->count();
                if ($mobile == 0) {
                    $mobile1 = Driver::where('mobile', '=', $request->mobile)->count();
                    if($mobile1 > 0){
                    Session::flash('message', "mobile number invalid");
                    return redirect()->intended('/add_driver');
                }
                }
                $randnum2 = rand(111111, 999999);
                $destination_license = public_path('uploads/license/' . date("Ymd")); // Directory for Image Upload
                $lic_image = $request->file('license');

                $lic_image->move($destination_license, $randnum2 .'.'. $lic_image->getClientOriginalExtension());
                echo $idp = $request->id;
                $dup = Driver::find($idp);
                $dup->firstname = $request->firstname;
                $dup->license = '/uploads/license/'.date("Ymd") . '/' . $randnum2 .'.'. $lic_image->getClientOriginalExtension();
                $dup->lastname = $request->lastname;
                $dup->email = $request->email;
                $key = hash('sha256', 'wrydes');
                $iv = substr(hash('sha256', 'dispatch'), 0, 16);
                echo $output = openssl_encrypt($request->password, "AES-256-CBC", $key, 0, $iv);
                $output2 = base64_encode($output);

                $dup->password = $output2;
                $dup->profile_status = 0;
                $dup->gender = strtoupper($request->gender);
                $dup->dob = $request->dob;
                $dup->licenseid = $request->licenseid;
                $dup->mobile = $request->mobile;
                $dup->country = $request->country;
                $dup->state = $request->state;
                $dup->city = $request->city;
                $dup->address = $request->address;
                $dup->save();
                Session::flash('message', "Driver Updated Successfully");
                echo $p = "/manage_driver";

                return redirect()->intended($p);


            }


        }

    }


    public function ManageDriver(Request $request)
    {
        //$cartype = CarType::all();
        $franchise = Franchise::all();
        $ride_category = VehicleCategory::all();
        session(['cf_driver' => $request->input('ride_category')]);
       if( (session('cf_driver') !=NUll) && (session('cf_driver') !=0)){


            $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
                ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
                ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
                ->join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_driver.ride_category')
                ->where('wy_driver.profile_status', '=', 1)
                ->where('wy_driver.ride_category','=',session('cf_driver'))
                ->where('wy_driver.driver_type', '=', 1)
                ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type', 'wy_cartype.car_board','wy_ridetype.ride_category')
                ->get();

            $blocked_driver = Driver::join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_driver.ride_category')
                ->where('wy_driver.profile_status', '=', -1)
                ->where('wy_driver.ride_category','=',session('cf_driver'))
                ->where('wy_driver.driver_type', '=', 1)
                ->select('wy_driver.*', 'wy_ridetype.ride_category')
                ->get();

            $nonactive_driver = Driver::join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_driver.ride_category')
                ->where('wy_driver.profile_status', '=', 0)
                ->where('wy_driver.ride_category','=',session('cf_driver'))
                ->where('wy_driver.driver_type', '=', 1)
                ->select('wy_driver.*', 'wy_ridetype.ride_category')
                ->get();

        }else{

            $active_driver = Driver::join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
                ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
                ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
                ->join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_driver.ride_category')
                ->where('wy_driver.profile_status', '=', 1)
                ->where('wy_driver.driver_type', '=', 1)
                ->select('wy_driver.*', 'wy_carlist.car_no', 'wy_cartype.car_type', 'wy_cartype.car_board','wy_ridetype.ride_category')
                ->get();
            
            $blocked_driver = Driver::join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_driver.ride_category')
                ->where('wy_driver.profile_status', '=', -1)
                ->where('wy_driver.driver_type', '=', 1)
                ->select('wy_driver.*', 'wy_ridetype.ride_category')
                ->get();
//print_r($blocked_driver); exit; 
            $nonactive_driver = Driver::join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_driver.ride_category')
                ->where('wy_driver.profile_status', '=', 0)
                ->where('wy_driver.driver_type', '=', 1)
                ->select('wy_driver.*','wy_ridetype.ride_category')
                ->get();
               

            //$active_driver = Driver::where('profile_status', '1')->where('driver_type', '=', 1)->get();
            //$nonactive_driver = Driver::where('profile_status', '0')->get();
            //$blocked_driver = Driver::where('profile_status', '-1')->get();
        }
        return view('driver.manage-driver', ['franchise'=>$franchise,'ride_category'=>$ride_category,'active_driver' => $active_driver, 'blocked_driver' => $blocked_driver, 'nonactive_driver' => $nonactive_driver]);

    }

    public function AssignTaxi(Request $request)
    {
        $cartype = CarType::all();
        $ride_category = VehicleCategory::all();
        $countrylist = Country::all();
        $driver_list = Driver::where('profile_status', '0')->where('driver_type','=','1')->get();
        /* $driver_list = DB::table('wy_driver')
            ->leftJoin('wy_assign_taxi', 'wy_driver.id', '=', 'wy_assign_taxi.driver_id')
            ->where('wy_driver.profile_status', '=', '0')
			->where('wy_driver.ride_category','=','1')
            ->whereNull('wy_assign_taxi.driver_id')
			->orWhere('wy_assign_taxi.status', '=', '2')
            ->select('wy_driver.*')
            ->get(); */

        $carlist = DB::table('wy_carlist')->where('status', '0')->where('car_attached','=','1')->get();
        /* $carlist = DB::table('wy_carlist')
            ->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->where('wy_carlist.status', '=', '0')
			->where('wy_carlist.ride_category','=','1')
			->whereNull('wy_assign_taxi.car_num')
			->orWhere('wy_assign_taxi.status', '=', '2')
            ->select('wy_carlist.*')
            ->get(); */


        return view('driver.assign-taxi', ['ride_category' => $ride_category, 'country_list' => $countrylist, 'driver_list' => $driver_list, 'carlist' => $carlist,'cartype'=>$cartype]);
    }

    public function ManageAssignTaxi(Request $request)
    {
        $franchise = Franchise::all();
        $assign = AssignTaxi::where('status','=',1)->get();
            $CarType = CarType::all();
        $ride_category = VehicleCategory::all();
        $request->session()->put('cf_avehicle', $request->input('ride_category'));
        if( session('cf_avehicle') !=NUll){
            $taxi_assigned_list = AssignTaxi::where('wy_assign_taxi.ride_category','=',session('cf_avehicle'))
                ->Where('wy_assign_taxi.status','1')
                ->Where('wy_assign_taxi.status','=',1)
            ->join('wy_carlist','wy_carlist.id','=','wy_assign_taxi.car_num')
            ->join('wy_cartype','wy_cartype.id','=','wy_carlist.car_type')
            ->where('wy_carlist.car_attached','=',1)
            ->get();
               // print_r($taxi_assigned_list); exit;
        }else{

//		    $taxi_assigned_list= DB::table('wy_assign_taxi')
//                ->join('wy_driver', 'wy_driver.id', '=', 'wy_assign_taxi.driver_id')
//                ->join('wy_ridetype', 'wy_ridetype.id', '=', 'wy_assign_taxi.ride_category')
//                ->where('wy_assign_taxi.status', '=',1)
//                ->select('wy_assign_taxi.*','wy_driver.firstname','wy_driver.lastname','wy_driver.gender','wy_driver.driver_id','wy_ridetype.ride_category')
//                ->get();
            $taxi_assigned_list = AssignTaxi::Where('wy_assign_taxi.status','=',1)
            ->join('wy_carlist','wy_carlist.id','=','wy_assign_taxi.car_num')
            ->join('wy_cartype','wy_cartype.id','=','wy_carlist.car_type')
            ->where('wy_carlist.car_attached','=',1)
            ->get();
            
        }
        return view('driver.manage-assign-taxi', ['franchise'=>$franchise ,'ride_category'=>$ride_category ,'CarType'=>$CarType,'taxi_assigned_list' => $taxi_assigned_list,'assign'=>$assign]);
    }

    // funciton assign taxi to driver
    public function PostAssignTaxi(Request $request)
    {
        $userid = Auth::user()->id;
        $rules = [
            'driver_name' => 'required',
            'taxi_status' => 'required',
            'car_number' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'ride_category' => 'required',
        ];
        //Define the validtion messgae for the rule
        $messages = [
            'driver_name.required' => 'Driver Name should be Required',
            'car_number.required' => 'Car Number should be Required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator);
        }
        //check the driver and taxi already exists
        /* $check_driver_status=AssignTaxi::Where('driver_id','!=',$request->input('driver_name'))
         ->Where('car_num','!=',$request->input('car_number'))
         ->get();
            if(count($check_driver_status) >0){
                return back()->withInput()
                        ->with('error_status', 'Driver or Taxi Number has already Assigned');
            }
        */
        //  store value
            
            
         //$delete_old = DB::table('wy_assign_taxi')->where('driver_id','=',$request->input('driver_name'))->where('car_num','=',$request->input('car_number'))->delete();

        $assign = new AssignTaxi;
        $assign->ride_category = $request->input('ride_category');
        $assign->driver_id = $request->input('driver_name');
        $assign->car_num = $request->input('car_number');
        $assign->owner_ship = $request->input('taxi_status');
        $assign->country = $request->input('country');
        $assign->state = $request->input('state');
        $assign->city = $request->input('city');
        $assign->created_by = $userid;
        $assign->save();
        $this->update_driver_status($old_driver = '', $request->input('driver_name')); // activate the driver status
        $this->update_taxi_status($old_taxi = '', $request->input('car_number'));  // activate the  car status
        $this->assign_taxi_history($request->input('driver_name'), $request->input('car_number')); // store the assign taxi history

        // Send notification to driver you are assigned
        $driver_detail = DB::table('wy_driver')->where('id',$request->input('driver_name'))->first();
        
        $token = $driver_detail->device_token;
        $message = 'You has been assigned to the taxi';
        // IOS 
        if($driver_detail->device_type == 1){

                    $key = '';
        //$batch = intval($count);
            $payload['aps'] = 
            array('alert' => $message, 'sound' => 'default','badge' => 0,'notify_key'=>$key);
            $payload = json_encode($payload);
            //print_r($payload);
            $apnsHost = 'gateway.sandbox.push.apple.com';
            $apnsPort = 2195;
            $apnsCert = 'GOApp.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
            $options = array('ssl' => array(
            'local_cert' => 'GOApp.pem',
            'passphrase' => 'armor'
            ));
            $streamContext = stream_context_create();
            stream_context_set_option($streamContext, $options);
            $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
            $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $token)) . chr(0) . chr(strlen($payload)) . $payload;
            fwrite($apns, $apnsMessage);
            fclose($apns);

        }

        // Android
        if($driver_detail->device_type == 2){

                        $fields = array(
                    'to' => $token ,
                    'priority' => "high",
                    'notification' => array( "tag"=>"chat", "body" => $message),
                );
                // echo "<br>";
                //json_encode($fields);
                //echo "<br>"; 
                $headers = array(
                    'Authorization: key=AIzaSyAPbEBHf_6y0oZbMuGM3H3c_TyI7NPY8wU',
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
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

        }
        
        Session::flash('message', "Taxi successfully assigned to Driver");
        return redirect()->intended('/manage_assign_taxi');

    }

public function apns_cus($devicetoken,$message,$rideid){
    $key = '';
//$batch = intval($count);
    $payload['aps'] = 
    array('alert' => $message, 'sound' => 'default','badge' => 0,'notify_key'=>$key);
    $payload = json_encode($payload);
    //print_r($payload);
    $apnsHost = 'gateway.sandbox.push.apple.com';
    $apnsPort = 2195;
    $apnsCert = 'GOApp.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
    $options = array('ssl' => array(
    'local_cert' => 'GOApp.pem',
    'passphrase' => 'armor'
    ));
    $streamContext = stream_context_create();
    stream_context_set_option($streamContext, $options);
    $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
    $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
    fwrite($apns, $apnsMessage);
    fclose($apns);
}

public function send_gcm_notify($reg_id, $message,$ride_id) {

    $fields = array(
        'to' => $reg_id ,
        'priority' => "high",
        'notification' => array( "tag"=>"chat", "body" => $message),
    );
    // echo "<br>";
    //json_encode($fields);
    //echo "<br>"; 
    $headers = array(
        'Authorization: key=' . FIREBASE_API_KEY,
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
    //echo $result;
}


//edit assign taxi

    public function update_driver_status($olddriver_id = "", $driver_id)
    {
        if ($olddriver_id != "") {
            if ($olddriver_id != $driver_id) {
                $assign = Driver::find($olddriver_id);
                $assign->profile_status = 0;
                $assign->save();
            }
        }
        $assign = Driver::find($driver_id);
        $assign->profile_status = 1;
        $assign->save();
        return true;
    }

    //update the assign taxi

    public function update_taxi_status($old_taxi_id = NULL, $taxi_id)
    {
        if ($old_taxi_id != NULL) {
            if ($old_taxi_id != $taxi_id) {
                $assign = Car::find($old_taxi_id);
                $assign->status = 0;
                $assign->save();
            }
        }
        $assign = Car::find($taxi_id);
        $assign->status = 1;
        $assign->save();
        return true;
    }

    // Function to Add Driver from Form

    public function assign_taxi_history($taxi = '', $driver_id = '')
    {
        $userid = Auth::user()->id;

        DB::table('wy_assign_history')->insert(
            array('driver_id' => $driver_id,
                'car_id' => $taxi,
                'created_by' => $userid,
                'created_at' => date("Y-m-d H:i:s")
            )
        );
    }

    public function edit_assign_taxi($id = "")
    {

        $taxi_assign_details = AssignTaxi::find($id);

        $driver_id = $taxi_assign_details->driver_id;
        
        $check5 = DB::table('wy_ridedetails')
                ->where('driver_id','=',$driver_id)
                ->whereIn('ride_status',array(0, 1, 2, 3))
                ->whereIn('accept_status',array(0,1))
                ->count();

        if($check5 != 0){

            return back()->with('message','Taxi in ride');
        }

        if (count($taxi_assign_details) == 0) {
            return redirect('/manage_assign_taxi');
        }
        $vehicle_type=$taxi_assign_details->ride_category;
        if(old('taxi_type')!=""){
            $vehicle_type=old('taxi_type');
        }

        $ride_category = VehicleCategory::all();
        $countrylist = Country::all();
        $cartype = CarType::all();
        /*$driver_list = DB::table('wy_driver')
            ->leftJoin('wy_assign_taxi', 'wy_driver.id', '=', 'wy_assign_taxi.driver_id')
            ->where('wy_driver.profile_status', '=', '0')
            ->where('wy_driver.driver_type', '=', '1')
            ->where('wy_driver.ride_category','=',$vehicle_type)
            ->whereNull('wy_assign_taxi.driver_id')
            ->orWhere('wy_driver.id', '=', $taxi_assign_details->driver_id)
            ->select('wy_driver.*')
            ->get();*/
            
            $driver_list=DB::SELECT(DB::RAW(" select distinct(wy_driver.driver_id),`wy_assign_taxi`.`driver_id`,`wy_driver`.* from `wy_driver` left join `wy_assign_taxi` 
on `wy_driver`.`id` = `wy_assign_taxi`.`driver_id` where `wy_driver`.`profile_status` = 0 
and `wy_driver`.`driver_type` = 1 and `wy_driver`.`ride_category` = '$vehicle_type' and 
`wy_assign_taxi`.`driver_id` is null or `wy_driver`.`id` = '$taxi_assign_details->driver_id' "));

       $carlist=DB::SELECT(DB::RAW(" select distinct(wy_carlist.id),`wy_carlist`.* from `wy_carlist` left join `wy_assign_taxi` on
 `wy_carlist`.`id` = `wy_assign_taxi`.`car_num` where `wy_carlist`.`car_attached` = 1 
and `wy_carlist`.`ride_category` = '$vehicle_type'and 
 `wy_carlist`.`status` = 0 and
 `wy_assign_taxi`.`car_num` is null OR `wy_assign_taxi`.`car_num` = '$taxi_assign_details->car_num' "));
 
        /*$carlist = DB::table('wy_carlist1')
            ->leftJoin('wy_assign_taxi', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->where('wy_carlist.status', '=', '0')
            ->where('wy_carlist.car_attached', '=', '1')
            ->where('wy_carlist.ride_category','=',$vehicle_type)
            ->whereNull('wy_assign_taxi.car_num')
            ->orWhere('wy_assign_taxi.car_num', '=', $taxi_assign_details->car_num)
            ->select('wy_carlist.*')
            ->get();*/
            
           
        $full_list = DB::table('wy_carlist')->where('status','=',0)->where('car_attached','=',1)->get();
        
        return view('driver.assign-taxi-edit', ['ride_category' => $ride_category, 'taxidetails' => $taxi_assign_details, 'country_list' => $countrylist, 'driver_list' => $driver_list, 'carlist' => $carlist,'cartype'=>$cartype,'full_list'=>$full_list]);
    }

    //while assign taxi to anyone activate the new driver status and deactivate the old driver status

    public function update_assign_taxi(Request $request)
    {

        $taxi_assign_details = AssignTaxi::find($request->input('assign_taxi_id'));
        $driverid = $taxi_assign_details->driver_id;
        $carid = $taxi_assign_details->car_num;
        $t = DB::table('wy_ridedetails')->where('driver_id','=',$driverid)->where('ride_status','=',3)->count();
        if($t != 0){
            return back()->with('message', "Unable to update, driver in Ride try later");
        }
        if (count($taxi_assign_details) == 0) {
            return redirect('/manage_assign_taxi');
        }
        $userid = Auth::user()->id;
        $rules = [
            'driver_name' => 'required',
            'taxi_status' => 'required',
            'car_number' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            
        ];
        //Define the validtion messgae for the rule
        $messages = [
            'driver_name.required' => 'Driver Name should be Required',
            'car_number.required' => 'Car Number should be Required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator);
        }


        //check the driver and taxi already exists
        /* $check_driver_status=AssignTaxi::Where('driver_id','!=',$request->input('driver_name'))
         ->Where('car_num','!=',$request->input('car_number'))
         ->Where('id','!=',$request->input('assign_taxi_id'))
         ->get();
            if(count($check_driver_status) > 0){

                return back()->withInput()
                        ->with('error_status', 'Driver or Taxi Number has already Assigned');
            }
            //get the old taxi and driver id
        */
        $old_driver = $taxi_assign_details->driver_id;
        $old_taxi = $taxi_assign_details->car_num;

        //  store value
        $assign = AssignTaxi::find($request->input('assign_taxi_id'));
        //$assign->ride_category = $request->input('ride_category');
        $assign->driver_id = $request->input('driver_name');
        $assign->car_num = $request->input('car_number');
        $assign->owner_ship = $request->input('taxi_status');
        $assign->country = $request->input('country');
        $assign->state = $request->input('state');
        $assign->city = $request->input('city');
        $assign->created_by = $userid;
        $assign->save();

        $this->update_driver_status($old_driver, $request->input('driver_name')); // activate the driver status
        $this->update_taxi_status($old_taxi, $request->input('car_number'));  // activate the  car status
        $this->assign_taxi_history($request->input('driver_name'), $request->input('car_number')); // store the

        Session::flash('message', "Taxi successfully assigned to Driver");
        return redirect()->intended('/manage_assign_taxi');

    }

    /*
    *
    while assign taxi to anyone activate the new driver status and deactivate the old driver status
    *
    */

    public function AddDriver1(Request $request)
    {
        $e = new Driver();
        if (isset($_POST)) {

            //Define validation rules
            $rules = [
                'firstname' => 'required',
                'gender' => 'required',
                'state' => 'required',
                'city' => 'required',
                'password' => 'required',
                'password1' => 'required|same:password'
            ];
            //Define the validation messgae for the rule
            $messages = [
                'firstname.numeric' => 'The Name should be numeric',
                'password' => 'The Minimum Kilometer should be numeric',
                'password1' => 'Not same',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect('/add_driver')->withInput()
                    ->withErrors($validator);
            } else {
                $s = strtoupper(md5(uniqid(rand(), true)));
                $file = $request->file('license');
                $destination_rc = 'uploads/license/' . date("Ymd"); // Directory for Image Upload
                if (!file_exists($destination_rc)) {
                    $destinationPath = mkdir($destination_rc, 0700);
                }

                $file->move($destinationPath, $s . $file->getClientOriginalName());

                $c = strtoupper($request->country[0]) . strtoupper($request->country[1]) . strtoupper($request->state[0]) . strtoupper($request->state[1]);
                $lid = $request->mobile[0] . $request->mobile[1] . $request->dob[0] . $request->dob[1] . rand(1, 10);

                $emailcheck = Driver::where('email', '=', $request->email)->count();
                if ($emailcheck > 0) {
                    Session::flash('email_present', "Email ID Already Present");
                    return redirect()->intended('/add_driver');
                } else {
                    $e->firstname = $request->firstname;
                    $e->license = $s . $file->getClientOriginalName();
                    $e->lastname = $request->lastname;
                    $e->email = $request->email;
                    $e->password = $request->password;
                    $e->gender = strtoupper($request->gender);
                    $e->dob = $request->dob;
                    $e->licenseid = $request->licenseid;
                    $e->mobile = $request->mobile;
                    $e->country = $request->country;
                    $e->state = $request->state;
                    $e->city = $request->city;
                    $e->address = $request->address;
                    $e->driver_id = $c . $lid;
                    $e->save();
                    Session::flash('driver_added', "Driver Added Successfully");
                    return redirect()->intended('/manage_driver');
                }
            }

        }
    }

    /*
    *
    ** Record the complete asign history
    *
    */

    public function Update1(Request $request)
    {
        if (isset($_POST)) {

            //Define validation rules
            $rules = [
                'firstname' => 'required',
                'gender' => 'required',
                'state' => 'required',
                'city' => 'required',
                'password' => 'required',
                'password1' => 'required|same:password'
            ];
            //Define the validation messgae for the rule
            $messages = [
                'firstname.numeric' => 'The Name should be numeric',
                'password' => 'The Minimum Kilometer should be numeric',
                'password1' => 'Not same',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return back()->withInput()
                    ->withErrors($validator);
            } else {

                $emailcheck = Driver::where('email', '=', $request->email)
                 ->where('id','=',$request->id)
                 ->count();
                if ($emailcheck == 0) {
                    $emailcheck1 = Driver::where('email', '=', $request->email)->count();
                    if($emailcheck1 > 0){
                    //Session::flash('message', "Email already exist");
                    return back()->withInput()->with('message', "Email already exist");
                    //return redirect()->intended('/add_driver');
                }
                }

                $license = Driver::where('licenseid', '=', $request->licenseid)
                ->where('id','=',$request->id)
                ->count();
                if ($license == 0) {
                    $license1 = Driver::where('licenseid', '=', $request->licenseid)->count();
                    if($license1 > 0){
                    Session::flash('message', "License Id already exists");
                    return back()->withInput();
                }
                }

                $mobile = Driver::where('mobile', '=', $request->mobile)->where('id','=',$request->id)->count();
                if ($mobile == 0) {
                    $mobile1 = Driver::where('mobile', '=', $request->mobile)->count();
                    if($mobile1 > 0){
                    Session::flash('message', "mobile number already exists");
                    return back()->withInput();
                }
                }


                $s = strtoupper(md5(uniqid(rand(), true)));
                $randnum2 = rand(111111, 999999);
                $destination_license = public_path('uploads/license/'.date("Ymd")); // Directory for Image Upload
                $destination_profile = public_path('uploads/driverpic/'.date("Ymd")); // Directory for Image Upload
               // $destination_rc = public_path('uploads/license/'. date("Ymd")); // Directory for Image Upload

                $profile_picture = $request->file('profilepicture');

                if($profile_picture){
                    $ext1 = $profile_picture->getClientOriginalExtension();
                if(($ext1 != 'jpg') && ($ext1 != 'png'))
                {
                    return back()->withInput()
                    ->withErrors('Unsupported Image Format');
                }

                $profile_picture->move($destination_profile, $randnum2 .'.'. $profile_picture->getClientOriginalExtension());
                }
                

                $lic_image = $request->file('license');
                if($lic_image){

                $lic_image->move($destination_license, $randnum2 .'.'. $lic_image->getClientOriginalExtension());

                }

                // $destinationPath = 'uploads'; // Directory for Image Upload
                //$file->move($destinationPath,$s.$file->getClientOriginalName());
                $emailcheck = Driver::where('email', '=', $request->email)->count();
                /*if($emailcheck > 0){
                    Session::flash('email_present', "Email ID Already Present");
                    return redirect()->intended('/add_driver');
                }
                else
                    {*/
                echo $idp = $request->id;
                $dup = Driver::find($idp);

                if($request->Franchise == 1){
                    $dup->isfranchise = 1;
                    $dup->franchise_id = $request->franchise;
                }
                else{
                    $dup->isfranchise = 0;
                    $dup->franchise_id = 0;
                }
                $dup->ride_category = $request->ridetype;
                $dup->firstname = $request->firstname;
                if($profile_picture){
                $dup->profile_photo = '/uploads/driverpic/'.date("Ymd") .'/'. $randnum2 .'.'. $profile_picture->getClientOriginalExtension();}
                if($lic_image){
                $dup->license = '/uploads/license/'.date("Ymd") .'/'. $randnum2 .'.'. $lic_image->getClientOriginalExtension();}
                $dup->lastname = $request->lastname;
                $dup->email = $request->email;
                $key = hash('sha256', 'wrydes');
                $iv = substr(hash('sha256', 'dispatch'), 0, 16);
                echo $output = openssl_encrypt($request->password, "AES-256-CBC", $key, 0, $iv);
                $output2 = base64_encode($output);

                $dup->password = $output2;
                $dup->gender = strtoupper($request->gender);
                $dup->dob = $request->dob;
                $dup->licenseid = $request->licenseid;
                $dup->mobile = $request->mobile;
                $dup->country = $request->country;
                $dup->state = $request->state;
                $dup->city = $request->city;
                $dup->address = $request->address;
                $dup->save();
                Session::flash('driver_updated', "Driver Updated Successfully");
                echo $p = "/manage_driver";

               return redirect()->intended($p);// 
                 }
            }
        }

    

    public function BlockDriver(Request $request)
    {
        $status = -1;

        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');


            $check5 = DB::table('wy_ridedetails')
                                    ->where('driver_id','=',$taxi_id)
                                    ->whereIn('ride_status',array(0, 1, 2, 3))
                                    ->whereIn('accept_status',array(0,1))->count();

                if($check5 == 0){

                $date_fmt = date("d-m-Y");
                $header = array();
                $header[] = 'Content-Type: application/json';
                $postdata = '{"status":true}';
                $ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_status/$taxi_id.json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                $result = curl_exec($ch);
                curl_close($ch);


                $this->driver->change_status($taxi_id, $status);

                $car = DB::table('wy_assign_taxi')->where('driver_id','=',$taxi_id)->first();
                $car_id = $car->car_num;
                $update_car = DB::table('wy_carlist')->where('id','=',$car_id)->update(['status' => 0]);
                return response()->json([

                    'Response' => 'Driver Successfully blocked',
                    'Status' => 'Success'
                ]);
            }
        }

    }

    public function AssignedBlockDriver(Request $request)
    {
        $status = -1;

        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');

            

            $taxi_Status= DB::table('wy_ridedetails')
            ->join('wy_ride', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
            ->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
            ->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
            ->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
            ->join('wy_cartype', 'wy_cartype.id', '=', 'wy_ride.car_type')
            ->whereIn('wy_ridedetails.ride_status', array(0, 1, 2, 3))
            ->whereIn('wy_ridedetails.accept_status', array(0, 1))
            ->where('wy_assign_taxi.driver_id', '=',$taxi_id)
            ->select('wy_ridedetails.driver_id')
            ->get();

         $checkdriver = DB::table('wy_assign_taxi')->where('driver_id','=',$taxi_id)->first();
         $carnum = $checkdriver->car_num;

         $driver_id = $taxi_id;


        $check5 = DB::table('wy_ridedetails')
                ->where('driver_id','=',$driver_id)
                ->whereIn('ride_status',array(0, 1, 2, 3))
                ->whereIn('accept_status',array(0,1))->count();

        if($check5 != 0){

            return response()->json([
                    'Response' => 'Driver/Taxi is in a ride.Try after Sometime',
                    'Status' => '2'
                ]);
        }
        else{
         
         $update_driver = DB::table('wy_carlist')->where('id','=',$carnum)->update(['status' => 0]);
         

            $delete = DB::table('wy_assign_taxi')->where('driver_id', '=', $taxi_id)->update(['status' => -1]);
			if($delete)
			{
			$date_fmt = date("d-m-Y");
            $header = array();
            $header[] = 'Content-Type: application/json';
            $postdata = '{"status":true}';
            $ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/blocked_status/$taxi_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
			}
            $carnum = DB::table('wy_assign_taxi')
                ->where('driver_id', '=', $taxi_id)->select('car_num')->first();
            $delete1 = DB::table('wy_carlist')->where('id', '=', $carnum->car_num)->update(['status' => 0]);

            $this->driver->change_status($taxi_id, $status);
            return response()->json([
                'Response' => 'Driver Successfully Deactivated',
                'Status' => '2'
            ]);
          }
        }

    }

    public function ActivateDriver(Request $request)
    {
        $status = 0;
        if ($request->input('_token') == null) {
            $taxi_id = $request->input('data_id');
            $this->driver->change_status($taxi_id, $status);
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

            //$delete1 = DB::table('wy_driver')->where('id', '=',$taxi_id)->update(['profile_status' => 2]);
            $this->driver->change_status($taxi_id, $status);
            return response()->json([
                'Response' => 'Driver Detail Deleted',
                'Status' => 'Success'
            ]);
        }
    }


// Driver review list display function

    public function review(Request $request,$id)
    {

        $alert = DB::table('wy_alert')
            ->Where('driver_id','=',$id)//0->$id
            ->select('ride_id', DB::raw('count(*) as total'))
            ->groupBy('ride_id')
            ->get();
        $reason = DB::table('wy_rating_reasons')->get();
        $customer = DB::table('wy_customer')->get();
        $rating = DriverRating::Where('driver_id','=',$id)->get();
        
        //$cus = Customer::Where('id','=',$id)->first();
        return view('driver.driver-rating', ['rating' => $rating,'alert' => $alert,'customer'=>$customer,'reason'=>$reason,'id'=>$id]);


    }

    public function filterreview(Request $request){

        $id = $request->id;
        $from_date = $request->fromdate;
        $to_date = $request->todate;
        $alert = DB::table('wy_alert')
            ->Where('driver_id','=',$id)//0->$id
            ->select('ride_id', DB::raw('count(*) as total'))
            ->groupBy('ride_id')
            ->get();
        $reason = DB::table('wy_rating_reasons')->get();
        $customer = DB::table('wy_customer')->get();
        // Filter start
        $where = array();

        $date = array();
        if($from_date != NULL){
            $from_date = date('Y-m-d H:i:s',strtotime($from_date));
            $where[] = "created_at > '$from_date'";
            $date[0] = $from_date;
        }

        if($to_date != NULL){
            $to_date = date('Y-m-d H:i:s',strtotime($to_date));
            $where[] = "created_at <= '$to_date'";
            $date[1] = $to_date;
        }

        $first = "SELECT * FROM wy_customerrate WHERE driver_id = '$id'";
        $final = array();
        $final[] = $first;
        foreach ($where as $w) {
            $final[] = $w;
        }
        $wheref = implode(" AND ", $final);


        $rating = DB::select( DB::raw($wheref) );
        //echo json_encode($rating);
        // End of filter
        return view('driver.driver-rating', ['rating' => $rating,'alert' => $alert,'customer'=>$customer,'reason'=>$reason,'id'=>$id,'date'=>$date]);
    }

    public function checkuser(Request $request)
    {
        $id = $request->input('data_id');
        $carnum = DB::table('wy_driver')
            ->where('driver_id', '=', $id)
            ->where('profile_status', '=', 2)
            ->select('*')->first();
        $key = hash('sha256', 'wrydes');
        $iv = substr(hash('sha256', 'dispatch'), 0, 16);

        $pass = openssl_decrypt(base64_decode($carnum->password), "AES-256-CBC", $key, 0, $iv);

        // Check driver is attached or normal 
        $drivercheck = DB::table('wy_driver')->where('driver_id','=',$id)->first();
        if($drivercheck->driver_type == 2){
           exit;
        }

        if ($carnum != "") {
            return response()->json([
                'firstname' => $carnum->firstname,
                'lastname' => $carnum->lastname,
                'email' => $carnum->email,
                'password' => $pass,
                'password1' => $pass,
                'licenseid' => $carnum->licenseid,
                'country' => $carnum->country,
                'dob' => $carnum->dob,
                'state' => $carnum->state,
                'city' => $carnum->city,
                'address' => $carnum->address,
                'mobile' => $carnum->mobile,
                'ride_category' => $carnum->ride_category,
                'licenseid' => $carnum->licenseid,
                'id' => $carnum->id,
                'Response' => $carnum,
                'Status' => 'Success'
            ]);
        } else {
            return response()->json([
                'Status' => 'Failed'
            ]);
        }
    }

    
//********* GET CAR AND DRIVER LIST BASED VEHICLE CATEGORY**********//

    public function getvehicle_driver_list(Request $request){
        //if($request->input('_token')== null){

        if($request->input('assign_taxi_id')!=""){

            $driver_list = DB::table('wy_driver')
                ->where('profile_status', '=', '0')
                ->where('ride_category','=',$request->input('category_id'))
                ->select('*')
                ->get();

            $vehicle_list = DB::table('wy_carlist')
                ->where('status', '=', '0')
                ->where('ride_category','=',$request->input('category_id'))
                ->select('*')
                ->get();
        }else{
           
            $driver_list = DB::table('wy_driver')
                ->where('profile_status', '=', '0')
                ->where('ride_category','=',$request->input('category_id'))
                ->select('*')
                ->get();

            $vehicle_list = DB::table('wy_carlist')
                ->where('status', '=', '0')
                ->where('ride_category','=',$request->input('category_id'))
                ->select('*')
                ->get();
        }

        $vehicle=array();
        $driver=array();
        foreach($vehicle_list as $val){
            $vehicle []=array(
                'id'=>$val->id,
                'car_no'=>$val->car_no
            );
        }


        foreach($driver_list as $val){
            $driver []=array(
                'id'=>$val->id,
                'driver_name'=>$val->driver_id
            );
        }
        $datalist=array('vehicle_list'=>$vehicle,'driver_list'=>$driver);
        return json_encode($datalist);

    }

}

