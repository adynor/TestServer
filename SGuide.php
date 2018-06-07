    <?php
    
    ////////////////////////////////////////
//Name: SGuide
//Purpose : show Guide list to all the students
//Project: Projectory
//Calls:
//called by:
////////////////////////////////////////
    include ('header.php');
   include ('db_config.php');
    ?>
   <div class="row" style="padding:20px"></div>
   <div class="container" >
       <div class="row" style="padding:20px 0px">
           
            <div id="lolo">
    <?php 
    
    $l_Check_Guide_request_rejected=mysql_query("select GR.GR_ResponseDateTime,GR.GR_SentDateTime,TM.UR_id_Guide from Guide_Requests as GR ,Teams as TM where TM.Org_id=GR.Org_id and TM.Org_id='".$_SESSION['g_Org_id']."' and TM.TM_id=GR.TM_id and TM.TM_id='".$_SESSION['g_TM_id']."' ORDER BY GR_ResponseDateTime ASC LIMIT 0 ,1");
   
    $l_G_request_rejected=  mysql_fetch_row($l_Check_Guide_request_rejected);
   
   if($l_G_request_rejected[0] !="" && $l_G_request_rejected[1] !="" && $l_G_request_rejected[2] == "")
   {
       if(isset($_POST['tm_change_project']))
       {
           $Teamid=$_POST['l_change_tm_id'];
           mysql_query('Delete from Guide_Requests where Org_id="'.$_SESSION['g_Org_id'].'" and TM_id='.$Teamid.'');
           
           $l_User_Teamtermination=mysql_query('Update Users set TM_id=NULL,PR_id=NULL where Org_id="'.$_SESSION['g_Org_id'].'" and TM_id='.$Teamid.'');
           $l_Teamtermination=mysql_query('Delete from Teams where Org_id="'.$_SESSION['g_Org_id'].'" and TM_id='.$Teamid.'');
            
           if($l_Teamtermination && $l_User_Teamtermination)
           {
               $_SESSION['g_TM_id']="";
                $_SESSION['g_PR_id']="";
            echo '<script>window.location.href="Projects.php"</script>' ;
               
           }
       }
      ?>
        
     
   <br><br>
    
       <a id="myBtn3" style="cursor: pointer; cursor: hand;">Change Project</a>
       <div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Do You really want to change the project?</h4>
          
        </div>
        <div class="modal-body">
          <ul><li>Your team will be reset.</li><li>You have to select the project and create the new team again.</li></ul> 
              
        </div>
         <div class="modal-footer">
          
          <form action="" method="POST">
              <input type="hidden" value="<?php echo $_SESSION['g_TM_id'];?>" name="l_change_tm_id">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> 
          <input class="btn btn-primary" type=submit value="I Want To Change" name="tm_change_project">
          </form>
        </div>
      </div>
      
    </div>
</div>
  <?php }
    

    
 ?>
    
   </div> 
           <div class="col-md-12  ady-row">
               
 <?php
 //print_r($_SESSION);
    $l_PR_id=$_SESSION['g_PR_id'];
    $l_UR_id=$_SESSION['g_UR_id'];
     $l_TM_id=$_SESSION['g_TM_id'];
    $l_IT_id=$_SESSION['g_IT_id'];
    $l_PG_id=$_SESSION['g_PG_id'];
    
    $l_UR_Sender=$l_UR_id;
    $l_guide = "";
    $l_TM_Message="I want to add you as my team Guide";
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_CM_Datetime = $date->format( 'YmdHi' );
    $l_TT_SentDateTime = $date->format( 'YmdHi');
    $l_UR_Type = $_SESSION['g_UR_Type'];
    
    /*Check The Guide Status*/
    if(!empty($l_TM_id))
    {
   $l_query_GStatus = 'Select GR.GR_ResponseDateTime FROM Guide_Requests AS GR WHERE GR.Org_id="'.$_SESSION['g_Org_id'].'" and GR.TM_id ='.$l_TM_id.' AND GR.GR_ResponseDateTime is NULL';
    
    $l_result_GStatus= mysql_query($l_query_GStatus);
     $l_count_GStatus=mysql_num_rows($l_result_GStatus);
    }
    
    /*Guide Status End*/
    if(is_null($l_UR_id) || $l_UR_Type!='S')
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a student. Please login correctly")
        window.location.href="login.php"; </script> ';
        
        print($l_alert_statement );
    }
    else
    {
        
        
        print('<div style="clear:left">');
        
        print ('<form action="" method="POST">');
        if(isset($_POST['yes']))  // if student wants to perform project alone and pressed Yes
        {
            $l_countteam_query = 'select max(TM_id) from Teams';
            $l_countteam_result = mysql_query($l_countteam_query) or die(mysql_error());
            
            if ($l_countteam_row = mysql_fetch_row($l_countteam_result))
            {
                if($l_countteam_row[0] == NULL)
                {
                    $l_countteam_row[0] = 0;
                }
                $l_TM_id = $l_countteam_row[0] + 1;
                $l_insert_Receiver = 'insert into Teams (TM_id, TM_Name, PR_id, TM_StartDate,Org_id,TM_PR_Type) values ('.$l_TM_id .',\' Team'.$l_TM_id.' \', \''.$l_PR_id.'\','.$l_CM_Datetime.',\''.$_SESSION['g_Org_id'].'\', \''.$_SESSION['g_UR_PR_Type'].'\')';
                mysql_query( $l_insert_Receiver) or die(mysql_error());
            }
            
            $l_upd_Sender = 'Update Users set TM_id = '.$l_TM_id.'
            where Org_id="'.$_SESSION['g_Org_id'].'" and UR_id = "'.$l_UR_id.'"';
            $l_upd_Sender= mysql_query($l_upd_Sender) or die(mysql_error());
            $_SESSION['g_TM_id']=$l_TM_id;
            
        }
        else if(isset($_POST['no']))   // student doesn't want to perform projects alone and presses NO
        {
          echo "<script> window.location.href = 'STeam.php'</script>"; 
        }
        
        if($l_TM_id!=NULL)  // Team is already set then select Guide-id
        {
          $l_sql_guide='select TM.UR_id_Guide from Teams as TM where TM.Org_id="'.$_SESSION['g_Org_id'].'" and TM.TM_id='.$l_TM_id.'';
            $l_result_guide=mysql_query($l_sql_guide) or die(mysql_error());
            $l_row_guide=mysql_fetch_row($l_result_guide);
      $l_guide = $l_row_guide[0];
            
        }
        
        if($l_TM_id==NULL) // Team not formed Yet
        {   
            print('<div class="panel panel-primary"><div class="panel-heading">');
            print('<center><div class="" style="font-size:18px;color:#FFFFFF">Do you wish to commence this project alone?</div></center>');
             print('</div><div class="panel-body">');
            print ('<center><input class="btn-primary ady-cus-btn" style="margin: 0px 45px;" type=submit value="yes" name=yes></input>');
            print ('<input class="btn-primary ady-cus-btn"  style="margin: 0px 45px;"  type=submit value="no" name=no></input></center>');
            print('</div></div>');
            print('</form>');
        }
        
        else if($l_TM_id==-99) // Request is pending. student haven't responded yet
        {
            $l_alert_statement =  ' <script type="text/javascript">
            window.alert("You still have some pending Requests! Please respond to them before adding a guide or wait for your teammates to respond")
            window.location.href="SHome.php"; </script> ';
            print($l_alert_statement);
        }
        else if($l_TM_id!=NULL && $l_TM_id!=-99 && !is_null($l_guide)) // Team is formed along with Guide already
        {
            print ('<table class="ady-table-content" border="1" width="100%" >');
            print ('<th> Guide Name </th>');
            print ('<th> Email ID </th>');
            print ('<th> Phone Number </th>');
            print ('<th> Select Guide </th></tr>');
            print ('<tr><td colspan=5><div class="alert alert-danger">Sorry ! You cannot add any guide in the middle of the project</div></td></tr>');
            print ('</table>');
        }
        else
        {
            /*If Guide Request is already pending*/
            if($l_count_GStatus > 0)
            {
                print ('<table class="ady-table-content" border="1" width="100%" >');
                print ('<th> Name </th>');
                print ('<th> Email ID </th>');
                print ('<th> Phone Number </th>');
                print ('<th> Select Guide </th></tr>');
               // print ('<tr><td colspan=5><div class="alert alert-danger">!Sorry Your Guide Response Pending</div></td></tr>');
               $l_guide_query=mysql_query("SELECT GR.UR_id,UR.UR_FirstName,UR.UR_MiddleName,UR.UR_LastName,UR.UR_Phno,UR.UR_Emailid,UR.UR_EmailidDomain FROM Guide_Requests as GR,Users as UR 
WHERE UR.Org_id='".$_SESSION['g_Org_id']."' and GR.Org_id=UR.Org_id and GR.TM_id=".$l_TM_id." AND GR.GR_ResponseDateTime is NULL AND GR.UR_id=UR.UR_id");
               $l_guide_result=  mysql_fetch_row($l_guide_query);
               
              
                       $l_guide_name=$l_guide_result[1].' '.$l_guide_result[2].' '.$l_guide_result[3];
                       $l_guide_mob=$l_guide_result[4];
                       $l_guide_Email=$l_guide_result[5]."@".$l_guide_result[6];
                echo '<tr>';
                echo '<td>'.$l_guide_name.'</td>';
                echo '<td>'.$l_guide_Email.'</td>';
                echo '<td>'.$l_guide_mob.'</td>';
                echo '<td><a class="btn btn-primary" href="SUpdateComm.php?g_updSQL=GCancel">cancel</a></td>';
                echo '</tr>';
                print ('</table>');
            }
            else
            {
            
                $URarray=array();
                
                $l_sql_UR='select UR_id from Users where Org_id="'.$_SESSION['g_Org_id'].'" and TM_id='.$l_TM_id.'';
                $l_result_UR=mysql_query($l_sql_UR) or die(mysql_error());
                $i=0;
                while($l_row_UR=mysql_fetch_row($l_result_UR))
                {
                    $URarray[$i]= $l_row_UR[0];
                    $i++;
                }
                $numrows_array=array();
                $j=0;
                foreach($URarray as $UR_id)
                {
                    //check if any team member has sent a teammate request and request is still pending
                    $l_sql_pendingrequest='select TT_SentDatetime from Teammate_Request where Org_id="'.$_SESSION['g_Org_id'].'" and UR_Sender = "'.$UR_id.'" and TT_ResponseDateTime is NULL';
                    $l_result_pendingrequest=mysql_query($l_sql_pendingrequest) or die(mysql_error());
                    $numrows_array[$j] = mysql_num_rows($l_result_pendingrequest);
                    $j++;
                }
                
                $count_flag="Y";   // flag to check if there are any pending teammate request in the team
                foreach($numrows_array as $value)
                {
                    if($value>0)
                    {
                        $count_flag="N";
                        break;
                    }
                    else
                    {
                        $count_flag="Y";
                    }
                }
                if($count_flag=="Y")  // show the guides only if there are no pending request in the team
                {
                  $l_query_Guide='select distinct UR.UR_id, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_Emailid, UR.UR_EmailidDomain, UR.UR_Phno from Project_SubDomains as PS, UR_Subdomains as US, Users as UR where UR.Org_id="'.$_SESSION['g_Org_id'].'" and UR.UR_id <>"'.$l_UR_id.'" and UR.UR_Type = "G" and PS.PR_id = '.$l_PR_id.' and PS.SD_id = US.SD_id and US.UR_id = UR.UR_id and UR.PG_id='.$l_PG_id.' and UR.IT_id='.$l_IT_id.'';
                    
                    $l_result_Guide= mysql_query($l_query_Guide) or die(mysql_error());
                    
                    print ('<table class="ady-table-content" border="1" width="100%" >');
                    print ('<th> Guide Name </th>');
                    print ('<th> Email ID </th>');
                    print ('<th> Phone Number </th>');
                    print ('<th> Select Guide </th>');
                    if(mysql_num_rows($l_result_Guide) > 0){
                    while($l_row_Guide=mysql_fetch_row($l_result_Guide))
                    {
                        
                        print('<tr>');
                        $l_UR_Receiver=$l_row_Guide[0];
                        print('<td>'.$l_row_Guide[1].' '.$l_row_Guide[2].' '.$l_row_Guide[3].'</td>');
                        print('<td>'.$l_row_Guide[4].'@'.$l_row_Guide[5].'</td>');
                        print('<td>'.$l_row_Guide[6].'</td>');
                        print('<td style="text-align:center"><input type="button" class="btn-primary ady-req-btn" value="Send Request" onClick=\'window.location="SInsertGuide.php?g_query='.$l_UR_Sender.'|'.$l_UR_Receiver.'|'.$l_TM_id.'|'.$l_TT_SentDateTime .'|'.$l_TM_Message.'|GR"\'></input></td>');
                        print('</tr>');
                       
                    }
                     } else{
                        print('<tr> <td style="color:red" colspan="4">!!Sorry Guides are not available</td></tr>');
                         }
                    print ('</table>');
                }
              else
                {
                    print ('<table class="ady-table-content" border="1" width="100%" >');
                    print ('<th> Name </th>');
                    print ('<th> Email ID </th>');
                    print ('<th> Phone Number </th>');
                    print ('<th> Select Guide </th></tr>');
                    print ('<tr><td colspan=5><div class="alert alert-danger">!Sorry you or your teammate still has pending teammate request</div></td></tr>');
                    print ('</table>');
                }
            }
            
        }
    }
    print('</div>');
    
    ?>
 </div>
       </div>
</div>
   <?php include('footer.php')?>

    
 <script>
$(document).ready(function(){
    //$("#lolo").css('border','2px solid green');
     //$("#lolo").css('height','200px');
     $("#lolo a").css('height','70px');
     $("#lolo a").css('margin','16px');
     $("#lolo a").css('border-radius','10px');
     $("#lolo a").css('background-color','rgba(111, 85, 91, 0.32)');
      $("#lolo a").css('font-size','24px');
       $("#lolo a").css('padding','19px 50px');
    
     $("#lolo a").mouseover(function(){
     $(this).css('border','2px solid grey');
      $(this).css('text-decoration','none');  
 });
   $("#lolo a").mouseout(function(){
     $(this).css('border','');
      $(this).css('text-decoration','none');
 });
 
 
 $("#myBtn3").click(function(){
      
         $("#myModal3").modal({backdrop: "static"});
    
     });
 
});

 </script>