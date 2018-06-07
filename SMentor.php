<?php
   ////////////////////////////////////////
   //Name: SMentor
   //Purpose : Show all the mentor list to student
   //Project: Projectory
   //Calls:
   //called by:
   ////////////////////////////////////////
   
     include ('header.php');
     include ('db_config.php');
      $l_PR_id=$_SESSION['g_PR_id'];
      $l_UR_id=$_SESSION['g_UR_id'];
      $l_TM_id=$_SESSION['g_TM_id'];
      $l_UR_Sender=$l_UR_id;
      $l_TM_Message="I want to add you as my Team Mentor";
      $timezone = new DateTimeZone("Asia/Kolkata" );
      $date = new DateTime();
      $date->setTimezone($timezone );
      $l_CM_Datetime = $date->format( 'YmdHi' );
      $l_TT_SentDateTime = $date->format( 'YmdHi');
      $l_UR_Type = $_SESSION['g_UR_Type'];
     
      $l_query_MStatus='Select MR.MR_ResponseDateTime FROM Mentor_Requests AS MR WHERE MR.TM_id ='.$l_TM_id.' AND MR.MR_ResponseDateTime is NULL ';
      $l_result_MStatus= mysql_query($l_query_MStatus);
      if($l_result_MStatus){
      $l_count_MStatus=mysql_num_rows($l_result_MStatus);
      }
      ?>
<div class="container" >
   <div class="row">
      <div class="col-md-12">
         <?php 
            if(is_null($l_UR_id) || $l_UR_Type!='S') {
            
            $l_alert_statement =  ' <script type="text/javascript">
            window.alert("You have not logged in as a student. Please login correctly")
            window.location.href=" login"; </script> ';
            
            print($l_alert_statement );
            }
            
            else
            {
            if(!empty($l_TM_id))
            {
            $l_query_checkMentor='select UR_id_Mentor,UR_id_Guide from Teams where TM_id='.$l_TM_id.' ';
            $l_result_checkMentor=mysql_query($l_query_checkMentor) ;
            $l_row_Mentor=mysql_fetch_row($l_result_checkMentor);
            $l_Mentor=$l_row_Mentor[0];
            $l_Guide=$l_row_Mentor[1];
            }
            
            if($l_TM_id==NULL || ($l_Guide==NULL && $l_UR_PR_Type=="C"))
            {
            if($l_UR_PR_Type == "C"){
            $l_alert_statement =  ' <script type="text/javascript"> var x=window.alert("You can not add a Mentor since you do not have a Team prepared or selected a Guide!"); window.location=" SHome.php"; </script> ';
             print($l_alert_statement );
            
            }else{
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
                  $l_insert_Receiver = 'insert into Teams (TM_id, TM_Name, PR_id, TM_StartDate,Org_id,TM_PR_Type) values ('.$l_TM_id .',\' Team'.$l_TM_id.' \', \''.$l_PR_id.'\','.$l_CM_Datetime.',"'.$_SESSION['g_Org_id'].'","'.$_SESSION['g_UR_PR_Type'].'")';
            
                  mysql_query( $l_insert_Receiver) or die(mysql_error());
              }
              
              $l_upd_Sender = 'Update Users set TM_id = '.$l_TM_id.'
              where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
              $l_upd_Sender= mysql_query($l_upd_Sender) or die(mysql_error());
              $_SESSION['g_TM_id']=$l_TM_id;
              
            }
            else if(isset($_POST['no']))   // student doesn't want to perform projects alone and presses NO
            {
            echo "<script> window.location.href = 'STeam.php'</script>"; 
            } 
              print ('<form action="" method="POST">');
              print('<div class="panel panel-primary"><div class="panel-heading">');
              print('<center><div class="" style="font-size:18px;color:#FFFFFF">Do you wish to commence this project alone?</div></center>');
              print('</div><div class="panel-body">');
              print ('<center><input class="btn-primary ady-cus-btn" style="margin: 0px 45px;" type=submit value="yes" name=yes></input>');
              print ('<input class="btn-primary ady-cus-btn"  style="margin: 0px 45px;"  type=submit value="no" name=no></input></center>');
              print('</div></div>');
              print('</form>');
            }
            
            }
             else if($l_TM_id==-99)
            {
            
            $l_alert_statement =  ' <script type="text/javascript"> var x=window.alert("You still have some pending Requests!") window.location=" SHome.php"; </script> ';
            print($l_alert_statement );
            }
            else if(isset($l_Mentor) ) {
            
            print ('<table class="ady-table-content" border="1" width="100%" >');
            print ('<th> Name </th>');
            print ('<th> Email ID </th>');
            print ('<th> Phone Number </th>');
            print ('<th> Select Guide </th></tr>');
            print ('<tr><td colspan=5><div class="alert alert-danger">Sorry!! You cannot add any Mentor in the middle of the project</div></td></tr>');
            print ('</table>');
            }
            
            else if($l_TM_id!=NULL && $l_TM_id!=-99)
            {
            /*If mentor request pending*/
               if( $l_count_MStatus >0 )
                {
            print ('<table class="ady-table-content" border="1" width="100%" >');
            print ('<th> Name </th>');
            print ('<th> Email ID </th>');
            print ('<th> Phone Number </th>');
            print ('<th> Select Guide </th></tr>');
            print ('<tr><td colspan=5><div class="alert alert-danger">Sorry Your Mentor Request Pending</div></td></tr>');
            print ('</table>');
                }
            else
               {
               ?>
         <h3> <?php ?></h3>
         <table class="ady-table-content" border="1" width="100%" >
            <tr>
               <th> Name </th>
               <th> Email ID </th>
               <th> Phone Number </th>
               <th> Select Mentor </th>
            </tr>
            <?php
               $mentorallowedquery=mysql_query('SELECT PR.PR_MentorAllowed ,UR.UR_CompanyName FROM Projects AS PR ,Users AS UR WHERE PR.UR_Owner=UR.UR_id and PR.PR_id="'.$l_PR_id.'"');
                   $mentorAllowed_res=mysql_fetch_row($mentorallowedquery);
                    $mentorAlW=$mentorAllowed_res[0];
                   $company_id=$mentorAllowed_res[1];
                   $l_sql_TargetOrg=  mysql_query('SELECT OC.TargetOrg,Org.Org_Name FROM Organisation_Customers AS OC,Organisations AS Org WHERE Org.Org_id=OC.TargetOrg AND OC.Org_id="'.$_SESSION['g_Org_id'].'"');
                  $finalcount=0;
                  While($l_TargetOrg_rows=  mysql_fetch_array($l_sql_TargetOrg)){
                      
                       ?>
            <?php   
               $l_sql_mentors=mysql_query('SELECT distinct(US.UR_id) FROM UR_Subdomains AS US, Project_SubDomains AS PSD WHERE PSD.PR_id='.$l_PR_id.' and  PSD.SD_id=US.SD_id AND PSD.SD_Preference="R" and  US.Org_id = "'.$l_TargetOrg_rows['TargetOrg'].'"');
               $finalcount = $finalcount+ mysql_num_rows($l_sql_mentors);
               While($l_mentors_rows= mysql_fetch_row($l_sql_mentors)){
               if($mentorAlW == 'Y'){
               $l_sql_mentor_details=  mysql_query('select UR_id ,UR_FirstName, UR_LastName, UR_Emailid, UR_EmailidDomain,UR_Phno from Users where UR_Type="M" and UR_id="'.$l_mentors_rows[0].'" and Org_id = "'.$l_TargetOrg_rows['TargetOrg'].'" AND UR_CompanyName="'.$company_id.'"' );
                if(mysql_num_rows($l_sql_mentor_details)>0){
              
               While($l_mentor_detailsrows= mysql_fetch_row($l_sql_mentor_details)){
               
               $l_UR_Receiver=$l_mentor_detailsrows[0];
                 ?>
            <tr>
               <td ><a href="MentorDetails.php?g_query=<?php echo $l_mentor_detailsrows[0];?>|<?php echo $l_TargetOrg_rows['TargetOrg'] ;?>"><?php echo $l_mentor_detailsrows[1]." ".$l_mentor_detailsrows[2];?></a></td>
               <td ><?php echo $l_mentor_detailsrows[3]."@".$l_mentor_detailsrows[4];?></td>
               <td ><?php echo $l_mentor_detailsrows[5];?></td>
               <td ><a class="btn btn-primary" href="SInsertMentor.php?g_query=<?php echo $l_UR_Sender.'|'.$l_UR_Receiver.'|'.$l_TM_id.'|'.$l_TT_SentDateTime .'|'.$l_TM_Message.'|MR|'.$l_TargetOrg_rows['TargetOrg'];?>">Send Request </a> </td>
            </tr>
            <?php 
               }
           }
               } 
               else {
               
               $l_sql_mentor_details_N=  mysql_query('select UR_id ,UR_FirstName, UR_LastName, UR_Emailid, UR_EmailidDomain,UR_Phno from Users where UR_Type="M" and UR_id="'.$l_mentors_rows[0].'" and Org_id = "'.$l_TargetOrg_rows['TargetOrg'].'"' );
               
                $finalcount = $finalcount+ mysql_num_rows($l_sql_mentor_details_N);
               if(mysql_num_rows($l_sql_mentor_details_N)>0){
              
               
                While($l_mentor_detailsrows_N= mysql_fetch_row($l_sql_mentor_details_N)){
               
               $l_UR_Receiver=$l_mentor_detailsrows_N[0];
                 ?>
            <tr>
               <td ><a href="MentorDetails.php?g_query=<?php echo $l_mentor_detailsrows_N[0];?>|<?php echo $l_TargetOrg_rows['TargetOrg'] ;?>"><?php echo $l_mentor_detailsrows_N[1]." ".$l_mentor_detailsrows_N[2];?></a></td>
               <td ><?php echo $l_mentor_detailsrows_N[3]."@".$l_mentor_detailsrows_N[4];?></td>
               <td ><?php echo $l_mentor_detailsrows_N[5];?></td>
               <td ><a class="btn btn-primary" href="SInsertMentor.php?g_query=<?php echo $l_UR_Sender.'|'.$l_UR_Receiver.'|'.$l_TM_id.'|'.$l_TT_SentDateTime .'|'.$l_TM_Message.'|MR|'.$l_TargetOrg_rows['TargetOrg'];?>">Send Request </a> </td>
            </tr>
            <?php 
               }
              }
                 }
              
                 } 
                
                 }
                 if($finalcount==0){
                                print('<tr><td colspan="4" style="color:red;" >Mentors not available !!</td></tr>');   
                 }
                 ?>
         </table>
         <?php } }}?>
      </div>
   </div>
</div>
<?php include('footer.php')?>