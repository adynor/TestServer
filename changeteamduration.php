<?php
session_start();
include('db_config.php');
$_POST = json_decode(file_get_contents('php://input'), true);

$itid=$_SESSION['g_IT_id'];
$l_TM_id=$_POST['tmid']; 
$l_PR_id=$_POST['prid'];
$prduration=$_POST['prduration'];
echo $l_proj_query = 'update Teams set TM_PR_Duration="'.$prduration.'" where PR_id="'.$l_PR_id.'" and TM_id="'.$l_TM_id.'"' ;

$l_proj_res = mysql_query($l_proj_query) or die(mysql_error());
//$l_count = mysql_num_rows($l_proj_res);
if($l_proj_res)
{ 

$data = array();

$data[] = 'done';

}else{
    
    echo "error";
}
 print json_encode($data);

?>