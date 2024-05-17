<?php
session_start();
$uid = $_SESSION['id'];
session_destroy();
require_once ('../includes/connect.php');
// Construct the logout URL without '/admin'
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$domain = $_SERVER['HTTP_HOST'];
$currentDir = $protocol . "://" . $domain;
echo "" . $currentDir . "";
// Redirect to the logout page
header('location: index.php');
exit;
?>