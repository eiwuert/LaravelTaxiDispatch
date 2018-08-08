<?php
date_default_timezone_set("Asia/Kolkata");
$con=mysqli_connect("localhost","root","qV2D5bfuuA/KYAswtR1wHw==","mobycabs");

$thisdate = date('Y-m-d');
$sql = "UPDATE wy_offers SET is_experied = 1 WHERE valid_to < '$thisdate'";
$q = mysqli_query($con,$sql);

?>