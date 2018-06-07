<?php
    //////////////////////////////////////////////
    // Name         : GMPendingRequest
    // Project      : Projectory
    // Purpose      : Display The Pending Request of Guide And mentor
    // Called By    : Ghome
    // Calls        : GupdateComm,MupdateComm
    // Mod history  :
    //////////////////////////////////////////////

 include ('header.php');
 include ('db_config.php');
 ?>
<div class="row" style="padding:20px"></div>
<div class="row" style="padding:20px"></div>
<div class="container" >
<?php
$l_UR_Type = $_SESSION['g_UR_Type'];
$l_UR_id  = $_SESSION['g_UR_id'];   // person loggin in

/////check the User login as a guide or mentor
/////Check The User Id not Empty ,User type not G and not M
if(is_null($l_UR_id) || ($l_UR_Type!='G' && $l_UR_Type!='M'))
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a guide. Please login correctly")
        window.location.href="login.php"; </script> ';

        print($l_alert_statement );
}
else
{


$l_UR_Receiver = $l_UR_id;

//check user should guide

if($l_UR_Type == 'G')
{
//select the pending request of team with projects
    $l_query =  "select  GR.GR_SentDateTime, PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, TM.TM_Name, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_USN, UR.UR_Semester, UR.UR_Phno, TM.TM_id, PR.PR_id from Guide_Requests as GR, Projects as PR, Teams as TM, Users as UR where UR.TM_id   = GR.TM_id and TM.TM_id   = GR.TM_id and PR.PR_id   = TM.PR_id and UR.UR_Type= 'S' and GR.GR_ResponseDateTime is null and GR.Org_id='".$_SESSION['g_Org_id']."' AND TM.Org_id='".$_SESSION['g_Org_id']."' and GR.UR_id   = '".$l_UR_Receiver."' order by TM.TM_Name, UR.UR_FirstName";
}
//check user should Mentor
else if($l_UR_Type == 'M')
{
//select the pending request of team with projects
    $l_query =  "select  MR.MR_SentDateTime, PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, TM.TM_Name, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_USN, UR.UR_Semester, UR.UR_Phno, TM.TM_id, PR.PR_id,TM.Org_id,PR.MO_id from Mentor_Requests as MR, Projects as PR, Teams as TM, Users as UR where UR.TM_id   = MR.TM_id and TM.TM_id   = MR.TM_id and PR.PR_id   = TM.PR_id and MR.Org_id='".$_SESSION['g_Org_id']."' and UR.UR_Type= 'S' and MR.MR_ResponseDateTime is null and MR.UR_id   = '".$l_UR_Receiver."' order by TM.TM_Name, UR.UR_FirstName";
}

$l_proj_res = mysql_query($l_query) or die(mysql_error());    // run the actual SQL
$l_count = mysql_num_rows($l_proj_res);

if($l_count > 0)
{
print('<table class="ady-table-content" border=1 style="width:100%" >');
print ('<tr>');
print ('<th >Team </th>');
print ('<th > Project </th>');
print ('<th >Team Members</th>');
print ('<th > Team Member  Details </th>');
print ('<th ></th>');
print ('<th ></th>');
print ('</tr>');
        $l_prev_teamname = 'Dummyname';
        
        while ($l_row = mysql_fetch_row($l_proj_res )) 
        {
            $l_TM_Name      = $l_row[4];
            $l_PR_Name      = $l_row[1];
            $l_UR_Name      = $l_row[5].' '.$l_row[6].' '.$l_row[7];
            $l_UR_USN1      = $l_row[8];
            $l_UR_Sem       = $l_row[9];
            $l_UR_Phone     = $l_row[10];
            $l_TM_id        = $l_row[11];
            $l_PR_id        = $l_row[12];
            $l_User_org       = $l_row[13];
            $l_model       = $l_row[14];
            print ('<tr style="' . $l_UR_Color_Table02. '">');
            
                if($l_prev_teamname <> $l_TM_Name)
                    {
                        print ( '<td>' . $l_TM_Name. '</td>');
                        print ( '<td>' . $l_PR_Name. '</td>');
                    }
                    else
                    {
                        print ( '<td> </td>');
                        print ( '<td> </td>');
                    }
                print ( '<td>' . $l_UR_Name. '</td>');
               
                print ( '<td>'.'USN- '.$l_UR_USN1.', Semester- '. $l_UR_Sem.', Phone- '.$l_UR_Phone.'</td>');
                
                if($l_prev_teamname <> $l_TM_Name)
                {
        // Check the User type should be Guide and accept/reject the request
                     if($l_UR_Type == 'G')
                     {
print ( "<td><input type='button'  value='Accept' class='btn btn-primary' onClick=\"window.location='GUpdateComm.php?g_updSQL=Accept|".$l_TM_id."|".$l_PR_id."'\"> </td>");

                    print ( "<td><input type='button'  class='btn btn-primary' value='Reject' onClick=\"window.location='GUpdateComm.php?g_updSQL=Reject|".$l_TM_id."'\"> </td>");
                     }
         // Check the User type should be Mentor and accept/Reject the request
                     else if($l_UR_Type == 'M')
                     {
print ( "<td><input type='button'  class='btn btn-primary' value='Accept' onClick=\"window.location='MUpdateComm.php?g_updSQL=Accept|".$l_TM_id."|".$l_PR_id."|".$l_User_org."&&model=".$l_model."'\"> </td>");

                    print ( "<td><input type='button' class='btn btn-primary' value='Reject' onClick=\"window.location='MUpdateComm.php?g_updSQL=Reject|".$l_TM_id."|".$l_User_org."'\"> </td>");
                     }
                }
                else 
                 {
                    print ( '<td></td>');
                    
                    print ( '<td></td>');
                 }
          	print ( '</tr>');
                
                $l_prev_teamname = $l_TM_Name;
         }

print ('</table>');

     }
else
    {

print('<table class="ady-table-content" border=1 style="width:100%" >');
print ('<tr>');
print ('<th >Team </th>');
print ('<th > Project </th>');
print ('<th >Team Members</th>');
print ('<th > Team Member  Details </th>');
print('</tr>');
    print ('<tr>');
    print ('<td style="text-align:center" colspan = "4" > <div class="alert alert-success">You currently do not have any more requests.</div></td>');
    print ('</tr>');
print ('</table>');
}

}

?>

</div>
<?php include('footer.php')?>