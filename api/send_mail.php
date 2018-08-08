<?php
$to = $_GET['to'];
$msg = $_GET['message'];
$body = "<h2><a href='http://wrydes.com/download-whitepaper.php?token=$msg>Click to Download</a></h2>";
require_once('PHPMailer_5.2.0/class.phpmailer.php');
$mail       = new PHPMailer();
   $mail->IsSMTP(); // enable SMTP
   $mail->SMTPAuth = true; // authentication enabled
   $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
   $mail->Host = "smtp.gmail.com";
   $mail->Port = 465; // or 587
   $mail->IsHTML(true);
   $mail->Username = 'armortechmobile@gmail.com';
   $mail->Password = 'Armor123';
   $mail->SetFrom('noreply@wrydes.com');
   $mail->AddAddress($to);
   $mail->CharSet = 'UTF-8';
   $mail->Subject    = 'test';
   $mail->MsgHTML($body); 

   if(!$mail->Send()){
    echo "fail";
   }else{
      header('Refresh: 0;url=http://wrydes.com/taxitopia.php?status=success');
    
   }
?>