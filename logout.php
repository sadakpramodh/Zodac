<?php
require_once("configurations.php");
require_once("functions.php"); 
session_start();
session_unset();
$_SESSION["email_id"]=null; 
$_SESSION["username"]=null;
$_SESSION["user_id"]=null;
$_SESSION["message"]=null;
$_SESSION["type"]=null;
$_SESSION["siteinit"]=null;
$_SESSION["lockscreen_status"]=null;
$_SESSION["secure_id"]=null;
$_SESSION['token']=null;
redirect_to("index.php");
?>