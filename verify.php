<?php include('header_signup.php'); ?>
<?php  include ('db_config.php');?>

<div class="container cus-top">
<?php
     $l_size_SD_sel_id_arr=0;
   $l_VerifyCodeEmail=$_REQUEST['uverify'];
  $l_UR_id=$_REQUEST['uid'];
   $l_UR_Type=$_REQUEST['utype'];

      $l_query_select ='SELECT UR_RegistrationStatus FROM  Users where UR_id="'.$l_UR_id.'"  and UR_Type="'.$l_UR_Type.'"';
          $l_query_select=mysql_query($l_query_select) or die(mysql_error());
          $l_res=mysql_fetch_row($l_query_select);
        
          if($l_res[0] == "P" || $l_res[0] == "C" ) 
           {
            $_SESSION['error']="<div class='alert alert-info'>You have registered Successfully!! Please Login</div>";
             echo"<script>window.location.href='login.php'</script>";
           } 
           else if(empty($l_UR_Type)||empty($l_UR_id)) 
           {
 
               $_SESSION['error']="<div class='alert alert-info'>!!Sorry Plese Try again</div>";
              echo"<script>window.location.href='signup.php'</script>";
           }  
           else 
            {
               if($l_UR_Type=='S'|| $l_UR_Type=='C'|| $l_UR_Type=='A')   {  
                    $l_query_update ='Update Users set UR_RegistrationStatus="C" where UR_id="'.$l_UR_id.'" and UR_VerifyCode="'.$l_VerifyCodeEmail.'" and UR_Type="'.$l_UR_Type.'"';
                    $l_query_result=mysql_query($l_query_update) or die(mysql_error());
                    print('<div class="alert alert-success"><h4>You have registered Successfully!!</h4><a href="login.php">Login</a></div>');   
                 } 
               else if($l_UR_Type=='G'|| $l_UR_Type=='M')
                   {
                  if(isset($_POST['Submit']))     
                     {
                     if($l_UR_Type=='M'){
		$l_mentor_days=$_POST['l_mentor_days'];
		$l_mentor_start_time= $_POST['l_mentor_start_time'];
		$l_mentor_end_time= $_POST['l_mentor_end_time'];
                $flag=0;
                for($k=0;$k<7;$k++){
                     if (($l_mentor_start_time[$k] !="" && $l_mentor_end_time[$k] != "") && ($l_mentor_start_time[$k] < $l_mentor_end_time[$k])) {
                         $flag+=1;
                     }
                       
                   }
		}
		
                    $l_SD_sel_id_arr = $_POST['l_SD_sel'];
                    $l_size_SD_sel_id_arr = count(  $l_SD_sel_id_arr);
                    $l_SD_id_arr_index =0;

                    if($l_size_SD_sel_id_arr==0)
                    {
                         echo "<font color=red>You have not selected any Skills.</font>";
                         echo count($l_mentor_start_time);
                        echo  count($l_mentor_end_time) ;
                    }
                    
                     else if($l_UR_Type =='M'&& $flag==0)  {
                   
                     echo "<font color=red>Please Select the Times Propely.</font>";
                    }
                   
                    else
                    {
			if($l_UR_Type=='M'){
				foreach($l_mentor_days as $keys)  {  
				$key=$keys-1;  
				//echo $keys."|".$l_mentor_start_time[$key]. "||".$l_mentor_end_time[$key]."<br>";
				             
					if($l_mentor_start_time[$key] !="" && $l_mentor_end_time[$key] !="") {
					if ($l_mentor_start_time[$key] < $l_mentor_end_time[$key]){
					
					 $sql_mc='INSERT INTO Mentor_Calendar( MC_DayNo, MC_StartTime, MC_EndTime, UR_id, Org_id, MC_Status) VALUES ('.$keys.','.$l_mentor_start_time[$key].','.$l_mentor_end_time[$key].',"'.$l_UR_id.'","ZP","Y")';
					mysql_query($sql_mc) OR die(mysql_error());
					}
					}
				}
			}
                   
                        while ($l_SD_id_arr_index < $l_size_SD_sel_id_arr)
                        {
                            $l_query = "insert into UR_Subdomains (UR_id, SD_id) values ('".$l_UR_id."',".$l_SD_sel_id_arr[$l_SD_id_arr_index].")";
                            $l_TEechnologies_Inserted = mysql_query($l_query) or die(mysql_error());    // run the actual SQL

                            $l_SD_id_arr_index = $l_SD_id_arr_index + 1;
                        }
                        if($l_SD_id_arr_index > 0){
                             $l_query_update ='Update Users set UR_RegistrationStatus="C" where UR_id="'.$l_UR_id.'" and UR_VerifyCode="'.$l_VerifyCodeEmail.'" and UR_Type="'.$l_UR_Type.'"';
                             $l_query_result=mysql_query($l_query_update) or die(mysql_error());
                             if($l_query_result){
                                  print('<div class="alert alert-success"><h4>You have registered Successfully!!</h4><a href="login.php">Login</a></div>');   
                             }
                            
                        }
                    }
                    
                 } 
                  if($l_size_SD_sel_id_arr==0)
                    {
                        print('<form method = "POST" action="">');
                        print('<table class="ady-table-content" style="width:100%">');
                        print('<tr><th colspan=2><center>Complete your Technologies known section by checking the Boxes.</center></th></tr>');

                        $l_select_sql = 'SELECT SD_id, SD_Name FROM SubDomain';
                        $l_result_sql = mysql_query($l_select_sql);

                        while($l_row = mysql_fetch_row($l_result_sql)){
                         print ('<tr>');
                            $l_SD_id       = $l_row[0];
                            $l_SD_Name= $l_row[1];

 				print( '<td>'.$l_SD_Name.'</td>');
				print('<td>');
                            print('<center><input type="checkbox" class="g_checkbox_select_DM" value="'.$l_SD_id.'" name="l_SD_sel[]"></center></td>');

                            print('</tr>'); 
                        }
                        print('</table>');
                        print ('<table class="ady-table-content" style="width:100%">');
              
                        print('</table>');
                        
                        /*Recent Modified for Mentor Availability Time for every Weeks*/
        if($l_UR_Type=='M' ){
            ?>
    <div class="col-md-8 col-md-offset-2">
      <h3 style="color:blue;"><b>Availability Times</b></h3>
        <div class="row">
            <div class="col-md-4 col-sm-4 text-center"><strong> Days </strong></div>
            <div class="col-md-4 col-sm-4 text-center"><strong> Start Time </strong></div>
            <div class="col-md-4 col-sm-4 text-center"> <strong> End Time</strong></div>
        </div>
         <?php 
         $days=array('','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
         
         $hours=array('12:00 AM','01:00 AM','02:00 AM', '03:00 AM', '04:00 AM', '05:00 AM', '06:00 AM', '07:00 AM', '08:00 AM','09:00 AM','10:00 AM','11:00 AM','12:00 PM', '01:00 PM','02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM', '06:00 PM', '07:00 PM', '08:00 PM','09:00 PM','10:00 PM','11:00 PM','12:00 AM');
                  for($j=1; $j<=7;$j++){?>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4"> 
                <?php echo $days[$j];?>
                <input type="hidden" name="l_mentor_days[]" value="<?php echo $j;?>" >
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4"> 
                <select class="form-control" name="l_mentor_start_time[]" id="">
                    <option value="">Choose..</option>
                    <?php for($a=0; $a<=23;$a++){?>
                    <option value="<?php echo $a;?>">
                    <?php echo $hours[$a];?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
                <select class="form-control" name="l_mentor_end_time[]" id="">
                    <option value=" ">Choose..</option>
                    <?php for($b=1; $b<=24;$b++){?>
                    <option value="<?php echo $b;?>"><?php echo $hours[$b];?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
         <?php }?>
         
    
    <?php 
            
        }
        ?>
       
		<div style="float:right;"><input type=submit  name="Submit" class="btn btn-primary btn-lg" value=Submit></div>
		<br><br><br>
		</div>
          
                        </form>
            <?php    /*===============End=======*/
                 }
                   
              }
         }



?>