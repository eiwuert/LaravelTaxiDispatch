<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$checkSum = "";



//Here checksum string will return by getChecksumFromArray() function.
$checkSum = getChecksumFromArray($_POST,PAYTM_MERCHANT_KEY);
//print_r($_POST);

 echo json_encode(array("CHECKSUMHASH" => $checkSum,"ORDER_ID" => $_POST["ORDER_ID"], "payt_STATUS" => "1"));

  //Sample response return to SDK
 
//  {"CHECKSUMHASH":"GhAJV057opOCD3KJuVWesQ9pUxMtyUGLPAiIRtkEQXBeSws2hYvxaj7jRn33rTYGRLx2TosFkgReyCslu4OUj\/A85AvNC6E4wUP+CZnrBGM=","ORDER_ID":"asgasfgasfsdfhl7","payt_STATUS":"1"} 
 

?>
