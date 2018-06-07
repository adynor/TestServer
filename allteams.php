<?php

/* get all the team details to show on admin page ATeams_Track.php*/


session_start();
include('db_config.php');
//$_POST = json_decode(file_get_contents('php://input'), true);

$itid=$_SESSION['g_IT_id'];
$q= "SELECT TM.TM_id FROM  `Users` AS UR, Teams AS TM
WHERE (
UR.UR_id = TM.UR_id_Guide
AND 
UR.IT_id ='".$itid."' AND UR.UR_Type ='G') Order by UR.UR_Type ASC";
$run=mysql_query($q);

$data = array();
while ($row = mysql_fetch_assoc($run)) {
$qf= "SELECT UR.UR_id,UR.UR_Type,UR.UR_FirstName,UR.UR_Emailid,UR.UR_EmailidDomain,TM.TM_Name,TM.TM_id,TM.PR_id,TM.TM_PR_Duration
FROM  `Users` AS UR, Teams AS TM
WHERE TM.TM_id =".$row['TM_id']."
AND (UR.UR_id = TM.UR_id_Mentor OR UR.UR_id = TM.UR_id_Guide) Order by UR.UR_Type ASC";
$qrun=mysql_query($qf);


while($row=mysql_fetch_assoc($qrun)){

$l_proj_query = 'select PD.PD_id,PD.PD_Name, PD.PD_SubmissionDate, AL.AL_Desc, TM.TM_Name, PD.PD_id, PD.PD_Status, PD.PD_Feedback, PD.PD_FeedbackDate, PD.PD_Rating, PD.PD_MFeedback, PD.PD_MFeedbackDate, PD.PD_MRating from Teams as TM, Project_Documents as PD , Access_Level as AL where TM.TM_id = '.$row['TM_id'].' and TM.TM_id = PD.TM_id and AL.AL_id = PD.AL_id order by PD_id DESC' ;

$l_proj_res = mysql_query($l_proj_query) or die(mysql_error());
$l_count = mysql_num_rows($l_proj_res);
$pdstatus= mysql_fetch_assoc($l_proj_res);
if($pdstatus['PD_Status']=="A"){
    $pdstatus['PD_Status']="Accepted";
}
else if($pdstatus['PD_Status']=="P")
{
    $pdstatus['PD_Status']="Pending";
}else if($pdstatus['PD_Status']=="R"){
        $pdstatus['PD_Status']="Rejected";
}

// queries for team start date from teams on the basis of this.
    
    $l_TM_StartDate_query ='select TM_StartDate from Teams where TM_id = "'.$row['TM_id'].'"';   // query for TM_startDate
    $l_TM_result = mysql_query($l_TM_StartDate_query);
    $l_TM_row = mysql_fetch_row($l_TM_result);
    $l_TM_StartDate = $l_TM_row[0];
    
    $l_PR_Duration_query ='select PR_Duration ,PR_SynopsisURL from Projects where PR_id = "'. $row['PR_id'] .'"';
    // query for PR_Duration
    $l_PR_result = mysql_query($l_PR_Duration_query);
    $l_PR_row = mysql_fetch_row($l_PR_result);
    $l_PR_Duration = $l_PR_row[0];      // will return result in days
   
    $l_PR_finalDate = date('d-M-Y',strtotime($l_TM_StartDate) + (24*3600*$l_PR_Duration)); //my preferred method

    
$l_LoginDate_res= date("d-M-Y h:i A", strtotime($row['UR_LastLogin']));
$data[] = array(
       "PR_id" => $row['PR_id'],
       "TM_id" => $row['TM_id'], 
        "TM_Name" => $row['TM_Name'], 
        "UR_FirstName" => $row['UR_FirstName'], 
        "UR_Type" => $row['UR_Type'],
        "UR_lastlogin" => $l_LoginDate_res,
        "UR_Emailid" => $row['UR_Emailid'],
        "UR_EmailidDomain" => $row['UR_EmailidDomain'],
        "PR_Status"=>$pdstatus['PD_Status'],
        "PD_id"=>$pdstatus['PD_id'],
        "Expiry_date"=>$l_PR_finalDate,
        "TM_PR_Duration"=>$row['TM_PR_Duration']
    );
    
}

}

   print json_encode($data);

?>