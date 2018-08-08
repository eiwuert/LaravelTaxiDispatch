<?php
date_default_timezone_set("Asia/Kolkata");
$con=mysqli_connect("localhost","root","qV2D5bfuuA/KYAswtR1wHw==","mobycabs");
define("FIREBASE_API_KEY", "AIzaSyDbqS-8FHm2axOL16g3obJz5FjbXRfS8ck");
define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");

$date = date("Y-m-d H:i:s");
$qry = mysqli_query($con,"select r.*,d.id as did,d.driver_id,d.created_at as created_att  from wy_ridedetails d join wy_ride r on d.ride_id=r.id where d.accept_status=0 and r.ride_type=1 and d.ride_status=0 and r.ride_status=0");
if(mysqli_num_rows($qry)>0){ 
	while($dev = mysqli_fetch_array($qry,MYSQLI_ASSOC)){ 
		echo $booktime = date("Y-m-d H:i:s",strtotime("+1 minute",strtotime($dev['created_att'])));
		if(strtotime($date)>strtotime($booktime)){ //mail("priyadharsini30591@gmail.com","instatus","1");
			$source_lat = $dev['source_lat'];
			$source_lng = $dev['source_lng'];
			$car_type = $dev['car_type'];
			$ride_type = $dev['ride_type'];
			echo $ride_id = $dev['id'];
			$did = $dev['did'];
			$driver_id = $dev['driver_id'];
			$car_board = selectsinglevalue($con,"select car_board as retv from wy_cartype where id='$car_type'");
			$qry2 = mysqli_query($con,"update wy_ridedetails set accept_status='2' WHERE id =  '$did' and ride_id='$ride_id'");
			
			/// firebase driver for delete			
			$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$driver_id.json");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			$result = curl_exec($ch); 
			curl_close($ch); 
			///firebase
			if($ride_type==1){
				$sql4="select u.*,(
					6371 *
					acos(
						cos( radians('$source_lat') ) *
						cos( radians( d.lat ) ) *
						cos(
							radians( d.lng ) - radians( '$source_lng' )
						) +
						sin(radians('$source_lat')) *
						sin(radians(d.lat))
					)
				) distance from wy_driver u join wy_assign_taxi a on a.driver_id=u.id join wy_carlist c on a.car_num=c.id 
				join wy_driverlocation d on u.id=d.driver_id 
				where c.car_type=$car_type 
				and u.id not in (select driver_id from wy_ridedetails where  accept_status = 2 and ride_id=$ride_id) 
				having 	distance<3 order by distance asc ";
				//echo $sql4;
				$gettaxi = mysqli_query($con,$sql4);
				$row=mysqli_num_rows($gettaxi);				//mail("priyadharsini30591@gmail.com","num","$row");
				if(mysqli_num_rows($gettaxi)>0){ 
					while($drivers = mysqli_fetch_array($gettaxi,MYSQLI_ASSOC)){
						echo $drv_id = $drivers['id']; 
						$online_status = $drivers['online_status']; //mail("priyadharsini30591@gmail.com","onlinestatus","$online_status");
						if($online_status==1){
							/* $qry1=mysqli_query($con,"select * from wy_ridedetails where driver_id=$drv_id");
							if(mysqli_num_rows($qry1)>0){ */
								$sql6=mysqli_query($con,"select driver_id from wy_ridedetails where driver_id=$drv_id and (accept_status in ('0,1') and ride_status in ('0,1,2,3'))");
								$row = mysqli_num_rows($sql6);
								if(mysqli_num_rows($sql6)<=0){ 
									$fail = 0;
									$sql5=mysqli_query($con,"insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$drv_id','$date','$date')");
									$device_type = $drivers['device_type'];
									echo $device_token = $drivers['device_token'];
									$message = "request for ride";
									if($device_type==1){
										apns($device_token,$message,$ride_id);
									}else{
										//$message = array("message" => $message);
										//$reg_id = array($device_token);
										send_gcm_notify($device_token, $message,$ride_id );
									}
									//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
									$sql6="update wy_driver set online_status=0 where id=$drv_id";
									$stmt = mysqli_query($con,$sql6);
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
										echo $postdata = '{"request_time":"'.$date.'","ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev['car_type'].'","reference_id":"'.$dev['reference_id'].'","customer_id":"'.$dev['customer_id'].'","source_location":"'.$dev['source_location'].'","source_lat":"'.$dev['source_lat'].'","source_lng":"'.$dev['source_lng'].'","ride_type":"'.$dev['ride_type'].'","ride_category":"'.$dev['ride_category'].'","booking_type":"'.$dev['booking_type'].'","destination_location":"'.$dev['destination_location'].'","destination_lat":"'.$dev['destination_lat'].'","destination_lng":"'.$dev['destination_lng'].'"}';
										
										$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$drv_id.json");
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
										curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
										//curl_setopt($ch, CURLOPT_POST,1);
										curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
										curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
										$result = curl_exec($ch); 
										curl_close($ch); 
										///firebase
									
									exit;
								}else{
									$fail = 1;
								}
							/* }else{
								$fail = 0;
								$sql5=mysqli_query($con,"insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$drv_id','$date','$date')");
								echo $device_type = $drivers['device_type'];
								$device_token = $drivers['device_token'];
								$message = "request for ride";
								if($device_type==1){
									apns($device_token,$message,$ride_id);
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
									$postdata = '{"request_time":"'.$date.'","ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev['car_type'].'","reference_id":"'.$dev['reference_id'].'","customer_id":"'.$dev['customer_id'].'","source_location":"'.$dev['source_location'].'","source_lat":"'.$dev['source_lat'].'","source_lng":"'.$dev['source_lng'].'","ride_type":"'.$dev['ride_type'].'","ride_category":"'.$dev['ride_category'].'","booking_type":"'.$dev['booking_type'].'","destination_location":"'.$dev['destination_location'].'","destination_lat":"'.$dev['destination_lat'].'","destination_lng":"'.$dev['destination_lng'].'"}';
									
									$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/ride_info/driver/$drv_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
									
								exit;
							} */
						}else{
							$fail = 1;
						}
					}
				}else{
					$fail = 1;
				}
				if($fail > 0){
					$upqry=mysqli_query($con,"update wy_ride set ride_status='3' where id='$ride_id'");
					$sql3 = "select r.*,c.mobile,c.fb_id from wy_ride r join wy_customer c on c.id=r.customer_id where r.id='$ride_id'";
					$stmt = mysqli_query($con,$sql3);
					$dev1 = mysqli_fetch_array($stmt,MYSQLI_ASSOC);
					
					/// firebase
						$date_fmt = date("d-m-Y");
						$header = array();
						$header[] = 'Content-Type: application/json';
						$postdata = '{"accept_status":"","ride_status":"","car_availability":"3"}';
						
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
			}
		}
	}
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
	//$apnsHost = 'gateway.sandbox.push.apple.com';
	$apnsHost = 'gateway.push.apple.com';
	$apnsPort = 2195;
	//$apnsCert = 'GoPartner.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	$apnsCert = 'GOPartner_pro.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	$options = array('ssl' => array(
	//'local_cert' => 'GoPartner.pem',
	'local_cert' => 'GOPartner_pro.pem',
	'passphrase' => 'armor'
	));
	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, $options);
	$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
	$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
	fwrite($apns, $apnsMessage);
	fclose($apns);
}

function selectsinglevalue($con,$qry)
{
$retval = '';
$res = mysqli_query($con,$qry);
$row = mysqli_fetch_array($res,MYSQL_ASSOC);
$retval = $row['retv'];
return $retval;
}