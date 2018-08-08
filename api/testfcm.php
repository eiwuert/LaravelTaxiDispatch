<?php

$response = array("error" => FALSE);

function send_gcm_notify($reg_id, $message) {

	define("FIREBASE_API_KEY", "AIzaSyAmH9TdMle9B0kjwx3BERETPVvDsvZYJ-s");
	define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");
	$fields = array(
		'to' => $reg_id ,
		'priority' => "high",
		'notification' => array( "tag"=>"chat", "body" => $message ),
	);
	echo "<br>";
	echo json_encode($fields);
	echo "<br>";
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
	echo $result;
}

$reg_id = "fm05e1QVB_Q:APA91bG-LHAsKPbXPNuSWGHCmPdL2DZzS93AppclRP3lcH7BuhYVjvutNpA2s86L4wERUP1IakSU4q0k9E_XjACLMDPKOZAGPCa9ISBEq_sEprxcyPdVujDOJi9zEpcGJag2iC4wISNy";
$msg = "hai this is test notify";

send_gcm_notify($reg_id, $msg);

?>

