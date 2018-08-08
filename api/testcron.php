<?php
//PATH=/usr/bin:/usr/local/bin
date_default_timezone_set('Asia/Kolkata');
$con=mysql_connect('localhost','root','admin') or ('error');
mysql_select_db('wrydes',$con);
define("FIREBASE_API_KEY", "AIzaSyAmH9TdMle9B0kjwx3BERETPVvDsvZYJ-s");
define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");

$date = date("Y-m-d H:i:s");
$date1 = date("Y-m-d");
$qry = mysql_query("select r.*,c.mobile,ct.capacity from wy_ride r join wy_customer c on c.id=r.customer_id join wy_cartype ct on ct.id=r.car_type where r.ride_type='2' and r.ride_status='0' and r.schedule_date='$date1'");
if(mysql_num_rows($qry)>0){ 
	while($dev = mysql_fetch_array($qry)){ 
		$fail = 1;
		$mobile = $dev['mobile'];
		echo $schedule_time = $dev['schedule_date']." ".$dev['schedule_time'];
		$schedule_time = date("Y-m-d H:i:s",strtotime($schedule_time));
		$schedule_time1 = date("Y-m-d H:i:s",strtotime("-5 minute",strtotime($schedule_time)));
		$booktime = date("Y-m-d H:i:s",strtotime("-30 minute",strtotime($schedule_time)));
		if((strtotime($date)>=strtotime($booktime)) && (strtotime($date)<=strtotime($schedule_time1))){ echo "in";
			$customer_id = $dev['customer_id'];
			$source_lat = $dev['source_lat'];
			$source_lng = $dev['source_lng'];
			$car_type = $dev['car_type'];
			$ride_id = $dev['id'];
			$capacity = $dev['capacity'];
			$ride_category = $dev['ride_category'];
			$car_board = selectsinglevalue("select car_board as retv from wy_cartype where id='$car_type'");
			$qry2 = mysql_query("select * from wy_ridedetails where ride_id='$ride_id' and accept_status='0'");
			$qry3 = mysql_query("select rd.driver_id from wy_ride r join wy_ridedetails rd on r.id=rd.ride_id where r.customer_id='$customer_id' and (rd.accept_status in ('0','1') and rd.ride_status in ('0','1','2','3')) and r.ride_type='1'");
			
			if(mysql_num_rows($qry3)<=0){ 
				if(mysql_num_rows($qry2)<=0){ 
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
						where c.car_type='$car_type' and u.status='1' and u.online_status='1' 
						and u.id not in (select driver_id from wy_ridedetails where  accept_status = '2' and ride_id='$ride_id') and a.status='1'
						having 	distance<3 order by distance asc ";
						
					echo $sql4; exit;
					$gettaxi = mysql_query($sql4); 
					$numrw = mysql_num_rows($gettaxi);
					//$response = file_get_contents('http://forty-fivene.com/tcpdf/examples/compositereport.php?user_id=1&fromdate=2016-12-21&todate=2017-01-20&email=gotocva@gmail.com');
				//echo $response;
					if($numrw > 0){  

						while($drivers = mysql_fetch_array($gettaxi)){ 
							$drv_id = $drivers['id'];
							$qry1=mysql_query("select * from wy_ridedetails where driver_id='$drv_id'");
							if(mysql_num_rows($qry1)>0){
								
								$sql6=mysql_query("select driver_id from wy_ridedetails where driver_id='$drv_id' and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
										ORDER BY `wy_ridedetails`.`id` ASC");
								if(mysql_num_rows($sql6)==0){
									$fail = 0;
									$sql5=mysql_query("insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$drv_id','$date','$date')");
									$device_type = $drivers['device_type'];
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
									/// firebase customer
										$date_fmt = date("d-m-Y");
										$header = array();
										$header[] = 'Content-Type: application/json';
										$postdata = '{"ride_type":"2","ride_category":"'.$dev['ride_category'].'","profile_photo":"","source_lng":"'.$dev['source_lng'].'","source_lat":"'.$dev['source_lat'].'","destination_lng":"'.$dev['destination_lng'].'","destination_lat":"'.$dev['destination_lat'].'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
										
										$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/customer/$ride_id.json");
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
										$postdata = '{"ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev['car_type'].'","reference_id":"'.$dev['reference_id'].'","customer_id":"'.$dev['customer_id'].'","source_location":"'.$dev['source_location'].'","source_lat":"'.$dev['source_lat'].'","source_lng":"'.$dev['source_lng'].'","ride_type":"'.$dev['ride_type'].'","ride_category":"'.$dev['ride_category'].'","booking_type":"'.$dev['booking_type'].'","destination_location":"'.$dev['destination_location'].'","destination_lat":"'.$dev['destination_lat'].'","destination_lng":"'.$dev['destination_lng'].'"}';
										
										$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/driver/$drv_id.json");
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
										curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
										//curl_setopt($ch, CURLOPT_POST,1);
										curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
										curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
										$result = curl_exec($ch); 
										curl_close($ch); 
										///firebase
									//break;
									exit;
								}else{
									$fail = 1;
								}
							}else{
								$fail = 0;
								$sql5=mysql_query("insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$drv_id','$date','$date')");
								$device_type = $drivers['device_type'];
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
								/// firebase customer
									echo $date_fmt = date("d-m-Y");
									$header = array();
									$header[] = 'Content-Type: application/json';
									$postdata = '{"ride_type":"2","ride_category":"'.$dev['ride_category'].'","profile_photo":"","source_lng":"'.$dev['lng'].'","source_lat":"'.$dev['lat'].'","destination_lng":"'.$dev['destination_lng'].'","destination_lat":"'.$dev['destination_lat'].'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
									
									$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/customer/$ride_id.json");
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
									$postdata = '{"ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev['car_type'].'","reference_id":"'.$dev['reference_id'].'","customer_id":"'.$dev['customer_id'].'","source_location":"'.$dev['source_location'].'","source_lat":"'.$dev['source_lat'].'","source_lng":"'.$dev['source_lng'].'","ride_type":"'.$dev['ride_type'].'","ride_category":"'.$dev['ride_category'].'","booking_type":"'.$dev['booking_type'].'","destination_location":"'.$dev['destination_location'].'","destination_lat":"'.$dev['destination_lat'].'","destination_lng":"'.$dev['destination_lng'].'"}';
									echo "https://go-cabs-7c7b5.firebaseio.com/ride_info/driver/$drv_id.json";
									$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/driver/$drv_id.json");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
									//curl_setopt($ch, CURLOPT_POST,1);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
									curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
									$result = curl_exec($ch); 
									curl_close($ch); 
									///firebase
								//break;
								exit;
							}
						}
					}else{ echo "nextlp";
						$sql = "select * from wy_cartype where ride_category='$ride_category' and capacity >= $capacity and id!='$car_type' order by capacity asc";
						$getcartype = mysql_query($sql);
						if(mysql_num_rows($getcartype)>0){
							while($getrw = mysql_fetch_array($getcartype)){
								$car_type1 = $getrw['id'];
								$car_name = $getrw['car_type'];
								$car_board = $getrw['car_board'];
								if($car_board==1) $txt = "White board";
								else if($car_board==2) $txt = "Yellow board";
								else $txt = "";
								echo $sql2="select u.*,(
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
								where c.car_type='$car_type1' and u.status='1' and u.online_status='1' 
								and u.id not in (select driver_id from wy_ridedetails where  accept_status = '2' and ride_id='$ride_id') and a.status='1'
								having 	distance<3 order by distance asc ";
								$gettaxi = mysql_query($sql2);
								echo mysql_num_rows($gettaxi);
								if(mysql_num_rows($gettaxi)>0){ echo "higher";
									while($drivers = mysql_fetch_array($gettaxi)){
										$drv_id = $drivers['id'];
										$qry1=mysql_query("select * from wy_ridedetails where driver_id='$drv_id'");
										if(mysql_num_rows($qry1)>0){
											
											$sql6=mysql_query("select driver_id from wy_ridedetails where driver_id='$drv_id' and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
													ORDER BY `wy_ridedetails`.`id` ASC");
											if(mysql_num_rows($sql6)==0){
												$fail = 0;
												$sql5=mysql_query("insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$drv_id','$date','$date')");
												$device_type = $drivers['device_type'];
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
												
												$message   =  "Your car is upgraded to $car_name $txt.";
												$message = urlencode($message);
												$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
												curl_setopt($ch, CURLOPT_HEADER, 0);
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												$output = curl_exec($ch);      
												curl_close($ch);
												
												/// firebase customer
													$date_fmt = date("d-m-Y");
													$header = array();
													$header[] = 'Content-Type: application/json';
													$postdata = '{"ride_type":"2","ride_category":"'.$dev['ride_category'].'","profile_photo":"","source_lng":"'.$dev['lng'].'","source_lat":"'.$dev['lat'].'","destination_lng":"'.$dev['destination_lng'].'","destination_lat":"'.$dev['destination_lat'].'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
													
													$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/customer/$ride_id.json");
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
													$postdata = '{"ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$car_type1.'","reference_id":"'.$dev['reference_id'].'","customer_id":"'.$dev['customer_id'].'","source_location":"'.$dev['source_location'].'","source_lat":"'.$dev['source_lat'].'","source_lng":"'.$dev['source_lng'].'","ride_type":"'.$dev['ride_type'].'","ride_category":"'.$dev['ride_category'].'","booking_type":"'.$dev['booking_type'].'","destination_location":"'.$dev['destination_location'].'","destination_lat":"'.$dev['destination_lat'].'","destination_lng":"'.$dev['destination_lng'].'"}';
													
													$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/driver/$drv_id.json");
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
													curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
													//curl_setopt($ch, CURLOPT_POST,1);
													curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
													curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
													$result = curl_exec($ch); 
													curl_close($ch); 
													///firebase
												//break;
												exit;
											}else{
												$fail = 1;
											}
										}else{
											$fail = 0;
											$sql5=mysql_query("insert into wy_ridedetails(ride_id,driver_id,created_at,updated_at) values('$ride_id','$drv_id','$date','$date')");
											$device_type = $drivers['device_type'];
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
												
												$message   =  "Your car is upgraded to $car_name $txt.";
												$message = urlencode($message);
												$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
												curl_setopt($ch, CURLOPT_HEADER, 0);
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												$output = curl_exec($ch);      
												curl_close($ch);
												
											/// firebase customer
												echo $date_fmt = date("d-m-Y");
												$header = array();
												$header[] = 'Content-Type: application/json';
												$postdata = '{"ride_type":"2","ride_category":"'.$dev['ride_category'].'","profile_photo":"","source_lng":"'.$dev['lng'].'","source_lat":"'.$dev['lat'].'","destination_lng":"'.$dev['destination_lng'].'","destination_lat":"'.$dev['destination_lat'].'","mobile":"","black_caricon":"","name":"","brand":"","model":"","reg_no":"","accept_status":"0","ride_status":"0","car_availability":"0"}';
												
												$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/customer/$ride_id.json");
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
												$postdata = '{"ride_id":"'.$ride_id.'","accept_status":"0","ride_status":"0","accept_time":"","car_board":"'.$car_board.'","car_type":"'.$dev['car_type'].'","reference_id":"'.$dev['reference_id'].'","customer_id":"'.$dev['customer_id'].'","source_location":"'.$dev['source_location'].'","source_lat":"'.$dev['source_lat'].'","source_lng":"'.$dev['source_lng'].'","ride_type":"'.$dev['ride_type'].'","ride_category":"'.$dev['ride_category'].'","booking_type":"'.$dev['booking_type'].'","destination_location":"'.$dev['destination_location'].'","destination_lat":"'.$dev['destination_lat'].'","destination_lng":"'.$dev['destination_lng'].'"}';
												$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/driver/$drv_id.json");
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
												//curl_setopt($ch, CURLOPT_POST,1);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
												curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
												$result = curl_exec($ch); 
												curl_close($ch); 
												///firebase
											//break;
											exit;
										}
									}
								}
							}
						}else{
							$fail = 1;
						}
					}
				}
			}else{
				$upqry=mysql_query("update wy_ride set ride_status='2',cancle_time='$date' where id='$ride_id'");
				$message   =  "Your scheduled ride has been cancelled.";
				$message = urlencode($message);
				$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);      
				curl_close($ch);
				
				/// firebase
					$date_fmt = date("d-m-Y");
					$header = array();
					$header[] = 'Content-Type: application/json';
					$postdata = '{"accept_status":"","ride_status":"5","car_availability":"2"}';
					
					$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/customer/$ride_id.json");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					//curl_setopt($ch, CURLOPT_POST,1);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
					$result = curl_exec($ch); 
					curl_close($ch); 
					///firebase
			}
			if(($fail == 1) && (strtotime($date)>=strtotime($schedule_time1))){
				$upqry=mysql_query("update wy_ride set ride_status='3' where id='$ride_id'");
				$message   =  "No cabs available for your scheduled ride.";
				$message = urlencode($message);
				$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);      
				curl_close($ch);
				
				/// firebase
					$date_fmt = date("d-m-Y");
					$header = array();
					$header[] = 'Content-Type: application/json';
					$postdata = '{"accept_status":"","ride_status":"","car_availability":"3"}';
					
					$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/customer/$ride_id.json");
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
		if(strtotime($date)>=strtotime($schedule_time1)){
			$rid=$dev['id'];
			$upqry=mysql_query("update wy_ride set ride_status='3' where id='".$dev['id']."'");
			$message   =  "No cabs available for your scheduled ride.";
			$message = urlencode($message);
			$ch = curl_init("http://smshorizon.co.in/api/sendsms.php?user=codekhadi&apikey=u087RHPOMtrX0zpcRBCN&mobile=".$mobile."&message=".$message."&senderid=CODEKH&type=txt"); 
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);      
			curl_close($ch);
			
			/// firebase
				$date_fmt = date("d-m-Y");
				$header = array();
				$header[] = 'Content-Type: application/json';
				$postdata = '{"accept_status":"","ride_status":"","car_availability":"3"}';
				
				$ch = curl_init("https://go-cabs-7c7b5.firebaseio.com/ride_info/customer/$rid.json");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				//curl_setopt($ch, CURLOPT_POST,1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				$result = curl_exec($ch); 
				curl_close($ch); 
				///firebase
		}
		//$upqry=mysql_query("update wy_ride set ride_status='3' where id='".$dev['id']."'");
	}
}

/* $sql3 = "select * from wy_driver where online_status='0'";
$stmt = mysql_query($sql3);
if(mysql_num_rows($stmt)>0){
	while($driver = mysql_fetch_array($stmt)){
		$driver_id = $driver['id'];
		$qry = mysql_query("select r.* from wy_ridedetails d join wy_ride r on d.ride_id=r.id where d.accept_status='0' and d.ride_status='0' and d.driver_id='$driver_id'");
		if(mysql_num_rows($qry)){
			$dev = mysql_fetch_array($qry);
			$source_lat = $dev['source_lat'];
			$source_lng = $dev['source_lng'];
			$car_type = $dev['car_type'];
			$ride_id = $dev['id'];
			$qry2 = "update wy_ridedetails set accept_status='2' WHERE driver_id =  '$driver_id' and ride_id='$ride_id'";
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
			) distance from wy_driver u join wy_carlist c on u.car_id=c.id 
			join wy_driverlocation d on u.id=d.driver_id 
			where c.car_type='$car_type' and u.status='1' and u.online_status='1' 
			and u.id not in (select driver_id from wy_ridedetails where  accept_status = '2' and driver_id!='$driver_id' and ride_id='$ride_id') 
			having 	distance<3 order by distance asc ";
			$gettaxi = mysql_query($sql4); 
			if(mysql_num_rows($gettaxi)>0){
				while($drivers = mysql_fetch_array($gettaxi)){
					$drv_id = $drivers['id'];
					$qry1=mysql_query("select * from wy_ridedetails where ride_status in ('1,2,3') and driver_id='$drv_id'");
					if(mysql_num_rows($qry1)>0){
						
						$sql6=mysql_query("select driver_id from wy_ridedetails where driver_id='$drv_id' and ((accept_status in ('0','1') and ride_status in ('0','1','2','3')))
								ORDER BY `wy_ridedetails`.`id` ASC");
						if(mysql_num_rows($sql6)>0){
							$fail = 0;
							$sql5=mysql_query("insert into wy_ridedetails(ride_id,driver_id,added_date,updated_date) values('$ride_id','$drv_id','$date','$date')");
							$device_type = $drivers['device_type'];
							$device_token = $drivers['device_token'];
							if($device_type==1){
								
							}else{
								$message = "request for ride";
								//$message = array("message" => $message);
								//$reg_id = array($device_token);
								send_gcm_notify($device_token, $message,$ride_id );
							}
							//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
							break;
						}else{
							$fail = 1;
						}
					}else{
						$fail = 0;
						$sql5=mysql_query("insert into wy_ridedetails(ride_id,driver_id,added_date,updated_date) values('$ride_id','$drv_id','$date','$date')");
						$device_type = $drivers['device_type'];
						$device_token = $drivers['device_token'];
						if($device_type==1){
							
						}else{
							$message = "request for ride";
							//$message = array("message" => $message);
							//$reg_id = array($device_token);
							send_gcm_notify($device_token, $message,$ride_id );
						}
						//echo '{"Result":"Success","Status":"Booking confrimed","tripid":'.json_encode($ride_id).',"ride_type":'.json_encode($register->ride_type).'}';
						break;
					}
				}
			}else{
				$upqry=mysql_query("update wy_ride set ride_status='3' where id='$register->ride_id'");
			}
		}
	}
} */
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
	$apnsCert = 'GoPartner.pem'; //apple_push_notification_production.pem'; //'apns-dev.pem';
	$options = array('ssl' => array(
	'local_cert' => 'GoPartner.pem',
	'passphrase' => 'armor'
	));
	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, $options);
	$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
	$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $devicetoken)) . chr(0) . chr(strlen($payload)) . $payload;
	fwrite($apns, $apnsMessage);
	fclose($apns);
}