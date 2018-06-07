<?php
include ('db_config.php');

$l_UR_USN             = $_SESSION['g_UR_USN']; // this is needed by the SQLs that run in this php
$l_UR_id              = $_SESSION['g_UR_id'];

$l_UR_Receiver = $l_UR_id;


$g_updSQL	=$_REQUEST['g_updSQL'];
$g_updSQL	=str_replace("\\","",$g_updSQL);
$l_arry = explode("|",$g_updSQL);
$l_choice = $l_arry[0];
$l_TM_id = $l_arry[1];

// Get DateTime
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_Datetime = $date->format( 'YmdHi' );
    
// Get UR_Sender from Communication table
      $l_ur_sender_query = 'select UR_Sender from Communications where UR_Receiver = "'.$l_UR_Receiver.'" and CM_Type = \'GR\' and Org_id = "'.$_SESSION['g_Org_id'].'"';
    $l_UR_Sender_result = mysql_query($l_ur_sender_query);
    $l_UR_sender_row = mysql_fetch_row($l_UR_Sender_result);
    $l_UR_Sender = $l_UR_sender_row[0];

$l_query_ReceiverName = 'select UR_FirstName, UR_LastName from Users where UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
       $l_result_ReceiverName = mysql_query($l_query_ReceiverName) or die(mysql_error());
$l_row_ReceiverName =mysql_fetch_row($l_result_ReceiverName);
$l_UR_NameReceiver = $l_row_ReceiverName[0];

    if($l_choice=='Accept')
    {
        $l_PR_id = $l_arry[2];
        

        $timezone = new DateTimeZone("Asia/Kolkata" );
        $date = new DateTime();
        $date->setTimezone($timezone );
        $l_Datetime = $date->format( 'YmdHi' );

        $l_CM_Message = 'Guide request Accepted';
        $l_CM_Type = 'GR';

        $l_count_query = 'select count(*) from Guide_Requests as GR where GR.GR_ResponseDateTime is NULL and GR.UR_id= "'.$l_UR_id.'" and GR.Org_id = "'.$_SESSION['g_Org_id'].'" ';
        $l_count_res = mysql_query($l_count_query);
        $l_count_row = mysql_fetch_row($l_count_res);
        $l_count = $l_count_row[0];

        if($l_count == 1)
            {

                $l_upd_users = 'Update Users set TM_id = null where UR_id = "'.$l_UR_id.'"  and Org_id = "'.$_SESSION['g_Org_id'].'"';
                mysql_query($l_upd_users);
            }
        // Get DateTime
        $timezone = new DateTimeZone("Asia/Kolkata" );
        $l_TM_EndDate = $date->format( 'Ymd' );

        // Update Teams
        $l_TM_upd_query = 'Update Teams set UR_id_Guide = "'.$l_UR_Receiver.'", TM_StartDate = "'.$l_Datetime.'" where TM_id = '.$l_TM_id.'  and Org_id = "'.$_SESSION['g_Org_id'].'" ';
        mysql_query($l_TM_upd_query);
        // Update Guide_request table 


        $l_TM_query = 'Update Guide_Requests set GR_ResponseDateTime = "'.$l_Datetime.'" where UR_id  = "'.$l_UR_id.'" and TM_id = '.$l_TM_id.' and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_TM_query);


       $l_select_users = 'select UR.UR_id from Users as UR where UR.TM_id = '.$l_TM_id.' and UR.Org_id = "'.$_SESSION['g_Org_id'].'" ';
       $l_res_select_users = mysql_query($l_select_users);



       while ( $l_users = mysql_fetch_row($l_res_select_users))
       {
           $l_insert_comm = 'Insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type, TM_id,Org_id) values ( "'.$l_UR_Receiver.'","'.$l_users[0].'", "'.$l_Datetime.'" , "'.$l_CM_Message.'" , "SN", '.$l_TM_id.',"'.$_SESSION['g_Org_id'].'")';
           mysql_query($l_insert_comm);
       }
   
       // Get the PastAttempts for a PR_id
       $l_PR_query  = 'Select PR.PR_NoOfPastAttempts from Projects as PR where PR.PR_id = '.$l_PR_id.' and PR.Org_id = "'.$_SESSION['g_Org_id'].'"';
       $l_PR_res    = mysql_query($l_PR_query);
       $l_PR_row    = mysql_fetch_row($l_PR_res);
       
       $l_NoOfPastAttempts = $l_PR_row[0];
       $l_NoOfPastAttempts = $l_NoOfPastAttempts + 1;
       
       // Update the  PR_PastAttempts 
        $l_PR_update_query = 'Update Projects set PR_NoOfPastAttempts = '.$l_NoOfPastAttempts.' where PR_id = '.$l_PR_id.' and Org_id = "'.$_SESSION['g_Org_id'].'"';
       mysql_query($l_PR_update_query);
    
   
// Accepting request Email 
      $l_query_Teammates = 'select UR.UR_Emailid, UR.UR_EmailidDomain from Users as UR where UR.TM_id = '.$l_TM_id.' and UR.Org_id = "'.$_SESSION['g_Org_id'].'"';
$l_result_Teammates = mysql_query($l_query_Teammates) or die(mysql_error());
$l_count_Teammates = mysql_num_rows($l_result_Teammates);
if($l_count_Teammates>0)
{
while($l_row_Teammates=mysql_fetch_row($l_result_Teammates))
{
$l_UR_EmailidTeammate = $l_row_Teammates[0];
$l_UR_EmailidDomainTeammate = $l_row_Teammates[1];

$l_webMaster = 'support@zaireprojects.com';
$l_message = $l_UR_NameReceiver." has accepted your team request. <br><br>Sincerely, <br>Zaireprojects Support Team";
        $l_subject = "Guide Response";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";
   mail($l_UR_EmailidTeammate.'@'.$l_UR_EmailidDomainTeammate, $l_subject, $l_message, $l_headers2);

}
}
}
else if($l_choice == 'Reject')
{
    $l_CM_Message = 'Guide request Rejected';
    $l_CM_Type = 'GR';
    
    // Get count 
    $l_count_query = 'select count(*) from Guide_Requests as GR where GR.GR_ResponseDateTime is NULL and GR.UR_id = "'.$l_UR_id.'" and GR.Org_id = "'.$_SESSION['g_Org_id'].'"';
    $l_count_res = mysql_query($l_count_query);
    $l_count_row = mysql_fetch_row($l_count_res);
    if($l_count_row[0] == 1)
    {
        $l_upd_users = 'Update Users set TM_id = NULL where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_upd_users);
    }
    
    
    // Update Guide_Request 
  $l_upd_guide = 'Update Guide_Requests set GR_ResponseDateTime = "'.$l_Datetime.'"where TM_id = "'.$l_TM_id.'" and UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
    mysql_query($l_upd_guide);
    
    
    $l_select_users = 'select UR.UR_id from Users as UR 
       where UR.TM_id = '.$l_TM_id.' and Org_id = "'.$_SESSION['g_Org_id'].'" ';
    $l_res_select_users = mysql_query($l_select_users);
   
    while ( $l_users = mysql_fetch_row($l_res_select_users))
    {
        $l_insert_comm = 'Insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type, TM_id,Org_id) values ( "'.$l_UR_Receiver.'","'.$l_users[0].'","'.$l_Datetime.'" , "'.$l_CM_Message.'" , "SN", '.$l_TM_id.',"'.$_SESSION['g_Org_id'].'" )';
        mysql_query($l_insert_comm);
    }
    
//Reject email send
             $l_query_Teammates = 'select UR.UR_Emailid, UR.UR_EmailidDomain from Users as UR where UR.TM_id = '.$l_TM_id.' and Org_id = "'.$_SESSION['g_Org_id'].'"';
$l_result_Teammates = mysql_query($l_query_Teammates) or die(mysql_error());
$l_count_Teammates = mysql_num_rows($l_result_Teammates);
if($l_count_Teammates>0)
{
while($l_row_Teammates=mysql_fetch_row($l_result_Teammates))
{
$l_UR_EmailidTeammate = $l_row_Teammates[0];
$l_UR_EmailidDomainTeammate = $l_row_Teammates[1];

$l_webMaster = 'support@zaireprojects.com';
$l_message = $l_UR_NameReceiver." has rejected your team request. <br><br>Sincerely, <br>Zaireprojects Support Team";
        $l_subject = "Guide Response";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";
   mail($l_UR_EmailidTeammate.'@'.$l_UR_EmailidDomainTeammate, $l_subject, $l_message, $l_headers2);
    }
   }
}
echo "<script> window.location.href = 'GHome.php'</script>";

?>