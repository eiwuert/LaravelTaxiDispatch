<?php
date_default_timezone_set("Asia/Kolkata");
$con=new mysqli('localhost','root','admin','wrydes') or ('error');

define("FIREBASE_API_KEY", "AIzaSyAmH9TdMle9B0kjwx3BERETPVvDsvZYJ-s");
define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");

$date=date('Y-m-d H:i:s');
$date1=date('Y-m-d');
$mobile = $_POST['mobile'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$car_type = $_POST['car_type'];
$name = $_POST['name'];
$email = $_POST['email'];
$ride_type = $_POST['ride_type'];
$location = $_POST['location'];
$destination_location = $_POST['destination_location'];
$destination_lat = $_POST['destination_lat'];
$destination_lng = $_POST['destination_lng'];
$city = $_POST['city'];
$sql2="select u.*,(
			6371 *
			acos(
				cos( radians( '$lat' ) ) *
				cos( radians( d.lat ) ) *
				cos(
					radians( d.lng ) - radians( '$lng' )
				) +
				sin(radians('$lat')) *
				sin(radians(d.lat))
			)
		) distance from wy_driver u join wy_carlist c on u.car_id=c.id 
		join wy_driverlocation d on u.id=d.driver_id 
		where c.car_type='$car_type' and u.status='1' and u.online_status='1' having distance<3 order by distance asc ";

try{
		$getcus = $con->query("select * from wy_customer where mobile like '%$mobile%'");
		if($getcus->num_rows > 0){ 
			$row = $getcus->fetch_object(); 
			$user_id = $row->id;
		}else{
			$insqry = $con->query("insert into wy_customer(name,mobile,email,created_by,created_at,updated_at,registered_by) values('$name','$mobile','$email','$id','$date','$date','3')");
			$user_id = $con->insert_id;
		}
		$sql3=selectsinglevalue("select id as retv from wy_ride order by id desc limit 1");
		if($sql3!=''){
			$ordid = $sql3+1;
			$ref_id = "WYDSTXCBE00".$ordid;
		}else{
			$ref_id = "WYDSTXCBE001";
		}
		if($ride_type=='1'){
			$stmt = $con->query($sql2); 
			if($stmt->num_rows > 0){
				while($val = $stmt->fetch_object()){
					$sql3="select * from wy_ridedetails where driver_id='$val->id'";
					$driverslist = $con->query($sql3);
					if($driverslist->num_rows > 0){
						$sql6="select driver_id from wy_ridedetails where driver_id='$val->id' and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
								ORDER BY `wy_ridedetails`.`id` ASC";
						$driverslistdet = $con->query($sql6);
						if($driverslistdet->num_rows <= 0){
							$fail = 0;
							$sql4="insert into wy_ride(city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at,booked_through) 
								values('$city','$ref_id','$user_id','$location','$lat','$lng','$car_type','$ride_type','$date1','$destination_location','$destination_lat','$destination_lng','$date','$date','3')";
							$ride = $con->query($sql4);
							$ride_id = $con->insert_id;
							$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$val->id','$date','$date')";
							$ride_det = $con->query($sql5);
							$device_type = $val->device_type;
							$device_token = $val->device_token;
							$message = "request for ride";
							if($device_type==1){
								apns($device_token,$message,$ride_id);
							}else{
								//$message = array("message" => $message);
								//$reg_id = array($device_token);
								send_gcm_notify($device_token, $message,$ride_id );
							}
							echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($ride_type).'}';
							break;
						}else{
							$fail = 1;
						}
					}else{
						$fail = 0;
						$sql4="insert into wy_ride(city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at,booked_through) 
							values('$city','$ref_id','$user_id','$location','$lat','$lng','$car_type','$ride_type','$date1','$destination_location','$destination_lat','$destination_lng','$date','$date','3')";
						$ride = $con->query($sql4);
						$ride_id = $con->insert_id;
						$sql5="insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$val->id','$date','$date')";
						$ride_det = $con->query($sql5);
						$device_type = $val->device_type;
						$device_token = $val->device_token;
						$message = "request for ride";
						if($device_type==1){
							apns($device_token,$message,$ride_id);
						}else{
							//$message = array("message" => $message);
							//$reg_id = array($device_token);
							send_gcm_notify($device_token, $message,$ride_id );
						}
						echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($ride_type).'}';
						
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
			$sql4="insert into wy_ride(city,reference_id,customer_id,source_location,source_lat,source_lng,car_type,ride_type,schedule_date,schedule_time,date_of_ride,destination_location,destination_lat,destination_lng,created_at,updated_at,booked_through) 
					values('$city','$ref_id','$user_id','$location','$lat','$lng','$car_type','$ride_type','$schedule_date','$schedule_time','$schedule_date','$destination_location','$destination_lat','$destination_lng','$date','$date','3')";
			$ride = $con->query($sql4);
			$ride_id = $con->insert_id;
			echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($ride_type).'}';
		}
}catch(PDOException $e){
	echo $e;
	echo '{"Result":"Failed"}';
}

function selectsinglevalue($qry)
{
$retval = '';
$res = mysql_query($qry);
$row = mysql_fetch_array($res,MYSQL_ASSOC);
$retval = $row['retv'];
return $retval;
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
