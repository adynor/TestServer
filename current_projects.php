<?php
include ('db_config.php');
$result=mysql_query("select PR.PR_Name,PR.PR_Desc FROM Users as UR,Projects as PR Where PR.PR_id=UR.PR_id AND UR.Org_id='".$_SESSION['g_Org_id']."' AND UR.UR_id='".$_SESSION['g_UR_id']."' ");
        $projects=  mysql_fetch_array($result);
       
 $project=  array(
    'Project_name'=>$projects['PR_Name'],
     'Project_Des'=>htmlspecialchars_decode($projects['PR_Desc'])
 );
    echo  json_encode ($project);
?>