<?php
//error_reporting(E_ALL);
if(!session_id()) {
     @session_start();
}

$conn=  mysql_connect('localhost','zairepro_elearn','4Dyn0rtech!');
$db=mysql_select_db("zairepro_projectory_test",$conn);
if(!$db){
    echo "Db connection failed".mysql_error();
}
require_once('../sendmail.php');
?>
