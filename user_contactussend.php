<?php 
session_start();
require_once('sendmail.php');
//print_r($_POST);
 $to= array('support@zaireprojects.com');
 //$to=array('support@adynor.com');
 $l_message ="Name:-        ".$_POST['user_name']."<br>";
 $l_message.= "Email:-      ".$_POST['user_email']."<br>";
 $l_message.= "Mobile:-     ".$_POST['user_mobile']."<br><br>";
 $l_message .="Message:-    ".$_POST['user_message']."<br>";
 $subject=    "Contact By  ".$_POST['user_name'];
 
 
 sendmail($to,$subject,$l_message);

 echo "Your message has been sent successfully!!";

 //echo "<script>window.location.href='Projects.php'</script>";
 
 
 
?>
