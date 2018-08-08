<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Franchise;
use App\AddDriver;
use App\Car;
use App\Http\Requests;
use DB;
use PDF;
use Excel;
use Session;
use Auth;

class ReportController extends Controller
{
    //
    public function TotalTrans(Request $request){

    	return view('report.Total_Trans');
    }

    public function SuccessfulRides(Request $request){

    	return view('report.Successful_Rides');
    }

    public function CancelRides(Request $request){

    	return view('report.Cancel_Rides');
    }

    public function RejectRides(Request $request){

    	return view('report.Reject_Rides');
    }
     public function DriverShare(Request $request){

    	return view('report.total_share');
    }



///New DASHBOARD REPORTS

 	public function rejected_ride(Request $request){

 		$datet = 0;
 		if($request->from_date){
 			$datet = $request->from_date;
 		}
 		if($request->to_date){
 			$datet = $request->to_date;
 		}
 		
 		$role=Session::get('user_role');
 		if($role == 3){
 			$Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
			$franchise_id=$Franchise[0]->id;
			$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
			$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );
 		}else{

 			if($request->franchise){

 				$franchise_id = $request->franchise;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );
				
				
 			}else{
 				$franchise_id = 0;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver ") );
 			}
		
 		}
 		

 		$franchise = Franchise::where('status','=',1)->get();

 		$filter_query="";


		$passenger_list = DB::select( DB::raw("select wy_customer.id,wy_customer.name from wy_customer") );

			//filter report
		$where = array();
		
		if($request->vehicle != NULL)	{	$where[] = "cl.id ='$request->vehicle'";}
		if($request->driver != NULL){	$where[] = "dr.id = '$request->driver'";	}
		if($request->passenger != NULL){ $where[] = " c.id = '$request->passenger'";}
		if($request->trip_id != NULL ){ $where[] = "r.reference_id LIKE '%$request->trip_id%'";}
		$fdate=date("Y-m-d",strtotime($request->from_date));	$tdate=date("Y-m-d",strtotime($request->to_date));
		if($request->from_date != NULL && $request->to_date != NULL){
		$where[] = "r.date_of_ride BETWEEN '$fdate' AND '$tdate'";
		}else if($request->from_date != NULL){ $where[] = "r.date_of_ride = '$fdate'";}
		else if($request->to_date != NULL){ $where[] = "r.date_of_ride = '$tdate'";	}else{
			$where[] = "r.date_of_ride > DATE_SUB(now(), INTERVAL 6 MONTH)";
		}
		if($role == 3){
			$where[] = "dr.franchise_id = $franchise_id";
			$where[] = "cl.franchise_id = $franchise_id";
		}
		if($request->franchise){
			$where[] = "dr.franchise_id = $request->franchise";
			$where[] = "cl.franchise_id = $request->franchise";
		}
		if(count($where)>0){
			$filter_query = " AND ".implode(" AND ", $where);
		}
		$graph_query = "SELECT r.date_of_ride as ride_date,count(*) as ride_count FROM  wy_ride r 
						JOIN `wy_ridedetails` rd ON r.id=rd.ride_id 
						JOIN wy_customer c  ON r.customer_id=c.id 
						LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
						LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
						LEFT JOIN wy_carlist cl ON at.car_num =cl.id
						where rd.accept_status=2  ";
				
		$graph_query .= "$filter_query GROUP BY r.date_of_ride ";
		$rejected_ride = DB::select( DB::raw($graph_query) );
	

		//DATA REPORT 
		$data_query = "SELECT r.reference_id,IF(r.ride_type =1,'Normal','Scheduled') as ride_type,CONCAT(dr.firstname,'-',dr.driver_id) as driver_name,CONCAT(c.name,'-',c.id) as passanger_name ,CONCAT(cl.car_no,' - ',ct.car_type,' - ',IF(ct.car_board >1,'W','Y')  ) as car_no,r.date_of_ride,r.source_location,r.destination_location FROM  wy_ride r 
		JOIN wy_ridedetails rd ON r.id=rd.ride_id 
		JOIN wy_customer c  ON r.customer_id=c.id 
		LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
		LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
		LEFT JOIN wy_carlist cl ON at.car_num =cl.id
		LEFT JOIN wy_cartype ct ON cl.car_type =ct.id where rd.accept_status=2 $filter_query";
		$rejected_list = DB::select( DB::raw($data_query) );
	
			view()->share(['rejected_list'=>$rejected_list,'rejected_ride_graph'=>$rejected_ride,'vehicle_list'=>$vehicle_list,'driver_list'=>$driver_list,'passenger_list'=>$passenger_list,'franchise'=>$franchise,'franchise_id'=>$franchise_id,'datet'=>$datet,'role'=>$role]);
			
			//=====EXPORT PDF AND EXCEL==========//
			if($request->has('export')){
				$file_name="rejected-rides-".date("d-m-Y");
				
				if($request->export == "pdf"){
					//return view('reports.export.pdf-rejected-ride');
					$pdf = PDF::loadView('reports.export.pdf.pdf-rejected-ride')->setPaper('a4', 'landscape');
					return $pdf->download($file_name.'.pdf');
				}else{
					Excel::create($file_name, function($excel) {
						$excel->sheet('rejected-ride-list', function($sheet) {
							$sheet->setSize(array(
								'A1' => array(
									'height'    => 20,
								)
							));
						$sheet->setAutoSize(true);
						  $sheet->loadView('reports.export.excel.excel-rejected-ride');
						});
					})->export('xls');
				}
			return false;
			}
		//=====END EXPORT PDF AND EXCEL==========//
			return view('reports.rejected_ride');
    }
	
	//============CANCEL RIDES========//
	
 	public function cancel_rides(Request $request){

 		$datet = 0;
 		if($request->from_date){
 			$datet = $request->from_date;
 		}
 		if($request->to_date){
 			$datet = $request->to_date;
 		}

 		$franchise = Franchise::where('status','=',1)->get();
 			$filter_query="";

		$role=Session::get('user_role');
 		if($role == 3){
 			$Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
			$franchise_id=$Franchise[0]->id;
			$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
			$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );
 		}else{

 			if($request->franchise){

 				$franchise_id = $request->franchise;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );

 			}else{
 				$franchise_id = 0;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver ") );
 			}
		
 		}

		$passenger_list = DB::select( DB::raw("select wy_customer.id,wy_customer.name from wy_customer") );

			//filter report
		$where = array();
		
		if($request->vehicle != NULL)	{	$where[] = "cl.id ='$request->vehicle'";}
		if($request->driver != NULL){	$where[] = "dr.id = '$request->driver'";	}
		if($request->passenger != NULL){ $where[] = " c.id = '$request->passenger'";}
		if($request->trip_id != NULL ){ $where[] = "r.reference_id LIKE '%$request->trip_id%'";}
		$fdate=date("Y-m-d",strtotime($request->from_date));	$tdate=date("Y-m-d",strtotime($request->to_date));
		if($request->from_date != NULL && $request->to_date != NULL){
		$where[] = "r.date_of_ride BETWEEN '$fdate' AND '$tdate'";
		}else if($request->from_date != NULL){ $where[] = "r.date_of_ride = '$fdate'";}
		else if($request->to_date != NULL){ $where[] = "r.date_of_ride = '$tdate'";	}else{
			$where[] = "r.date_of_ride > DATE_SUB(now(), INTERVAL 6 MONTH)";
		}
		if($role == 3){
			$where[] = "dr.franchise_id = $franchise_id";
			$where[] = "cl.franchise_id = $franchise_id";
		}
		if($request->franchise){
			$where[] = "dr.franchise_id = $request->franchise";
			$where[] = "cl.franchise_id = $request->franchise";
		}
		if(count($where)>0){
			$filter_query = " AND ".implode(" AND ", $where);
		}
		$graph_query = "SELECT r.date_of_ride as ride_date,count(*) as ride_count FROM  wy_ride r 
				JOIN `wy_ridedetails` rd ON r.id=rd.ride_id 
				JOIN wy_customer c  ON r.customer_id=c.id 
				LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
				LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
				LEFT JOIN wy_carlist cl ON at.car_num =cl.id
				where (rd.accept_status=3 OR rd.ride_status=5) ";
				
		$graph_query .= "$filter_query GROUP BY r.date_of_ride ";
		$cancelled_ride = DB::select( DB::raw($graph_query) );
	

		//DATA REPORT 
					$data_query = "SELECT rd.cancel_notes,r.reference_id,IF(r.ride_type =1,'Ride now','Ride later') as ride_type,CONCAT(dr.firstname,'-',dr.driver_id) as driver_name,CONCAT(c.name,'-',c.id) as passanger_name ,CONCAT(cl.car_no,' - ',ct.car_type,' - ',IF(ct.car_board >1,'W','Y') ) as car_no,r.date_of_ride,r.source_location,r.destination_location,IF(rd.accept_status = '3', 'Driver', NULL) as Cancelled_ByD,IF(rd.ride_status = '5', 'Customer', NULL) as Cancelled_ByC FROM  wy_ride r 
JOIN wy_ridedetails rd ON r.id=rd.ride_id 
JOIN wy_customer c  ON r.customer_id=c.id 
LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
LEFT JOIN wy_carlist cl ON at.car_num =cl.id
LEFT JOIN wy_cartype ct ON cl.car_type =ct.id
where (rd.accept_status=3 OR rd.ride_status=5) $filter_query";
		$cancelled_list = DB::select( DB::raw($data_query) );
	
			view()->share(['cancelled_list'=>$cancelled_list,'cancelled_ride_graph'=>$cancelled_ride,'vehicle_list'=>$vehicle_list,'driver_list'=>$driver_list,'passenger_list'=>$passenger_list,'franchise'=>$franchise,'franchise_id'=>$franchise_id,'datet'=>$datet,'role'=>$role]);
			
			//=====EXPORT PDF AND EXCEL==========//
			if($request->has('export')){
				$file_name="cancelled-rides-".date("d-m-Y");
				
				if($request->export == "pdf"){
			
					//return view('reports.export.pdf.pdf-cancelled-ride');
					$pdf = PDF::loadView('reports.export.pdf.pdf-cancelled-ride')->setPaper([0, 0, 1200, 1200], 'landscape');
					return $pdf->download($file_name.'.pdf');
				}else{
					Excel::create($file_name, function($excel) {
						$excel->sheet('cancelled-ride-list', function($sheet) {
							$sheet->setSize(array(
								'A1' => array(
									'height'    => 20,
								)
							));
						$sheet->setAutoSize(true);
						  $sheet->loadView('reports.export.excel.excel-cancelled-ride');
						});
					})->export('xls');
				}
			return false;
			}
		//=====END EXPORT PDF AND EXCEL==========//
			return view('reports.cancelled_ride');
  }

  
  //****************SUCCESS RIDES*****************//
	
 	public function success_rides(Request $request){

 		$datet = 0;
 		if($request->from_date){
 			$datet = $request->from_date;
 		}
 		if($request->to_date){
 			$datet = $request->to_date;
 		}

 		$franchise = Franchise::where('status','=',1)->get();
 		$filter_query="";

		$role=Session::get('user_role');
 		if($role == 3){
 			$Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
			$franchise_id=$Franchise[0]->id;
			$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
			$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );
 		}else{

 			if($request->franchise){

 				$franchise_id = $request->franchise;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );

 			}else{
 				$franchise_id = 0;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver ") );
 			}
 			
		
 		}
		$passenger_list = DB::select( DB::raw("select wy_customer.id,wy_customer.name from wy_customer") );

			//filter report
		$where = array();
		
		if($request->vehicle != NULL)	{	$where[] = "cl.id ='$request->vehicle'";}
		if($request->driver != NULL){	$where[] = "dr.id = '$request->driver'";	}
		if($request->passenger != NULL){ $where[] = " c.id = '$request->passenger'";}
		if($request->trip_id != NULL ){ $where[] = "r.reference_id LIKE '%$request->trip_id%'";}
		$fdate=date("Y-m-d",strtotime($request->from_date));	$tdate=date("Y-m-d",strtotime($request->to_date));
		if($request->from_date != NULL && $request->to_date != NULL){
		$where[] = "r.date_of_ride BETWEEN '$fdate' AND '$tdate'";
		}else if($request->from_date != NULL){ $where[] = "r.date_of_ride = '$fdate'";}
		else if($request->to_date != NULL){ $where[] = "r.date_of_ride = '$tdate'";	}else{
			$where[] = "r.date_of_ride > DATE_SUB(now(), INTERVAL 6 MONTH)";
		}
		if($role == 3){
			$where[] = "dr.franchise_id = $franchise_id";
			$where[] = "cl.franchise_id = $franchise_id";
		}
		if($request->franchise){
			$where[] = "dr.franchise_id = $request->franchise";
			$where[] = "cl.franchise_id = $request->franchise";
		}
		if(count($where)>0){
			$filter_query = " AND ".implode(" AND ", $where);
		}
		$graph_query = "SELECT r.date_of_ride as ride_date,count(*) as ride_count FROM  wy_ride r 
						JOIN `wy_ridedetails` rd ON r.id=rd.ride_id 
						JOIN wy_customer c  ON r.customer_id=c.id 
						LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
						LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
						LEFT JOIN wy_carlist cl ON at.car_num =cl.id
						where rd.ride_status=4 and r.date_of_ride";
				
		$graph_query .= "$filter_query GROUP BY r.date_of_ride ";
		$success_ride = DB::select( DB::raw($graph_query) );
	

		//DATA REPORT 
					$data_query = "SELECT r.reference_id,CONCAT(dr.firstname,'-',dr.driver_id) as driver_name,CONCAT(c.name,'-',c.id) as passanger_name ,CONCAT(cl.car_no,' - ',ct.car_type,' - ',IF(ct.car_board =1,'W','Y') ) as car_no,r.date_of_ride,IF(r.ride_type =1,'Ride Now','Ride Later') as ride_type,r.source_location,r.destination_location,case r.padi_by When 1 then 'Cash' When 2 then 'E-Wallet' When 3 then 'Cash/E-Wallet' When 4 then 'POS' When 5 then 'POS/E-Wallet' When 0 then 'None' end as payment_type,
					ROUND((r.final_amount*ct.attacheddriver_share/100),2) as driver_share,
					ROUND((r.final_amount*ct.franchise_share/100),2) as franchise_share,
					ROUND((r.final_amount*ct.companydriver_share/100),2) as company_share,
					r.total_amount,r.paid_cash,r.paid_taximoney
					FROM  wy_ride r 
					JOIN wy_ridedetails rd ON r.id=rd.ride_id 
					JOIN wy_customer c  ON r.customer_id=c.id 
					LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
					LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
					LEFT JOIN wy_carlist cl ON at.car_num =cl.id
					LEFT JOIN wy_cartype ct ON cl.car_type =ct.id
					where rd.ride_status=4  $filter_query";
					
		$success_list = DB::select( DB::raw($data_query) );
	
			view()->share(['success_list'=>$success_list,'success_ride_graph'=>$success_ride,'vehicle_list'=>$vehicle_list,'driver_list'=>$driver_list,'passenger_list'=>$passenger_list,'franchise'=>$franchise,'datet'=>$datet,'franchise_id'=>$franchise_id,'role'=>$role]);
			
			//=====EXPORT PDF AND EXCEL==========//
			if($request->has('export')){
				$file_name="success-rides-".date("d-m-Y");
				
				if($request->export == "pdf"){
			
					//return view('reports.export.pdf.pdf-success-ride');
					$pdf = PDF::loadView('reports.export.pdf.pdf-success-ride')->setPaper([0, 0, 1200, 1200], 'landscape');
					return $pdf->download($file_name.'.pdf');
				}else{
					Excel::create($file_name, function($excel) {
						$excel->sheet('success-ride-list', function($sheet) {
							$sheet->setSize(array(
								'A1' => array(
									'height'    => 20,
								)
							));
						$sheet->setAutoSize(true);
						  $sheet->loadView('reports.export.excel.excel-success-ride');
						});
					})->export('xls');
				}
			return false;
			}
		//=====END EXPORT PDF AND EXCEL==========//
			return view('reports.success_ride');
  }

  
  /********TOTAL RIDE LIST**************************/
  

 	public function total_rides(Request $request){

 		$datet = 0;
 		if($request->from_date){
 			$datet = $request->from_date;
 		}
 		if($request->to_date){
 			$datet = $request->to_date;
 		}

 		$franchise = Franchise::where('status','=',1)->get();
 			$filter_query="";
		$role=Session::get('user_role');
 		if($role == 3){
 			$Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
			$franchise_id=$Franchise[0]->id;
			$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
			$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );
 		}else{

 			if($request->franchise){
 				$franchise_id = $request->franchise; 
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );
 			}else{
 				$franchise_id = 0;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type") );
			$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver ") );
 			}
 			
 			
		
 		}
		$passenger_list = DB::select( DB::raw("select wy_customer.id,wy_customer.name from wy_customer") );

			//filter report
		$where = array();
		
		if($request->vehicle != NULL)	{	$where[] = "cl.id ='$request->vehicle'";}
		if($request->driver != NULL){	$where[] = "dr.id = '$request->driver'";	}
		if($request->passenger != NULL){ $where[] = " c.id = '$request->passenger'";}
		if($request->trip_id != NULL ){ $where[] = "r.reference_id LIKE '%$request->trip_id%'";}
		$fdate=date("Y-m-d",strtotime($request->from_date));	$tdate=date("Y-m-d",strtotime($request->to_date));
		if($request->from_date != NULL && $request->to_date != NULL){
		$where[] = "r.date_of_ride BETWEEN '$fdate' AND '$tdate'";
		}else if($request->from_date != NULL){ $where[] = "r.date_of_ride = '$fdate'";}
		else if($request->to_date != NULL){ $where[] = "r.date_of_ride = '$tdate'";	}else{
			$where[] = "r.date_of_ride > DATE_SUB(now(), INTERVAL 6 MONTH)";
		}
		if($role == 3){
			$where[] = "dr.franchise_id = $franchise_id";
			$where[] = "cl.franchise_id = $franchise_id";
		}
		if($request->franchise){
			$where[] = "dr.franchise_id = $request->franchise";
			$where[] = "cl.franchise_id = $request->franchise";
		}
		if(count($where)>0){

			$filter_query = " Where ".implode(" AND ", $where);
		}
		$graph_query = "SELECT r.date_of_ride as ride_date,count(*) as ride_count  FROM  wy_ride r 
				JOIN `wy_ridedetails` rd ON r.id=rd.ride_id 
				JOIN wy_customer c  ON r.customer_id=c.id 
				LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
				LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
				LEFT JOIN wy_carlist cl ON at.car_num =cl.id";
				
		$graph_query .= "$filter_query GROUP BY r.date_of_ride ";
		$total_ride = DB::select( DB::raw($graph_query) );
	

		//DATA REPORT 
					$data_query = "SELECT r.reference_id,IF(r.ride_type =1,'Normal','Scheduled') as ride_type,CONCAT(dr.firstname,'-',dr.driver_id) as driver_name,CONCAT(c.name,'-',c.id) as passanger_name ,CONCAT(cl.car_no,' - ',ct.car_type,' - ',IF(ct.car_board =1,'W','Y')) as car_no,r.date_of_ride,r.source_location,r.destination_location,case r.padi_by When 1 then 'Cash' When 2 then 'E-Wallet' When 3 then 'Cash/E-Wallet' When 4 then 'POS' When 5 then 'POS/E-Wallet' When 0 then 'None' end as payment_type,r.total_amount,r.paid_cash,r.paid_taximoney,r.paid_pos,
					(CASE 
							WHEN rd.accept_status = 1 || (rd.ride_status = 1 || rd.ride_status = 2 || rd.ride_status = 3|| rd.ride_status = 4)THEN 'Successful'
							WHEN rd.accept_status = 3 || rd.ride_status  = 5 THEN 'Cancelled'
							WHEN rd.accept_status = 2 THEN 'Auto Denied' 
					END) AS ride_status
					FROM wy_ride r 
					JOIN wy_ridedetails rd ON r.id=rd.ride_id 
					JOIN wy_customer c  ON r.customer_id=c.id 
					LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
					LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
					LEFT JOIN wy_carlist cl ON at.car_num =cl.id
					LEFT JOIN wy_cartype ct ON cl.car_type =ct.id $filter_query";
					
		$total_list = DB::select( DB::raw($data_query) );
	
			view()->share(['total_list'=>$total_list,'total_ride_graph'=>$total_ride,'vehicle_list'=>$vehicle_list,'driver_list'=>$driver_list,'passenger_list'=>$passenger_list,'franchise'=>$franchise,'franchise_id'=>$franchise_id,'datet'=>$datet,'role'=>$role]);
			
			//=====EXPORT PDF AND EXCEL==========//
			if($request->has('export')){
				$file_name="total-rides-".date("d-m-Y");
				
				if($request->export == "pdf"){
			
					//return view('reports.export.pdf.pdf-success-ride');
					$pdf = PDF::loadView('reports.export.pdf.pdf-total-ride')->setPaper([0, 0, 1200, 1200], 'landscape');
					return $pdf->download($file_name.'.pdf');
				}else{
					Excel::create($file_name, function($excel) {
						$excel->sheet('total-ride-list', function($sheet) {
							$sheet->setSize(array(
								'A1' => array(
									'height'    => 20,
								)
							));
						$sheet->setAutoSize(true);
						  $sheet->loadView('reports.export.excel.excel-total-ride');
						});
					})->export('xls');
				}
			return false;
			}
		//=====END EXPORT PDF AND EXCEL==========//
			return view('reports.total_ride');
  }
  
  
  
  /********SHARE DETAILS LIST REPORT**************************/
  

 	public function drivers_share(Request $request){

 		$datet = 0;
 		if($request->from_date){
 			$datet = $request->from_date;
 		}
 		if($request->to_date){
 			$datet = $request->to_date;
 		}

 		$franchise = Franchise::where('status','=',1)->get();
 			$filter_query="";
		$role=Session::get('user_role');
 		if($role == 3){
 			$Franchise=Franchise::where('user_id','=',Auth::user()->id)->get();
			$franchise_id=$Franchise[0]->id;
			$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
			$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );
 		}else{

 			if($request->franchise){

 				$franchise_id = $request->franchise;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type WHERE cl.franchise_id = $franchise_id") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver WHERE franchise_id = $franchise_id") );

 			}else{
 				$franchise_id = 0;
 				$vehicle_list = DB::select( DB::raw("select cl.id,concat(cl.car_no,' - ',ct.car_type,' (',CASE WHEN ct.car_board= 1 THEN 'W' ELSE 'Y' END,')') AS car_no from wy_carlist cl LEFT JOIN wy_cartype ct ON ct.id=cl.car_type") );
				$driver_list = DB::select( DB::raw("select wy_driver.id,concat(wy_driver.driver_id,' - ',wy_driver.firstname,' ',wy_driver.lastname) as driver_id from wy_driver ") );
 			}
		
 		}
	
			//filter report
		$where = array();
		
		if($role == 3){
			$franchise_id=$Franchise[0]->id;
			$where[] = "dr.franchise_id ='$franchise_id'";
		}
		if($request->vehicle != NULL)	{	$where[] = "cl.id ='$request->vehicle'";}
		if($request->driver != NULL){	$where[] = "dr.id = '$request->driver'";	}
		$fdate=date("Y-m-d",strtotime($request->from_date));	$tdate=date("Y-m-d",strtotime($request->to_date));
		if($request->from_date != NULL && $request->to_date != NULL){
		$where[] = "r.date_of_ride BETWEEN '$fdate' AND '$tdate'";
		}else if($request->from_date != NULL){ $where[] = "r.date_of_ride = '$fdate'";}
		else if($request->to_date != NULL){ $where[] = "r.date_of_ride = '$tdate'";	}else{
			$where[] = "r.date_of_ride > DATE_SUB(now(), INTERVAL 6 MONTH)";
		}
		if(count($where)>0){

			$filter_query = " AND ".implode(" AND ", $where);
		}
		//DATA REPORT 
					$data_query = "SELECT r.date_of_ride,CONCAT(dr.firstname,'-',dr.driver_id) as driver_name,CONCAT(cl.car_no,'-',ct.car_type,'-',IF(ct.car_board =1,'W','Y') ) as car_no,
ROUND((r.final_amount*ct.attacheddriver_share/100),2) as driver_share,
ROUND((r.final_amount*ct.franchise_share/100),2) as franchise_share,
ROUND((r.final_amount*ct.companydriver_share/100),2) as company_share
FROM  wy_ride r 
JOIN wy_ridedetails rd ON r.id=rd.ride_id 
LEFT JOIN wy_driver dr ON rd.driver_id=dr.id 
LEFT JOIN wy_assign_taxi at ON dr.id =at.driver_id
LEFT JOIN wy_carlist cl ON at.car_num =cl.id
LEFT JOIN wy_cartype ct ON cl.car_type =ct.id where rd.ride_status=4 $filter_query";
					
		$driver_share = DB::select( DB::raw($data_query) );


		if($franchise_id){

			$tot_drivershare = DB::table('wy_ride')
			->join('wy_ridedetails','wy_ride.id','=','wy_ridedetails.ride_id')
			->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
			->where('wy_driver.franchise_id','=',$franchise_id)
			->sum('wy_ride.driver_share');
			
			$tot_companyshare = DB::table('wy_ride')	
			->join('wy_ridedetails','wy_ride.id','=','wy_ridedetails.ride_id')
			->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
			->where('wy_driver.franchise_id','=',$franchise_id)
			->sum('wy_ride.company_share');

			$tot_franchiseshare = DB::table('wy_ride')	
			->join('wy_ridedetails','wy_ride.id','=','wy_ridedetails.ride_id')
			->join('wy_driver', 'wy_driver.id', '=', 'wy_ridedetails.driver_id')
			->where('wy_driver.franchise_id','=',$franchise_id)
			->sum('wy_ride.franchise_share');

		}else{
			
			$tot_drivershare = DB::table('wy_ride')->sum('driver_share');
			$tot_franchiseshare = DB::table('wy_ride')->sum('franchise_share');
			$tot_companyshare = DB::table('wy_ride')->sum('company_share');
		}
		
		

		// $total_driver_share = DB::table('wy_ride')->sum('driver_share');
		// $total_company_share = DB::table('wy_ride')->sum('company_share');
	

			view()->share(['driver_share'=>$driver_share,'vehicle_list'=>$vehicle_list,'driver_list'=>$driver_list,'franchise'=>$franchise,'tot_drivershare'=>$tot_drivershare,'tot_franchiseshare'=>$tot_franchiseshare,'tot_companyshare'=>$tot_companyshare,'franchise_id'=>$franchise_id,'datet'=>$datet,'role'=>$role]);
			
			//=====EXPORT PDF AND EXCEL==========//
			if($request->has('export')){
				$file_name="driver-share-details-".date("d-m-Y");
				
				if($request->export == "pdf"){
			
					//return view('reports.export.pdf.pdf-success-ride');
					$pdf = PDF::loadView('reports.export.pdf.pdf-shared-driver')->setPaper('a4', 'landscape');
					return $pdf->download($file_name.'.pdf');
				}else{
					Excel::create($file_name, function($excel) {
						$excel->sheet('driver-share-details', function($sheet) {
							$sheet->setSize(array(
								'A1' => array(
									'height'    => 20,
								)
							));
						$sheet->setAutoSize(true);
						  $sheet->loadView('reports.export.excel.excel-shared-driver');
						});
					})->export('xls');
				}
			return false;
			}
		//=====END EXPORT PDF AND EXCEL==========//
			return view('reports.drivers_share');
  }


  public function getvehicledriver($id)
  {
  	# code...
  	$query = "SELECT cl.*,ct.* FROM wy_carlist cl INNER JOIN wy_cartype ct ON cl.car_type = ct.id WHERE cl.franchise_id = $id";
	$data = DB::select( DB::raw($query) );
  	$driver = AddDriver::where('franchise_id','=',$id)->get();
  	$car = Car::where('franchise_id','=',$id)->get();
  	return response()->json([
  			'vehicle_list' => $data,
  			'driver_list' => $driver,
  		]);
  }

}


