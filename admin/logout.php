<?php
session_start();
//$uid = $_SESSION['id'];
session_destroy();
require_once ('../includes/connect.php');

header('location: login.php');

exit;
?>