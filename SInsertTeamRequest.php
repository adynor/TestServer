<?php
   include ('db_config.php');
    
  // get date and time
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_TT_SentDateTime = $date->format( 'YmdHi'); // set current date time while sending request
    $l_TT_ResponseDateTime ='NULL';
    
    // get data from query string and assign local variables
     $l_sql=$_REQUEST['g_query'];
    $l_sql=str_replace("\\","",$l_sql);
    $l_arry = explode("|",$l_sql);
    
    	 $l_UR_Sender = $l_arry[0];
    	 $l_UR_Receiver =$l_arry[1];
    	 $l_TM_id = $l_arry[2];
	 $l_CM_DateTime =$l_arry[3];
	 $l_CM_Message = $l_arry[4];
 	 $l_CM_Type = $l_arry[5];
    
// query to insert request date time and response date time
    $l_insert_sql = "insert into Teammate_Request (TT_ResponseDateTime, TT_SentDateTime, UR_Receiver, UR_Sender,Org_id )
    values (". $l_TT_ResponseDateTime . ", " . $l_TT_SentDateTime . ", \"" . $l_UR_Receiver . "\", \"" .$l_UR_Sender ."\",'".$_SESSION['g_Org_id']."') ";
            mysql_query($l_insert_sql);
            
            if($l_TM_id == -99)
            {
            // query to insert into communications
           $l_sql = "insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type ,Org_id) values (\"".$l_UR_Sender."\", \"" .$l_UR_Receiver."\" , \"" .$l_CM_DateTime ."\", \"" .$l_CM_Message ."\", \"" .$l_CM_Type."\",and Org_id = '".$_SESSION['g_Org_id']."')";
            mysql_query($l_sql);
            
            // update users and set team id of the sender
            $l_upd_sender_query = 'Update Users set TM_id ='.$l_TM_id.' where UR_id ="'.$l_UR_Sender.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            mysql_query($l_upd_sender_query);
            
            //update users and set team id according to reciever response
            $l_upd_receiver_query = 'Update Users set TM_id ='.$l_TM_id.' where UR_id ="'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            $l_upd_receiver_query;
            mysql_query($l_upd_receiver_query);
            }
            else if($l_TM_id == NULL) // if team is not set
            {
            // communications table
            $l_sql = "insert into Communications (UR_Sender, UR_Receiver, TM_id, CM_DateTime, CM_Message, CM_Type,Org_id ) values (\"".$l_UR_Sender."\", \"" .$l_UR_Receiver."\" ,-99 , \"" .$l_CM_DateTime ."\", \"" .$l_CM_Message ."\", \"" .$l_CM_Type."\",'".$_SESSION['g_Org_id']."')";
            mysql_query($l_sql);
            
            // set team id -99 on sending request
            $l_upd_sender_query = 'Update Users set TM_id =-99 where UR_id ="'.$l_UR_Sender.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            mysql_query($l_upd_sender_query);
            
            // set team id -99 on recieving request
            $l_upd_receiver_query = 'Update Users set TM_id =-99 where UR_id ="'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'" ';
            mysql_query($l_upd_receiver_query);
            }
            else {
            //update in communications on sending and recieving request
            $l_sql = "insert into Communications (UR_Sender, UR_Receiver, TM_id, CM_DateTime, CM_Message, CM_Type, Org_id) values (\"".$l_UR_Sender."\", \"" .$l_UR_Receiver."\" ,".$l_TM_id." , \"" .$l_CM_DateTime ."\", \"" .$l_CM_Message ."\", \"" .$l_CM_Type."\",'".$_SESSION['g_Org_id']."')";
            mysql_query($l_sql);
            
            // update reciever teamid on request
            $l_upd_receiver_query = 'Update Users set TM_id =-99 where UR_id ="'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            mysql_query($l_upd_receiver_query);
            }
            
            //get sender name
            $l_query_SenderName = 'select UR_FirstName, UR_LastName from Users where UR_id = "'.$l_UR_Sender.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            $l_result_SenderName = mysql_query($l_query_SenderName) or die(mysql_error());
            $l_row_SenderName =mysql_fetch_row($l_result_SenderName);
            
            //get reciever email id to send him an email on team request
            $l_query_ReceiverEmail= 'select UR_Emailid, UR_EmailidDomain from Users where UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            $l_result_ReceiverEmail = mysql_query($l_query_ReceiverEmail) or die(mysql_error());
            $l_row_ReceiverEmail =mysql_fetch_row($l_result_ReceiverEmail);
            $l_UR_EmailidReceiver = $l_row_ReceiverEmail[0];
            $l_UR_EmailidDomainReceiver = $l_row_ReceiverEmail[1];
            
            $l_UR_SenderName = $l_row_SenderName[0].' '.$l_row_SenderName[1];
            
            //send intimation email to reciever on recieving teammate request
            $l_webMaster = 'support@zaireprojects.com';
            $l_message = $l_UR_SenderName." has sent you teammate Request. Please login and view them in http://www.zaireprojects.com/SHome. <br><br>Sincerely, <br>Zaireprojects Support Team";
            $l_subject = "Pending Request";
            $l_headers2 = "From: $l_webMaster\r\n";
            $l_headers2 .= "Content-type:  text/html\r\n";
           
            $to=array($l_UR_EmailidReceiver.'@'.$l_UR_EmailidDomainReceiver);
             sendmail($to,$subject,$l_message);
            //mail($l_UR_EmailidReceiver.'@'.$l_UR_EmailidDomainReceiver, $l_subject, $l_message, $l_headers2);
            
           
            mysql_close();

           echo "<script> window.location.href = 'SHome.php'</script>"; 


    ?>
