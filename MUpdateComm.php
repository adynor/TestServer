<?php
    /////////////////////////////////////////////
    //// Name            : MUpdatecomm
    ////Project         : Projectory
    ////Purpose         : Pending Request accept or reject and store the record of communication
    ////Called By       : GMPendingRequest
    ////Calls           : MHome
    ////Mod history:
    //////////////////////////////////////////////
     
include ('db_config.php');
//include ('header.php');
if(!session_id){session_start();}

$l_UR_USN             = $_SESSION['g_UR_USN']; // this is needed by the SQLs that run in this php
$l_UR_id              = $_SESSION['g_UR_id'];

$l_UR_Receiver = $l_UR_id;


$g_updSQL	=$_REQUEST['g_updSQL']; 
$g_updSQL	=str_replace("\\","",$g_updSQL);
$l_arry = explode("|",$g_updSQL);


$l_choice = $l_arry[0];
$l_TM_id = $l_arry[1];
$l_User_Org=$l_arry[2];
// Get DateTime
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_Datetime = $date->format( 'YmdHi' );
    
// Get UR_Sender from Communication table
    $l_ur_sender_query = 'select UR_Sender from Communications where UR_Receiver = "'.$l_UR_Receiver.'" and CM_Type = \'MR\' and Org_id = "'.$_SESSION['g_Org_id'].'"';
    $l_UR_Sender_result = mysql_query($l_ur_sender_query);
    $l_UR_sender_row = mysql_fetch_row($l_UR_Sender_result);
    $l_UR_Sender = $l_UR_sender_row[0];
//echo    $l_choice;
//echo $l_ur_sender_query;
//get the details of receiver
$l_query_ReceiverName = 'select UR_FirstName, UR_LastName from Users where UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
       $l_result_ReceiverName = mysql_query($l_query_ReceiverName) or die(mysql_error());
$l_row_ReceiverName =mysql_fetch_row($l_result_ReceiverName);
$l_UR_NameReceiver = $l_row_ReceiverName[0];

// if the request is accepted
    if($l_choice=='Accept')
    {
        $l_PR_id = $l_arry[2];
       //echo "Accept";

        $timezone = new DateTimeZone("Asia/Kolkata" );
        $date = new DateTime();
        $date->setTimezone($timezone );
        $l_Datetime = $date->format( 'YmdHi' );

        $l_CM_Message = 'Mentor request Accepted';
        $l_CM_Type = 'MR';
////////////////// check the  response time  from mentor request
        $l_count_query = 'select count(*) from Mentor_Requests as MR where MR.MR_ResponseDateTime is NULL and MR.UR_id= "'.$l_UR_id.'" and MR.Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_count_res = mysql_query($l_count_query);
        $l_count_row = mysql_fetch_row($l_count_res);
        $l_count = $l_count_row[0];

//echo $l_count_query;

        if($l_count == 1)
            {
//if count of response time is '1' set the team id null in user table
                $l_upd_users = 'Update Users set TM_id = null where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'" ';
                mysql_query($l_upd_users);
            }
        // Get DateTime
        $timezone = new DateTimeZone("Asia/Kolkata" );
        $l_TM_EndDate = $date->format( 'Ymd' );

        // Update Teams
        if($_REQUEST['model'] != 1){
        $l_TM_upd_query = 'Update Teams set UR_id_Mentor = "'.$l_UR_Receiver.'" where TM_id = '.$l_TM_id.' ';
        mysql_query($l_TM_upd_query);
        }
        // Update Guide_request table 
        
//echo $l_TM_upd_query;
///////set the MR_ResponseDateTime 
        $l_TM_query = 'Update Mentor_Requests set MR_ResponseDateTime = "'.$l_Datetime.'", MR_Status="A" where UR_id  = "'.$l_UR_id.'" and TM_id = '.$l_TM_id.' and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_TM_query);

//echo $l_TM_query;
//Get the User id teammebers
       $l_select_users = 'select UR.UR_id from Users as UR where UR.TM_id = '.$l_TM_id.'';
       $l_res_select_users = mysql_query($l_select_users);
//echo $l_select_users;


       while ( $l_users = mysql_fetch_row($l_res_select_users))
       {
       //insert the sender and receiver with time and message
           $l_insert_comm = 'Insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type, TM_id,Org_id) values (  "'.$l_UR_Receiver.'", "'.$l_users[0].'", "'.$l_Datetime.'" , "'.$l_CM_Message.'" , "SN", '.$l_TM_id.',"'.$_SESSION['g_Org_id'].'" )';
           mysql_query($l_insert_comm);
       }
// Accepting request  send through Email 
       $l_query_Teammates = 'select UR.UR_Emailid, UR.UR_EmailidDomain from Users as UR where UR.TM_id = '.$l_TM_id.'';
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
        $l_subject = "Mentor Response";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";
   mail($l_UR_EmailidTeammate.'@'.$l_UR_EmailidDomainTeammate, $l_subject, $l_message, $l_headers2);

}
}   
       
}
//Rejected send through mail
else if($l_choice == 'Reject')
{
    $l_CM_Message = 'Mentor request Rejected';
    $l_CM_Type = 'MR';
    
    
    $l_count_query = 'select count(*) from Mentor_Requests as MR where MR.MR_ResponseDateTime is NULL and MR.UR_id = "'.$l_UR_id.'" and MR.Org_id = "'.$_SESSION['g_Org_id'].'" ';
    $l_count_res = mysql_query($l_count_query);
    $l_count_row = mysql_fetch_row($l_count_res);
    if($l_count_row[0] == 1)
    {
        $l_upd_users = 'Update Users set TM_id = NULL where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_upd_users);
    }
    
    
    // Update Guide_Request 
    $l_upd_guide = 'Update Mentor_Requests set MR_ResponseDateTime = "'.$l_Datetime.'",MR_Status="R" where TM_id = "'.$l_TM_id.'" and UR_id = "'.$l_UR_Receiver.'"';
    mysql_query($l_upd_guide);
    
    
   $l_select_users = 'select UR.UR_id from Users as UR where UR.TM_id = '.$l_TM_id.'  ';
    $l_res_select_users = mysql_query($l_select_users);
   
    while ( $l_users = mysql_fetch_row($l_res_select_users))
    {
        $l_insert_comm = 'Insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type, TM_id,Org_id) values ( "'.$l_UR_Receiver.'", "'.$l_users[0].'", "'.$l_Datetime.'" , "'.$l_CM_Message.'" , "SN", '.$l_TM_id.',"'.$_SESSION['g_Org_id'].'")';
        mysql_query($l_insert_comm);
    }
    
//Reject request send through mail
             $l_query_Teammates = 'select UR.UR_Emailid, UR.UR_EmailidDomain from Users as UR where UR.TM_id = '.$l_TM_id.'';
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
        $l_subject = "Mentor Response";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";
   mail($l_UR_EmailidTeammate.'@'.$l_UR_EmailidDomainTeammate, $l_subject, $l_message, $l_headers2);


}

}


}

$l_err = mysql_error() ;

header("Location:MHome.php");

  //  include ('footer.php');

?>