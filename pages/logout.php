<?php 
session_start();
$_SESSION["user"] = NULL;
$_SESSION["password"] = NULL;
header("Location:../pages/index.php");
?>