<?php   

include ('db_config.php');
include ('header.php');
?>
<div class="container" >
       <div class="row" style="width:100%;">
           <div class=" ady-row">



<?php

$l_UR_id                = $_SESSION['g_UR_id'];
$l_UR_Type                 = $_SESSION['g_UR_Type']; 
if(is_null($l_UR_id) || $l_UR_Type!='T')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as the admin. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login"; </script> ';

        print($l_alert_statement );
}

    if(isset ($_POST['Update']))
    {
    $data=$_POST;
    if($data['IT_name'] ==""  && $data['PG_name'] ==""){
   
       }else if($data['IT_name'] !=""  && $data['PG_name'] !=""){
           $sql_update=mysql_query('UPDATE Users SET IT_id='.$data['IT_name'].',PG_id='.$data['PG_name'].', UR_ProfileInfo=NULL WHERE UR_id="'.$data['userid'].'" AND Org_id="ZAP"');
      if($sql_update)
       echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'"</script>';
    }
       else if ($data['IT_name'] !="") {
              $sql_update=mysql_query('UPDATE Users SET IT_id='.$data['IT_name'].', UR_ProfileInfo=NULL WHERE UR_id="'.$data['userid'].'" AND Org_id="ZAP"');
      if($sql_update)
       echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'"</script>';
        
    } else if ($data['PG_name'] !="") {
           $sql_update=mysql_query('UPDATE Users SET PG_id='.$data['PG_name'].', UR_ProfileInfo=NULL WHERE UR_id="'.$data['userid'].'" AND Org_id="ZAP"');
      if($sql_update)
       echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'"</script>';
        
    }  
    }
    


      $l_select_sql = 'Select UR.UR_id, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_Emailid, UR.UR_EmailidDomain,UR.UR_ProfileInfo,IT.IT_Name,PG.PG_Name, UR.UR_Type      
        from Users as UR
        LEFT JOIN  Institutes as IT ON UR.IT_id=IT.IT_id  
         LEFT JOIN Programs as PG ON PG.PG_id=UR.PG_id 
         where  (UR.UR_Type = "S" OR UR.UR_Type = "G") AND  (UR.IT_id is null OR UR.PG_id is null)' ;

$l_result = mysql_query($l_select_sql);
$l_UR_count = mysql_num_rows($l_result);
        
?>




<table class="ady-table-content" border=1 style="width:100%">
	<tr>
		<th>User<br>Name</th>
		<th>User<br>Email Id</th>
		<th>User<br>Type</th>
		<th>Institute<br>Name</th>
		<th>Programme<br>Name</th>        
		<th>Update</th>
	</tr>
        <?php
        if($l_UR_count == 0)
        {
            ?>
            <tr>
           <td colspan=6>There are no new Students</td>
           <?php
        }
        else
        {
        while ($l_row = mysql_fetch_row($l_result))
        {
//            echo "<pre>";
//            print_r($l_row);
//            echo "</pre>";
$l_studentName=$l_row[1].' '.$l_row[2].' '.$l_row[3];
$l_userprofile=explode('||',$l_row[6]);

if($l_row[7] == NULL){
 $l_IT_Name = $l_userprofile[0];
  $it=0;
}else{
    $l_IT_Name  = $l_row[7];
//    echo $l_row[7];
   $it=1;
}
if($l_row[8] == NULL){
$l_PG_Name = $l_userprofile[1];
   $pg=0;
}else{
   $l_PG_Name = $l_row[8];
   $pg=1;
}


        ?>  
	<tr>
		<td> <?php echo $l_studentName ;?></td>
		<td><?php echo $l_row[4].'@'.$l_row[5] ?></td>
		<td><?php echo $l_row[9];?></td>
		<td><?php echo $l_IT_Name; ?></td>
		<td> <?php echo $l_PG_Name ;?></td>
		<td><a class="btn btn-primary miButton newmodal" data-url="TUpdate_Unregister.php?pg=<?php echo $pg; ?>&&it=<?php echo $it; ?>&&user=<?php echo $l_row[0]; ?>" datadata="<?php echo $l_row[0]; ?>" data-toggle="modal" id="" data-target="#exampleModa" data-whatever="@getbootstrap">edit</a>
		</td>
	</tr>            
       <?php   } ?>
	
      <?php  } ?>

	</table>   
	

		</div>
	</div>
	
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel">Update Institute & Programs</h4>
        </div>
         <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="modal-body">
           

          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <input type="submit" class="btn btn-primary" name="Update" value="Update" onclick="return updateuser()">
        </div>
        </form>
      </div>
    </div>
  </div>

<?php include('footer.php')?>
<div id="unregisterbg" style="height: 100%;width: 100%;position: fixed;top:0px;right: 0px;left: 0px;bottom: 0px;    background: rgba(0, 0, 0, 0.42);display: none">
    <div id="unregister" style="position: fixed;top:20%;height: auto;left:40%;width:300px;background: #E0E0E0;padding:10px;display: none" ></div>
</div>

<script>
$(function(){
 //when click a button
  $(".miButton").click(function(){

  var str= $(this).attr('datadata');
   // alert(str);
    //pass the data in the modal body adding html elements
    $("input[name='userid']").val('')
    $("input[name='userid']").val(str) ;
    //open the modal
    
  })
})
function myclose(){
    $("#unregisterbg").hide();
     $("#unregister").hide();
     $("#unregister").html('');
}

$(".newmodal").on('click',function(){
  url=$(this).data('url');
   $.ajax({
        type: "GET",
       
        url: url,
        async: false,
        success : function(data) {
            
            $("#unregisterbg").show();
            $("#unregister").show();
            $("#unregister").html('<button class="close-span" onclick="myclose();" style="position: absolute; right: 0px;border: 0px solid;top: 0px;border-radius: 50%;">x</button>')
            $("#unregister").append(data);
        }
     });
     

});
 function updateuser(){
 var institute_id = $('#user_it_id').val();
 if(institute_id !=""){
 if(confirm("Are you sure you want to update ")) {
    return true;
} else {
    return false;
}}
else {
    return false;
}
 }
 

</script>