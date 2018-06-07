<?php
session_start();
include('db_config.php');
$_POST = json_decode(file_get_contents('php://input'), true);

$itid=$_SESSION['g_IT_id'];
$l_TM_id=$_POST['tmid']; 


$l_proj_query = 'select PD.PD_Name, PD.PD_SubmissionDate, AL.AL_Desc, TM.TM_Name, PD.PD_id, PD.PD_Status, PD.PD_Feedback, PD.PD_FeedbackDate, PD.PD_Rating, PD.PD_MFeedback, PD.PD_MFeedbackDate, PD.PD_MRating from Teams as TM, Project_Documents as PD , Access_Level as AL where TM.TM_id = '.$l_TM_id.' and TM.TM_id = PD.TM_id and AL.AL_id = PD.AL_id' ;

$l_proj_res = mysql_query($l_proj_query) or die(mysql_error());
$l_count = mysql_num_rows($l_proj_res);
if($l_count>0)
{ 
$run=mysql_query($l_proj_query);

$data = array();
while ($row = mysql_fetch_assoc($run)) {

$data[] = $row;
    }
}
 print json_encode($data);

?>