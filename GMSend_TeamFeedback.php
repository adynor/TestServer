<?php
include ('header.php');
include ('db_config.php'); 

    //////////////////////////////////////////////
    // Name            : GMsend_TeamFeedback
    // Project         : Projectory
    // Purpose         : Display Files/Documents of the Teams 
    // Called By       : GMview_Team_ProjFiles01
    // Calls           : GHome,Mhome,EmailNotifications
    // Mod history:
    //////////////////////////////////////////////
//ob_start();
 ?>
<div class="row" style="padding:20px"></div>
<div class="row" style="padding:20px"></div>
<div class="container" >
    <div class="row" >
        <div class="col-md-3" ></div>
             <div class="col-md-6" >
 <?php
$l_UR_id = $_SESSION['g_UR_id'];
$l_UR_Type = $_SESSION['g_UR_Type'];
//Check the user login as a guide or Mentor
//Check The User Id is empty
if(is_null($l_UR_id) || ($l_UR_Type!='G' && $l_UR_Type!='M'))
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a guide. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login"; </script> ';

        print($l_alert_statement);
}
else
{




$l_UR_USN          = $_SESSION['g_UR_USN'];  // this is needed by the SQLs that run in this php
$l_UR_id                = $_SESSION['g_UR_id'];  // For the communication table we need the from id


if (is_null( $l_UR_id ))  // send the control back to login page - just in case some hacker is trying to run the program directly
        { header('Location:login.php'); }
if(isset($_POST['submit']) )
       {
        $l_PD_Feedback = $_POST['l_feedback'];
        $l_PD_Rating   = $_POST['l_rating_sel'];
        $l_PD_Status   = $_POST['l_status_sel'];
        $l_PD_id       = $_POST['l_PD_id'];
        $l_TM_id       = $_POST['l_TM_id'];
        
        $timezone = new DateTimeZone("Asia/Kolkata" );
        $date = new DateTime();
        $date->setTimezone($timezone );
        $l_PD_FeedbackDate = $date->format( 'YmdHi' );
        


        if($l_UR_Type=='G')
        {
        // Set the Guide Feedback for Teams
$l_sql = 'update Project_Documents set PD_Feedback = "'.$l_PD_Feedback.'", PD_FeedbackDate = '.$l_PD_FeedbackDate.', PD_Status = "'.$l_PD_Status.'", PD_Rating = "'.$l_PD_Rating.'" where PD_id = '.$l_PD_id ;
            mysql_query($l_sql);
  //header('Location:EmailNotifications.php?g_query=Guide|'.$l_TM_id.'|'.$l_PD_id.'|'.$l_PD_Feedback.'|'.$l_PD_Status.'');
 echo "<script>window.location.href='EmailNotifications.php?g_query=Guide|".$l_TM_id."|".$l_PD_id."|".$l_PD_Feedback."|".$l_PD_Status."'</script>";

 }
        else if($l_UR_Type=='M')
        {   

              // Set the Mentor Feedback for Teams
              $l_sql = 'update Project_Documents set PD_MFeedback = "'.$l_PD_Feedback.'", PD_MFeedbackDate = '.$l_PD_FeedbackDate.',';
              if($_POST['l_TMPR_Type']== 'N'):
              $l_sql .= 'PD_Status = "'.$l_PD_Status.'",';
              endif;
              $l_sql .= 'PD_MRating = "'.$l_PD_Rating.'" where PD_id = '.$l_PD_id.'';
               mysql_query($l_sql);
    //header('Location:EmailNotifications.php?g_query=Mentor|'.$l_TM_id.'|'.$l_PD_id.'|'.$l_PD_Feedback.'');
    echo "<script>window.location.href='EmailNotifications.php?g_query=Mentor|".$l_TM_id."|".$l_PD_id."|".$l_PD_Feedback."'</script>";


        }
        

        
        
    }
    else 
{
        $l_sql=$_REQUEST['g_query'];
        $l_sql=str_replace("","",$l_sql);
        $l_arry = explode("|",$l_sql);
        $l_PD_id = $l_arry[0];
        $l_TM_id = $l_arry[1];
  

        //to check if the authorised person is giving feedback
        $l_Teamcheck_query = 'select TM.TM_id  from Teams as TM, Project_Documents as PD where TM.TM_id='.$l_TM_id.' and PD.PD_id='.$l_PD_id.' and PD.TM_id=TM.TM_id and (UR_id_Guide ="'.$l_UR_id.'" or UR_id_Mentor="'.$l_UR_id.'")';

        $l_Teamcheck_result = mysql_query($l_Teamcheck_query);
        
        $l_Teamcheck_count = mysql_num_rows($l_Teamcheck_result);
        if($l_Teamcheck_count==0)
        {
               if($l_UR_Type=='G')
               {
                    $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You are not the authorised person to give this feedback.")
        window.location.href="GHome.php"; </script> ';

                    print($l_alert_statement );
                }
                else if($l_UR_Type=='M')
               {
                    $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You are not the authorised person to give this feedback.")
        window.location.href="MHome.php"; </script> ';

                    print($l_alert_statement );
                }
        }


}
// Select the Documents Name from Project Documents
$l_PDname_Query = 'select PD_Name  from Project_Documents where  PD_id='.$l_PD_id;
 $l_PDname_result = mysql_query($l_PDname_Query);
$l_PDName_row = mysql_fetch_row($l_PDname_result);
$l_PDName =$l_PDName_row[0];

$l_TMname_Query = 'select TM_Name ,TM_PR_Type from Teams where TM_id='.$l_TM_id ;
 $l_TMname_result = mysql_query($l_TMname_Query);
$l_TMName_row = mysql_fetch_row($l_TMname_result);
$l_TMName = $l_TMName_row[0];
$l_TMPR_Type = $l_TMName_row[1];
?>
    <div class="panel panel-success">
        <div class="panel-heading"><h4>You are giving feedback to <?php echo $l_PDName.' from '.$l_TMName; ?></h4></div>
         <div class="panel-body table-responsive table">
    <?php

print('<form class="" action="" method="POST">');   
print('<table class="ady-table-content" border=1 style="width:100%" >');
print ('<tr>');
print ('<th  font-size:18px; font-weight:bold; text-align:center" colspan=2>FeedBack</th>');
print ('</tr>');
print ('<tr><td>Rating</td><td><select class="form-control" name="l_rating_sel" >');
            
        print ('<option value="1">1</option>' );
        print ('<option value="2">2</option>' );
        print ('<option value="3">3</option>' );
        print ('<option value="4">4</option>' );
        print ('<option value="5">5</option>' );
        
        print('</select> </td></tr>');  
        if($l_UR_Type=='G' || $l_TMPR_Type == 'N')
        {
             print ('<tr><td>Status</td><td><select  class="form-control" name="l_status_sel" align="left">');

             print ('<option value="A">Accept</option>' );
             print ('<option value="R">Reject</option>' );

             print('</select> </td></tr>');
        }
        print('<tr><td>Feedback:</td><td><textarea class="form-control"  name="l_feedback"></textarea></td></tr>');
         print('<input type="hidden" name=l_TMPR_Type  value="'.$l_TMPR_Type.'" >');
        print('<input type="hidden" name=l_PD_id  value="'.$l_PD_id.'" >');
        print('<input type="hidden" name=l_TM_id  value="'.$l_TM_id.'" >');


      print ('<tr>');
    print ('<td colspan="2" style="text-align:center"> <input type="submit" class="btn btn-primary"name=submit  value="Submit Feedback" style="font-weight:bold;"></td>');
    print ('</tr>');
    print('</table>');

}
?>
        </div>
    </div>
                 <div class="col-md-3" ></div>
                     
             </div>
 </div>   
</div>
<?php include('footer.php')?>