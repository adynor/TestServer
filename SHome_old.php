 <?php 
 include('header.php');
 include('db_config.php');

?>
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
    
    
    
     
    $querypr='select UR_PR_Type from Users where UR_id="'.$l_UR_id.'"';
    $result=mysql_fetch_row(mysql_query($querypr));
    
    $l_UR_PR_Type=$result[0];
    //$l_UR_PR_Type=$_SESSION['g_UR_PR_Type'];
    
    
    
    
    
    
    
    //$l_UR_PR_Type=$_SESSION['g_UR_PR_Type'];
    
    
    $sql_freeprojects_check=mysql_query('SELECT MO_Amount FROM Projects AS PR ,Model AS MO WHERE MO.MO_id=PR.MO_id AND PR.PR_id='.$l_PR_id.'');
  $l_PR_amount=  mysql_fetch_row($sql_freeprojects_check)[0];
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
    
    
    
//    if(is_null($l_UR_id) || $l_UR_Type!='S')
//    {
//        $l_alert_statement =  ' <script type="text/javascript">
//        window.alert("You have not logged in as a student. Please login correctly")
//        window.location.href="login.php"; </script> ';
//        
//        print($l_alert_statement );
//    }
//    else
//    {
     // For date and time
        $l_LastLoginDate_query = 'select  UR_LastLogin from Users where UR_id = "'.$l_UR_id.'"' ;
        $l_LastLoginDate = mysql_query($l_LastLoginDate_query);
        $l_Date=mysql_fetch_row($l_LastLoginDate);
        $l_LoginDate_res=$l_Date[0];
        
        $l_LoginDate_res= date("d-M-Y h:i A", strtotime($l_LoginDate_res));
    
    
       
          ?>
<div class="row alert alert-info" style="font-size: large;     margin-top: 14px;">
    <div class="col-md-5">
    <b>Welcome to Projectory:&nbsp;</b><font color="ff6347"><?php echo $l_UR_FName;?></font>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-4 ady-logged-in" >
   logged in at <?php echo $l_LoginDate_res;?>
    </div>
</div>
     <?php 
     
     if(!empty($l_TM_id))  {
 $query='select PS.PD_id,PS.PD_Seen,PD.PD_FeedbackDate from PRdoc_Seen as PS,Project_Documents as PD where PS.PD_TM_id='.$l_TM_id.' and PS.PD_TM_id=PD.TM_id and PS.PD_id=PD.PD_id and PS.UR_Id="'.$l_UR_id.'" order by PS.PD_id DESC limit 0,1';
    $queryrun=mysql_query($query);
    
 // $count=mysql_num_rows($queryrun);
  
  //if($count>0){
    $runquery=mysql_fetch_row($queryrun);
   // }
      if($runquery[2]!=NULL && ($runquery[1]=='P' || $runquery[1]=='I')){  ?>
    <div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Notification: </strong>Guide has given feedback to your document<a href="SViewDocuments.php?flag=S&&pd_id=<?php echo $runquery[0]; ?>"> Click here  </a>to view
</div>  
    <?php }else if($runquery[2]==NULL && $runquery[1]=='P'){?>
       <div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Notification: </strong>Document submitted by Teammate <a href="SViewDocuments.php?flag=S&pd_id=<?php echo $runquery[0]; ?>"> Click here  </a>to view
</div>
    <?php } }?>
        
        
   
        
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
    <div class="col-md-3" style="color:red;"></div>
    <div class="col-md-6">
   <?php if($l_TM_StartDate !='')  {?>
        <p>The project needs to be completed by:<?php echo $l_PR_finalDate;?></p>
        <?php } ?>
        <div class="progress" style="background-color: rgba(111, 85, 91, 0.32)">
            <div class="progress-bar " role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="2" aria-valuemax="100" style="min-width: 2em; width:<?php echo $percentage.'%'; ?>;">
                <?php echo $percentage; ?>%
                
            </div>
        </div>
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

       <?php
         print('<div class="row "><div class="col-md-4 "></div>');
        print('<div class="col-md-4 "><table class="ady-row" border ="0" bordercolor="#718DE2">');
        
       // print('<tr><td ><a class="btn btn-primary ady-btn" role="button" href="PDFView.php">View Synopsis</a></td></tr>');
         print('<tr><td ><a class="btn btn-primary ady-btn" role="button" href="ViewSynopsis.php">View Synopsis</a></td></tr>');
        print('<tr ><td><a class="btn btn-primary ady-btn" role="button" href="STeam.php">Add a Teammate</a></td></tr>');
      if($l_UR_PR_Type=='C')
       {
       print('<tr><td><a class="btn btn-primary ady-btn" href="SGuide.php">Add a Guide</a></td></tr>');
        
       } 
       
       
        
        if($l_PR_amount != 0){
        print('<tr><td><a class="btn btn-primary ady-btn" href="SMentor.php">Add a Mentor</a></td></tr>');
        }
        print('<tr><td><a class="btn btn-primary ady-btn" href="SDocSubmit.php">Submit a Document</a></td></tr>');
        
        
        print('<tr><td> <a class="btn btn-primary ady-btn" href="SViewDocuments.php">View your Documents and feedbacks</a></td></tr>');
      //  print('<tr><td> <a class="btn btn-primary ady-btn" href="completedprojects.php">View Completed Projects</a></td></tr>');

        print('</table></div>');
        print('<div class="col-md-4 "></div></div>');
        
        
        
        
        
        print('<br><div class="row"><div class="col-md-12  ady-row"> <table class="ady-table-dashboard" style="width:100%; border:1px solid #134D69;" >');
        print ('<tr><th align="center" colspan=2>Details </th></tr>');
        
        // query for project details
        $l_sql_PR='select PR.PR_Name,PR.PR_Desc from Projects as PR where PR.PR_id='.$_SESSION['g_PR_id'].'';
        $l_result_PR = mysql_query($l_sql_PR);
        if($l_row_PR=mysql_fetch_row($l_result_PR))
        {
            $l_PR_Name= $l_row_PR[0];
            $l_PR_Desc= $l_row_PR[1];
        }
        
        print ('<tr>');
        print('<td >Your Project is </td>');
        print('<td>'.$l_PR_Name .'</td>');
        print('</tr>');
        
        print ('<tr>');
        print('<td >Your Project description is </td>');
        print('<td>'.htmlspecialchars_decode($l_PR_Desc).'</td>');
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
        
        print('</table></div></div>');
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
<?php include('footer.php')?>
<script>
    $(document).ready(function(){
    $(".list-group-item").hover(function(){
        $(this).css("background-color", "#d9d9d9");
        }, function(){
        $(this).css("background-color", "#FFFFFF");
    });
});
    </script>