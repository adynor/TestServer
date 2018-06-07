  <meta name="viewport" content="width=device-width, initial-scale=1">      
	  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>           
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
 <?php 

// Create connection

$conn=  mysql_connect('localhost','zairepro_test','test@123');
$db=mysql_select_db("zairepro_projectory_test",$conn);

$student_ids= array('tests4','tests5');
print_r($student_ids);

foreach ($student_ids as $userid)
{
    echo $sql='select PR_id,TM_id from Users Where UR_id="'.$userid.'"';
    $result=  mysql_fetch_row(mysql_query($sql));
    $PR_id=$result[0];
    $TM_id=$result[1];
echo "<br>";
    echo $sql_users="Update Users set TM_id=NULL,PR_id=NULL,UR_PR_Type=NULL where UR_id='".$userid."'"; 
    $sucessUR=mysql_query($sql_users);

    echo "<br>";
    echo $sql_Teams="DELETE FROM Teams WHERE TM_id='".$TM_id."' AND PR_id='".$PR_id."'";
    $sucessTM=mysql_query($sql_Teams);
  echo "<br>";

    echo $sql_Project_Doc="DELETE FROM Project_Documents WHERE TM_id='".$TM_id."' AND PR_id='".$PR_id."'";
    $sucessPD=mysql_query($sql_Project_Doc); 
    
    $PRdoc_Seen='DELETE FROM PRdoc_Seen Where UR_Id="'.$userid.'"';
    $sucessPRdoc_Seen =mysql_query($PRdoc_Seen);
     
    
  
  echo "<br>";
     // check for guide request 
    $Check_queryGid="select UR_id from Guide_Requests where TM_id='".$TM_id."'";
    $rungid=  mysql_fetch_row(mysql_query($Check_queryGid));
    
    $checkNoofguidereq='select GR_ResponseDateTime from Guide_Requests where UR_id="'.$rungid[0].'"';
    $countofguidereq=  mysql_num_rows(mysql_query($checkNoofguidereq));
  
    if($countofguidereq==1){
    echo $sql_Guide_r ="update Users Set TM_id=NULL WHERE UR_id='".$rungid[0]."'";
    $sucesGR=mysql_query($sql_Guide_req);    
   }
   $sql_Guide_req ="DELETE FROM Guide_Requests WHERE TM_id='".$TM_id."'";
    $sucessGR=mysql_query($sql_Guide_req);
    // check for guide request 
    
    // check for Mentor request 
    $Check_queryMid="select UR_id from Mentor_Requests where TM_id='".$TM_id."'";
    $runmid=  mysql_query($Check_queryMid);
    
     $checkNoofmentorreq='select MR_ResponseDateTime from Mentor_Requests where UR_id="'.$runmid[0].'"';
    $countofmentorreq=  mysql_num_rows(mysql_query($checkNoofmentorreq));
  
    if($countofmentorreq==1){
    echo $sql_mentor_r ="update Users Set TM_id=NULL WHERE UR_id='".$runmid[0]."'";
    $sucesMR=mysql_query($sql_mentor_r);    
   }
    
    $sql_Mentor_req ="DELETE FROM Mentor_Requests WHERE TM_id='".$TM_id."'";
    $sucessMR=mysql_query($sql_Mentor_req);
  
    // check for Mentor request 
    
   echo "<br>";
   echo  $sql_Teams_req ="DELETE FROM Teammate_Request WHERE UR_Sender='".$userid."' or UR_Receiver='".$userid."'";
    $sucessTR=mysql_query($sql_Teams_req);  
    echo "<br>";
    echo $sql_Teams_req ="DELETE FROM Project_Applications WHERE UR_id='".$userid."' and PR_id='".$PR_id."' and TM_id='".$TM_id."'";
    $sucessPA=mysql_query($sql_Teams_req);
    
    
}   
    if($sucessUR){
    echo "<script>Materialize.toast('Users Updated Sucessfully', 4000, 'rounded')</script>";
    }
    if($sucessTM){
    echo "<script>Materialize.toast('Teams Cleared Sucessfully', 4000, 'rounded')</script>";
    }
    if($sucessPD){
    echo "<script>Materialize.toast('Project Documents Cleared Sucessfully', 4000, 'rounded')</script>";
    }
    if($sucessMR){
    echo "<script>Materialize.toast('Mentor Requesr Cleared Sucessfully', 4000, 'rounded')</script>";
    }
    if($sucessGR){
    echo "<script>Materialize.toast('Guide Request Cleared Sucessfully', 4000, 'rounded')</script>";
    }
     if($sucessTR){
    echo "<script>Materialize.toast('TeamMate Request Cleared Sucessfully', 4000, 'rounded')</script>";
    }
    if($sucessPA){
    echo "<script>Materialize.toast('Project Application Cleared Sucessfully', 4000, 'rounded')</script>";
    }
    

?>

  
      
   