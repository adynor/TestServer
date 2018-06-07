<?php
    //////////////////////////////////////////////
    // Name            : GMview_Team_ProjFiles01
    // Project         : Projectory
    // Purpose         : Display Files/Documents of the Teams 
    // Called By       : GHome,Mhome
    // Calls           : PDFVie,GMsend_TeamFeedback
    // Mod history:
    //////////////////////////////////////////////

include ('header.php');
include ('db_config.php'); 
 ?>

<div class="container" >
<?php
$l_UR_id            = $_SESSION['g_UR_id'];  // For the Communications table we need the from id
$l_UR_Type          = $_SESSION['g_UR_Type'];
//check the user login as Guide Or Mentor
//check user id is empty
if(is_null($l_UR_id) || ($l_UR_Type!='G' && $l_UR_Type!='M'))
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a guide. Please login correctly")
        window.location.href="Signout.php"; </script> ';
 print($l_alert_statement );
}
else
{

// check The User Type is Guide
   if($l_UR_Type == 'G')
    {
     // select the Team With Documents
    //check the PD_FeedbackDate is null
            $l_proj_query = 'select  PD.PD_Name, PD.PD_SubmissionDate, AL.AL_Desc, TM.TM_Name, PD.PD_id, TM.TM_id from Teams as TM, Project_Documents as PD , Access_Level as AL where  TM.UR_id_Guide = "'.$l_UR_id.'" and TM.TM_id = PD.TM_id and AL.AL_id = PD.AL_id and PD.PD_FeedbackDate is NULL ' ;
    }
// check The User Type is Mentor
else if($l_UR_Type == 'M')
    {
    // select the Team With Documents
     //check the PD_FeedbackDate is null
            $l_proj_query = 'select  PD.PD_Name, PD.PD_SubmissionDate, AL.AL_Desc, TM.TM_Name, PD.PD_id, TM.TM_id from Teams as TM, Project_Documents as PD , Access_Level as AL where  TM.UR_id_Mentor = "'.$l_UR_id.'" and TM.TM_id = PD.TM_id and AL.AL_id = PD.AL_id and PD.PD_MFeedbackDate is NULL ' ;
    }
$l_proj_res = mysql_query($l_proj_query) or die(mysql_error());
$l_count = mysql_num_rows($l_proj_res);
 ?>
<div class="panel panel-success">
        <div class="panel-heading"><h4>Teams' Documents</h4></div>
         <div class="panel-body table-responsive table">
        <?php
print('<table class="ady-table-content" border=1 style="width:100%" >');
print ('<tr>');
print ('<th >Document  Name</th>');
print ('<th > Document type </th>');
print ('<th >Team</th>');
print ('<th >Submission Date</th>');
print ('<th >View/Download</th>');
print ('<th >Feedback</th>');

if($l_count > 0)
{
        
       while($l_proj_row = mysql_fetch_row($l_proj_res))
       {

           print ('<tr>');
            $l_PD_Name             =$l_proj_row[0];
            $l_PD_SubmissionDate   =$l_proj_row[1];
            $l_AL_Desc             =$l_proj_row[2];
            $l_TM_Name             =$l_proj_row[3];
            $l_PD_id               =$l_proj_row[4];
            $l_TM_id               =$l_proj_row[5];
            
            print( '<td>' .$l_PD_Name . '</td>');          // PD_Name
            
            print( '<td>' . $l_AL_Desc. '</td>');  	   // AL_Desc
            
            print( '<td>' . $l_TM_Name. '</td>');          // TM_Name

            $l_PD_SubmissionDate= date("d-M-Y", strtotime($l_PD_SubmissionDate));

            print( '<td>' . $l_PD_SubmissionDate.'</td>');                   // PD_SubmissionDate

            echo '<td style="text-align:center"> <a type="button"  class="btn btn-primary" href="'.$l_filehomepath.'/blob_download.php?pdid='.$l_PD_id.'" > Download</a>';
            echo "</td>";
            //check the user type  as  guide  for  Feedback
            if($l_UR_Type == 'G')
            {

                echo "<td style='text-align:center'> <input type=button class='btn btn-primary' onClick=\"window.location='GMSend_TeamFeedback.php?g_query=".$l_PD_id."|".$l_TM_id."'\" value='Feedback'>  ";
                echo "</td>";
            }
            //check the user type  as Mentor  for  Feedback
            else if($l_UR_Type == 'M')
            {

                echo "<td style='text-align:center'> <input type=button class='btn btn-primary' onClick=\"window.location='GMSend_TeamFeedback.php?g_query=".$l_PD_id."|".$l_TM_id."'\" value='Feedback'>  ";
                echo "</td>";
            }

            print('</tr>');
        }
}
else
{
print ('<tr>');
print ('<td colspan = "6" > You have no pending documents to give feedback</td>');
print ('</tr>');
}
        
mysql_free_result($l_proj_res);
 print ('</table>');
}
?>
</div></div>
<?php include('footer.php')?>