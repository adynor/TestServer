<?php 
  session_start();
  include 'db_config.php';
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
  
   
   if($l_UR_Type=='S'){
   if(!empty($l_TM_id)){
      $query='select PS.PD_id,PS.PD_Seen,PD.PD_FeedbackDate from PRdoc_Seen as PS,Project_Documents as PD where PS.PD_TM_id='.$l_TM_id.' and       PS.PD_TM_id=PD.TM_id and PS.PD_id=PD.PD_id and PS.UR_Id="'.$l_UR_id.'" order by PS.PD_id DESC limit 0,1';
    $queryrun=mysql_query($query);
    $runquery=mysql_fetch_row($queryrun);
    }
    
    if($runquery[2]!=NULL && ($runquery[1]=='P' || $runquery[1]=='I')){?>
       <div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Notification: </strong>Guide has given feedback to your document<a href="SViewDocuments.php?flag=S&&pd_id=<?php echo $runquery[0]; ?>"> Click here  </a>to view
</div> 
  <?php }else if($runquery[2]==NULL && $runquery[1]=='P'){?>
       <div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Notification: </strong>Document submitted by Teammate <a href="SViewDocuments.php?flag=S&pd_id=<?php echo $runquery[0]; ?>"> Click here  </a>to view
</div>
    <?php }
    
    
  // for teammate requests we have a queries
     if($l_TM_id != -99 && isset($l_TM_id)){
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
                    print('<div class="alert alert-warning">');
                    print('<p><span style="font-size:14px;">Requests:</span>');
                    while ($l_sender_row =mysql_fetch_row($l_sender_result))
                    {
                        $l_UR_Name = $l_sender_row[0] . ' ' . $l_sender_row[1] . ' ' . $l_sender_row[2];
                        print ('<hr style="
    background-color: #3F51B5;
">'. $l_UR_Name );     // From
                      
                        
                        print ( '<a style="border-radius: 0px;" class ="btn btn-primary  btn-xs pull-right" href="TeamResp.php?res=A&&sender='.$l_UR_Sender.'">Accept</a>&nbsp;');
                        print ( '<a style="border-radius: 0px;" class ="btn btn-danger  btn-xs pull-right" href="TeamResp.php?res=R&&sender='.$l_UR_Sender.'">Reject</a>');
                        
                        
                   }
                   print('</p></div>');
            }
        } 
        
      } ?>