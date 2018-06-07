<?php 
include ('db_config.php');
  
$l_pg_id=$_GET['pgid'];
$l_pr_id=$_GET['prid']; 
$flag=$_GET['flag'];

if(isset($l_pg_id)&& isset($l_pr_id) && ($flag=='delete'))
{
    $l_programs_sql ='Delete from PG_Projects where PG_id ='.$l_pg_id.' and PR_id='.$l_pr_id.'';
    $l_programs_result =mysql_query($l_programs_sql);
    if($l_programs_result){echo "sucess";}
}
if(isset($l_pg_id)&& isset($l_pr_id) && ($flag=='insert'))
{
    $l_programs_sql ='INSERT INTO PG_Projects(PR_id,PG_id) VALUES ('.$l_pr_id.','.$l_pg_id.')';
    $l_programs_result =mysql_query($l_programs_sql);
    if($l_programs_result){
        echo "inserted";
       }
}
 
?>
