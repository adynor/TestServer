<?php

if(!session_id()) 
{
     session_start();
}

include ('db_config.php');
include ('header.php');

?>
<br><br><br><br>
   <div class="container" >
       
 <div class="row " style="padding:0px 0px">
           <div class="ady-row">


<?php 


$l_UR_id        = $_SESSION['g_UR_id'];  // For the Communications table we need the from id
$l_UR_Type     = $_SESSION['g_UR_Type']; 
if(is_null($l_UR_id) || $l_UR_Type!='T')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as the Zaireprojects Admin. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login"; </script> ';

        print($l_alert_statement );
}

// For date and time 
$l_LastLoginDate_query = 'select  UR_LastLogin from Users where UR_id = "'.$l_UR_id.'"' ;
$l_LastLoginDate = mysql_query($l_LastLoginDate_query) or die(mysql_error());
$l_Date=mysql_fetch_row($l_LastLoginDate);
$l_LoginDate_res=$l_Date[0];

$l_LoginDate_res= date("d-M-Y h:i A", strtotime($l_LoginDate_res));


// query for new students in portal
$queryNewStudent='select UR_Type from Users where
UR_Type="S" and UR_RegistrationStatus="P"';
$resultS=mysql_query($queryNewStudent) or die(mysql_error());
$NewUserS=mysql_num_rows($resultS);

// query for new companies in portal
$queryNewCompany='select UR_Type from Users where
UR_Type="C" and UR_RegistrationStatus="P"';
$resultC=mysql_query($queryNewCompany) or die(mysql_error());
$NewUserC=mysql_num_rows($resultC);

// query for new Guides in portal
$queryNewG='select UR_Type from Users where
UR_Type="G" and UR_RegistrationStatus="P"';
$resultG=mysql_query($queryNewG) or die(mysql_error());
$NewUserG=mysql_num_rows($resultG);

// query for new Mentors in portal
$queryNewM='select UR_Type from Users where
UR_Type="M" and UR_RegistrationStatus="P"';
$resultM=mysql_query($queryNewM) or die(mysql_error());
$NewUserM=mysql_num_rows($resultM);

// query for new College Admins in portal
$queryNewA='select UR_Type from Users where
UR_Type="A" and UR_RegistrationStatus="P"';
$resultA=mysql_query($queryNewA) or die(mysql_error());
$NewUserA=mysql_num_rows($resultA);
$UnregisteredStudents=mysql_num_rows(mysql_query('select UR.UR_Type from Users as UR where
UR.UR_Type="S"  AND UR.IT_id=0 AND UR.UR_ProfileInfo <> ""'));
print('<div class="alert alert-info">Last logged in on&nbsp;' .$l_LoginDate_res. '</div>');
print('<table class="ady-table-content" border=1 style="width:100%">');
print('<tr><th><center>Admin Page</center></th></tr>');
print('<tr><td><a href="'.$l_filehomepath.'/TStatistics.php">Check Statistics</a></td></tr>');
print('<tr><td><a href="'.$l_filehomepath.'/TView_NewCompanies.php">Check for new companies</a>&nbsp; <span class="ady-badge">'.$NewUserC.'</span></td></tr>');

print('<tr><td><a href="'.$l_filehomepath.'/TView_NewMentors.php">Check for new Mentors</a>&nbsp;<span class="ady-badge">'.$NewUserM.'</span></td></tr>');

print('<tr><td><a href="'.$l_filehomepath.'/TView_NewStudents.php">Check for new Students</a>&nbsp;<span class="ady-badge">'.$NewUserS.'</span></td></tr>');
print('<tr><td><a href="'.$l_filehomepath.'/TView_NewUnRegisterS.php">Check for new Unregistred Users</a>&nbsp;<span class="ady-badge">'.$UnregisteredStudents.'</span></td></tr>');
print('<tr><td><a href="'.$l_filehomepath.'/TView_NewGuides.php">Check for new Guides</a>&nbsp;<span class="ady-badge">'.$NewUserG.'</span></td></tr>');

print('<tr><td><a href="'.$l_filehomepath.'/TView_NewCollegeAdmins.php">Check for new College Admins</a>&nbsp;<span class="ady-badge">'.$NewUserA.'</td></tr>');

print('<tr><td><a href="'.$l_filehomepath.'/TView_Projects01.php">View projects in the portal</a></td></tr>');

print('<tr><td><a href="'.$l_filehomepath.'/TAdd_NewInstitute.php">Add a new Institute</a></td></tr>');

print('<tr><td><a href="'.$l_filehomepath.'/TAdd_NewProgram.php">Add a new Program</a></td></tr>');
print('<tr><td><a href="'.$l_filehomepath.'/TAdd_DefaultTemplate.php">Add Default Template</a></td></tr>');

print('<tr><td><a href="'.$l_filehomepath.'/TSendMail.php">Send Mail</a></td></tr>');
print('<tr><td><a href="'.$l_filehomepath.'/TCreateUser.php">Create User</a></td></tr>');
print('<tr><td><a href="'.$l_filehomepath.'/PaidCreateUser.php">Walet Registration</a></td></tr>');

print('</table>');
print('</body></form></html>');
?>
</div></div></div>
<?php include('footer.php')?>