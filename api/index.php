<?php
date_default_timezone_set("Asia/Kolkata");
$con=mysql_connect('localhost','root','qV2D5bfuuA/KYAswtR1wHw==') or ('error');
mysql_select_db('mobycabs',$con);
define("FIREBASE_API_KEY", "AAAAfsxv2qs:APA91bHaO_ZafPsVK0okVdIowloYyKjaf6eMpsARWMV_RH98U6mKH5ohQttw10jLMVX9CirPNtJOnoD9Iu6aDAUrFVeg8KtrMdv4zR5iaPkAGPNijUzU_qmJK5xRPELdIPT07QUOy7K_");
define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");
require_once('PHPMailer_5.2.0/class.phpmailer.php');
require 'Slim/Slim.php';
$app = new Slim();

$app->post('/UserRegister', 'UserRegister');
$app->post('/login', 'login');
$app->post('/login_driver', 'login_driver');
$app->post('/otp_verification', 'otp_verification');
$app->post('/driverlocation', 'driverlocation');
$app->post('/conformbooking', 'conformbooking');
$app->get('/get_car', 'get_car'); 
$app->get('/resend_otp_user/:userid', 'resend_otp_user');
$app->get('/get_requests/:driver_id', 'get_requests');
$app->get('/process_requests/:ride_id/:driver_id/:status', 'process_requests');
$app->get('/reached_pickup/:ride_id/:driver_id', 'reached_pickup');
$app->get('/onlinestatus/:driver_id/:status', 'onlinestatus');
$app->get('/ratecard/:car_type/:booking_type', 'ratecard');
$app->post('/rate_estimate', 'rate_estimate');
$app->post('/cancel_ride', 'cancel_ride');
$app->get('/getride_details/:ride_id/:type', 'getride_details');
$app->get('/start_ride_v1/:ride_id/:driver_id/:crn', 'start_ride_v1');
$app->get('/start_ride/:ride_id/:driver_id', 'start_ride');
$app->post('/forgotpassword', 'forgotpassword');
$app->post('/endride', 'endride');
$app->get('/getbill/:ride_id/:type', 'getbill');
$app->get('/getcartype', 'getcartype');
$app->get('/getactiveride/:user_id', 'getactiveride');
$app->post('/userrating', 'userrating');
$app->post('/driverrating', 'driverrating');
$app->post('/add_taximoney', 'add_taximoney');
$app->get('/user_logout/:user_id', 'user_logout');
$app->get('/get_taximoney/:user_id', 'get_taximoney');
$app->post('/add_emergency', 'add_emergency');
$app->get('/emergency_status/:contactid/:status', 'emergency_status');
$app->delete('/delete_emergency', 'delete_emergency');
$app->get('/get_emergency/:user_id', 'get_emergency');
$app->post('/send_alert', 'send_alert');
$app->put('/changepassword', 'changepassword');
$app->put('/updateprofile', 'updateprofile');
$app->put('/updatemobile', 'updatemobile');
$app->put('/resend_otp_update', 'resend_otp_update');
$app->get('/ridehistory_customer/:user_id', 'ridehistory_customer');
$app->get('/ridehistory_driver/:user_id', 'ridehistory_driver');
$app->put('/driver_update', 'driver_update');
$app->get('/driver_rating/:user_id', 'driver_rating');
$app->post('/email_invoice', 'email_invoice');
$app->get('/getrate_reasons', 'getrate_reasons');
$app->get('/ratecard_details/:cartype/:booking_type', 'ratecard_details');
$app->post('/contact_us', 'contact_us');
$app->get('/logout/:driver_id', 'logout');
$app->get('/get_offers/:user_id', 'get_offers');
$app->post('/chk_offers', 'chk_offers');
$app->post('/addoffer_wallet', 'addoffer_wallet');
$app->post('/bill_offline', 'bill_offline');
$app->get('/chk_peaktime/:ride_category/:car_type', 'chk_peaktime');
$app->post('/getcartype_avail', 'getcartype_avail');
$app->get('/driver_details/:driver_id', 'driver_details');
$app->post('/conformbooking_firebase', 'conformbooking_firebase');
$app->post('/get_package', 'get_package');
$app->get('/resend_crn/:ride_id', 'resend_crn');
$app->post('/reports','reports');

$app->run();

//checking authentication of user
function check_authtoken($auth_token,$auth_id,$type){
	$db = getConnection();
	// 1-customer, 2-driver
	if($type==1){
		$get_tkn = "select id from wy_customer where auth_token=:auth_token and id=:id"; 
		$stmt = $db->prepare($get_tkn); 
		$stmt->bindParam("auth_token", $auth_token);
		$stmt->bindParam("id", $auth_id);
		$stmt->execute();
		$get_det = $stmt->fetch(PDO::FETCH_OBJ);
		if($get_det){
			return 1;
		}else{
			return 0;
		}
	}else{
		$get_tkn = "select id from wy_driver where auth_token=:auth_token and id=:id"; 
		$stmt = $db->prepare($get_tkn); 
		$stmt->bindParam("auth_token", $auth_token);
		$stmt->bindParam("id", $auth_id);
		$stmt->execute();
		$get_det = $stmt->fetch(PDO::FETCH_OBJ);
		if($get_det){
			return 1;
		}else{
			return 0;
		}
	}
}
//

function reports()
{
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d');
	
	// $cancel="select * from wy_ride where (ride_status not in ('4','1')) and (created_at between :fromdate and :todate) and (customer_id=:auth_id)";
	// $succes="select * from wy_ride where (ride_status='4') and (created_at between :fromdate and :todate) and (customer_id=:user_id)";
	$sql="SELECT sum(if(padi_by=1,driver_share,0)) as cash,sum(if(padi_by=2,driver_share,0)) as paytm,sum(driver_share) as driver_share, (select count(id) from wy_ridedetails where (ride_status='4') and (date(created_at)= :fromdate) and driver_id= :user_id) as success,
 (select count(id) from wy_ridedetails where (date(created_at)=:fromdate) and driver_id=:user_id) as total_ride,
 (select count(id) from wy_ridedetails where accept_status in ('2','3') and (date(created_at)= :fromdate) and driver_id=:user_id) as cancel
 from wy_ride LEFT JOIN wy_ridedetails ON wy_ride.id=wy_ridedetails.ride_id where wy_ridedetails.driver_id=:user_id and (date(wy_ridedetails.created_at)= :fromdate)";

	try{
		$headers = $request->headers();  
		if(isset($headers['authorization'])) {
			
			//$arr=array();
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db = getConnection();
				
				
				$fromDate = $register->fromdate;
				$toDate = $register->todate;


				while (strtotime($fromDate) <= strtotime($toDate)) {
				$stmt = $db->prepare($sql); 
				$stmt->bindParam("user_id", $auth_id);
				$stmt->bindParam("fromdate",$fromDate);
				$stmt->execute();
				$get_det = $stmt->fetch(PDO::FETCH_OBJ);
				$temp_date = date('Y-m-d',strtotime($fromDate));
				$arr[] = array($temp_date=>$get_det);
                $fromDate = date ("Y-m-d", strtotime("+1 day", strtotime($fromDate)));
			}

				
 		 //$sql1="SELECT sum(driver_share) as driver_share, (select count(id) from wy_ridedetails where (date(created_at) BETWEEN :fromdate and :todate) and driver_id=:userid) as total_ride from wy_ride LEFT JOIN wy_ridedetails ON wy_ride.id=wy_ridedetails.ride_id where wy_ridedetails.driver_id=:userid and (date(wy_ridedetails.created_at) BETWEEN :fromdate and :todate)";
 		   $sql1="SELECT sum(driver_share) as driver_share, (select count(id) from wy_ridedetails where (date(created_at) BETWEEN '$register->fromdate' and '$toDate') and driver_id='$auth_id') as total_ride from wy_ride LEFT JOIN wy_ridedetails ON wy_ride.id=wy_ridedetails.ride_id where wy_ridedetails.driver_id='$auth_id' and (date(wy_ridedetails.created_at) BETWEEN '$register->fromdate' and '$toDate')";
			
			$stmt = $db->query($sql1);
			$det = $stmt->fetch(PDO::FETCH_OBJ);


				echo '{"Result":"Success","details":'.json_encode($arr).',"over_all":'.json_encode($det).'}';
			}
			else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}
		else
		{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}


	}
	catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}

}

function resend_crn($ride_id){
	$request = Slim::getInstance()->request();
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db = getConnection();
				$crn = rand (1000,9999);
				$sql1="select c.mobile,c.device_type,c.device_token from wy_ride r join wy_customer c on c.id=r.customer_id where r.id=:ride_id"; 
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("ride_id", $ride_id);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_OBJ);
				if($user){
					$sql="select * from wy_ride where  id=:ride_id";

					$stmt = $db->prepare($sql); 
					$stmt->bindParam("ride_id", $ride_id);
					$stmt->execute();
					$ride = $stmt->fetch(PDO::FETCH_OBJ);
					if($ride){
						$sql2="update wy_ride set crn=:crn where id=:ride_id"; 
						$stmt = $db->prepare($sql2); 
						$stmt->bindParam("ride_id", $ride_id);
						$stmt->bindParam("crn", $crn);
						$stmt->execute();
						
						$message1 = "Your OTP Number is $crn";
						if($user->device_type==1){
							if($user->device_token!=''){
								apns_cus($user->device_token,$message1,$ride_id);
							}
						}else{
							send_gcm_notify($user->device_token, $message1,$ride_id );
						}
									
						// SEND CRN NUMBER TO CUSTOMER
						/* $message   =  "Your booking reference number is $ride->reference_id and your CRN number is: $crn";
						$message = urlencode($message);
						$mobile   =  $user->mobile;
						$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);      
						curl_close($ch); */ 
						echo '{"Result":"Success","Status":"OTP sent successfully"}';
					}else{
						echo '{"Result":"Failed","Status":"Ride not found"}';
					}
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}
//

function get_package(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$sql1="select id from wy_customer where id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("user_id", $auth_id);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_OBJ);
				if($user){
					$sql2="select f.min_time,f.fare_id,f.car_id,f.ride_category,f.package_id,f.booking_type,f.package_days,p.package_name,f.district,f.fare_type,f.min_km,f.min_fare_amount,f.ride_each_km,f.ride_fare,f.distance_time,f.distance_fare from wy_faredetails f join wy_packagelist p on p.id=f.package_id where f.car_id=:car_type and f.booking_type=:booking_type";
					$stmt = $db->prepare($sql2); 
					$stmt->bindParam("car_type", $register->car_type);
					$stmt->bindParam("booking_type", $register->booking_type);
					$stmt->execute();
					$pack = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($pack){
						if($register->booking_type==2){
							$q = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$register->source_lat,$register->source_lng&destinations=$register->dest_lat,$register->dest_lng&mode=driving&sensor=false";
							$json = file_get_contents($q); 
							$details = json_decode($json, TRUE); //print_r($details); exit;
							$duration = $details['rows'][0]['elements'][0]['duration']['text'];
							$durationv = $details['rows'][0]['elements'][0]['duration']['value'];
							$distance = $details['rows'][0]['elements'][0]['distance']['text'];
							$distancev = $details['rows'][0]['elements'][0]['distance']['value'];
							$dist = explode(" ",$distance);
							$tax = selectsinglevalue("select count(percentage) as retv from wy_tax");
							foreach($pack as $val){
								if($dist[0]>($val->min_km)){
									$balkm = $dist[0]-($val->min_km);
									$balamt=$balkm*$val->ride_fare;
								}else{
									$balkm=0;
									$balamt=0;
								}
								$tamt=($val->min_fare_amount)+$balamt;
								$arrtime= date("Y-m-d H:i:s",strtotime($register->booking_time."+".$val->package_days." day"));
								$packArr[]=array(
									"package_name" => $val->package_name,
									"package" => $val->package_id,
									"total amount" => $tamt,
									"estimated_distance" => $distance,
									"min_km" => $val->min_km,
									"minkm_fare" => $val->min_fare_amount,
									"ride_additional_kms" => $balamt,
									"ride_charge" => $val->distance_fare,
									"tax" => $tamt*($tax/100),
									"trip_duration" => $val->package_days*24,
									"departure" => $register->booking_time,
									"arrived" => $arrtime,
								);
							}
						}else{
							foreach($pack as $val){
								$packArr[]=array(
									"package_name" => $val->package_name,
									"package" => $val->package_id,
									"total amount" => '',
									"estimated_distance" => '',
									"min_km" => $val->min_km,
									"minkm_fare"=> $val->min_fare_amount,
									"ride_additional_kms" => $val->ride_fare,
									"ride_charge" => $val->distance_fare,
									"tax" => '',
									"trip_duration" => $val->min_time,
									"departure" => '',
									"arrived" => '',
								);
							}
						}
						echo '{"Result":"Success","details":'.json_encode($packArr).'}';
					}else{
						echo '{"Result":"Failed","Status":"No packages found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function conformbooking_firebase(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$sql1="select id from wy_customer where id=:user_id";
	if($register->ride_category==1) $cat = "cabs";
	else if($register->ride_category==2) $cat = "auto";
	else  $cat = "auto";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_OBJ);
				$car_board = selectsinglevalue("select car_board as retv from wy_cartype where id='$register->car_type'");
				$car_type = selectsinglevalue("select car_type as retv from wy_cartype where id='$register->car_type'");
				if($user){
					$sql3=selectsinglevalue("select id as retv from wy_ride order by id desc limit 1");
					if($sql3!=''){
						$ordid = $sql3+1;
						$ref_id = "WYDSTXCBE00".$ordid;
					}else{
						$ref_id = "WYDSTXCBE001";
					}
					if($register->coupon_code!='')
						$offer_id = selectsinglevalue("select id as retv from wy_offers where coupon_code='$register->coupon_code'");
					else
						$offer_id = "";
					if($register->ride_type=='1'){
						$qry = "select rd.driver_id from wy_ride r join wy_ridedetails rd on r.id=rd.ride_id where r.customer_id=:user_id and (rd.accept_status in ('0','1') and rd.ride_status in ('0','1','2','3'))";
						$stmt = $db->prepare($qry);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->execute();
						$ridelist = $stmt->fetchAll(PDO::FETCH_OBJ);
						if(!$ridelist){ 
							for($i=0;$i<count($register->driverid);$i++){ 
								$d_id = $register->driverid[$i]->id; 
								$sql2="select id,device_type,device_token from wy_driver where online_status=1 and id=:driver_id";
								$stmt = $db->prepare($sql2);
								$stmt->bindParam("driver_id", $register->driverid[$i]->id);
								$stmt->execute();							
								$drivers = $stmt->fetch(PDO::FETCH_OBJ);
								if($drivers){
									$sql6="select driver_id from wy_ridedetails where driver_id=:driver_id and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
											ORDER BY `wy_ridedetails`.`id` ASC";
									$stmt = $db->prepare($sql6);
									$stmt->bindParam("driver_id", $drivers->id);
									$stmt->execute();	
									$driverslistdet = $stmt->fetchAll(PDO::FETCH_OBJ);
									if(!$driverslistdet){
										$fail = 0;
										$sql4="insert into wy_ride(offer_id,coupon_code,ride_category,booking_type,city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at) 
												values(:offer_id,:coupon_code,:ride_category,:booking_type,:city,:ref_id,:user_id,:location,:lat,:lng,:car_type,:ride_type,:date_of_ride,:destination_location,:destination_lat,:destination_lng,:created_at,:updated_at)";
										$stmt = $db->prepare($sql4);
										$stmt->bindParam("offer_id", $offer_id);
										$stmt->bindParam("coupon_code", $register->coupon_code);
										$stmt->bindParam("ride_category", $register->ride_category);
										$stmt->bindParam("booking_type", $register->booking_type);
										$stmt->bindParam("city", $register->city);
										$stmt->bindParam("ref_id", $ref_id);
										$stmt->bindParam("user_id", $register->user_id);
										$stmt->bindParam("location", $register->location);
										$stmt->bindParam("lat", $register->lat);
										$stmt->bindParam("lng", $register->lng);
										$stmt->bindParam("car_type", $register->car_type);
										$stmt->bindParam("ride_type", $register->ride_type);
										$stmt->bindParam("date_of_ride", $date1);
										$stmt->bindParam("destination_location", $register->destination_location);
										$stmt->bindParam("destination_lat", $register->destination_lat);
										$stmt->bindParam("destination_lng", $register->destination_lng);
										$stmt->bindParam("created_at", $date);
										$stmt->bindParam("updated_at", $date);
										$stmt->execute();
										$ride_id = $db->lastInsertId();
										$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values(:ride_id,:driver_id,:created_at,:updated_at)";
										$stmt = $db->prepare($sql5);
										$stmt->bindParam("ride_id", $ride_id);
										$stmt->bindParam("driver_id", $drivers->id);
										$stmt->bindParam("created_at", $date);
										$stmt->bindParam("updated_at", $date);
										$stmt->execute();
										$device_type = $drivers->device_type;
										$device_token = $drivers->device_token;
										$message = "request for ride";
										if($device_type==1){
											if($device_token!=''){
												apns($device_token,$message,$ride_id);
											}
										}else{
											send_gcm_notify($device_token, $message,$ride_id );
										}
										$sql6="update wy_driver set online_status=0 where id=:driver_id";
										$stmt = $db->prepare($sql6);
										$stmt->bindParam("driver_id", $drivers->id);
										$stmt->execute();
										echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
										/// firebase customer
										$date_fmt = date("d-m-Y");
										$header = array();
										$header[] = 'Content-Type: application/json';
										$postdata = '{"ride_type":"'.$register->ride_type.'","car_board":"'.$car_board.'","car_type":"'.$car_type.'","ride_category":"'.$register->ride_category.'","profile_photo":"","source_lng":"'.$register->lng.'","source_lat":"'.$register->lat.'","destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
										
										$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
										curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
										//curl_setopt($ch, CURLOPT_POST,1);
										curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
										curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
										$result = curl_exec($ch); 
										curl_close($ch); 
										///firebase
										/// firebase driver
										$date_fmt = date("d-m-Y");
										$header = array();
										$header[] = 'Content-Type: application/json';
										$postdata = '{"request_time":"'.$date.'","ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$register->car_type.'","reference_id":"'.$ref_id.'","customer_id":"'.$register->user_id.'","source_location":"'.$register->location.'","source_lat":"'.$register->lat.'","source_lng":"'.$register->lng.'","ride_type":"'.$register->ride_type.'","ride_category":"'.$register->ride_category.'","booking_type":"'.$register->booking_type.'","destination_location":"'.$register->destination_location.'","destination_lat":"'.$register->destination_lat.'","destination_lng":"'.$register->destination_lng.'"}';
										
										$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$drivers->id.json");
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
										curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
										//curl_setopt($ch, CURLOPT_POST,1);
										curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
										curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
										$result = curl_exec($ch); 
										curl_close($ch); 
										///firebase
										break;
									}else{
										$fail = 1;
									}
									/* if($fail==1){
										echo '{"Result":"Failed","Status":"No '.$cat.' available in your area"}';
									} */
								}else{
									$fail = 1;
								}
							}
							if($fail==1){
								echo '{"Result":"Failed","Status":"No nearby '.$cat.' found"}';
							}
						}else{
							echo '{"Result":"Failed","Status":"You cannot book a '.$cat.' while in another ride"}';
						}
					}else{
						$sc_time = $register->schedule_date." ".$register->schedule_time;
						$sc_time = date("Y-m-d H:i:s",strtotime($sc_time));
						$cur_time = date("Y-m-d H:i:s",strtotime("+50 minute"));
						$sql5 = "select id,schedule_date,schedule_time from wy_ride where customer_id=:user_id and ride_type='2' and ride_status='0'";
						$stmt = $db->prepare($sql5);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->execute();
						$schlist = $stmt->fetch(PDO::FETCH_OBJ);
						if($schlist){
							$sc_ptime = $schlist->schedule_date." ".$schlist->schedule_time;
							$sc_ptime = date("Y-m-d H:i:s",strtotime($sc_ptime));
							$sc_ptime1 = date("Y-m-d H:i:s",strtotime("-1 hour".$sc_ptime));
							$sc_ptime2 = date("Y-m-d H:i:s",strtotime("+1 hour".$sc_ptime));
							if(strtotime($sc_time)>strtotime($sc_ptime1) && strtotime($sc_time)<strtotime($sc_ptime2)){
								echo '{"Result":"Failed","Status":"Ride cannot be scheduled. Already there is a ride among this time."}';
							}else{
								if(strtotime($sc_time)>= strtotime($cur_time)){
									$sql4="insert into wy_ride(offer_id,coupon_code,ride_category,booking_type,city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at) 
												values(:offer_id,:coupon_code,:ride_category,:booking_type,:city,:ref_id,:user_id,:location,:lat,:lng,:car_type,:ride_type,:date_of_ride,:destination_location,:destination_lat,:destination_lng,:created_at,:updated_at)";
									$stmt = $db->prepare($sql4);
									$stmt->bindParam("offer_id", $offer_id);
									$stmt->bindParam("coupon_code", $register->coupon_code);
									$stmt->bindParam("ride_category", $register->ride_category);
									$stmt->bindParam("booking_type", $register->booking_type);
									$stmt->bindParam("city", $register->city);
									$stmt->bindParam("ref_id", $ref_id);
									$stmt->bindParam("user_id", $register->user_id);
									$stmt->bindParam("location", $register->location);
									$stmt->bindParam("lat", $register->lat);
									$stmt->bindParam("lng", $register->lng);
									$stmt->bindParam("car_type", $register->car_type);
									$stmt->bindParam("ride_type", $register->ride_type);
									$stmt->bindParam("date_of_ride", $date1);
									$stmt->bindParam("destination_location", $register->destination_location);
									$stmt->bindParam("destination_lat", $register->destination_lat);
									$stmt->bindParam("destination_lng", $register->destination_lng);
									$stmt->bindParam("created_at", $date);
									$stmt->bindParam("updated_at", $date);
									$stmt->execute();
									$ride_id = $db->lastInsertId();
									echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
									/// firebase customer
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"ride_type":"'.$register->ride_type.'","ride_category":"'.$register->ride_category.'","profile_photo":"","source_lng":"'.$register->lng.'","source_lat":"'.$register->lat.'","destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								}else{
									echo '{"Result":"Failed","Status":"Ride cannot be scheduled in past time."}';
								}
							}
						}else{
							if(strtotime($sc_time)>= strtotime($cur_time)){
								$sql4="insert into wy_ride(offer_id,coupon_code,ride_category,booking_type,city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at) 
										values(:offer_id,:coupon_code,:ride_category,:booking_type,:city,:ref_id,:user_id,:location,:lat,:lng,:car_type,:ride_type,:date_of_ride,:destination_location,:destination_lat,:destination_lng,:created_at,:updated_at)";
								$stmt = $db->prepare($sql4);
								$stmt->bindParam("offer_id", $offer_id);
								$stmt->bindParam("coupon_code", $register->coupon_code);
								$stmt->bindParam("ride_category", $register->ride_category);
								$stmt->bindParam("booking_type", $register->booking_type);
								$stmt->bindParam("city", $register->city);
								$stmt->bindParam("ref_id", $ref_id);
								$stmt->bindParam("user_id", $register->user_id);
								$stmt->bindParam("location", $register->location);
								$stmt->bindParam("lat", $register->lat);
								$stmt->bindParam("lng", $register->lng);
								$stmt->bindParam("car_type", $register->car_type);
								$stmt->bindParam("ride_type", $register->ride_type);
								$stmt->bindParam("date_of_ride", $date1);
								$stmt->bindParam("destination_location", $register->destination_location);
								$stmt->bindParam("destination_lat", $register->destination_lat);
								$stmt->bindParam("destination_lng", $register->destination_lng);
								$stmt->bindParam("created_at", $date);
								$stmt->bindParam("updated_at", $date);
								$stmt->execute();
								$ride_id = $db->lastInsertId();
								echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
								/// firebase customer
								$date_fmt = date("d-m-Y");
								$header = array();
								$header[] = 'Content-Type: application/json';
								$postdata = '{"ride_type":"'.$register->ride_type.'","ride_category":"'.$register->ride_category.'","profile_photo":"","source_lng":"'.$register->lng.'","source_lat":"'.$register->lat.'","destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
								
								$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
								//curl_setopt($ch, CURLOPT_POST,1);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
								curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
								$result = curl_exec($ch); 
								curl_close($ch); 
								///firebase
							}else{
								echo '{"Result":"Failed","Status":"Ride cannot be scheduled in past time."}';
							}
						}
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function driver_details($driver_id){
	$request = Slim::getInstance()->request();
	$date=date('Y-m-d H:i:s');
	$sql1="select d.*,ct.car_type,ct.car_board,ct.yellow_caricon,ct.franchise_share,if(d.driver_type=1,ct.companydriver_share,ct.attacheddriver_share) as share,c.car_no from wy_driver d join wy_assign_taxi a on a.driver_id=d.id join wy_carlist c on a.car_num=c.id join wy_cartype ct on c.car_type=ct.id where d.id=:driver_id and a.status='1' ";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql1);
				$stmt->bindParam("driver_id", $driver_id);
				$stmt->execute();
				$user1 = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($user1){
					echo '{"Result":"Success","userdetails":'.json_encode($user1).'}';
				}else{
					echo '{"Result":"Failed","Status":"Invalid driver id"}';
				}
			}else{
				//$response=Slim::getInstance()->response(); print_r($response);
				//$app->response->setStatus(400);
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}	
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}	
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function getcartype_avail(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$stat=0;
	$sql = "select id from wy_cartype where ride_category=:ride_category order by ride_category asc,car_board desc,capacity asc";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_category", $register->ride_category);
				$stmt->execute();
				$ride = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($ride){	
					foreach($ride as $val){ 
						$sql2="select u.id,(6371*acos(cos(radians(:source_lat))*cos(radians(d.lat))*cos(radians(d.lng)-radians(:source_lng))+sin(radians(:source_lat))*sin(radians(d.lat)))) distance from wy_driver u 
							join wy_assign_taxi a on a.driver_id=u.id join wy_carlist c on a.car_num=c.id 
							join wy_driverlocation d on u.id=d.driver_id 
							where c.car_type=:car_type and u.online_status=1 and a.status=1
							having 	distance<3 order by distance asc ";
						//echo $sql2;
						$stmt = $db->prepare($sql2); 
						$stmt->bindParam("source_lat", $register->source_lat);
						$stmt->bindParam("source_lng", $register->source_lng);
						$stmt->bindParam("car_type", $val->id);
						$stmt->execute();
						$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($drivers){
							$stat=0;
							$cartype=$val->id;
							foreach($drivers as $drv){
								/* $sql3="select * from wy_ridedetails where driver_id='$drv->id'";
								$stmt = $db->query($sql3);
								$driverslist = $stmt->fetchAll(PDO::FETCH_OBJ);
								if($driverslist){ */
									$stat=0;
									$sql6="select driver_id from wy_ridedetails where driver_id=:driver_id and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
											ORDER BY `wy_ridedetails`.`id` ASC";
									$stmt = $db->prepare($sql6);
									$stmt->bindParam("driver_id",$drv->id);
									$stmt->execute();
									$driverslistdet = $stmt->fetchAll(PDO::FETCH_OBJ);
									if(!$driverslistdet){
										echo '{ "Result": "Success","Cartype_id":"'.$cartype.'"}';
										exit;
									}else{
										$stat=1;
									}
								/* }else{
									$stat=0;
									echo '{ "Result": "Success","Cartype_id":"'.$cartype.'"}';
									exit;
								} */
							}
						}else{
							$stat=1;
						}
					}
					if($stat==1){
						echo '{ "Result": "Failed","Status":"Cartype not available"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Cartype not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function chk_peaktime($ride_category,$car_type){
	$request = Slim::getInstance()->request();
	$sql = "select id,fare_type,ride_start_time,ride_end_time from wy_faredetails where status=1 and ride_category=:ride_category and car_id=:car_type order by fare_type desc";
	$date1=date("Y-m-d");
	$date=date("Y-m-d H:i:s");
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_category",$ride_category);
				$stmt->bindParam("car_type",$car_type);
				$stmt->execute();
				$res = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($res){
					foreach($res as $val){
						if($val->fare_type >1){ 
							$start_time = $val->ride_start_time;
							$end_time = $val->ride_end_time;
							$srt_datetime = $date1." ".$start_time;
							$srt_datetime = date("Y-m-d H:i:s",strtotime($srt_datetime));
							if(strtotime($start_time)>strtotime($end_time)){
								$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
								$end_datetime = $date1." ".$end_time;
								$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
							}else{
								$end_datetime = $date1." ".$end_time;
								$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
							} 
							if(strtotime($date)>=strtotime($srt_datetime) && strtotime($date)<strtotime($end_datetime)){
								$peak = "Peaktime charge applicable";
								break;
							}else{  
								$peak ="";
							}
						}else{  
							$peak ="";
						}
					}
					echo '{ "Result": "Success","Status":"'.$peak.'"}';
				}else{
					echo '{ "Result": "Failed","Status":"Fare details not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function bill_offline(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	// added d.isfranchise in $sql to get driver isfranchise status
	// added ct.franchise_share in $sql to get franchise share percentage
	$sql = "select r.*,d.driver_type,d.isfranchise,ct.companydriver_share,ct.franchise_share,ct.attacheddriver_share,c.driver_id,c.start_ride_time,c.end_ride_time,cu.mobile,cu.device_type,cu.device_token from wy_ride r join wy_ridedetails c on c.ride_id=r.id 
				join  wy_customer cu on cu.id=r.customer_id 
				join wy_driver d on d.id=c.driver_id join wy_cartype ct on ct.id=r.car_type where r.ride_status=1 and r.id=:ride_id and c.driver_id=:driver_id";
	//$sql = "select * from wy_ride where id='$register->ride_id'";
	$sql1 = "update wy_ridedetails set ride_status='4',end_ride_time=:end_ride_time where ride_id=:ride_id and driver_id=:driver_id";
	$sql2 = "update wy_ride set peak_amount=:peak_amount,peak_percent=:peak_percent,ride_status='4',fare_id=:fare_id,fare_type=:fare_type,destination_location=:destination_location,destination_lat=:destination_lat,destination_lng=:destination_lng,distance=:distance,waiting_time=:waiting_time,distance_amount=:distance_amount,minimum_charge=:minimum_charge,ride_charge=:ride_charge,
			rideing_time=:rideing_time,waiting_charge=:waiting_charge,total_tax=:total_tax,total_amount=:total_amount,updated_at=:updated_at,offer_amount=:offer_amount,final_amount=:final_amount,driver_share=:driver_share,company_share=:company_share,franchise_share=:franchise_share,ride_endby='2',padi_by='1',paid_cash=:paid_cash where id=:ride_id";
	$sql3="insert into wy_ride_location(ride_id,lat,lng,datetime,created_at,updated_at) values(:ride_id,:lat,:lng,:datetime,:created_at,:updated_at)";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id",$register->ride_id);
				$stmt->bindParam("driver_id",$register->driver_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){	
					// Check driver isfranchise or not
					if($ride->isfranchise == 1){ 
						$franchise_share = round(($register->final_amount * ($ride->franchise_share/100)),2);
						$company_share = round(($register->final_amount * ($ride->companydriver_share/100)),2);
						$driver_share= round(($register->final_amount * ($ride->attacheddriver_share/100)),2);
					}else{
						if($ride->driver_type == 1){
							$vehicle_check = vehicle_isfranchise($register->driver_id);
							if($vehicle_check == 1){
								$franchise_share = $register->final_amount * ($ride->franchise_share/100);
								$driver_share = $register->final_amount * ($ride->companydriver_share/100);
								$company_share = $register->final_amount * ($driver_share + $franchise_share);
							}
							else{
								$driver_share = $register->final_amount * ($ride->companydriver_share/100);
								$company_share = $register->final_amount * $driver_share;
							}
						}else{
							$driver_share = round(($register->final_amount * ($ride->attacheddriver_share/100)),2);
							$company_share = round(($register->final_amount * ($ride->companydriver_share/100)),2);
						}
					}
					if(empty($franchise_share)){
						$franchise_share = 0;
					}
					
					// Check offer or not
					if($register->offer_amount!='' && $register->offer_amount!='0.0'){
						$offr = "select id,usage_count from wy_offers where coupon_code=:coupon_code";
						$stmt = $db->prepare($offr);
						$stmt->bindParam("coupon_code",$ride->coupon_code);
						$stmt->execute();
						$offers = $stmt->fetch(PDO::FETCH_OBJ);
						$rideoffr = selectsinglevalue("select count(*) as retv from wy_ride where customer_id='$ride->customer_id' and coupon_code='$ride->coupon_code' and ride_status='4'");
						$rideoffr = $rideoffr+1;
						if($rideoffr==$offers->usage_count){
							$sql13 = "update wy_offernotification set usage_count=usage_count+1, used='1', updated_at=:updated_at where offer_id =:id and user_id=:customer_id";
							$stmt = $db->prepare($sql13);
							$stmt->bindParam("id",$offers->id);
							$stmt->bindParam("customer_id",$ride->customer_id);
							$stmt->bindParam("updated_at",$date);
							$stmt->execute();
						}else{
							$sql13 = "update wy_offernotification set usage_count=usage_count+1, updated_at=:updated_at where offer_id =:id and user_id=:customer_id";
							$stmt = $db->prepare($sql13);
							$stmt->bindParam("id",$offers->id);
							$stmt->bindParam("customer_id",$ride->customer_id);
							$stmt->bindParam("updated_at",$date);
							$stmt->execute();
						}
					}
					///
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("ride_id",$register->ride_id);
					$stmt->bindParam("driver_id",$register->driver_id);
					$stmt->bindParam("end_ride_time",$date);
					$stmt->execute();
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("ride_id", $register->ride_id);
					$stmt->bindParam("peak_amount", $register->peak_amount);
					$stmt->bindParam("peak_percent", $register->peak_percent);
					$stmt->bindParam("fare_id", $register->fare_id);
					$stmt->bindParam("fare_type", $register->fare_type);
					$stmt->bindParam("distance", $register->distance);
					$stmt->bindParam("destination_location", $register->destination_location);
					$stmt->bindParam("destination_lng", $register->destination_lng);
					$stmt->bindParam("destination_lat", $register->destination_lat);
					$stmt->bindParam("rideing_time", $register->rideing_time);
					$stmt->bindParam("waiting_time", $register->waiting_time);
					$stmt->bindParam("distance_amount", $register->distance_amount);
					$stmt->bindParam("minimum_charge", $register->minimum_charge);
					$stmt->bindParam("ride_charge", $register->ride_charge);
					$stmt->bindParam("waiting_charge", $register->waiting_charge);
					$stmt->bindParam("total_tax", $register->tax);
					$stmt->bindParam("total_amount", $register->total_amount);
					$stmt->bindParam("offer_amount", $register->offer_amount);
					$stmt->bindParam("final_amount", $register->final_amount);
					$stmt->bindParam("company_share", $company_share);
					$stmt->bindParam("franchise_share", $franchise_share); // update franchise share
					$stmt->bindParam("driver_share", $driver_share);
					$stmt->bindParam("updated_at", $date);
					$stmt->bindParam("paid_cash", $register->final_amount);
					$stmt->execute();
					echo '{ "Result": "Success","Status":"Ride details updated"}';
					
						/// firebase
						$date_fmt = date("d-m-Y");
						$header = array();
						$header[] = 'Content-Type: application/json';
						$postdata = '{"destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","accept_status":"1","ride_status":"4"}';
					
						$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						//curl_setopt($ch, CURLOPT_POST,1);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
						$result = curl_exec($ch); 
						curl_close($ch); 
						///firebase
					
					/// firebase driver
					$date_fmt = date("d-m-Y");
					$header = array();
					$header[] = 'Content-Type: application/json';

					$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$ride->driver_id.json");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					//curl_setopt($ch, CURLOPT_POST,1);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
					$result = curl_exec($ch); 
					curl_close($ch); 
					///firebase
					
					for($i=0;$i<count($register->details);$i++){
						$stmt = $db->prepare($sql3); 
						$stmt->bindParam("ride_id", $register->ride_id);
						$stmt->bindParam("lat", $register->details[$i]->lat);
						$stmt->bindParam("lng", $register->details[$i]->lng);
						$stmt->bindParam("datetime", $register->details[$i]->datetime);
						$stmt->bindParam("created_at", $date);
						$stmt->bindParam("updated_at", $date);
						$stmt->execute();
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function addoffer_wallet(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$time=date('H:i:s');
	$sql = "select id from wy_customer where id=:user_id";
	$sql1 = "select n.*,o.usage_count as uc,o.coupon_value,o.coupon_type,o.coupon_basedon,o.coupon_typevalue,o.valid_from from wy_offernotification n join wy_offers o on o.id=n.offer_id where n.coupon_code=:coupon_code and n.user_id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$cus = $stmt->fetch(PDO::FETCH_OBJ);
				if($cus){	
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("coupon_code", $register->coupon_code);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->execute();
					$offer = $stmt->fetch(PDO::FETCH_OBJ);
					if($offer){
						if(strtotime($offer->valid_from)<=strtotime($date1)){
							if($offer->is_experied=='0'){
								if($offer->used=='0'){
									if($offer->coupon_type=='1' && $offer->coupon_basedon!='3'){
										$counts=$offer->usage_count+1;
										if($offer->coupon_basedon=='4'){
											$valu = selectsinglevalue("select count(*) as retv from wy_ride where coupon_code='$register->coupon_code' and ride_status='4'");
											$valu1 = selectsinglevalue("select count(*) as retv from wy_taximoney_history where coupon_code='$register->coupon_code' group by customer_id");
											if($valu=='') $valu=0;
											if($valu1=='') $valu1=0;
											$valu = $valu+$valu1;
											if(intval($valu)<intval($offer->coupon_typevalue)){
												$sql2 = "update wy_taximoney set offer_id=:offer_id,amount=amount+:coupon_value,updated_at=:updated_at where customer_id=:user_id";
												$sql3 = "insert into wy_taximoney_history(customer_id,amount,based_on,type,created_at,updated_at,coupon_code) values(:user_id,:coupon_value,'2','1',:created_at,:updated_at,:coupon_code)";
												$stmt = $db->prepare($sql2);
												$stmt->bindParam("offer_id", $offer->offer_id);
												$stmt->bindParam("coupon_value", $offer->coupon_value);
												$stmt->bindParam("user_id", $register->user_id);
												$stmt->bindParam("updated_at", $date);
												$stmt->execute();
												$stmt = $db->prepare($sql3);
												$stmt->bindParam("coupon_value", $offer->coupon_value);
												$stmt->bindParam("user_id", $register->user_id);
												$stmt->bindParam("coupon_code", $register->coupon_code);
												$stmt->bindParam("updated_at", $date);
												$stmt->bindParam("created_at", $date);
												$stmt->execute();
												if(intval($offer->uc)==intval($counts)){
													$sql13 = "update wy_offernotification set used='1',usage_count=usage_count+1, updated_at=:updated_at where offer_id =:offer_id and user_id=:user_id";
												}else{
													$sql13 = "update wy_offernotification set usage_count=usage_count+1, updated_at=:updated_at where offer_id =:offer_id and user_id=:user_id";
												}
												$stmt = $db->prepare($sql13);
												$stmt->bindParam("offer_id", $offer->offer_id);
												$stmt->bindParam("user_id", $register->user_id);
												$stmt->bindParam("updated_at", $date);
												$stmt->execute();
												echo '{ "Result": "Success","Status":"Coupon applied successfully"}';
											}else{
												echo '{"Result":"Failed","Status":"Coupon code limit reached."}';
											}
										}else{
											$sql2 = "update wy_taximoney set offer_id=:offer_id,amount=amount+:coupon_value,updated_at=:updated_at where customer_id=:user_id";
											$sql3 = "insert into wy_taximoney_history(customer_id,amount,based_on,type,created_at,updated_at,coupon_code) values(:user_id,:coupon_value,'2','1',:created_at,:updated_at,:coupon_code)";
											$stmt = $db->prepare($sql2);
											$stmt->bindParam("offer_id", $offer->offer_id);
											$stmt->bindParam("coupon_value", $offer->coupon_value);
											$stmt->bindParam("user_id", $register->user_id);
											$stmt->bindParam("updated_at", $date);
											$stmt->execute();
											$stmt = $db->prepare($sql3);
											$stmt->bindParam("coupon_value", $offer->coupon_value);
											$stmt->bindParam("user_id", $register->user_id);
											$stmt->bindParam("coupon_code", $register->coupon_code);
											$stmt->bindParam("updated_at", $date);
											$stmt->bindParam("created_at", $date);
											$stmt->execute();
											if(intval($offer->uc)==intval($counts)){
												$sql13 = "update wy_offernotification set used='1',usage_count=usage_count+1, updated_at=:updated_at where offer_id =:offer_id and user_id=:user_id";
											}else{
												$sql13 = "update wy_offernotification set usage_count=usage_count+1, updated_at=:updated_at where offer_id =:offer_id and user_id=:user_id";
											}
											$stmt = $db->prepare($sql13);
											$stmt->bindParam("offer_id", $offer->offer_id);
											$stmt->bindParam("user_id", $register->user_id);
											$stmt->bindParam("updated_at", $date);
											$stmt->execute();
											echo '{ "Result": "Success","Status":"Coupon applied successfully"}';
										}
									}else{
										echo '{"Result":"Failed","Status":"Coupon cannot apply to wallet. You can use it on booking ride."}';
									}
								}else{
									echo '{"Result":"Failed","Status":"Coupon already used"}';
								}
							}else{
								echo '{"Result":"Failed","Status":"Coupon code expired"}';
							}
						}else{
							echo '{"Result":"Failed","Status":"Offer not yet started"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Invalid coupon code"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function chk_offers(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$time=date('H:i:s');
	$sql = "select id from wy_customer where id=:user_id";
	$sql1 = "select n.*,o.usage_count as uc,o.coupon_basedon,o.coupon_typevalue,o.valid_from from wy_offernotification n join wy_offers o on o.id=n.offer_id where n.coupon_code='$register->coupon_code' and n.user_id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid']; 
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$cus = $stmt->fetch(PDO::FETCH_OBJ);
				if($cus){	
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->execute();
					$offer = $stmt->fetch(PDO::FETCH_OBJ);
					if($offer){
						if(strtotime($offer->valid_from)<=strtotime($date1)){
							if($offer->is_experied=='0'){
								if($offer->used=='0'){
									if($offer->coupon_basedon=='3'){
										if($register->car_type==$offer->coupon_typevalue){
											echo '{ "Result": "Success","Status":"Coupon applied successfully"}';
										}else{
											echo '{"Result":"Failed","Status":"Coupon code cannot applied for this vehical type"}';
										}
									}elseif($offer->coupon_basedon=='4'){
										$valu = selectsinglevalue("select count(*) as retv from wy_ride where coupon_code='$register->coupon_code' and ride_status='4'");
										if($valu<$offer->coupon_typevalue){
											echo '{ "Result": "Success","Status":"Coupon applied successfully"}';
										}else{
											echo '{"Result":"Failed","Status":"Coupon code limit reached."}';
										}
									}else{
										echo '{ "Result": "Success","Status":"Coupon applied successfully"}';
									}
								}else{
									echo '{"Result":"Failed","Status":"Coupon already used"}';
								}
							}else{
								echo '{"Result":"Failed","Status":"Coupon code expired"}';
							}
						}else{
							echo '{"Result":"Failed","Status":"Offer not yet started"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Invalid coupon code"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function get_offers($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d");
	$time = date("H:i:s");
	$sql="select id from wy_customer where id=:user_id";
	$sql1="select o.*,n.used from wy_offernotification n join wy_offers o on o.id=n.offer_id where n.user_id=:user_id and o.is_experied=0 and n.used=0";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$rate = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($rate){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->execute();
					$offer = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($offer){
						echo '{ "Result": "Success","details":'.json_encode($offer).'}';
					}else{
						echo '{"Result":"Failed","Status":"Offers not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Customer not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function logout($driver_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d");
	$time = date("H:i:s");
	$sql="select id from wy_driver where id=:driver_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("driver_id", $driver_id);
				$stmt->execute();
				$rate = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($rate){
					$sql1 = "update wy_driver set status='0', online_status='0' where id=:driver_id";
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("driver_id", $driver_id);
					$stmt->execute();
					echo '{ "Result": "Success","Status":"Loggout successfully"}';
				}else{
					echo '{"Result":"Failed","Status":"Driver not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}


function contact_us(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select id,name,email from wy_customer where id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$cus = $stmt->fetch(PDO::FETCH_OBJ);
				if($cus){	
					$sql1="insert into wy_contactus(customer_id,message,created_at,updated_at) values(:customer_id,:message,:added_date,:updated_date)";
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("customer_id", $register->user_id);
					$stmt->bindParam("message", $register->message);
					$stmt->bindParam("added_date", $date);
					$stmt->bindParam("updated_date", $date);
					$stmt->execute();
					$subject="Customer Enquirey";
					$username = $cus->name;
					$content = "
								<b><h3>Customer Enquirey</h3></b><br>
								<table>
								<tr><td><b>Name</b></td><td>&nbsp;</td><td>$username</td></tr>
								<tr><td><b>Message</b></td><td>&nbsp;</td><td>$register->message</td></tr>
								</table><br>
								";
					$html='<html class="no-js" lang="en"> 
						<body>
						<div style="
							width: auto;
							border: 15px solid #efc01a;
							padding: 20px;
							margin: 10px;
						">
						 <div class="container">
							<div class="navbar-header">
								<div style="text-align: center;">
								<a href="" title="" style="margin-top:0px"><img src="http://www.mobycabs.com/images/logo-red.png"  class="img-responsive logo-new" ></a>
								</div>
								<?php
								
								?>
								<span style="float:right; text-align:right;">
									
								</span>
							   
								<div style="clear:both;" ></div>
								<hr width="100%" />
							</div>
							<div class="mail-container">
								<br />
								'.$content.' 
								<br />
							</div>
							<br />
							<hr width="100%" />
							<footer class="navbar-inverse">
								
							</footer>
						</div>
						</body>
						</html>';
					$mail       = new PHPMailer();
					$mail->IsSMTP(); // enable SMTP
					$mail->SMTPAuth = true; // authentication enabled
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
					$mail->Host = "smtp.gmail.com";
					$mail->Port = 465; // or 587
					$mail->IsHTML(true);
					$mail->Username = 'armortechmobile@gmail.com';
					$mail->Password = 'Codekhadi$34';
					$mail->ClearAllRecipients( ); // clear all
					//$uname = "support@forty-fivene.com";
					$mail->From = $cus->email; //Default From email same as smtp user
					$mail->FromName = $username;
					//$mail->SetFrom($cus->email, $username);
					$mail->AddAddress('armortechmobile@gmail.com', '');
					$mail->CharSet = 'UTF-8';
					$mail->Subject    = $subject;
					$mail->MsgHTML($html); 

					if(!$mail->Send()){
						echo '{"Result":"Failed","Status":"Message sent failed"}';
					}else{
						
						echo '{ "Result": "Success","Status":"Message sent successfully"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function ratecard_details($car_type,$booking_type){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d");
	$time = date("H:i:s");
	$sql="select min_fare_amount,ride_fare,distance_fare,waiting_charge from wy_faredetails where car_id=:car_type AND booking_type = :booking_type and fare_type='1' and status='1' order by fare_type desc";
	$sql1="select distinct(CONCAT(brand,' ',model)) as brand from wy_carlist where car_type='$car_type'";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("car_type", $car_type);
				$stmt->bindParam("booking_type", $booking_type);
				$stmt->execute();
				$rate = $stmt->fetch(PDO::FETCH_OBJ);
				if($rate){
					$ratedet[] = array(
						"label_key" => "Base fare",
						"value" => "₹ ".$rate->min_fare_amount,
						"notes" => "",
					);
					$ratedet[] = array(
						"label_key" => "Rate/km",
						"value" => "₹ ".$rate->ride_fare,
						"notes" => "",
					);
					$ratedet[] = array(
						"label_key" => "Ride time charges",
						"value" => "₹ ".$rate->distance_fare,
						"notes" => "",
					);
					$ratedet[] = array(
						"label_key" => "Waiting time charges",
						"value" => "₹ ".$rate->waiting_charge,
						"notes" => "",
					);
					$ratedet[] = array(
						"label_key" => "Peak time charges",
						"value" => "",
						"notes" => "Peak time charges may be applicable during high demand hours.",
					);
					$ratedet[] = array(
						"label_key" => "Special time charges",
						"value" => "",
						"notes" => "Special time charges may be applicable during special events.",
					);
					$sql4 = "select tax_name,percentage from wy_tax where status=1";
					$stmt = $db->prepare($sql4);
					$stmt->execute();
					$tax = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($tax){
						foreach($tax as $val){
							$ratedet[] = array(
								"label_key" => $val->tax_name,
								"value" => $val->percentage." %",
								"notes" => "",
							);
						}
					}
					echo '{ "Result": "Success","details":'.json_encode($ratedet).'}';
				}else{
					echo '{"Result":"Failed","Status":"Rate not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function getrate_reasons(){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select rating from wy_rating_reasons group by rating";

	function getratingres($rating){
		$sql="select * from wy_rating_reasons where rating=:rating and status=0";
		$db=getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("rating", $rating);
		$stmt->execute();
		$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
		return $cus;
	}
	try{
		$headers = $request->headers();
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$getArr = array();
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					foreach($cus as $val){
						$getlist[] = array(
							$val->rating => getratingres($val->rating),
						);
					}
					echo '{ "Result": "Success","details":'.json_encode($getlist).'}';
				}else{
					echo '{"Result":"Failed","Status":"Details not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function email_invoice(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select r.*,c.name,c.email from wy_ride r join wy_customer c on c.id=r.customer_id where r.id=:ride_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $register->ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){	
					///mail
					$subject="Bill";
					$username = $ride->name;
					$content = "
								<b><h3>Bill Details</h3></b><br>
								<table>
								<tr><td><b>BaseFare</b></td><td>&nbsp;</td><td>$ride->minimum_charge</td></tr>
								<tr><td><b>Distance Fare</b></td><td>&nbsp;</td><td>$ride->distance_amount</td></tr>
								<tr><td><b>Ride Time Fare</b></td><td>&nbsp;</td><td>$ride->ride_charge</td></tr>
								<tr><td><b>Waiting Charge</b></td><td>&nbsp;</td><td>$ride->waiting_charge</td></tr>
								<tr><td><b>Total Tax</b></td><td>&nbsp;</td><td>$ride->total_tax</td></tr>
								<tr><td><b>Additional Charge</b></td><td>&nbsp;</td><td>$ride->peak_amount</td></tr>
								<tr><td><b><h3>Total Amount</h3></b></td><td>&nbsp;</td><td>$ride->final_amount</td></tr>
								</table><br>
								<table cellpadding='15'>
								<tr><td><b>From:</b></td><td>&nbsp;</td><td> $ride->source_location </td></tr>
								<tr><td><b>To:</b></td><td>&nbsp;</td><td> $ride->destination_location</td></tr>
								<tr><td><b>Distance:</b></td><td>&nbsp;</td><td> $ride->distance </td></tr>
								<tr><td><b>Travel Time:</b></td><td>&nbsp;</td><td> $ride->rideing_time </td></tr>
								</table>
								";
					$sign = "Thank you<br>
							MobyCabs Team<br><br>
							© 2017 MobyCabs. All Rights Reserved.";
					$html='<html class="no-js" lang="en"> 
						<body>
						<div style="
							width: auto;
							border: 15px solid #efc01a;
							padding: 20px;
							margin: 10px;
						">
						 <div class="container">
							<div class="navbar-header">
								<div style="text-align: center;">
								<a href="" title="" style="margin-top:0px"><img src="http://mobycabs.com/moby_admin/api/upload/wry_logo.png"  class="img-responsive logo-new" ></a>
								</div>
								<?php
								
								?>
								<span style="float:right; text-align:right;">
									
								</span>
							   
								<div style="clear:both;" ></div>
								<hr width="100%" />
							</div>
							<div class="mail-container">
								<br />
								<b>Hi '.$username.' </b>
								<br />
								<br />
								<b> Thanks for riding with Us</b>
								<br />
								'.$content.' 
								<br />
							</div>
							<br />
							<hr width="100%" />
							<footer class="navbar-inverse">
								<div class="row">
								'.$sign.'
									<div class="collapse navbar-collapse"></div>
								</div>
							</footer>
						</div>
						</body>
						</html>';
					$mail       = new PHPMailer();
					$mail->IsSMTP(); // enable SMTP
					$mail->SMTPAuth = true; // authentication enabled
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
					$mail->Host = "smtp.gmail.com";
					$mail->Port = 465; // or 587
					$mail->IsHTML(true);
					$mail->Username = 'armortechmobile@gmail.com';
					$mail->Password = 'Codekhadi$34';
					//$uname = "support@forty-fivene.com";
					$mail->From = $mail->Username; //Default From email same as smtp user
					$mail->FromName = "MobyCabs";
					$mail->AddAddress($register->email, '');
					$mail->CharSet = 'UTF-8';
					$mail->Subject    = $subject;
					$mail->MsgHTML($html); 

					if(!$mail->Send()){
						echo '{"Result":"Failed","Status":"Invoice sent failed"}';
					}else{
						
						echo '{ "Result": "Success","Status":"Invoice sent successfully"}';
					}
					/* $mail->CharSet = 'UTF-8';
					$mail->IsSMTP(); 
					$mail->Host = "smtp.gmail.com";
					$mail->SMTPAuth = true; 
					$mail->Username = "armortechmobile@gmail.com"; // SMTP username
					$mail->Password = "techteam34"; // SMTP password
					$mail->SMTPSecure = "tls";
					$mail->From = $mail->Username;
					$mail->FromName = "TaxiTaxi";
					//$mail->AddReplyTo("armormobiletesting@gmail.com","");
					$mail->AddAddress($register->email, '');
					$mail->WordWrap = 100;
					$mail->IsHTML(true);
					$mail->Subject    = $subject;
					$mail->MsgHTML($html); */
				
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function driver_rating($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$datee = date("Y-m-d");
	$sql="select id from wy_driver where id='$user_id'";
	$sql1="select if(ROUND((SUM( rating ) / COUNT( rating ) ),1),ROUND((SUM( rating ) / COUNT( rating ) ),1),0) as rating,(select count(id) from wy_ridedetails where driver_id=:user_id and accept_status='1') as accepted,(select count(id) from wy_ridedetails where driver_id=:user_id and accept_status='1' and ride_status='4' and date(accept_time)=:datee) as today_ride,(select count(id) from wy_ridedetails where driver_id=:user_id and accept_status='3') as cancel from wy_customerrate where driver_id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$getArr = array();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->bindParam("datee", $datee);
					$stmt->execute();
					$details = $stmt->fetch(PDO::FETCH_OBJ);
					if($details){
						//$total_rating = round(($details->sum/$details->totcount),2);
						echo '{ "Result": "Success","details":'.json_encode($details).'}';
					}else{
						echo '{"Result":"Failed","Status":"Details not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Driver not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function driver_update(){
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql="select id from wy_driver where id=:driver_id";
	$sql2="select d.*,ct.car_type,ct.car_board from wy_driver d join wy_assign_taxi a on a.driver_id=d.id join wy_carlist c on a.car_num=c.id join wy_cartype ct on c.car_type=ct.id where d.id=:driver_id and a.status='1' ";
	$sql1="update wy_driver set firstname=:firstname,lastname=:lastname,mobile=:mobile,updated_at=:updated_date where id=:driver_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("driver_id", $register->driver_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("driver_id", $register->driver_id);
					$stmt->bindParam("firstname", $register->firstname);
					$stmt->bindParam("lastname", $register->lastname);
					$stmt->bindParam("mobile", $register->mobile);
					$stmt->bindParam("updated_date", $date);
					$stmt->execute();
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("driver_id", $register->driver_id);
					$stmt->execute();
					$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
					echo '{ "Result": "Success","Status":"Details updated successfully","details":'.json_encode($cus).'}';
				}else{
					echo '{"Result":"Failed","Status":"Driver not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function ridehistory_driver($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select id from wy_driver where id=:user_id";
	$sql1="select r.id,r.booking_type,r.source_location,r.created_at,r.source_lat,r.source_lng,r.fare_id,r.reference_id,r.destination_location,r.destination_lat,r.destination_lng,rd.accept_status,rd.start_ride_time,rd.end_ride_time,rd.ride_status,r.ride_type,r.schedule_date,r.schedule_time,r.fare_type,r.waiting_time,r.rideing_time,r.distance_amount,r.minimum_charge,r.ride_charge,r.waiting_charge,r.distance,r.final_amount,r.offer_amount,r.padi_by,r.paid_cash,r.paid_taximoney,r.company_share,r.driver_share,r.peak_percent,r.peak_amount,ct.car_type,ct.car_board,r.car_type as cartype_id,ct.yellow_caricon,round((r.final_amount-r.total_tax)) as amount_exclude_tax,r.total_tax
			from wy_ride r join wy_ridedetails rd on r.id=rd.ride_id 
			join wy_cartype ct on ct.id=r.car_type
			where rd.driver_id=:user_id and rd.accept_status!=2 order by r.id desc";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$getArr = array();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->execute();
					$details = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($details){
						foreach($details as $val){
							$fareinfo=array();
							$taxinfo=array();
							$shareinfo=array();
							/* $sql4 = "select * from wy_tax";
							$stmt = $db->query($sql4);
							$tax = $stmt->fetchAll(PDO::FETCH_OBJ); */
							if($val->fare_id!=0){
								$sql3 = "select min_km from wy_faredetails where car_id=:cartype_id and status=1 and booking_type=:booking_type and fare_type=1"; 
								$stmt = $db->prepare($sql3);
								$stmt->bindParam("cartype_id", $val->cartype_id);
								$stmt->bindParam("booking_type", $val->booking_type);
								$stmt->execute();
								$card = $stmt->fetch(PDO::FETCH_OBJ);
									
								$fareinfo[] = array(
									"title" => "First $card->min_km km",
									"value" => "₹ ".$val->minimum_charge,
								);
								if($val->distance > $card->min_km){
									$balkm = $val->distance - $card->min_km;
									$fareinfo[] = array(
										"title" => "Rate for $balkm km",
										"value" => "₹ ".$val->distance_amount,
									);
								}
								$fareinfo[] = array(
									"title" => "Ridetime charge for $val->rideing_time",
									"value" => "₹ ".$val->ride_charge,
								);
								$fareinfo[] = array(
									"title" => "Waiting charge for $val->waiting_time",
									"value" => "₹ ".$val->waiting_charge,
								);
								/* $fareinfo[] = array(
									"title" => "Total Tax",
									"value" => "₹ ".$val->total_tax,
								); */
								// $taxinfo[] = array(
								// 	"title" => "Offer Amount",
								// 	"value" => "₹ ".$val->offer_amount,
								// );
								/* $shareinfo[] = array(
									"title" => "Company's Share",
									"value" => "₹ ".$val->company_share,
								); */
								$shareinfo[] = array(
									"title" => "Driver's Share",
									"value" => "₹ ".$val->driver_share,
								);
								if($val->peak_percent!=0){
									if($val->fare_type==4) $name = "Peak";
									else $name = "Special";
									$fareinfo[] = array(
										"title" => $name." time charges",
										"value" => "₹ ".$val->peak_amount,
									);
								}
								//foreach($tax as $vale){
									$taxinfo[] = array(
										"title" => "Taxes",
										"value" => "₹ ".$val->total_tax,
									);
								//}
							}else{
								$fareinfo[] = array(
									"title" => "First 0 km",
									"value" => "₹ 0",
								);
								$fareinfo[] = array(
									"title" => "Ridetime charge for 0 minute",
									"value" => "₹ 0",
								);
								$fareinfo[] = array(
									"title" => "Waiting charge for 0 minute",
									"value" => "₹ 0",
								);
								$taxinfo[] = array(
									"title" => "Offer Amount",
									"value" => "₹ 0",
								);
								/* $shareinfo[] = array(
									"title" => "Company's Share",
									"value" => "₹ 0",
								); */
								$shareinfo[] = array(
									"title" => "Driver's Share",
									"value" => "₹ 0",
								);
								//foreach($tax as $vale){
									$taxinfo[] = array(
										"title" => "Taxes",
										"value" => "₹ 0",
									);
								//}
							}
							
							$getArr[] = array(
								"id" => $val->id,
								"reference_id" => $val->reference_id,
								"source_location" => $val->source_location,
								"source_lat" => $val->source_lat,
								"source_lng" => $val->source_lng,
								"destination_location" => $val->destination_location,
								"destination_lat" => $val->destination_lat,
								"destination_lng" => $val->destination_lng,
								"accept_status" => $val->accept_status,
								"start_ride_time" => $val->start_ride_time,
								"end_ride_time" => $val->end_ride_time,
								"ride_status" => $val->ride_status,
								"total_amount" => $val->final_amount,
								"paid_cash" => $val->paid_cash,
								"paid_taximoney" => $val->paid_taximoney,
								"car_type" => $val->car_type,
								"car_board" => $val->car_board,
								"grey_caricon" => $val->yellow_caricon,
								"booking_time" => $val->created_at,
								"bill_details" => $fareinfo,
								"share_details" => $shareinfo,
								"tax_details" => $taxinfo,
							);
						}
						echo '{ "Result": "Success","details":'.json_encode($getArr).'}';
					}else{
						echo '{"Result":"Failed","Status":"Details not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Driver not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function ridehistory_customer($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select id from wy_customer where id=:user_id";
	$sql1="select r.id,r.ride_status as scheduled_status,r.source_location,r.created_at,r.source_lat,r.source_lng,r.reference_id,r.destination_location,r.destination_lat,r.destination_lng,r.ride_type,r.schedule_date,r.schedule_time,r.rideing_time,r.distance,r.final_amount,r.padi_by,r.paid_cash,r.paid_taximoney,ct.car_type,ct.car_board,ct.grey_caricon,round((r.final_amount-r.total_tax)) as amount_exclude_tax,r.total_tax
			from wy_ride r join wy_cartype ct on ct.id=r.car_type
			where  r.customer_id=:user_id order by r.id desc";
	function getrating($ride_id){
		$sql = "select rating from wy_customerrate where ride_id=:ride_id";
		$db=getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("ride_id", $ride_id);
		$stmt->execute();
		$details = $stmt->fetch(PDO::FETCH_OBJ);
		if($details){
			$getrate = $details->rating;
			$israte = "Yes";
		}else{
			$getrate='';
			$israte = "No";
		}
		return array($getrate,$israte);
	}
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$getArr = array();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				$fail =1;
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->execute();
					$details = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($details){
						foreach($details as $val){
							$fareinfo=array();
							$fareinfo[] = array(
								"title" => "Total Fare",
								"value" => "₹ ".$val->amount_exclude_tax,
							);
							$fareinfo[] = array(
								"title" => "Taxes",
								"value" => "₹ ".$val->total_tax,
							);
							$fareinfo[] = array(
								"title" => "Total Bill",
								"value" => "₹ ".$val->final_amount,
							);
							if($val->ride_type==1){
								$sql2 = "select rd.accept_status,rd.start_ride_time,rd.end_ride_time,rd.ride_status,d.profile_photo,d.firstname,d.lastname,ct.car_type,ct.car_board,ct.grey_caricon
										from wy_ridedetails rd join wy_driver d on d.id=rd.driver_id join wy_assign_taxi a on d.id=a.driver_id
										join wy_carlist c on c.id=a.car_num join wy_cartype ct on ct.id=c.car_type 
										where (rd.accept_status='1' || rd.accept_status='3') and rd.ride_id=:id";
								$stmt = $db->prepare($sql2);
								$stmt->bindParam("id", $val->id);
								$stmt->execute();
								$det = $stmt->fetch(PDO::FETCH_OBJ);
								if($det){
									$fail =0;
									list($a, $b) = getrating($val->id);
									$getArr[] = array(
										"id" => $val->id,
										"reference_id" => $val->reference_id,
										"source_location" => $val->source_location,
										"source_lat" => $val->source_lat,
										"source_lng" => $val->source_lng,
										"destination_location" => $val->destination_location,
										"destination_lat" => $val->destination_lat,
										"destination_lng" => $val->destination_lng,
										"start_ride_time" => $det->start_ride_time,
										"end_ride_time" => $det->end_ride_time,
										"ride_status" => $det->ride_status,
										"accept_status" => $det->accept_status,
										"scheduled_status" => $val->scheduled_status,
										"ride_type" => $val->ride_type,
										"schedule_date" => $val->schedule_date,
										"schedule_time" => $val->schedule_time,
										"rideing_time" => $val->rideing_time,
										"distance" => $val->distance,
										"total_amount" => $val->final_amount,
										"paid_cash" => $val->paid_cash,
										"paid_taximoney" => $val->paid_taximoney,
										"name" => $det->firstname." ".$det->lastname,
										"profile_photo" => $det->profile_photo,
										"car_type" => $det->car_type,
										"car_board" => $det->car_board,
										"grey_caricon" => $det->grey_caricon,
										"rating" => $a,
										"is_Rate" => $b,
										"booking_time" => $val->created_at,
										"bill_details" => $fareinfo,
									);
								}
							}else{
								$sql2 = "select rd.accept_status,rd.start_ride_time,rd.end_ride_time,rd.ride_status,d.profile_photo,d.firstname,d.lastname,ct.car_type,ct.car_board,ct.grey_caricon
										from wy_ridedetails rd join wy_driver d on d.id=rd.driver_id join wy_assign_taxi a on d.id=a.driver_id
										join wy_carlist c on c.id=a.car_num join wy_cartype ct on ct.id=c.car_type
										where (rd.accept_status='1' || rd.accept_status='3') and rd.ride_id=:id";
								$stmt = $db->prepare($sql2);
								$stmt->bindParam("id", $val->id);
								$stmt->execute();
								$det = $stmt->fetch(PDO::FETCH_OBJ);
								if($det){
									$fail =0;
									list($a, $b) = getrating($val->id);
									$getArr[] = array(
										"id" => $val->id,
										"reference_id" => $val->reference_id,
										"source_location" => $val->source_location,
										"source_lat" => $val->source_lat,
										"source_lng" => $val->source_lng,
										"destination_location" => $val->destination_location,
										"destination_lat" => $val->destination_lat,
										"destination_lng" => $val->destination_lng,
										"start_ride_time" => $det->start_ride_time,
										"end_ride_time" => $det->end_ride_time,
										"ride_status" => $det->ride_status,
										"accept_status" => $det->accept_status,
										"scheduled_status" => $val->scheduled_status,
										"ride_type" => $val->ride_type,
										"schedule_date" => $val->schedule_date,
										"schedule_time" => $val->schedule_time,
										"rideing_time" => $val->rideing_time,
										"distance" => $val->distance,
										"total_amount" => $val->final_amount,
										"paid_cash" => $val->paid_cash,
										"paid_taximoney" => $val->paid_taximoney,
										"name" => $det->firstname." ".$det->lastname,
										"profile_photo" => $det->profile_photo,
										"car_type" => $det->car_type,
										"car_board" => $det->car_board,
										"grey_caricon" => $det->grey_caricon,
										"rating" => $a,
										"is_Rate" => $b,
										"booking_time" => $val->created_at,
										"bill_details" => $fareinfo,
									);
								}else{
									$fail =0;
									list($a, $b) = getrating($val->id);
									$getArr[] = array(
										"id" => $val->id,
										"reference_id" => $val->reference_id,
										"source_location" => $val->source_location,
										"source_lat" => $val->source_lat,
										"source_lng" => $val->source_lng,
										"destination_location" => $val->destination_location,
										"destination_lat" => $val->destination_lat,
										"destination_lng" => $val->destination_lng,
										"start_ride_time" => '',
										"end_ride_time" => '',
										"ride_status" => '',
										"accept_status" => '',
										"scheduled_status" => $val->scheduled_status,
										"ride_type" => $val->ride_type,
										"schedule_date" => $val->schedule_date,
										"schedule_time" => $val->schedule_time,
										"rideing_time" => $val->rideing_time,
										"distance" => $val->distance,
										"total_amount" => $val->final_amount,
										"paid_cash" => $val->paid_cash,
										"paid_taximoney" => $val->paid_taximoney,
										"name" => '',
										"profile_photo" => '',
										"car_type" => $val->car_type,
										"car_board" => $val->car_board,
										"grey_caricon" => $val->grey_caricon,
										"rating" => $a,
										"is_Rate" => $b,
										"booking_time" => $val->created_at,
										"bill_details" => $fareinfo,
									);
								}
							}
						}
						if($fail ==0)
							echo '{ "Result": "Success","details":'.json_encode($getArr).'}';
					}else{
						$fail =1;
					}
					if($fail ==1)
						echo '{"Result":"Failed","Status":"Details not found"}';
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function resend_otp_update(){
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql="select id from wy_customer where id=:user_id";
	$sql1="update wy_customer set OTP=:OTP,updated_at=:updated_date where id=:user_id";
	$verification_code=rand(1111, 9999);
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("OTP", $verification_code);
					$stmt->bindParam("updated_date", $date);
					$stmt->execute();
					
					$message   =  "Verification code from MobyCabs: $verification_code";
					$message = urlencode($message);
					$mobile   =  $register->mobile;
					$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);      
					curl_close($ch);
						
					echo '{ "Result": "Success","Status":"OTP sent successfully"}';
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function updatemobile(){
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql="select * from wy_customer where id=:user_id";
	$sql1="update wy_customer set mobile=:mobile,OTP='',updated_at=:updated_date where id=:user_id";
	$sql2= "select otp_time from wy_customer where id=:user_id and OTP=:OTP";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("OTP", $register->OTP);
					$stmt->execute();
					$contact = $stmt->fetch(PDO::FETCH_OBJ);
					if($contact){
						$otptime = $contact->otp_time;
						$otpexcesstime = date("Y-m-d H:i:s",strtotime("+15 minute".$otptime));
						if(strtotime($otpexcesstime) >= strtotime($date)){
							$stmt = $db->prepare($sql1);
							$stmt->bindParam("user_id", $register->user_id);
							$stmt->bindParam("mobile", $register->mobile);
							$stmt->bindParam("updated_date", $date);
							$stmt->execute();
							
							$stmt = $db->prepare($sql);
							$stmt->bindParam("user_id", $register->user_id);
							$stmt->execute();
							$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
							echo '{ "Result": "Success","Status":"Mobile number updated successfully","details":'.json_encode($cus).'}';
						}else{
							echo '{"Result":"Failed","Status":"OTP Expired"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Invalid OTP"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function updateprofile(){
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql="select * from wy_customer where id=:user_id";
	$sql1="update wy_customer set name=:name,email=:email,updated_at=:updated_date where id=:user_id";
	$sql2= "select * from wy_customer where id=:user_id and mobile=:mobile";
	$sql4= "select * from wy_customer where mobile=:mobile and id!=:user_id";
	$sql5= "select * from wy_customer where email=:email and id!=:user_id";
	$verification_code=rand(1111, 9999);
	try{
		$db=getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("user_id", $register->user_id);
		$stmt->execute();
		$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($cus){
			$stmt = $db->prepare($sql4);
			$stmt->bindParam("mobile", $register->mobile);
			$stmt->bindParam("user_id", $register->user_id);
			$stmt->execute();
			$mob = $stmt->fetchAll(PDO::FETCH_OBJ);
			if(!$mob){
				$stmt = $db->prepare($sql5);
				$stmt->bindParam("email", $register->email);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$emailid = $stmt->fetchAll(PDO::FETCH_OBJ);
				if(!$emailid){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("name", $register->name);
					$stmt->bindParam("email", $register->email);
					$stmt->bindParam("updated_date", $date);
					$stmt->execute();
					
					$stmt = $db->prepare($sql);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->execute();
					$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
			
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("mobile", $register->mobile);
					$stmt->execute();
					$contact = $stmt->fetchAll(PDO::FETCH_OBJ);
					if(!$contact){
						$sql3 = "update wy_customer set OTP=:verification_code,otp_time=:otp_time,updated_at=:updated_date where id=:user_id";
						$stmt = $db->prepare($sql3);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->bindParam("verification_code", $verification_code);
						$stmt->bindParam("otp_time", $date);
						$stmt->bindParam("updated_date", $date);
						$stmt->execute();
						
						$stmt = $db->prepare($sql);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->execute();
						$cust = $stmt->fetchAll(PDO::FETCH_OBJ);
						
						$message   =  "Verification code from MobyCabs: $verification_code";
						$message = urlencode($message);
						$mobile   =  $register->mobile;
						$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);      
						curl_close($ch);

						echo '{ "Result": "Success","Status":"OTP has been sent"}';
					}else{
						echo '{"Result":"Success","Status":"Profile updated successfully","details":'.json_encode($cus).'}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Emailid already registered"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Mobile number already registered"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"User not found"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function changepassword(){
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql="select id from wy_customer where id=:user_id";
	$sql1="update wy_customer set password=:newpassword,updated_at=:updated_date where id=:user_id";
	$key = hash('sha256', 'wrydes');
	$iv = substr(hash('sha256', 'dispatch'), 0, 16);
	$output = openssl_encrypt($register->oldpassword, "AES-256-CBC", $key, 0, $iv);
	$password = base64_encode($output);
	$sql2= "select id from wy_customer where id=:user_id and password=:oldpassword";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$cus = $stmt->fetch(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("oldpassword", $password);
					$stmt->execute();
					$contact = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($contact){
						$stmt = $db->prepare($sql1);
						$stmt->bindParam("user_id", $register->user_id);
						$key = hash('sha256', 'wrydes');
						$iv = substr(hash('sha256', 'dispatch'), 0, 16);
						$output = openssl_encrypt($register->newpassword, "AES-256-CBC", $key, 0, $iv);
						$newpassword = base64_encode($output);
						$stmt->bindParam("newpassword", $newpassword);
						$stmt->bindParam("updated_date", $date);
						$stmt->execute();

						echo '{ "Result": "Success","Status":"Password changed successfully"}';
					}else{
						echo '{"Result":"Failed","Status":"Old password does not match"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}


function send_alert(){
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql="select id,name from wy_customer where id=:user_id";
	$sql3="select r.driver_id,d.firstname,d.lastname,d.mobile,c.car_no as reg_no,c.brand,c.model from wy_ridedetails r join wy_driver d on r.driver_id=d.id join wy_assign_taxi a on a.driver_id=d.id join wy_carlist c on c.id=a.car_num where r.ride_id=:ride_id and a.status='1' and r.accept_status=1 and r.ride_status=3";
	$sql1="select mobile from wy_emergencycontacts where customer_id=:user_id";
	$sql2="insert into wy_alert(customer_id,ride_id,driver_id,alert_location,lat,lng,created_at,updated_at) values(:user_id,:ride_id,:driver_id,:alert_location,:lat,:lng,:added_date,:updated_date)";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$cus = $stmt->fetch(PDO::FETCH_OBJ);
				if($cus){
					//
					$stmt = $db->prepare($sql3);
					$stmt->bindParam("ride_id", $register->ride_id);
					$stmt->execute();
					$dridata = $stmt->fetch(PDO::FETCH_OBJ);
					//
					if($dridata){
						$stmt = $db->prepare($sql1);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->execute();
						$contact = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($contact){
							$stmt = $db->prepare($sql2);
							$stmt->bindParam("user_id", $register->user_id);
							$stmt->bindParam("ride_id", $register->ride_id);
							$stmt->bindParam("alert_location", $register->alert_location);
							$stmt->bindParam("lat", $register->lat);
							$stmt->bindParam("driver_id", $dridata->driver_id);
							$stmt->bindParam("lng", $register->lng);
							$stmt->bindParam("added_date", $date);
							$stmt->bindParam("updated_date", $date);
							$stmt->execute();
							$stmt = $db->prepare($sql3);
							$stmt->bindParam("ride_id", $register->ride_id);
							$stmt->execute();
							$alertmsg = $stmt->fetch(PDO::FETCH_OBJ);
							$message = "$cus->name has sent an emergency alert from $register->alert_location. Vehicle Info : $alertmsg->reg_no, $alertmsg->brand $alertmsg->model,$alertmsg->firstname $alertmsg->lastname, $alertmsg->mobile";
							$message = urlencode($message);
							$mobileno='';
							foreach($contact as $val){
								$mobile = preg_replace('/[^A-Za-z0-9]/', "", $val->mobile);
								$mobileno .= $mobile.',';
							}
							$mobile=rtrim($mobileno, ',');
							$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$output = curl_exec($ch);      
							curl_close($ch);

							echo '{ "Result": "Success","Status":"Alert Sent to your emergency contacts"}';
						}else{
							echo '{"Result":"Failed","Status":"Configure Emergency Contacts"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Ride not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}


function get_emergency($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select id from wy_customer where id=:user_id";
	$sql1="select * from wy_emergencycontacts where customer_id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->execute();
					$contact = $stmt->fetchAll(PDO::FETCH_OBJ);
					echo '{ "Result": "Success","details":'.json_encode($contact).'}';
					
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}


function delete_emergency(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select id from wy_emergencycontacts where id=:contactid";
	$sql2 = "delete from wy_emergencycontacts where id=:contactid";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("contactid",$register->contactid);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){			
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("contactid", $register->contactid);
					$stmt->execute();
					echo '{ "Result": "Success","Status":"Contact deleted successfully"}';
				
				}else{
					echo '{"Result":"Failed","Status":"Contact not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function emergency_status($contactid,$status){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select id from wy_emergencycontacts where id=:contactid";
	$sql1="update wy_emergencycontacts set is_showride=:status where id=:contactid";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("contactid", $contactid);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("contactid", $contactid);
					$stmt->bindParam("status", $status);
					$stmt->execute();
					echo '{ "Result": "Success","Status":"Status updated"}';
					
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}


function add_emergency(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select id from wy_customer where id=:customer_id";
	$sql2 = "insert into wy_emergencycontacts(customer_id,name,mobile,created_at,updated_at) values(:customer_id,:name,:mobile,:added_date,:updated_date)";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("customer_id", $register->customer_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){			
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("customer_id", $register->customer_id);
					$stmt->bindParam("name", $register->name);
					$stmt->bindParam("mobile", $register->mobile);
					$stmt->bindParam("added_date", $date);
					$stmt->bindParam("updated_date", $date);
					$stmt->execute();
					$id = $db->lastInsertId();
					$sql3 = "select * from wy_emergencycontacts where id=:id";
					$stmt = $db->prepare($sql3);
					$stmt->bindParam("id", $id);
					$stmt->execute();
					$cont = $stmt->fetch(PDO::FETCH_OBJ);
					echo '{ "Result": "Success","Status":"Contact added successfully","details":'.json_encode($cont).'}';
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function get_taximoney($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select id from wy_customer where id=:user_id";
	$sql1="select * from wy_taximoney where customer_id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->execute();
					$money = $stmt->fetchAll(PDO::FETCH_OBJ);
					echo '{ "Result": "Success","details":'.json_encode($money).'}';
					
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function user_logout($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select id from wy_customer where id=:user_id";
	$sql1="update wy_customer set login_status='0',device_token='' where id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$cus = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($cus){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->execute();
					echo '{ "Result": "Success","Status":"User logged out"}';
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function add_taximoney(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select id from wy_customer where id=:user_id";
	$sql2 = "update wy_taximoney set amount=amount+:amount,updated_at=:updated_at where customer_id=:user_id";
	$sql3 = "insert into wy_taximoney_history(customer_id,amount,based_on,type,created_at,updated_at) values(:user_id,:amount,'1','1',:created_at,:updated_at)";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){			
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("amount", $register->amount);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("updated_at", $date);
					$stmt->execute();
					$stmt = $db->prepare($sql3);
					$stmt->bindParam("amount", $register->amount);
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("created_at", $date);
					$stmt->bindParam("updated_at", $date);
					$stmt->execute();
					echo '{ "Result": "Success","Status":"Money added successfully"}';
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function userrating(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select id from wy_ride where id=:ride_id";
	$sql1 = "select driver_id from wy_ridedetails where ride_id=:ride_id and ride_status=4";
	$sql2 = "insert into wy_customerrate(customer_id,driver_id,ride_id,rating,reason_id,comments,created_at,updated_at) values(:customer_id,:driver_id,:ride_id,:rating,:reason_id,:comments,:added_date,:updated_date)";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $register->ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){			
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("ride_id", $register->ride_id);
					$stmt->execute();
					$driver = $stmt->fetch(PDO::FETCH_OBJ);
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("customer_id", $register->customer_id);
					$stmt->bindParam("driver_id", $driver->driver_id);
					$stmt->bindParam("ride_id", $register->ride_id);
					$stmt->bindParam("rating", $register->rating);
					$stmt->bindParam("reason_id", $register->reason_id);
					$stmt->bindParam("comments", $register->comments);
					$stmt->bindParam("added_date", $date);
					$stmt->bindParam("updated_date", $date);
					$stmt->execute();
					echo '{ "Result": "Success","Status":"Rating success"}';
					/// firebase customer			
					$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
					$result = curl_exec($ch); 
					curl_close($ch); 
					///firebase
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function driverrating(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select r.*,c.name,c.email,c.fb_id from wy_ride r join wy_customer c on c.id=r.customer_id where r.id=:ride_id and r.ride_status=4";
	$sql2 = "insert into wy_driverrate(customer_id,driver_id,ride_id,rating,created_at,updated_at) values(:customer_id,:driver_id,:ride_id,:rating,:added_date,:updated_date)";
	$sql3 = "update wy_ride set padi_by=:padi_by,paid_cash=:paid_cash,paid_taximoney=:paid_taximoney,updated_at='$date' where id='$register->ride_id'";
	$sql1="insert into wy_ride_location(ride_id,lat,lng,datetime,created_at,updated_at) values(:ride_id,:lat,:lng,:datetime,:created_at,:updated_at)";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db = getConnection();
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $register->ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){	
					$stmt = $db->prepare($sql3);
					$stmt->bindParam("padi_by", $register->paid_by);
					$stmt->bindParam("paid_cash", $register->paid_cash);
					$stmt->bindParam("paid_taximoney", $register->paid_taximoney);
					$stmt->execute();
					$sql4 = "update wy_taximoney set amount=amount-:paid_taximoney,updated_at=:updated_at where customer_id=:customer_id";
					$stmt = $db->prepare($sql4);
					$stmt->bindParam("paid_taximoney", $register->paid_taximoney);
					$stmt->bindParam("customer_id", $ride->customer_id);
					$stmt->bindParam("updated_at", $date);
					$stmt->execute();
					$sql4 = "insert into wy_taximoney_history(customer_id,amount,type,created_at,updated_at) values(:customer_id,:paid_taximoney,'2',:created_at,:updated_at)";
					$stmt = $db->prepare($sql4);
					$stmt->bindParam("paid_taximoney", $register->paid_taximoney);
					$stmt->bindParam("customer_id", $ride->customer_id);
					$stmt->bindParam("created_at", $date);
					$stmt->bindParam("updated_at", $date);
					$stmt->execute();
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("customer_id", $ride->customer_id);
					$stmt->bindParam("driver_id", $register->driver_id);
					$stmt->bindParam("ride_id", $register->ride_id);
					$stmt->bindParam("rating", $register->rating);
					$stmt->bindParam("added_date", $date);
					$stmt->bindParam("updated_date", $date);
					$stmt->execute();
					
					///mail
					$subject="Bill";
					$username = $ride->name;
					$content = "
								<b><h3>Bill Details</h3></b><br>
								<table>
								<tr><td><b>BaseFare</b></td><td>&nbsp;</td><td>$ride->minimum_charge</td></tr>
								<tr><td><b>Distance Fare</b></td><td>&nbsp;</td><td>$ride->distance_amount</td></tr>
								<tr><td><b>Ride Time Fare</b></td><td>&nbsp;</td><td>$ride->ride_charge</td></tr>
								<tr><td><b>Waiting Charge</b></td><td>&nbsp;</td><td>$ride->waiting_charge</td></tr>
								<tr><td><b>Total Tax</b></td><td>&nbsp;</td><td>$ride->total_tax</td></tr>
								<tr><td><b>Additional Charge</b></td><td>&nbsp;</td><td>$ride->peak_amount</td></tr>
								<tr><td><b><h3>Total Amount</h3></b></td><td>&nbsp;</td><td>$ride->total_amount</td></tr>
								</table><br>
								<table cellpadding='15'>
								<tr><td><b>From:</b></td><td>&nbsp;</td><td> $ride->source_location </td></tr>
								<tr><td><b>To:</b></td><td>&nbsp;</td><td> $ride->destination_location</td></tr>
								<tr><td><b>Distance:</b></td><td>&nbsp;</td><td> $ride->distance </td></tr>
								<tr><td><b>Travel Time:</b></td><td>&nbsp;</td><td> $ride->rideing_time </td></tr>
								</table>
								";
					$sign = "Thank you<br>
							MobyCabs Team<br><br>
							© 2017 MobyCabs. All right reserved.";
					$html='<html class="no-js" lang="en"> 
						<body>
						<div style="
							width: auto;
							border: 15px solid #efc01a;
							padding: 20px;
							margin: 10px;
						">
						 <div class="container">
							<div class="navbar-header">
								<div style="text-align: center;">
								<a href="" title="" style="margin-top:0px"><img src="http://mobycabs.com/moby_admin/api/upload/wry_logo.png"  class="img-responsive logo-new" ></a>
								</div>
								<?php
								
								?>
								<span style="float:right; text-align:right;">
									
								</span>
							   
								<div style="clear:both;" ></div>
								<hr width="100%" />
							</div>
							<div class="mail-container">
								<br />
								<b> Hi '.$username.' </b>
								<br />
								<br />
								<b> Thanks for riding with Us </b>
								<br />
								'.$content.' 
								<br />
							</div>
							<br />
							<hr width="100%" />
							<footer class="navbar-inverse">
								<div class="row">
								'.$sign.'
									<div class="collapse navbar-collapse"></div>
								</div>
							</footer>
						</div>
						</body>
						</html>';
					$mail       = new PHPMailer();
					$mail->IsSMTP(); // enable SMTP
					$mail->SMTPAuth = true; // authentication enabled
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
					$mail->Host = "smtp.gmail.com";
					$mail->Port = 465; // or 587
					$mail->IsHTML(true);
					$mail->Username = 'armortechmobile@gmail.com';
					$mail->Password = 'Codekhadi$34';
					$mail->From = $mail->Username; //Default From email same as smtp user
					$mail->FromName = "MobyCabs";
					$mail->AddAddress($ride->email, '');
					$mail->CharSet = 'UTF-8';
					$mail->Subject    = $subject;
					$mail->MsgHTML($html); 
					$mail->Send();
					echo '{ "Result": "Success","Status":"Rating success"}';
					
					for($i=0;$i<count($register->details);$i++){
						$stmt = $db->prepare($sql1); 
						$stmt->bindParam("ride_id", $register->ride_id);
						$stmt->bindParam("lat", $register->details[$i]->lat);
						$stmt->bindParam("lng", $register->details[$i]->lng);
						$stmt->bindParam("datetime", $register->details[$i]->datetime);
						$stmt->bindParam("created_at", $date);
						$stmt->bindParam("updated_at", $date);
						$stmt->execute();
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function getactiveride($user_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select profile_status from wy_customer where id=:user_id";
	$sql1="select c.car_board,c.car_type,r.id from wy_ride r join wy_ridedetails rd on r.id=rd.ride_id join wy_cartype c on c.id=r.car_type where r.customer_id=:user_id and r.ride_status in ('0','1')";
	$sql3="select r.id,rd.end_ride_time,r.source_location,r.source_lat,r.source_lng,r.destination_location,r.destination_lat,r.destination_lng,r.total_amount,c.car_board,c.car_type,c.yellow_caricon,c.grey_caricon,c.yellow_caricon,d.profile_photo from wy_ride r join wy_ridedetails rd on r.id=rd.ride_id 
		join wy_cartype c on c.id=r.car_type join wy_driver d on d.id=rd.driver_id where r.customer_id=:user_id and rd.ride_status ='4' order by r.id desc limit 1";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $user_id);
				$stmt->execute();
				$cus = $stmt->fetch(PDO::FETCH_OBJ);
				if($cus){
					$status=$cus->profile_status;
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("user_id", $user_id);
					$stmt->execute();
					$ride = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($ride){
						echo '{ "Result": "Success","isRate":"No","details":'.json_encode($ride).',"is_blocked":'.json_encode($status).'}';
					}else{
						$stmt = $db->prepare($sql3);
						$stmt->bindParam("user_id", $user_id);
						$stmt->execute();
						$ride = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($ride){
							foreach($ride as $val)
							$sql2="select ride_id from wy_customerrate where ride_id=:id";
							$stmt = $db->prepare($sql2);
							$stmt->bindParam("id", $val->id);
							$stmt->execute();
							$ridedet = $stmt->fetchAll(PDO::FETCH_OBJ);
							if(!$ridedet){
								echo '{ "Result": "Failed","isRate":"Yes","details":'.json_encode($ride).',"is_blocked":'.json_encode($status).'}';
							}else{
								echo '{"Result":"Failed","Status":"Ride not found","is_blocked":'.json_encode($status).'}';
							}
						}else{
							echo '{"Result":"Failed","Status":"Ride not found","is_blocked":'.json_encode($status).'}';
						}
					}
				}else{
					echo '{"Result":"Failed","Status":"Customer not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function getcartype(){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select * from wy_ridetype";
	function getcar_type($id){
		$Arr = array();
		$sql1="select id,ride_category,car_board,car_type,is_category,capacity,yellow_caricon,grey_caricon,black_caricon from wy_cartype where ride_category=:id and cartype_basedon like '%1%' order by capacity asc";
		$db=getConnection();
		$stmt = $db->prepare($sql1);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$ride = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($ride){
			foreach($ride as $val){
				$Arr[] = array(
					"id" => $val->id,
					"ride_category" => $val->ride_category,
					"car_board" => $val->car_board,
					"is_category" => $val->is_category,
					"car_type" => $val->car_type,
					"capacity" => $val->capacity,
					"yellow_caricon" => $val->yellow_caricon,
					"grey_caricon" => $val->grey_caricon,
					"black_caricon" => $val->black_caricon,
					"car_list" => getcar_list($val->is_category)
				);
			}
		}
		return $Arr;
	}
	function getcar_list($id){
		$Arr = array();
		if($id!=1){
		$sql1="select id,ride_category,car_board,car_type,is_category,capacity,yellow_caricon,grey_caricon,black_caricon from wy_cartype where cartype_basedon like '%$id%' order by capacity asc";
		$db=getConnection();
		$stmt = $db->prepare($sql1);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$ride = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($ride){
			foreach($ride as $val){
				$Arr[] = array(
					"id" => $val->id,
					"ride_category" => $val->ride_category,
					"car_board" => $val->car_board,
					"car_type" => $val->car_type,
					"capacity" => $val->capacity,
					"yellow_caricon" => $val->yellow_caricon,
					"grey_caricon" => $val->grey_caricon,
					"black_caricon" => $val->black_caricon
				);
			}
		}
		}
		return $Arr;
	}
	$Arr = array();
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$ride = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($ride){
					foreach($ride as $val){
						$Arr[] = array(
							"id" => $val->id,
							"ride_category" => $val->ride_category,
							"car_type" => getcar_type($val->id),
						);
					}
					echo '{ "Result": "Success","details":'.json_encode($Arr).'}';
				}else{
					echo '{"Result":"Failed","Status":"Cartype not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function getbill($ride_id,$type){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select r.*,c.car_type as cartype,c.car_board,c.yellow_caricon from wy_ride r join wy_cartype c on c.id=r.car_type where r.id=:ride_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,$type);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){
					$profile_photo = selectsinglevalue("select d.profile_photo as retv from wy_driver d join wy_ridedetails r on r.driver_id=d.id where r.ride_id='$ride->id'");
					$sql3 = "select min_km from wy_faredetails where car_id=:car_type and booking_type=:booking_type and fare_id=:fare_id";
					$stmt = $db->prepare($sql3);
					$stmt->bindParam("car_type", $ride->car_type);
					$stmt->bindParam("booking_type", $ride->booking_type);
					$stmt->bindParam("fare_id", $ride->fare_id);
					$stmt->execute();
					$card = $stmt->fetchAll(PDO::FETCH_OBJ);
					$sql4 = "select tax_name,percentage from wy_tax where status=1";
					$stmt = $db->prepare($sql4);
					$stmt->execute();
					$tax = $stmt->fetchAll(PDO::FETCH_OBJ);
					$fareinfo = array();
					$taxinfo = array();
					$dist = explode(" ",$ride->distance);
					foreach($card as $val){
						$fareinfo[] = array(
							"title" => "Rate for first $val->min_km km",
							"value" => "₹ ".$ride->minimum_charge,
						);
						if($dist[0] > $val->min_km){
							$balkm = $dist[0] - $val->min_km;
							$fareinfo[] = array(
								"title" => "Rate for $balkm km",
								"value" => "₹ ".$ride->distance_amount,
							);
						}
					}
					$taximony = selectsinglevalue("select amount as retv from wy_taximoney where customer_id='$ride->customer_id'");
					$ride_money = $taximony;
					$taximoney = "-".$ride->final_amount;
					if(($ride_money < $ride->final_amount) && ($ride->final_amount!=0)){
						$cash = $ride->final_amount - $ride_money;
						$taximoney=$taximony;
					}elseif(($ride_money >= $ride->final_amount) && ($ride->final_amount!=0)){
						$cash = 0;
						$taximoney=$ride->final_amount;
					}else{
						$cash=0; 
						$taximoney=0;
						//$taximoney="-".$ride_money-$ride->total_amount;
					}
					$rideinfo = array(
						"totalamount" => $ride->final_amount,
						"offeramount" => $ride->offer_amount,
						"source" => $ride->source_location,
						"destination" => $ride->destination_location,
						"rideid" => $ride_id,
						"ride_money" => $ride_money,
						"cash" => $cash,
						"profile_photo" => $profile_photo,
						"car_type" => $ride->cartype,
						"car_board" => $ride->car_board,
						"yellow_caricon" => $ride->yellow_caricon,
					);
					
					$fareinfo[] = array(
						"title" => "Ridetime charge for $ride->rideing_time",
						"value" => "₹ ".$ride->ride_charge,
					);
					$fareinfo[] = array(
						"title" => "Waiting charge for $ride->waiting_time",
						"value" => "₹ ".$ride->waiting_charge,
					);
					/* $fareinfo[] = array(
						"title" => "Payment by Taxi Money",
						"value" => "₹ ".$taximoney,
					);
					$taxinfo[] = array(
						"title" => "Offer Amount",
						"value" => "₹ ".$ride->offer_amount,
					); */
					$shareinfo[] = array(
						"title" => "Driver's Share",
						"value" => "₹ ".$ride->driver_share,
					);
					/* $shareinfo[] = array(
						"title" => "Company's Share",
						"value" => "₹ ".$ride->company_share,
					); */
					if($ride->peak_percent!=0){
						if($ride->fare_type==4) $name="Peak";
						else $name="Special";
						$fareinfo[] = array(
							"title" => "$name time charges (".$ride->peak_percent." %)",
							"value" => "₹ ".$ride->peak_amount,
						);
					}
					$totamt = $ride->minimum_charge+$ride->waiting_charge+$ride->distance_amount+$ride->ride_charge;
					if($tax){
						foreach($tax as $val){
							$taxinfo[] = array(
								"title" => $val->tax_name,
								"value" => "₹ ".round(($totamt*$val->percentage/100),2),
							);
						}
					}
					echo '{ "Result": "Success","rideinfo":'.json_encode($rideinfo).',"faredetails":'.json_encode($fareinfo).',"sharedetails":'.json_encode($shareinfo).',"taxdetails":'.json_encode($taxinfo).'}';
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function endride(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date1=date('Y-m-d');
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select r.*,c.driver_id,d.driver_type,d.isfranchise,ct.franchise_share,ct.companydriver_share,ct.attacheddriver_share,c.driver_id,c.start_ride_time,c.end_ride_time,cu.mobile,cu.device_type,cu.device_token from wy_ride r join wy_ridedetails c on c.ride_id=r.id 
				join  wy_customer cu on cu.id=r.customer_id 
				join wy_driver d on d.id=c.driver_id join wy_cartype ct on ct.id=r.car_type where r.id=:ride_id and c.ride_status='3'";
	$sql1 = "update wy_ridedetails set ride_status='4',end_ride_time=:end_ride_time where ride_id=:ride_id and ride_status='3'";
	$sql2 = "update wy_ride set peak_amount=:peak_amount,peak_percent=:peak_percent,ride_status='4',fare_id=:fare_id,fare_type=:fare_type,destination_location=:destination_location,destination_lat=:destination_lat,destination_lng=:destination_lng,distance=:distance,waiting_time=:waiting_time,distance_amount=:distance_amount,minimum_charge=:minimum_charge,ride_charge=:ride_charge,
			rideing_time=:rideing_time,waiting_charge=:waiting_charge,total_tax=:total_tax,total_amount=:total_amount,updated_at='$date',offer_amount=:offer_amount,final_amount=:final_amount,driver_share=:driver_share,franchise_share=:franchise_share,company_share=:company_share,ride_endby='1' where id='$register->ride_id'";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $register->ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){
					$starttime = date("H:i:s",strtotime($ride->start_ride_time));
					$endtime = date("H:i:s",strtotime($ride->end_ride_time));
					$device_token = $ride->device_token;
					$device_type = $ride->device_type;
					$distance = $register->distance;
					$dist = explode(" ",$distance);
					$sql4 = "select sum(percentage) as cntt from wy_tax where status='1'";
					$sql3 = "select * from wy_faredetails where car_id=:car_type and status='1' and booking_type=:booking_type and fare_type!=1 order by fare_type desc"; 
					$stmt = $db->prepare($sql3); 
					$stmt->bindparam("car_type",$ride->car_type);
					$stmt->bindparam("booking_type",$ride->booking_type);
					$stmt->execute();
					$rate = $stmt->fetchAll(PDO::FETCH_OBJ);
					$sql31 = "select * from wy_faredetails where car_id=:car_type and status='1' and booking_type=:booking_type and package_id=:package_id and fare_type=1"; 
					$stmt = $db->prepare($sql31); 
					$stmt->bindparam("car_type",$ride->car_type);
					$stmt->bindparam("booking_type",$ride->booking_type);
					$stmt->bindparam("package_id",$ride->package_type);
					$stmt->execute();
					$rate1 = $stmt->fetch(PDO::FETCH_OBJ);
					$peaktime=0;
					$pkamt=0;
					$waittim=0;
					$fare_id = $rate1->fare_id;
					if($ride->booking_type==1){
						foreach($rate as $val){
							//mor time
							$start_time = $val->ride_start_time;
							$end_time = $val->ride_end_time;
							$srt_datetime = $date1." ".$start_time;
							$srt_datetime = date("Y-m-d H:i:s",strtotime($srt_datetime));
							if(strtotime($start_time)>strtotime($end_time)){
								$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
								$end_datetime = $date1." ".$end_time;
								$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
							}else{
								$end_datetime = $date1." ".$end_time;
								$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
							} 
							//nit time
							$nstart_time = $val->nit_start_time;
							$nend_time = $val->nit_end_time;
							$nsrt_datetime = $date1." ".$nstart_time;
							$nsrt_datetime = date("Y-m-d H:i:s",strtotime($nsrt_datetime));
							if(strtotime($nstart_time)>strtotime($nend_time)){
								$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
								$nend_datetime = $date1." ".$nend_time;
								$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
							}else{
								$nend_datetime = $date1." ".$nend_time;
								$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
							} 
							if((strtotime($ride->start_ride_time)>=strtotime($srt_datetime) && strtotime($ride->start_ride_time)<strtotime($end_datetime)) || (strtotime($ride->start_ride_time)>=strtotime($nsrt_datetime) && strtotime($ride->start_ride_time)<strtotime($nend_datetime))){
								$peaktime = $val->fare_percent;
								$fare_type = $val->fare_type;
								break;
							}
						}
						//if($dist[0]!='00.00' || $dist[0]!='0.0'){
							if($dist[0] <= $rate1->min_km){
								$amount = $rate1->min_fare_amount;
								$amount1 = $rate1->min_fare_amount;
							}else{
								$km = $dist[0] - $rate1->min_km;
								$amount1 = $rate1->min_fare_amount;
								$amount2 = $km*$rate1->ride_fare;
								$amount = $amount1 + $amount2;
							}
						/* }else{
								$amount = 0;
								$amount1 = 0;
							} */
						if($register->waiting_duration!=''){
							$wt = $register->waiting_duration;
							$waittim = $rate1->waiting_charge*$wt;
						}else{
							$wt = 0;
							$waittim = $rate1->waiting_charge*$wt;
						}
						$ridetim = $rate1->distance_fare*$register->duration;
							
						/* }else{
							$amount = 0;
							$waittim=0;
							$ridetim=0;
						} */
						
						$tamt= $amount+$waittim+$ridetim;
						if($peaktime>0){
							$pkamt = round($tamt*($peaktime/100));
							$tamt = $tamt+$pkamt;
						}
					}else{
						$fare_type =1;
						if($dist[0] <= $rate1->min_km){
								$amount = $rate1->min_fare_amount;
								$amount1 = $rate1->min_fare_amount;
						}else{
							$km = $dist[0] - $rate1->min_km;
							$amount1 = $rate1->min_fare_amount;
							$amount2 = $km*$rate1->ride_fare;
							$amount = $amount1 + $amount2;
						}
						if($ride->booking_type==2){
							$ridetim = $rate1->distance_fare;
						}else{
							if(round(($register->duration/60)) <= $rate1->min_time){
								$ridetim=0;
							}else{
								$km = round(($register->duration/60)) - $rate1->min_time;
								$ridetim = $km*$rate1->distance_fare;
							}
						}
						$tamt= $amount+$ridetim;
					}
					// taxes
					$stmt = $db->prepare($sql4); 
					$stmt->execute();
					$taxs = $stmt->fetch(PDO::FETCH_OBJ);
					$taxes = $taxs->cntt; 
					///
					$tax = round(($tamt*$taxes/100));
					$amt = round($tamt+$tax);
						
					$offer_id = $ride->offer_id;
					if($offer_id!=0){
						$offr = "select * from wy_offers where id=:offer_id";
						$stmt = $db->prepare($offr);
						$stmt->bindParam("offer_id",$offer_id);
						$stmt->execute();
						$offers = $stmt->fetch(PDO::FETCH_OBJ);
						$rideoffr = selectsinglevalue("select count(*) as retv from wy_ride where customer_id='$ride->customer_id' and coupon_code='$ride->coupon_code' and ride_status=4");
						$rideoffr1 = selectsinglevalue("select count(*) as retv from wy_taximoney_history where customer_id='$ride->customer_id' and coupon_code='$ride->coupon_code'");
						$rideoffr = $rideoffr1+$rideoffr+1;
						if(intval($rideoffr)>=intval($offers->usage_count)){
							$sql13 = "update wy_offernotification set usage_count=usage_count+1, used=1, updated_at=:updated_at where offer_id =:offer_id and user_id=:customer_id";
							$stmt = $db->prepare($sql13);
							$stmt->bindParam("offer_id",$offer_id);
							$stmt->bindParam("customer_id",$ride->customer_id);
							$stmt->bindParam("updated_at",$date);
							$stmt->execute();
						}else{
							$sql13 = "update wy_offernotification set usage_count=usage_count+1, updated_at=:updated_at where offer_id =:offer_id and user_id=:customer_id";
							$stmt = $db->prepare($sql13);
							$stmt->bindParam("offer_id",$offer_id);
							$stmt->bindParam("customer_id",$ride->customer_id);
							$stmt->bindParam("updated_at",$date);
							$stmt->execute();
						}
						if($offers->coupon_type==1){
							$offer_amount = $offers->coupon_value;
							if($offer_amount>$amt){
								$final_amount = 0;
								$taxi_amount = $offer_amount-$amt;
								/* $sql13 = "update wy_taximoney set amount=amount+$taxi_amount, updated_at='$date' where customer_id='$ride->customer_id'";
								$stmt = $db->query($sql13);
								$sql13 = "insert into wy_taximoney_history(customer_id,amount,based_on,type,created_at,updated_at) values('$ride->customer_id','$taxi_amount','2','1','$date','$date')";
								$stmt = $db->query($sql13); */
							}else{
								$final_amount = $amt-$offer_amount;
							}
						}elseif($offers->coupon_type==2){
							$offer_amount = $amt*($offers->coupon_value/100);
							if($offer_amount>$amt) $final_amount = 0;
							else $final_amount = $amt-$offer_amount;
						}else{
							$offer_amount = $amt;
							$final_amount = $amt-$offer_amount;
						}
						
					}else{
						$final_amount = $amt;
					}

					if($ride->isfranchise == 1){ // Check driver isfranchise or not
						$franchise_share = round(($final_amount * ($ride->franchise_share/100)),2);
						$company_share = round(($final_amount * ($ride->companydriver_share/100)),2);
						$driver_share= round(($final_amount * ($ride->attacheddriver_share/100)),2);
					}else{
						if($ride->driver_type == 1){
							$vehicle_check = vehicle_isfranchise($ride->driver_id);
							if($vehicle_check == 1){
								$franchise_share = round(($final_amount * ($ride->franchise_share/100)),2);
								$driver_share = round(($final_amount * ($ride->companydriver_share/100)),2);
								$company_share = round(($final_amount * ($driver_share + $franchise_share)),2);
							}else{
								$driver_share = round(($final_amount * ($ride->companydriver_share/100)),2);
								$company_share = round(($final_amount * $driver_share),2);
							}
						}else{
							$driver_share = round(($final_amount * ($ride->attacheddriver_share/100)),2);
							$company_share = round(($final_amount * ($ride->companydriver_share/100)),2);
						}
						
					}
					/* 	
					if(empty($franchise_share)){
						$franchise_share = 0;
					}
 */
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("peak_amount", $pkamt);
					$stmt->bindParam("peak_percent", $peaktime);
					$stmt->bindParam("fare_id", $fare_id);
					$stmt->bindParam("fare_type", $fare_type);
					$stmt->bindParam("distance", $register->distance);
					$stmt->bindParam("destination_location", $register->destination_location);
					$stmt->bindParam("destination_lng", $register->destination_lng);
					$stmt->bindParam("destination_lat", $register->destination_lat);
					$stmt->bindParam("rideing_time", $register->rideing_time);
					$stmt->bindParam("waiting_time", $register->waiting_time);
					$stmt->bindParam("distance_amount", $amount2);
					$stmt->bindParam("minimum_charge", $amount1);
					$stmt->bindParam("ride_charge", $ridetim);
					$stmt->bindParam("waiting_charge", $waittim);
					$stmt->bindParam("total_tax", $tax);
					$stmt->bindParam("total_amount", $amt);
					$stmt->bindParam("offer_amount", $offer_amount);
					$stmt->bindParam("final_amount", $final_amount);
					$stmt->bindParam("company_share", $company_share);
					$stmt->bindParam("franchise_share", $franchise_share);
					$stmt->bindParam("driver_share", $driver_share);
					$stmt->execute();
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("ride_id", $register->ride_id);
					$stmt->bindParam("end_ride_time", $date);
					$stmt->execute();			
					//$message = "Thanks for riding with us.Your ride amount is Rs.$amt";
					$message = "Thanks for riding with us";
					if($device_type==1){
						if($device_token!=''){
							apns_cus($device_token,$message,$register->ride_id);
						}
					}else{
						send_gcm_notify($device_token, $message,$register->ride_id );
					}

					echo '{ "Result": "Success","ride_id":'.json_encode($register->ride_id).'}';
					
					/// firebase
					$date_fmt = date("d-m-Y");
					$header = array();
					$header[] = 'Content-Type: application/json';
					$postdata = '{"destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","accept_status":"1","ride_status":"4"}';
				
					$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					//curl_setopt($ch, CURLOPT_POST,1);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
					$result = curl_exec($ch); 
					curl_close($ch); 
					///firebase
				
					/// firebase driver
					$date_fmt = date("d-m-Y");
					$header = array();
					$header[] = 'Content-Type: application/json';

					$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$ride->driver_id.json");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					//curl_setopt($ch, CURLOPT_POST,1);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
					$result = curl_exec($ch); 
					curl_close($ch); 
					///firebase
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function forgotpassword(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql = "select name,temp_token from wy_customer where email=:email";
	try{
		$db = getConnection();
		$db=getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("email", $register->email);
		$stmt->execute();
		$customer = $stmt->fetch(PDO::FETCH_OBJ);
		if($customer){ 
			$subject="Forgot Password";
			$username = $customer->name;
			$token = $customer->temp_token;
			$id = urlencode(base64_encode($register->email));
			$content = "So you lost your password? No problem! Please click the link below to reset your password<br><br>
			<a href='http://mobycabs.com/moby_admin/forgotpassword/$id/$token'>Click here</a>";
				$sign = "Thank you<br>
MobyCabs Team<br><br>
© 2017 MobyCabs. All right reserved.";
			$html='<html class="no-js" lang="en"> 
				<body>
				<div style="
					width: auto;
					border: 15px solid #efc01a;
					padding: 20px;
					margin: 10px;
				">
				 <div class="container">
					<div class="navbar-header">
						<div style="text-align: center;">
						<a href="" title="" style="margin-top:0px"><img src="http://mobycabs.com/moby_admin/api/upload/wry_logo.png"  class="img-responsive logo-new" ></a>
						</div>
						
						<span style="float:right; text-align:right;">
							
						</span>
					   
						<div style="clear:both;" ></div>
						<hr width="100%" />
					</div>
					<div class="mail-container">
						<br />
						<b> '.$username.' </b>
						<br />
						<br />
						'.$content.' 
						<br />
					</div>
					<br />
					<hr width="100%" />
					<footer class="navbar-inverse">
						<div class="row">
						'.$sign.'
							<div class="collapse navbar-collapse"></div>
						</div>
					</footer>
				</div>
				</body>
				</html>';
			$mail       = new PHPMailer();
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = 'armortechmobile@gmail.com';
			$mail->Password = 'Codekhadi$34';
			$mail->From = $mail->Username; //Default From email same as smtp user
			$mail->FromName = "MobyCabs";
			$mail->AddAddress($register->email, '');
			$mail->CharSet = 'UTF-8';
			$mail->Subject    = $subject;
			$mail->MsgHTML($html); 
			if($mail->Send()) {
				echo '{ "Result": "Success","Status":"Password reset link has been sent to your mail"}';
			} else {
			  echo '{"Result":"Failed","Status":"Email sent failed"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"User not found"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function start_ride_v1($ride_id,$driver_id,$crn){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$date1 = date("Y-m-d");
	$sql="select r.*,c.name,c.mobile from wy_ride r join wy_customer c on c.id=r.customer_id where r.id=:ride_id and r.ride_status=1";
	$sql1="select id from wy_driver where id=:driver_id";
	$sql2 = "update wy_ridedetails set ride_status='3', start_ride_time=:start_ride_time, updated_at=:updated_at where ride_id=:ride_id and driver_id=:driver_id";
	$sqlcrn = "select * from wy_ride where id = :id and crn = :crn";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$crncheck = $db->prepare($sqlcrn);
				$crncheck->bindParam("id", $ride_id);
				$crncheck->bindParam("crn", $crn);
				$crncheck->execute();
				$crndata = $crncheck->fetch(PDO::FETCH_OBJ);
				if(!$crndata){
					echo '{"Result":"Failed","Status":"Wrong OTP Number"}';exit;
				}
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("driver_id", $driver_id);
					$stmt->execute();
					$dr_det = $stmt->fetch(PDO::FETCH_OBJ);
					if($dr_det){ 
						$stmt = $db->prepare($sql2);
						$stmt->bindParam("ride_id", $ride_id);
						$stmt->bindParam("driver_id", $driver_id);
						$stmt->bindParam("start_ride_time", $date);
						$stmt->bindParam("updated_at", $date);
						$stmt->execute();
						/* $sql3="select d.firstname,d.lastname,d.mobile,c.car_no as reg_no,b.brand,m.model from wy_ridedetails r join wy_driver d on r.driver_id=d.id 
								join wy_assign_taxi a on d.id=a.driver_id join wy_carlist c on c.id=a.car_num join wy_brand b on b.id=c.brand 
								join wy_model m on m.id=c.model where r.ride_id=:ride_id and a.status='1' and r.accept_status=1";
						$stmt = $db->prepare($sql3);
						$stmt->bindParam("ride_id", $ride_id);
						$stmt->execute();
						$alertmsg = $stmt->fetch(PDO::FETCH_OBJ);
						$message = "$ride->name($ride->mobile) started a ride from $ride->source_location. Vehicle Info : $alertmsg->reg_no, $alertmsg->brand $alertmsg->model,$alertmsg->firstname $alertmsg->lastname, $alertmsg->mobile";
						$message = urlencode($message);
						$mobileno=''; */
						/* $sql4="select mobile from wy_emergencycontacts where customer_id=:customer_id and is_showride='1'";
						$stmt = $db->prepare($sql4);
						$stmt->bindParam("customer_id", $ride->customer_id);
						$stmt->execute();
						$contact = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($contact){
							foreach($contact as $val){
								$mobile = preg_replace('/[^A-Za-z0-9]/', "", $val->mobile);
								$mobileno .= $mobile.',';
							}
							$mobile=rtrim($mobileno, ',');
							$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$output = curl_exec($ch);      
							curl_close($ch);
						} */
						if($ride->booked_through=='1'){
							/// firebase
							$date_fmt = date("d-m-Y");
							$header = array();
							$header[] = 'Content-Type: application/json';
							$postdata = '{"accept_status":"1","ride_status":"3"}';
						
							$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
							//curl_setopt($ch, CURLOPT_POST,1);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							$result = curl_exec($ch); 
							curl_close($ch); 
							///firebase
						}
						
						if($ride->booked_through=='2'){
							/// firebase
							$date_fmt = date("d-m-Y");
							$header = array();
							$header[] = 'Content-Type: application/json';
							$postdata = '{"accept_status":"1","ride_status":"3"}';
						
							$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/dispatch_devp/$ride->booked_by/$date_fmt/$ride_id.json");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
							//curl_setopt($ch, CURLOPT_POST,1);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							$result = curl_exec($ch); 
							curl_close($ch); 
							///firebase
						}
						
						/// firebase driver
						$date_fmt = date("d-m-Y");
						$header = array();
						$header[] = 'Content-Type: application/json';
						$postdata = '{"accept_status":"1","ride_status":"3"}';

						$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$driver_id.json");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						//curl_setopt($ch, CURLOPT_POST,1);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
						$result = curl_exec($ch); 
						curl_close($ch); 
						///firebase
						
						//ratecard details
						$ratedet=array();
						$taxdet=array();
						$sql2="select fare_type,fare_percent,ride_start_time,ride_end_time,nit_start_time,nit_end_time from wy_faredetails where car_id=:car_type AND booking_type = :booking_type and status='1' order by fare_type desc";
						$stmt = $db->prepare($sql2);
						$stmt->bindParam("car_type", $ride->car_type);
						$stmt->bindParam("booking_type", $ride->booking_type);
						$stmt->execute();
						$ratedet1 = $stmt->fetchAll(PDO::FETCH_OBJ);
						$sql3="select * from wy_faredetails where car_id=:car_type AND booking_type = :booking_type and status='1' and fare_type=1";
						$stmt = $db->prepare($sql3);
						$stmt->bindParam("car_type", $ride->car_type);
						$stmt->bindParam("booking_type", $ride->booking_type);
						$stmt->execute();
						$ratedet2 = $stmt->fetch(PDO::FETCH_OBJ);
						//$number_of_rows = $stmt->rowCount();
						if($ratedet1){
							$peaktime='';
							$fare_type=1;
							foreach($ratedet1 as $rate){
									$start_time = $rate->ride_start_time;
									$end_time = $rate->ride_end_time;
									$srt_datetime = $date1." ".$start_time;
									$srt_datetime = date("Y-m-d H:i:s",strtotime($srt_datetime));
									if(strtotime($start_time)>strtotime($end_time)){
										$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
										$end_datetime = $date1." ".$end_time;
										$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
									}else{
										$end_datetime = $date1." ".$end_time;
										$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
									}
									//nit time
									$nstart_time = $rate->nit_start_time;
									$nend_time = $rate->nit_end_time;
									$nsrt_datetime = $date1." ".$nstart_time;
									$nsrt_datetime = date("Y-m-d H:i:s",strtotime($nsrt_datetime));
									if(strtotime($nstart_time)>strtotime($nend_time)){
										$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
										$nend_datetime = $date1." ".$nend_time;
										$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
									}else{
										$nend_datetime = $date1." ".$nend_time;
										$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
									} 
									if((strtotime($date)>=strtotime($srt_datetime) && strtotime($date)<strtotime($end_datetime)) || (strtotime($date)>=strtotime($nsrt_datetime) && strtotime($date)<strtotime($nend_datetime))){
										$peaktime = $rate->fare_percent;
										$fare_type = $rate->fare_type;
										break;
									}
								}
								$ratedet[] = array(
										"fare_id" => $ratedet2->fare_id,
										"fare_type" => $fare_type,
										"min_km" => $ratedet2->min_km,
										"min_fare_amount" => $ratedet2->min_fare_amount,
										"ride_each_km" => $ratedet2->ride_each_km,
										"ride_fare" => $ratedet2->ride_fare,
										"distance_time" => $ratedet2->distance_time,
										"distance_fare" => $ratedet2->distance_fare,
										"waiting_time" => $ratedet2->waiting_time,
										"waiting_charge" => $ratedet2->waiting_charge,
										"peaktime" => $peaktime,
									);
							$sql4 = "select tax_name,percentage from wy_tax where status=1";
							$stmt = $db->prepare($sql4);
							$stmt->execute();
							$tax = $stmt->fetchAll(PDO::FETCH_OBJ);
							if($tax){
								foreach($tax as $val){
									$taxdet[] = array(
										"label_key" => $val->tax_name,
										"value" => $val->percentage,
									);
								}
							}
						}
						$offer = array();
						if($ride->coupon_code!=''){
							$qry = "select * from wy_offers where coupon_code=:coupon_code";
							$stmt = $db->prepare($qry);
							$stmt->bindParam("coupon_code", $ride->coupon_code);
							$stmt->execute();
							$offer = $stmt->fetchAll(PDO::FETCH_OBJ);
						}
						///
						echo '{ "Result": "Success","Status":"status updated","details":'.json_encode($ratedet).',"tax":'.json_encode($taxdet).',"offers":'.json_encode($offer).'}';
					}else{
						echo '{"Result":"Failed","Status":"Driver not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function start_ride($ride_id,$driver_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$date1 = date("Y-m-d");
	$sql="select r.*,c.name,c.mobile from wy_ride r join wy_customer c on c.id=r.customer_id where r.id=:ride_id and r.ride_status=1";
	$sql1="select id from wy_driver where id=:driver_id";
	$sql2 = "update wy_ridedetails set ride_status='3', start_ride_time=:start_ride_time, updated_at=:updated_at where ride_id=:ride_id and driver_id=:driver_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("driver_id", $driver_id);
					$stmt->execute();
					$dr_det = $stmt->fetch(PDO::FETCH_OBJ);
					if($dr_det){ 
						$stmt = $db->prepare($sql2);
						$stmt->bindParam("ride_id", $ride_id);
						$stmt->bindParam("driver_id", $driver_id);
						$stmt->bindParam("start_ride_time", $date);
						$stmt->bindParam("updated_at", $date);
						$stmt->execute();
						/* $sql3="select d.firstname,d.lastname,d.mobile,c.car_no as reg_no,b.brand,m.model from wy_ridedetails r join wy_driver d on r.driver_id=d.id 
								join wy_assign_taxi a on d.id=a.driver_id join wy_carlist c on c.id=a.car_num join wy_brand b on b.id=c.brand 
								join wy_model m on m.id=c.model where r.ride_id=:ride_id and a.status='1' and r.accept_status=1";
						$stmt = $db->prepare($sql3);
						$stmt->bindParam("ride_id", $ride_id);
						$stmt->execute();
						$alertmsg = $stmt->fetch(PDO::FETCH_OBJ);
						$message = "$ride->name($ride->mobile) started a ride from $ride->source_location. Vehicle Info : $alertmsg->reg_no, $alertmsg->brand $alertmsg->model,$alertmsg->firstname $alertmsg->lastname, $alertmsg->mobile";
						$message = urlencode($message);
						$mobileno='';
						$sql4="select mobile from wy_emergencycontacts where customer_id=:customer_id and is_showride='1'";
						$stmt = $db->prepare($sql4);
						$stmt->bindParam("customer_id", $ride->customer_id);
						$stmt->execute();
						$contact = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($contact){
							foreach($contact as $val){
								$mobile = preg_replace('/[^A-Za-z0-9]/', "", $val->mobile);
								$mobileno .= $mobile.',';
							}
							$mobile=rtrim($mobileno, ',');
							$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$output = curl_exec($ch);      
							curl_close($ch);
						} */
						if($ride->booked_through=='1'){
							/// firebase
							$date_fmt = date("d-m-Y");
							$header = array();
							$header[] = 'Content-Type: application/json';
							$postdata = '{"accept_status":"1","ride_status":"3"}';
						
							$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
							//curl_setopt($ch, CURLOPT_POST,1);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							$result = curl_exec($ch); 
							curl_close($ch); 
							///firebase
						}
						
						if($ride->booked_through=='2'){
							/// firebase
							$date_fmt = date("d-m-Y");
							$header = array();
							$header[] = 'Content-Type: application/json';
							$postdata = '{"accept_status":"1","ride_status":"3"}';
						
							$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/dispatch_devp/$ride->booked_by/$date_fmt/$ride_id.json");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
							//curl_setopt($ch, CURLOPT_POST,1);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							$result = curl_exec($ch); 
							curl_close($ch); 
							///firebase
						}
						
						/// firebase driver
						$date_fmt = date("d-m-Y");
						$header = array();
						$header[] = 'Content-Type: application/json';
						$postdata = '{"accept_status":"1","ride_status":"3"}';

						$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$driver_id.json");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						//curl_setopt($ch, CURLOPT_POST,1);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
						$result = curl_exec($ch); 
						curl_close($ch); 
						///firebase
						
						//ratecard details
						$ratedet=array();
						$taxdet=array();
						$sql2="select fare_type,fare_percent,ride_start_time,ride_end_time,nit_start_time,nit_end_time from wy_faredetails where car_id=:car_type AND booking_type = :booking_type and status='1' order by fare_type desc";
						$stmt = $db->prepare($sql2);
						$stmt->bindParam("car_type", $ride->car_type);
						$stmt->bindParam("booking_type", $ride->booking_type);
						$stmt->execute();
						$ratedet1 = $stmt->fetchAll(PDO::FETCH_OBJ);
						$sql3="select * from wy_faredetails where car_id=:car_type AND booking_type = :booking_type and status='1' and fare_type=1";
						$stmt = $db->prepare($sql3);
						$stmt->bindParam("car_type", $ride->car_type);
						$stmt->bindParam("booking_type", $ride->booking_type);
						$stmt->execute();
						$ratedet2 = $stmt->fetch(PDO::FETCH_OBJ);
						//$number_of_rows = $stmt->rowCount();
						if($ratedet1){
							$peaktime='';
							$fare_type=1;
							foreach($ratedet1 as $rate){
									$start_time = $rate->ride_start_time;
									$end_time = $rate->ride_end_time;
									$srt_datetime = $date1." ".$start_time;
									$srt_datetime = date("Y-m-d H:i:s",strtotime($srt_datetime));
									if(strtotime($start_time)>strtotime($end_time)){
										$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
										$end_datetime = $date1." ".$end_time;
										$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
									}else{
										$end_datetime = $date1." ".$end_time;
										$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
									}
									//nit time
									$nstart_time = $rate->nit_start_time;
									$nend_time = $rate->nit_end_time;
									$nsrt_datetime = $date1." ".$nstart_time;
									$nsrt_datetime = date("Y-m-d H:i:s",strtotime($nsrt_datetime));
									if(strtotime($nstart_time)>strtotime($nend_time)){
										$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
										$nend_datetime = $date1." ".$nend_time;
										$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
									}else{
										$nend_datetime = $date1." ".$nend_time;
										$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
									} 
									if((strtotime($date)>=strtotime($srt_datetime) && strtotime($date)<strtotime($end_datetime)) || (strtotime($date)>=strtotime($nsrt_datetime) && strtotime($date)<strtotime($nend_datetime))){
										$peaktime = $rate->fare_percent;
										$fare_type = $rate->fare_type;
										break;
									}
								}
								$ratedet[] = array(
										"fare_id" => $ratedet2->fare_id,
										"fare_type" => $fare_type,
										"min_km" => $ratedet2->min_km,
										"min_fare_amount" => $ratedet2->min_fare_amount,
										"ride_each_km" => $ratedet2->ride_each_km,
										"ride_fare" => $ratedet2->ride_fare,
										"distance_time" => $ratedet2->distance_time,
										"distance_fare" => $ratedet2->distance_fare,
										"waiting_time" => $ratedet2->waiting_time,
										"waiting_charge" => $ratedet2->waiting_charge,
										"peaktime" => $peaktime,
									);
							$sql4 = "select tax_name,percentage from wy_tax where status=1";
							$stmt = $db->prepare($sql4);
							$stmt->execute();
							$tax = $stmt->fetchAll(PDO::FETCH_OBJ);
							if($tax){
								foreach($tax as $val){
									$taxdet[] = array(
										"label_key" => $val->tax_name,
										"value" => $val->percentage,
									);
								}
							}
						}
						$offer = array();
						if($ride->coupon_code!=''){
							$qry = "select * from wy_offers where coupon_code=:coupon_code";
							$stmt = $db->prepare($qry);
							$stmt->bindParam("coupon_code", $ride->coupon_code);
							$stmt->execute();
							$offer = $stmt->fetchAll(PDO::FETCH_OBJ);
						}
						///
						echo '{ "Result": "Success","Status":"status updated","details":'.json_encode($ratedet).',"tax":'.json_encode($taxdet).',"offers":'.json_encode($offer).'}';
					}else{
						echo '{"Result":"Failed","Status":"Driver not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function reached_pickup($ride_id,$driver_id){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select r.*,c.mobile,c.fb_id,c.device_type,c.device_token from wy_ride r join wy_customer c on r.customer_id=c.id where r.id=:ride_id and r.ride_status=1";
	$sql1="select d.firstname,d.lastname,d.mobile,c.car_no,b.brand,m.model from wy_driver d join wy_assign_taxi a on a.driver_id=d.id join wy_carlist c on c.id=a.car_num 
			join wy_brand b on b.id=c.brand join wy_model m on m.id=c.model where d.id=:driver_id";
	$sql2 = "update wy_ridedetails set ride_status='2', reach_pickuploc_time=:reach_pickuploc_time, updated_at=:updated_at where ride_id=:ride_id and driver_id=:driver_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("driver_id", $driver_id);
					$stmt->execute();
					$dr_det = $stmt->fetch(PDO::FETCH_OBJ);
					if($dr_det){ 
						$stmt = $db->prepare($sql2);
						$stmt->bindParam("ride_id", $ride_id);
						$stmt->bindParam("driver_id", $driver_id);
						$stmt->bindParam("reach_pickuploc_time", $date);
						$stmt->bindParam("updated_at", $date);
						$stmt->execute();
						$device_token = $ride->device_token;
						$device_type = $ride->device_type;
						$message1 = "$dr_det->firstname $dr_det->lastname reached your location in a $dr_det->brand $dr_det->model $dr_det->car_no.";
						if($device_type==1){
							if($device_token!=''){
								apns_cus($device_token,$message1,$ride_id);
							}
						}else{
							send_gcm_notify($device_token, $message1,$ride_id );
						}
									
						/* $mobile = $ride->mobile;
						$message   =  "$dr_det->firstname $dr_det->lastname($dr_det->mobile) reached your Location.";
						$message = urlencode($message);
						$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);      
						curl_close($ch);  */
						
						/// firebase
						$date_fmt = date("d-m-Y");
						$header = array();
						$header[] = 'Content-Type: application/json';
						$postdata = '{"accept_status":"1","ride_status":"2"}';
					
						$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						//curl_setopt($ch, CURLOPT_POST,1);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
						$result = curl_exec($ch); 
						curl_close($ch); 
						///firebase
						
						/// firebase driver
						$date_fmt = date("d-m-Y");
						$header = array();
						$header[] = 'Content-Type: application/json';
						$postdata = '{"accept_status":"1","ride_status":"2"}';

						$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$driver_id.json");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						//curl_setopt($ch, CURLOPT_POST,1);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
						$result = curl_exec($ch); 
						curl_close($ch); 
						///firebase
						
						echo '{ "Result": "Success","Status":"status updated"}';
					}else{
						echo '{"Result":"Failed","Status":"Driver not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function getride_details($ride_id,$type){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d H:i:s");
	$sql="select id from wy_ride where id=:ride_id";
	$sql3 = "select dr.id,dr.profile_photo,CONCAT(dr.firstname,' ',dr.lastname) as name,dr.mobile,ct.car_type,ct.car_board,ct.yellow_caricon as black_caricon,c.capacity,b.brand,m.model,c.car_no as reg_no,c.insurance_image,c.created_at,c.updated_at,r.accept_status,r.ride_status,rr.crn,rr.source_location,rr.source_lat,rr.source_lng,rr.destination_location,rr.destination_lat,rr.destination_lng,rr.booking_type,rr.ride_status as cab_availability 
			from wy_ridedetails r  join wy_driver dr on dr.id=r.driver_id 
			join wy_assign_taxi a on a.driver_id=dr.id join wy_carlist c on c.id=a.car_num join wy_ride rr on rr.id=r.ride_id 
			join wy_brand b on c.brand=b.id join wy_model m on m.id=c.model 
			join wy_cartype ct on ct.id=c.car_type where r.ride_id=:ride_id and a.status='1'  order by r.id desc";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,$type);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $ride_id);
				$stmt->execute();
				$ride = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride){
					$stmt = $db->prepare($sql3);
					$stmt->bindParam("ride_id", $ride_id);
					$stmt->execute();
					$dr_det = $stmt->fetch(PDO::FETCH_OBJ);
					if($dr_det){
						echo '{ "Result": "Success","details":'.json_encode($dr_det).'}';
					}else{
						echo '{"Result":"Failed","Status":"Details not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function cancel_ride(){
	$date = date("Y-m-d H:i:s");
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$sql="select customer_id from wy_ride where id=:ride_id and ride_status not in ('4','3','2')";
	$sql1="update wy_ride set ride_status=2,cancel_notes=:cancel_notes,cancle_time=:cancle_time where id=:ride_id";
	$sql2="update wy_ridedetails set ride_status=5,cancel_notes=:cancel_notes,cancel_time=:cancle_time where ride_id=:ride_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,$register->type);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("ride_id", $register->ride_id);
				$stmt->execute();
				$ride1 = $stmt->fetch(PDO::FETCH_OBJ);
				if($ride1){
				$sql="select r.*,rd.accept_status,rd.ride_status,d.id as driver_id,d.firstname,d.lastname,d.mobile,c.car_no as reg_no,b.brand,m.model from wy_ride r join wy_ridedetails rd on r.id=rd.ride_id 
							join wy_driver d on d.id=rd.driver_id join wy_assign_taxi a on a.driver_id=d.id join wy_carlist c on c.id=a.car_num
							join wy_brand b on b.id=c.brand join wy_model m on m.id=c.model where r.id=:ride_id and rd.accept_status in ('0','1') and a.status=1 order by rd.id desc limit 1";
					$stmt = $db->prepare($sql);
					$stmt->bindParam("ride_id", $register->ride_id);
					$stmt->execute();
					$ride = $stmt->fetch(PDO::FETCH_OBJ);
					if($register->type=='1'){
						if($ride){
							if($ride->ride_status!=3){
								$stmt = $db->prepare($sql1);
								$stmt->bindParam("ride_id", $register->ride_id);
								$stmt->bindParam("cancel_notes", $register->cancel_notes);
								$stmt->bindParam("cancle_time", $date);
								$stmt->execute();
								$stmt = $db->prepare($sql2);
								$stmt->bindParam("ride_id", $register->ride_id);
								$stmt->bindParam("cancel_notes", $register->cancel_notes);
								$stmt->bindParam("cancle_time", $date);
								$stmt->execute();
								$sql3 = "select d.device_type,d.device_token from wy_ridedetails r join wy_driver d on r.driver_id=d.id 
										 where r.ride_id=:ride_id and r.accept_status=1";
								$stmt = $db->prepare($sql3);
								$stmt->bindParam("ride_id", $register->ride_id);
								$stmt->execute();
								$rate = $stmt->fetch(PDO::FETCH_OBJ);
								if($rate){
									$device_type = $rate->device_type;
									$device_token = $rate->device_token;
									$message = "Ride cancelled";
									if($device_type==1){
										if($device_token!=''){
											apns($device_token,$message,$register->ride_id);
										}
									}else{
										send_gcm_notify($device_token, $message,$register->ride_id );
									}
								}
								//if($ride->accept_status==0 || $ride->accept_status==1){
									/// firebase driver
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"ride_status":"5"}';

									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$ride->driver_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								//}
								echo '{ "Result": "Success","Status":"Ride has been canceled"}';
							}else{
								echo '{ "Result": "Failed","Status":"Ride cannot be canceled, ride already started"}';
							}
						}else{
							$stmt = $db->prepare($sql1);
							$stmt->bindParam("ride_id", $register->ride_id);
							$stmt->bindParam("cancel_notes", $register->cancel_notes);
							$stmt->bindParam("cancle_time", $date);
							$stmt->execute();
							
							/// firebase
							$date_fmt = date("d-m-Y");
							$header = array();
							$header[] = 'Content-Type: application/json';
							$postdata = '{"ride_status":"5"}';
							
							$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
							//curl_setopt($ch, CURLOPT_POST,1);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							$result = curl_exec($ch); 
							curl_close($ch); 
							///firebase
							
							echo '{ "Result": "Success","Status":"Ride has been canceled"}';
						}
						
					}else{
						$sql6="update wy_ridedetails set accept_status=3,cancel_notes=:cancel_notes,cancel_time=:cancle_time where ride_id=:ride_id and driver_id=:driver_id";
						$stmt = $db->prepare($sql6);
						$stmt->bindParam("driver_id", $register->driver_id);
						$stmt->bindParam("cancel_notes", $register->cancel_notes);
						$stmt->bindParam("ride_id", $register->ride_id);
						$stmt->bindParam("cancle_time", $date);
						$stmt->execute();
						if($register->status=='1'){
							$stmt = $db->prepare($sql1);
							$stmt->bindParam("ride_id", $register->ride_id);
							$stmt->bindParam("cancel_notes", $register->cancel_notes);
							$stmt->bindParam("cancle_time", $date);
							$stmt->execute();
							$sql3 = "select device_type,device_token from wy_customer where id=:customer_id";
							$stmt = $db->prepare($sql3);
							$stmt->bindParam("customer_id", $ride1->customer_id);
							$stmt->execute();
							$rate = $stmt->fetch(PDO::FETCH_OBJ);
							$device_type = $rate->device_type;
							$device_token = $rate->device_token;
							$message = "Ride cancelled";
							if($device_type==1){
								if($device_token!=''){
									//apns_cus($device_token,$message,$register->ride_id);
								}
							}else{
								send_gcm_notify($device_token, $message,$register->ride_id );
							}
							/// firebase
								$date_fmt = date("d-m-Y");
								$header = array();
								$header[] = 'Content-Type: application/json';
								$postdata = '{"accept_status":"3"}';
								
								$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
								//curl_setopt($ch, CURLOPT_POST,1);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
								curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
								$result = curl_exec($ch); 
								curl_close($ch); 
								///firebase
								
								/// firebase driver
								$date_fmt = date("d-m-Y");
								$header = array();
								$header[] = 'Content-Type: application/json';
								$postdata = '{"accept_status":"3"}';
								
								$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$register->driver_id.json");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
								//curl_setopt($ch, CURLOPT_POST,1);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
								curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
								$result = curl_exec($ch); 
								curl_close($ch); 
								///firebase
						}else{
							$sql3 = "select r.*,c.fb_id,c.device_token,c.device_type,c.mobile from wy_ride r join wy_customer c on r.customer_id=c.id where r.id=:ride_id";
							$stmt = $db->prepare($sql3);
							$stmt->bindParam("ride_id", $register->ride_id);
							$stmt->execute();
							$dev = $stmt->fetch(PDO::FETCH_OBJ);
							$car_board = selectsinglevalue("select car_board as retv from wy_cartype where id='$dev->car_type'");
							$qryy="select d.firstname,d.lastname,ct.*,b.brand,m.model from wy_driver d join wy_ridedetails c on d.id=c.driver_id join wy_assign_taxi a on a.driver_id=d.id join wy_carlist ct on a.car_num=ct.id 
									join wy_model m on m.id=ct.model join wy_brand b on b.id=ct.brand where c.ride_id=:ride_id and c.driver_id=:driver_id and a.status='1' ";
							$stmt = $db->prepare($qryy);
							$stmt->bindParam("ride_id", $register->ride_id);
							$stmt->bindParam("driver_id", $register->driver_id);
							$stmt->execute();
							$drvr = $stmt->fetch(PDO::FETCH_OBJ);
							if($dev){
								$device_token = $dev->device_token;
								$device_type = $dev->device_type;
								$mobile = $dev->mobile;
								
								$message = "Driver($drvr->firstname $drvr->lastname,$drvr->brand $drvr->model $drvr->car_no) is unable to reach you. Another driver will reach you soon.";
								if($device_type==1){
									if($device_token!=''){
										apns_cus($device_token,$message,$register->ride_id);
									}
								}else{
									send_gcm_notify($device_token, $message,$register->ride_id );
								}
								
								/// firebase
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"accept_status":"0","ride_status":"0"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								
								/// firebase driver
								$date_fmt = date("d-m-Y");
								$header = array();
								$header[] = 'Content-Type: application/json';
								
								$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$register->driver_id.json");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
								$result = curl_exec($ch); 
								curl_close($ch); 
								///firebase
								
								/* $message = urlencode($message);
								$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
								curl_setopt($ch, CURLOPT_HEADER, 0);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								$output = curl_exec($ch);      
								curl_close($ch); */
								
								$sql4="select u.id,u.device_type,u.device_token,(
									6371 *
									acos(
										cos( radians(:source_lat) ) *
										cos( radians( d.lat ) ) *
										cos(
											radians( d.lng ) - radians( :source_lng )
										) +
										sin(radians(:source_lat)) *
										sin(radians(d.lat))
									)
								) distance from wy_driver u join wy_assign_taxi a on a.driver_id=u.id join wy_carlist c on a.car_num=c.id 
								join wy_driverlocation d on u.id=d.driver_id 
								where c.car_type=:car_type and u.online_status=1 and a.status=1 
								and u.id not in (select driver_id from wy_ridedetails where  accept_status in ('2','3') and ride_id=:ride_id)
								having 	distance<3	order by distance asc ";
								$stmt = $db->prepare($sql4); 
								$stmt->bindParam("car_type", $dev->car_type);
								$stmt->bindParam("source_lat", $dev->source_lat);
								$stmt->bindParam("source_lng", $dev->source_lng);
								$stmt->bindParam("ride_id", $register->ride_id);
								$stmt->execute();
								$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
								if($drivers){
									foreach($drivers as $val){
										/* $sql3="select * from wy_ridedetails where driver_id='$val->id'";
										$stmt = $db->query($sql3);
										$driverslist = $stmt->fetchAll(PDO::FETCH_OBJ);
										if($driverslist){ */
											$sql6="select driver_id from wy_ridedetails where driver_id=:id and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
													ORDER BY `wy_ridedetails`.`id` ASC";
											$stmt = $db->prepare($sql6);
											$stmt->bindParam("id", $val->id);
											$stmt->execute();
											$driverslistdet = $stmt->fetchAll(PDO::FETCH_OBJ);
											if(!$driverslistdet){
												$fail = 0;
												$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values(:ride_id,:id,:created_at,:updated_at)";
												$stmt = $db->prepare($sql5);
												$stmt->bindParam("id", $val->id);
												$stmt->bindParam("ride_id", $register->ride_id);
												$stmt->bindParam("created_at", $date);
												$stmt->bindParam("updated_at", $date);
												$stmt->execute();
												$device_type = $val->device_type;
												$device_token = $val->device_token;
												$message = "request for ride";
												if($device_type==1){
													if($device_token!=''){
														apns($device_token,$message,$register->ride_id);
													}
												}else{
													send_gcm_notify($device_token, $message,$register->ride_id );
												}
												$sql6="update wy_driver set online_status=0 where id=:id";
												$stmt = $db->prepare($sql6);
												$stmt->bindParam("id", $val->id);
												$stmt->execute();
												//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
												
												/// firebase
												$date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"accept_status":"0","ride_status":"0"}';
												
												$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
												
												/// firebase driver
												$date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"request_time":"'.$date.'","ride_id":"'.$register->ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev->car_type.'","reference_id":"'.$dev->reference_id.'","customer_id":"'.$dev->customer_id.'","source_location":"'.$dev->source_location.'","source_lat":"'.$dev->source_lat.'","source_lng":"'.$dev->source_lng.'","ride_type":"'.$dev->ride_type.'","ride_category":"'.$dev->ride_category.'","booking_type":"'.$dev->booking_type.'","destination_location":"'.$dev->destination_location.'","destination_lat":"'.$dev->destination_lat.'","destination_lng":"'.$dev->destination_lng.'"}';

												
												$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$val->id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
												
												break;
											}else{
												$fail = 1;
											}
										/* }else{
											$fail = 0;
											$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$register->ride_id','$val->id','$date','$date')";
											$stmt = $db->query($sql5);
											$device_type = $val->device_type;
											$device_token = $val->device_token;
											$message = "request for ride";
											if($device_type==1){
												if($device_token!=''){
													apns($device_token,$message,$register->ride_id);
												}
											}else{
												//$message = array("message" => $message);
												//$reg_id = array($device_token);
												send_gcm_notify($device_token, $message,$register->ride_id );
											}
											//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
											if($dev->booked_through=='1'){
											/// firebase
												$date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"accept_status":"0","ride_status":"0"}';
												
												$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												//curl_setopt($ch, CURLOPT_POST,1);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
											}
												/// firebase driver
												$date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"request_time":"'.$date.'","ride_id":"'.$register->ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev->car_type.'","reference_id":"'.$dev->reference_id.'","customer_id":"'.$dev->user_id.'","source_location":"'.$dev->source_location.'","source_lat":"'.$dev->source_lat.'","source_lng":"'.$dev->source_lng.'","ride_type":"'.$dev->ride_type.'","ride_category":"'.$dev->ride_category.'","booking_type":"'.$dev->booking_type.'","destination_location":"'.$dev->destination_location.'","destination_lat":"'.$dev->destination_lat.'","destination_lng":"'.$dev->destination_lng.'"}';
												
												$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$val->id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												//curl_setopt($ch, CURLOPT_POST,1);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
												
											if($dev->booked_through=='2'){
												/// firebase
												$date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"driver_fname":"'.$val->firstname.'","driver_lname":"'.$val->lastname.'","driver_email":"'.$val->email.'","driver_mobile":"'.$val->mobile.'","driver_mobile":"'.$val->mobile.'","accept_status":"0","ride_status":"0"}';
												
												$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/dispatch/$dev->booked_by/$date_fmt/$register->ride_id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												//curl_setopt($ch, CURLOPT_POST,1);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
											}
											break;
										} */
									}
								}else{
									$fail = 1;
								}
								if($fail==1){
									$upqry="update wy_ride set ride_status=3 where id=:ride_id";
									$stmt = $db->prepare($upqry);
									$stmt->bindParam("ride_id", $register->ride_id);
									$stmt->execute();
									/// firebase
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"car_availability":"3"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$register->ride_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								}
							}
						}
						echo '{ "Result": "Success","Status":"Ride has been canceled"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function rate_estimate(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$time=date('H:i:s');
	$sql = "select ride_start_time,ride_end_time,nit_start_time,nit_end_time,fare_percent from wy_faredetails where car_id=:car_type and booking_type=:booking_type and status='1' order by fare_type desc";
	$sql2 = "select * from wy_faredetails where car_id=:car_type and booking_type=:booking_type and status='1' and fare_type=1";
	$sql1 = "select sum(percentage) as cntt from wy_tax where status=1";
	$amount=0;
	$ridetim=0;
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				//$km = distanceCalculation($register->source_lat, $register->source_lng, $register->dest_lat, $register->dest_lng); // Calculate distance in kilometres (default)
				$q = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$register->source_lat,$register->source_lng&destinations=$register->dest_lat,$register->dest_lng&mode=driving&sensor=false";
				$json = file_get_contents($q);
				$details = json_decode($json, TRUE); //print_r($details); exit;
				$duration = $details['rows'][0]['elements'][0]['duration']['text'];
				$durationv = $details['rows'][0]['elements'][0]['duration']['value'];
				$distance = $details['rows'][0]['elements'][0]['distance']['text'];
				$distancev = $details['rows'][0]['elements'][0]['distance']['value'];
				$dist = explode(" ",$distance);
				//******************
				$stmt = $db->prepare($sql); 
				$stmt->bindParam("car_type", $register->car_type);
				$stmt->bindParam("booking_type", $register->booking_type);
				$stmt->execute();
				$rate = $stmt->fetchAll(PDO::FETCH_OBJ);
				
				$stmt = $db->prepare($sql2); 
				$stmt->bindParam("car_type", $register->car_type);
				$stmt->bindParam("booking_type", $register->booking_type);
				$stmt->execute();
				$rate1 = $stmt->fetch(PDO::FETCH_OBJ);
				$peak=0;
				//if($distancev!=0){
					foreach($rate as $val){
						//mor time
						$start_time = $val->ride_start_time;
						$end_time = $val->ride_end_time;
						$srt_datetime = $date1." ".$start_time;
						$srt_datetime = date("Y-m-d H:i:s",strtotime($srt_datetime));
						if(strtotime($start_time)>strtotime($end_time)){
							$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
							$end_datetime = $date1." ".$end_time;
							$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
						}else{
							$end_datetime = $date1." ".$end_time;
							$end_datetime = date("Y-m-d H:i:s",strtotime($end_datetime));
						} 
						//nit time
						$nstart_time = $val->nit_start_time;
						$nend_time = $val->nit_end_time;
						$nsrt_datetime = $date1." ".$nstart_time;
						$nsrt_datetime = date("Y-m-d H:i:s",strtotime($nsrt_datetime));
						if(strtotime($nstart_time)>strtotime($nend_time)){
							$date1=date('Y-m-d', strtotime($date1 . ' +1 day')); 
							$nend_datetime = $date1." ".$nend_time;
							$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
						}else{
							$nend_datetime = $date1." ".$nend_time;
							$nend_datetime = date("Y-m-d H:i:s",strtotime($nend_datetime));
						} 
						if((strtotime($date)>=strtotime($srt_datetime) && strtotime($date)<strtotime($end_datetime)) || (strtotime($date)>=strtotime($nsrt_datetime) && strtotime($date)<strtotime($nend_datetime))){
							$peak = $val->fare_percent;
							break;
						}
					}
					if($dist[0]<=$rate1->min_km){
						$amount = $rate1->min_fare_amount;
					}else{
						$km = $dist[0] - $rate1->min_km;
						$amount1 = $rate1->min_fare_amount;
						$amount2 = $km*$rate1->ride_fare;
						$amount = $amount1 + $amount2;
					}
					$ridetim = ($rate1->distance_fare*$durationv*$rate1->distance_time)/60;
				/* }else{
					$amount=0;
					$ridetim=0;
				} */
				$stmt = $db->prepare($sql1); 
				$stmt->execute();
				$taxs = $stmt->fetch(PDO::FETCH_OBJ);
				$taxes = $taxs->cntt;
				
				$tax = $amount*$taxes/100;
				$amt = round($amount+$tax+$ridetim);
				if($peak>0){
					$pkamt = $amt*($peak/100);
					$amt = round($pkamt+$amt);
				}
				$details = array(
					"distance" => $distance,
					"duration" => $duration,
					"amount" => $amt
				);
				echo '{ "Result": "Success","details":'.json_encode($details).'}';
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function ratecard($car_type,$booking_type){
	$request = Slim::getInstance()->request();
	$date = date("Y-m-d");
	$time = date("H:i:s");
	$sql="select min_fare_amount,ride_fare,distance_fare from wy_faredetails where car_id=:car_type AND booking_type = :booking_type and fare_type='1' and status='1' order by fare_type desc";
	$sql1="select distinct(CONCAT(b.brand,' ',m.model)) as brand from wy_carlist c join wy_brand b on c.brand=b.id join wy_model m on c.model=m.id where c.car_type=:car_type";
	$sql2="select yellow_caricon from wy_cartype where id=:car_type";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db=getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("booking_type", $booking_type);
				$stmt->bindParam("car_type", $car_type);
				$stmt->execute();
				$rate = $stmt->fetch(PDO::FETCH_OBJ);
				if($rate){
					if($booking_type==1){
						$getpeak = "select * from wy_faredetails where booking_type=:booking_type and fare_type in ('2,3,4,5') and status='1' and car_id=:car_type";
						$stmt = $db->prepare($getpeak);
						$stmt->bindParam("booking_type", $booking_type);
						$stmt->bindParam("car_type", $car_type);
						$stmt->execute();
						$getpeakdet = $stmt->fetch(PDO::FETCH_OBJ);
						if($getpeakdet){
							$msg = "Special charges will be applicable";
						}else{
							$msg = "";
						}
					}else{
						$msg = "";
					}
					$stmt = $db->prepare($sql2);
					$stmt->bindParam("car_type", $car_type);
					$stmt->execute();
					$carimg = $stmt->fetch(PDO::FETCH_OBJ);
					$stmt = $db->prepare($sql1);
					$stmt->bindParam("car_type", $car_type);
					$stmt->execute();
					$brand = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($brand){
						foreach($brand as $value){
							$cardetls[] = $value->brand;
						}
						$cardet = implode(',',$cardetls);
					}else{
						$cardet='';
					}
					
					$ratedet[] = array(
						"label_key" => "Base fare",
						"value" => $rate->min_fare_amount,
					);
					$ratedet[] = array(
						"label_key" => "Rate/km",
						"value" => $rate->ride_fare,
					);
					$ratedet[] = array(
						"label_key" => "Ride rate/min",
						"value" => $rate->distance_fare,
					);
					
					echo '{ "Result": "Success","details":'.json_encode($ratedet).',"cars":'.json_encode($cardet).',"car_img":'.json_encode($carimg->yellow_caricon).',"peaktime":'.json_encode($msg).'}';
				}else{
					echo '{"Result":"Failed","Status":"Rate not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function onlinestatus($driver_id,$status){
		$request = Slim::getInstance()->request();
		$date = date("Y-m-d");
		$sql="select id from wy_driver where id=:driver_id";
		$sql3 = "select r.*,d.ride_id from wy_ridedetails d join wy_ride r on r.id=d.ride_id WHERE d.driver_id = :driver_id and d.accept_status=0 and d.ride_status=0";
		try{
			$headers = $request->headers();  //print_r($headers); exit;
			if(isset($headers['authorization'])){
				$auth_token = $headers['authorization']; 
				$auth_id = $headers['auth-userid'];
				$chk = check_authtoken($auth_token,$auth_id,2);
				if($chk=='1'){
					$db=getConnection();
					$stmt = $db->prepare($sql);
					$stmt->bindParam("driver_id", $driver_id);
					$stmt->execute();
					$car = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($car){
						$status = intval($status);
						$sql2 = "update wy_driver set online_status=:status  WHERE id =:driver_id ";
						$stmt = $db->prepare($sql2);
						$stmt->bindParam("status", $status);
						$stmt->bindParam("driver_id", $driver_id);
						$stmt->execute();
						$stmt = $db->prepare($sql3);
						$stmt->bindParam("driver_id", $driver_id);
						$stmt->execute();
						$rid = $stmt->fetch(PDO::FETCH_OBJ);
						if($rid){
							if($status==0){
								$car_board = selectsinglevalue("select car_board as retv from wy_cartype where id='$rid->car_type'");
								$sql4 = "update wy_ridedetails set accept_status=2 WHERE driver_id = :driver_id and ride_id=:ride_id";
								$stmt = $db->prepare($sql4);
								$stmt->bindParam("ride_id", $rid->ride_id);
								$stmt->bindParam("driver_id", $driver_id);
								$stmt->execute();
								/// firebase driver for delete			
								$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$driver_id.json");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
								$result = curl_exec($ch); 
								curl_close($ch); 
								///firebase
					
								$qry="select u.id,u.device_type,u.device_token,(6371*acos(cos(radians(:source_lat))*cos(radians(d.lat))*cos(radians(d.lng)-radians(:source_lng))+sin(radians(:source_lat))*sin(radians(d.lat)))) distance from wy_driver u 
									join wy_assign_taxi a on a.driver_id=u.id join wy_carlist c on a.car_num=c.id join wy_driverlocation d on u.id=d.driver_id 
									where c.car_type=:car_type and a.status=1  and u.id not in (select driver_id from wy_ridedetails where  accept_status in  ('2','3') and ride_id=:ride_id) and u.online_status=1
									having 	distance<3 order by distance asc ";
								$stmt = $db->prepare($qry); 
								$stmt->bindParam("source_lat", $rid->source_lat);
								$stmt->bindParam("source_lng", $rid->source_lng);
								$stmt->bindParam("car_type", $rid->car_type);
								$stmt->bindParam("ride_id", $rid->ride_id);
								$stmt->execute();
								$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
								if($drivers){
									foreach($drivers as $val){
										/* $sql3="select * from wy_ridedetails where driver_id='$val->id'";
										$stmt = $db->query($sql3);
										$driverslist = $stmt->fetchAll(PDO::FETCH_OBJ);
										if($driverslist){ */
											$sql6="select driver_id from wy_ridedetails where driver_id=:id and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
													ORDER BY `wy_ridedetails`.`id` ASC";
											$stmt = $db->prepare($sql6);
											$stmt->bindParam("id", $val->id);
											$stmt->execute();
											$driverslistdet = $stmt->fetchAll(PDO::FETCH_OBJ);
											if(!$driverslistdet){
												$fail = 0;
												$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values(:ride_id,:id,:created_at,:updated_at)";
												$stmt = $db->prepare($sql5);
												$stmt->bindParam("ride_id", $rid->ride_id);
												$stmt->bindParam("id", $val->id);
												$stmt->bindParam("created_at", $date);
												$stmt->bindParam("updated_at", $date);
												$stmt->execute();
												$device_type = $val->device_type;
												$device_token = $val->device_token;
												$message = "request for ride";
												if($device_type==1){
													if($device_token!=''){
														apns($device_token,$message,$rid->ride_id);
													}
												}else{
													send_gcm_notify($device_token, $message,$rid->ride_id );
												}
												$sql6="update wy_driver set online_status=0 where id=:id";
												$stmt = $db->prepare($sql6);
												$stmt->bindParam("id", $val->id);
												$stmt->execute();
												/// firebase
												$date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"accept_status":"0","ride_status":"0"}';
												
												$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$rid->ride_id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												//curl_setopt($ch, CURLOPT_POST,1);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
												
												/// firebase driver
												$date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"request_time":"'.$date.'","ride_id":"'.$rid->ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$rid->car_type.'","reference_id":"'.$rid->reference_id.'","customer_id":"'.$rid->customer_id.'","source_location":"'.$rid->source_location.'","source_lat":"'.$rid->source_lat.'","source_lng":"'.$rid->source_lng.'","ride_type":"'.$rid->ride_type.'","ride_category":"'.$rid->ride_category.'","booking_type":"'.$rid->booking_type.'","destination_location":"'.$rid->destination_location.'","destination_lat":"'.$rid->destination_lat.'","destination_lng":"'.$rid->destination_lng.'"}';
												
												$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$val->id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												//curl_setopt($ch, CURLOPT_POST,1);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
													
												break;
											}else{
												$fail = 1;
											}
										/* }else{
											$fail = 0;
											$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$rid->ride_id','$val->id','$date','$date')";
											$stmt = $db->query($sql5);
											$device_type = $val->device_type;
											$device_token = $val->device_token;
											$message = "request for ride";
											if($device_type==1){
												if($device_token!=''){
													apns($device_token,$message,$rid->ride_id);
												}
											}else{
												send_gcm_notify($device_token, $message,$rid->ride_id );
											}
											
											/// firebase
											$date_fmt = date("d-m-Y");
											$header = array();
											$header[] = 'Content-Type: application/json';
											$postdata = '{"accept_status":"0","ride_status":"0"}';
											
											$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$rid->ride_id.json");
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
											curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
											//curl_setopt($ch, CURLOPT_POST,1);
											curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
											curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
											$result = curl_exec($ch); 
											curl_close($ch); 
											///firebase
											
											/// firebase driver
											$date_fmt = date("d-m-Y");
											$header = array();
											$header[] = 'Content-Type: application/json';
											$postdata = '{"ride_id":"'.$rid->ride_id.'","accept_status":"0","ride_status":"0"}';
											
											$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$val->id.json");
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
											curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
											//curl_setopt($ch, CURLOPT_POST,1);
											curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
											curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
											$result = curl_exec($ch); 
											curl_close($ch); 
											///firebase
											
											break;
										} */
									}
								}else{
									$fail = 1;
								}
								if($fail ==1){
									$upqry="update wy_ride set ride_status=3 where id=:ride_id";
									$stmt = $db->prepare($upqry);
									$stmt->bindParam("ride_id", $rid->ride_id);
									$stmt->execute();
									/// firebase
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"accept_status":"","ride_status":"","car_availability":"3"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$rid->ride_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								}
							}
						}
						echo '{ "Result": "Success","Status":"status changed successfully"}';
					}else{
						echo '{"Result":"Failed","Status":"Driver not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Invalid authentication"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Authentication required"}';
			}
		}catch(PDOException $e) {
			echo $e;
			echo '{ "Result": "Failed"}'; 
		}
}

function process_requests($ride_id,$driver_id,$status){
		$request = Slim::getInstance()->request();
		$date = date("Y-m-d H:i:s");
		$time = date("H:i:s");
		$sql="select d.*,b.brand,m.model,c.car_no as reg_no,ct.yellow_caricon from wy_driver d join wy_assign_taxi a on a.driver_id=d.id 
				join wy_carlist c on c.id=a.car_num join wy_brand b on b.id=c.brand
				join wy_model m on m.id=c.model 
				join wy_cartype ct on ct.id=c.car_type where d.id=:driver_id and a.status='1' ";
		$sql1="select car_type,booking_type from wy_ride where id=:ride_id and ride_status not in ('2','4','3')";
		$fail = 0;
		try{
			$headers = $request->headers();  //print_r($headers); exit;
			if(isset($headers['authorization'])){
				$auth_token = $headers['authorization']; 
				$auth_id = $headers['auth-userid'];
				$chk = check_authtoken($auth_token,$auth_id,2);
				if($chk=='1'){
					$db=getConnection();
					$stmt = $db->prepare($sql);
					$stmt->bindParam("driver_id", $driver_id);
					$stmt->execute();
					$drvr = $stmt->fetch(PDO::FETCH_OBJ);
					if($drvr){
						$stmt = $db->prepare($sql1);
						$stmt->bindParam("ride_id", $ride_id);
						$stmt->execute();
						$rid = $stmt->fetch(PDO::FETCH_OBJ);
						if($rid){
							$cartype=$rid->car_type;
							$booking_type=$rid->booking_type;
							if($status=='Accept'){
								$sql2="update wy_ridedetails set accept_status=1,accept_time=:accept_time,ride_status=1,updated_at=:updated_at where driver_id=:driver_id and ride_id=:ride_id";
								$stmt = $db->prepare($sql2);
								$stmt->bindParam("ride_id", $ride_id);
								$stmt->bindParam("driver_id", $driver_id);
								$stmt->bindParam("accept_time", $date);
								$stmt->bindParam("updated_at", $date);
								$stmt->execute();
								$sql2="update wy_ride set ride_status=1,updated_at=:updated_at where id=:ride_id";
								$stmt = $db->prepare($sql2);
								$stmt->bindParam("ride_id", $ride_id);
								$stmt->bindParam("updated_at", $date);
								$stmt->execute();
								if($booking_type!=1){
									$sql4="update wy_ridedetails set accept_status=2,updated_at=:updated_at where id=:ride_id and driver_id=:driver_id";
									$stmt = $db->prepare($sql4);
									$stmt->bindParam("driver_id", $driver_id);
									$stmt->bindParam("ride_id", $ride_id);
									$stmt->bindParam("updated_at", $date);
									$stmt->execute();
								}
								$sql3 = "select r.crn,r.id,r.booked_through,r.booked_by,r.source_location,r.source_lat,r.source_lng,r.destination_location,r.destination_lat,r.destination_lng,c.name,r.customer_id,c.mobile,c.device_type,c.device_token,c.fb_id from wy_ride r join wy_customer c on c.id=r.customer_id where r.id=:ride_id";
								$stmt = $db->prepare($sql3);
								$stmt->bindParam("ride_id", $ride_id);
								$stmt->execute();
								$dev = $stmt->fetchAll(PDO::FETCH_OBJ);
								if($dev){
									foreach($dev as $vall)
									$device_type = $vall->device_type;
									$device_token = $vall->device_token;
									$message   =  "$drvr->firstname $drvr->lastname is on the way to your Location in a $drvr->brand $drvr->model $drvr->reg_no . Your CRN Number is $vall->crn";
									/* $mobile = $vall->mobile;
									$message = urlencode($message);
									$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
									curl_setopt($ch, CURLOPT_HEADER, 0);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									$output = curl_exec($ch);      
									curl_close($ch);  */
									$message1 = "Share this OTP  $vall->crn with driver to start ride. $drvr->firstname $drvr->lastname is on the way to your Location in a $drvr->brand $drvr->model $drvr->reg_no. ";
									if($device_type==1){
										if($device_token!=''){
											apns_cus($device_token,$message1,$ride_id);
										}
									}else{
										send_gcm_notify($device_token,$message1,$ride_id);
									}
									echo '{ "Result": "Success","details":'.json_encode($dev).'}';
									
									/// firebase
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"profile_photo":"'.$drvr->profile_photo.'","mobile":"'.$drvr->mobile.'","black_caricon":"'.$drvr->yellow_caricon.'","name":"'.$drvr->firstname.' '.$drvr->lastname.'","brand":"'.$drvr->brand.'","model":"'.$drvr->model.'","reg_no":"'.$drvr->reg_no.'","accept_status":"1","ride_status":"1"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									
									/// firebase driver
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"accept_status":"1","ride_status":"1"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$drvr->id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
									
								}else{
									echo '{"Result":"Failed","Status":"No request found"}';
								}
							}else{
								$sql2="update wy_ridedetails set accept_status=2,updated_at=:updated_at where driver_id=:driver_id and ride_id=:ride_id";
								$stmt = $db->prepare($sql2);
								$stmt->bindParam("driver_id", $driver_id);
								$stmt->bindParam("ride_id", $ride_id);
								$stmt->bindParam("updated_at", $date);
								$stmt->execute();
								/// firebase driver for delete
								$date_fmt = date("d-m-Y");
								$header = array();
								$header[] = 'Content-Type: application/json';
								
								$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$driver_id.json");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
								$result = curl_exec($ch); 
								curl_close($ch); 
								///firebase
								if($booking_type==1){		
									$sql3 = "select * from wy_ride where id=:ride_id and ride_status=0";
									$stmt = $db->prepare($sql3);
									$stmt->bindParam("ride_id", $ride_id);
									$stmt->execute();
									$dev = $stmt->fetch(PDO::FETCH_OBJ);
									if($dev){
										$qry="select u.*,(
												6371 *
												acos(
													cos( radians( :source_lat ) ) *
													cos( radians( d.lat ) ) *
													cos(
														radians( d.lng ) - radians( :source_lng )
													) +
													sin(radians(:source_lat)) *
													sin(radians(d.lat))
												)
											) distance from wy_driver u  join wy_assign_taxi a on a.driver_id=u.id join wy_carlist c on a.car_num=c.id 
											join wy_driverlocation d on u.id=d.driver_id 
											where c.car_type=:car_type and u.id not in (select driver_id from wy_ridedetails where  accept_status in  ('2','3') and ride_id=:ride_id) and u.online_status=1 and a.status=1 
											having 	distance<3 order by distance asc ";
										$stmt = $db->prepare($qry); 
										$stmt->bindParam("source_lat", $dev->source_lat);
										$stmt->bindParam("source_lng", $dev->source_lng);
										$stmt->bindParam("car_type", $dev->car_type);
										$stmt->bindParam("ride_id", $ride_id);
										$stmt->execute();
										$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
										$car_board = selectsinglevalue("select car_board as retv from wy_cartype where id='$dev->car_type'");
										if($drivers){
											foreach($drivers as $val){
												/* $sql3="select * from wy_ridedetails where driver_id='$val->id'";
												$stmt = $db->query($sql3);
												$driverslist = $stmt->fetchAll(PDO::FETCH_OBJ);
												if($driverslist){ */
													$sql6="select driver_id from wy_ridedetails where driver_id=:id and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
															ORDER BY `wy_ridedetails`.`id` ASC";
													$stmt = $db->prepare($sql6);
													$stmt->bindParam("id", $val->id);
													$stmt->execute();
													$driverslistdet = $stmt->fetchAll(PDO::FETCH_OBJ);
													if(!$driverslistdet){
														$fail = 0;
														$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values(:ride_id,:id,:updated_at,:updated_at)";
														$stmt = $db->prepare($sql5);
														$stmt->bindParam("id", $val->id);
														$stmt->bindParam("ride_id", $ride_id);
														$stmt->bindParam("created_at", $date);
														$stmt->bindParam("updated_at", $date);
														$stmt->execute();
														$device_type = $val->device_type;
														$device_token = $val->device_token;
														$message = "request for ride";
														if($device_type==1){
															if($device_token!=''){
																apns($device_token,$message,$ride_id);
															}
														}else{
															send_gcm_notify($device_token, $message,$ride_id );
														}
														$sql6="update wy_driver set online_status=0 where id=:id";
														$stmt = $db->prepare($sql6);
														$stmt->bindParam("id", $val->id);
														$stmt->execute();
														/// firebase
														$date_fmt = date("d-m-Y");
														$header = array();
														$header[] = 'Content-Type: application/json';
														$postdata = '{"accept_status":"0","ride_status":"0"}';
														
														$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
														curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
														curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
														curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
														$result = curl_exec($ch); 
														curl_close($ch); 
														///firebase
															
														/// firebase driver
														$date_fmt = date("d-m-Y");
														$header = array();
														$header[] = 'Content-Type: application/json';
														$postdata = '{"request_time":"'.$date.'","ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev->car_type.'","reference_id":"'.$dev->reference_id.'","customer_id":"'.$dev->customer_id.'","source_location":"'.$dev->source_location.'","source_lat":"'.$dev->source_lat.'","source_lng":"'.$dev->source_lng.'","ride_type":"'.$dev->ride_type.'","ride_category":"'.$dev->ride_category.'","booking_type":"'.$dev->booking_type.'","destination_location":"'.$dev->destination_location.'","destination_lat":"'.$dev->destination_lat.'","destination_lng":"'.$dev->destination_lng.'"}';
														
														$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$val->id.json");
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
														curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
														curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
														curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
														$result = curl_exec($ch); 
														curl_close($ch); 
														///firebase
																
														break;
													}else{
														$fail = 1;
													}
												/* }else{
													$fail = 0;
													$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$val->id','$date','$date')";
													$stmt = $db->query($sql5);
													$device_type = $val->device_type;
													$device_token = $val->device_token;
													$message = "request for ride";
													if($device_type==1){
														if($device_token!=''){
															apns($device_token,$message,$ride_id);
														}
													}else{
														//$message = array("message" => $message);
														//$reg_id = array($device_token);
														send_gcm_notify($device_token, $message,$ride_id );
													}
													//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
													
													/// firebase
														$date_fmt = date("d-m-Y");
														$header = array();
														$header[] = 'Content-Type: application/json';
														$postdata = '{"accept_status":"0","ride_status":"0"}';
														
														$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
														curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
														//curl_setopt($ch, CURLOPT_POST,1);
														curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
														curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
														$result = curl_exec($ch); 
														curl_close($ch); 
														///firebase
													
														/// firebase driver
														$date_fmt = date("d-m-Y");
														$header = array();
														$header[] = 'Content-Type: application/json';
														$postdata = '{"request_time":"'.$date.'","ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev->car_type.'","reference_id":"'.$dev->reference_id.'","customer_id":"'.$dev->customer_id.'","source_location":"'.$dev->source_location.'","source_lat":"'.$dev->source_lat.'","source_lng":"'.$dev->source_lng.'","ride_type":"'.$dev->ride_type.'","ride_category":"'.$dev->ride_category.'","booking_type":"'.$dev->booking_type.'","destination_location":"'.$dev->destination_location.'","destination_lat":"'.$dev->destination_lat.'","destination_lng":"'.$dev->destination_lng.'"}';
														
														$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$val->id.json");
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
														curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
														//curl_setopt($ch, CURLOPT_POST,1);
														curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
														curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
														$result = curl_exec($ch); 
														curl_close($ch); 
														///firebase
													
													break;
												} */
											}
										}else{
											$fail = 1;
										}
									}
								}else{
									$cntqry="select ride_id as cnt from wy_ridedetails where ride_id='$ride_id' and accept_status in ('0','1')";
									$stmt = $db->prepare($cntqry);
									$stmt->bindParam("ride_id", $ride_id);
									$stmt->execute();
									$cntdet = $stmt->fetchAll(PDO::FETCH_OBJ);
									if(!$cntdet){
										$fail = 1;
									}
								}
								if($fail > 0){
									$upqry="update wy_ride set ride_status='3' where id='$ride_id'";
									$stmt = $db->prepare($upqry);
									$stmt->bindParam("ride_id", $ride_id);
									$stmt->execute();
									/// firebase
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"accept_status":"","ride_status":"","car_availability":"3"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								}
								echo '{ "Result": "Success","Status":"You denied the request"}';
							}
						}else{
							echo '{"Result":"Failed","Status":"Ride not found"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Driver not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Invalid authentication"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Authentication required"}';
			}
		}catch(PDOException $e) {
			echo $e;
			echo '{ "Result": "Failed"}'; 
		}
}

function get_requests($driver_id){
		$request = Slim::getInstance()->request();
		$date = date("Y-m-d");
		$sql="select id from wy_driver where id=:driver_id";
		$sql2="select r.*,ct.car_type,ct.car_board,rd.driver_id,rd.accept_status,rd.ride_status as driverride_status,rd.accept_time,rd.created_at as request_time from wy_ridedetails rd join wy_ride r on r.id=rd.ride_id 
			   join wy_cartype ct ON ct.id = r.car_type where rd.driver_id=:driver_id and rd.accept_status='0' and rd.ride_status='0'";
		try{
			$headers = $request->headers();  //print_r($headers); exit;
			if(isset($headers['authorization'])){
				$auth_token = $headers['authorization'];
				$auth_id = $headers['auth-userid'];			
				$chk = check_authtoken($auth_token,$auth_id,2);
				if($chk=='1'){
					$db=getConnection();
					$stmt = $db->prepare($sql);
					$stmt->bindParam("driver_id", $driver_id);
					$stmt->execute();
					$car = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($car){
						$stmt = $db->prepare($sql2);
						$stmt->bindParam("driver_id", $driver_id);
						$stmt->execute();
						$dev = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($dev){
							echo '{ "Result": "Success","details":'.json_encode($dev).'}';
						}else{
							echo '{"Result":"Failed","Status":"No request found"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Driver not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Invalid authentication"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Authentication required"}';
			}
		}catch(PDOException $e) {
			
			echo '{ "Result": "Failed"}'; 
		}
}

function resend_otp_user($userid){
		$date = date("Y-m-d H:i:s");
		$sql="select * from wy_customer where id=:userid";
		$request = Slim::getInstance()->request();
		try{
			$headers = $request->headers();  //print_r($headers); exit;
			if(isset($headers['authorization'])){
				$auth_token = $headers['authorization']; 
				$auth_id = $headers['auth-userid'];
				$chk = check_authtoken($auth_token,$auth_id,1);
				if($chk=='1'){
					$db=getConnection();
					$stmt = $db->prepare($sql);
					$stmt->bindParam("userid", $userid);
					$stmt->execute();
					$car = $stmt->fetch(PDO::FETCH_OBJ);
					if($car){
						
						if(($car->mobile=='+919965524724') || ($car->mobile=='9965524724')){
							$verification_code="1234";
						}else{
							$verification_code=rand(1111, 9999);
							// if($verification_code=="1234")
							// $verification_code=rand(1111, 9999);
						}
						$sql1="update wy_customer set OTP=:verification_code,verification_status=0, otp_time=:otp_time where id=:userid";
						$stmt = $db->prepare($sql1);
						$stmt->bindParam("verification_code", $verification_code);
						$stmt->bindParam("otp_time", $date);
						$stmt->bindParam("userid", $userid);
						$stmt->execute();
						$message   =  "Verification code from MobyCabs: $verification_code";
						$message = urlencode($message);
						$mobile   =  $car->mobile;
						$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);      
						curl_close($ch); 
						
						echo '{ "Result": "Success","Status":"Verfication code sent"}';
						
					}else{
						echo '{"Result":"Failed","Status":"User not found"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Invalid authentication"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Authentication required"}';
			}
		}catch(PDOException $e) {
			
			echo '{ "Result": "Failed"}'; 
		}
}

function get_car(){
		$date = date("Y-m-d");
		$sql="select id,car_no from wy_carlist where id not in (select car_id from wy_dailydrive where status='1')";
		//$sql1="select * from wy_dailydrive where status='1' and date(login_date)='$date'";
		try{
			$db=getConnection();
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$car = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($car){
				
				echo '{ "Result": "Success","Status":"car list found","details": ' . json_encode($car) . '}';
			}else{
				echo '{"Result":"Failed","Status":"Car not found"}';
			}
		}catch(PDOException $e) {
			
			echo '{ "Result": "Failed"}'; 
		}
}
	
function conformbooking(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$sql1="select id,mobile from wy_customer where id=:user_id";
	$sql2="select u.*,ct.car_board,ct.car_type,(6371*acos(cos(radians(:lat))*cos( radians(d.lat))*cos(radians(d.lng)-radians(:lng))+sin(radians(:lat))*sin(radians(d.lat)))) distance from wy_driver u 
			join wy_assign_taxi a on a.driver_id=u.id join wy_carlist c on a.car_num=c.id 
			join wy_driverlocation d on u.id=d.driver_id 
			join wy_cartype ct on ct.id=c.car_type 
			where c.car_type=:car_type and c.is_booking_basedon like '%$register->booking_type%' and a.status=1 and u.online_status=1 having distance<3 order by distance asc ";
	if($register->ride_category==1) $cat = "cabs";
	else if($register->ride_category==2) $cat = "auto";
	else  $cat = "auto";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$crn = rand ( 1000 , 9999 );
				$db = getConnection();
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_OBJ);
				/* $car_board = selectsinglevalue("select car_board as retv from wy_cartype where id='$register->car_type'");
				$car_type = selectsinglevalue("select car_type as retv from wy_cartype where id='$register->car_type'"); */
				if($user){
					$sql3=selectsinglevalue("select id as retv from wy_ride order by id desc limit 1");
					if($sql3!=''){
						$ordid = $sql3+1;
						$ref_id = "WYDSTXCBE00".$ordid;
					}else{
						$ref_id = "WYDSTXCBE001";
					}
					if($register->coupon_code!='')
						$offer_id = selectsinglevalue("select id as retv from wy_offers where coupon_code='$register->coupon_code'");
					else
						$offer_id = 0;
					if($register->ride_type=='1'){
						$qry = "select rd.driver_id from wy_ride r join wy_ridedetails rd on r.id=rd.ride_id where r.customer_id=:user_id and (rd.accept_status in ('0','1') and rd.ride_status in ('0','1','2','3'))";
						$stmt = $db->prepare($qry);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->execute();
						$ridelist = $stmt->fetchAll(PDO::FETCH_OBJ);
						if(!$ridelist){
							$stmt = $db->prepare($sql2); 
							$stmt->bindParam("car_type", $register->car_type);
							$stmt->bindParam("city", $register->city);
							$stmt->bindParam("lat", $register->lat);
							$stmt->bindParam("lng", $register->lng);
							$stmt->execute();
							$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
							if($drivers){
								$sql4="insert into wy_ride(offer_id,coupon_code,ride_category,booking_type,city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at,crn) 
												values(:offer_id,:coupon_code,:ride_category,:booking_type,:city,:ref_id,:user_id,:location,:lat,:lng,:car_type,:ride_type,:date_of_ride,:destination_location,:destination_lat,:destination_lng,:created_at,:updated_at,:crn)";
								$stmt = $db->prepare($sql4);
								$stmt->bindParam("offer_id", $offer_id);
								$stmt->bindParam("coupon_code", $register->coupon_code);
								$stmt->bindParam("ride_category", $register->ride_category);
								$stmt->bindParam("booking_type", $register->booking_type);
								$stmt->bindParam("city", $register->city);
								$stmt->bindParam("ref_id", $ref_id);
								$stmt->bindParam("user_id", $register->user_id);
								$stmt->bindParam("location", $register->location);
								$stmt->bindParam("lat", $register->lat);
								$stmt->bindParam("lng", $register->lng);
								$stmt->bindParam("car_type", $register->car_type);
								$stmt->bindParam("ride_type", $register->ride_type);
								$stmt->bindParam("date_of_ride", $date1);
								$stmt->bindParam("destination_location", $register->destination_location);
								$stmt->bindParam("destination_lat", $register->destination_lat);
								$stmt->bindParam("destination_lng", $register->destination_lng);
								$stmt->bindParam("created_at", $date);
								$stmt->bindParam("updated_at", $date);
								$stmt->bindParam("crn", $crn);
								$stmt->execute();
								$ride_id = $db->lastInsertId();
								foreach($drivers as $val){
										$sql6="select driver_id from wy_ridedetails where driver_id=:driver_id and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
												ORDER BY `wy_ridedetails`.`id` ASC";
										$stmt = $db->prepare($sql6);
										$stmt->bindParam("driver_id", $val->driver_id);
										$stmt->execute();
										$driverslistdet = $stmt->fetchAll(PDO::FETCH_OBJ);
										if(!$driverslistdet){
											
											$fail = 0;
											$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values(:ride_id,:driver_id,:created_at,:updated_at)";
											$stmt = $db->prepare($sql5);
											$stmt->bindParam("ride_id", $ride_id);
											$stmt->bindParam("driver_id", $val->id);
											$stmt->bindParam("created_at", $date);
											$stmt->bindParam("updated_at", $date);
											$stmt->execute();
											$device_type = $val->device_type;
											$device_token = $val->device_token;
											$message = "request for ride";
											if($device_type==1){
												if($device_token!=''){
													apns($device_token,$message,$ride_id);
												}
											}else{
												send_gcm_notify($device_token, $message,$ride_id );
											}
											$sql6="update wy_driver set online_status=0 where id=:driver_id";
											$stmt = $db->prepare($sql6);
											$stmt->bindParam("driver_id", $val->id);
											$stmt->execute();
											echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
											/// firebase customer
											$date_fmt = date("d-m-Y");
											$header = array();
											$header[] = 'Content-Type: application/json';
											$postdata = '{"ride_type":"'.$register->ride_type.'","car_board":"'.$val->car_board.'","car_type":"'.$val->car_type.'","ride_category":"'.$register->ride_category.'","profile_photo":"","source_lng":"'.$register->lng.'","source_lat":"'.$register->lat.'","destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
											
											$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
											curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
											//curl_setopt($ch, CURLOPT_POST,1);
											curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
											curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
											$result = curl_exec($ch); 
											curl_close($ch); 
											///firebase
											/// firebase driver
											$date_fmt = date("d-m-Y");
											$header = array();
											$header[] = 'Content-Type: application/json';
											$postdata = '{"request_time":"'.$date.'","ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$val->car_board.'","car_type":"'.$register->car_type.'","reference_id":"'.$ref_id.'","customer_id":"'.$register->user_id.'","source_location":"'.$register->location.'","source_lat":"'.$register->lat.'","source_lng":"'.$register->lng.'","ride_type":"'.$register->ride_type.'","ride_category":"'.$register->ride_category.'","booking_type":"'.$register->booking_type.'","destination_location":"'.$register->destination_location.'","destination_lat":"'.$register->destination_lat.'","destination_lng":"'.$register->destination_lng.'"}';
											
											$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$val->id.json");
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
											curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
											//curl_setopt($ch, CURLOPT_POST,1);
											curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
											curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
											$result = curl_exec($ch); 
											curl_close($ch); 
											///firebase
											if($register->booking_type==1){
												// SEND CRN NUMBER TO CUSTOMER
												// $message   =  "Booking confirmed successfully. Your booking reference number is $ref_id and your CRN number is: $crn";
												// $message = urlencode($message);
												// $mobile   =  $user->mobile;
												// $ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
												// curl_setopt($ch, CURLOPT_HEADER, 0);
												// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												// $output = curl_exec($ch);      
												// curl_close($ch); 
												
												break;
											}
										}else{
											$fail = 1;
										}
								}
								if($fail==1){
									$del = "delete from wy_ride where id=:ride_id";
									$stmt = $db->prepare($del);
									$stmt->bindParam("ride_id", $ride_id);
									$stmt->execute();
									echo '{"Result":"Failed","Status":"No '.$cat.' available in your area"}';
								}
							}else{
								echo '{"Result":"Failed","Status":"No nearby '.$cat.' found"}';
							}
						}else{
							echo '{"Result":"Failed","Status":"You cannot book a '.$cat.' while in another ride"}';
						}
					}else{
						$sc_time = $register->schedule_date." ".$register->schedule_time;
						$sc_time = date("Y-m-d H:i:s",strtotime($sc_time));
						$cur_time = date("Y-m-d H:i:s",strtotime("+50 minute"));
						$sql5 = "select schedule_date,schedule_time from wy_ride where customer_id=:user_id and ride_type='2' and ride_status='0'";
						$stmt = $db->prepare($sql5);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->execute();
						$schlist = $stmt->fetch(PDO::FETCH_OBJ);
						if($schlist){
							$sc_ptime = $schlist->schedule_date." ".$schlist->schedule_time;
							$sc_ptime = date("Y-m-d H:i:s",strtotime($sc_ptime));
							$sc_ptime1 = date("Y-m-d H:i:s",strtotime("-1 hour".$sc_ptime));
							$sc_ptime2 = date("Y-m-d H:i:s",strtotime("+1 hour".$sc_ptime));
							if(strtotime($sc_time)>strtotime($sc_ptime1) && strtotime($sc_time)<strtotime($sc_ptime2)){
								echo '{"Result":"Failed","Status":"Ride cannot be scheduled. Already there is a ride among this time."}';
							}else{
								if(strtotime($sc_time)>= strtotime($cur_time)){
									$sql4="insert into wy_ride(crn,offer_id,coupon_code,ride_category,booking_type,city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,schedule_date,schedule_time,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at) 
											values(:crn,:offer_id,:coupon_code,:ride_category,:booking_type,:city,:ref_id,:user_id,:location,:lat,:lng,:car_type,:ride_type,:schedule_date,:schedule_time,:date_of_ride,:destination_location,:destination_lat,:destination_lng,:created_at,:updated_at)";
									$stmt = $db->prepare($sql4);
									$stmt->bindParam("offer_id", $offer_id);
									$stmt->bindParam("crn", $crn);
									$stmt->bindParam("coupon_code", $register->coupon_code);
									$stmt->bindParam("ride_category", $register->ride_category);
									$stmt->bindParam("booking_type", $register->booking_type);
									$stmt->bindParam("city", $register->city);
									$stmt->bindParam("ref_id", $ref_id);
									$stmt->bindParam("user_id", $register->user_id);
									$stmt->bindParam("location", $register->location);
									$stmt->bindParam("lat", $register->lat);
									$stmt->bindParam("lng", $register->lng);
									$stmt->bindParam("car_type", $register->car_type);
									$stmt->bindParam("ride_type", $register->ride_type);
									$stmt->bindParam("schedule_date", $register->schedule_date);
									$stmt->bindParam("schedule_time", $register->schedule_time);
									$stmt->bindParam("date_of_ride", $register->schedule_date);
									$stmt->bindParam("destination_location", $register->destination_location);
									$stmt->bindParam("destination_lat", $register->destination_lat);
									$stmt->bindParam("destination_lng", $register->destination_lng);
									$stmt->bindParam("created_at", $date);
									$stmt->bindParam("updated_at", $date);
									$stmt->execute();
									$ride_id = $db->lastInsertId();
									echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
									// SEND CRN NUMBER TO CUSTOMER
									// $message   =  "Booking confirmed successfully. Your booking reference number is $ref_id and your CRN number is: $crn";
									// $message = urlencode($message);
									// $mobile   =  $user->mobile;
									// $ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
									// curl_setopt($ch, CURLOPT_HEADER, 0);
									// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									// $output = curl_exec($ch);      
									// curl_close($ch); 
									
									/// firebase customer
									$date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"ride_type":"'.$register->ride_type.'","ride_category":"'.$register->ride_category.'","profile_photo":"","source_lng":"'.$register->lng.'","source_lat":"'.$register->lat.'","destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								}else{
									echo '{"Result":"Failed","Status":"Ride cannot be scheduled in past time."}';
								}
							}
						}else{
							if(strtotime($sc_time)>= strtotime($cur_time)){
								$sql4="insert into wy_ride(crn,offer_id,coupon_code,ride_category,booking_type,city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,schedule_date,schedule_time,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at) 
											values(:crn,:offer_id,:coupon_code,:ride_category,:booking_type,:city,:ref_id,:user_id,:location,:lat,:lng,:car_type,:ride_type,:schedule_date,:schedule_time,:date_of_ride,:destination_location,:destination_lat,:destination_lng,:created_at,:updated_at)";
								$stmt = $db->prepare($sql4);
								$stmt->bindParam("offer_id", $offer_id);
								$stmt->bindParam("crn", $crn);
								$stmt->bindParam("coupon_code", $register->coupon_code);
								$stmt->bindParam("ride_category", $register->ride_category);
								$stmt->bindParam("booking_type", $register->booking_type);
								$stmt->bindParam("city", $register->city);
								$stmt->bindParam("ref_id", $ref_id);
								$stmt->bindParam("user_id", $register->user_id);
								$stmt->bindParam("location", $register->location);
								$stmt->bindParam("lat", $register->lat);
								$stmt->bindParam("lng", $register->lng);
								$stmt->bindParam("car_type", $register->car_type);
								$stmt->bindParam("ride_type", $register->ride_type);
								$stmt->bindParam("schedule_date", $register->schedule_date);
								$stmt->bindParam("schedule_time", $register->schedule_time);
								$stmt->bindParam("date_of_ride", $register->schedule_date);
								$stmt->bindParam("destination_location", $register->destination_location);
								$stmt->bindParam("destination_lat", $register->destination_lat);
								$stmt->bindParam("destination_lng", $register->destination_lng);
								$stmt->bindParam("created_at", $date);
								$stmt->bindParam("updated_at", $date);
								$stmt->execute();
								$ride_id = $db->lastInsertId();
								echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
								// SEND CRN NUMBER TO CUSTOMER
									// $message   =  "Booking confirmed successfully. Your booking reference number is $ref_id and your CRN number is: $crn";
									// $message = urlencode($message);
									// $mobile   =  $user->mobile;
									// $ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
									// curl_setopt($ch, CURLOPT_HEADER, 0);
									// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									// $output = curl_exec($ch);      
									// curl_close($ch); 
								/// firebase customer
								$date_fmt = date("d-m-Y");
								$header = array();
								$header[] = 'Content-Type: application/json';
								$postdata = '{"ride_type":"'.$register->ride_type.'","ride_category":"'.$register->ride_category.'","profile_photo":"","source_lng":"'.$register->lng.'","source_lat":"'.$register->lat.'","destination_lng":"'.$register->destination_lng.'","destination_lat":"'.$register->destination_lat.'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
								
								$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/customer/$ride_id.json");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
								//curl_setopt($ch, CURLOPT_POST,1);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
								curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
								$result = curl_exec($ch); 
								curl_close($ch); 
								///firebase
							}else{
								echo '{"Result":"Failed","Status":"Ride cannot be scheduled in past time."}';
							}
						}
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function driverlocation(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql1="select id from wy_driver where id=:user_id";
	$sql3="select driver_id from wy_driverlocation where driver_id=:user_id";
	$sql2="insert into wy_driverlocation(driver_id,lat,lng,zipcode,startup_time,created_date,updated_date) values(:driver_id,:lat,:lng,:zipcode,:startup_time,:added_date,:updated_date)";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization'];
			$auth_id = $headers['auth-userid'];		
			$chk = check_authtoken($auth_token,$auth_id,2);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_OBJ);
				if($user){
					$stmt = $db->prepare($sql3); 
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->execute();
					$loctn = $stmt->fetch(PDO::FETCH_OBJ);
					if(!$loctn){
						$stmt = $db->prepare($sql2); 
						$stmt->bindParam("driver_id", $register->user_id);
						$stmt->bindParam("lat", $register->lat);
						$stmt->bindParam("lng", $register->lng);
						$stmt->bindParam("zipcode", $register->zipcode);
						$stmt->bindParam("startup_time", $date);
						$stmt->bindParam("added_date", $date);
						$stmt->bindParam("updated_date", $date);
						$stmt->execute();
						echo '{"Result":"Success","Status":"Location updated"}';
					}else{
						$chk = "select zipcode from wy_driverlocation where zipcode=:zipcode and driver_id=:user_id";
						$stmt = $db->prepare($chk);
						$stmt->bindParam("zipcode", $register->zipcode);
						$stmt->bindParam("user_id", $register->user_id);
						$stmt->execute();
						$zip = $stmt->fetch(PDO::FETCH_OBJ);
						if($zip){
							$sql4="update wy_driverlocation set lat=:lat,lng=:lng,updated_date=:updated_date,zipcode=:zipcode where driver_id=:user_id";
							$stmt = $db->prepare($sql4); 
							$stmt->bindParam("user_id", $register->user_id);
							$stmt->bindParam("lat", $register->lat);
							$stmt->bindParam("lng", $register->lng);
							$stmt->bindParam("zipcode", $register->zipcode);
							$stmt->bindParam("updated_date", $date);
							$stmt->execute();
						}else{
							$sql4="update wy_driverlocation set lat=:lat,lng=:lng,updated_date=:updated_date,startup_time=:startup_time,zipcode=:zipcode where driver_id=:user_id";
							$stmt = $db->prepare($sql4); 
							$stmt->bindParam("user_id", $register->user_id);
							$stmt->bindParam("lat", $register->lat);
							$stmt->bindParam("lng", $register->lng);
							$stmt->bindParam("zipcode", $register->zipcode);
							$stmt->bindParam("startup_time", $date);
							$stmt->bindParam("updated_date", $date);
							$stmt->execute();
						}
						echo '{"Result":"Success","Status":"Location updated"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function otp_verification(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql1="select * from wy_customer where id=:user_id";
	$sql2="select id,otp_time from wy_customer where id=:user_id and OTP=:OTP";
	$sql3="update wy_customer set verification_status='1',OTP='' where id=:user_id";
	try{
		$headers = $request->headers();  //print_r($headers); exit;
		if(isset($headers['authorization'])){
			$auth_token = $headers['authorization']; 
			$auth_id = $headers['auth-userid'];
			$chk = check_authtoken($auth_token,$auth_id,1);
			if($chk=='1'){
				$db = getConnection();
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_OBJ);
				if($user){
					$stmt = $db->prepare($sql2); 
					$stmt->bindParam("user_id", $register->user_id);
					$stmt->bindParam("OTP", $register->OTP);
					$stmt->execute();
					$otp = $stmt->fetch(PDO::FETCH_OBJ);
					if($otp){
						$otptime = $otp->otp_time;
						$otpexcesstime = date("Y-m-d H:i:s",strtotime("+15 minute".$otptime));
						if(strtotime($otpexcesstime) >= strtotime($date)){
							$stmt = $db->prepare($sql3); 
							$stmt->bindParam("user_id", $register->user_id);
							$stmt->execute();
							$stmt = $db->prepare($sql1); 
							$stmt->bindParam("user_id", $register->user_id);
							$stmt->execute();
							$user = $stmt->fetch(PDO::FETCH_OBJ);
							echo '{"Result":"Success","Status":"OTP successfully validated","details":'.json_encode($user).'}';
							
							//offers
							$offer = "select * from wy_offers where is_experied=0 and coupon_basedon in ('3','4','5')";
							$stmt = $db->prepare($offer); 
							$stmt->execute();
							$offerdet = $stmt->fetchAll(PDO::FETCH_OBJ);
							$devicetype=$user->device_type;
							$devicetoken=$user->device_token;
							if($offerdet){
								foreach($offerdet as $val){
									$offer_id=$val->id;
									$coupon_code=$val->coupon_code;
									$offern = "insert into wy_offernotification(offer_id,user_id,coupon_code,created_at,updated_at) values(:offer_id,:user_id,:coupon_code,:created_at,:updated_at)";
									$stmt = $db->prepare($offern);
									$stmt->bindParam("offer_id", $offer_id);
									$stmt->bindParam("user_id", $register->user_id);
									$stmt->bindParam("coupon_code", $coupon_code);
									$stmt->bindParam("created_at", $date);
									$stmt->bindParam("updated_at", $date);
									$stmt->execute();
								}
								$message = array("message" => 'New offer received');					
								$message1 =  'New offer received';					
								if($devicetype==1){
									if($devicetoken!=''){
										apns_cus($devicetoken,$message1,$offer_id);
									}
								}else{
									send_gcm_notify($devicetoken, $message1,$offer_id );
								}
							}
							//
						}else{
							echo '{"Result":"Failed","Status":"OTP expired"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Invalid OTP"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"User not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid authentication"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Authentication required"}';
		}
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function login_driver(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql="select id,profile_status from wy_driver where driver_id=:username";
	$sql1="select d.*,ct.car_type,ct.car_board,ct.yellow_caricon,ct.franchise_share,if(d.driver_type=1,ct.companydriver_share,ct.attacheddriver_share) as share,c.car_no from wy_driver d join wy_assign_taxi a on a.driver_id=d.id join wy_carlist c on a.car_num=c.id join wy_cartype ct on c.car_type=ct.id where d.driver_id=:username and d.password=:password and a.status='1' ";
	/* $mykey=getEncryptKey();
	$password=encryptPaswd($register->password,$mykey); */
	$key = hash('sha256', 'wrydes');
	$iv = substr(hash('sha256', 'dispatch'), 0, 16);
	$output = openssl_encrypt($register->password, "AES-256-CBC", $key, 0, $iv);
	$password = base64_encode($output);
	$auth_token = sha1($date);
	try{
	
		$db = getConnection();
		$stmt = $db->prepare($sql); 
		$stmt->bindParam("username", $register->username);
		$stmt->execute();
		$user1 = $stmt->fetch(PDO::FETCH_OBJ);
		if($user1){
			if($user1->profile_status==1){
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("username", $register->username);
				$stmt->bindParam("password", $password);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_OBJ);
				if($user){
					$sql2="update wy_driver set auth_token=:auth_token,status='1',device_type=:device_type,device_token=:device_token,updated_at=:updated_at where driver_id=:username";
					$stmt = $db->prepare($sql2); 
					$stmt->bindParam("auth_token", $auth_token);
					$stmt->bindParam("device_type", $register->device_type);
					$stmt->bindParam("device_token", $register->device_token);
					$stmt->bindParam("username", $register->username);
					$stmt->bindParam("updated_at", $date);
					$stmt->execute();
					$stmt = $db->prepare($sql1); 
					$stmt->bindParam("username", $register->username);
					$stmt->bindParam("password", $password);
					$stmt->execute();
					$user = $stmt->fetch(PDO::FETCH_OBJ);
					echo '{"Result":"Success","Status":"Login successfully","userdetails":'.json_encode($user).'}';
					
				}else{
					echo '{"Result":"Failed","Status":"Invalid login details"}';
				}
			}elseif($user1->profile_status==0){
				echo '{"Result":"Failed","Status":"You have not assigned to any taxi"}';
			}else{
				echo '{"Result":"Failed","Status":"You have been blocked"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Invalid driver id"}';
		}		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function login(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql1="select * from wy_customer where mobile=:username and registered_by='1'";
	$sql2="select * from wy_customer where email=:username and registered_by='1'";
	$key = hash('sha256', 'wrydes');
	$iv = substr(hash('sha256', 'dispatch'), 0, 16);
	$output = openssl_encrypt($register->password, "AES-256-CBC", $key, 0, $iv);
	$password = base64_encode($output);
	$auth_token = sha1($date);
	try{
	
		$db = getConnection();
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("username", $register->username);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if($user){
			if($user->verification_status==1){
				if($user->profile_status==1){
					$sql3="select * from wy_customer where mobile=:username and password=:password and registered_by='1'";
					$stmt = $db->prepare($sql3); 
					$stmt->bindParam("username", $register->username);
					$stmt->bindParam("password", $password);
					$stmt->execute();
					$userdet = $stmt->fetch(PDO::FETCH_OBJ);
					if($userdet){
						$qry="update wy_customer set auth_token=:auth_token,login_status='1',device_type=:device_type,device_token=:device_token,last_logintime='$date' where id=:username";
						$stmt = $db->prepare($qry); 
						$stmt->bindParam("username", $userdet->id);
						$stmt->bindParam("auth_token", $auth_token);
						$stmt->bindParam("device_type", $register->device_type);
						$stmt->bindParam("device_token", $register->device_token);
						$stmt->execute();
						$sql3="select * from wy_customer where mobile=:username and password=:password and registered_by='1'";
						$stmt = $db->prepare($sql3); 
						$stmt->bindParam("username", $register->username);
						$stmt->bindParam("password", $password);
						$stmt->execute();
						$userdet = $stmt->fetch(PDO::FETCH_OBJ);
						echo '{"Result":"Success","Status":"Login successfully","userdetails":'.json_encode($userdet).'}';
					}else{
						echo '{"Result":"Failed","Status":"Invalid password"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Account deactivated"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Not yet verified","userdetails":'.json_encode($user).'}';
			}
		}else{
			$stmt = $db->prepare($sql2); 
			$stmt->bindParam("username", $register->username);
			$stmt->execute();
			$mob = $stmt->fetch(PDO::FETCH_OBJ);
			if($mob){
				if($mob->verification_status==1){
					if($mob->profile_status==1){
						$sql3="select * from wy_customer where email=:username and password=:password and registered_by='1'";
						$stmt = $db->prepare($sql3); 
						$stmt->bindParam("username", $register->username);
						$stmt->bindParam("password", $password);
						$stmt->execute();
						$userdet = $stmt->fetch(PDO::FETCH_OBJ);
						if($userdet){
							$qry="update wy_customer set auth_token=:auth_token,login_status='1',device_type=:device_type,device_token=:device_token,last_logintime='$date' where id=:username";
							$stmt = $db->prepare($qry); 
							$stmt->bindParam("username", $userdet->id);
							$stmt->bindParam("auth_token", $auth_token);
							$stmt->bindParam("device_type", $register->device_type);
							$stmt->bindParam("device_token", $register->device_token);
							$stmt->execute();
							$sql3="select * from wy_customer where email=:username and password=:password and registered_by='1'";
							$stmt = $db->prepare($sql3); 
							$stmt->bindParam("username", $register->username);
							$stmt->bindParam("password", $password);
							$stmt->execute();
							$userdet = $stmt->fetch(PDO::FETCH_OBJ);
							echo '{"Result":"Success","Status":"Login successfully","userdetails":'.json_encode($userdet).'}';
						}else{
							echo '{"Result":"Failed","Status":"Invalid password"}';
						}
					}else{
						echo '{"Result":"Failed","Status":"Account deactivated"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Not yet verified","userdetails":'.json_encode($mob).'}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid login details"}';
			}
		}
		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}


function UserRegister(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$sql="select * from wy_customer where email=:email and mobile=:mobile and registered_by='1'";
	$sql1="select id from wy_customer where mobile=:mobile  and registered_by='1'";
	$sql2="select id from wy_customer where email=:email  and registered_by='1'";
	$insertquery="insert into wy_customer(auth_token,temp_token,name,mobile,email,password,device_type,device_token,OTP,otp_time,created_at,updated_at)
				values(:auth_token,:temp_token,:name,:mobile,:email,:password,:devicetype,:devicetoken,:OTP,:otp_time,:added_date,:updated_date)";
	$temp_token=rand(11111, 99991);
	$temp_token = base64_encode($temp_token);
	$verification_code=rand(1111, 9999);
	if(($register->mobile=='+919965524724') || ($register->mobile=='9965524724')){
		$verification_code="1234";
	}else{
		$verification_code=rand(1111, 9999);
		if($verification_code=="1234")
		$verification_code=rand(1111, 9999);
	}
	$auth_token = sha1($date);
	try{
	
		$db = getConnection();
		$stmt = $db->prepare($sql); 
		$stmt->bindParam("email", $register->email);
		$stmt->bindParam("mobile", $register->mobile);
		$stmt->execute();
		$user = $stmt->fetchAll(PDO::FETCH_OBJ);
		if(!$user){
			$stmt = $db->prepare($sql1); 
			$stmt->bindParam("mobile", $register->mobile);
			$stmt->execute();
			$mob = $stmt->fetchAll(PDO::FETCH_OBJ);
			$stmt = $db->prepare($sql2); 
			$stmt->bindParam("email", $register->email);
			$stmt->execute();
			$emil = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($mob){
				echo '{"Result":"Failed","Status":"Mobile already registered"}';
			}else if($emil){
				echo '{"Result":"Failed","Status":"Email already registered"}';
			}else{
				$key = hash('sha256', 'wrydes');
				$iv = substr(hash('sha256', 'dispatch'), 0, 16);
				$output = openssl_encrypt($register->password, "AES-256-CBC", $key, 0, $iv);
				$password = base64_encode($output);
				$stmt = $db->prepare($insertquery); 
				$stmt->bindParam("auth_token",  $auth_token);
				$stmt->bindParam("temp_token",  $temp_token);
				$stmt->bindParam("name",  $register->name);
				$stmt->bindParam("mobile",  $register->mobile);
				$stmt->bindParam("email",  $register->email);
				$stmt->bindParam("password",  $password);
				$stmt->bindParam("devicetype",  $register->devicetype);
				$stmt->bindParam("devicetoken",  $register->devicetoken);
				$stmt->bindParam("OTP",  $verification_code);
				$stmt->bindParam("otp_time",  $date);
				$stmt->bindParam("added_date",  $date);
				$stmt->bindParam("updated_date",  $date);
				$stmt->execute();
				$user_id = $db->lastInsertId();
				$stmt = $db->prepare($sql); 
				$stmt->bindParam("email", $register->email);
				$stmt->bindParam("mobile", $register->mobile);
				$stmt->execute();
				$userdet = $stmt->fetchAll(PDO::FETCH_OBJ);
				
				$message   =  "Verification code from MobyCabs: $verification_code";
				$message = urlencode($message);
				$mobile   =  $register->mobile;
				$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);      
				curl_close($ch); 
				
				echo '{"Result":"Success","Userdetails":'.json_encode($userdet).'}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Already registered, please login"}';
		}
		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="qV2D5bfuuA/KYAswtR1wHw==";
	$dbname="mobycabs";
	
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function send_gcm_notify($reg_id, $message,$ride_id) {

	$fields = array(
		'to' => $reg_id ,
		'priority' => "high",
		'notification' => array( "tag"=>"chat", "body" => $message,"ride_id"=> $ride_id),
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

function apns($devicetoken,$message,$rideid){
	$key = '';
//$batch = intval($count);
	$payload['aps'] = array('alert' => $message, 'sound' => 'default','badge' => 0,'notify_key'=>$key,'rideid'=>$rideid);
	$payload = json_encode($payload);
	//print_r($payload);
	$apnsHost = 'gateway.sandbox.push.apple.com';
	//$apnsHost = 'gateway.push.apple.com';
	$apnsPort = 2195;
	$apnsCert = 'MobyPartner.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	//$apnsCert = 'GOPartner_pro.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	$options = array('ssl' => array(
	'local_cert' => 'MobyPartner.pem',
	//'local_cert' => 'GOPartner_pro.pem',
	'passphrase' => 'armor'
	));
	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, $options);
	$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
	$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
	fwrite($apns, $apnsMessage);
	fclose($apns);
}

function apns_cus($devicetoken,$message,$rideid){
	$key = '';
//$batch = intval($count);
	$payload['aps'] = array('alert' => $message, 'sound' => 'default','badge' => 0,'notify_key'=>$key,'rideid'=>$rideid);
	$payload = json_encode($payload);
	//print_r($payload);
	$apnsHost = 'gateway.sandbox.push.apple.com';
	//$apnsHost = 'gateway.push.apple.com';
	$apnsPort = 2195;
	$apnsCert = 'mobycabs_cus.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	//$apnsCert = 'GOApp.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	$options = array('ssl' => array(
	//'local_cert' => 'GOApp.pem',
	'local_cert' => 'mobycabs_cus.pem',
	'passphrase' => 'armor'
	));
	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, $options);
	$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
	$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
	fwrite($apns, $apnsMessage);
	fclose($apns);
}

function getEncryptKey(){
	return base64_encode('!@#%^**$#-87');
}
function encryptPaswd($string, $key){
	$result = '';
	for($i=0; $i<strlen ($string); $i++){
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return base64_encode($result);
}

function decryptPaswd($string, $key){
	$result = '';
	$string = base64_decode($string);
	for($i=0; $i<strlen($string); $i++)	{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}
	return $result;
}

function vehicle_isfranchise($driverid){
	$db = getConnection();
	$carid = "select * from wy_assign_taxi where driver_id=$driverid and status =1";
	$stmt = $db->query($carid); 
	$car_id = $stmt->fetch(PDO::FETCH_OBJ);
	if($car_id){
		$car = $car_id->car_num;
		$qry="select * from wy_carlist where id=$car";
		$stmt = $db->query($qry); 
		$isfran = $stmt->fetch(PDO::FETCH_OBJ);
		if($isfran->isfranchise==1) return 1;
		else return 0;
	}
}

function selectsinglevalue($qry)
{
$retval = '';
$res = mysql_query($qry);
$row = mysql_fetch_array($res,MYSQL_ASSOC);
$retval = $row['retv'];
return $retval;
}


