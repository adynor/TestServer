<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

<?php
 include ('header.php');
include ('db_config.php');  
 ?>
<style>

.ady-cus-th
{
        text-align: left !important;
        padding-left:20px !important;
        width:50%
    }
.backbutton{
    border: 2px solid;
    padding: 9px 26px;
    border-radius: 5px;
    margin-bottom: 0px;
    margin-left: 1px;
}
.backbutton:hover
{
background:rgba(19, 121, 150, 0.98);
border:none;
color:#FFFFFF !important;
}
th,td
{
    border:1px solid #FFFFFF;
}
</style>
<div class="row" style="padding:20px"></div>
<div class="row" style="padding:20px"></div>
<div class="container" >
<?php
 $l_UR_id      = $_SESSION['g_UR_id']; //User Id 
 $l_UR_Type     = $_SESSION['g_UR_Type'];//User Type
 //check the user id is empty  and user type not PA(Project Admin)
if(is_null($l_UR_id) || $l_UR_Type!='T')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as the Adynor Project Admin. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login.php"; </script> ';

        //print($l_alert_statement );
}
if(!isset($_REQUEST['PID']) || is_null($_REQUEST['PID']) || empty($_REQUEST['PID'])){
  $l_alert_statement1 =  ' <script type="text/javascript">
        window.location.href="'.$l_filehomepath.'/PAHome.php"; </script> ';
        //print($l_alert_statement1);
}
////Get The Project id from URL
 if($_REQUEST['PID']!="")
 {
 $pid=$_REQUEST['PID'];
 ////decode the project id 
  $pid_encode=base64_decode(str_replace(md5(162).'==','@@',$pid));
 }
 else if($_REQUEST['PR_id']!="")
 {
     $pid_encode=$_REQUEST['PR_id']; 
 }
 
if(isset($_POST['p_model'])){
 $p_model=$_POST['p_model'];
 //// Set The Project Model
 $l_update_query=mysql_query('UPDATE Projects SET MO_id='.$p_model.' WHERE PR_id='.$pid_encode.'');
 //// Display The Sucess Notification
if($l_update_query){
print('<div class="alert alert-success"><h4 class="text-aling:center"> Project Model Changed Successfully</h4></div>');
}
 }
 
 // Display The Back Button
if($l_UR_Type=='PA')
    {
 print('<a class="backbutton" href="PAProjectList.php">Back</a>');
}
else if($l_UR_Type=='T'){
    print('<a class="backbutton" href="'.$l_filehomepath.'/TView_Projects01.php">Back</a>');

}
//select the Details of Projects and Owner Name
$query='SELECT PR.PR_Name,PR.PR_Desc,PR.PR_ReleaseDate,PR.PR_ExpiryDate,PR.PR_Duration,PR.PR_SynopsisURL,PR.MO_id ,UR.UR_FirstName,UR.UR_LastName,PR.PR_Status FROM Projects as PR , Users as UR WHERE UR.UR_id = PR.UR_Owner AND PR.PR_id='.$pid_encode.'';
$l_Pro_Details_Query=mysql_query($query);
$l_Pro_Details=mysql_fetch_array($l_Pro_Details_Query);
$l_SD_sql = 'select SD.SD_id, SD.SD_Name from SubDomain as SD, Project_SubDomains as PS where PS.SD_id = SD.SD_id and PS.PR_id = '.$pid_encode;
$l_SD_res = mysql_query($l_SD_sql);

$l_PG_sql = 'select PG.PG_id, PG.PG_Name from Programs as PG, PG_Projects as PGP where PG.PG_id = PGP.PG_id and PGP.PR_id = '.$pid_encode;
$l_PG_res = mysql_query($l_PG_sql);
$l_PG_checked=array();
while($l_PG_row=  mysql_fetch_array($l_PG_res)){
    array_push($l_PG_checked, $l_PG_row['PG_id']);
}

$l_all_pg='select PG_id,PG_Name from Programs';
$l_all_pg_res=mysql_query($l_all_pg);
//convet time to date Time Format
$l_Pro_ReleaseDate= date("d-M-Y", strtotime($l_Pro_Details[2]));
$l_Pro_ExpiryDate= date("d-M-Y ", strtotime($l_Pro_Details[3]));
//Set the synopsis link at session for PDfview  page
 $_SESSION['g_pdf_view']=$l_Pro_Details[5];
//Display the Details of the Projects

print('<table class="ady-table-content" border=1 style="width:100%" >');
print('<tr><th colspan="2" style="text-align:center;">Project Details</th></tr>');
print('<tr><th class="ady-cus-th" >Project Name</th><td>'.$l_Pro_Details[0].'</td></tr>');
print('<tr><th class="ady-cus-th" > Project Description</th><td>'.htmlspecialchars_decode($l_Pro_Details[1]).'</td></tr>');
print('<tr><th class="ady-cus-th" >Project Synopsis</th><td><a class="btn btn-primary" role="button" href="'.$l_filehomepath.'/ViewSynopsis.php?prid='.$pid_encode.'">View Synopsis</a></td></tr>');
print('<tr><th class="ady-cus-th" >Project ReleaseDate</th><td>'.$l_Pro_ReleaseDate.'</td></tr>');
print('<tr><th class="ady-cus-th" >Project ExpiryDate</th><td>'.$l_Pro_ExpiryDate.'</td></tr>');
print('<tr><th class="ady-cus-th" >Project Duration</th><td>'.$l_Pro_Details[4].' days</td></tr>');
print('<tr><th class="ady-cus-th" >Project Owner</th><td>'.$l_Pro_Details[7].' '.$l_Pro_Details[8].'</td></tr>');
print('<tr><th class="ady-cus-th" >Project Model</th><td>');
//select all the models for display as dropdown

$l_model_name_query=mysql_query('SELECT MO_id,MO_Name FROM Model');

print('<form action=" " method="POST">');
?>
    <div class="row">
        <div class="col-md-8">
 <?php
print('<select class="form-control" name="p_model" id="pmodel">');
while($l_model_name_list=mysql_fetch_row($l_model_name_query)){
//check for selected model
    if($l_model_name_list[0]== $l_Pro_Details[6]){

print('<option value="'.$l_model_name_list[0].'" selected >'.$l_model_name_list[1].'</option>');
}else{
    print('<option value="'.$l_model_name_list[0].'">'.$l_model_name_list[1].'</option>');
}}
print('</select>');

?>
            </div>
        <div class="col-lg-4">
 <?php
print('<input type="submit" class="btn btn-primary" name="save-model" id="save-model" value="Update"></form>');
?>
        </div>
    </div>
<?php
print('</td></tr>');
//nav start
print('<tr><th class="ady-cus-th" >Technologies Used</th>');
print('<td ><ul style="list-style-type:square">');
while($l_SD_row = mysql_fetch_row($l_SD_res))
        {
            
            $l_SD_id      = $l_SD_row[0];
            $l_SD_Name    = $l_SD_row[1];

               print('<li>'.$l_SD_Name.'</li>');           
        }
        print('</td></tr></ul>');

print('<tr><th class="ady-cus-th">Project Streams</th>');
print('<td><ul style="list-style-type:square">');
while($l_ALL_PG_row = mysql_fetch_row($l_all_pg_res))
        {
          $l_ALL_PG_id    = $l_ALL_PG_row[0];
          $l_ALL_PG_Name    = $l_ALL_PG_row[1];
          
if(in_array($l_ALL_PG_id,$l_PG_checked))
 {
               print('<li>'.$l_ALL_PG_Name.'<input type="checkbox" class="checkprogramme" value="'.$l_ALL_PG_id.'"  id="'.$l_ALL_PG_id.'" name="l_PG_id" value="'.$l_ALL_PG_id.'" checked><span id="help_block_'.$l_ALL_PG_id.'"></span></li><br/>');
}else{
    print('<li>'.$l_ALL_PG_Name.'<input type="checkbox" class="checkprogramme" value="'.$l_ALL_PG_id.'" id="'.$l_ALL_PG_id.'" name="l_PG_id" value="'.$l_ALL_PG_id.'" ><span id="help_block_'.$l_ALL_PG_id.'"></span></li><br/>');
    }

      }
      print('</td></tr></ul>');


print('<tr><th class="ady-cus-th" >Project Process</th>');

$sql_count='select max(PS.PS_Seq_No) from ProjectDocument_Sequence as PS where PS.PR_id='.$pid_encode;

$l_Max_PS_id=mysql_fetch_row(mysql_query($sql_count));

print('<td><ul style="list-style-type:square">');
for($inc = 1; $inc <= $l_Max_PS_id[0]; $inc++)
            {
                // get Access level names in the list according to sequence number
                $l_Seq_sql = 'select PS.AL_id, AL.AL_Desc from ProjectDocument_Sequence as PS,Access_Level as AL where PS.PR_id='.$pid_encode.' and PS.PS_Seq_No='.$inc.' and PS.AL_id=AL.AL_id';
                $l_res = mysql_query($l_Seq_sql) or die(mysql_error());
                
                if($l_data = mysql_fetch_row($l_res))
                {
                    print ('<li>'.$l_data[1].'</li>' );  // showing acces level list on the basis of sequence
                }
            }
 print('</td></tr></ul>');

//nav ends
?>
    <tr><th class="ady-cus-th" >Project Status</th><td>
        <?php 
        if($l_Pro_Details['PR_Status']=="C")
            {
            echo "<span id='confirm'>Confirm </span>";
            } 
            else if($l_Pro_Details['PR_Status']=="P")
                {
               echo "<span id='Pending'>Pending </span>";
                } ?>
     </td></tr>
<?php
print('<tr><th colspan="2" style="text-align:center;padding:10px"></th></tr>');
print('</table>');
?>
</div>
<div id="dialog" title="Confirmation Required">
  Are you sure about this?
</div>


<script>

    $("#confirm").css("color", "Green");  
    $( "#confirm" ).css( "fontSize", "22px" ); 
  
 
    $("#Pending").css("color", "red");
    $( "#Pending" ).css( "fontSize", "22px" ); 
</script>

<!-- naveen go for jquery -->
<script type="text/javascript">
 $(document).ready(function() {


$(".checkprogramme").click(function(){
      
      var sendpgid=$(this).attr('id');
      $helpspan=$("#help_block_"+sendpgid);
      var sendprid= <?php echo $pid_encode ?>;
     
      
      if(this.checked){
          var flagdata="insert";
         $.ajax({
                url: "Updelete.php",
                type: "GET",
                
                data: {pgid:sendpgid,prid:sendprid,flag:flagdata},
                success: function(data) {
                  $helpspan.html('<p style="color: green !important;"><span style="color: green !important;" class="glyphicon glyphicon-plus">Added</span></p>');
                }
                
                
            });

            }
            else{
            var flagdata="delete";
            $.ajax({
                type: "GET",
               url: 'Updelete.php',
               
               data: {pgid:sendpgid,prid:sendprid,flag:flagdata},
                success: function(data) {
                   
                    $helpspan.html('<p style="color: #FF5722 !important;"><span style="color: #FF5722 !important;" class="glyphicon glyphicon-trash">Removed</span></p>');
                }
            });
            
            }
   
     });
});
</script>

<?php include('footer.php')?>