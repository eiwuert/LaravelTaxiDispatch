<?php
date_default_timezone_set("Asia/Kolkata");
$con=mysqli_connect("localhost","root","qV2D5bfuuA/KYAswtR1wHw==","mobycabs");

$date = date("Y-m-d H:i:s");
$qry = mysqli_query($con,"select dl.* from wy_driver d join wy_driverlocation dl on d.id=dl.driver_id where d.online_status=1");
if(mysqli_num_rows($qry)){ 
	while($dev = mysqli_fetch_array($qry,MYSQLI_ASSOC)){ 
		$driver_id = $dev['driver_id'];
		$currenttime = date("Y-m-d H:i:s",strtotime("+5 minute",strtotime($dev['updated_date'])));
		if(strtotime($date)>strtotime($currenttime)){
			$qry1 = "select ct.car_type,ct.car_board from wy_assign_taxi a join wy_carlist c on a.car_num=c.id join wy_cartype ct on c.car_type=ct.id where a.status=1 and a.driver_id=$driver_id";
			$qry3 = mysqli_query($con,$qry1);
			$row = mysqli_fetch_array($qry3,MYSQLI_ASSOC);
			$cartype = $row['car_type']."_".$row['car_board'];
			$qry2 = "update wy_driver set online_status=0 where id=$driver_id";
			mysqli_query($con,$qry2);
			
			//firebase
			$header = array();
			
			$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/driver_location/$cartype/$driver_id.json");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$result = curl_exec($ch);
			curl_close($ch); 
		}
	}
}

