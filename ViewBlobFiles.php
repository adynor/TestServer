<?php
include('db_config.php');
$l_PR_id=$_GET['prid']; 
 $query='SELECT PR_Synopsis_Data,PR_Synopsis_Name FROM Project_Synopsis where PR_id='.$l_PR_id.'';

$gotten = mysql_query($query);

$row =mysql_fetch_row($gotten);

 $file  = $row[0];
   //$file = 'Projectory/ProjectDocuments/Abstract2_1.pdf';
  $filename = 'Synopsis.pdf';
  file_put_contents($filename, $file);
  /*header('Content-type: application/pdf');
  header('Content-Disposition: inline; filename="' . $filename . '"');
  header('Content-Transfer-Encoding: binary') ));
  header('Accept-Ranges: bytes');
  @readfile($file);*/
  header('Content-type: application/pdf');
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-Disposition: inline;filename='".$filename."'");
header("Content-length: ".strlen($file));

echo $file;
  
  
?>