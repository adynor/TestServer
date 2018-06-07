<?php
    //////////////////////////////////////////////
    // Name            : MHome
    // Project         : Projectory
    // Purpose         : Interface for Mentor Dasboad
    // Called By       : Login
    // Calls           : AddProject,GMview_STeams,GMview_Team_ProjFiles01,GMTeamDocs,MProjList01,GMPendingRequest,mentorhelp
    // Mod history:
     //////////////////////////////////////////////
include ('db_config.php');
include ('header.php');  
?>

<div class="container" >
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js" integrity="sha256-xoE/2szqaiaaZh7goVyF5p9C/qBu9dM3V5utrQaiJMc=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css" integrity="sha256-zV9aQFg2u+n7xs0FTQEhY0zGHSFlwgIu7pivQiwJ38E=" crossorigin="anonymous" />   
    
<style>


.mystyle{
border: 1px solid rgba(128, 128, 128, 0.34) !important;
    
    width: 100px !important;
    height: 27px !important;
    }
    
    .yourstyle{
    border: 1px solid #d9534f !important;
    width: 100px !important;
    height: 27px !important;
    }
</style>


 <?php   

$l_UR_id = $_SESSION['g_UR_id'];
$l_UR_Type = $_SESSION['g_UR_Type'];


// Select the User Last Login
$l_LastLoginDate_query = 'select  UR_LastLogin from Users where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"' ;
$l_LastLoginDate = mysql_query($l_LastLoginDate_query) or die(mysql_error());
$l_Date=mysql_fetch_row($l_LastLoginDate);
$l_LoginDate_res=$l_Date[0];

$l_LoginDate_res= date("d-M-Y h:i A", strtotime($l_LoginDate_res));


print('<div style="clear:left">');


//Check the User Id null or user type not M(Mentor)
/*if(is_null($l_UR_id) || $l_UR_Type!='M')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a Mentor. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login"; </script> ';

        print($l_alert_statement );
}

else
{*/
$l_UR_Details_query = 'select UR_FirstName from Users where UR_id = "'.$l_UR_id.'" and Org_id = "'.$_SESSION['g_Org_id'].'"';
$l_UR_Details_result = mysql_query($l_UR_Details_query) or die(mysql_error);
$l_UR_Name_row = mysql_fetch_row($l_UR_Details_result);
$l_UR_FName = $l_UR_Name_row[0];

$l_checkrequest_query = 'select TM_id from Users where UR_id = "'.$l_UR_id.'" and UR_Type = \'M\' and Org_id = "'.$_SESSION['g_Org_id'].'"';
$l_checkrequest_result = mysql_query($l_checkrequest_query) or die(mysql_error());
$l_TM_id_row = mysql_fetch_row($l_checkrequest_result);
$l_TM_id = $l_TM_id_row[0];

/*print('<div  style="float:left;"><h2><b> <font color="#4682b4">Welcome to Projectory :</font><font color="ff6347"> '.$l_UR_FName.'</font></b></h2></div>
<br><div  style="float:right;"> <a href="'.$l_filehomepath.'/EditProfile" ><font color="00ccff"><u>[Edit Profile]</u></font></a> <a href="'.$l_filehomepath.'/mentorhelp"><u> Help </u></a></div>');


print('<div align="center"><font color="#4682b4">logged in at '.$l_LoginDate_res. '</font></div>');*/
$notificationguide='select PD.PD_FeedbackDate,TM.TM_Name,PD.PD_Status from Project_Documents as PD,Teams as TM where PD.PD_FeedbackDate is NUll and PD.PD_Status="P"  and TM.TM_id=PD.TM_id  and TM.UR_id_Mentor="'.$l_UR_id.'" order by PD.PD_SubmissionDate DESC limit 0,1';
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
} ?>
<?php
//Check the  pending Request
if($l_TM_id == -99)
{
     // print('<div class="alert alert-warning" role="alert"> You have one or more Pending requests. Please <a  class="alert-link" href="GMPendingRequest.php">Click Here</a> to view them</div>');
}
 print('<div class="row "><div class="col-md-4 "></div>');
print('<div class="col-md-4 "><table class="ady-row" border ="0" bordercolor="#718DE2">');
print('<tr><td><a class="btn btn-primary ady-btn"  href="AddProject.php">Add a project</a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn"  href="GMView_STeams.php">View Teams under you</a></td></tr>');
print('<tr><td><a  class="btn btn-primary ady-btn" href="GMView_Team_ProjFiles01.php">View Teams\' Documents </a></td></tr>');
print('<tr><td><a class="btn btn-primary ady-btn"  href="GMTeamDocs.php">View Teams\' Feedbacks </a></td></tr>');

print('<tr><td><a class="btn btn-primary ady-btn"  href="MProjList01.php">View your Projects</a></td></tr>');

print('<tr><td><a  class="btn btn-primary ady-btn"  href="GMPendingRequest.php">Pending Requests</a></td></tr>');
 print('</table></div>');
 print('<div class="col-md-4 "></div></div>');



//-- display the Dashboard --------------------------------//
    $l_sql_UR = 'Select UR.UR_ProfileInfo from Users as UR where UR.UR_id="' . $l_UR_id . '" and UR.Org_id = "'.$_SESSION['g_Org_id'].'"';
   //Display the User Profile Details
$l_sql_CompanyName = 'Select  UR.UR_FirstName , UR.UR_MiddleName , UR.UR_LastName from Users as UR where UR.UR_id = (select innerUR.UR_CompanyName from Users as innerUR where innerUR.UR_id="' . $l_UR_id . '") and UR.Org_id = "'.$_SESSION['g_Org_id'].'" ';


 $l_result_UR = mysql_query($l_sql_UR) or die(mysql_error);
 $l_result_CN = mysql_query($l_sql_CompanyName);   
   
 $l_row_UR = mysql_fetch_row($l_result_UR);
 $l_row_CN = mysql_fetch_row($l_result_CN);
 $l_count_CN = mysql_num_rows($l_result_CN);

 print('<table class="ady-table-dashboard" border=1 style="width:100%" >');
    print('<tr><th  colspan="2" ><center>Dashboard</th></tr>');
   print( "<tr><td><strong>Profile Info:</strong> ".$l_row_UR[0]."</td></tr>");
    print( "<tr><td><strong>Company name:</strong> ".$l_row_CN[0]." ".$l_row_CN[1]." ".$l_row_CN[2]."</td></tr></table>");
  

// to display the Teams With team Details
    $l_teaminfo_query =  "select PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, TM.TM_Name, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_USN, UR.UR_Semester, UR.UR_Phno,TM.UR_id_Guide from Projects as PR, Teams as TM, Users as UR where UR.TM_id   = TM.TM_id and PR.PR_id   = TM.PR_id and TM.UR_id_Mentor   = '".$l_UR_id."' and TM.Org_id = '".$_SESSION['g_Org_id']."' and TM.TM_EndDate is NULL order by TM.TM_Name, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName";

    $l_teaminfo_res = mysql_query($l_teaminfo_query) or die(mysql_error); 
    $l_count = mysql_num_rows($l_teaminfo_res);
    if($l_count>0)
    { 
        print('<br /><br/><table class="ady-table-dashboard" border=1 style="width:100%" >');
        print ('<tr><th>Teams currently under you</th></tr>');
    }


    $l_prev_teamname = 'Dummyname';
    
    while ($l_row = mysql_fetch_row($l_teaminfo_res)) 
        {
            $l_TM_Name= $l_row[3];
            $l_PR_Name= $l_row[0];
            $l_UR_Name= $l_row[4] . ' ' . $l_row[5] . ' ' . $l_row[6];
            $l_UR_USN= $l_row[7];
            $l_guide_id=$l_row[10];
            //chek the teams list is not Zero
            if($l_prev_teamname <> $l_TM_Name)
                    {
                        print ('<tr style="background:#99CCFF"><td><strong>Team:</strong>' . $l_TM_Name. '</td><t/r>');
                        print ('<tr><td><strong>Project:</strong>' . $l_PR_Name. '</td></tr>');
                        print ('<tr><td><strong>Guide:</strong> ' .$l_guide_id. '</td></tr>');
                        print ('<tr><td colspan><strong>Students in the Team:</strong>');
                    }
                    
                print ('<br/>'.$l_UR_Name);
               
                $l_prev_teamname =  $l_TM_Name;
            
        }
    
        mysql_free_result($l_teaminfo_res);
         print('</td></tr>');
   print('</table><br>');
//}

?>
 </div> 
<?php include('footer.php')?>