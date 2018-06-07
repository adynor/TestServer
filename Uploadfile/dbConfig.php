<?php
//DB details
$dbHost = 'localhost';
$dbUsername = 'zairepro_dbuser';
$dbPassword = '4dyn0rtech!';
$dbName = 'zairepro_Projectory';

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}

?>