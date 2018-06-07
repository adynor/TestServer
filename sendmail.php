<?php

require 'PHPMailer/class.phpmailer.php';
require 'PHPMailer/PHPMailerAutoload.php';

function sendmail($to,$subject,$body){
$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'localhost';
$mail->SMTPAuth = true;
$mail->Username = 'zaireprojects';
$mail->Password = '4Dyn0rtech!';
$mail->SMTPSecure = 'tls';
$mail->Port       = 25;
//$mail->SMTPDebug = 2;

$mail->From = 'support@zaireprojects.com';
$mail->FromName = 'Zaire Support';
foreach($to as $email){
$mail->addAddress($email);
}
$mail->isHTML(true);

$mail->Subject = $subject;
$mail->Body    = $body;
$mail->AddReplyTo("support@zaireprojects.com","Zaire Support");

if(!$mail->send()) {
    $myfile = fopen("Emailerrorlogs.txt", "a+") or die("Unable to open file!");
    $msg= PHP_EOL .'TO:'.implode($to,',').PHP_EOL;
    $msg.=' Error:-'.$mail->ErrorInfo;
fwrite($myfile,$msg);
fclose($myfile);
 } else {
    $myfile = fopen("EmailsucessLogs.txt", "a+") or die("Unable to open file!");
    $msg=PHP_EOL .'TO:'.implode($to,',').PHP_EOL.'Subject:-'.$subject.PHP_EOL.'Body:-'.$body;
fwrite($myfile,$msg);
fclose($myfile);
}

}
?>