<?php
session_start();
$uid = $_SESSION['id'];
session_destroy();
require_once ('../includes/connect.php');
// redirect user to login page
$domain = $_SERVER['HTTP_HOST'];
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$currentDir = str_replace("/admin", "", $domain);
header("location:$protocol://$currentDir/logout.php");
?>