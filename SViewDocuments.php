<?php
   
 include ('db_config.php');
 include ('header.php');
    ?>

<div class="row" style="padding:20px"></div>
   <div class="container" >
       <div class="row" style="padding:20px 0px">
           <div class="col-md-12  ady-row">
<?php 

    $l_UR_id    = $_SESSION['g_UR_id'];
    $l_UR_Type =  $_SESSION['g_UR_Type'];
    $l_TM_id =$_SESSION['g_TM_id'];
    
   print('<div style="clear:left">');
   
   if($_REQUEST['flag'] && $_REQUEST['pd_id']){
       
      $PD_Status=$_REQUEST['flag'];
      $PD_id=$_REQUEST['pd_id'];
       
    $updatenotification='update PRdoc_Seen set PD_Seen="S" where PD_id='.$PD_id.' and UR_Id="'.$l_UR_id.'" and PD_TM_id='. $l_TM_id.'';
       mysql_query($updatenotification);
   }
   
    
if(is_null($l_UR_id) || $l_UR_Type!='S')
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a student. Please login correctly")
        window.location.href="Signout.php"; </script> ';
        
        print($l_alert_statement );
    }else if(empty($l_TM_id)){
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("Your team is not formed yet. Please form a team.")
        window.location.href="SHome.php"; </script> ';
        
        print($l_alert_statement );
        
    } else
    // count the number of documents submited
    $l_count_check       = "select count(*) from Project_Documents  as PD where PD.TM_id = ".$l_TM_id." and PD.Org_id = '".$_SESSION['g_Org_id']."'";
    $l_result_count   = mysql_query($l_count_check) or die(mysql_error());
    $l_count_result      = mysql_fetch_row($l_result_count);
    $l_count = $l_count_result[0];
    
    $l_sql_MentorSet="select UR_id_Mentor from Teams where TM_id=".$l_TM_id."";
    $Run_Mysql=  mysql_query($l_sql_MentorSet);
    $resultSet=  mysql_fetch_row($Run_Mysql);
   $mentorset=$resultSet[0];
   
    
    print('<br><br><br><table border=1 style="width:100%; border:1px solid black;" class="ady-table-content"');
    print ('<tr>');
    print ('<th>Project  Name </th>');
    print ('<th> Project  Link </th>');
    print ('<th> Description</th>');
    if($l_UR_PR_Type == 'C'){
    print ('<th>  Status</th>');
    print ('<th>Guide Rating</th>');
    print ('<th>Guide Feedback</th>');
    }
    if($mentorset!=""){
    print ('<th> Mentor Rating</th>');
    print ('<th>Mentor Feedback</th>');
    }
    print ('</tr>');
    
    // if document is submited by the student then show the details
    if($l_count > 0)
    {
        // get all the details about the document submited by students
        $l_proj_query = 'select PD.PD_id, PD.PD_Name, PD.PD_SubmissionDate, PD.PD_Status, AL.AL_Desc, PD.PD_Rating, PD.PD_Feedback,PD.PD_MRating,PD.PD_MFeedback from Project_Documents as PD , Access_Level as AL where AL.AL_id = PD.AL_id and PD.TM_id = '.$l_TM_id.' and PD.Org_id = "'.$_SESSION['g_Org_id'].'" ' ;
        $l_proj_res = mysql_query($l_proj_query);
        while($l_row = mysql_fetch_row($l_proj_res))
        {
            $l_PD_id            =$l_row[0];
            $l_PD_Name           =$l_row[1];
            $l_PD_SubmissionDate =$l_row[2];
            $l_PD_Status         =$l_row[3];
            $l_AL_Desc           =$l_row[4];
            $l_PD_Rating         =$l_row[5];
            $l_PD_Feedback       =$l_row[6];
            $l_PD_MRating        =$l_row[7];
            $l_PD_MFeedback      =$l_row[8];
            
            
            print ('<tr style="' . $l_UR_Color_Table03. '">');
            
            // id documents is accepted
            if ($l_PD_Status == 'A')
            {
                print( '<td><font color=009933>' .$l_PD_Name. '</font></td>');
                echo "<td><a  class='btn btn-primary' href='blob_download.php?pdid=".$l_PD_id."'>Download </a></td> ";
               
                print( '<td style="width:50px"><font color=009933>'  .$l_AL_Desc. '</font></td>');
                 if($l_UR_PR_Type == 'C'){
                print( '<td><font color=009933> Accepted </font></td>');
                print( '<td><font color=009933>'  .$l_PD_Rating. '</font></td>');
                print( '<td><font color=009933>' .$l_PD_Feedback. '</font></td>');
                 }
               
               if($mentorset!=""){
                if($l_PD_MFeedback==null )
                {
                    print( '<td>Pending</td>');
                    print( '<td>Pending</td>');
                    
                }
                else
                {
                    print( '<td><font color=009933>'  .$l_PD_MRating. '</font></td>');
                    print( '<td><font color=009933>' .$l_PD_MFeedback. '</font></td>');
                }
           
           }
            }
            // if document is rejected
            if ($l_PD_Status == 'R')
            {
                print( '<td><font color=#A00000 >' .$l_PD_Name. '</font></td>');
                echo "<td><a  class='btn btn-primary' href='blob_download.php?pdid=".$l_PD_id."'>Download </a></td>";
                print( '<td style="width:50px"><font color=#A00000 >'  .$l_AL_Desc. '</font></td>');
                if($l_UR_PR_Type == 'C'){
                print( '<td> <font color=#A00000 >Rejected </font></td>');
                print( '<td><font color=#A00000 >'  .$l_PD_Rating. '</font></td>');
                print( '<td><font color=#A00000 >' .$l_PD_Feedback. '</font></td>');
                }
                
                if($mentorset!=""){
                if($l_PD_MFeedback==null )
                {
                    print( '<td>Pending</td>');
                    print( '<td>Pending</td>');
                    
                }
                else
                {
                    print( '<td><font color=#A00000 >' .$l_PD_MRating.'</font></td>');
                    print( '<td><font color=#A00000 >'.$l_PD_MFeedback.'</font></td>');
                }
                }
            }
            
            // if response from guide or mentor is still pending
            else if($l_PD_Status == 'P')
            {
                
                print( '<td>' .$l_PD_Name. '</td>');
                echo "<td><a  class='btn btn-primary' href='blob_download.php?pdid=".$l_PD_id."'>Download </a></td> ";
                print( '<td style="width:50px">'  .$l_AL_Desc. '</td>');
                
                if($l_UR_PR_Type == 'C'){
                print( '<td> Pending </td>');
                print( '<td> Pending </td>');
                print( '<td> Pending </td>');
                }
                if($mentorset!=""){
                print( '<td> Pending </td>');
                print( '<td> Pending </td>');
                }
                
            }
            
            print('</tr>');
        }
        
        
        
    }
    else
    {
        print ('<tr style="">');
        print( '<td colspan="8"> There are no documents to be shown </td>');
        print('</tr>');
        
    }
    
    
    print('</table><br>');
    print('</div>');
    ?>
                       </div>
       </div>
   </div>
<?php include('footer.php')?>