<?php
if(isset($_GET['device_token'])){
	$devicetoken = $_GET['device_token'];
	$message = $_GET['message'];
	$key = $_GET['key'];
    $rideid = $_GET['rideid'];
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