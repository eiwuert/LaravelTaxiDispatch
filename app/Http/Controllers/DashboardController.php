<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Ride;
use App\RideDetails;
use App\Driver;
use App\Car;
use App\Franchise;
use DB;
class DashboardController extends Controller
{




	// After login redirect to home page
				public function home(Request $request)
				{
					$vehicle_num=$request->input('vehicle-number');
						$role=Session::get('user_role');
							switch($role)
						  {
						      case "1" : //ADMIN Dashboard
						         return view('home',$this->admin_dashboard($vehicle_num));
						         break;
						      case "3" : //Franchise Dashboard
						      	return view('home',$this->franchise_dashboard($vehicle_num));
						          break;
						      default :
						          return redirect('/');
						  }
			}
	
		//*************ADMIN DASHBOARD****************//
	
		public function admin_dashboard($vehicle_num){
	//start
				$active_vehicles=DB::SELECT( "select cl.id as vehicle_id,cl.car_no,ct.car_type,
							CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END AS car_board
							from wy_driver d JOIN wy_driverlocation dl ON d.id=dl.driver_id 
							LEFT JOIN wy_ridedetails s1 ON d.id=s1.driver_id
							LEFT JOIN wy_ridedetails AS s2 ON s1.driver_id= s2.driver_id AND s1.id < s2.id
							LEFT JOIN wy_assign_taxi at ON d.id=at.driver_id AND at.status=1
							LEFT JOIN wy_carlist cl ON at.car_num=cl.id
								LEFT JOIN wy_cartype ct ON cl.car_type=ct.id
							where ((s1.accept_status=0 OR  s1.accept_status=1 ) AND (s1.ride_status IN (0,1,2,3))) OR (d.online_status=1) 
							and  s2.driver_id IS NULL ");
					
					
				if($vehicle_num!= "" && $vehicle_num != 'ALL'){
						 session(['d_vehiclenum' => $vehicle_num]);
						//$request->session()->put('d_vehiclenum', $vehicle_num);
						$vehicles_status =DB::SELECT( "select cl.ride_category,cl.id as vehicle_id,cl.car_no,dl.lat,dl.lng,d.driver_id,
							CASE WHEN s1 .ride_status= 5 OR s1 .ride_status = 4 OR s1.accept_status=2 OR s1.accept_status=3  THEN 'free' ELSE 'rides' END AS ride_status
							from wy_driver d JOIN wy_driverlocation dl ON d.id=dl.driver_id 
							LEFT JOIN wy_ridedetails s1 ON d.id=s1.driver_id
							LEFT JOIN wy_ridedetails AS s2 ON s1.driver_id= s2.driver_id AND s1.id < s2.id
							LEFT JOIN wy_assign_taxi at ON d.id=at.driver_id AND at.status=1
							LEFT JOIN wy_carlist cl ON at.car_num=cl.id
							where ((s1.accept_status=0 OR  s1.accept_status=1 ) AND (s1.ride_status IN (0,1,2,3))) OR (d.online_status=1) 
							and  s2.driver_id IS NULL AND cl.id = $vehicle_num  order by s1.driver_id " );
					}else{
					
					 session(['d_vehiclenum' => '']);
						//$request->session()->put('d_vehiclenum', "");
						$vehicles_status =DB::SELECT( "select cl.ride_category,cl.id as vehicle_id,cl.car_no,dl.lat,dl.lng,d.driver_id,
							CASE WHEN s1 .ride_status= 5 OR s1 .ride_status = 4 OR s1.accept_status=2 OR s1.accept_status=3  THEN 'free' ELSE 'rides' END AS ride_status
							from wy_driver d JOIN wy_driverlocation dl ON d.id=dl.driver_id 
							LEFT JOIN wy_ridedetails s1 ON d.id=s1.driver_id
							LEFT JOIN wy_ridedetails AS s2 ON s1.driver_id= s2.driver_id AND s1.id < s2.id
							LEFT JOIN wy_assign_taxi at ON d.id=at.driver_id AND at.status=1
							LEFT JOIN wy_carlist cl ON at.car_num=cl.id
							where ((s1.accept_status=0 OR  s1.accept_status=1 ) AND (s1.ride_status IN (0,1,2,3))) OR (d.online_status=1) 
							and  s2.driver_id IS NULL order by s1.driver_id ");
					}
						//	print_r($vehicle_query);	
						

			
					$total_drivers=DB::table('wy_assign_taxi')->get();
					$total_cars=DB::table('wy_assign_taxi')->get();

					$total_today_wryde=RideDetails::whereDate('start_ride_time', '=', date('Y-m-d'))
					->whereIn('ride_status', array(0,1, 2, 3,4))
					->whereIn('accept_status', array(0,1))
					->get();
			
					DB::statement('call individual_share()');
					DB::statement('call company_share()');
					$individual_driver_share= DB::table('temp_individual_drivershare')
				 		->join('wy_driver', 'wy_driver.id', '=', 'temp_individual_drivershare.driver_id')
					->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
					->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
					->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
					->select('wy_driver.driver_id as driver_name','temp_individual_drivershare.*','wy_cartype.car_type')
					->get();
					$individual_company_share= DB::table('temp_cmpy_drivershare')
				 		->join('wy_driver', 'wy_driver.id', '=', 'temp_cmpy_drivershare.driver_id')
					->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
					->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
					->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
					->select('wy_driver.driver_id as driver_name','temp_cmpy_drivershare.*','wy_cartype.car_type')
					->get();
					$total_driver_share = DB::table('wy_ride')->sum('driver_share');
					$total_company_share = DB::table('wy_ride')->sum('company_share');
			
					/***** TOTAL CARTYPE WITH COUNT*********/
					/*$total_vehicle_count= DB::table('wy_cartype')
					->leftjoin('wy_carlist', 'wy_cartype.id', '=', 'wy_carlist.car_type')
					->select("count(wy_carlist.car_type) as car_count")
					->select(DB::raw("wy_cartype.car_type"))
					 ->groupBy('wy_carlist.car_type')
				   ->get();*/
				   
				   $vehicletype_details=DB::SELECT("SELECT CONCAT(wy_cartype.car_type,' - ', CASE WHEN wy_cartype.car_board= 1 THEN 'W' ELSE 'Y' END) vehicle_type ,(select count(wy_carlist.car_type) as car_type from wy_carlist Where wy_cartype.id = wy_carlist.car_type group by wy_carlist.car_type) as vehicle_cnt from wy_cartype");
	
						/****LAST 3 MONTH RIDE DETAILS WIHT COUNT*********/
						$latest_ride_details= DB::table('wy_ride')
					->leftjoin('wy_ridedetails', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
					->select(DB::raw("count(wy_ride.date_of_ride) as ride_count,date_of_ride"))
					->whereIn('wy_ridedetails.ride_status', array(1, 2, 3,4))
					->whereIn('wy_ridedetails.accept_status', array(1))
					->whereRaw('wy_ride.date_of_ride > DATE_SUB(now(), INTERVAL 6 MONTH)')
					 ->groupBy('wy_ride.date_of_ride')
				   ->get();
				   
				   
				   $completed_ride= DB::table('wy_ridedetails')
					->select(DB::raw("count(*) as ride_cnt"))
					->whereIn('wy_ridedetails.ride_status', array(1, 2, 3,4))
					->whereIn('wy_ridedetails.accept_status', array(1))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
				   
				  $rejectby_customer= DB::table('wy_ridedetails')
					->select(DB::raw("count(*) as ride_cnt"))
					->whereIn( 'wy_ridedetails.ride_status', array(5))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
				   
				    $rejectby_driver= DB::table('wy_ridedetails')
					->select(DB::raw("count(*) as ride_cnt"))
					->whereIn('wy_ridedetails.accept_status', array(3))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
				   
				    $auto_denied= DB::table('wy_ridedetails')
					->select(DB::raw("count(*) as ride_cnt"))
					->whereIn('wy_ridedetails.accept_status', array(2))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
				   
				   $datalist=array(
					'active_vehicles' =>$active_vehicles,
					'vehicles_status'=>$vehicles_status,
					'total_driver_share'=>$total_driver_share,
					'total_company_share'=>$total_company_share,
					'total_drivers'=>$total_drivers,
					'total_cars'=>$total_cars,
					'today_wryde'=>$total_today_wryde,
					'individual_driver_share'=>$individual_driver_share,
					'individual_company_share'=>$individual_company_share,
					 'vehicletype_details'=>$vehicletype_details,
					'latest_ride_details'=>$latest_ride_details,
					'completed_ride'=>$completed_ride,
					'rejectby_customer'=>$rejectby_customer,
					'rejectby_driver'=>$rejectby_driver,
					'auto_denied'=>$auto_denied
				);
					return $datalist;
				//end			

		}
		//*************Franchise DASHBOARD****************//
	
		public function franchise_dashboard($vehicle_num){
				//start
				
				//get the Franchise ID
				  $Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
				  $franchis_id=$Franchise[0]->id;
			
         	  session(['_franchise_id' => $franchis_id]);
         		
				$active_vehicles=DB::SELECT( "select cl.ride_category,cl.id as vehicle_id,cl.car_no,ct.car_type,
							CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END AS car_board
							from wy_driver d JOIN wy_driverlocation dl ON d.id=dl.driver_id 
							LEFT JOIN wy_ridedetails s1 ON d.id=s1.driver_id
							LEFT JOIN wy_ridedetails AS s2 ON s1.driver_id= s2.driver_id AND s1.id < s2.id
							LEFT JOIN wy_assign_taxi at ON d.id=at.driver_id AND at.status=1
							LEFT JOIN wy_carlist cl ON at.car_num=cl.id
							LEFT JOIN wy_cartype ct ON cl.car_type=ct.id
							where  (((s1.accept_status=0 OR s1.accept_status=1 ) AND (s1.ride_status IN (0,1,2,3))) OR (d.online_status=1)) and  d.franchise_id='".$franchis_id."' and  s2.driver_id IS NULL order by s1.driver_id");
					
					
				if($vehicle_num!= "" && $vehicle_num != 'ALL'){
						 session(['d_vehiclenum' => $vehicle_num]);
						//$request->session()->put('d_vehiclenum', $vehicle_num);
						$vehicles_status =DB::SELECT( "select cl.ride_category, cl.id as vehicle_id,cl.car_no,dl.lat,dl.lng,d.driver_id,
							CASE WHEN s1 .ride_status= 5 OR s1 .ride_status = 5 OR s1.accept_status=2 OR s1.accept_status=3  THEN 'free' ELSE 'rides' END AS ride_status
							from wy_driver d JOIN wy_driverlocation dl ON d.id=dl.driver_id 
							LEFT JOIN wy_ridedetails s1 ON d.id=s1.driver_id
							LEFT JOIN wy_ridedetails AS s2 ON s1.driver_id= s2.driver_id AND s1.id < s2.id
							LEFT JOIN wy_assign_taxi at ON d.id=at.driver_id AND at.status=1
							LEFT JOIN wy_carlist cl ON at.car_num=cl.id
							where  (((s1.accept_status=0 OR s1.accept_status=1 ) AND (s1.ride_status IN (0,1,2,3))) OR (d.online_status=1)) and  d.franchise_id='".$franchis_id."' and cl.id = '".$vehicle_num."'  and  s2.driver_id IS NULL order by s1.driver_id" );
					}else{
					
					 session(['d_vehiclenum' => '']);
						//$request->session()->put('d_vehiclenum', "");
						
					
							
						$vehicles_status =DB::SELECT( "select cl.ride_category,cl.id as vehicle_id,cl.car_no,dl.lat,dl.lng,d.driver_id,
							CASE WHEN s1 .ride_status= 5 OR s1 .ride_status = 5 OR s1.accept_status=2 OR s1.accept_status=3  THEN 'free' ELSE 'rides' END AS ride_status
							from wy_driver d JOIN wy_driverlocation dl ON d.id=dl.driver_id 
							LEFT JOIN wy_ridedetails s1 ON d.id=s1.driver_id
							LEFT JOIN wy_ridedetails AS s2 ON s1.driver_id= s2.driver_id AND s1.id < s2.id
							LEFT JOIN wy_assign_taxi at ON d.id=at.driver_id AND at.status=1
							LEFT JOIN wy_carlist cl ON at.car_num=cl.id
							where  (((s1.accept_status=0 OR s1.accept_status=1 ) AND (s1.ride_status IN (0,1,2,3))) OR (d.online_status=1)) and  d.franchise_id='".$franchis_id."' and  s2.driver_id IS NULL order by s1.driver_id ");
					}
						//	print_r($vehicle_query);	
						

			
					$total_drivers=DB::table('wy_driver')->where('wy_driver.franchise_id','=',$franchis_id)->get();
					$total_cars=DB::table('wy_carlist')->where('wy_carlist.franchise_id','=',$franchis_id)->get();
			
						$total_today_wryde= DB::table('wy_ride')
					->leftjoin('wy_ridedetails', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
					->leftjoin('wy_driver', 'wy_ridedetails.driver_id', '=', 'wy_driver.id')
					->select(DB::raw("count(*) as ride_count"))
					->where('wy_driver.franchise_id','=',$franchis_id)
					->whereIn('wy_ridedetails.ride_status', array(1, 2, 3,4))
					->whereIn('wy_ridedetails.accept_status', array(1))
					->whereDate('start_ride_time', '=', date('Y-m-d'))
					->get();
				   
			
					DB::statement('call individual_share()');
					DB::statement('call company_share()');
					$individual_driver_share= DB::table('temp_individual_drivershare')
				 	->join('wy_driver', 'wy_driver.id', '=', 'temp_individual_drivershare.driver_id')
					->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
					->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
					->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
					->where('wy_driver.franchise_id','=',$franchis_id)
					->select('wy_driver.driver_id as driver_name','temp_individual_drivershare.*','wy_cartype.car_type')
					->get();
					$individual_company_share= DB::table('temp_cmpy_drivershare')
				 		->join('wy_driver', 'wy_driver.id', '=', 'temp_cmpy_drivershare.driver_id')
					->join('wy_assign_taxi', 'wy_assign_taxi.driver_id', '=', 'wy_driver.id')
					->join('wy_carlist', 'wy_carlist.id', '=', 'wy_assign_taxi.car_num')
					->join('wy_cartype', 'wy_cartype.id', '=', 'wy_carlist.car_type')
					->where('wy_driver.franchise_id','=',$franchis_id)
					->select('wy_driver.driver_id as driver_name','temp_cmpy_drivershare.*','wy_cartype.car_type')
					->get();



					$total_driver_share = DB::table('wy_ride')
					->join('wy_ridedetails','wy_ride.id','=','wy_ridedetails.ride_id')
					->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
					->where('wy_driver.franchise_id','=',$franchis_id)
					->sum('wy_ride.driver_share');
					
					$total_company_share = DB::table('wy_ride')	
					->join('wy_ridedetails','wy_ride.id','=','wy_ridedetails.ride_id')
					->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
					->where('wy_driver.franchise_id','=',$franchis_id)
					->sum('wy_ride.company_share');

					$tcshare = DB::table('wy_ride')
									->join('wy_ridedetails','wy_ride.id','=','wy_ridedetails.ride_id')
									->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
									->where('wy_ridedetails.ride_status','=',4)
									->where('wy_driver.franchise_id','=',$franchis_id)
									->sum('wy_ride.company_share');

					$tdshare = DB::table('wy_ride')
									->join('wy_ridedetails','wy_ride.id','=','wy_ridedetails.ride_id')
									->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
									->where('wy_ridedetails.ride_status','=',4)
									->where('wy_driver.franchise_id','=',$franchis_id)
									->sum('wy_ride.driver_share');

			$franchise_share1 = DB::select( DB::raw("select sum(rd.company_share) as cshare from wy_ride rd inner join wy_ridedetails dr ON rd.id = dr.ride_id inner join wy_driver wd ON wd.id = dr.driver_id where wd.franchise_id = $franchis_id") );
			// print_r($franchise_share); exit;
			$franchise_share = $franchise_share1[0]->cshare; 

			$d_share = DB::select( DB::raw("select sum(rd.driver_share) as cshare from wy_ride rd inner join wy_ridedetails dr ON rd.id = dr.ride_id inner join wy_driver wd ON wd.id = dr.driver_id where wd.franchise_id = $franchis_id") );
			// print_r($franchise_share); exit;
			$driver_sharef = $d_share[0]->cshare; 
					/***** TOTAL CARTYPE WITH COUNT*********/
					/*$total_vehicle_count= DB::table('wy_cartype')
					->leftjoin('wy_carlist', 'wy_cartype.id', '=', 'wy_carlist.car_type')
					->select("count(wy_carlist.car_type) as car_count")
					->select(DB::raw("wy_cartype.car_type"))
					 ->groupBy('wy_carlist.car_type')
				   ->get();*/
				   
				   $vehicletype_details=DB::SELECT("SELECT CONCAT(wy_cartype.car_type,' - ', CASE WHEN wy_cartype.car_board= 1 THEN 'W' ELSE 'Y' END) vehicle_type ,(select count(wy_carlist.car_type) as car_type from wy_carlist Where wy_cartype.id = wy_carlist.car_type AND wy_carlist.franchise_id='".$franchis_id."' group by wy_carlist.car_type) as vehicle_cnt from wy_cartype");
	
						/****LAST 3 MONTH RIDE DETAILS WIHT COUNT*********/
						$latest_ride_details= DB::table('wy_ride')
					->leftjoin('wy_ridedetails', 'wy_ride.id', '=', 'wy_ridedetails.ride_id')
					->leftjoin('wy_driver', 'wy_ridedetails.driver_id', '=', 'wy_driver.id')
					->select(DB::raw("count(wy_ride.date_of_ride) as ride_count,date_of_ride"))
					->where('wy_driver.franchise_id','=',$franchis_id)
					->whereIn('wy_ridedetails.ride_status', array(1, 2, 3,4))
					->whereIn('wy_ridedetails.accept_status', array(1))
					->whereRaw('wy_ride.date_of_ride > DATE_SUB(now(), INTERVAL 6 MONTH)')
					 ->groupBy('wy_ride.date_of_ride')
				   ->get();
				   
				   
				   $completed_ride= DB::table('wy_ridedetails')
				   ->leftjoin('wy_driver', 'wy_ridedetails.driver_id', '=', 'wy_driver.id')
					->select(DB::raw("count(*) as ride_cnt"))
					->where('wy_driver.franchise_id','=',$franchis_id)
					->whereIn('wy_ridedetails.ride_status', array(1, 2, 3,4))
					->whereIn('wy_ridedetails.accept_status', array(1))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
				   
				  $rejectby_customer= DB::table('wy_ridedetails')
				  ->leftjoin('wy_driver', 'wy_ridedetails.driver_id', '=', 'wy_driver.id')
					->select(DB::raw("count(*) as ride_cnt"))
					->where('wy_driver.franchise_id','=',$franchis_id)
					->whereIn('wy_ridedetails.ride_status', array(5))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
				   
			    $rejectby_driver= DB::table('wy_ridedetails')
			  	->leftjoin('wy_driver', 'wy_ridedetails.driver_id', '=', 'wy_driver.id')
					->select(DB::raw("count(*) as ride_cnt"))
					->where('wy_driver.franchise_id','=',$franchis_id)
					->whereIn('wy_ridedetails.accept_status', array(3))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
				    
				    $auto_denied= DB::table('wy_ridedetails')
			  	->leftjoin('wy_driver', 'wy_ridedetails.driver_id', '=', 'wy_driver.id')
					->select(DB::raw("count(*) as ride_cnt"))
					->where('wy_driver.franchise_id','=',$franchis_id)
					->whereIn('wy_ridedetails.accept_status', array(2))
					->whereRaw('wy_ridedetails.created_at > DATE_SUB(now(), INTERVAL 6 MONTH)')
					->get();
					
							 
				   $datalist=array(
					'active_vehicles' =>$active_vehicles,
					'vehicles_status'=>$vehicles_status,
					'total_driver_share'=>$total_driver_share,
					'total_company_share'=>$total_company_share,
					'total_drivers'=>$total_drivers,
					'total_cars'=>$total_cars,
					'today_wryde'=>$total_today_wryde,
					'individual_driver_share'=>$individual_driver_share,
					'individual_company_share'=>$individual_company_share,
					 'vehicletype_details'=>$vehicletype_details,
					'latest_ride_details'=>$latest_ride_details,
					'completed_ride'=>$completed_ride,
					'rejectby_customer'=>$rejectby_customer,
					'rejectby_driver'=>$rejectby_driver,
					'franchise_share'=>$franchise_share,
					'auto_denied'=>$auto_denied
				);
					return $datalist;
				//end			
		}
}
