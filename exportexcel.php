<?php
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=download.xls");

include ('db_config.php');
$l_select_sql = 'Select UR.UR_id, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_Emailid, UR.UR_EmailidDomain,PG.PG_Name,IT.IT_Name from Users as UR, Institutes as IT, Programs as PG where UR.UR_Type = "S" and UR.UR_RegistrationStatus = "P" and UR.PG_id = PG.PG_id and UR.IT_id = IT.IT_id and UR.IT_id = IT.IT_id and UR.PG_id<>"" and UR.IT_id<>""' ;

$l_result = mysql_query($l_select_sql);
$l_UR_count = mysql_num_rows($l_result);
  $output='';  
    if( isset($_POST['getexcel']))
    {
 
   if($l_UR_count>0){
   
   $output.= '<table class="ady-table-content" border=1 style="width:100%">
        <tr style=" background: #337AB7;"> 
       <th>Student<br>Name</th>
       <th>Student<br>Email Id</th>
        <th>Institute<br>Name</th>
        <th>Programme<br>Name</th>       
       </tr>';
   
while ($l_row = mysql_fetch_row($l_result))
        {
 $l_studentName=$l_row[1].' '.$l_row[2].' '.$l_row[3];
 $l_PG_Name = $l_row[6];
$l_IT_Name = $l_row[7];

$output.='<tr>
<td>'.$l_studentName.'</td>
<td>'.$l_row[4].'@'.$l_row[5].'</td>
<td>'.$l_IT_Name.'</td>
<td>'.$l_PG_Name.'</td>
</tr>';            
        
     }
   $output.='</table>';

   echo $output;
   }
    
 }

?>