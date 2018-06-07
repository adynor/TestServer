<?php include('header.php');
if(!isset($_SESSION['g_UR_id'])) {
echo "<script>window.location.href='https://www.zaireprojects.com/test'</script>";
exit();
}?>
<?php  include ('db_config.php');


?>
<style>
#loading {
   width: 100%;
   height: 100%;
   top: 0px;
   left: 0px;
   position: fixed;
   display: block;
   opacity: 0.7;
   background-color: #fff;
   z-index: 99;
   text-align: center;
   display:none;
}

#loading-image {
  position: absolute;
  top: 200px;
  left: 50%;
  z-index: 100;
}
.three-col {
       -moz-column-count: 2;
       -moz-column-gap: 20px;
       -webkit-column-count: 2;
       -webkit-column-gap : 20px;
       -moz-column-rule-color:  #ccc;
       -moz-column-rule-style:  solid;
       -moz-column-rule-width:  1px;
       -webkit-column-rule-color:  #ccc;
       -webkit-column-rule-style: solid ;
       -webkit-column-rule-width:  1px;
}
</style>

<div class="container cus-top">

<?php
if(isset($_POST['user_submit']) ){
    echo '<div class="alert alert-info" style="text-align:center" >';
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $l_Insert_Datetime = $date->format( 'YmdHi' );
    
    //$l_UR_Type=$_POST['user_type'];
    $l_UR_Type=$_POST['user_type'];
    if($l_UR_Type == 'M' || $l_UR_Type == 'C'){
    $l_UR_Org_id="ALL";
    }
    else{
    $l_UR_Org_id="ZAP";
    }
    $l_UR_Salutation=$_POST['user_Salutation'];
    $l_Name=$_POST['user_name'];
     $l_StudentType=$_POST['student_type'];
    $l_UR_id=$_POST['user_id'];
    $l_pass=$_POST['user_password'];
    $l_cpass=$_POST['user_password_confirm'];

    $l_UR_USN=$_POST['user_urn'];
    if($l_UR_Type == 'S' || $l_UR_Type == 'G')  {
    if(empty($_POST['Other_Institute']) && empty($_POST['Other_program'])) {
    $l_UR_ProfileInfo=NULL;
    } else{
     $l_UR_ProfileInfo = $_POST['Other_Institute'].'||'.$_POST['Other_program'];
    }
    }
    else if($l_UR_Type == 'A') {
    $l_UR_ProfileInfo = $_POST['Other_Institute'];
    }
    else{
    $l_UR_ProfileInfo = $_POST['user_company_info'];
    }
    $l_UR_Company = $_POST['user_company_id'];
    $l_company_id=$_POST['user_company_id'];
   if(isset($_POST['user_it_id']) && empty($_POST['Other_Institute'])){
    $l_IT_id=$_POST['user_it_id'];
    } else{
   $l_IT_id="NULL";
    }
    if(isset($_POST['user_PG_id']) && empty($_POST['Other_program'])){
    $l_PG_id=$_POST['user_PG_id'];
    } else{
    $l_PG_id="NULL";
    }
    $l_Company_Name=$_POST['user_company_id'];
    if(isset($_POST['user_semester'])){
    $l_Semester=$_POST['user_semester'];
    }
    else{
   echo $l_Semester=NULL;
    }

   $l_Emailid=$_POST['user_email'];
    $array_Email=explode('@',$l_Emailid);
    $l_UR_Emailid =$array_Email[0];
    $l_UR_EmailidDomain = $array_Email[1];
    
 
// this is to check the user-id already in record
$l_query="select UR_id from Users where UR_id='".$l_UR_id."'";
$l_query_result=mysql_query($l_query) or die(mysql_error());
$l_UR_idCount=mysql_num_rows($l_query_result);

// this is to check the mail id already in record
$l_query="select UR_id from Users where CONCAT(UR_Emailid,UR_EmailidDomain)='".$l_UR_Emailid.$l_UR_EmailidDomain."'";
$l_query_result=mysql_query($l_query) or die(mysql_error());
$l_UR_EmailidCount=mysql_num_rows($l_query_result);






$array_Name=explode(' ',$l_Name);
$l_count=count($array_Name);

if($l_count == 1)
{
    $l_UR_FirstName = $array_Name[0];
   $l_UR_MiddleName = "";
   $l_UR_LastName ="";
}
else if($l_count ==2)
{
$l_UR_FirstName = $array_Name[0];
   $l_UR_MiddleName = "";
   $l_UR_LastName =$array_Name[1];
}
else if($l_count ==3)
{
$l_UR_FirstName = $array_Name[0];
   $l_UR_MiddleName = $array_Name[1];
   $l_UR_LastName =$array_Name[2];
}
else if($l_count>3) //Naveen Singh Kumar Mat Pateriya
{
$l_UR_FirstName = $array_Name[0];
$l_UR_MiddleName = "";
for ($i=1; $i<$l_count-1; $i++)
   {  

$l_UR_MiddleName = $l_UR_MiddleName.$array_Name[$i].' ';  

}
   
$l_UR_LastName =$array_Name[$l_count-1];
}
     

if (empty($l_pass) || empty($l_UR_id)) {
         echo "<font color=red>User id or password is missing.</font>";
     } 
     else if ($l_pass != $l_cpass) {
         // error matching passwords
         

echo '<font color=red>Your passwords do not match. Please type carefully.</font>';
     }

else if(strlen($l_pass)>15 || strlen($l_pass)<1)
{
echo"<font color=red>Password must be between 1 and 15 characters</font>";
}
     else if($l_UR_idCount==1)
{ 
echo "<font color=red>The <b>User-ID</b> you entered already exist. Please try again with different ID.</font>";
}
else if(empty($l_Name) && ($l_UR_Type==M||$l_UR_Type==G||$l_UR_Type==S)) 
{
echo"<font color=red>All fields must be filled.Please check !</font>";
}
else if(empty($l_Name) && ($l_UR_Type==C)) 
{
echo"<font color=red>All fields must be filled.Please check !</font>";
}
else if(empty($l_UR_Emailid) ||empty($l_UR_EmailidDomain))
{
echo"<font color=red>Please enter a valid email id</font>";

}
else if($l_UR_EmailidCount>=1)
{ 
echo "<font color=red>The <b>Email-ID</b> you entered already exist. Please try again with different ID.</font >";
}

else {
        $l_webMaster                 = 'support@zaireprojects.com';
        $l_random_number = rand(100000,999999);
        $l_random_str = strval( $l_random_number) ;                     ///convert the random number to alfa (string)
        print('<input type=hidden name=l_random_str  value="' . $l_random_str . '">  ');
        
       // $l_message = "Thank you for registering with us. <br>Your Verification Code is:".$l_random_str." <br><br>Sincerely, <br>Zaireprojects Support Team";
   /* $l_message ='Hi '.$l_UR_FirstName.',<br>Thank you for registering with us. Please click on the link below to complete email verification<br> <a href="http://zaireprojects.com/test/verify.php?uverify='.$l_random_str.'&&uid='.$l_UR_id.'&&utype='.$l_UR_Type.'">http://zaireprojects.com/test/verify.php</a><br><br>Sincerely, <br> Support Team';
       
       $l_subject = "Confirm Registration";
       $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";*/
       $l_SD_sel_arrs = $_POST['l_SD_sel'];
       print_r($l_SD_sel_arrs);
       
  $l_query = "insert into Users (UR_id, UR_Khufiya, UR_Emailid, UR_EmailidDomain, UR_Type, UR_USN, UR_Salutation,UR_FirstName, UR_MiddleName,UR_LastName,UR_CompanyName,UR_ProfileInfo,UR_InsertDate,UR_RegistrationStatus,UR_VerifyCode,UR_Semester,IT_id,PG_id,Org_id) values
 ('".$l_UR_id."', '".md5($l_pass)."', '".$l_UR_Emailid."', '".$l_UR_EmailidDomain."' , '".$l_UR_Type."','".$l_UR_USN."','".$l_UR_Salutation."','".$l_UR_FirstName."',
'".$l_UR_MiddleName."','".$l_UR_LastName."','".$l_company_id."','".$l_UR_ProfileInfo."','".$l_Insert_Datetime ."','C','".$l_random_str."','".$l_Semester."',".$l_IT_id.",".$l_PG_id.",'".$l_UR_Org_id."')";
                    $success=mysql_query($l_query);
                    
if($success){ 
$to=array($l_UR_Emailid.'@'.$l_UR_EmailidDomain);
if(isset($l_SD_sel_arrs)){
		foreach ($l_SD_sel_arrs as $l_SD_sel_arr)
                        {
                          echo   $l_query = "insert into UR_Subdomains (UR_id, SD_id,Org_id) values ('".$l_UR_id."',".$l_SD_sel_arr.",'".$l_UR_Org_id."')";
                            $l_TEechnologies_Inserted = mysql_query($l_query) or die(mysql_error());    // run the actual SQL
                         }
 			}
//sendmail($to,$subject,$l_message);
//mail( $l_UR_Emailid.'@'.$l_UR_EmailidDomain, $l_subject, $l_message, $l_headers2);
print('<div class="alert alert-success"><h5>Please check your mail and click on the verification link sent to you. The mail might take a few minutes to reach you. If you do not receive any mail please check your spam folder.</h5></div>');
}
        //echo "<script> window.location.href = 'signup_verify.php'</script>"; 
} 
echo '</div>';
}
?>

 <?php
         
$l_URPR_Type=$_REQUEST['q'];
$signup='Sign Up';
if($l_URPR_Type=='S'){
    $name="Name";
    $signup='Sign Up as Student';
    $usn='University Registration Number';
}else if($l_URPR_Type=='M'){
    $name="Name";
    $signup='Sign Up as Mentor';
    $usn='Mentor Registration Number';
}else if($l_URPR_Type=='G'){ 
    $name="Name";
    $signup='Sign Up as Guide';
    $usn='Guide Registration Number';
}else if($l_URPR_Type=='C'){
    $name="Company Name";
    $signup='Sign Up as Company';
}else if($l_URPR_Type=='A'){
    $name="Name";
    $signup='Sign Up as College Admin';
}else{
    $signup='Sign Up';
}

?>
  <div class="row">
        <div class="col-md-3" ></div>
        <div class="col-md-6 " >  
            <div class="bs-example" data-example-id="contextual-panels"> 
              <div class="panel panel-primary">
                  <div class="panel-heading" id="signup-panel-heading" style="text-align: center"><?php echo $signup;?></div>
                <div class="panel-body "> 
                    <form class="form-horizontal" action="" method="POST">
                        <div class="control-group" id="" >
                            <label class="control-label" for="">Select Your User Type<span style="color:red" >*</span></label>
                            <select   id="user_type" onchange="Usertype()" name="user_type" class="form-control input-lg">
                                <option  value="" >---</option>
                                <option  value="S" <?php if($l_URPR_Type=='S'){echo "selected";}?>>Student</option>
                                <option   value="G" <?php if($l_URPR_Type=='G'){echo "selected";}?>>Guide</option>
                                <option   value="C" <?php if($l_URPR_Type=='C'){echo "selected";}?>>Company</option>
                                <option  value="M" <?php if($l_URPR_Type=='M'){echo "selected";}?>>Mentor</option>
                                <option  value="A" <?php if($l_URPR_Type=='A'){echo "selected";}?>>College Admin</option>
                            </select>
                      </div>
          <?php  if($l_URPR_Type=='S'||$l_URPR_Type=='G' ||$l_URPR_Type=='A' ||$l_URPR_Type=='C' || $l_URPR_Type=='M'){
              if($l_URPR_Type !='C'){
             ?>
                    
                     <div class="control-group" >
                            <label class="control-label" for="Salutation">Salutation<span style="color:red">*</span></label>
                                <select  name="user_Salutation" id="user_Salutation" class="form-control input-lg">
                                    <option   value="Mr">Mr</option>
                                    <option  value="Ms">Ms</option>
                                    <option  value="Dr">Dr</option>
                                </select>
                          
                    </div>
         <?php } ?>
                    <div class="control-group">
                        <label class="control-label" for="username" id="user_name_label"><?php echo $name?><span style="color:red">*</span></label>
                        <div class="controls">
                        <input type="text" id="user_name" name="user_name" placeholder="Your Name" class="form-control input-lg">
                        <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="userid">User id<span style="color:red">*</span></label>
                        <div class="controls">
                        <input type="text" id="user_id" name="user_id" placeholder="Choose Your User Id" class="form-control input-lg">
                        <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="email">E-mail<span style="color:red">*</span></label>
                        <div class="controls">
                        <input type="text" id="user_email" name="user_email" placeholder="Enter Your E-mail" class="form-control input-lg">
                        <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="password">Password<span style="color:red">*</span></label>
                        <div class="controls">
                        <input type="password" id="user_password" name="user_password" placeholder="Choose Your Password" class="form-control input-lg">
                        <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="control-group" >
                        <label class="control-label" for="password_confirm">Confirm Password <span style="color:red">*</span></label>
                        <div class="controls">
                        <input type="password" id="user_password_confirm" name="user_password_confirm" placeholder="Confirm password" class="form-control input-lg">
                        <p class="help-block"></p>
                        </div>
                    </div>
         <?php } if($l_URPR_Type=='S'||$l_URPR_Type=='G' ||$l_URPR_Type=='M') {?>
                    <div class="control-group" >
                         <label class="control-label" for="URN"><?php echo $usn;?></label>
                        <div class="controls">
                            <input type="text" id="user_usn" name="user_urn" placeholder="" class="form-control input-lg">
                            <p class="help-block"></p>
                        </div>
                    </div>
         <?php } if($l_URPR_Type=='S'){?>
                    <div class="control-group"  >
                            <label class="control-label" for="User Semester">Semester<span style="color:red">*</span></label>
                            <select   id="user_semester" name="user_semester" class="form-control input-lg">
                                <option value="">----</option>
                                 <?php for($i=1;$i<9;$i++) {?>
                                <option id="user_semester_<?php echo $i ?>" value="<?php echo $i ?>" ><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                    </div>

                    <?php
                    }
                    $l_institutes= array();
                    if($l_URPR_Type=='S'||$l_URPR_Type=='G' ||$l_URPR_Type=='A' )
                    {    
                    $l_sql      ='SELECT IT_id, IT_Name FROM  Institutes WHERE IT_id <> 0 ORDER BY IT_Name ';
                    $l_result =mysql_query($l_sql);
                    while($l_row_results=mysql_fetch_row($l_result)){
                    array_push($l_institutes,$l_row_results);
                    }
                    ?>
                        <?php if($l_URPR_Type=='G'|| $l_URPR_Type=='S' ||$l_URPR_Type=='A'){?>
                    <div class="control-group" id="">
                         <label class="control-label" for="Institutes">Select Your Institutes<span style="color:red">* <span style="color: #2196F3;">(Please select  others if your institute is not shown)</span></span></label>
                        
                         <select class=" form-control input-lg" onchange="InstituteChange()" id="user_it_id" name="user_it_id" style="overflow-y: auto;">
                                <option value="">------</option>
                                <?php foreach ($l_institutes as $l_institute  ){?>
                                <option id="institute_change_<?php echo $l_institute[0]; ?>"  value="<?php echo $l_institute[0]; ?>">
                                       <?php echo $l_institute[1] ?>
                                </option>
                                <?php } ?>  
                                 <option id="institute_change_0"  value="0">
                                       Others
                                </option>
                                 
                            </select>
                       
                    </div>
                        <?php if($l_URPR_Type=='G'|| $l_URPR_Type=='S'){?>
                    <div class="control-group"  id="user_program">
                        <label class="control-label" for="Programs">Select Your Stream<span style="color:red">* <span style="color: #2196F3;">(Please select  others if your stream is not shown)</span></span></label>
                        <select  id="user_PG_id" class=" form-control input-lg"  onchange="ProgramChange()" name="user_PG_id"  >
                              <option value="" > ------ </option>
                              
                        </select>
                    </div>
                        <?php } }?>
                        <?php
                    } 
                   
                    if($l_URPR_Type=='M')
                    {  
                         $l_companys=array();
                    $l_sql1 ='Select UR.UR_id, UR.UR_FirstName,UR.UR_MiddleName,UR.UR_LastName from Users as UR where UR.UR_Type ="C" and UR.UR_RegistrationStatus="C"';
                        $l_result1 =mysql_query($l_sql1);
                        while($l_row_results1=mysql_fetch_row($l_result1)){
                            array_push($l_companys,$l_row_results1);
                        }
                    
                    ?>
                    <div class="control-group" >
                         <label class="control-label" for="CompanyName">Select Your Company Name<span style="color:red">*</span></label>

                            <select class="form-control input-lg" id="user_company_id" name="user_company_id" >
                                <option value="">----</option>
                                <?php foreach ($l_companys as $l_company  ){?>
                                
                                <option value="<?php echo $l_company[0]; ?>">
                                       <?php echo $l_company[1]." ".$l_company[2]." ".$l_company[3]; ?>
                                </option>>
                                <?php } ?>   
                            </select>

                    </div>
                    <?php } if($l_URPR_Type=='M'){?>
                        
                        <div class="control-group" id="">
                            <label class="control-label" for="CompanyInfo"> Profile Information<span style="color:red">*</span></label>
                         <textarea class="form-control" rows="5" id="profile_info" name="user_company_info"id="comment"></textarea>
                         <p class="help-block"></p>
                        </div>
                    <?php } ?>
                    <?php if($l_URPR_Type=='M' || $l_URPR_Type=='G'){?>
                      <div class="row">
        <ul class="col-md-12 " style=" list-style-type:none;">
        <?php 
            $subdomainsql= 'SELECT SD_id, SD_Name FROM SubDomain';
                        $subdomainresult = mysql_query($subdomainsql);

                while($subdomainrows = mysql_fetch_row($subdomainresult))
                {
                        $l_SD_id       = $subdomainrows[0];
                        $l_SD_Name= $subdomainrows[1];
 			print('<li>'.$l_SD_Name.'<input  style="float: left;margin-right: 10px;" type="checkbox" class="g_checkbox_select_DM" value="'.$l_SD_id.'" name="l_SD_sel[]"></li>');
                }

?>
    </ul></div>
    <?php } ?>
    
                    <div class="control-group">
                    <!-- Button -->
                    <div class="controls" style="margin-top:10px;">
                        <!--<a class="btn btn-primary" href="login.php">Back</a>-->
                        <!-- <button   class="btn btn-primary" onclick="window.location.href ='login.php'"  >Back</button>-->
                        <?php  if($l_URPR_Type=='S'||$l_URPR_Type=='G' ||$l_URPR_Type=='A' ||$l_URPR_Type=='C' || $l_URPR_Type=='M'){ ?>
                        <input type="submit" onclick="return Validation()" style="float:right" name="user_submit" value="Create an Account" id="create_an_acc" class="btn btn-success ">
                        <?php } ?>
                           
                        </div>
                       
                    </div>
                    </form>
                </div> 
              </div> 
          </div>
       </div>
        <div class="col-md-3 ">  </div>
        </div>
      
    <div id="loading">
  <img id="loading-image" src="https://www.zaireprojects.com/test/assets/ajax-loader.gif" alt="Loading..." />
</div>
</div>
<?php include('footer.php'); ?>
<script>

function  Usertype(){
$('#loading').show();
    var str = $('#user_type').val();
    
    if(str == ""){
     window.location="https://www.zaireprojects.com/test/TCreateUser.php";
        
    } 
    else{
    
   if( window.location="https://www.zaireprojects.com/test/TCreateUser.php?q="+str){
   $('#loading').show();
    window.setTimeout(function() {
     $('#loading').hide();
     }, 15000);
   }
    
    }
    
}
function ProgramChange(){
 var Program_id = $('#user_PG_id').val();
 $('#loading').show();
 if(Program_id=='0'){ 
 $("#user_PG_id").parent().append('<br class="Other_program"><div class="Other_program" class="control-group"><div class="controls"><input type="text" id="Other_program" name="Other_program" placeholder="Choose Your Stream" class="form-control input-lg"><p class="help-block"></p></div></div>');
 $('#loading').hide();
 }
  else{
 $('.Other_program').remove();
  $('#loading').hide();
 }
}
function InstituteChange(){
 var institute_id = $('#user_it_id').val();
 $('#loading').show();
 if(institute_id=='0'){
 //$('#loading').show();
 $("#user_it_id").parent().append('<br class="Other_Institute"><div class="Other_Institute" class="control-group"><div class="controls"><input type="text" id="Other_Institute" name="Other_Institute" placeholder="Choose Your Institute" class="form-control input-lg"><p class="help-block"></p></div></div>');
  $('#loading').hide();
 }
 else{
 $('.Other_Institute').remove();
  $('#loading').hide();
 }
 
 var data2={user_institute_id:institute_id};
 $select = $('#user_PG_id');
    $.ajax({
        type: "GET",
         dataType: "json",
        data:data2,
        url: "signup_programs.php",
        async: false,
        success : function(data) {
           //alert(data);
    //clear the current content of the select
       $select.html('');
       //iterate over the data and append a select option
       $.each(data, function(key, val){
         $select.append('<option value="' + val[0] + '">' + val[1] + '</option>');
       });

       $("#user_PG_id option:last").after("<option value='0'>Others</option>");
       
       $('#loading').hide();
        }
     });
 
 }
$("#user_id").on('focusout',function(){
 data3=$("#user_id").val();
data3=data3.trim();
 if(data3 !=""){
 $.ajax({
        type: "GET",
         dataType: "json",
        data:{UserId:data3},
        url: "signup_idcheck.php",
        async: false,
        success : function(data) {
      if(data['q'] >= 1){      
      $("#user_id").val('');
      $("#user_id").css('border','1px solid red')
      $("#user_id").parent().find(".help-block").html('<span style="color:red"> Sorry !! User Id already exists<span>');
      }else{
      $("#user_id").css('border','1px solid green');
      $("#user_id").parent().find(".help-block").html(' ');
      }
        }
     });
     } else{
     $("#user_id").css('border','1px solid red');
     }
 });
  $("#user_email").on('focusout',function(){
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($("#user_email").val())){
 data4=$("#user_email").val();
 $.ajax({
        type: "GET",
         dataType: "json",
        data:{UserEmail:data4},
        url: "signup_idcheck.php",
        async: false,
        success : function(data) {
      if(data['q'] >= 1){
       
      $("#user_email").val('');
      $("#user_email").css('border','1px solid red')
      $("#user_email").parent().find(".help-block").html('<span style="color:red"> Sorry !! User Email already exists<span>');
      }else{
      $("#user_email").css('border','1px solid green');
      $("#user_email").parent().find(".help-block").html(' ');
      
      }
        }
     });
     }else{
     $("#user_email").css('border','1px solid red')
      $("#user_email").parent().find(".help-block").html('<span style="color:red"> Sorry !! Invalid Email <span>');
      
     }
 
 });
 
 $( "form" ).submit(function( event ) {
     flag=true;
     $userType=$("#user_type");
     $userName=$("#user_name");
     $userId=$("#user_id");
     $user_email=$("#user_email");
     $user_psw=$("#user_password");
     $user_cpsw=$("#user_password_confirm");
     $user_semester=$("#user_semester");
     $user_it_id=$("#user_it_id");
     $user_PG_id=$("#user_PG_id");
     $profile_info=$("#profile_info");
     $user_company_id=$("#user_company_id");
     $Other_Institute=$("#Other_Institute");
     $Other_program=$("#Other_program");
     
     $selector=$(".help-block");
     if($user_it_id.val() == '0'){
     if($Other_Institute.val() !="" && $Other_Institute.val().length < 10 ){
      $Other_Institute.css('border','1px solid red');
      
     flag=false; 
     }
     else{
     $Other_Institute.css('border','');
     }
     }
     if($user_PG_id.val() == '0'){
     if($Other_program.val() == "" ){
     //alert();
      $Other_program.css('border','1px solid red');
     flag=false; 
     }
      else{
     $Other_program.css('border','');
     }
     }
     if($userName.val() == "")
     {
          $userName.css('border','1px solid red');
          //$userName.parent().find($selector).html('<span style="color:red"> Please Try Again!!<span>');
         flag=false; 
     } else{
         $userName.css('border','')
         
         
     }
     if($userId.val() == ""){
         $userId.css('border','1px solid red')
         flag=false; 
     }
     else
     {
     $userId.css('border','');
     }
     if($user_email.val() == "")
     {
         $user_email.css('border','1px solid red')
         flag=false; 
     }
     else{
     if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($user_email.val())){   
       $user_email.css('borde','');
      }
     else{
   
         $user_email.css('border','1px solid red')
         flag=false; 
         }
         
     }
     re0 = /[0-9]/;
     re1 = /[a-z]/;
     re2 = /[A-Z]/;
      if($user_psw.val() == ""  ){
    $user_psw.parent().find(".help-block").html('<span style="color:red"> Passwords must contain at least 6 characters maximum 18 characters,including uppercase, lowercase letters and numbers<span>');
         $user_psw.css('border','1px solid red');
         flag=false; 
     }
     else{
         $user_psw.css('border','');
         $user_psw.parent().find(".help-block").html('');
     }
     if(($user_cpsw.val() != $user_psw.val()) || $user_cpsw.val() == ""){
         $user_cpsw.css('border','1px solid red');
         flag=false; 
     }
     else{
         $user_cpsw.css('border','');
     }
     if($user_semester.val() == ""){
         $user_semester.css('border','1px solid red');
          flag=false; 
     }
     else{
         $user_semester.css('border','');
     }
      if($user_it_id.val() == ""){
         $user_it_id.css('border','1px solid red');
         flag=false; 
     }
     else{
         $user_it_id.css('border','');
     }
      if($user_PG_id.val() == ""){
         $user_PG_id.css('border','1px solid red');
         flag=false; 
     }
     else{
         $user_PG_id.css('border','');
     }
       if($profile_info.val() == ""){
         $profile_info.css('border','1px solid red');
         flag=false; 
     }
     else{
         $profile_info.css('border','');
     }
      if($user_company_id.val() == ""){
         $user_company_id.css('border','1px solid red');
         flag=false; 
     }
     else{
         $user_company_id.css('border','');
     }
     

     if(flag == true){
         return true;
     }
     else{
         return false;
     }
 })

</script> 