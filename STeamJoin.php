 <?php
   include ('db_config.php');
   //get date and time
   $timezone = new DateTimeZone("Asia/Kolkata" );
   $date = new DateTime();
   $date->setTimezone($timezone );
   $l_TT_SentDateTime = $date->format( 'YmdHi'); // set current date time while sending request
   $l_TT_ResponseDateTime ='NULL';
   $l_UR_Receiver ='TEAM@#'.$_REQUEST['team'];
   $l_UR_Sender=$_SESSION['g_UR_id'];
    
    $l_CM_DateTime =$l_TT_SentDateTime;
    $l_CM_Message = "I Want To Join This Team";
    $l_CM_Type = 'ST';
      $l_teamreqSql = "insert into Teammate_Request (TT_ResponseDateTime, TT_SentDateTime, UR_Receiver, UR_Sender,Org_id )
    values (". $l_TT_ResponseDateTime . ", " .$l_TT_SentDateTime. ", \"" .$l_UR_Receiver. "\", \"".$l_UR_Sender."\",'".$_SESSION['g_Org_id']."')";
    mysql_query($l_teamreqSql);
      $l_teamidsetSql='Update Users set TM_id =-99 where UR_id ="'.$l_UR_Sender.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
    mysql_query($l_teamidsetSql);
     $l_comm_sql = "insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type ,Org_id) values (\"".$l_UR_Sender."\", \"" .$l_UR_Receiver."\" , \"" .$l_CM_DateTime ."\", \"" .$l_CM_Message ."\", \"" .$l_CM_Type."\",'".$_SESSION['g_Org_id']."')";
    mysql_query($l_comm_sql);
    echo "<script>window.location.href='STeam.php'</script>";
?>