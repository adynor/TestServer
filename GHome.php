<?php
    //////////////////////////////////////////////
    // Name            : Ghome
    // Project         : Projectory
    // Purpose         : Guide's Home Page
    // Called By       : login01
    // Calls           : Addproject, GMview_STeams, GMview_Team_ProjFiles01, gmteamdocs, GAddMarks,       GProjList01
    // Mod history:
    //////////////////////////////////////////////

include ('db_config.php');
include ('header.php');
?>
<div class="container" >
       <div class="row" style="padding:20px 0px">
           <div class="col-md-12  ady-row">
               
 <?php 
$l_UR_id = $_SESSION['g_UR_id'];
$l_UR_Type = $_SESSION['g_UR_Type'];
$l_UR_FName = $l_UR_Name_row[0];

// For date and time 
$l_LastLoginDate_query = 'select  UR_LastLogin from Users where Org_id="'.$_SESSION['g_Org_id'].'" and UR_id = "'.$l_UR_id.'"' ;
$l_LastLoginDate = mysql_query($l_LastLoginDate_query) or die(mysql_error());
$l_Date=mysql_fetch_row($l_LastLoginDate);
$l_LoginDate_res=$l_Date[0];

$l_LoginDate_res= date("d-M-Y h:i A", strtotime($l_LoginDate_res));



print('<div style="clear:left">');



//Check the user should login as a guide 
if(is_null($l_UR_id) || $l_UR_Type!='G')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a guide. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login.php"; </script> ';

        print($l_alert_statement );
}

else
{

$l_UR_Details_query = 'select UR_FirstName from Users where Org_id="'.$_SESSION['g_Org_id'].'" and UR_id = "'.$l_UR_id.'"';
$l_UR_Details_result = mysql_query($l_UR_Details_query);
$l_UR_Name_row = mysql_fetch_row($l_UR_Details_result);
$l_UR_FName = $l_UR_Name_row[0];

$l_checkrequest_query = 'select TM_id from Users where Org_id="'.$_SESSION['g_Org_id'].'" and UR_id = "'.$l_UR_id.'" and UR_Type = \'G\' ';
$l_checkrequest_result = mysql_query($l_checkrequest_query);
$l_TM_id_row = mysql_fetch_row($l_checkrequest_result);
$l_TM_id = $l_TM_id_row[0];

$notificationguide='select PD.PD_FeedbackDate,TM.TM_Name,PD.PD_Status from Project_Documents as PD,Teams as TM where PD.PD_FeedbackDate is NUll and PD.PD_Status="P"  and TM.TM_id=PD.TM_id  and TM.UR_id_Guide="'.$l_UR_id.'" order by PD.PD_SubmissionDate DESC limit 0,1';
$runnoti=  mysql_query($notificationguide);
$runresult=  mysql_fetch_row($runnoti);

  ?>
<div class="row alert alert-info" style="font-size: large;     margin-top: 14px;">
    <div class="col-md-5">
    <b>Welcome to Projectory:&nbsp;</b><font color="ff6347"><?php echo $l_UR_FName;?></font>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-4 ady-logged-in" >
   logged in at <?php echo $l_LoginDate_res;?>
    </div>
</div>
       
<?php if($runresult[0]==NULL && $runresult[2]=='P'){ ?>
<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Notification: </strong><b><?php echo $runresult[1]; ?></b>has submitted a document<a href="GMView_Team_ProjFiles01.php"> Click here  </a>to give feedback
</div>  
 <?php
} 
    
// check for pending request 
if($l_TM_id == -99)
{
      print('<h5>You have one or more Pending requests. Please <a href="GMPendingRequest.php">Click Here</a> to view them</h5>');
}
print('<div class="row" >');
print('<div class="col-md-4" ></div>');
print('<div class="col-md-4" >');
print('<table class="ady-row" border ="0">');
print('<tr><td><a class="btn btn-primary ady-btn" role="button" href="AddProject.php">Add a project</a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn" role="button" href="GMView_STeams.php">View Teams under you</a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn" data-toggle="popover" data-trigger="hover" data-content="View and give Feedback to these documents." role="button" href="GMView_Team_ProjFiles01.php">View Teams\' Documents </a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn" data-toggle="popover" data-trigger="hover" data-content="View documents which you have given  feedbacks." role="button" href="GMTeamDocs.php">View Teams\' Feedbacks </a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn" data-toggle="popover" data-trigger="hover" data-content="Marks must be given after all documents submition" role="button" href="GAddMarks.php">Give Marks </a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn" role="button" href="GProjList01.php">View your Projects</a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn" role="button" href="GMPendingRequest.php">Pending Requests</a></td></tr>');
print('</table>');
print('</div><div class="col-md-4" ></div>');
print('</div >');


//-- display the Dashboard --------------------------------//
// Display the Guide Program And Institute
    $l_sql_UR = 'select PG.PG_Name, IT.IT_Name from Users as UR, Institutes as IT, Programs as PG where IT.IT_id   = UR.IT_id and PG.PG_id  = UR.PG_id and UR.Org_id="'.$_SESSION['g_Org_id'].'" and UR.UR_id="' . $l_UR_id . '"';
    $l_result_UR = mysql_query($l_sql_UR);
    $l_row_UR = mysql_fetch_row($l_result_UR);


     print ('<br/>');
    print('<table class="ady-table-dashboard" border=1 style="width:100%">');
    print('<tr><th><center>Dashboard<center></th></tr>');
    print( "<tr><td>Your Institute is: "  . $l_row_UR[1] . "</td></tr>");
    print( "<tr><td>Your Program is: "  . $l_row_UR[0] . "</td></tr>");
    mysql_free_result($l_result_UR);


// Display the no. of Teams(Which team requested accepted by Same guide) with Project details 
   $l_teaminfo_query =  "select PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, TM.TM_Name, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_USN, UR.UR_Semester, UR.UR_Phno from Projects as PR, Teams as TM, Users as UR where UR.TM_id   = TM.TM_id and PR.PR_id   = TM.PR_id and TM.Org_id='".$_SESSION['g_Org_id']."' and TM.UR_id_Guide   = '".$l_UR_id."' and TM.TM_EndDate is NULL order by TM.TM_Name, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName";

    $l_teaminfo_res = mysql_query($l_teaminfo_query); 
    $l_count = mysql_num_rows($l_teaminfo_res);
    //Check the Teams are available 
    if($l_count>0)
    {
         
        print ('</table><br/><table class="ady-table-dashboard" border=1 style="width:100%;"><tr><th>Teams currently under you</th></tr>');
    }


    $l_prev_teamname = 'Dummyname';
    while ($l_row = mysql_fetch_row($l_teaminfo_res)) 
        {
            $l_TM_Name= $l_row[3];
            $l_PR_Name= $l_row[0];
            $l_UR_Name= $l_row[4] . ' ' . $l_row[5] . ' ' . $l_row[6];
            $l_UR_USN= $l_row[7];
            
            if($l_prev_teamname <> $l_TM_Name)
                    {
                        print ('</td></tr><tr>');
                        print ('<td bgcolor="#99CCFF">Team:' . $l_TM_Name. '</td>');
                        print ('</tr>' );
                        print ('<tr><td>Project:' . $l_PR_Name. '</td></tr>');
                        print ('<tr><td>Students in the Team:');
                    }
                    
                print ('<br/>'.$l_UR_Name);
                
                $l_prev_teamname =  $l_TM_Name;
            
        }
    
        mysql_free_result($l_teaminfo_res);
   print('</table>');
}


?>
</div></div></div>
<?php include('footer.php')?>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>