<form action="TView_NewUnRegisterS.php" method="POST">
<?php  include ('db_config.php'); 
 //print_r($_GET);
$l_institutes= array();
$l_sql      ='SELECT IT_id, IT_Name FROM  Institutes ORDER BY IT_Name';
$l_result =mysql_query($l_sql);
while($l_row_results=mysql_fetch_row($l_result)){
array_push($l_institutes,$l_row_results);
}
if($_GET['it'] == '0'){ ?>
            <div class="form-group">
              <label for="recipient-name" class="form-control-label"> Set Institute:</label>
              <?php ?>
              <select name="IT_name" id="user_it_id" class="form-control" onchange="InstituteChange()"{>
               <option value="" > ------ </option>
               <?php foreach ($l_institutes as $l_institute  ){?>
               
              
                                <option id="institute_change_<?php echo $l_institute[0]; ?>"  value="<?php echo $l_institute[0]; ?>">
                                       <?php echo $l_institute[1] ?>
                                </option>
                                <?php } ?>
              </select>
            </div>
<?php }

if($_GET['it'] == '0' && $_GET['pg'] == '0'){
    
    ?>
             <div class="control-group"  id="user_program">
                        <label class="control-label" for="Programs">Set Program<span style="color:red">*</span></label>
                        <select  id="user_PG_id" class=" form-control "  name="PG_name"  >
                              <option value="" > ------ </option>
                              
                        </select>
            </div>
<?php }
if($_GET['it'] == '1' && $_GET['pg'] == '0'){
$l_programs= array();
$l_sqlp      ='SELECT PG_id, PG_Name FROM  Programs  WHERE  PG_id <> 0 ORDER BY PG_Name';
$l_resultp =mysql_query($l_sqlp);
while($l_row_resultsp=mysql_fetch_row($l_resultp)){
array_push($l_programs,$l_row_resultsp);
}?>
            <div class="form-group">
              <label for="Programs" class="form-control-label"> Set Program:</label>
              <?php ?>
              <select  id="user_PG_id" class=" form-control "  name="PG_name"  >
               <option value="" > ------ </option>
               <?php foreach ($l_programs as $l_program ){?>
               
              
                                <option id=program_nstitute_change_<?php echo $l_program[0]; ?>"  value="<?php echo $l_program[0]; ?>">
                                       <?php echo $l_program[1] ?>
                                </option>
                                <?php } ?>
              </select>
            </div>

<?php } ?>
 <input type="hidden" name="userid"  value="<?php echo $_GET['user'];?>">
 <input type="submit" class="btn btn-primary pull-right" style="margin-top: 10px;" value='submit' name='Update'>
</form>
<script>
    function InstituteChange(){
var institute_id = $('#user_it_id').val();
if( institute_id==""){
$('#user_PG_id').html('');
$('#user_PG_id').append('<option value=""> -----</option>');
}
else{
 var data2={user_institute_id:institute_id};
$select = $('#user_PG_id');
    $.ajax({
        type: "GET",
        dataType: "json",
        data:data2,
        url: "signup_programs.php",
        async: false,
        success : function(data) {
          //  alert(data);
    //clear the current content of the select
       $select.html('');
       //iterate over the data and append a select option
       $.each(data, function(key, val){
         $select.append('<option value="' + val[0] + '">' + val[1] + '</option>');
       });
       $('#loading').hide();
        }
     });
     }

 }
    </script>