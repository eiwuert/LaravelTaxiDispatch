<?php
date_default_timezone_set("Asia/Kolkata");
$con=mysql_connect('localhost','armorco','5Z4Xv72q') or ('error');
mysql_select_db('armorco_patrol',$con);
require_once('PHPMailer_5.2.0/class.phpmailer.php');
require 'Slim/Slim.php';
$app = new Slim();

$app->post('/fileupload','fileupload');
$app->post('/UserRegister', 'UserRegister');
$app->post('/login', 'login');
$app->post('/login_driver', 'login_driver');
$app->post('/otp_verification', 'otp_verification');
$app->post('/driver_otp_verification', 'driver_otp_verification');
$app->post('/driverlocation', 'driverlocation');
$app->post('/conformbooking', 'conformbooking');
$app->post('/device_reg', 'device_reg');
$app->get('/get_car', 'get_car');
$app->get('/resend_otp/:mobileno', 'resend_otp');
$app->get('/resend_otp_user/:userid', 'resend_otp_user');
$app->get('/car_mapping/:driver_id/:car_id', 'car_mapping');
$app->get('/get_requests/:driver_id', 'get_requests');
$app->get('/process_requests/:ride_id/:driver_id/:status', 'process_requests');
$app->get('/reached_pickup/:ride_id/:driver_id', 'reached_pickup');
$app->get('/onlinestatus/:driver_id/:status', 'onlinestatus');
$app->get('/logout/:driver_id', 'logout');
$app->get('/ratecard/:car_type', 'ratecard');
$app->post('/rate_estimate', 'rate_estimate');
$app->post('/cancel_ride', 'cancel_ride');
$app->get('/getride_details/:ride_id', 'getride_details');
$app->get('/start_ride/:ride_id/:driver_id', 'start_ride');
$app->post('/forgotpassword', 'forgotpassword');

$app->run();

function forgotpassword(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$sql = "select * from wy_customer where email='$register->email'";
	try{
		$db = getConnection();
		$db=getConnection();
		$stmt = $db->query($sql);
		$customer = $stmt->fetch(PDO::FETCH_OBJ);
		if($customer){
			$subject="Forgot Password";
			$username = $customer->name;
			$id = urlencode(base64_encode($register->email));
			$content = "So you lost your password? No problem! Please click the link below to reset your password<br><br>
			<a href='armor.co.in/wrydes/admin/forgotpassword.php?id=$id'>Click here</a>";
				$sign = "Thank you<br>
Wrydes Team<br><br>
© 2016 Wrydes TM. All right reserved.";
			$html='<html class="no-js" lang="en"> 
				<body>
				<div style="
					width: auto;
					border: 15px solid #FF0000;
					padding: 20px;
					margin: 10px;
				">
				 <div class="container">
					<div class="navbar-header">
						<div style="text-align: center;">
						<a href="" title="" style="margin-top:0px"><img src="t2k.png"  class="img-responsive logo-new" width="50%" ></a>
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
			 $mail             = new PHPMailer();
			$mail->SetFrom('support@wrydes.com', 'wrydes');
			$mail->AddReplyTo("support@wrydes.com","wrydes");
			$mail->AddAddress($register->email, '');
			$mail->CharSet = 'UTF-8';
			$mail->Subject    = $subject;
			$mail->MsgHTML($html); 
			if($mail->Send()) {
				echo '{ "Result": "Success","Status":"Email sent sucessfully"}';
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


function start_ride($ride_id,$driver_id){
	$date = date("Y-m-d H:i:s");
	$sql="select * from wy_ride where id='$ride_id'";
	$sql1="select * from wy_driver where id='$driver_id'";
	$sql2 = "update wy_ridedetails set ride_status='3', start_ride_time='$date', updated_date='$date' where ride_id='$ride_id' and driver_id='$driver_id'";
	try{
		$db=getConnection();
		$stmt = $db->query($sql);
		$ride = $stmt->fetch(PDO::FETCH_OBJ);
		if($ride){
			$stmt = $db->query($sql1);
			$dr_det = $stmt->fetch(PDO::FETCH_OBJ);
			if($dr_det){ 
				$stmt = $db->query($sql2);
				echo '{ "Result": "Success","Status":"status updated"}';
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Ride not found"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function reached_pickup($ride_id,$driver_id){
	$date = date("Y-m-d H:i:s");
	$sql="select * from wy_ride where id='$ride_id'";
	$sql1="select * from wy_driver where id='$driver_id'";
	$sql2 = "update wy_ridedetails set ride_status='2', reach_pickuploc_time='$date', updated_date='$date' where ride_id='$ride_id' and driver_id='$driver_id'";
	try{
		$db=getConnection();
		$stmt = $db->query($sql);
		$ride = $stmt->fetch(PDO::FETCH_OBJ);
		if($ride){
			$stmt = $db->query($sql1);
			$dr_det = $stmt->fetch(PDO::FETCH_OBJ);
			if($dr_det){ 
				$stmt = $db->query($sql2);
				echo '{ "Result": "Success","Status":"status updated"}';
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Ride not found"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function getride_details($ride_id){
	$date = date("Y-m-d H:i:s");
	$sql="select * from wy_ride where id='$ride_id'";
	$sql3 = "select dr.id,dr.name,dv.phone from wy_ridedetails r join wy_dailydrive d on r.driver_id=d.driver_id 
			join wy_device dv on dv.id=d.device_id join wy_driver dr on dr.id=d.driver_id where r.ride_id='$ride_id' and r.accept_status='1' and d.status='1'";
	try{
		$db=getConnection();
		$stmt = $db->query($sql);
		$ride = $stmt->fetch(PDO::FETCH_OBJ);
		if($ride){
			$stmt = $db->query($sql3);
			$dr_det = $stmt->fetch(PDO::FETCH_OBJ);
			if($dr_det){
				echo '{ "Result": "Success","details":'.json_encode($dr_det).'}';
			}else{
				echo '{"Result":"Failed","Status":"Details not found"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Ride not found"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function cancel_ride(){
	$date = date("Y-m-d H:i:s");
	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$sql="select * from wy_ride where id='$register->ride_id'";
	$sql1="update wy_ride set ride_status='2',cancel_notes='$register->cancel_notes',cancle_time='$date' where id='$register->ride_id'";
	$sql2="update wy_ridedetails set ride_status='5',cancel_notes='$register->cancel_notes',cancel_time='$date' where ride_id='$register->ride_id'";
	try{
		$db=getConnection();
		$stmt = $db->query($sql);
		$ride = $stmt->fetch(PDO::FETCH_OBJ);
		if($ride){
			if($register->type=='1'){
				$stmt = $db->query($sql1);
				$stmt = $db->query($sql2);
				$sql3 = "select * from wy_ridedetails r join wy_dailydrive d on r.driver_id=d.driver_id 
						join wy_device dv on dv.id=d.device_id where r.ride_id='$register->ride_id' and r.accept_status='1'";
				$stmt = $db->query($sql3);
				$rate = $stmt->fetch(PDO::FETCH_OBJ);
				if($rate){
					$device_type = $rate->device_type;
					$device_token = $rate->device_token;
					$message = "Booking canceled";
					if($device_type==1){
						
					}else{
						send_gcm_notify($device_token, $message,$register->ride_id );
					}
				}
			}else{
				if($register->status=='1'){
					$sql6="update wy_ridedetails set accept_status='3',ride_status='5',cancel_notes='$register->cancel_notes',cancel_time='$date' where ride_id='$register->ride_id' and driver_id='$register->driver_id'";
					$stmt = $db->query($sql6);
					$sql3 = "select * from wy_customer where id='$ride->customer_id'";
					$stmt = $db->query($sql3);
					$rate = $stmt->fetch(PDO::FETCH_OBJ);
					$device_type = $rate->device_type;
					$device_token = $rate->device_token;
					$message = "Booking canceled";
					if($device_type==1){
						
					}else{
						send_gcm_notify($device_token, $message,$register->ride_id );
					}
				}else{
					$sql6="update wy_ridedetails set accept_status='3',cancel_notes='$register->cancel_notes',cancel_time='$date' where ride_id='$register->ride_id' and driver_id='$register->driver_id'";
					$stmt = $db->query($sql6);
					$sql3 = "select * from wy_ride where id='$register->ride_id'";
					$stmt = $db->query($sql3);
					$dev = $stmt->fetch(PDO::FETCH_OBJ);
					if($dev){
						$sql4="select u.*,dv.*,(
							6371 *
							acos(
								cos( radians('$dev->source_lat') ) *
								cos( radians( d.lat ) ) *
								cos(
									radians( d.lng ) - radians( '$dev->source_lng' )
								) +
								sin(radians('$dev->source_lat')) *
								sin(radians(d.lat))
							)
						) distance from wy_dailydrive u join wy_carlist c on u.car_id=c.id 
						join wy_driverlocation d on u.driver_id=d.driver_id 
						join wy_device dv on u.device_id=dv.id 
						where c.car_type='$dev->car_type' and u.status='1' and u.online_status='1' 
						and u.driver_id not in (select driver_id from wy_ridedetails where  (accept_status =  '2' OR ride_status NOT IN ('1,2,3')) and u.driver_id!='$register->driver_id' and ride_id='$register->ride_id') 
						order by distance asc ";
						$stmt = $db->query($sql4); 
						$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($drivers){
							foreach($drivers as $val){
								$sql5="insert into wy_ridedetails(ride_id,driver_id,added_date,updated_date) values('$register->ride_id','$val->driver_id','$date','$date')";
								$stmt = $db->query($sql5);
								$device_type = $val->device_type;
								$device_token = $val->device_token;
								if($device_type==1){
									
								}else{
									$message = "request for ride";
									//$message = array("message" => $message);
									//$reg_id = array($device_token);
									send_gcm_notify($device_token, $message,$register->ride_id );
								}
								//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).'}';
								break;
							}
						}
					}
				}
			}
			echo '{ "Result": "Success","Status":"Ride has been canceled"}';
		}else{
			echo '{"Result":"Failed","Status":"Ride not found"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function rate_estimate(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d H:i:s');
	$time=date('H:i:s');
	$sql = "select * from wy_ratecard where car_type=:car_type";
	$sql1 = "select sum(value) as cntt from wy_ratecard where car_type=:car_type and rate_details='tax'";
	
	/* function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
		// Calculate the distance in degrees
		$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
	 
		// Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
		switch($unit) {
			case 'km':
				$distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
				break;
			case 'mi':
				$distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
				break;
			case 'nmi':
				$distance =  $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
		}
		return round($distance, $decimals);
	} */
	try{
		$db = getConnection();
		//$km = distanceCalculation($register->source_lat, $register->source_lng, $register->dest_lat, $register->dest_lng); // Calculate distance in kilometres (default)
		$q = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$register->source_lat,$register->source_lng&destinations=$register->dest_lat,$register->dest_lng&mode=driving&sensor=false";
		$json = file_get_contents($q);
		$details = json_decode($json, TRUE);
		$duration = $details['rows'][0]['elements'][0]['duration']['text'];
		$distance = $details['rows'][0]['elements'][0]['distance']['text'];
		$dist = explode(" ",$distance);
		//******************
		$stmt = $db->prepare($sql); 
		$stmt->bindParam("car_type", $register->car_type);
		$stmt->execute();
		$rate = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach($rate as $val){
			switch($val->label_key){
				case 'basefare':
					$basefare = $val->value;
					$basefarekm = $val->rate_details;
					break;
				case 'basefare_night':
					$basefare_night = $val->value;
					$basefare_nightkm = $val->rate_details;
					break;
				case 'night_time':
					$from_time = $val->from_time;
					$to_time = $val->to_time;
					break;
				case 'rate':
					$rate = $val->value;
					break;
				case 'ridetime':
					$ridetime = $val->value;
					break;
				case 'rate_night':
					$rate_night = $val->value;
					break;
			}
		}
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("car_type", $register->car_type);
		$stmt->execute();
		$taxs = $stmt->fetch(PDO::FETCH_OBJ);
		$taxes = $taxs->cntt;
		if((strtotime($time)>=strtotime($from_time)) && (strtotime($time)<=strtotime($to_time))){
			if($dist[0]<=$basefare_nightkm){
				$amount = $basefare_night;
			}else{
				$km = $dist[0] - $basefare_nightkm;
				$amount1 = $basefare_night;
				$amount2 = $km*$rate_night;
				$amount = $amount1 + $amount2;
			}
		}else{
			if($dist[0]<=$basefarekm){
				$amount = $basefare;
			}else{
				$km = $dist[0] - $basefarekm;
				$amount1 = $basefare;
				$amount2 = $km*$rate;
				$amount = $amount1 + $amount2;
			}
		}
		$ridetim = $ridetime*$duration;
		$tax = $amount*$taxes/100;
		$amt = round($amount+$tax+$ridetim);
		$details = array(
			"distance" => $distance,
			"duration" => $duration,
			"amount" => $amt
		);
		echo '{ "Result": "Success","details":'.json_encode($details).'}';
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function ratecard($car_type){
	$date = date("Y-m-d");
	$sql="select * from wy_ratecard where car_type='$car_type' AND label_key IN ('basefare',  'rate',  'ridetime')";
	try{
		$db=getConnection();
		$stmt = $db->query($sql);
		$rate = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($rate){
			echo '{ "Result": "Success","details":'.json_encode($rate).'}';
		}else{
			echo '{"Result":"Failed","Status":"Rate not found"}';
		}
	}catch(PDOException $e) {
		
		echo '{ "Result": "Failed"}'; 
	}
}

function logout($driver_id){
		$date = date("Y-m-d");
		$sql="select * from wy_driver where id='$driver_id'";
		$sql2="update wy_dailydrive set status='0',online_status='0' where driver_id='$driver_id'";
		try{
			$db=getConnection();
			$stmt = $db->query($sql);
			$car = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($car){
				$stmt = $db->query($sql2);
				echo '{ "Result": "Success","Status":"Logout successfully"}';
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
			}
		}catch(PDOException $e) {
			
			echo '{ "Result": "Failed"}'; 
		}
}

function onlinestatus($driver_id,$status){
		$date = date("Y-m-d");
		$sql="select * from wy_driver where id='$driver_id'";
		/* $sql2="UPDATE wy_dailydrive d INNER JOIN (
SELECT id
FROM wy_dailydrive
WHERE driver_id =  '$driver_id'
ORDER BY id DESC 
LIMIT 1 

) AS d1 ON d.id < d1.id
SET d.online_status =  '$status' WHERE d.driver_id =  '$driver_id' AND d.id < d1.id"; */
		$sql2 = "update wy_dailydrive set online_status='$status'  WHERE driver_id =  '$driver_id' ";
		try{
			$db=getConnection();
			$stmt = $db->query($sql);
			$car = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($car){
				$stmt = $db->query($sql2);
				echo '{ "Result": "Success","Status":"status changed successfully"}';
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
			}
		}catch(PDOException $e) {
			echo $e;
			echo '{ "Result": "Failed"}'; 
		}
}

function process_requests($ride_id,$driver_id,$status){
		$date = date("Y-m-d H:i:s");
		$sql="select * from wy_driver where id='$driver_id'";
		$sql1="select * from wy_ride where id='$ride_id'";
		try{
			$db=getConnection();
			$stmt = $db->query($sql);
			$drvr = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($drvr){
				$stmt = $db->query($sql1);
				$rid = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($rid){
					if($status=='Accept'){
						$sql2="update wy_ridedetails set accept_status='1',accept_time='$date',ride_status='1',updated_date='$date' where driver_id='$driver_id' and ride_id='$ride_id'";
						$stmt = $db->query($sql2);
						$sql3 = "select r.id,r.source_location,r.source_lat,r.source_lng,r.destination_location,r.destination_lat,r.destination_lng,c.name,r.customer_id,c.mobile,c.device_type,c.device_token from wy_ride r join wy_customer c on c.id=r.customer_id where r.id='$ride_id'";
						$stmt = $db->query($sql3);
						$dev = $stmt->fetchAll(PDO::FETCH_OBJ);
						if($dev){
							foreach($dev as $vall)
							$device_type = $vall->device_type;
							$device_token = $vall->device_token;
							if($device_type==1){
								
							}else{
								$message = "Booking confrimed";
								//$message = array("message" => $message);
								//$reg_id = array($device_token);
								send_gcm_notify($device_token, $message,$ride_id );
							}
							echo '{ "Result": "Success","details":'.json_encode($dev).'}';
						}else{
							echo '{"Result":"Failed","Status":"No request found"}';
						}
					}else{
						$sql2="update wy_ridedetails set accept_status='2',updated_date='$date' where driver_id='$driver_id' and ride_id='$ride_id'";
						$stmt = $db->query($sql2);
						$sql3 = "select * from wy_ride where id='$ride_id'";
						$stmt = $db->query($sql3);
						$dev = $stmt->fetch(PDO::FETCH_OBJ);
						if($dev){
							$sql4="select u.*,dv.*,(
								6371 *
								acos(
									cos( radians('$dev->source_lat') ) *
									cos( radians( d.lat ) ) *
									cos(
										radians( d.lng ) - radians( '$dev->source_lng' )
									) +
									sin(radians('$dev->source_lat')) *
									sin(radians(d.lat))
								)
							) distance from wy_dailydrive u join wy_carlist c on u.car_id=c.id 
							join wy_driverlocation d on u.driver_id=d.driver_id 
							join wy_device dv on u.device_id=dv.id 
							where c.car_type='$dev->car_type' and u.status='1' and u.online_status='1' 
							and u.driver_id not in (select driver_id from wy_ridedetails where  (accept_status not in  ('2,3') OR ride_status NOT IN ('1,2,3')) and ride_id='$ride_id') 
							order by distance asc ";
							$stmt = $db->query($sql4); 
							$drivers = $stmt->fetchAll(PDO::FETCH_OBJ);
							if($drivers){
								foreach($drivers as $val){
									$sql5="insert into wy_ridedetails(ride_id,driver_id,added_date,updated_date) values('$ride_id','$val->driver_id','$date','$date')";
									$stmt = $db->query($sql5);
									$device_type = $val->device_type;
									$device_token = $val->device_token;
									if($device_type==1){
										
									}else{
										$message = "request for ride";
										//$message = array("message" => $message);
										//$reg_id = array($device_token);
										send_gcm_notify($device_token, $message,$ride_id );
									}
									//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).'}';
									break;
								}
							}
						}
						echo '{ "Result": "Success","Status":"You denied the request"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Ride not found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
			}
		}catch(PDOException $e) {
			echo $e;
			echo '{ "Result": "Failed"}'; 
		}
}

function get_requests($driver_id){
		$date = date("Y-m-d");
		$sql="select * from wy_driver where id='$driver_id'";
		$sql2="select * from wy_ridedetails rd join wy_ride r on r.id=rd.ride_id where driver_id='$driver_id' and accept_status='0' and ride_status!='5'";
		try{
			$db=getConnection();
			$stmt = $db->query($sql);
			$car = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($car){
				$stmt = $db->query($sql2);
				$dev = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($dev){
					echo '{ "Result": "Success","details":'.json_encode($dev).'}';
				}else{
					echo '{"Result":"Failed","Status":"No request found"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
			}
		}catch(PDOException $e) {
			
			echo '{ "Result": "Failed"}'; 
		}
}

function car_mapping($driver_id,$car_id){
		$date = date("Y-m-d");
		$sql="select * from wy_driver where id='$driver_id'";
		$sql2="select * from wy_dailydrive where driver_id='$driver_id' and status='1'";
		$sql1="update wy_dailydrive set car_id='$car_id' where driver_id='$driver_id' and status='1' and date(login_date)='$date'";
		try{
			$db=getConnection();
			$stmt = $db->query($sql);
			$car = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($car){
				$stmt = $db->query($sql2);
				$dev = $stmt->fetchAll(PDO::FETCH_OBJ);
				if($dev){
					$stmt = $db->query($sql1);
					echo '{ "Result": "Success","Status":"Car mapped to driver"}';
				}else{
					echo '{"Result":"Failed","Status":"Driver not loggedin"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
			}
		}catch(PDOException $e) {
			
			echo '{ "Result": "Failed"}'; 
		}
}

function resend_otp_user($userid){
		$date = date("Y-m-d H:i:s");
		$sql="select * from wy_customer where id='$userid'";
		$verification_code=rand(1111, 9999);
		$sql1="update wy_customer set OTP='$verification_code',verification_status='0', otp_time='$date' where id='$userid'";
		try{
			$db=getConnection();
			$stmt = $db->query($sql);
			$car = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($car){
				$stmt = $db->query($sql1);
				echo '{ "Result": "Success","Status":"Verfication code sent"}';
				
			}else{
				echo '{"Result":"Failed","Status":"User not found"}';
			}
		}catch(PDOException $e) {
			
			echo '{ "Result": "Failed"}'; 
		}
}

function resend_otp($mobileno){
		$date = date("Y-m-d H:i:s");
		$sql="select * from wy_device where phone='$mobileno'";
		$verification_code=rand(1111, 9999);
		$sql1="update wy_device set OTP='$verification_code',verification_status='0', otp_time='$date' where phone='$mobileno'";
		try{
			$db=getConnection();
			$stmt = $db->query($sql);
			$car = $stmt->fetchAll(PDO::FETCH_OBJ);
			if($car){
				$stmt = $db->query($sql1);
				echo '{ "Result": "Success","Status":"Verfication code sent"}';
				
			}else{
				echo '{"Result":"Failed","Status":"Driver not found"}';
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
			$stmt = $db->query($sql);
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

function driver_otp_verification(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$sql1="select * from wy_device where phone=:phone";
	$sql2="select * from wy_device where phone=:phone and OTP=:OTP";
	$sql3="update wy_device set verification_status='1' where phone=:phone";
	try{
	
		$db = getConnection();
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("phone", $register->phone);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if($user){
			$stmt = $db->prepare($sql2); 
			$stmt->bindParam("phone", $register->phone);
			$stmt->bindParam("OTP", $register->OTP);
			$stmt->execute();
			$otp = $stmt->fetch(PDO::FETCH_OBJ);
			if($otp){
				$otptime = $otp->otp_time;
				$otpexcesstime = date("Y-m-d H:i:s",strtotime("+15 minute".$otptime));
				if(strtotime($otpexcesstime) >= strtotime($date)){
					$stmt = $db->prepare($sql3); 
					$stmt->bindParam("phone", $register->phone);
					$stmt->execute();
					echo '{"Result":"Success","Status":"OTP successfully validated"}';
				}else{
					echo '{"Result":"Failed","Status":"OTP expired"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid OTP"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"Phonenumber not found"}';
		}
		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}


function device_reg(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$sql1="select * from wy_device where device_id=:device_id";
	$sql3="select * from wy_device where phone=:phone and device_id!=:device_id";
	$sql2="insert into wy_device(phone,OTP,device_type,device_id,device_token,brand,model,added_date,updated_date,otp_time) values(:phone,:OTP,:device_type,:device_id,:device_token,:brand,:model,:added_date,:updated_date,:otp_time)";
	$sql4="update wy_device set phone=:phone,OTP=:OTP,verification_status='0',device_type=:device_type,device_token=:device_token,brand=:brand,model=:model,updated_date=:updated_date,otp_time=:otp_time where device_id=:device_id";
	$verification_code=rand(1111, 9999);
	try{
		$db = getConnection();
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("device_id", $register->device_id);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if(!$user){
			$stmt = $db->prepare($sql3); 
			$stmt->bindParam("phone", $register->phone);
			$stmt->bindParam("device_id", $register->device_id);
			$stmt->execute();
			$loctn = $stmt->fetch(PDO::FETCH_OBJ); 
			if(!$loctn){
				$stmt = $db->prepare($sql2); 
				$stmt->bindParam("phone", $register->phone);
				$stmt->bindParam("OTP", $verification_code);
				$stmt->bindParam("device_id", $register->device_id);
				$stmt->bindParam("device_type", $register->device_type);
				$stmt->bindParam("device_token", $register->device_token);
				$stmt->bindParam("brand", $register->brand);
				$stmt->bindParam("model", $register->model);
				$stmt->bindParam("added_date", $date);
				$stmt->bindParam("updated_date", $date);
				$stmt->bindParam("otp_time", $date);
				$stmt->execute();
				echo '{"Result":"Success","Status":"Device registered"}';
			}else{
				echo '{"Result":"Failed","Status":"Mobile number already mapped to another device"}';
			}
		}else{
			$stmt = $db->prepare($sql3); 
			$stmt->bindParam("phone", $register->phone);
			$stmt->bindParam("device_id", $register->device_id);
			$stmt->execute();
			$loctn = $stmt->fetch(PDO::FETCH_OBJ);
			if(!$loctn){
				$stmt = $db->prepare($sql4); 
				$stmt->bindParam("phone", $register->phone);
				$stmt->bindParam("OTP", $verification_code);
				$stmt->bindParam("device_id", $register->device_id);
				$stmt->bindParam("device_type", $register->device_type);
				$stmt->bindParam("device_token", $register->device_token);
				$stmt->bindParam("brand", $register->brand);
				$stmt->bindParam("model", $register->model);
				$stmt->bindParam("updated_date", $date);
				$stmt->bindParam("otp_time", $date);
				$stmt->execute();
				echo '{"Result":"Success","Status":"Device updated"}';
			}else{
				echo '{"Result":"Failed","Status":"Mobile number already mapped to another device"}';
			}
		}
		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}
	
function conformbooking(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$date1=date('Y-m-d');
	$sql1="select * from wy_customer where id=:user_id";
	$sql2="select u.*,dv.*,(
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
			) distance from wy_dailydrive u join wy_carlist c on u.car_id=c.id 
			join wy_driverlocation d on u.driver_id=d.driver_id 
			join wy_device dv on u.device_id=dv.id 
			where c.car_type=:car_type and u.status='1' and u.online_status='1' order by distance asc ";
	
	try{
		$db = getConnection();
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("user_id", $register->user_id);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if($user){
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
						$sql3="select * from wy_ridedetails where ride_status in ('1,2,3') and driver_id='$val->id'";
						$stmt = $db->query($sql3);
						$driverslist = $stmt->fetchAll(PDO::FETCH_OBJ);
						if(!$driverslist){
							$fail = 0;
							$sql4="insert into wy_ride(customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,added_date,updated_date) 
								values('$register->user_id','$register->location','$register->lat','$register->lng','$register->car_type','$register->ride_type','$date1','$register->destination_location','$register->destination_lat','$register->destination_lng','$date','$date')";
							$stmt = $db->query($sql4);
							$ride_id = $db->lastInsertId();
							$sql5="insert into wy_ridedetails(ride_id,driver_id,added_date,updated_date) values('$ride_id','$val->id','$date','$date')";
							$stmt = $db->query($sql5);
							$device_type = $val->device_type;
							$device_token = $val->device_token;
							if($device_type==1){
								
							}else{
								$message = "request for ride";
								//$message = array("message" => $message);
								//$reg_id = array($device_token);
								send_gcm_notify($device_token, $message,$ride_id );
							}
							echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).'}';
							break;
						}else{
							$fail = 1;
						}
					}
					if($fail==1){
						echo '{"Result":"Failed","Status":"No cabs available in your area"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"No nearby cabs found"}';
				}
			}else{
				$sql4="insert into wy_ride(customer_id,source_location,source_lat,source_lng,car_type,ride_type,schedule_date,schedule_time,date_of_ride,destination_location,destination_lat,destination_lng,added_date,updated_date) 
						values('$register->user_id','$register->location','$register->lat','$register->lng','$register->car_type','$register->ride_type','$register->schedule_date','$register->schedule_time','$register->schedule_date','$register->destination_location','$register->destination_lat','$register->destination_lng','$date','$date')";
				$stmt = $db->query($sql4);
				echo '{"Result":"Success","Status":"Booking confrimed"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"User not found"}';
		}
		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function driverlocation(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$sql1="select * from wy_driver where id=:user_id";
	$sql3="select * from wy_driverlocation where driver_id=:user_id";
	$sql2="insert into wy_driverlocation(driver_id,lat,lng,added_date,updated_date) values(:driver_id,:lat,:lng,:added_date,:updated_date)";
	$sql4="update wy_driverlocation set lat=:lat,lng=:lng,updated_date=:updated_date where driver_id=:user_id";
	try{
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
				$stmt->bindParam("added_date", $date);
				$stmt->bindParam("updated_date", $date);
				$stmt->execute();
				echo '{"Result":"Success","Status":"Location updated"}';
			}else{
				$stmt = $db->prepare($sql4); 
				$stmt->bindParam("user_id", $register->user_id);
				$stmt->bindParam("lat", $register->lat);
				$stmt->bindParam("lng", $register->lng);
				$stmt->bindParam("updated_date", $date);
				$stmt->execute();
				echo '{"Result":"Success","Status":"Location updated"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"User not found"}';
		}
		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function otp_verification(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$sql1="select * from wy_customer where id=:user_id";
	$sql2="select * from wy_customer where id=:user_id and OTP=:OTP";
	$sql3="update wy_customer set verification_status='1' where id=:user_id";
	try{
	
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
				}else{
					echo '{"Result":"Failed","Status":"OTP expired"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Invalid OTP"}';
			}
		}else{
			echo '{"Result":"Failed","Status":"User not found"}';
		}
		
	}catch(PDOException $e){
		echo $e;
		echo '{"Result":"Failed"}';
	}
}

function login_driver(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$sql1="select * from wy_driver where driver_id=:username and password=:password";
	$sql2="insert into wy_dailydrive(driver_id,device_id,status,login_date,added_date,updated_date) values(:driver_id,:device_id,'1',:login_date,:added_date,:updated_date)";
	$mykey=getEncryptKey();
	$password=encryptPaswd($register->password,$mykey);
	try{
	
		$db = getConnection();
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("username", $register->username);
		$stmt->bindParam("password", $password);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if($user){
			if($user->profile_status==1){
				$stmt = $db->prepare($sql2); 
				$stmt->bindParam("driver_id", $user->id);
				$device_id = selectsinglevalue("SELECT id as retv from wy_device where device_id='$register->device_id'");
				$stmt->bindParam("device_id", $device_id);
				$stmt->bindParam("login_date", $date);
				$stmt->bindParam("added_date", $date);
				$stmt->bindParam("updated_date", $date);
				$stmt->execute();
				echo '{"Result":"Success","Status":"Login successfully","userdetails":'.json_encode($user).'}';
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

function login(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$sql1="select * from wy_customer where mobile=:username";
	$sql2="select * from wy_customer where email=:username";
	$mykey=getEncryptKey();
	$password=encryptPaswd($register->password,$mykey);
	try{
	
		$db = getConnection();
		$stmt = $db->prepare($sql1); 
		$stmt->bindParam("username", $register->username);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if($user){
			if($user->verification_status==1){
				if($user->profile_status==1){
					$sql3="select * from wy_customer where mobile=:username and password=:password";
					$stmt = $db->prepare($sql3); 
					$stmt->bindParam("username", $register->username);
					$stmt->bindParam("password", $password);
					$stmt->execute();
					$userdet = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($userdet){
						$qry="update wy_customer set device_type=:device_type,device_token=:device_token where email=:username";
						$stmt = $db->prepare($qry); 
						$stmt->bindParam("username", $register->username);
						$stmt->bindParam("device_type", $register->device_type);
						$stmt->bindParam("device_token", $register->device_token);
						$stmt->execute();
						echo '{"Result":"Success","Status":"Login successfully","userdetails":'.json_encode($userdet).'}';
					}else{
						echo '{"Result":"Failed","Status":"Invalid password"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Account deactivated"}';
				}
			}else{
				echo '{"Result":"Failed","Status":"Not yet verified","userdetails":'.json_encode($userdet).'}';
			}
		}else{
			$stmt = $db->prepare($sql2); 
			$stmt->bindParam("username", $register->username);
			$stmt->execute();
			$mob = $stmt->fetch(PDO::FETCH_OBJ);
			if($mob){
				if($mob->verification_status==1){
					$sql3="select * from wy_customer where email=:username and password=:password";
					$stmt = $db->prepare($sql3); 
					$stmt->bindParam("username", $register->username);
					$stmt->bindParam("password", $password);
					$stmt->execute();
					$userdet = $stmt->fetchAll(PDO::FETCH_OBJ);
					if($userdet){
						$qry="update wy_customer set device_type=:device_type,device_token=:device_token where email=:username";
						$stmt = $db->prepare($qry); 
						$stmt->bindParam("username", $register->username);
						$stmt->bindParam("device_type", $register->device_type);
						$stmt->bindParam("device_token", $register->device_token);
						$stmt->execute();
						echo '{"Result":"Success","Status":"Login successfully","userdetails":'.json_encode($userdet).'}';
					}else{
						echo '{"Result":"Failed","Status":"Invalid password"}';
					}
				}else{
					echo '{"Result":"Failed","Status":"Not yet verified"}';
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

function fileupload(){
	$request = Slim::getInstance()->request();
	$support = json_decode($request->getBody());
	$date=date("Y-m-d H:i:s");
	
	try {
		$fileName = $_FILES['file']['name'];
		$tmpName  = $_FILES['file']['tmp_name'];
		$path = "/home/armorco/public_html/wrydes/upload/".basename($fileName);
		$img = "armor.co.in/wrydes/upload/".basename($fileName);
		
		if( move_uploaded_file( $tmpName , $path ) ){
			echo '{ "Result": "Success","Details":"File uploaded","image":"'.$img.'"}';
		}else{
			echo '{ "Result": "Failed","Details":"File not uploaded"}';
		}
	
	} catch(PDOException $e) {
	echo '{ "Result": "Failed"}'; 
	}
}

function UserRegister(){

	$request = Slim::getInstance()->request();
	$register = json_decode($request->getBody());
	$date=date('Y-m-d h:i:s');
	$sql="select * from wy_customer where email=:email and mobile=:mobile";
	$sql1="select * from wy_customer where mobile=:mobile";
	$sql2="select * from wy_customer where email=:email";
	$insertquery="insert into wy_customer(name,mobile,email,password,device_type,device_token,OTP,otp_time,added_date,updated_date)
				values(:name,:mobile,:email,:password,:devicetype,:devicetoken,:OTP,:otp_time,:added_date,:updated_date)";
	$verification_code=rand(1111, 9999);
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
				$mykey=getEncryptKey();
				$password=encryptPaswd($register->password,$mykey);
				$stmt = $db->prepare($insertquery); 
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
				$stmt = $db->prepare($sql); 
				$stmt->bindParam("email", $register->email);
				$stmt->bindParam("mobile", $register->mobile);
				$stmt->execute();
				$userdet = $stmt->fetchAll(PDO::FETCH_OBJ);
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
	$dbuser="armorco";
	$dbpass="5Z4Xv72q";
	$dbname="armorco_patrol";
	
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function send_gcm_notify($reg_id, $message,$ride_id) {

	define("FIREBASE_API_KEY", "AIzaSyAmH9TdMle9B0kjwx3BERETPVvDsvZYJ-s");
	define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");
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

function selectsinglevalue($qry)
{
$retval = '';
$res = mysql_query($qry);
$row = mysql_fetch_array($res,MYSQL_ASSOC);
$retval = $row['retv'];
return $retval;
}