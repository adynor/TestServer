<?php 
include ('db_config.php');
 $l_credit= $_SESSION['g_Credits'];
$l_deduct_credit=$_SESSION['payment'];
$l_PR_id=$_GET['N_PR_id_pay'];
$l_UR_PR_Type=$_GET['l_User_PR_Type'];
$l_UR_id=$_SESSION['g_UR_id'];
$l_org_id=$_SESSION['g_Org_id'];
$timezone = new DateTimeZone("Asia/Kolkata");
$date = new DateTime();
$date->setTimezone($timezone);
$l_Datetime = $date->format('YmdHi');
if($l_credit >= $l_deduct_credit){
$l_remaining_credit=$l_credit-$l_deduct_credit;
 $l_query="UPDATE Users SET UR_Credits=".$l_remaining_credit.",PR_id =".$l_PR_id.",UR_PR_Type='". $l_UR_PR_Type."' WHERE  UR_id ='".$l_UR_id."' AND Org_id='".$l_org_id."'";
$l_res=mysql_query($l_query);
if(!$l_res){ die('Invalid query1: ' . mysql_error()); } else{
   $l_queryprapp='insert into Project_Applications(UR_id,PR_id,PP_ApplicationDate,Org_id) values ("'.$l_UR_id.'",'.$l_PR_id.','.$l_Datetime.',"'.$l_org_id.'")';
    $l_resprapp=mysql_query($l_queryprapp);
    if(!$l_resprapp){ die('Invalid query2: ' . mysql_error()); } else{
    $_SESSION['g_PR_id']=$l_PR_id;
    $_SESSION['g_UR_PR_Type']=$l_UR_PR_Type;
    $_SESSION['g_Credits']=$l_remaining_credit;
    //echo "<script>window.location.href='SHome.php'</script>";
    echo "";
    } 
   } 
} else{
//$_SESSION['error']='<div class="alert alert-danger"> You have insufficient credit </div>';
echo '<div class="alert alert-danger"> You have insufficient credit </div>';
//echo "<script>window.location.href='ProjectPayment.php'</script>";
}

?>