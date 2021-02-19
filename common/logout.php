<?php
session_start(); //Start the current session
require_once 'common.php';
//echo $GLOBALS['rootpath']."/index.php";

session_destroy(); //Destroy it! So we are logged out now
header("location:index.php"); 
?>




