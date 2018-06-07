<?php
    //////////////////////////////////////////////
    // Name            :GMview_Team_ProjFiles02
    // Project         :Projectory
    // Purpose         :Guide or Mentor Should Observe The Team's Documents with Details
    // Called By       :GMTeamdocs
    // Calls           :GMTeamdocs
    // Mod history:
    //////////////////////////////////////////////
 include ('header.php');
 include ('db_config.php'); 
 ?>
<div class="container" >
<?php
$l_UR_id = $_SESSION['g_UR_id'];  // For the Communications table we need the from id
$l_UR_Type= $_SESSION['g_UR_Type'];

$l_sql=$_REQUEST['g_query'];
$l_sql=str_replace("\\","",$l_sql);
$l_arry = explode("|",$l_sql);
$l_TM_id= $l_arry[0];
$l_TM_Name= $l_arry[1];
//Check THE userid is empty
//Check the User login as a Guide Or Mentor
if(is_null($l_UR_id) || ($l_UR_Type!='G' && $l_UR_Type!='M'))
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a guide. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login.php"; </script> ';
                print($l_alert_statement );
}

else
{
// check logged in person is authorised or not

$l_check_query='select TM_id,UR_id_Mentor,UR_id_Guide from Teams as TM where TM.TM_id='.$l_TM_id.' and (TM.UR_id_Guide="'.$l_UR_id.'" or TM.UR_id_Mentor="'.$l_UR_id.'")';
$l_check_res=mysql_query($l_check_query);
$l_row_count=mysql_num_rows($l_check_res);
$GuideMentorSet=mysql_fetch_row($l_check_res);

    
if($l_row_count==0)
{
 $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You are not authorised person. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login.php"; </script> ';
print($l_alert_statement );
}

// check ends here
?>

<div class="panel panel-success">
        <div class="panel-heading"><h4>You are viewing the documents of <?php echo $l_TM_Name; ?></h4></div>
         <div class="panel-body table-responsive table">
 <?php
//Display the list of documents  wthe details
$l_proj_query = 'select   PD.PD_Name, PD.PD_SubmissionDate, AL.AL_Desc, TM.TM_Name, PD.PD_id, PD.PD_Status, PD.PD_Feedback, PD.PD_FeedbackDate, PD.PD_Rating, PD.PD_MFeedback, PD.PD_MFeedbackDate, PD.PD_MRating from Teams as TM, Project_Documents as PD , Access_Level as AL where (TM.UR_id_Guide = "'.$l_UR_id.'" or TM.UR_id_Mentor = "'.$l_UR_id.'") and TM.TM_id = '.$l_TM_id.' and TM.TM_id = PD.TM_id and AL.AL_id = PD.AL_id' ;

$l_proj_res = mysql_query($l_proj_query) or die(mysql_error());
$l_count = mysql_num_rows($l_proj_res);
if($l_count > 0)
{

print('<table class="ady-table-content" border=1 style="width:100%" >');
print ('<tr>');
print ('<th >Document  Name</th>');
print ('<th > Document  type </th>');
print ('<th >Submission Date</th>');
if($l_UR_Type == 'G')
{
     print ('<th >Status</th>');
     print ('<th >Your Feedback</th>');
     print ('<th >Your Feedback Date</th>');
     print ('<th >Your Ranking</th>');
     if($GuideMentorSet[1]!="")
     {
     print ('<th >Mentor Feedback</th>');
     print ('<th >Mentor Feedback Date</th>');
     print ('<th >Mentor Ranking</th>');
     }
}
else
{
     print ('<th >Status</th>');
     if($GuideMentorSet[2]!="")
     {
     print ('<th >Guide Feedback</th>');
     print ('<th >Guide Feedback Date</th>');
     print ('<th >Guide Ranking</th>');
     }
     print ('<th >Your Feedback</th>');
     print ('<th >Your Feedback Date</th>');
     print ('<th >Your Ranking</th>');
}
print ('<th >View/Download</th>');

print ('</tr>');
print ('<tr>');

print ('</tr>');

       while($l_proj_row = mysql_fetch_row($l_proj_res))
       {

           print ('<tr>');
            $l_PD_Name                    =$l_proj_row[0] ;
            $l_PD_SubmissionDate   =$l_proj_row[1];
            $l_AL_Desc                       =  $l_proj_row[2];
            $l_TM_Name                    = $l_proj_row[3];
            $l_PD_id                            = $l_proj_row[4];
            $l_PD_Status                    = $l_proj_row[5];
            $l_PD_Feedback                = $l_proj_row[6];
            $l_PD_FeedbackDate        = $l_proj_row[7];
            $l_PD_Rating                   = $l_proj_row[8];
            $l_PD_MFeedback                = $l_proj_row[9];
            $l_PD_MFeedbackDate        = $l_proj_row[10];
            $l_PD_MRating                   = $l_proj_row[11];

            
            print( '<td>' .$l_PD_Name . '</td>');                   // PD_Name
            print( '<td>' . $l_AL_Desc. '</td>');  	// AL_Desc
            $l_PD_SubmissionDate= date("d-M-Y", strtotime($l_PD_SubmissionDate));
            print( '<td>' . $l_PD_SubmissionDate.'</td>');                   // PD_SubmissionDate
//Check the Document Status is Accept or Reject or Pending
            if ($l_PD_Status =='A')
            {
                $l_PD_StatusA='Accepted';
                print( '<td>' . $l_PD_StatusA.'</td>');
               
               $l_len = strlen($l_PD_FeedbackDate)  - 4;
              $l_PD_FeedbackDate_str= substr($l_PD_FeedbackDate, 0, $l_len ) ;
              $l_PD_FeedbackDate =$l_PD_FeedbackDate_str;

              $l_PD_FeedbackDate= date("d-M-Y", strtotime($l_PD_FeedbackDate));
 if($GuideMentorSet[2]!="")
              {
              print( '<td style ="width:140px">' . $l_PD_Feedback.'</td>');            
              print( '<td>' . $l_PD_FeedbackDate.'</td>'); 
              print( '<td style="text-align:center">' . $l_PD_Rating.'</td>');
            
             }
              //chek the feedback is  null or not
              if($l_PD_MFeedbackDate==NULL && $GuideMentorSet[1]!="")
              {
                    print( '<td style ="width:140px">Pending</td>');            
                    print( '<td>Pending</td>'); 
                    print( '<td style="text-align:center">Pending</td>');
              }
              else
              {
                    $l_Mlen = strlen($l_PD_MFeedbackDate)  - 4;
                  $l_PD_MFeedbackDate_str= substr($l_PD_MFeedbackDate, 0, $l_Mlen ) ;
                   $l_PD_MFeedbackDateStr =$l_PD_MFeedbackDate_str;
                        $l_PD_MFeedbackDate= date("d-M-Y", strtotime($l_PD_MFeedbackDateStr));
                if($GuideMentorSet[1]!=""){    
                    print( '<td style ="width:140px">' . $l_PD_MFeedback.'</td>');            
                    print( '<td>' . $l_PD_MFeedbackDate.'</td>'); 
                    print( '<td style="text-align:center">' . $l_PD_MRating.'</td>');
                    }
              }

            }

            else if($l_PD_Status =='R')
            {
                    $l_PD_StatusR='Rejected' ;
                    print( '<td>' . $l_PD_StatusR.'</td>');
                   $l_len = strlen($l_PD_FeedbackDate)  - 4;
              $l_PD_FeedbackDate_str= substr($l_PD_FeedbackDate, 0, $l_len ) ;
              $l_PD_FeedbackDate =$l_PD_FeedbackDate_str;

              $l_PD_FeedbackDate= date("d-M-Y", strtotime($l_PD_FeedbackDate));

             if($GuideMentorSet[2]!="")
              {
              print( '<td style ="width:140px">' . $l_PD_Feedback.'</td>');            
              print( '<td>' . $l_PD_FeedbackDate.'</td>'); 
              print( '<td style="text-align:center">' . $l_PD_Rating.'</td>');
              }
              
              
              //chek the feedback is  null or not
              if($l_PD_MFeedbackDate==NULL && $GuideMentorSet[1]!="")
              {
                    print( '<td style ="width:140px">Pending</td>');            
                    print( '<td>Pending</td>'); 
                    print( '<td style="text-align:center">Pending</td>');
              }
              else
              {
                    $l_Mlen = strlen($l_PD_MFeedbackDate)  - 4;
                  $l_PD_MFeedbackDate_str= substr($l_PD_MFeedbackDate, 0, $l_Mlen ) ;
                   $l_PD_MFeedbackDateStr =$l_PD_MFeedbackDate_str;
                        $l_PD_MFeedbackDate= date("d-M-Y", strtotime($l_PD_MFeedbackDateStr));
               if($GuideMentorSet[1]!=""){          
                    print( '<td style ="width:140px">' . $l_PD_MFeedback.'</td>');            
                    print( '<td>' . $l_PD_MFeedbackDate.'</td>'); 
                    print( '<td style="text-align:center">' . $l_PD_MRating.'</td>');
               }
              }
            }

            else if($l_PD_Status =='P')
            {
                    $l_PD_StatusR='Pending' ;
                    print( '<td>' . $l_PD_StatusR.'</td>');

              print( '<td style ="width:140px">Pending</td>');            
              print( '<td>Pending</td>'); 
              print( '<td style="text-align:center">Pending</td>');
              //chek the feedback is  null or not
              if($GuideMentorSet[1]!="")
              {
                    print( '<td style ="width:140px">Pending</td>');            
                    print( '<td>Pending</td>'); 
                    print( '<td style="text-align:center">Pending</td>');
              }
              else
              {
                    $l_Mlen = strlen($l_PD_MFeedbackDate)  - 4;
                  $l_PD_MFeedbackDate_str= substr($l_PD_MFeedbackDate, 0, $l_Mlen ) ;
                   $l_PD_MFeedbackDateStr =$l_PD_MFeedbackDate_str;
                        $l_PD_MFeedbackDate= date("d-M-Y", strtotime($l_PD_MFeedbackDateStr));
                         if($GuideMentorSet[1]!="")
                             { 
                    print( '<td style ="width:140px">' . $l_PD_MFeedback.'</td>');            
                    print( '<td>' . $l_PD_MFeedbackDate.'</td>'); 
                    print( '<td style="text-align:center">' . $l_PD_MRating.'</td>');
                         }
              }
            }
            
           
            print ( '<td style="text-align:center"> <a type="button"  class="btn btn-primary" href="'.$l_filehomepath.'/blob_download.php?pdid='.$l_PD_id.'"> Download</a> ');
            print ( "</td>");

            print('</tr>');
        }
          print('</table>');
}
else
    {
    print ('<table class="ady-table-content" border=1 style="width:100%" >');
print ('<tr>');
print ('<th >Document  Name</th>');
print ('<th > Document  type </th>');
print ('<th >Team</th>');
print ('<th >Submission Date</th>');
print ('<th >Status</th>');
print ('<th >Feedback</th>');
print ('<th >Feedback Date</th>');
print ('<th >Ranking</th>');
print ('<th >View/Download</th>');

print ('</tr>');
    print ('<tr>');
    print ('<td colspan = "9" font-weight:bold; text-align:center"> There are no documents that you have given feedbacks to.</td>');
    print ('</tr>');
    print ( '</table>');
}


}
?>
</div>
</div>
<?php include('footer.php')?>