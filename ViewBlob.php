<?php
session_start();
include('db_config.php');
$l_PR_id=$_GET['PR_id']; 
 $query='SELECT PR_Synopsis_Data,PR_Synopsis_Name FROM Project_Synopsis where PR_id='.$l_PR_id.'';

$gotten = mysql_query($query);

$row =mysql_fetch_row($gotten);

$bytes = $row[0];

header("Content-type: application/pdf");

header('Content-disposition: attachment; filename="'.$row[1].'"');
echo $bytes;
	

?>