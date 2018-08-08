<?php
date_default_timezone_set('Asia/Kolkata');
$con=mysqli_connect("localhost","root","qV2D5bfuuA/KYAswtR1wHw==","mobycabs");

$get_cartype = "SELECT * FROM wy_cartype";
$q = mysqli_query($con,$get_cartype);

while($row=mysqli_fetch_assoc($q)){

	$id = $row['id'];
	$sql = "SELECT * FROM wy_faredetails WHERE car_id = '$id' and status=1 order by fare_type desc";
	//echo $sql;
	$qe = mysqli_query($con,$sql);
	while($r=mysqli_fetch_assoc($qe)){
		$date = date('Y-m-d H:i:s');
		$date1 = date('Y-m-d');
		//mor time
		$start_time = $r['ride_start_time'];
		$end_time = $r['ride_end_time'];
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
		$nstart_time = $r['nit_start_time'];
		$nend_time = $r['nit_end_time'];
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
		$type = 0;
		if((strtotime($date)>=strtotime($srt_datetime) && strtotime($date)<strtotime($end_datetime)) || (strtotime($date)>=strtotime($nsrt_datetime) && strtotime($date)<strtotime($nend_datetime))){
			if($r['fare_type']==5){ $type = 2; }
			else if($r['fare_type']==4){ $type = 1; }
			else { $type = 0; }
		}elseif($start_time=='00:00:00'){
			$type = 0;
		}
		$postdata = '{"fare":'.$type.'}';
		$ch = curl_init("https://mobycabs-3e9bd.firebaseio.com/peak_time/$id.json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		$result = curl_exec($ch); 
		curl_close($ch);
		break;
	}
}



	 