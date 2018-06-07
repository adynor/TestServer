<?php
session_start();
//unset($_SESSION['']);
unset($_SESSION['g_conid']);
 unset($_SESSION['g_convnodeid']);
 unset($_SESSION['d_turn_count']);
 unset($_SESSION['d_request_count']);
header("location:chat.php");

?>