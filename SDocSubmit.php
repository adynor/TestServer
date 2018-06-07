<?php
    include ('header.php');
    ////////////////////////////////////////
    //Name: SDocSubmit
    //Purpose : Documents submitted by the students
    //Project: Projectory
    //Calls:
    //called by:
    ////////////////////////////////////////
    
    include ('db_config.php');
    
    ?>
    
    <style>

body {
    padding:85px;
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
    max-width:500px;
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
</style>

<div class="row" style="padding:20px 0px">
<div class="col-md-1"></div>
 <?php  
    $backgroundDone= 'background: #14a76c';
    $backgroundProgress= 'background: rgba(255, 99, 71, 0.85)';
     // Get Team-id,User-type,User-id,Project id from session variables

    $l_TM_id=$_SESSION['g_TM_id'];
    $l_UR_Type = $_SESSION['g_UR_Type'];
    $l_UR_id = $_SESSION['g_UR_id'];
    $l_PR_id=$_SESSION['g_PR_id'];
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
     $l_PD_SubmissionDate = $date->format( 'Ymd' ); echo "<br>";
   $l_TM_StartDate_query ='select TM_StartDate from Teams where TM_id = "'.$l_TM_id.'"';   // query for TM_startDate
    $l_TM_result = mysql_query($l_TM_StartDate_query);
    $l_TM_row = mysql_fetch_row($l_TM_result);
     $l_PR_Duration_query ='select PR_Duration ,PR_SynopsisURL from Projects where PR_id = "'. $l_PR_id .'"';
    // query for PR_Duration
    $l_PR_result = mysql_query($l_PR_Duration_query);
    $l_PR_row = mysql_fetch_row($l_PR_result);
    $l_PR_Duration = $l_PR_row[0];
     $l_TM_endDate = date('Ymd',strtotime($l_TM_row[0]) + (24*3600*$l_PR_Duration));
     
     if(strtotime($l_TM_endDate) < strtotime($l_PD_SubmissionDate ) && isset($l_TM_id)){
     echo "<h3 style='color:red;margin:0 auto;text-align:center'>Sorry !! Your Project Duration is Over</h3>";
     }
     else {
    ?>
  <div class="col-md-12">
<div class="bar"></div>
<div class="timeline">
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
        </div>
        
        
        <div class="col-md-1"></div>
        
        </div>
<div class="container" >
<div class="row" style="padding:0px 0px">
<div class="col-md-3"></div>
<div class="col-md-6  ady-row">
<?php

    
    // Checking if User loged-in
    if(is_null($l_UR_id) || $l_UR_Type!='S')
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a student. Please login correctly")
        window.location.href="login.php"; </script> ';
        
        print($l_alert_statement );
    }else if(is_null ($l_TM_id))
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("Your Team is not prepared. Please form a team first.")
        window.location.href="STeam.php"; </script> ';
        
        print($l_alert_statement );
    }
    else
    
    { print('<div style="clear:left">');
        
        // query to check the total number of document submition is needed
        $l_AL_check_query = 'select count(AL.AL_id) from Access_Level as AL, Payment_Access as PA where AL.AL_type = "PA" and AL.AL_id = PA.AL_id  and PA.UR_id = "'.$l_UR_id.'"  and PA.TM_id = '.$l_TM_id.'';
        $l_AL_check_res = mysql_query($l_AL_check_query);
        $l_AL_check_row = mysql_fetch_row($l_AL_check_res);
        $l_count = $l_AL_check_row[0];
        mysql_free_result($l_AL_check_res);
        
        if($l_count > 0)
        {
            $l_AL_size_query = 'select max(AL.AL_Size) from Access_Level as AL, Payment_Access as PA where AL.AL_type = "PA" and AL.AL_id = PA.AL_id  and PA.UR_id = "'.$l_UR_id.'"  and PA.TM_id = '.$l_TM_id.' ';
            $l_AL_size_res = mysql_query($l_AL_size_query);
            $l_AL_size_row = mysql_fetch_row($l_AL_size_res);
            $l_max_filesize = $l_AL_size_row[0];
            
        }
        
        else
        {
            $l_max_filesize = 100000;
        }
        
        print('<div class="alert alert-info"> <h4>Once you upload a file, You cannot delete the uploaded file</h4> </div>');
        
        //query to select the project-id of the team
        $l_query_PR = 'select TM.PR_id from Teams as TM where Org_id="'.$_SESSION['g_Org_id'].'" and TM.TM_id='.$l_TM_id.'';
        $l_result_PR = mysql_query($l_query_PR);    // run the actual SQL
        $l_PR_id_row = mysql_fetch_row($l_result_PR);
        $l_PR_id = $l_PR_id_row[0];
        
        //query to select the guide of the team
        $l_query_guide = 'select TM.UR_id_Guide,TM.UR_id_Mentor from Teams as TM where Org_id="'.$_SESSION['g_Org_id'].'" and TM.TM_id='.$l_TM_id.'';
        $l_result_guide = mysql_query($l_query_guide);    // run the actual SQL
        $l_guide_id_row = mysql_fetch_row($l_result_guide);
        $l_guide_id = $l_guide_id_row[0];
        $l_Mentor_id = $l_guide_id_row[1];
        
        
        if(is_null($l_PR_id))
        {
            $l_alert_statement =  ' <script type="text/javascript">
            var x=window.alert("You cannot Upload file  without  selecting project.")
            
            window.location="SHome.php";
            
            </script>';
            print($l_alert_statement );
            
        }
        else if(is_null($l_guide_id) && $_SESSION['g_UR_PR_Type']=='C')
        {
            $l_alert_statement =  ' <script type="text/javascript">
            var x=window.alert("You cannot Upload file  without  selecting a guide. Would you like to add a guide  ?")
            
            window.location="SHome.php";
            
            </script>';
            print($l_alert_statement );
        }
        else if(is_null($l_Mentor_id) && $_SESSION['g_UR_PR_Type']=='N')
        {
            $l_alert_statement =  ' <script type="text/javascript">
            var x=window.alert("You cannot Upload file  without  selecting a Mentor. Would you like to add a Mentor  ?")
            
            window.location="SHome.php";
            
            </script>';
            print($l_alert_statement );
        }
/*
        if(isset($_POST['submit'])) // check if this is the 2nd time the php is being run after pressing the Save button
        {
            $l_in_filename = $_FILES['file']['tmp_name'];    // this is the name of the temp file that the <input type="file" name="file" creates in the /tmp directory of the server
            $filename  = $_FILES['file']['name'];
            $filesize  = $_FILES['file']['size'];
            $filetype  = $_FILES['file']['type'];
            
            //$filename  = basename($_FILES['file']['name']);
            // $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            $not_allowed = array('php','php5','exe','css','php','c','cpp','java','pl','htm','js','css','xml','jsp','ser','jsf','jse','bat','cmd','jad','json','aspx','lib','pdb','dbg','php3','pmp','pm','bml','p','j','hta','com','lnk','pif','scr','vb','vbs','wsh','html');
            
            if(in_array($extension, $not_allowed))
            {
                echo '<font color=red>Please change your file to ZIP/rar and try uploading again</font>';
                
            }
            
            else  {
                
                if ($_FILES["file"]["error"] > 0)
                {
                    echo "<font color=red> You have not selected any file. Please choose the File !</font>";
                }
                else
                {
                    
                    $l_filesize = ($_FILES["file"]["size"] / 1024); // File size in KBs
                    
                    $iex= explode('&%',$_POST['l_AL_id']);
                    $l_AL_Name=$iex[1];
                    $l_AL_id=$iex[0];

                    
                    if($l_filesize > $l_max_filesize)
                    {
                        echo 'You do not have access for big files, please pay.';
                    }
                    else
                    {
                        
                        $l_PD_sql = 'select count(*) from Project_Documents as PD where Org_id="'.$_SESSION['g_Org_id'].'" and PD.TM_id = '.$l_TM_id.' and PD.AL_id = '.$l_AL_id.' and PD.PD_Status = "P"';
                        $l_PD_res = mysql_query($l_PD_sql) or die(mysql_error());
                        $l_PD_row = mysql_fetch_row($l_PD_res);
                       
                        $l_count_PD = $l_PD_row[0];
                        
                        if($l_count_PD == 0)
                        {
                            $l_AL_query = 'Select AL.AL_Desc from Access_Level as AL where AL.AL_id = '.$l_AL_id.'';
                          
                            $l_AL_res = mysql_query($l_AL_query) or die(mysql_error());
                            $l_Al_row = mysql_fetch_row($l_AL_res);
                            $l_AL_Desc = $l_Al_row[0];

                  

   
                            if (empty( $_FILES["file"]["name"]))
                            {
                                $_FILES["file"]["name"] . "<font color=red> Please choose file to Submit. </font>";
                            }
                            else
                            {
                              
                                $l_TM_Revise = 'select TM.TM_Revise from Teams as TM  where Org_id="'.$_SESSION['g_Org_id'].'" and TM.TM_id ='.$l_TM_id.' ';
                                $l_result_TM = mysql_query($l_TM_Revise) or die(mysql_error());
                                $l_row_TM = mysql_fetch_row($l_result_TM);
                                $l_Revise_TM=$l_row_TM[0];
                                
                                //ECHO $l_TM_Revise;
                                if($l_Revise_TM == "Y")
                                {
                                    $l_Update_query_N = 'update Teams set TM_Revise = "N" where Org_id="'.$_SESSION['g_Org_id'].'" and TM_id ='. $l_TM_id.'';
                                    $l_Update_revise_N = mysql_query($l_Update_query_N) or die(mysql_error());
                                }
                                
                                
                                //$folder = 'Projectory/ProjectDocuments'; // for linux
                               // move_uploaded_file($l_in_filename, $folder.'/'.$l_PD_Name );
                                
                                $l_PD_id_count = 'Select MAX(PD.PD_id) from Project_Documents as PD where Org_id="'.$_SESSION['g_Org_id'].'"';
                                $l_PD_id_res = mysql_query($l_PD_id_count) or die(mysql_error());
                                $l_PD_row = mysql_fetch_row($l_PD_id_res);
                                $l_PD_id = $l_PD_row[0];
                                
                                $l_PD_id = $l_PD_id + 1;
                                
                                $l_PD_Status = 'P';
                                //$l_PD_URL = $l_filehomepath.'/Projectory/ProjectDocuments/'.$l_PD_Name;
                                
                                if(!empty($l_in_filename)){
                                    $filedata= addslashes(file_get_contents($l_in_filename));
                                }
                                //$test=file_get_contents($l_in_filename);
                                
                                $l_find_doc_name=mysql_query('Select count(PD_id) from Project_Documents where Org_id="'.$_SESSION['g_Org_id'].'" and PR_id="'.$l_PR_id.'" and AL_id="'.$l_AL_id.'" and TM_id="'.$l_TM_id.'"');
                                $row_pdcount_row=  mysql_fetch_row($l_find_doc_name);
                                $row_pdcount = $row_pdcount_row[0];
                                $PD_Count = $row_pdcount+1;
                                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                $rename =  $l_AL_Desc.$l_TM_id.'_'.$PD_Count;
                                $l_PD_Name = $rename.'.'.$extension;
            
                               $l_insert_sql = 'insert into Project_Documents (AL_id, PD_Feedback, PD_FeedbackDate, PD_id, PD_Name, PD_Rating, PD_Status, PD_SubmissionDate,PD_Data, PR_id, TM_id ,Org_id,PD_Data_Size,PD_Data_Type,PD_Original_Name) values ("' . $l_AL_id . '",NULL, NULL, ' . $l_PD_id . ',  "' . $l_PD_Name . '", NULL, "' . $l_PD_Status . '",'.$l_PD_SubmissionDate.',"' .$filedata.'",'.$l_PR_id.',"'.$l_TM_id.'","'.$_SESSION['g_Org_id'].'",'.$filesize.',"'.$filetype.'","'.$filename.'")';
                                $result=mysql_query($l_insert_sql) or die(mysql_error());
                                if($result){print('<div class="alert alert-success">Document submitted successfully</div>');}
                                else{print('<div class="alert alert-danger">!!Sorry Please try again......</div>');}
                                //mysql_free_result($l_PD_id_res);
                            
                             $getallteammates='select UR_id from Users where TM_id='.$l_TM_id;
                                $runqueryteammates=mysql_query($getallteammates);
                                
                            while($row=mysql_fetch_row($runqueryteammates)){
                                IF($row[0]==$l_UR_id)
                                    { 
                                    $P='I';
                                    }
                                else
                                    {
                                    $P='P';
                                    
                                    }
                                   $l_insertpd_sql = 'insert into  PRdoc_Seen (PD_AL_id,PD_Seen,PD_id,PD_TM_id,Org_id,UR_id) values ("'. $l_AL_id .'","'.$P.'","'.$l_PD_id.'","'.$l_TM_id.'","'.$_SESSION['g_Org_id'].'","'.$row[0].'")';
                                $resultpd=mysql_query( $l_insertpd_sql) or die(mysql_error());
                                
                                }  
                            } //else---if (file_exists("upload/" . $_FILES["file"]["name"]))
                            //header('location:EmailNotifications?g_query=Student|'.$l_TM_id.'|'.$l_AL_Desc.'');
                            
                           echo '<script>window.location.href="EmailNotifications.php?g_query=Student|'.$l_TM_id.'|'.$l_AL_Desc.'"</script>';
                           
                           mysql_free_result($l_AL_res);
                        }
                        else
                        {
                            $l_alert_statement =  ' <script type="text/javascript">
                            window.alert("Your previous document have not been given any feedback. You cannot submit any document until the previous document is given a feedback")
                            </script> ';
                            
                            print($l_alert_statement );
                        }
                    }
                }//else ---if(isset($_POST['submit'])) /
            }
        }
        //if(isset($_POST['submit'])) / close isset block here
        */
        
        ?>



<img id="loader" style='visibility: hidden;' src="aloader.gif" />

<form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="myForm" enctype="multipart/form-data">
<table  border=1 class="ady-table-content" style="width:100%">
<tr>
<th colspan="2" style="">Upload File </th>
</tr>
<tr>
<td>File Name</td>
<td>
<input  type="file" name="file" id="sortpicture">
</td>
</tr>
<tr>
<td >Select Type of File </td>
<td>
<?php

    //query to get total number of access level
    $l_PD_sql = 'select distinct (PD.AL_id) from Project_Documents as PD where  PD.TM_id ='.$l_TM_id.' and PD.PD_Status = "A"';
    $l_PD_res = mysql_query($l_PD_sql);
    $l_PS_id_arr = array();
    
    $l_count_PD = mysql_num_rows($l_PD_res);
    
    
    // if Access level found
    if($l_count_PD>0)
    {
        while($l_PD_row = mysql_fetch_row($l_PD_res))
        {
            $l_PS_Seq_sql  ='select PS.PS_Seq_No from  ProjectDocument_Sequence as PS where PS.AL_id='.$l_PD_row[0].' and PS.PR_id='.$l_PR_id.'';
            
            $l_PS_Seq_res = mysql_query($l_PS_Seq_sql);
            
            while($l_PS_row = mysql_fetch_row($l_PS_Seq_res))
            {
                array_push($l_PS_id_arr, $l_PS_row[0]);
                
            }
        }
    }
    
    // access level not found
    if($l_count_PD == 0)
    {
        $l_sql  ='select PS.AL_id, AL.AL_Desc FROM  ProjectDocument_Sequence as PS,Access_Level as AL where PS.PR_id='.$l_PR_id.' and PS.PS_Seq_No=1 and PS.AL_id=AL.AL_id';
        
        //echo $l_sql;
        $l_result = mysql_query($l_sql);
        
        print('<select class="form-control" name="l_AL_id" >');
        
        while ($l_data=mysql_fetch_row($l_result))
        {
            print ('<option value = "'.$l_data[0].'_'.$l_data[1] .'">'.$l_data[1]. '</option> ' ); // showing default access level whose sequence number is first

        }
        print ('</select>');
        mysql_free_result($l_result);
    }
    
    else
    {
        $l_Max_PS_id = max($l_PS_id_arr);
        $l_Max_PS_id = $l_Max_PS_id + 1;
        print('<select class="form-control" name="l_AL_id" >');
        for($inc = $l_Max_PS_id; $inc >= 1; $inc--)
        {
            // get Access level names in the list according to sequence number
            $l_Seq_sql = 'select PS.AL_id, AL.AL_Desc from ProjectDocument_Sequence as PS,Access_Level as AL where PS.PR_id='.$l_PR_id.' and PS.PS_Seq_No='.$inc.' and PS.AL_id=AL.AL_id';
            $l_res = mysql_query($l_Seq_sql) or die(mysql_error());
            
            if($l_data = mysql_fetch_row($l_res))
            {
                print ('<option value = "'.$l_data[0].'&%'.$l_data[1] .'">'.$l_data[1]. '</option> ' );  // showing acces level list on the basis of sequence
            }
        }
        print ('</select>');
    }
    ?>

</td>
</tr>

<tr>
<td colspan=2>
<center> <input type="submit" class="btn-primary ady-cus-btn" id="upload" name="submit" value = "Submit Document"></center>   
</td>
</tr>
</form>

</table>

</div>
<br /><br />
<?php
    // nav code for view template documents starts here !!!!!
    print('<table class="ady-table-content" style="width:100%;margin-top:15px;" border="1" ><tr><th>Documents Templates</th><th>Template Status</th></tr>');
   
    if($l_count_PD == 0)
    {
        
        //select template data where project document sequence number is 1
        $l_Template_query  ='select AL.AL_Desc,PDS.PS_id,PDS.PS_Doc_Size,AL.AL_id,AL.AL_Templatec_Size FROM  ProjectDocument_Sequence as PDS,Access_Level as AL where  PDS.PR_id='.$l_PR_id.' and PDS.PS_Seq_No=1 and PDS.AL_id=AL.AL_id';
        $l_Template_res = mysql_query($l_Template_query);
        if($l_row = mysql_fetch_row($l_Template_res))
        {
            $l_AL_Desc           =$l_row[0];
            $l_Template_ID      =$l_row[1];
            $l_Template_Size      =$l_row[2];
             $l_AL_id=$l_row[3];
            $l_AL_Size=$l_row[4];
            if($l_Template_Size!=0)
            {
            
                print( '<tr><td><font color=009933>' .$l_AL_Desc . '</font></td>');
                echo "<td><a class='btn btn-primary' href='blob_download.php?psid=".$l_Template_ID."'>Download</a>";
                echo "</td></tr>";
            }
           else 
                {
                
                if($l_AL_Size==0){
                
                    print( '<tr><td ><font color=009933> '.$l_AL_Desc .' </font></td><td style="text-align:center" >Not Available</td></tr>');
                    
                    }else{
                   echo"<tr><td ><font color=009933> ".$l_AL_Desc ."</font></td>";
                echo "<td><a class='btn btn-primary' href='DefaultTemplateDownload.php?ALid=".$l_AL_id."'>Download</a>";
                    echo "</td></tr>";
                    }
                    
                }
        }
        
    }
    else
    {
        for($inc = 1; $inc <= $l_Max_PS_id; $inc++)
        {
            //select template data as per the project document sequence number
          
          
            $l_Template_query = 'select AL.AL_Desc,PDS.PS_id,PDS.PS_Doc_Size,AL.AL_id,AL.AL_Templatec_Size FROM  ProjectDocument_Sequence as PDS , Access_Level as AL where AL.AL_id = PDS.AL_id and PDS.PS_Seq_No='.$inc.' and PDS.PR_id = '.$l_PR_id.'' ;
            $l_Template_res = mysql_query($l_Template_query);
            if($l_row = mysql_fetch_row($l_Template_res))
            {
                $l_AL_Desc           =$l_row[0];
                $l_Template_ID      =$l_row[1];
                $l_Template_Size      =$l_row[2];
                $l_AL_id=$l_row[3];
                $l_AL_Size=$l_row[4];
                if($l_Template_Size!=0)
                {
                    print( '<tr><td><font color=009933>' .$l_AL_Desc . '</font></td>');
                    echo "<td><a class='btn btn-primary' href='blob_download.php?psid=".$l_Template_ID."'>Download</a>";
                    echo "</td></tr>";
                }
                else 
                {
                if($l_AL_Size==0){
                    print( '<tr><td ><font color=009933> '.$l_AL_Desc .' </font></td><td style="text-align:center" >Not Available</td></tr>');
                    
                    }else{
                echo"<tr><td ><font color=009933> ".$l_AL_Desc ."</font></td>";
                echo "<td><a class='btn btn-primary' href='DefaultTemplateDownload.php?ALid=".$l_AL_id."'>Download</a>";
                    echo "</td></tr>";
                    }
                    
                }
            }
        }
        
    }
    print('</table>');
    
    // Get all the Access levels as a drop down from the Access_Levels =-=-=-
    // Then when he uploads the file ask him to select the one that he's trying to upload =-=-=-
    // Get all the values from the Payment_Access for the payment which he has made
    // Upload into a folder based on the TEAMNAME
    
    
    }
    ?>
</div>
</div>
</div>
<script>
function showLoading(){
document.getElementById("loader").style = "visibility: visible";
}
function hideLoading(){
document.getElementById("loader").style = "visibility: hidden";
}
$(document).ready(function() {
  $(".myform").submit(function() {
showLoading();
alert($(this).serialize());
    // do the extra stuff here
    $.ajax({
     type: "POST",
      url: "SDocsum.php",
      data: $(this).serialize(),
      success: function(data) {
        hideLoading()
        alert(data);
       }
    })

  })
})
</script>
     <?php }include('footer.php')?>