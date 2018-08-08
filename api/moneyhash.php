<?php

$key=$_POST["key"];

$salt="XXXXXXXX";
$txnId=$_POST["txnid"];
$amount=$_POST["amount"];
$productName=$_POST["productInfo"];
$firstName=$_POST["firstName"];
$email=$_POST["email"];
$udf1=$_POST["udf1"];
$udf2=$_POST["udf2"];
$udf3=$_POST["udf3"];
$udf4=$_POST["udf4"];
$udf5=$_POST["udf5"];

$payhash_str = $key . '|' . checkNull($txnId) . '|' .checkNull($amount)  . '|' .checkNull($productName)  . '|' . checkNull($firstName) . '|' . checkNull($email) . '|' . checkNull($udf1) . '|' . checkNull($udf2) . '|' . checkNull($udf3) . '|' . checkNull($udf4) . '|' . checkNull($udf5) . '|' . $salt;


function checkNull($value) {
            if ($value == null) {
                  return '';
            } else {
                  return $value;
            }
      }


$hash = strtolower(hash('sha512', $payhash_str));
$arr['result'] = $hash;
$arr['status']=0;
$arr['errorCode']=null;
$arr['responseCode']=null;
$output=$arr;


echo json_encode($output);

?>
