<?php 
include('db_config.php');
$email=$_POST['email'];
function checkEmail($email=NULL){
   $query="SELECT UR_id FROM Users WHERE CONCAT(UR_Emailid,'@', UR_EmailidDomain)='".$email."'";
   $res=mysql_query($query);
   $result=mysql_num_rows($res);
   return $result;
 
}
function sendEmail($email=NULL){
    $subject="Wellcome To My Team";
    $l_message="Please Accept My Request And Join as team Member";
    $to=array($email);
    sendmail($to,$subject,$l_message);
}
switch($_GET['fun']) {
    case 'checkEmail':
        $EmailCount=array('count'=>checkEmail($email));
        header('Content-Type: application/json');
        echo json_encode($EmailCount);
        break;
    case 'sendEmail' :
        sendEmail($email);
        break;
}


?>