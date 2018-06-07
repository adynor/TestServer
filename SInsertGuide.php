<?php
    
////////////////////////////////////////
//Name: SInsertGuide
//Purpose : student send request to guide and request accepted or rejected by guide.
//Project: Projectory
//Calls:
//called by:
////////////////////////////////////////
session_start();
 include ('db_config.php');
    
    $l_sql=$_REQUEST['g_query'];
    $l_arry = explode('|',$l_sql);  // split data coming from query string
    
    /* Data from query string*/
    $l_UR_Sender = $l_arry[0];
    $l_UR_Receiver =$l_arry[1];
    $l_TM_id = $l_arry[2];
    $l_CM_DateTime =$l_arry[3];
    $l_CM_Message = $l_arry[4];
    $l_CM_Type = $l_arry[5];
    
    // query to insert the record of sender and reciever
    $l_sql = "insert into Communications (UR_Sender, UR_Receiver, TM_id, CM_DateTime, CM_Message, CM_Type,Org_id ) values (\"". $l_UR_Sender."\",\"".$l_UR_Receiver."\" ,".$l_TM_id.",".$l_CM_DateTime .",\"".$l_CM_Message ."\", \"" .$l_CM_Type."\",\"".$_SESSION['g_Org_id']."\")";
    mysql_query($l_sql) or die(mysql_error());
    
    // Get DateTime
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_GR_SentDateTime = $date->format( 'YmdHi' );
    
    $l_GR_ResponseDateTime ='NULL';
    
    // query for the guide request
    $l_insert_sql = "insert into Guide_Requests (TM_id , UR_id ,  GR_SentDateTime, GR_ResponseDateTime,Org_id) values (".$l_TM_id .",\"" . $l_UR_Receiver . "\",". $l_GR_SentDateTime.",".$l_GR_ResponseDateTime.",'".$_SESSION['g_Org_id']."')";
    mysql_query($l_insert_sql) or die(mysql_error());
    
    //this has to insert when guide accept student request
    $l_upd_receiver = 'Update Users set TM_id = -99 where UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
    mysql_query($l_upd_receiver) or die(mysql_error());
    
    
    
    //send mail to guide and teammates
    $l_query_TeamName = 'select TM_Name from Teams where TM_id = '.$l_TM_id.' and Org_id = "'.$_SESSION['g_Org_id'].'"';
    $l_result_TeamName = mysql_query($l_query_TeamName) or die(mysql_error());
    $l_row_TeamName =mysql_fetch_row($l_result_TeamName);
    
    $l_query_ReceiverGuide= 'select UR_Emailid, UR_EmailidDomain, UR_FirstName, UR_LastName from Users where UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
    $l_result_ReceiverGuide = mysql_query($l_query_ReceiverGuide) or die(mysql_error());
    $l_row_ReceiverGuide = mysql_fetch_row($l_result_ReceiverGuide);
    $l_UR_EmailidReceiver = $l_row_ReceiverGuide[0];
    $l_UR_EmailidDomainReceiver = $l_row_ReceiverGuide[1];
    $l_UR_NameReceiver = $l_row_ReceiverGuide[2].' '.$l_row_ReceiverGuide[3];
    
    $l_UR_TeamName = $l_row_TeamName[0];
    
    // send email to Guide on recieving guide request from teams
    $l_webMaster = 'support@zaireprojects.com';
    $l_message = $l_UR_TeamName." has sent you a Guide Request. Please login and view them in http://www.zaireprojects.com/GHome. <br><br>Sincerely, <br>Zaireprojects Support Team";
    $l_subject = "Pending Request";
    $l_headers2 = "From: $l_webMaster\r\n";
    $l_headers2 .= "Content-type:  text/html\r\n";
    $to=array($l_UR_EmailidReceiver.'@'.$l_UR_EmailidDomainReceiver);
            sendmail($to,$subject,$l_message);
    
    //mail($l_UR_EmailidReceiver.'@'.$l_UR_EmailidDomainReceiver, $l_subject, $l_message, $l_headers2);
    
    // query to get emailid of sender
    $l_query_Teammates = 'select UR.UR_Emailid, UR.UR_EmailidDomain from Users as UR where  UR.UR_id<>"'.$l_UR_Sender .'" and UR.TM_id = '.$l_TM_id.' and UR.Org_id = "'.$_SESSION['g_Org_id'].'" ';
    $l_result_Teammates = mysql_query($l_query_Teammates) or die(mysql_error());
    $l_count_Teammates = mysql_num_rows($l_result_Teammates);
    if($l_count_Teammates>0)
    {
        while($l_row_Teammates=mysql_fetch_row($l_result_Teammates))
        {
            $l_UR_EmailidTeammate = $l_row_Teammates[0];
            $l_UR_EmailidDomainTeammate = $l_row_Teammates[1];
            
            // send email to all the other team members when guide request send by any team member
            $l_webMaster = 'support@zaireprojects.com';
            $l_message = "Your team has sent a Guide Request to ".$l_UR_NameReceiver.". <br><br>Sincerely, <br>Zaireprojects Support Team";
            $l_subject = "Guide Request";
            $l_headers2 = "From: $l_webMaster\r\n";
            $l_headers2 .= "Content-type:  text/html\r\n";
            mail($l_UR_EmailidTeammate.'@'.$l_UR_EmailidDomainTeammate, $l_subject, $l_message, $l_headers2);
        }
        
    }
    mysql_close();
    
    
    // send control back to the calling php
    
   // wp_redirect($l_filehomepath."/shome");
    echo "<script> window.location.href = 'SHome.php'</script>"; 
    ?>