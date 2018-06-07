 <?php
   include ('db_config.php');
   //get date and time
   $timezone = new DateTimeZone("Asia/Kolkata" );
   $date = new DateTime();
   $date->setTimezone($timezone );
    // set current date time while sending request
   $l_TT_ResponseDateTime =$date->format( 'YmdHi');
   $l_UR_Receiver ='TEAM@#'.$_SESSION[g_TM_id];;
   $l_Res =$_REQUEST['res'];
    $l_UR_Sender=$_REQUEST['sender'];
    $l_CM_DateTime =$l_TT_ResponseDateTime;
    $l_CM_Message = " Accept Member Request For MY  Team";
    $l_CM_Type = 'TS';
    
       $l_teamreqSql = "UPDATE Teammate_Request SET TT_ResponseDateTime =". $l_TT_ResponseDateTime . " WHERE UR_Receiver ='".$l_UR_Receiver."' AND UR_Sender ='".$l_UR_Sender."' AND Org_id ='".$_SESSION['g_Org_id']."'";
    mysql_query($l_teamreqSql);
    
      $l_teamidsetSql='Update Users set';
      if($l_Res == 'A'){
      $l_teamidsetSql .= ' TM_id ='.$_SESSION[g_TM_id];
      } else if($l_Res == 'R'){
      $l_teamidsetSql .= ' TM_id =NULL';
      }
        $l_teamidsetSql .=' where UR_id ="'.$l_UR_Sender.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
    mysql_query($l_teamidsetSql);
    
      $l_comm_sql = "insert into Communications (UR_Sender, UR_Receiver, CM_DateTime, CM_Message, CM_Type ,Org_id) values (\"".$l_UR_Sender."\", \"" .$l_UR_Receiver."\" , \"" .$l_CM_DateTime ."\", \"" .$l_CM_Message ."\", \"" .$l_CM_Type."\",'".$_SESSION['g_Org_id']."')";
    mysql_query($l_comm_sql);
    
    echo "<script>window.location.href='SHome.php'</script>";
?>