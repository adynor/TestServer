  <?php 
 include('header.php');
 include('db_config.php');

 function getProjectName(){
 $psql="select PR_Name FROM Projects Where PR_id='".$_SESSION['g_PR_id']."'";
 $pexe=mysql_query($psql);
 $pres=mysql_fetch_array($pexe);
 return $pres[0];
 }
 getMentorRequest();
 $_SESSION['mentor'];
 //print_r($_SESSION);
$_SESSION['l_User_PR_Type']=$_SESSION['g_UR_PR_Type'];
$_SESSION['g_PR_Name_pay']=getProjectName();
//print_r($_SESSION);
$l_PR_id=$_SESSION['g_PR_id'];
$timezone = new DateTimeZone("Asia/Kolkata" );
$date = new DateTime();
$date->setTimezone($timezone );
$currentTime = $date->format('YmdHi');
$team=$_SESSION['g_TM_id'];

 function setMentorhr($mentor,$team,$ctime,$hours,$org){
 $msql="INSERT INTO Mentor_Requests(UR_id,TM_id,MR_SentDateTime,MR_Hours,Org_id) values('$mentor','$team','$ctime','$hours','$org')";
 mysql_query($msql);
 }
 function getMentorRequest(){
 /*$Msql="SELECT TM.UR_id_Mentor FROM Teams AS TM WHERE TM.TM_id=".$_SESSION['g_TM_id']."";
 $mres=mysql_query($Msql);
 $mRow=mysql_fetch_row($mres);
	 if($mRow[0] == NULL){
	$MRsql="SELECT UR_id FROM Mentor_Requests WHERE  TM_id=".$_SESSION['g_TM_id']." AND MR_ResponseDateTime is NULL ORDER BY MR_SentDateTime DESC LIMIT 0,1";
	
	 $MRres=mysql_query($MRsql);
	  $MRrow=mysql_fetch_row($MRres);
	 $MRcount=mysql_num_rows($MRres);
	 
	 }else{
	  $MRcount=-1;
	  $MRrow[0]=$mRow[0];
	 }
	 switch($MRcount){
	 case 0:
	    return array(0=>"Failure",1=>$MRrow[0]);
	     break;
	 case -1:
	    return array(0=>"Success",1=>$MRrow[0]);
	     break;
	 default:
	   return array(0=>"Pending",1=>$MRrow[0]);
	    break;
	     }*/
	     $MRsql="SELECT MR.UR_id,MR.MR_STATUS,MR.MR_Hours FROM Mentor_Requests as MR WHERE  MR.TM_id=".$_SESSION['g_TM_id']."  ORDER BY MR_SentDateTime DESC LIMIT 0,1";
	 $MRres=mysql_query($MRsql);
	 $MRrow=mysql_fetch_row($MRres);
	 $MRcount=mysql_num_rows($MRres);
	 
	  switch($MRrow[1]){
	 case P:
         $_SESSION['mentor']=$MRrow[0];
         if(empty($_POST['mhr'])){
	 $_POST['mhr']=$MRrow[2];
	 }
	  return array(0=>"Pending",1=>$MRrow[0],);
	     break;
	 case A:
	 if(empty($_POST['mhr'])){
	 $_POST['mhr']=$MRrow[2];
	 }
	 $_SESSION['mentor']=$MRrow[0];
	    return array(0=>"Success",1=>$MRrow[0]);
	     break;
	 default:
	        return array(0=>"Failure",1=>NULL);
	    break;
	     }
	     
	     
 }
 switch($_REQUEST['func']) {
 case 1:
 setMentorhr($_POST['user'],$team,$currentTime,$_POST['hr'],$_POST['org']);
  break;
  
 default :
 ?>
<div class="row" style="padding:10px"></div>
<div class="container" >
<?php function hoursInput(){
    $return= 'Ask For Mentor Hours <br>';
    $return.= '<form method="POST" action="';
    $return.= htmlentities($_SERVER['PHP_SELF']);
    $return.='" >';
    
    $return.= '<input type="number" name="mhr" value="'.$_POST[mhr].'" >';
    $return.= ' <input type="submit" name="savemhr" value="Go"> </form>';
        return  $return;
       }
       echo hoursInput();
        ?>
        
        <?php if($_POST['mhr']!=""){
	         $l_sql_mentors=mysql_query('SELECT DISTINCT (US.UR_id), CONCAT( UR.UR_FirstName," ", UR.UR_MiddleName," ", UR.UR_LastName ) UR_FullName, CONCAT( UR.UR_Emailid,"@", UR.UR_EmailidDomain ) UR_Email,UR.UR_Phno,UR.Org_id FROM UR_Subdomains AS US, Project_SubDomains AS PSD, Users AS UR WHERE US.UR_id = UR.UR_id AND UR.UR_Type="M" AND PSD.PR_id =37 AND PSD.SD_id = US.SD_id AND PSD.SD_Preference =  "R"');
	         ?>
	          <table id="" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Select Mentor</th>
                
            </tr>
        </thead>
        <tbody>
	         <?php
	         $_SESSION['payment']=$_POST['mhr']*1000;
	         $_SESSION['g_PR_id_pay']=$_SESSION['g_PR_id'];
         while($rows=mysql_fetch_row($l_sql_mentors)){
         //print_r($rows);
         ?>

      
        
            <tr>
                <td><?php echo $rows[1]; ?></td>
                <td><?php echo $rows[2]; ?></td>
                <td><?php echo $rows[3]; ?></td>
                
                <td>
                <?php $mstatus=getMentorRequest();
             // print_r($mstatus);
                switch($mstatus[0]){
                case Success:
               // echo "S";
                if($rows[0] == $mstatus[1]){ ?>
                    <!--<a class="btn btn-success" data-org="<?php echo $rows[4];?>" data-user="<?php echo $rows[0];?>" data-hr="<?php echo $_POST['mhr'];?>" disabled="disabled"> Add Mentor</a>-->
                   <a class="btn btn-success"  href="payment_gateway/extrapayment.php" > Continue</a>
                   <?php //echo hoursInput();?>
               <?php } else{ ?>
                     <a class="btn btn-primary " data-org="<?php echo $rows[4];?>" data-user="<?php echo $rows[0];?>" data-hr="<?php echo $_POST['mhr'];?>" disabled="disabled"> Add Mentor</a>
                <?php }
                break;
                 case Failure: ?>
               
                     <a class="btn btn-primary mentor-hr" data-org="<?php echo $rows[4];?>" data-user="<?php echo $rows[0];?>" data-hr="<?php echo $_POST['mhr'];?>"> Add Mentor</a>
                <?php
                break;
                case Pending:
             //echo "p";
                if($rows[0] == $mstatus[1]){ ?>
                    <a class="btn btn-warning" data-org="<?php echo $rows[4];?>" data-user="<?php echo $rows[0];?>" data-hr="<?php echo $_POST['mhr'];?>" disabled="disabled"> Add Mentor</a>
               <?php } else{ ?>
                     <a class="btn btn-primary " data-org="<?php echo $rows[4];?>" data-user="<?php echo $rows[0];?>" data-hr="<?php echo $_POST['mhr'];?>" disabled="disabled"> Add Mentor</a>
                <?php }
                break;
                default:
                ?>
                <a class="btn btn-primary mentor-hr" data-org="<?php echo $rows[4];?>" data-user="<?php echo $rows[0];?>" data-hr="<?php echo $_POST['mhr'];?>"> Add Mentor</a>
                <?php
                }
                ?>
                </td>
               
            </tr>
       
         <?php
         }?>
          </tbody>
    </table>
         <?php
        }
        ?>
</div>
<script>
$(function(){
    $(".mentor-hr").on("click",function(){
        $(this).removeClass("btn-primary");
        $(this).addClass('btn-warning');
        var userid=$(this).data('user');
        var hours=$(this).data('hr');
        var org=$(this).data('org');
        $(".mentor-hr").attr('disabled', true);
        $(".mentor-hr").unbind( "click");
        $(".mentor-hr").removeClass("mentor-hr");       
   $.ajax({
    type:'POST',
    url :'PaymentMentorhr.php?func=1',
    data:{user:userid,hr:hours,org:org},
    success:function(data){
     //alert();
    // console.log(data);
    }
 });
    });
});

</script>
<?php }
 include('footer.php')?>