<?php
session_start();
$uid = $_SESSION['id'];
session_destroy();
require_once ('../includes/connect.php');
// Construct the logout URL without '/admin'
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$domain = $_SERVER['HTTP_HOST'];
$currentDir = $protocol . "://" . $domain . "/logout.php";
// Redirect to the logout page
header("Location: " . $currentDir);
exit;
?>