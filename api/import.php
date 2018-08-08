<?php
  header('Content-Type: text/html; charset=utf-8');
$con = mysql_connect("localhost", "root", "admin")or die(mysql_error());
$db_selected = mysql_select_db("goapp")or die(mysql_error());

$sql = mysql_query("select id from wy_driver where id in ('7','11','10','13','23','15','16','21','22','27','28','29','30','31','32')");
if(mysql_num_rows($sql)>0){ echo "test";
	while($rw = mysql_fetch_array($sql)){
		$drvr_id = $rw['id'];
		$sql1=mysql_query("select * from wy_ridedetails where driver_id='$drvr_id'");
		if(mysql_num_rows($sql1)>0){
			while($rw1 = mysql_fetch_array($sql1)){
				$ride_id = $rw1['ride_id'];
				mysql_query("delete from wy_ride where id='$ride_id'");
				mysql_query("delete from wy_customerrate where ride_id='$ride_id'");
			}
		}
		mysql_query("delete from wy_ridedetails where driver_id='$drvr_id'");
		mysql_query("delete from wy_driver where id='$drvr_id'");
	}
}
$sql2 = mysql_query("select * from wy_assign_taxi where driver_id in ('7','11','10','13','23','15','16','21','22','27','28','29','30','31','32')");
if(mysql_num_rows($sql)>0){
	while($rw2 = mysql_fetch_array($sql2)){
		$car_id=$rw2['car_num'];
		mysql_query("delete from wy_assign_taxi where car_num='$car_id'");
		mysql_query("delete from wy_carlist where id='$car_id'");
	}
}
		