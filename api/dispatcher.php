<?php
date_default_timezone_set("Asia/Kolkata");
$con=mysql_connect('localhost','root','admin') or ('error');
mysql_select_db('wrydes_developer',$con);
define("FIREBASE_API_KEY", "AIzaSyAmH9TdMle9B0kjwx3BERETPVvDsvZYJ-s");
define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");
require 'Slim/Slim.php';
$app = new Slim();

$app->post('/login', 'login');
$app->post('/get_customer', 'get_customer');
$app->post('/ride_estimate', 'ride_estimate');
$app->post('/conformbooking', 'conformbooking');
$app->get('/getcartype', 'getcartype');

$app->run();

function check_authtoken($auth_token,$id){
	$db = getConnection();
	$get_tkn = "select * from wy_dispatcher where auth_token='$auth_token' and id='$id'"; 
	$stmt = $db->query($get_tkn); 
	$get_det = $stmt->fetch(PDO::FETCH_OBJ);
	if($get_det){
		return 1;
	}else{
		return 0;
	}
}

function getcartype(){
	$date = date("Y-m-d H:i:s");
	$sql="select * from wy_cartype";
	try{
		$db=getConnection();
		$stmt = $db->query($sql);
		$ride = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($ride){
			echo '{ "Result": "Success","Details":'.json_encode($ride).'}';
		}else{
			echo '{"Result":"Failed","Status":"Cartype not found"}';
		}
	}catch(PDOException $e) {
		echo $e;
		echo '{ "Result": "Failed"}'; 
	}
}

function conformbooking(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$date1=date('Y-m-d');
	$sql2="select u.*,ct.car_type,(
				6371 *
				acos(
					cos( radians( :lat ) ) *
					cos( radians( d.lat ) ) *
					cos(
						radians( d.lng ) - radians( :lng )
					) +
					sin(radians(:lat)) *
					sin(radians(d.lat))
				)
			) distance from wy_driver u join wy_assign_taxi a on a.driver_id=u.id join wy_carlist c on a.car_num=c.id 
			join wy_driverlocation d on u.id=d.driver_id 
			join wy_cartype ct on c.car_type=ct.id 
			where c.car_type=:car_type and u.status='1' and u.online_status='1' having 	distance<3 order by distance asc ";
	
	try{
		$headers = $request->headers();  
		$auth_token = $headers['auth-type']; 
		$chk = check_authtoken($auth_token,$register->id);
		$db = getConnection();
		if($chk=='1'){
			if($register->user_id==''){
				$insqry = "insert into wy_customer(name,mobile,email,created_by,created_at,updated_at,registered_by) values(:name,:mobile,:email,:created_by,:created_at,:updated_at,'2')";
				$stmt = $db->prepare($insqry);
				$stmt->bindParam("name", $register->name);
				$stmt->bindParam("mobile", $register->mobile);
				$stmt->bindParam("email", $register->email);
				$stmt->bindParam("created_by", $register->id);
				$stmt->bindParam("created_at", $date);
				$stmt->bindParam("updated_at", $date);
				$stmt->execute();
				$userid = $db->lastInsertId();
			}else{
				$userid = $register->user_id;
			}
			$sql3=selectsinglevalue("select id as retv from wy_ride order by id desc limit 1");
			if($sql3!=''){
				$ordid = $sql3+1;
				$ref_id = "WYDSTXCBE00".$ordid;
			}else{
				$ref_id = "WYDSTXCBE001";
			}
			if($register->ride_type=='1'){
				$stmt = $db->prepare($sql2); 
				$stmt->bindParam("car_type", $register->car_type);
				$stmt->bindParam("city", $register->city);
				$stmt->bindParam("lat", $register->lat);
				$stmt->bindParam("lng", $register->lng);
				$stmt->execute();
				$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($drivers){
					foreach($drivers as $val){
						$sql3="select * from wy_ridedetails where driver_id='$val->id'";
						$stmt = $db->query($sql3);
						$driverslist = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($driverslist){
							$sql6="select driver_id from wy_ridedetails where driver_id='$val->id' and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
									ORDER BY `wy_ridedetails`.`id` ASC";
							$stmt = $db->query($sql6);
							$driverslistdet = $stmt->fetchAll(PDO::FETCH_OBJ);
							if(!$driverslistdet){
								$fail = 0;
								$sql4="insert into wy_ride(city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at,booked_through,booked_by) 
									values('$register->city','$ref_id','$userid','$register->location','$register->lat','$register->lng','$register->car_type','$register->ride_type','$date1','$register->destination_location','$register->destination_lat','$register->destination_lng','$date','$date','2','$register->id')";
								$stmt = $db->query($sql4);
								$ride_id = $db->lastInsertId();
								$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$val->id','$date','$date')";
								$stmt = $db->query($sql5);
								$device_type = $val->device_type;
								$device_token = $val->device_token;
								$message = "request for ride";
								if($device_type==1){
									apns($device_token,$message,$ride_id);
								}else{
									$message = "request for ride";
									//$message = array("message" => $message);
									//$reg_id = array($device_token);
									send_gcm_notify($device_token, $message,$ride_id );
								}
								echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
					/// firebase
					$date_fmt = date("d-m-Y");
					$header = array();
					$header[] = 'Content-Type: application/json';
					$postdata = '{"reference_id" : "'.$ref_id.'", "customer_name" : "'.$register->name.'","customer_email":"'.$register->email.'","mobile":"'.$register->mobile.'","source_location":"'.$register->location.'","car_type":"'.$val->car_type.'",
					"destination_location":"'.$register->destination_location.'","booked_time":"'.$date.'","driver_fname":"'.$val->firstname.'","driver_lname":"'.$val->lastname.'","driver_email":"'.$val->email.'","driver_mobile":"'.$val->mobile.'","driver_mobile":"'.$val->mobile.'",
					"ride_id":"'.$ride_id.'","rideing_time":"","duration":"","waiting_time":"","distance":"","total_amount":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
					
					$ch = curl_init("https://taxi-taxi-3c93b.firebaseio.com/dispatch/$register->id/$date_fmt/$ride_id.json");
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
					$postdata = '{"ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0"}';
					
					$ch = curl_init("https://taxi-taxi-3c93b.firebaseio.com/ride_info/driver/$val->id.json");
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
						}else{
							$fail = 0;
							$sql4="insert into wy_ride(city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at,booked_through,booked_by) 
								values('$register->city','$ref_id','$userid','$register->location','$register->lat','$register->lng','$register->car_type','$register->ride_type','$date1','$register->destination_location','$register->destination_lat','$register->destination_lng','$date','$date','2','$register->id')";
							$stmt = $db->query($sql4);
							$ride_id = $db->lastInsertId();
							$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$val->id','$date','$date')";
							$stmt = $db->query($sql5);
							$device_type = $val->device_type;
							$device_token = $val->device_token;
							$message = "request for ride";
							if($device_type==1){
								apns($device_token,$message,$ride_id);
							}else{
								$message = "request for ride";
								//$message = array("message" => $message);
								//$reg_id = array($device_token);
								send_gcm_notify($device_token, $message,$ride_id );
							}
							echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
							/// firebase
							$date_fmt = date("d-m-Y");
							$header = array();
							$header[] = 'Content-Type: application/json';
							$postdata = '{"reference_id" : "'.$ref_id.'", "customer_name" : "'.$register->name.'","customer_email":"'.$register->email.'","mobile":"'.$register->mobile.'","source_location":"'.$register->location.'","car_type":"'.$val->car_type.'",
							"destination_location":"'.$register->destination_location.'","booked_time":"'.$date.'","driver_fname":"'.$val->firstname.'","driver_lname":"'.$val->lastname.'","driver_email":"'.$val->email.'","driver_mobile":"'.$val->mobile.'","driver_mobile":"'.$val->mobile.'",
							"ride_id":"'.$ride_id.'","duration":"","rideing_time":"","waiting_time":"","distance":"","total_amount":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
							
							$ch = curl_init("https://taxi-taxi-3c93b.firebaseio.com/dispatch/$register->id/$date_fmt/$ride_id.json");
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
							$postdata = '{"ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0"}';
							
							$ch = curl_init("https://taxi-taxi-3c93b.firebaseio.com/ride_info/driver/$val->id.json");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
							//curl_setopt($ch, CURLOPT_POST,1);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							$result = curl_exec($ch); 
							curl_close($ch); 
							///firebase
							break;
						}
					}
					if($fail==1){
						echo '{"Result":"Failed","Status":"No cabs available in your area"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"No nearby cabs found"}';
				}
			}else{
				$sql4="insert into wy_ride(city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,schedule_date,schedule_time,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at,booked_through,booked_by) 
						values('$register->city','$ref_id','$userid','$register->location','$register->lat','$register->lng','$register->car_type','$register->ride_type','$register->schedule_date','$register->schedule_time','$register->schedule_date','$register->destination_location','$register->destination_lat','$register->destination_lng','$date','$date','2','$register->id')";
				$stmt = $db->query($sql4);
				$ride_id = $db->lastInsertId();
				echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Invalid authentication"}';
		}	
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function ride_estimate(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql1="select * from wy_faredetails where car_id='$register->car_type'";
	$sql2 = "select sum(tax_percentage) as cntt from wy_tax";
	$pk_chrg = "";
	try{
		$headers = $request->headers();  
		$auth_token = $headers['auth-type']; 
		$chk = check_authtoken($auth_token,$register->id);
		$db = getConnection();
		if($chk=='1'){
			$q = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$register->source_lat,$register->source_lng&destinations=$register->dest_lat,$register->dest_lng&mode=driving&sensor=false";
			$json = file_get_contents($q);
			$details = json_decode($json, TRUE); //print_r($details); exit;
			$duration = $details['rows'][0]['elements'][0]['duration']['text'];
			$durationv = $details['rows'][0]['elements'][0]['duration']['value'];
			$distance = $details['rows'][0]['elements'][0]['distance']['text'];
			$distancev = $details['rows'][0]['elements'][0]['distance']['value'];
			$dist = explode(" ",$distance);
			
			$stmt = $db->prepare($sql1); 
			$stmt->execute();
			$fare = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($fare){
				foreach($fare as $val){
					if($distancev!=0){
						if((strtotime($time)>=strtotime($val->ride_start_time)) && (strtotime($time)<=strtotime($val->ride_end_time))){
							if($dist[0]<=$val->min_km){
								$amount = $val->min_fare_amount;
							}else{
								$km = $dist[0] - $val->min_km;
								$amount1 = $val->min_fare_amount;
								$amount2 = $km*$val->ride_fare*$val->ride_each_km;
								$amount = $amount1 + $amount2;
							}
						}else{
							if($dist[0]<=$val->min_km){
								$amount = $val->min_fare_night;
							}else{
								$km = $dist[0] - $val->min_km_night;
								$amount1 = $val->min_fare_night;
								$amount2 = $km*$val->ride_fare_night*$val->ride_each_km_night;
								$amount = $amount1 + $amount2;
							}
						}
					}else{
						$amount=0;
					}
					$ridetim = $val->distance_fare*$durationv/60;
				}
				$sql5 = "select * from wy_surge where car_type='$register->car_type'";
				$stmt = $db->query($sql5); 
				$pkrate = $stmt->fetchAll(PDO::FETCH_OBJ);
				foreach($pkrate as $value){
					if((strtotime($time)>=strtotime($value->start_time)) && (strtotime($time)<=strtotime($value->end_time))){
						$amount = $amount* $value->surge_percentage;
						$pk_chrg = $value->surge_percentage;
					}
				}
				$stmt = $db->query($sql2); 
				$taxs = $stmt->fetch(PDO::FETCH_OBJ);
				$taxes = $taxs->cntt;
				$tax = $amount*$taxes/100;
				$amt = round($amount+$tax+$ridetim);
				$details = array(
					"distance" => $distance,
					"duration" => $duration,
					"amount" => $amt,
					"tax" => $tax,
					"peak_charge" => $pk_chrg
				);
				echo '{"Result":"Success","Details":'.json_encode($details).'}';
			}else{
				echo '{"Result":"Failed","Status":"Fare not found"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Invalid authentication"}';
		}		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function get_customer(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql1="select * from wy_customer where mobile like '%$register->mobile%' and is_deleted='0'";
	try{
		$headers = $request->headers();  
		$auth_token = $headers['auth-type']; 
		$chk = check_authtoken($auth_token,$register->id);
		$db = getConnection();
		if($chk=='1'){
			$stmt = $db->prepare($sql1); 
			//$stmt->bindParam("mobile", $register->mobile);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_OBJ);
			if($user){
				if($user->profile_status==1){
					echo '{"Result":"Success","Details":'.json_encode($user).'}';
				}else{
					echo '{"Result":"Failed","Status":"Customer have been blocked"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Customer not found"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Invalid authentication"}';
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
	$sql1="select * from wy_dispatcher where username=:username and password=:password and is_deleted='0'";
	$password=sha1($register->password);
	$token = sha1($date);
	try{
	
		$db = getConnection();
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("username", $register->username);
		$stmt->bindParam("password", $password);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if($user){
			if($user->is_blocked==0){
				$sql2="update wy_dispatcher set auth_token='$token',is_loggedin='1',device_type='$register->device_type',device_token='$register->device_token',last_logintime='$date' where id='$user->id'";
				$stmt = $db->query($sql2); 
				$stmt = $db->prepare($sql1); 
				$stmt->bindParam("username", $register->username);
				$stmt->bindParam("password", $password);
				$stmt->execute();
				$user = $stmt->fetchAll(PDO::FETCH_OBJ);
				echo '{"Result":"Success","Status":"Login successfully","Details":'.json_encode($user).'}';
			}else{
				echo '{"Result":"Failed","Status":"You have been blocked"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Invalid login details"}';
		}		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="admin";
	$dbname="wrydes_developer";
	
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function selectsinglevalue($qry)
{
$retval = '';
$res = mysql_query($qry);
$row = mysql_fetch_array($res,MYSQL_ASSOC);
$retval = $row['retv'];
return $retval;
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
	$apnsPort = 2195;
	$apnsCert = 'WrydesPartner_Dev.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	$options = array('ssl' => array(
	'local_cert' => 'WrydesPartner_Dev.pem',
	'passphrase' => 'armor'
	));
	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, $options);
	$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
	$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
	fwrite($apns, $apnsMessage);
	fclose($apns);
}