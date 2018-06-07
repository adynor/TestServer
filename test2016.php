<?php
include ('sendmail.php');
$to=array('ankur@adynor.com');
$subject="SMTP mail It will boot your system";
$body="everything doesnot have body";
sendmail($to,$subject,$body);
/*
require 'PHPMailer/class.phpmailer.php';
require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'localhost';
$mail->SMTPAuth = true;
$mail->Username = 'zaireprojects';
$mail->Password = '4Dyn0rtech!';
$mail->SMTPSecure = 'tls';
$mail->Port       = 25;
$mail->SMTPDebug = 2;

$mail->From = 'support@zaireprojects.com';
$mail->FromName = 'Zaire Support';
$mail->addAddress('ankur@adynor.com');

$mail->isHTML(true);

$mail->Subject = 'Test Mail Subject!';
$mail->Body    = 'This is SMTP Email Test';
$mail->AddReplyTo("support@zaireprojects.com","Zaire Support");


if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
 } else {
    echo 'Message has been sent';
}*/
//echo '<script>window.location.href="login.php"</script>';
?>