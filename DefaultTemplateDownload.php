<?php
require_once('db_config.php');

if(isset($_GET['ALid']))
    {
     $l_AL_id=$_GET['ALid']; 
     $query='SELECT AL_Template,AL_Desc,AL_Templatec_Size,AL_Template_Type FROM Access_Level
 where AL_id='.$l_AL_id.'';
 }
    else{      
   echo "<script>window.location.href='".$_SERVER['HTTP_REFERER']."'</script>";
        }
$gotten = mysql_query($query);
$row = mysql_fetch_row($gotten);
$row_name=$row[1].'.pdf';
header("Content-disposition: attachment; filename= ".$row_name."");
header("Cache-Control: no-cache");
echo $row[0];
?>