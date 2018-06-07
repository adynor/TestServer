 <?php 
 include('header.php');
 include('db_config.php');

?>
 <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="dist/css/bootstrap-imageupload.css" rel="stylesheet">
<style>

.circle {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  font-size: 10px;
  color: #fff;
  line-height: 50px;
  text-align: center;
      border: 2px solid rgb(226, 249, 4);
  float: left;
}


body {
    padding:25px;
    font-family:sans-serif;
}
.timeline {
    white-space:nowrap;
    overflow-x: scroll;
    padding:33px 0 10px 0;
    position:relative;
}

.entry {
    display:inline-block;
    vertical-align:top;
    background:rgba(4, 50, 107, 0.54);
    color:#fff;
    padding:10px;
    font-size:15px;;
    text-align:center;
    position:relative;
    border-top:4px solid rgba(4, 50, 107, 0.54);
    border-radius:43px;
    min-width:150px;
    max-width:500px;d
}

.entry:after {
content: '';
display: block;
background: rgb(255, 255, 255);
width: 12px;
height: 12px;
border-radius: 6px;
border: 1px solid #2196f3;
position: absolute;
left: 46%;
top: -31px;
margin-left: 1px;
}

.entry:before {
content: '';
display: block;
background: rgb(33, 150, 243);
width: 2px;
height: 17px;
position: absolute;
left: 50%;
top: -21px;
margin-left: 0px;
}

.entry h1 {
    color:#fff;
    font-size:18px;
    font-family:Georgia, serif;
    font-weight:bold;
    margin-bottom:10px;
}

.entry h2 {
    letter-spacing:.2em;
    margin-bottom:10px;
    font-size:14px;
}

.bar {
height: 2px;
background: #2196F3;
width: 100%;
position: relative;
top: 13px;
left: 0;
}
.user_tip_marker .inner_blink{
    display: inline-block;
    width: 12px;
    height: 12px;
    background-color: #00a2ea;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    margin-left: -14px;
    margin-top: -9px;
    border: 3px solid #fff;
     animation: blinker 2s linear infinite;
}

.circular--portrait {
  position: relative;
  width: 100px;
  height: 100px;
  overflow: hidden;
  border-radius: 50%;
}

.circular--portrait img {
  width: 100%;
  height: auto;
}

@keyframes blinker {  
  50% { opacity: 0; }
}


.user_tip_marker .blink {
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-left: -1px;
    margin-top: -1px;
    border: 4px solid #00a2ea;
    border-radius: 50%;
    -webkit-animation: pulsate 2s ease-out infinite;
    -moz-animation: pulsate 2s ease-out infinite;
    -o-animation: pulsate 2s ease-out infinite;
    animation: pulsate 2s ease-out infinite;
}

span{
    border: 0;
    outline: 0;
    vertical-align: baseline;
    background: transparent;
    margin: 0;
    padding: 0;
}

.user{
    margin-top: 0px;
   margin: -11px -6px -11px 20px;
    display: inline-block;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
    border: 2px solid #f8f8f8;
}
.one {
  background-image: url('http://placehold.it/350x150');
  
 .btn-social {
    width: 100px;
    position: relative;
    opacity: 0.5;
    transition: 0.3s ease;
    cursor: pointer;
}

.btn-social:hover {
    transform: scale(1.5, 1.5);
    opacity: 1;
}
  
}



</style>


<?php

 if($_REQUEST['project'] !="" ){
$_SESSION['g_PR_id']=$_REQUEST['project'];
}
 if($_SESSION['g_PR_id'] ==""){
echo "<script>window.location.href='Projects.php'</script>";
}
print('<div class="row" style="padding:10px"></div><div class="container" >'); 
 /*if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 899)) {
        // last request was more than 30 minutes ago
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("Your session is timed out!! Please login again!!!")
        window.location.href="Signout.php"; </script> ';
        
        print($l_alert_statement );
    }*/    
       
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
        $l_UR_id = $_SESSION['g_UR_id'];
        $l_UR_Type = $_SESSION['g_UR_Type'];
        $l_TM_id = $_SESSION['g_TM_id'];
     $l_PR_id = $_SESSION['g_PR_id'];
        $l_UR_Receiver=$l_UR_id;
        //$l_UR_PR_Type=$_SESSION['g_UR_PR_Type'];
        $l_query_UR_PR_Type="Select UR_PR_Type from Users WHERE  UR_id ='".$l_UR_id."'";
        $rowpr=mysql_query($l_query_UR_PR_Type);
        $l_UR_PR_Type_result= mysql_fetch_row($rowpr);
        $l_UR_PR_Type=$l_UR_PR_Type_result[0];
        $_SESSION['g_UR_PR_Type']=$l_UR_PR_Type;
        
    $sql_freeprojects_check=mysql_query('SELECT MO.MO_Amount,MO.MO_id FROM Projects AS PR ,Model AS MO WHERE MO.MO_id=PR.MO_id AND PR.PR_id='.$l_PR_id.'');
    $Presult= mysql_fetch_row($sql_freeprojects_check);
     $l_PR_amount= $Presult[0];
     $l_MO_id=  $Presult[1];
     // query for project completion 
    $l_project_complete ='select TM_EndDate from Teams where TM_id = "'.$l_TM_id .'"';
    $l_project_complete_result = mysql_query($l_project_complete);
    $l_project_row = mysql_fetch_row($l_project_complete_result);
    $l_PR_complete = $l_project_row[0];
    
    
    $l_UR_Details_query ='select UR_FirstName from Users where UR_id = "'.$l_UR_id.'"';
    $l_UR_Details_result = mysql_query($l_UR_Details_query);
    $l_UR_Name_row = mysql_fetch_row($l_UR_Details_result);
    $l_UR_FName = $l_UR_Name_row[0];
    
    // queries for team start date from teams on the basis of this.
    
    $l_TM_StartDate_query ='select TM_StartDate from Teams where TM_id = "'.$l_TM_id.'"';   // query for TM_startDate
    $l_TM_result = mysql_query($l_TM_StartDate_query);
    $l_TM_row = mysql_fetch_row($l_TM_result);
    $l_TM_StartDate = $l_TM_row[0];
    // queries for project duration on the basis of this. project should be end by students.
    
    $l_PR_Duration_query ='select PR_Duration ,PR_SynopsisURL from Projects where PR_id = "'. $l_PR_id .'"';
    // query for PR_Duration
    $l_PR_result = mysql_query($l_PR_Duration_query);
    $l_PR_row = mysql_fetch_row($l_PR_result);
    $l_PR_Duration = $l_PR_row[0];      // will return result in days
    $_SESSION['g_pdf_view'] = $l_PR_row[1];   // for synopsis URL
    
    $l_PR_finalDate = date('d-M-Y',strtotime($l_TM_StartDate) + (24*3600*$l_PR_Duration)); //my preferred method

    // profile picture query 
   $q ="SELECT UR_image from Users WHERE UR_id='". $l_UR_id."'";
    $row =mysql_query($q);
    $imgresult=mysql_fetch_row($row);
 $imagename= $imgresult[0];
    
    if(is_null($l_UR_id) || $l_UR_Type!='S')
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a student. Please login correctly")
        window.location.href="login.php"; </script> ';
        
        print($l_alert_statement );
    }
    else
    {
     // For date and time
        $l_LastLoginDate_query = 'select  UR_LastLogin from Users where UR_id = "'.$l_UR_id.'"' ;
        $l_LastLoginDate = mysql_query($l_LastLoginDate_query);
        $l_Date=mysql_fetch_row($l_LastLoginDate);
        $l_LoginDate_res=$l_Date[0];
        
        $l_LoginDate_res= date("d-M-Y h:i A", strtotime($l_LoginDate_res));
      if(!empty($l_TM_id)){
      $query='select PS.PD_id,PS.PD_Seen,PD.PD_FeedbackDate from PRdoc_Seen as PS,Project_Documents as PD where PS.PD_TM_id='.$l_TM_id.' and PS.PD_TM_id=PD.TM_id and PS.PD_id=PD.PD_id and PS.UR_Id="'.$l_UR_id.'" order by PS.PD_id DESC limit 0,1';
    $queryrun=mysql_query($query);
    $runquery=mysql_fetch_row($queryrun);
    }
    
       /* print('<div  class="row alert alert-info " style="font-size: large;     margin-top: 14px;
}"><b align="center">Welcome to Projectory :<font color="ff6347"> '.$l_UR_FName.'</font><span style="float:right; color:#4682b4;">logged in at ' .$l_LoginDate_res. '</span></div>');
        
       
        */
          ?>
<div class="row alert alert-info" style="font-size: large;        margin-top: 23px;">
    <div class="col-md-5">
    <b>Welcome to Projectory:&nbsp;</b><font color="ff6347">
<?php echo $l_UR_FName;?></font><?php if($_SESSION['login']){ ?><a  class ="btn btn-danger" href ="gitlogin.php?action=login" >Git Access</a><?php } ?>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-4 ady-logged-in" >
   logged in at <?php echo $l_LoginDate_res;?> <span class="btn-social" data-toggle="tooltip" data-placement="bottom" title="Change Profile Picture"><img  class="  previewing user" style="cursor: pointer;" data-toggle="modal" data-target="#myModal"  src="<?php if($imagename==""){echo 'assets/images/avatar_2x.png'; }else{echo "upload/".$imagename;}  ?>"   /></span>
</div>
    
</div>

       <?php 
     
       
       if($runquery[2]!=NULL && ($runquery[1]=='P' || $runquery[1]=='I')){?>
       <div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Notification: </strong>Guide has given feedback to your document<a href="SViewDocuments.php?flag=S&&pd_id=<?php echo $runquery[0]; ?>"> Click here  </a>to view
</div> 
  <?php  }else if($runquery[2]==NULL && $runquery[1]=='P'){?>
       <div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Notification: </strong>Document submitted by Teammate <a href="SViewDocuments.php?flag=S&pd_id=<?php echo $runquery[0]; ?>"> Click here  </a>to view
</div>
    <?php } ?>
    
          
       <?php  if($l_TM_id != -99 && isset($l_TM_id)){
          $count = 'select count(*) from Teammate_Request as TT where TT.TT_ResponseDateTime is NULL and TT.UR_Receiver ="TEAM@#'.$l_TM_id.'"';
            $count_query = mysql_query($count);
             $l_count = mysql_fetch_row($count_query);
             if($l_count[0] > 0)
            {
                $l_Sender_query = 'select UR_Sender from Teammate_Request where TT_ResponseDateTime is null  and UR_Receiver ="TEAM@#'.$l_TM_id.'"';
                $l_Sender_Res = mysql_query($l_Sender_query);
                $l_Sender_row = mysql_fetch_row($l_Sender_Res);
                $l_UR_Sender = $l_Sender_row[0];
                $l_sender_query='select UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName from Users as UR where UR.UR_id  = "'.$l_UR_Sender.'"';
                $l_sender_result = mysql_query($l_sender_query);    // run the actual SQL
                    print('<div class="alert alert-info">');
                    print('<p><span style="font-size:14px;">Requests:</span>');
                    while ($l_sender_row =mysql_fetch_row($l_sender_result))
                    {
                        $l_UR_Name = $l_sender_row[0] . ' ' . $l_sender_row[1] . ' ' . $l_sender_row[2];
                        print ( $l_UR_Name );     // From
                      
                        
                        print ( '<a class ="btn btn-primary" href="TeamResp.php?res=A&&sender='.$l_UR_Sender.'">Accept</a>');
                        print ( '<a class ="btn btn-primary" href="TeamResp.php?res=R&&sender='.$l_UR_Sender.'">Reject</a>');
                        
                        
                   }
                   print('</p></div>');
            }
        } ?>
       <?php
   
        if($l_TM_id == -99)
        {
            $count = 'select count(*) from Teammate_Request as TT where TT.TT_ResponseDateTime is NULL and TT.UR_Receiver ="'.$l_UR_id.'"';
            $count_query = mysql_query($count);
            $l_count = mysql_fetch_row($count_query);
            
            if($l_count[0] > 0)
            {
                // Get the Sender id
                $l_Sender_query = 'select UR_Sender from Teammate_Request where TT_ResponseDateTime is null  and UR_Receiver ="'.$l_UR_Receiver.'"';
                $l_Sender_Res = mysql_query($l_Sender_query);
                $l_Sender_row = mysql_fetch_row($l_Sender_Res);
                $l_UR_Sender = $l_Sender_row[0];
                //print $l_Sender_query;
                //print 'sender='.$l_Sender_Res;
                $l_count = mysql_num_rows($l_Sender_Res);
                
                if($l_count > 0)
                {
                    
                    // Get the message
                    $l_msg_query = 'Select CM_Message from Communications  where UR_Sender ="'.$l_UR_Sender.'" and UR_Receiver ="'.$l_UR_Receiver.'" order by CM_DateTime desc limit 1';
                    $l_msg_res = mysql_query($l_msg_query) ;
                    $l_msg_row = mysql_fetch_row($l_msg_res);
                    $l_CM_Message = $l_msg_row[0];
                    $l_Teamname = substr($l_CM_Message, 28);
                    
                    // print $l_msg_query;
                    
                    $l_sender_query='select UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName from Users as UR where UR.UR_id  = "'.$l_UR_Sender.'"';
                    $l_sender_result = mysql_query($l_sender_query);    // run the actual SQL
                    print('<div class="alert alert-info">');
                    print('<p><span style="font-size:24px;">Requests:</span>');
                    while ($l_sender_row =mysql_fetch_row($l_sender_result))
                    {
                        $l_UR_Name = $l_sender_row[0] . ' ' . $l_sender_row[1] . ' ' . $l_sender_row[2];
                        print ( $l_UR_Name );     // From
                        print ("<input   type='button'  class='btn btn-primary' value='Accept' onClick=\"window.location='SUpdateComm.php?g_updSQL=Accept'\"/> ");
                        
                        
                        print ( "<input   type='button' class='btn btn-primary'  value='Reject' onClick=\"window.location='SUpdateComm.php?g_updSQL=Reject'\"/> ");
                 }
                    print ('</p></div>');
                   
                    
                    mysql_free_result($l_msg_res);
                    
                }
                mysql_free_result($count_query);
                
            } ///////////-------if team id count is -99----end--------///////////////

        } 
          function getNotification($notify){
      $arr=array('a','b','c');
      return $notify;
      }

        /*
         //milestones
         
         1. Add Team
         2. Add Guide/Mentor
         3 - n. Number of documents to be submitted
         */
        
        
        // queries for showing milestone
        $l_sql_status='select PR_id,TM_id from Users as UR where UR.UR_id="'.$l_UR_id.'"';
        $l_result_status = mysql_query($l_sql_status);
        $l_row_UR= mysql_fetch_row($l_result_status);
        $l_PR_set=$l_row_UR[0];
        $l_TM_set=$l_row_UR[1];
        
        
        $sql_GM_set='select UR_id_Mentor,UR_id_Guide from Teams as TM where TM.TM_id='.$l_TM_id.'';
        $l_result_GM = mysql_query($sql_GM_set);
        
        if($l_result_GM!=NULL)
        {
            $l_row_GM= mysql_fetch_row($l_result_GM);
        }
        $l_Mentor_set=$l_row_GM[0];
        
        $l_Guide_set=$l_row_GM[1];
        
        //--------------------------------------------------
        $l_PD_sql = 'select distinct (PD.AL_id) from Project_Documents as PD where PD.TM_id ='.$l_TM_id.' and PD.PD_Status = "A"';
        $l_PD_res = mysql_query($l_PD_sql);
        if($l_PD_res!=Null)
        {
            $l_count_PD = mysql_num_rows($l_PD_res);
        }
        $l_PS_id_arr = array();
        
        $sql_MaxPS_num='select max(PS_Seq_No) from ProjectDocument_Sequence as PDS where PDS.PR_id='.$l_PR_id.'';
        $l_result_num = mysql_query($sql_MaxPS_num) ;
        $l_row= mysql_fetch_row($l_result_num);
        $l_max_num=$l_row[0];
        $done_mile=0;
        $final_mile = $l_max_num+2;
        
        if($l_TM_id == NULL) //team is not formed
        {
            $done_mile=0;
            $percentage=8;
        }
        else if($l_TM_id != NULL&&$l_Guide_set==NULL) //only a team is formed without guide selected
        {
            $done_mile=1;
            $percentage = round(($done_mile/$final_mile)*100);
            
        }
        else if($l_count_PD==0) //team is formed along with guide or mentor but without any doc approved
        {
            $done_mile=2;
            $percentage = round(($done_mile/$final_mile)*100);
            
        }
        else
        {
            $done_mile=$l_count_PD + 2;
            $percentage = round(($done_mile/$final_mile)*100);
            
            
        }
        ?>
<div class="row">
    <div class="col-md-3" style="color:red;"> <div class="circle" style=" background-color: #14a76c;">Accept</div><div class="circle" style=" background-color: #6a6969;">Reject</div><div class="circle" style=" background-color: #ff7a62;">Pending</div><div class="circle" style=" background-color: #7790af;">Default</div>
        </div>
    <div class="col-md-6">
  
    <?php
    $currentdate=date('d-M-Y');
    $l_PR_Before = (strtotime($l_PR_finalDate) - strtotime($currentdate))/ (60 * 60 * 24);
     if($l_TM_StartDate!=NULL )
    {?>
    	<p>The project needs to be completed by:<?php echo $l_PR_finalDate;?></p>
    <?php }?>
    
        
        <div class="progress" style='<?php if($l_PR_Before <= 7 && $l_TM_StartDate!=NULL ){ echo "background-color: #d9534f"; } else{ echo "background-color: rgba(111, 85, 91, 0.32)" ; } ?>'>
            <div class="progress-bar " role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="2" aria-valuemax="100" style="min-width: 2em; width:<?php echo $percentage.'%'; ?>;">
                <?php echo $percentage; ?>%
                
            </div>
        </div>
        <?php
        if($l_TM_StartDate!=NULL ){ 
    if($l_PR_Before <= 7 && $l_PR_Before >0){?> 
       <p style='color: red !important;margin: -20px 0px 5px;text-align:center'> Your project is ending within a week</p>
    <?php } else if($l_PR_Before<=0){
    echo "<p style='color: red !important;margin: -20px 0px 5px;text-align:center'> Your Project Duration is Over</p>";
    }
    
    }?>
    <?php    if($l_PR_complete!=NULL)
    {
    ?>
   <div class="alert alert-success alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Congratulations!</strong> You have completed project successfully!
</div>
  <?php  } ?>
    </div>
    <div class="col-md-3">
        
    </div>
</div>


 
 
 <div class="bar"></div>
<div class="timeline">
    <?php  
    $backgroundDone= 'background: #14a76c';
    $backgroundProgress= 'background: rgba(255, 99, 71, 0.85)';
    
    ?>
    <div class="entry" style="<?php if($l_TM_set==-99){echo $backgroundProgress;}else if($l_TM_set!=NULL && $l_TM_set>0){echo $backgroundDone;} ?>">
     <?php if($l_TM_set!=NULL && $l_TM_set>0){ ?><span class="glyphicon glyphicon glyphicon-ok-sign"></span> <?php }else{echo '<span class="user_tip_marker"><span class="blink"> </span><span class="inner_blink"></span></span>'; $c=4;} echo 'Form a Team' ;?>
        
    </div>
    
    <?php if($l_UR_PR_Type=='C')
        {
?>
    <div class="entry" style="<?php if($l_Guide_set!=NULL ){echo $backgroundDone;} ?>">
       <?php if($l_Guide_set!=NULL ){ ?> <span class="glyphicon glyphicon glyphicon-ok-sign"></span> <?php } else if($c!=4){echo '<span class="user_tip_marker"><span class="blink"> </span><span class="inner_blink"></span></span>'; $c=5;} echo 'Select Guide' ;?>
        
    </div>
    <?php } 
    
  
    
   ?>
     <?php if($l_MO_id!=1)
        {
?>
    <div class="entry" style="<?php  if($l_Mentor_set!=NULL){echo $backgroundDone;}else if($l_Mentor_set==NULL && $l_Mentor_response!=NULL ){echo $backgroundProgress;} ?>">
    <?php if($l_Mentor_set!=NULL){  ?> <span class="glyphicon glyphicon glyphicon-ok-sign"></span>   <?php } else if($c!=5 && $c!=4){echo '<span class="user_tip_marker"><span class="blink"> </span><span class="inner_blink"></span></span>'; $c=6;} echo 'Select Mentor';?>
        
    </div>
     <?php } 
    
  
    
   ?>
   
    <?php 
     
       $l_PS_Seq_sql  ='select max(PS.PS_Seq_No) from  ProjectDocument_Sequence as PS where  PS.PR_id='.$l_PR_id.'';
            
     $l_PS_Seq_res = mysql_fetch_row(mysql_query($l_PS_Seq_sql));
       
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
           
            if($pdrun[0]=='A' ){
                $bg='background: #14a76c'; 
            }else if($pdrun[0]=='R'){ 
                $bg='background:rgba(19, 18, 18, 0.63)'; }else if($pdrun[0]=='P'){
                    $bg='background: rgba(255, 99, 71, 0.85) ';
                       
                }else { 
            
            $bg="" ; 
            
            $i++;
           }
          
       
            ?> 
  <div class="entry" style="<?php echo $bg;?>">
      <?php if($pdrun[0]=='A')
      {  ?> <span class="glyphicon glyphicon glyphicon-ok-sign"></span>   <?php 
      }
      else if($pdrun[0]=='R')
      {$r=4;?>
       <!--<span class="glyphicon glyphicon glyphicon glyphicon-remove-sign"></span> -->
      <span class="user_tip_marker"><span class="blink"> </span><span class="inner_blink"></span></span> 
      <?php 
      }
      else if($pdrun[0]=='P' && $c!=4 && $c!=5 && $c!=6 && $r!=4)
      {$j=7;?>
      <span class="user_tip_marker"><span class="blink"> </span><span class="inner_blink"></span></span> 
      <?php 
      }
      else if($i==1 && $pdrun[0]!='P' && $pdrun[0]!='A' && $j!=7 && $c!=4 && $c!=5 && $c!=6 && $r!=4)
      {  ?>
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
         $l_sql_PR='select PR.PR_Name,PR.PR_Desc,PR_AllowedSynopsis from Projects as PR where PR.PR_id='.$_SESSION['g_PR_id'].'';
        $l_result_PR = mysql_query($l_sql_PR);
        $l_PR_AllowedSynopsis="N";
        if($l_row_PR=mysql_fetch_row($l_result_PR))
        {
            $l_PR_Name= $l_row_PR[0];
            $l_PR_Desc= $l_row_PR[1];
             $l_PR_AllowedSynopsis= $l_row_PR[2];
        }
         ?>
         <div class="row "><div class="col-md-4 ">
   
    <div class="circular--portrait" style=" box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19) !important;">
  <img  src="<?php if($imagename==""){echo 'assets/images/avatar_2x.png'; }else{echo "upload/".$imagename;}  ?>" />
</div>

        
         </div>
         <?php
        print('<div class="col-md-4 "><Your Project is class="ady-row" border ="0" bordercolor="#718DE2">');
        
       // print('<tr><td ><a class="btn btn-primary ady-btn" role="button" href="PDFView.php">View Synopsis</a></td></tr>');
        if($l_PR_AllowedSynopsis == 'Y'){
        print('<tr><td ><a class="btn btn-primary ady-btn" role="button" href="iframetest.php">View Synopsis</a></td></tr>');
        }  else {
         print('<tr><td ><a class="btn btn-primary ady-btn" role="button" href="ViewSynopsis.php">View Synopsis</a></td></tr>');
        }
        print('<tr ><td><a class="btn btn-primary ady-btn" role="button" href="STeam.php">Add a Teammate</a></td></tr>');
       if($l_UR_PR_Type=='C')
        {
        print('<tr><td><a class="btn btn-primary ady-btn" href="SGuide.php">Add a Guide</a></td></tr>');
        
        }
        if($l_PR_amount != 0){
        print('<tr><td><a class="btn btn-primary ady-btn" href="SMentor.php">Add a Mentor</a></td></tr>');
        } 
        else if(isset($_SESSION['g_TM_id'])){?>
         <tr><td><a class="btn btn-primary ady-btn" href="PaymentMentorhr.php">Hire A Mentor</a></td></tr>
        <?php
        }
        print('<tr><td><a class="btn btn-primary ady-btn" href="SDocSubmit.php">Submit a Document</a></td></tr>');
        
        
        print('<tr><td> <a class="btn btn-primary ady-btn" href="SViewDocuments.php">View your Documents and feedbacks</a></td></tr>');
      //  print('<tr><td> <a class="btn btn-primary ady-btn" href="completedprojects.php">View Completed Projects</a></td></tr>');

        print('</table></div>');
        ?>
        <div class="col-md-4 ">
     <div class="panel panel-default" >
      <div class="panel-heading" style=" color: navajowhite !important;
    background-color: #d9534f;
    border-color: #d9534f;">Recent Updates</div>
      <div class="panel-body" style="height: 45%; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19) !important;">
          <span class="glyphicon glyphicon-chevron-right"></span>
          <a href="sharedlinks.php">Shared Drives</a>
          </div>
    </div>
    
         </div>
        </div>
        
        
        
        <?php
        
        print('<br><div class="row"><div class="col-md-12  ady-row"> <table class="ady-table-dashboard" style="width:100%; border:1px solid #134D69;" >');
        print ('<tr><th align="center" colspan=2>Details </th></tr>');
        
        // query for project details
      
        
        print ('<tr>');
        print('<td >Your Project is </td>');
        print('<td>'.$l_PR_Name .'</td>');
        print('</tr>');
      
        print ('<tr>');
        print('<td >Your Project description is </td>');
        print('<td>'.htmlspecialchars_decode($l_PR_Desc).'</td>');
        print('</tr>');
        
        $techquery="select SD.SD_Name from SubDomain as SD,Project_SubDomains as PSD where PSD.SD_id=SD.SD_id and PSD.PR_id=".$_SESSION['g_PR_id']." group by SD.SD_Name";
        $techrun=mysql_query($techquery);
        $techcount = mysql_num_rows($techrun);
        print ('<tr>');
        print('<td >Technologies to be used </td>');
        print('<td>');
        $count=1;
        
        while($l_row_tech = mysql_fetch_row($techrun))
                {
                 if($count<$techcount)   
                     print($l_row_tech[0].' , ');
                else
                     print($l_row_tech[0]);
 
                $count=$count+1;
                }
        print('</td>');
        print('</tr>');
        //select  Institute Name,programme Name,university serial number
        $l_sql_UR = 'select UR.UR_USN, UR.UR_Semester, PG.PG_Name, IT.IT_Name from Users as UR, Institutes as IT, Programs as PG where IT.IT_id = UR.IT_id and PG.PG_id  = UR.PG_id and UR.UR_id="' . $l_UR_id . '"';
        
        $l_result_UR = mysql_query($l_sql_UR);
        if($l_row_UR=mysql_fetch_row($l_result_UR))
        {
            $l_IT_Name= $l_row_UR[3];
            $l_PG_Name= $l_row_UR[2];
            
        }
        
        print ('<tr>');
        print('<td >Your Institute is </td>');
        print('<td>'.$l_IT_Name .'</td>');
        print('</tr>');
        
        print ('<tr>');
        print('<td >Your Program is </td>');
        print('<td>'.$l_PG_Name.'</td>');
        print('</tr>');
        
        // Check Team is Formed
       
        if($l_TM_id!=NULL && $l_TM_id!=-99)
        {
            
            // query to get Team details
            $l_sql_TM = 'select TM.TM_Name, TM.UR_id_Guide , TM.UR_id_Mentor from Teams as TM where TM.TM_id ='.$l_TM_id.'';
            $l_result_TM = mysql_query($l_sql_TM);
            if($l_row_TM_Name = mysql_fetch_row($l_result_TM))
            {
                $l_TM_Name= $l_row_TM_Name[0];
                $l_UR_id_Guide= $l_row_TM_Name[1];
                $l_UR_id_Mentor=$l_row_TM_Name[2];
            }
            print ('<tr>');
            print('<td >Your Team Name is </td>');
            print('<td>'.$l_TM_Name .'</td>');
            print('</tr>');
            
           $l_sql_members = 'select UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName from Users as UR where UR.UR_id<>"'.$l_UR_id.'" and TM_id='.$l_TM_id.'';
            $l_result_members = mysql_query($l_sql_members);
            $l_count_members = mysql_num_rows($l_result_members);
            if($l_count_members>0)
            {
                print ('<tr>');
                print('<td rowspan='.$l_count_members.'>Your Team members are : </td>');
                $i=1;
                while($l_row_TM_members = mysql_fetch_row($l_result_members))
                {
                    

                    if($i==1)
                    {
                        print('<td>'. $l_row_TM_members[0].' '.$l_row_TM_members[1].' '. $l_row_TM_members[2].'</td>');
                        print('</tr>');

                    }
                    else
                    {
                        print ('<tr>');
                        print('<td>'. $l_row_TM_members[0].' '.$l_row_TM_members[1].' '. $l_row_TM_members[2].'</td>');
                        print('</tr>');

                    }
                    $i=$i+1;

                }

            }
            
           // Check if Guide is formed
            if($l_UR_id_Guide!=NULL)
            {
                // query to get Guide details
                $l_sql_Guide_Name = 'select UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName from Users as UR where UR.UR_id="'.$l_UR_id_Guide.'"';
                $l_result_Guide_Name = mysql_query($l_sql_Guide_Name) ;
                if($l_row_Guide_Name = mysql_fetch_row($l_result_Guide_Name))
                {
                    $l_UR_FirstName= $l_row_Guide_Name[0];
                    $l_UR_MiddleName= $l_row_Guide_Name[1];
                    $l_UR_LastName=$l_row_Guide_Name[2];
                }
                print ('<tr>');
                print('<td >Your Guide\'s name is </td>');
                print('<td>'. $l_UR_FirstName.' '.$l_UR_MiddleName.' '. $l_UR_LastName.'</td>');
                print('</tr>');
            }
           
            // Check if Mentor is formed
            if($l_UR_id_Mentor!=NULL)
                
            {
                
               // query to get Mentor details
                $l_sql_Mentor_Name = 'select UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName from Users as UR where UR.UR_id="'.$l_UR_id_Mentor.'"';
                $l_result_Mentor_Name  = mysql_query($l_sql_Mentor_Name )
                ;
                if($l_row_Mentor_Name  = mysql_fetch_row($l_result_Mentor_Name ))
                {
                    $l_UR_FirstName= $l_row_Mentor_Name [0];
                    $l_UR_MiddleName= $l_row_Mentor_Name [1];
                    $l_UR_LastName=$l_row_Mentor_Name [2];
                }
                print ('<tr>');
                print('<td >Your Mentor\'s name is </td>');
                print('<td>'. $l_UR_FirstName.' '.$l_UR_MiddleName.' '. $l_UR_LastName.'</td>');
                print('</tr>');
            }
            
            
        }
        
        print('</Your Project is></div></div>');
        print('</div><br>');
    //}
        ?>
 <?php  $l_completed_projects='select PR.PR_id,PR.PR_Name from Student_Results as ST,Projects  as PR  where UR_Student="'.$l_UR_id.'" and PR.PR_id=ST.PR_id';   
 
 $l_completed_query=  mysql_query($l_completed_projects);
 $l_completed_count=  mysql_num_rows($l_completed_query);
 
 
       
if( $l_completed_count >0){
 ?>

<div class="container">
<div class="panel panel-primary ">
    <div class="panel-heading">
        <h3 style="padding:0px !important;margin:0px !important"> View Completed Projects</h3>
    </div>
    <div class="panel-body">
      <div  class="list-group">
        <?php 
        while($l_completed_row=  mysql_fetch_row($l_completed_query)){
        ?>
             <a class="list-group-item " href="completedprojects.php?PR_id=<?php echo $l_completed_row[0]; ?>"><?php echo $l_completed_row[1]; ?></a>
        <?php } ?>
     </div>
    </div>
</div>
<?php } ?>
 </div>
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style=" background: powderblue;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color: #4d90fe;">Upload Profile Picture</h4>
        </div>
        <div class="modal-body">
        <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
<div id="image_preview"><img style=" width: 266px;     height: 240px; border: 2px solid #4d90fe;
    border-radius: 11px;" class="previewing" src="<?php if($imagename==''){echo 'assets/images/avatar_2x.png';}else{ echo 'upload/'.$imagename;} ?>" /></div>
<hr id="line">
<div id="selectImage">
<label>Select Your Image</label><br/>

<input class="form-control" type="file" name="file" id="file" required />
<input class="form-control btn-primary" type="submit" value="Upload" class="submit" />

</div>
</form>
<div id="message"></div>
</div>


        </div>
        
      </div>
    </div>
  </div>
</div>

<?php } include('footer.php')?>
<script>
    $(document).ready(function(){
    $(".list-group-item").hover(function(){
        $(this).css("background-color", "#d9d9d9");
        }, function(){
        $(this).css("background-color", "#FFFFFF");
    });
});
    </script>
    
            <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="dist/js/bootstrap-imageupload.js"></script>

<script>

$(document).ready(function (e) {
$("#uploadimage").on('submit',(function(e) {
e.preventDefault();
$("#message").empty();
$('#loading').show();
$.ajax({
url: "ajax_php_file.php", // Url to which the request is send
type: "POST",             // Type of request to be send, called as method
data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
contentType: false,       // The content type used when sending data to the server.
cache: false,             // To unable request pages to be cached
processData:false,        // To send DOMDocument or non processed data file it is set to false
success: function(data)   // A function to be called if request succeeds
{
$('#loading').hide();
$("#message").html(data);
}
});
}));

// Function to preview image after validation
$(function() {
$("#file").change(function() {
$("#message").empty(); // To remove the previous error message
var file = this.files[0];
var imagefile = file.type;
var match= ["image/jpeg","image/png","image/jpg"];
if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
{
$('.previewing').attr('src','noimage.png');
$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
return false;
}
else
{
var reader = new FileReader();
reader.onload = imageIsLoaded;
reader.readAsDataURL(this.files[0]);
}
});
});
function imageIsLoaded(e) {
$("#file").css("color","green");
$('#image_preview').css("display", "block");
$('.previewing').attr('src', e.target.result);
$('.previewing').attr('width', '250px');
$('.previewing').attr('height', '230px');
};
});

$("#message").dialog({
    height: 140,
    modal: true,
    open: function(event, ui){
     setTimeout("$('#acknowledged-dialog').dialog('close')",5000);
    }
});

</script>
