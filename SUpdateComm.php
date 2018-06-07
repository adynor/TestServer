<?php
    ////////////////////////////////////////
    //Name: SupdateComm
    //Purpose : keep communication between the teammate requests and update information
    //Project: Projectory
    //Calls:
    //called by:
    ////////////////////////////////////////
    
    include ('db_config.php');
   
    $l_UR_id                 = $_SESSION['g_UR_id'];
    $l_PR_id=$_SESSION['g_PR_id'];
    $l_UR_Receiver               = $l_UR_id;
    $l_team_request_response=$_REQUEST['g_updSQL'];
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_CM_Datetime = $date->format( 'YmdHi' );
   $l_ur_sender_query ='select TT.UR_Sender, UR.UR_FirstName, CM.CM_Message from Teammate_Request as TT, Users as UR, Communications as CM where TT.TT_ResponseDateTime is NULL  and  TT.UR_Receiver ="'.$l_UR_Receiver.'" and UR.UR_id = TT.UR_Sender and CM.UR_Sender = TT.UR_Sender and CM.UR_Receiver ="'.$l_UR_Receiver.'" and UR.UR_id = CM.UR_Sender and UR.Org_id =TT.Org_id and UR.Org_id =CM.Org_id and UR.Org_id = "'.$_SESSION['g_Org_id'].'"';
    $l_UR_Sender_result = mysql_query($l_ur_sender_query);
    $l_UR_sender_row = mysql_fetch_row($l_UR_Sender_result);
   $l_UR_Sender = $l_UR_sender_row[0];
    if($l_team_request_response=='Accept')
    {
        $l_msg_query = 'Select CM_Message from Communications  where UR_Sender ="'.$l_UR_Sender.'" and UR_Receiver="'.$l_UR_Receiver.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_msg_res = mysql_query($l_msg_query);
        $l_msg_row = mysql_fetch_row($l_msg_res);
        $l_Message = $l_msg_row[0];
        
        // check team ID of the sender
        $l_check_query = 'select UR.TM_id from Users as UR where UR.UR_id = "'.$l_UR_Sender.'" AND UR.Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_check_result = mysql_query($l_check_query);
        $l_row_check = mysql_fetch_row($l_check_result);
        
        //check the maximum no of students added in that team
        $l_check_no_pr_students_query=  mysql_query("SELECT PR_No_Students FROM Projects WHERE PR_id='".$l_PR_id."'");
        $l_check_no_pr_students=mysql_fetch_row($l_check_no_pr_students_query);
        if($l_row_check[0] != '-99'){
            
            $l_check_teammembers_query=mysql_query("SELECT UR_id FROM Users WHERE TM_id='".$l_row_check[0]."' AND Org_id = '".$_SESSION['g_Org_id']."'");
            $l_check_teammembers= mysql_num_rows($l_check_teammembers_query);
            $_GuideCheck_sql='SELECT TM_id FROM Teams AS TM WHERE TM.UR_id_Guide is NOT NULL AND TM.TM_id="'.$l_row_check[0].'" AND TM.Org_id = "'.$_SESSION['g_Org_id'].'"';
            $l_GuideAvailability=  mysql_num_rows(mysql_query($_GuideCheck_sql));
            
        }
        else{
            $l_check_teammembers=0;
            $l_GuideAvailability=0;
        }
        //$l_check_teammembers.$l_check_no_pr_students[0];
        if(($l_check_teammembers < $l_check_no_pr_students[0]) && ($l_GuideAvailability == 0)){
            
            if($l_row_check[0] == '-99')  // If the sender is not in any team
            {
                
                // Generate a team ID and insert into Team Table
                $l_countteam_query = 'select max(TM_id) from Teams';
                $l_countteam_result = mysql_query($l_countteam_query);
                
                if($l_countteam_row = mysql_fetch_row($l_countteam_result)){
                    if($l_countteam_row[0] == NULL)
                    {
                        $l_countteam_row[0] = 0;
                        $l_TM_id = $l_countteam_row[0] + 1;
                    }
                    else
                    {
                        
                        $l_TM_id = $l_countteam_row[0] + 1;
                    }
                    
                }else{
                    $l_countteam_row[0] = 0;
                    $l_TM_id = $l_countteam_row[0] + 1;
                }
                $l_insert_Receiver = 'insert into Teams (TM_id, TM_Name, PR_id,TM_PR_Type, TM_StartDate,Org_id) values ('.$l_TM_id .',\' Team'.$l_TM_id.' \', \''.$l_PR_id.'\', \''.$_SESSION['g_UR_PR_Type'].'\','.$l_CM_Datetime.',\''.$_SESSION['g_Org_id'].'\')';
                
                mysql_query( $l_insert_Receiver);
                $l_upd_Sender = 'Update Users set TM_id = '.$l_TM_id.' where UR_id = "'.$l_UR_Sender.'" AND Org_id = "'.$_SESSION['g_Org_id'].'" ';
                $l_upd_Sender= mysql_query($l_upd_Sender);
                
                $l_upd_Receiver = 'Update Users set TM_id = '.$l_TM_id.' where UR_id = "'.$l_UR_Receiver.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
                $l_upd_Receiver= mysql_query( $l_upd_Receiver);
                $_SESSION['g_TM_id']=$l_TM_id;
                
                
                
                
            }
            else
            {
                $l_TM_id = $l_row_check[0];
                
                $l_upd_Users1 = 'Update Users set TM_id = '.$l_TM_id.' where UR_id = "'.$l_UR_Receiver.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
                //$_SESSION['g_TM_id']=$l_TM_id;
                mysql_query($l_upd_Users1);
                
            }
            
            // Update Teammate_request table
            $l_TM_query = 'Update Teammate_Request set TT_ResponseDateTime = '.$l_CM_Datetime.' where UR_Receiver  = "'.$l_UR_Receiver.'" and UR_Sender = "'.$l_UR_Sender.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
            mysql_query($l_TM_query);
            
            $l_CM_Message ='Accepted Teammate Request';
            $l_CM_Type ='TR';
            
            $l_sql = "insert into Communications (UR_Sender, UR_Receiver, TM_id, CM_Message, CM_Type ,CM_DateTime,Org_id) values (\"".$l_UR_Receiver."\", \"".$l_UR_Sender."\" ," .$l_TM_id." ,  \"" .$l_CM_Message ."\", \"".$l_CM_Type."\", ".$l_CM_Datetime.",\"".$_SESSION['g_Org_id']."\")";
            
            $l_sql_result = mysql_query($l_sql);
            
            // Accepting Email
            
            $l_query_ReceiverName = 'select UR_FirstName, UR_LastName from Users where UR_id = "'.$l_UR_Receiver.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
            $l_result_ReceiverName = mysql_query($l_query_ReceiverName) or die(mysql_error());
            $l_row_ReceiverName =mysql_fetch_row($l_result_ReceiverName);
            
            
            $l_query_SenderEmail= 'select UR_Emailid, UR_EmailidDomain from Users where UR_id = "'.$l_UR_Sender.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
            $l_result_SenderEmail = mysql_query($l_query_SenderEmail) or die(mysql_error());
            $l_row_SenderEmail =mysql_fetch_row($l_result_SenderEmail);
            $l_UR_EmailidSender = $l_row_SenderEmail[0];
            $l_UR_EmailidDomainSender = $l_row_SenderEmail[1];
            
            $l_UR_ReceiverName = $l_row_ReceiverName[0].' '.$l_row_ReceiverName[1];
            
            $l_webMaster = 'support@zaireprojects.com';
            $l_message = $l_UR_ReceiverName." has accepted your teammate request. Please login and view them in http://www.zaireprojects.com/SHome. <br><br>Sincerely, <br>Zaireprojects Support Team";
            $l_subject = "Team Request Accepted";
            $l_headers2 = "From: $l_webMaster\r\n";
            $l_headers2 .= "Content-type:  text/html\r\n";
             $to  = array($l_UR_EmailidSender.'@'.$l_UR_EmailidDomainSender);
            sendmail($to,$subject, $l_message);
            //mail($l_UR_EmailidSender.'@'.$l_UR_EmailidDomainSender, $l_subject, $l_message, $l_headers2);
            
        }else{
            echo "<script>window.location='SUpdateComm.php?g_updSQL=Reject'</script>";
        }
    }
    else if($l_team_request_response == 'Reject')
    {
    
   
        $l_CM_Message = 'Sorry ! ';
        $l_CM_Type = 'TR';
        
        //checking team id for the sender
        $sender_TM_id_query = 'select TM_id from Users where UR_id = "'.$l_UR_Sender.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
        $sender_TM_id_result = mysql_query($sender_TM_id_query);
        $sender_TM_id_row = mysql_fetch_row($sender_TM_id_result);
        $sender_TM_id = $sender_TM_id_row[0];
        
        //checking the count of teammate request from the sender
        $count_query = 'select count(*) from Teammate_Request as TT where TT.TT_ResponseDateTime is NULL and TT.UR_Sender ="'. $l_UR_Sender.'" AND TT.Org_id = "'.$_SESSION['g_Org_id'].'" ';
        $count_res = mysql_query($count_query);
        $count_row = mysql_fetch_row($count_res);
        $count = $count_row[0];
        
        if($count == 1 && $sender_TM_id==-99) // if number of teammate request from sender is 1 and sender team id is not set
        {
            $l_upd_Users_Sender = 'Update Users set TM_id = null  where UR_id = "'.$l_UR_Sender.'" AND Org_id = "'.$_SESSION['g_Org_id'].'"';
            mysql_query($l_upd_Users_Sender);
        }
        
        $l_insert_Comm = "insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type,Org_id) values (\"".$l_UR_Sender."\", \"".$l_UR_Receiver."\", '.$l_CM_Datetime.', \"".$l_CM_Message."\",\"".$l_CM_Type."\",\"".$_SESSION['g_Org_id']."\")";
        mysql_query($l_insert_Comm);
        
        // Update users table with a null team Id.
        $l_upd_Users1 = 'Update Users set TM_id = null  where UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_upd_Users1);
        $_SESSION['g_TM_id']=NULL;
        
        // Update Teammate_request table
         $l_TM_query = 'Update Teammate_Request set TT_ResponseDateTime = '.$l_CM_Datetime.' where UR_Receiver  = "'.$l_UR_Receiver.'" and UR_Sender = "'.$l_UR_Sender.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_TM_query);
        
      
        //Reject email send
        $l_query_ReceiverName = 'select UR_FirstName, UR_LastName from Users where UR_id = "'.$l_UR_Receiver.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_result_ReceiverName = mysql_query($l_query_ReceiverName) or die(mysql_error());
        $l_row_ReceiverName =mysql_fetch_row($l_result_ReceiverName);
        
        
        $l_query_SenderEmail= 'select UR_Emailid, UR_EmailidDomain from Users where UR_id = "'.$l_UR_Sender.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_result_SenderEmail = mysql_query($l_query_SenderEmail) or die(mysql_error());
        $l_row_SenderEmail =mysql_fetch_row($l_result_SenderEmail);
        $l_UR_EmailidSender = $l_row_SenderEmail[0];
        $l_UR_EmailidDomainSender = $l_row_SenderEmail[1];
        
        $l_UR_ReceiverName = $l_row_ReceiverName[0].' '.$l_row_ReceiverName[1];
        
        $l_webMaster = 'support@zaireprojects.com';
        $l_message = $l_UR_ReceiverName." has rejected your teammate request. Please login and view them in http://www.zaireprojects.com/SHome. <br><br>Sincerely, <br>Zaireprojects Support Team";
        $l_subject = "Team Request Rejected";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";
        mail($l_UR_EmailidSender.'@'.$l_UR_EmailidDomainSender, $l_subject, $l_message, $l_headers2);
        
        
    }
    else if($l_team_request_response == 'Cancel')
    {
        $l_CM_Message = 'Sorry ! ';
        $l_CM_Type = 'TR';
        
        //checking team id for the sender
        $sender_TM_id_query = 'select TM_id from Users where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        $sender_TM_id_result = mysql_query($sender_TM_id_query);
        $sender_TM_id_row = mysql_fetch_row($sender_TM_id_result);
        $sender_TM_id = $sender_TM_id_row[0];
        
        //checking the count of teammate request from the sender
        $count_query = 'select TT.UR_Receiver from Teammate_Request as TT where TT.TT_ResponseDateTime is NULL and TT.UR_Sender ="'.$l_UR_id.'" and TT.Org_id = "'.$_SESSION['g_Org_id'].'"';
        $count_res = mysql_query($count_query);
        $count_row = mysql_fetch_row($count_res);
        $count = mysql_num_rows($count_res);
        $UR_Receiver_id=$count_row[0];
        if($count == 1 && $sender_TM_id==-99) // if number of teammate request from sender is 1 and sender team id is not set
        {
            $l_upd_Users_Sender = 'Update Users set TM_id = null  where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            mysql_query($l_upd_Users_Sender);
        }
        
        $l_insert_Comm = "insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type.Org_id) values (\"".$l_UR_id."\", \"".$UR_Receiver_id."\", '.$l_CM_Datetime.', \"".$l_CM_Message."\",\"".$l_CM_Type."\",\"".$_SESSION['g_Org_id']."\")";
        mysql_query($l_insert_Comm);
        
        // Update users table with a null team Id.
        $l_upd_Users1 = 'Update Users set TM_id = null  where UR_id = "'.$UR_Receiver_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'" ';
        mysql_query($l_upd_Users1);
        
        // Update Teammate_request table
        $l_TM_query = 'Update Teammate_Request set TT_ResponseDateTime = '.$l_CM_Datetime.' where UR_Receiver  = "'.$UR_Receiver_id.'" and UR_Sender = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_TM_query);
        
        
        //Reject email send
        $l_query_ReceiverName = 'select UR_FirstName, UR_LastName from Users where UR_id = "'.$UR_Receiver_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_result_ReceiverName = mysql_query($l_query_ReceiverName) or die(mysql_error());
        $l_row_ReceiverName =mysql_fetch_row($l_result_ReceiverName);
        
        
        $l_query_SenderEmail= 'select UR_Emailid, UR_EmailidDomain from Users where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_result_SenderEmail = mysql_query($l_query_SenderEmail) or die(mysql_error());
        $l_row_SenderEmail =mysql_fetch_row($l_result_SenderEmail);
        $l_UR_EmailidSender = $l_row_SenderEmail[0];
        $l_UR_EmailidDomainSender = $l_row_SenderEmail[1];
        
        $l_UR_senderName = $l_row_ReceiverName[0].' '.$l_row_ReceiverName[1];
        
        $l_webMaster = 'support@zaireprojects.com';
        $l_message = $l_UR_senderName." has canceled your teammate request. Please login and view them in http://www.zaireprojects.com/SHome. <br><br>Sincerely, <br>Zaireprojects Support Team";
        $l_subject = "Team Request Canceled";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";
        mail($l_UR_EmailidSender.'@'.$l_UR_EmailidDomainSender, $l_subject, $l_message, $l_headers2);
        
        
    }
    else if($l_team_request_response == 'GCancel')
    {
        $l_CM_Message = 'Guide request Canceled';
        $l_CM_Type = 'GR';
        $l_TM_id=$_SESSION['g_TM_id'];
        // Get count
        $l_count_query = "select GR.UR_id from Guide_Requests as GR where GR.GR_ResponseDateTime is NULL and GR.TM_id =".$l_TM_id." and Org_id = '".$_SESSION['g_Org_id']."'";
        
        $l_count_res = mysql_query($l_count_query);
        $l_count_row = mysql_num_rows($l_count_res);
        $l_result_guide=  mysql_fetch_row($l_count_res);
        $l_guide_name=$l_result_guide[0];
        if($l_count_row == 1)
        {
            $l_upd_users = 'Update Users set TM_id = NULL where UR_id = "'.$l_guide_name.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
            mysql_query($l_upd_users);
        }
        
        
        // Update Guide_Request
        $l_upd_guide = 'Update Guide_Requests set GR_ResponseDateTime = "'.$l_CM_Datetime.'" where TM_id = "'.$l_TM_id.'" and UR_id = "'.$l_guide_name.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
        mysql_query($l_upd_guide);
        
        
        
        $l_insert_comm = 'Insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type, TM_id,Org_id) values ( "'.$l_UR_id.'","'.$l_guide_name.'","'.$l_CM_Datetime.'" , "'.$l_CM_Message.'" , "GR", '.$l_TM_id.',"'.$_SESSION['g_Org_id'].'" )';
        mysql_query($l_insert_comm);
        
        
        //Reject email send
        $l_query_Teammates = 'select UR.UR_Emailid, UR.UR_EmailidDomain from Users as UR where UR.UR_id = "'.$l_guide_name.'"';
        $l_result_Teammates = mysql_query($l_query_Teammates) or die(mysql_error());
        $l_count_Teammates = mysql_num_rows($l_result_Teammates);
        $l_row_Teammates=mysql_fetch_row($l_result_Teammates);
        $l_UR_EmailidTeammate = $l_row_Teammates[0];
        $l_UR_EmailidDomainTeammate = $l_row_Teammates[1];
        $l_UR_NameReceiver=
        $l_webMaster = 'support@zaireprojects.com';
        $l_message = "Team".$l_TM_id." canceled  your guide. <br><br>Sincerely, <br>Zaireprojects Support Team";
        $l_subject = "Guide Response";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";
        mail($l_UR_EmailidTeammate.'@'.$l_UR_EmailidDomainTeammate, $l_subject, $l_message, $l_headers2);
        
        
    }
    //header("Location:'Shome'");  
    
    
     
    echo "<script>window.location.href='SHome.php'</script>";
    
    
    ?>