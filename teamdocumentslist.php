<?php
session_start();
error_reporting(0);
include('db_config.php');
$_POST = json_decode(file_get_contents('php://input'), true);

$itid=$_SESSION['g_IT_id'];
$l_TM_id=$_POST['tmid']; 
$l_PR_id=$_POST['prid'];
?>

<div class="bar"></div>
<div class="timeline">
    <?php 
     
     $l_PS_Seq_sql  ='select max(PS.PS_Seq_No) AS myMAX from  ProjectDocument_Sequence as PS where  PS.PR_id='.$l_PR_id.'';
     $docqueryrun=mysql_query($l_PS_Seq_sql);
     $l_PS_Seq_res = mysql_fetch_row($docqueryrun);
       
        $l_Max_PS_id = max($l_PS_Seq_res);
        $l_Max_PS_id = $l_Max_PS_id + 1;
        $i=0;
        for($inc = 1; $inc <= $l_Max_PS_id; $inc++)
        {
            // get Access level names in the list according to sequence number
            $l_Seq_sql = 'select AL.AL_Desc,AL.AL_id from ProjectDocument_Sequence as PS,Access_Level as AL where PS.PR_id="'.$l_PR_id.'" and PS.PS_Seq_No="'.$inc.'" and PS.AL_id=AL.AL_id';
            $l_res = mysql_query($l_Seq_sql) or die(mysql_error());
          
           
           
           if($l_data = mysql_fetch_row($l_res))
            { 
            $pdquery="select PD_Status from Project_Documents as PD where PD.AL_id=".$l_data[1]." and PD.TM_id='".$l_TM_id."' and PD.PR_id=".$l_PR_id." ORDER BY PD_id DESC LIMIT 0,1";
            $pdrun=mysql_fetch_row(mysql_query($pdquery));
            if($pdrun[0]=='A'){$bg='background: #14a76c'; 
            
            }else if($pdrun[0]=='R'){ 
               
                $bg='background:rgba(19, 18, 18, 0.63)'; }
                else if($pdrun[0]=='P'){
               
                $bg='background: rgba(255, 99, 71, 0.85) ';}else { 
                
            
            $bg="" ; 
            
            $i++;
           }
          
       
            ?> 
  <div class="entry" style="<?php echo $bg;?>">
      <?php if($pdrun[0]=='A')
      {  ?> <span class="glyphicon glyphicon glyphicon-ok-sign"></span>   <?php 
      }
      else if($pdrun[0]=='P')
      {$j=7;?>
      <span class="user_tip_marker"><span class="blink"> </span><span class="inner_blink"></span></span> 
      <?php 
      }
      else if($i==1 && $pdrun[0]!='P' && $pdrun[0]!='A' && $j!=7)
      {?>
      <span class="user_tip_marker"><span class="blink"> </span><span class="inner_blink"></span></span> 
      <?php 
      }
      
       echo $l_data[0]; ?>
        
    </div> 
 
           <?php
           }
        }
        
   
    
 ?>
</div>



















<?php

/*
//$l_proj_query = 'select PD.PD_Name, PD.PD_SubmissionDate, AL.AL_Desc, TM.TM_Name, PD.PD_id, PD.PD_Status, PD.PD_Feedback, PD.PD_FeedbackDate, PD.PD_Rating, PD.PD_MFeedback, PD.PD_MFeedbackDate, PD.PD_MRating,TM.TM_StartDate from Teams as TM, Project_Documents as PD , Access_Level as AL where TM.TM_id = '.$l_TM_id.' and TM.TM_id = PD.TM_id and AL.AL_id = PD.AL_id' ;

$l_proj_res = mysql_query($l_proj_query) or die(mysql_error());
$l_count = mysql_num_rows($l_proj_res);
if($l_count>0)
{ 
$run=mysql_query($l_proj_query);

$data = array();
while ($row = mysql_fetch_assoc($run)) {

$data[] = $row;
    }
}
 print json_encode($data);
 */

?>



