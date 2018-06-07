<?php
    include ('header.php');
    include ('db_config.php');
    ?>
<div class="row" style="padding:20px"></div>
<div class="container" >
<div class="row" style="padding:20px 0px">
<div class="col-md-12  ady-row">
<?php
    //Session data
    $l_TM_id=$_SESSION['g_TM_id'];
    $l_UR_id=$_SESSION['g_UR_id'];
    $l_PR_id=$_SESSION['g_PR_id'];
    $l_IT_id=$_SESSION['g_IT_id'];
    $l_PG_id=$_SESSION['g_PG_id'];
    $l_UR_Type = $_SESSION['g_UR_Type'];
    $l_UR_Sender=$l_UR_id;
    $l_UR_Semester=$_SESSION['g_Semester_id'];
    //session data end;
    //set the date and time
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_TT_SentDateTime = $date->format( 'YmdHi');
    //-=-=-=-=-=--=-=-==-=--=--======
    
    $l_TM_Message = 'I want to add you in my Team';
    $l_UR_id_Guide="";
    $l_result_TM_Count_rowcount=0;
    //check student and user id is not empty
    if(is_null($l_UR_id) || $l_UR_Type!='S')   // only Student is allowed
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a student. Please login correctly")
        window.location.href="login.php"; </script> ';
        
        print($l_alert_statement );
    }
    else if($l_TM_id == NULL || $l_TM_id == -99) //if Team is not formed then set team Guide not set
    {
        $l_UR_id_Guide=NULL;
        $l_result_TM_Count_rowcount=0;
    }
    else
    {
        
        $l_query_Guide='select TM.UR_id_Guide from Teams as TM where TM.TM_id='.$l_TM_id.' and TM.Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_result_Guide=mysql_query($l_query_Guide) or die(mysql_error());
        while($l_row_Guide=mysql_fetch_row($l_result_Guide))
        {
            $l_UR_id_Guide=$l_row_Guide[0];
        }
        $l_query_TM_Count='select UR.UR_id from Users as UR where UR.TM_id='.$l_TM_id .' and UR.Org_id = "'.$_SESSION['g_Org_id'].'"';
        $l_result_TM_Count= mysql_query($l_query_TM_Count) or die(mysql_error());
        $l_result_TM_Count_rowcount=mysql_num_rows($l_result_TM_Count);
        
        
    }
    
    
    if($l_UR_id_Guide!=NULL)
    {
        print ('<table class="ady-table-content" border="1" width="100%" >');
        print ('<th> Name </th>'); print ('<th> Email ID </th>');
        print ('<th> USN </th>');
        print ('<th> Add To Team </th></tr>');
        print('<tr><td colspan=7><font color="#C71585"><div class="alert alert-danger">Sorry!! You can not add a team mate as you are a part of an ongoing project</div></font></td></tr>');
        print('</table>');
    }
    else
    {
        $l_check_no_pr_students_query=  mysql_query("SELECT PR_No_Students FROM Projects WHERE PR_id='".$l_PR_id."' and (Org_id = '".$_SESSION['g_Org_id']."' or Org_id = 'ALL')");
        $l_check_no_pr_students=mysql_fetch_row($l_check_no_pr_students_query);
        
        $l_checkNoStudents = $l_check_no_pr_students[0];

        if($l_result_TM_Count_rowcount >= $l_checkNoStudents)
        {
            print ('<table class="ady-table-content" border="1" width="100%" >');
            print ('<th> Name </th>'); print ('<th> Email ID </th>');
            print ('<th> USN </th>');
            print ('<th> Add To Team </th></tr>');
            print('<tr><td colspan=7><font color="#C71585"><div class="alert alert-danger">Sorry!! You have reached the maximum limit of students to perform the project</div></font></td></tr>');
            print('</table>');
            
        }
        else
        {
            $l_query_Receiver=mysql_query('SELECT UR_Receiver,UR_Sender FROM Teammate_Request WHERE (UR_Receiver="'.$l_UR_id.'" OR UR_Sender="'.$l_UR_id.'") AND Org_id = "'.$_SESSION['g_Org_id'].'" AND TT_ResponseDateTime IS NULL');
            $l_R_or_S_result=  mysql_fetch_row($l_query_Receiver);
            if($l_R_or_S_result[0] == $l_UR_id){
                $l_r_or_s_id=$l_R_or_S_result[1] ;
            }else if ($l_R_or_S_result[1] == $l_UR_id) {
                $l_r_or_s_id=$l_R_or_S_result[0] ;
            }
            else{
                $l_r_or_s_id= NULL;
            }
            
            
            if($l_UR_PR_Type=="C"){
                $l_query_UR_teamselect="select UR.UR_id, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_Emailid, UR.UR_EmailidDomain,  UR.UR_USN  from Users as UR where  UR.UR_id <> '".$l_UR_id."' and UR.Org_id = '".$_SESSION['g_Org_id']."' and UR.UR_Type = 'S' and (UR.TM_id is NULL OR UR.UR_id='".$l_r_or_s_id."')  and UR.UR_RegistrationStatus ='C' and UR.IT_id = ".$l_IT_id."  and UR.PG_id = ".$l_PG_id." and UR.UR_Semester = ".$l_UR_Semester."  and UR.PR_id=".$l_PR_id." and UR_PR_Type='".$l_UR_PR_Type."'";
            }
            else {
                $l_query_UR_teamselect="select UR.UR_id, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_Emailid, UR.UR_EmailidDomain,  UR.UR_USN  from Users as UR where  UR.UR_id <> '".$l_UR_id."' and UR.UR_Type = 'S' and (UR.TM_id is NULL OR UR.UR_id='".$l_r_or_s_id."')  and UR.Org_id = '".$_SESSION['g_Org_id']."' and UR.UR_RegistrationStatus ='C' and  UR.PR_id=".$l_PR_id." and UR_PR_Type='".$l_UR_PR_Type."'";
                
            }
            $l_result_UR_teamselect= mysql_query($l_query_UR_teamselect) or die(mysql_error());
            $l_UR_teamselect_count = mysql_num_rows($l_result_UR_teamselect);
            print ('<table class="ady-table-content" border="1" width="100%" >');
            print ('<th> Name </th>');
            print ('<th> Email ID </th>');
            print ('<th> USN </th>');
            print ('<th> Add To Team </th>');
            if($l_UR_teamselect_count==0) // if nobody has applied for this project
            {
                print('<tr><td colspan = 6><div class="alert alert-info">There are no students who have applied for this project</div></td></tr>');
            }
            else
            {
                while($l_row_UR_teamselect=mysql_fetch_row($l_result_UR_teamselect))  // show all the students who are performing same project
                {
                    $l_UR_Receiver= $l_row_UR_teamselect[0] ;
                    print('<tr>');
                    print('<td>'.$l_row_UR_teamselect[1].' '.$l_row_UR_teamselect[2].' '.$l_row_UR_teamselect[3].'</td>');
                    print('<td>'.$l_row_UR_teamselect[4].'@'.$l_row_UR_teamselect[5].'</td>');
                    print('<td>'.$l_row_UR_teamselect[6].'</td>');
                    print('<td>');
                    if($l_r_or_s_id == NULL){
                        
                        print('<input type="button" class="btn-primary" value="Add Teammate" onClick=\'window.location="SInsertTeamRequest.php?g_query='.$l_UR_Sender.'|'.$l_UR_Receiver.'|'.$l_TM_id.'|'.$l_TT_SentDateTime.'|'.$l_TM_Message.'|TR"\'></input>');
                    } else if ($l_R_or_S_result[1] == $l_UR_id) {
                        if($l_UR_Receiver == $l_r_or_s_id){
                            print('<a class="btn btn-primary" href="SUpdateComm.php?g_updSQL=Cancel"> Cancel </a>');
                        }
                        else{
                            //  echo $l_UR_Receiver ."|".$l_r_or_s_id;
                            echo "<p style='color: red !important;text-align: center;'>Your Response Pending</p>";
                        }
                    }
                    else if ($l_R_or_S_result[0] == $l_UR_id) {
                        if($l_UR_Receiver == $l_R_or_S_result[1]){
                            print('<a class="btn btn-primary" href="SUpdateComm.php?g_updSQL=Accept" > Accept </a>&nbsp&nbsp&nbsp');
                            print('<a class="btn btn-primary" href="SUpdateComm.php?g_updSQL=Reject"> Reject </a>');
                        }
                        else{
                            echo "";
                        }
                    }
                    print('</td></tr>');
                    
                }
            }
            print ('</table>');
            
            
            
        }
    }
    if($l_TM_id == NULL || $l_TM_id == -99){
      $l_limitstudentque=mysql_query('SELECT PR.PR_No_Students  FROM  Projects PR WHERE PR_id='.$l_PR_id);
      $l_PR_limit=mysql_fetch_row($l_limitstudentque)[0];
      function getCountTeammates($teamid)
      {
      $Tque=mysql_query('SELECT UR_id FROM  Users PR WHERE TM_id='.$teamid);
      $teammatecount=mysql_num_rows($Tque);
      return $teammatecount;
      }
    if($l_UR_PR_Type=="C"){
      $teamque='SELECT  TM.TM_Name,UR.UR_FirstName,UR.UR_MiddleName,UR.UR_LastName,UR.UR_Emailid,UR.UR_EmailidDomain,TM.TM_id  FROM Teams as TM,Users as UR WHERE UR.TM_id = TM.TM_id and UR.PR_id='.$l_PR_id.' and UR.IT_id='.$l_IT_id.' and UR.PG_id='.$l_PG_id.' and UR.UR_PR_Type="'.$l_UR_PR_Type.'" ORDER BY TM.TM_id ';
    } else {
    $teamque='SELECT  TM.TM_Name,UR.UR_FirstName,UR.UR_MiddleName,UR.UR_LastName,UR.UR_Emailid,UR.UR_EmailidDomain,TM.TM_id   FROM Teams as TM,Users as UR WHERE UR.TM_id = TM.TM_id and UR.PR_id='.$l_PR_id.'  and UR.UR_PR_Type="'.$l_UR_PR_Type.'" ORDER BY TM.TM_id ';
    }
    $teamsql=mysql_query($teamque);
if(mysql_num_rows($teamsql) >0){
    ?>
    <br><br>
    
      <table class="ady-table-content" border="1" width="100%" >
            <tr>
	            <th> Team Name </th>
	            <th> Name </th>
	            <th> Email ID </th>
	            <th> Join With us </th>
            </tr>
            <?php $l_prev_teamname="";
            
            while($teamsresrow=mysql_fetch_row($teamsql)){ 
            if($l_PR_limit > getCountTeammates($teamsresrow[6])){
            ?>
            
            <tr>
                    <td> <?php if($l_prev_teamname <> $teamsresrow[0]){
                    echo $teamsresrow[0]; }
                    ?></td>
	            <td><?php echo $teamsresrow[1].' '.$teamsresrow[2].' '.$teamsresrow[3]; ?></td>
	            <td><?php echo $teamsresrow[4].'@'.$teamsresrow[5]; ?></td>
	            <td>
	            
	            <?php if($l_prev_teamname <> $teamsresrow[0]){ 
	                    if($l_r_or_s_id == NULL){ ?>
	            <a class="btn btn-block btn-default" href="STeamJoin.php?team=<?php echo $teamsresrow[6]; ?>">Join <?php //echo getCountTeammates($teamsresrow[6]); ?></a> 
	            <?php   } else {  if('TEAM@#'.$teamsresrow[6] === $l_r_or_s_id){
                            print('<a class="btn btn-block btn-primary" href="SUpdateComm.php?g_updSQL=Cancel"> Cancel </a>');
                        }
                        else{
                            //  echo $l_UR_Receiver ."|".$l_r_or_s_id;
                            echo "<p style='color: red !important;text-align: center;'>Your Response Pending</p>";
                        } }
	              ?>
	            <?php }?>
	            
	             </td>  
            </tr>
            <?php }
             $l_prev_teamname =$teamsresrow[0] ;} ?>
       </table>
       <?php } } ?>
</div>
</div>
  <div>
    <label >Invite Friends</label>
    <input type="text" name="InvtFrnd" style="width: 300px;height: 33px;"id="InvtFrnd" >
    <input type="submit" name="go"  class="btn btn-primary " id="InvtFrndGo" value="go"> 
  </div>
</div>
<script>
$(function(){
    $("#InvtFrndGo").on('click',function(){
    var dataval=$("#InvtFrnd").val();
    alert();
      $.ajax({
         url: "InviteFrnd.php?fun=checkEmail",
         type: "POST",
         data: {email:dataval},
         dataType: "json",
         success: function (data) {
             alert(data.count);},
            complete:function(){
             $.ajax({
                 url: "InviteFrnd.php?fun=sendEmail",
                 type: "POST",
                 data: {email:dataval},
                 dataType: "json",
                 success: function (data1) {
                 alert();
                   
                 }
             });
         },
       });
    });
});
</script>
<?php include('footer.php')?>